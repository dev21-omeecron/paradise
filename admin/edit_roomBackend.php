<?php
require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $room_id = $_POST['room_id'];
  $room_type = $_POST['room_type'];
  $room_number = $_POST['room_number'];
  $price = $_POST['price'];
  $status = $_POST['status'];
  $description = $_POST['description'];
  $delete_images = isset($_POST['delete_images']) ? $_POST['delete_images'] : [];


  if (!$room_id || !$room_type || !$room_number || !$price || !$status || !$description) {
    echo json_encode([
      "status" => "error",
      "message" => "Invalid or missing inputs."
    ]);
    exit;
  }

  try {
    $stmt = $conn->prepare("SELECT images FROM rooms WHERE room_id = :room_id");
    $stmt->execute([':room_id' => $room_id]);
    $room = $stmt->fetch();

    if (!$room) {
      echo json_encode([
        "status" => "error",
        "message" => "Room not found."
      ]);
      exit;
    }

    $current_images = json_decode($room['images'], true) ?: [];

    // Handle image deletions
    foreach ($delete_images as $image) {
      $image_path = "../uploads/rooms/" . basename($image);
      if (file_exists($image_path)) {
        unlink($image_path);
        $current_images = array_diff($current_images, [$image]);
      }
    }

    // Handle new image uploads
    $uploaded_images = [];
    if (isset($_FILES['images'])) {
      $upload_dir = "../uploads/rooms/";
      foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
          $file_name = uniqid() . '_' . basename($_FILES['images']['name'][$key]);
          $file_size = $_FILES['images']['size'][$key];
          $file_tmp = $_FILES['images']['tmp_name'][$key];
          $file_type = mime_content_type($file_tmp);

          if ($file_size > 25000000) {
            echo json_encode([
              "status" => "error",
              "message" => "File size exceeds 25MB limit"
            ]);
            exit;
          }

          $allowed_types = ["image/jpeg", "image/jpg", "image/png"];
          if (!in_array($file_type, $allowed_types)) {
            echo json_encode([
              "status" => "error",
              "message" => "Only JPG, JPEG and PNG files are allowed."
            ]);
            exit;
          }

          if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
            $uploaded_images[] = $file_name;
          }
        }
      }
    }

    // Merge current and new images
    $final_images = array_merge($current_images, $uploaded_images);
    $images_json = json_encode(array_values($final_images));

    $update_stmt = $conn->prepare(
      "UPDATE rooms SET 
                room_type = :room_type,
                room_number = :room_number,
                price = :price,
                status = :status,
                description = :description,
                images = :images
             WHERE room_id = :room_id"
    );

    $update_stmt->execute([
      ':room_type' => $room_type,
      ':room_number' => $room_number,
      ':price' => $price,
      ':status' => $status,
      ':description' => $description,
      ':images' => $images_json,
      ':room_id' => $room_id
    ]);

    echo json_encode([
      "status" => "success",
      "message" => "Room updated successfully."
    ]);
  } catch (Exception $e) {
    echo json_encode([
      "status" => "error",
      "message" => "Error updating room: " . $e->getMessage()
    ]);
  }
}
