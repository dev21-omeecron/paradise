<?php
require_once("../dbcon.php");

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Get available rooms for selected room type
  if (isset($_GET['action']) && $_GET['action'] === 'get_rooms' && isset($_GET['room_type'])) {
    try {
      $sql = "SELECT DISTINCT room_number FROM rooms WHERE room_type = :room_type ORDER BY room_number";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':room_type', $_GET['room_type'], PDO::PARAM_STR);
      $stmt->execute();
      $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($rooms) {
        echo json_encode(['status' => 'success', 'rooms' => $rooms]);
      } else {
        echo json_encode(['status' => 'error', 'message' => 'No rooms found for this type']);
      }
      exit;
    } catch (PDOException $e) {
      echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
      exit;
    }
  }

  // Get booking details for selected room
  if (isset($_GET['action']) && $_GET['action'] === 'get_bookings' && isset($_GET['room_type']) && isset($_GET['room_number'])) {
    try {
      $sql = "SELECT rb.*, r.images
                    FROM room_bookings rb
                    JOIN rooms r ON rb.room_number = r.room_number
                    WHERE rb.room_type = :room_type 
                    AND rb.room_number = :room_number
                    ORDER BY rb.booking_id DESC";

      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':room_type', $_GET['room_type'], PDO::PARAM_STR);
      $stmt->bindParam(':room_number', $_GET['room_number'], PDO::PARAM_STR);
      $stmt->execute();

      $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($bookings) {
        echo json_encode(['status' => 'booked', 'data' => $bookings]);
      } else {
        echo json_encode(['status' => 'available', 'message' => 'No bookings found for this room']);
      }
      exit;
    } catch (PDOException $e) {
      echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
      exit;
    }
  }
}

// Handle booking deletion
if (isset($_GET['delete'])) {
  try {
    $sql = "DELETE FROM room_bookings WHERE booking_id = :booking_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':booking_id', $_GET['delete'], PDO::PARAM_INT);
    $stmt->execute();

    header("Location: roombooking_details.php");
    exit;
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
  }
}

// Default query for initial page load
try {
  $sql = "SELECT rb.*, r.images
            FROM room_bookings rb
            JOIN rooms r ON rb.room_number = r.room_number
            ORDER BY rb.booking_id DESC";

  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $room_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
  exit;
}
