<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (
    isset($_POST['hall_type'], $_POST['hall_number'], $_POST['capacity'], $_POST['price_per_hour'], $_POST['status'], $_POST['description'])
    && isset($_FILES['images'])
  ) {
    $hall_type = $_POST['hall_type'];
    $hall_number = $_POST['hall_number'];
    $capacity = $_POST['capacity'];
    $price_per_hour = $_POST['price_per_hour'];
    $status = $_POST['status'];
    $description = $_POST['description'];
    $uploaded_images = [];

    $upload_dir = "../uploads/halls/";
    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }

    // File size and type validation
    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
      $file_name = basename($_FILES['images']['name'][$key]);
      $target_file = $upload_dir . $file_name;
      $file_size = $_FILES['images']['size'][$key];
      $file_type = $_FILES['images']['type'][$key];

      // Check file size (limit to 25MB)
      if ($file_size > 25000000) {
        echo json_encode([
          "status" => "error",
          "message" => "File size exceeds 25MB limit"
        ]);
        exit;
      }

      // Check allowed file types
      $allowed_types = ['image/jpg', 'image/jpeg', 'image/png'];
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
      $sql = "INSERT INTO event_halls (hall_type, hall_number, capacity, price_per_hour, status, description, images)
                    VALUES (:hall_type, :hall_number, :capacity, :price_per_hour, :status, :description, :images)";
      $stmt = $conn->prepare($sql);

      $stmt->bindParam(':hall_type', $hall_type);
      $stmt->bindParam(':hall_number', $hall_number);
      $stmt->bindParam(':capacity', $capacity);
      $stmt->bindParam(':price_per_hour', $price_per_hour);
      $stmt->bindParam(':status', $status);
      $stmt->bindParam(':description', $description);
      $stmt->bindParam(':images', $images_json);

      if ($stmt->execute()) {
        echo json_encode([
          "status" => "success",
          "message" => "Event Hall added successfully!"
        ]);
      } else {
        echo json_encode([
          "status" => "error",
          "message" => "Failed to insert Event Hall. Please try again."
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
