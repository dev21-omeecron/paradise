<?php
require_once("../dbcon.php");

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch all rooms initially
try {
  $sql = "SELECT room_id, room_type, room_number, price, status, description, images FROM rooms";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo json_encode([
    "status" => "error",
    "message" => "Error fetching room data: " . $e->getMessage()
  ]);
  exit;
}

// Handle room deletion
if (isset($_POST['delete_room'])) {
  $room_id = $_POST['room_id'];

  try {
    $sql = "SELECT images FROM rooms WHERE room_id = :room_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':room_id', $room_id);
    $stmt->execute();
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($room['images'])) {
      $images = json_decode($room['images'], true);
      foreach ($images as $image) {
        $filePath = "../uploads/rooms/" . $image;
        if (file_exists($filePath)) {
          unlink($filePath);
        }
      }
    }

    $sql = "DELETE FROM rooms WHERE room_id = :room_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':room_id', $room_id);

    if ($stmt->execute()) {
      echo json_encode([
        "status" => "success",
        "message" => "Room deleted successfully!"
      ]);
    } else {
      echo json_encode([
        "status" => "error",
        "message" => "Error deleting room. Please try again."
      ]);
    }
  } catch (PDOException $e) {
    echo json_encode([
      "status" => "error",
      "message" => "Error deleting room: " . $e->getMessage()
    ]);
  }
  exit;
}

// Handle advanced search
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filters'])) {
  $filters = json_decode($_POST['filters'], true);

  try {
    $sql = "SELECT * FROM rooms WHERE 1=1";
    $params = [];

    foreach ($filters as $filter) {
      $field = $filter['field'];
      $value = $filter['value'];

      switch ($field) {
        case 'room_id':
          $sql .= " AND room_id = :room_id";
          $params[':room_id'] = $value;
          break;
        case 'room_type':
          $sql .= " AND room_type = :room_type";
          $params[':room_type'] = $value;
          break;
        case 'room_number':
          $sql .= " AND room_number = :room_number";
          $params[':room_number'] = $value;
          break;
        case 'price':
          $sql .= " AND price = :price";
          $params[':price'] = $value;
          break;
        case 'status':
          $sql .= " AND status = :status";
          $params[':status'] = $value;
          break;
        case 'description':
          $sql .= " AND description LIKE :description";
          $params[':description'] = "%$value%";
          break;
      }
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format data for DataTables
    $formattedRooms = [];
    foreach ($rooms as $room) {
      $formattedRooms[] = [
        $room['room_id'],
        $room['room_type'],
        '<a href="edit_room.php?room_number=' . htmlspecialchars($room['room_number']) . '">' . htmlspecialchars($room['room_number']) . '</a>',
        $room['price'],
        $room['status'],
        $room['description'],
        '<form action="room_list.php" method="POST" style="display:inline;">
          <input type="hidden" name="room_id" value="' . $room['room_id'] . '">
          <button type="submit" name="delete_room" class="btn btn-danger delete-room" data-room-id="' . $room['room_id'] . '">Delete</button>
        </form>'
      ];
    }

    echo json_encode([
      "status" => "success",
      "data" => $formattedRooms
    ]);
    exit;
  } catch (PDOException $e) {
    echo json_encode([
      "status" => "error",
      "message" => "Error searching rooms: " . $e->getMessage()
    ]);
    exit;
  }
}

// Handle reset functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
  try {
    $sql = "SELECT * FROM rooms ORDER BY room_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format data for DataTables
    $formattedRooms = [];
    foreach ($rooms as $room) {
      $formattedRooms[] = [
        $room['room_id'],
        $room['room_type'],
        '<a href="edit_room.php?room_number=' . htmlspecialchars($room['room_number']) . '">' . htmlspecialchars($room['room_number']) . '</a>',
        $room['price'],
        $room['status'],
        $room['description'],
        '<form action="room_list.php" method="POST" style="display:inline;">
          <input type="hidden" name="room_id" value="' . $room['room_id'] . '">
          <button type="submit" name="delete_room" class="btn btn-danger delete-room" data-room-id="' . $room['room_id'] . '">Delete</button>
        </form>'
      ];
    }

    echo json_encode([
      "status" => "success",
      "data" => $formattedRooms
    ]);
    exit;
  } catch (PDOException $e) {
    echo json_encode([
      "status" => "error",
      "message" => "Error fetching rooms: " . $e->getMessage()
    ]);
    exit;
  }
}
