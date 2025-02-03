<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (
    isset($_POST['room_type'], $_POST['room_number'], $_POST['price'], $_POST['status'], $_POST['description'])
    && isset($_FILES['images'])
  ) {
    $room_type = $_POST['room_type'];
    $room_number = $_POST['room_number'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $description = $_POST['description'];
    $uploaded_images = [];

    $upload_dir = "../uploads/rooms/";
    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }

    // File size and type validation
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
      $file_name = basename($_FILES['images']['name'][$key]);
      $target_file = $upload_dir . $file_name;
      $file_size = $_FILES['images']['size'][$key];
      $file_type = $_FILES['images']['type'][$key];

      if ($file_size > 25000000) {
        echo json_encode([
          "status" => "error",
          "message" => "File size exceeds 25MB limit"
        ]);
        exit;
      }

      $allowed_types = ['images/jpg', 'image/jpeg', 'image/png'];
      if (!in_array($file_type, $allowed_types)) {
        echo json_encode([
          "status" => "error",
          "message" => "Invalid file type. Only JPG, JPEG, and PNG are allowed."
        ]);
        exit;
      }

      // Move uploaded file to target directory
      if (move_uploaded_file($tmp_name, $target_file)) {
        $uploaded_images[] = $file_name;
      } else {
        echo json_encode([
          "status" => "error",
          "message" => "Failed to upload file: " . $file_name
        ]);
        exit;
      }
    }

    $images_json = json_encode($uploaded_images);

    try {
      $sql = "INSERT INTO rooms (room_type, room_number, price, status, description, images)
                    VALUES (:room_type, :room_number, :price, :status, :description, :images)";
      $stmt = $conn->prepare($sql);

      $stmt->bindParam(':room_type', $room_type);
      $stmt->bindParam(':room_number', $room_number);
      $stmt->bindParam(':price', $price);
      $stmt->bindParam(':status', $status);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':images', $images_json);

      if ($stmt->execute()) {
        echo json_encode([
          "status" => "success",
          "message" => "Room added successfully!"
        ]);
      } else {
        echo json_encode([
          "status" => "error",
          "message" => "Failed to insert room. Please try again."
        ]);
      }
    } catch (PDOException $e) {
      error_log("Database error: " . $e->getMessage());
      echo json_encode([
        "status" => "error",
        "message" => "Database error. Please try again later."
      ]);
    }
  } else {
    echo json_encode([
      "status" => "error",
      "message" => "All fields are required."
    ]);
  }
} else {
  echo json_encode([
    "status" => "error",
    "message" => "Invalid request method."
  ]);
}
