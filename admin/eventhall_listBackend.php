<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

$admin_id = $_SESSION['admin_id'];

try {
  $sql = "SELECT hall_id, hall_type, hall_number, capacity, price_per_hour, status, description, images FROM event_halls";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $eventhalls = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}

// Handle event hall deletion
if (isset($_POST['delete_eventhall'])) {
  $hall_id = $_POST['hall_id'];

  try {
    // Fetch the images related to the event hall
    $sql = "SELECT images FROM event_halls WHERE hall_id = :hall_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':hall_id', $hall_id);
    $stmt->execute();
    $eventhall = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($eventhall) {
      $images = json_decode($eventhall['images']);

      // Delete the images from the server
      if (is_array($images)) {
        foreach ($images as $image) {
          $imagePath = "../uploads/halls/" . $image;
          if (file_exists($imagePath)) {
            unlink($imagePath);
          }
        }
      }
    }

    $sql = "DELETE FROM event_halls WHERE hall_id = :hall_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':hall_id', $hall_id);
    $stmt->execute();

    echo json_encode([
      "status" => "success",
      "message" => "Event Hall deleted successfully"
    ]);
    exit();
  } catch (PDOException $e) {
    echo json_encode([
      "status" => "error",
      "message" => "Error deleting event hall: " . $e->getMessage()
    ]);
    exit();
  }
}

try {
  if (isset($_POST['reset'])) {
    // Return all event halls if reset is requested
    $sql = "SELECT * FROM event_halls ORDER BY hall_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $event_halls = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
      "status" => "success",
      "event_halls" => $event_halls
    ]);
    exit();
  } elseif (!empty($_POST)) {
    $sql = "SELECT * FROM event_halls WHERE 1=1";
    $params = [];

    if (!empty($_POST['hall_type'])) {
      $sql .= " AND hall_type = :hall_type";
      $params[':hall_type'] = $_POST['hall_type'];
    }
    if (!empty($_POST['hall_number'])) {
      $sql .= " AND hall_number = :hall_number";
      $params[':hall_number'] = $_POST['hall_number'];
    }
    if (!empty($_POST['status'])) {
      $sql .= " AND status = :status";
      $params[':status'] = $_POST['status'];
    }
    if (!empty($_POST['capacity'])) {
      $sql .= " AND capacity = :capacity";
      $params[':capacity'] = $_POST['capacity'];
    }
    if (!empty($_POST['price_per_hour'])) {
      $sql .= " AND price_per_hour = :price_per_hour";
      $params[':price_per_hour'] = $_POST['price_per_hour'];
    }

    $stmt = $conn->prepare($sql);
    foreach ($params as $key => &$value) {
      $stmt->bindParam($key, $value);
    }
    $stmt->execute();
    $event_halls = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
      "status" => "success",
      "event_halls" => $event_halls
    ]);
    exit();
  }
} catch (PDOException $e) {
  echo json_encode([
    "status" => "error",
    "message" => "Error fetching event halls: " . $e->getMessage()
  ]);
  exit();
}
