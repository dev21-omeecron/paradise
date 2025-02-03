<?php
require_once("../dbcon.php");

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

if (isset($_POST['room_type'])) {
  try {
    $room_type = $_POST['room_type'];

    $sql = "SELECT room_number FROM rooms WHERE room_type = :room_type ORDER BY room_number";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':room_type', $room_type);
    $stmt->execute();

    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rooms);
  } catch (PDOException $e) {
    echo json_encode([
      "status" => "error",
      "message" => "Error fetching room numbers: " . $e->getMessage()
    ]);
  }
}
