<?php
require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

if (isset($_POST['update_room'])) {
  $room_type = $_POST['room_type'];
  $price = $_POST['price'];
  $description = $_POST['description'];
  $room_number = $_POST['room_number'];
  $status = $_POST['status'];

  try {
    // Fetch the current room details to get the existing images
    $sql = "SELECT images FROM rooms WHERE room_number = :room_number";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':room_number', $room_number);
    $stmt->execute();
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
      echo json_encode([
        "status" => "error",
        "message" => "Room not found."
      ]);
      exit;
    }

    // Handle existing images (delete selected images)
    $existing_images = json_decode($room['images'], true) ?? [];

    if (!empty($_POST['delete_images'])) {
      foreach ($_POST['delete_images'] as $image) {
        $filePath = "../uploads/rooms/" . $image;
        if (file_exists($filePath)) {
          unlink($filePath);  // Delete the image
        }
      }
      // Remove the deleted images from the existing images array
      $existing_images = array_values(array_diff($existing_images, $_POST['delete_images']));
    }

    // Handle new images upload
    if (!empty($_FILES['new_images']['name'][0])) {
      foreach ($_FILES['new_images']['tmp_name'] as $key => $tmp_name) {
        $file_type = $_FILES['new_images']['type'][$key];
        $file_size = $_FILES['new_images']['size'][$key];

        // Validate file type
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($file_type, $allowed_types)) {
          echo json_encode([
            "status" => "error",
            "message" => "Invalid file type. Only JPG, JPEG, and PNG are allowed."
          ]);
          exit;
        }

        // Validate file size (25MB limit)
        if ($file_size > 25000000) {
          echo json_encode([
            "status" => "error",
            "message" => "File size exceeds 25MB limit"
          ]);
          exit;
        }

        $new_image = uniqid() . "_" . basename($_FILES['new_images']['name'][$key]);
        $upload_path = "../uploads/rooms/" . $new_image;

        // Ensure the upload directory exists
        if (!is_dir("../uploads/rooms/")) {
          mkdir("../uploads/rooms/", 0777, true);
        }

        // Ensure the image is uploaded
        if (move_uploaded_file($tmp_name, $upload_path)) {
          $existing_images[] = $new_image;
        } else {
          echo json_encode([
            "status" => "error",
            "message" => "Error uploading new image: " . $_FILES['new_images']['name'][$key]
          ]);
          exit;
        }
      }
    }

    // Convert the images array to JSON for database storage
    $images_json = json_encode(array_values($existing_images));

    // Update the room details in the database
    $sql = "UPDATE rooms SET room_type = :room_type, price = :price, status = :status, description = :description, images = :images WHERE room_number = :room_number";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':room_type', $room_type);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':images', $images_json);
    $stmt->bindParam(':room_number', $room_number);

    if ($stmt->execute()) {
      echo json_encode([
        "status" => "success",
        "message" => "Room updated successfully!"
      ]);
    } else {
      echo json_encode([
        "status" => "error",
        "message" => "Failed to update room."
      ]);
    }
  } catch (PDOException $e) {
    echo json_encode([
      "status" => "error",
      "message" => "Database error: " . $e->getMessage()
    ]);
  }
} else {
  echo json_encode([
    "status" => "error",
    "message" => "Invalid request method."
  ]);
}
