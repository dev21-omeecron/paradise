<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Get available halls for selected hall type
  if (isset($_GET['action']) && $_GET['action'] === 'get_halls' && isset($_GET['hall_type'])) {
    try {
      $sql = "SELECT DISTINCT hall_number FROM event_halls WHERE hall_type = :hall_type ORDER BY hall_number";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':hall_type', $_GET['hall_type'], PDO::PARAM_STR);
      $stmt->execute();
      $halls = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($halls) {
        echo json_encode(['status' => 'success', 'halls' => $halls]);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'No halls found for this type']);
      }
      exit;
    } catch (PDOException $e) {
      echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
      exit();
    }
  }

  // Get booking details for selected hall
  if (isset($_GET['action']) && $_GET['action'] === 'get_bookings' && isset($_GET['hall_type']) && isset($_GET['hall_number'])) {
    try {
      $sql = "SELECT hb.*, e.images
                    FROM hall_bookings hb
                    JOIN event_halls e ON hb.hall_number = e.hall_number
                    WHERE hb.hall_type = :hall_type AND hb.hall_number = :hall_number ORDER BY hb.booking_id DESC";

      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':hall_type', $_GET['hall_type'], PDO::PARAM_STR);
      $stmt->bindParam(':hall_number', $_GET['hall_number'], PDO::PARAM_STR);
      $stmt->execute();

      $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($bookings) {
        echo json_encode(['status' => 'booked', 'data' => $bookings]);
      } else {
        echo json_encode(['status' => 'available', 'message' => 'No bookings found for this hall']);
      }
      exit();
    } catch (PDOException $e) {
      echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
      exit();
    }
  }
}

// Handle booking deletion
if (isset($_GET['delete'])) {
  try {
    $sql = "DELETE FROM hall_bookings WHERE booking_id = :booking_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':booking_id', $_GET['delete'], PDO::PARAM_INT);
    $stmt->execute();

    header("Location: hallbooking_details.php");
    exit();
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
  }
}

// Default query for initial page load
try {
  $sql = "SELECT hb.*, e.images
            FROM hall_bookings hb
            JOIN event_halls e ON hb.hall_number = e.hall_number
            ORDER BY hb.booking_id DESC";

  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $hall_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
  exit;
}
