<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
  case 'fetchRooms':
    fetchRooms($_POST['room_type']);
    break;

  case 'fetchDetails':
    fetchRoomDetails($_POST['room_number']);
    break;

  case 'bookRoom':
    bookRoom($_POST);
    break;

  default:
    echo "Invalid action.";
    break;
}

function fetchRooms($room_type)
{
  global $conn;
  $sql = "SELECT room_number FROM rooms WHERE room_type = :room_type";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':room_type', $room_type, PDO::PARAM_STR);
  $stmt->execute();
  $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if ($rooms) {
    foreach ($rooms as $room) {
      echo "<option value='" . $room['room_number'] . "'>" . $room['room_number'] . "</option>";
    }
  } else {
    echo "<option value=''>No rooms available</option>";
  }
}

function fetchRoomDetails($room_number)
{
  global $conn;
  try {
    $sql = "SELECT room_id, price, description, images FROM rooms WHERE room_number = :room_number";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
    $stmt->execute();
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) {
      // Parse the JSON string to get the array of images
      $images = json_decode($room['images'], true);
      if (!is_array($images)) {
        $images = [];
      }

      $response = array(
        'price' => $room['price'],
        'description' => $room['description'],
        'images' => $images
      );

      header('Content-Type: application/json');
      echo json_encode($response);
    } else {
      echo json_encode(array('error' => 'Room not found.'));
    }
  } catch (PDOException $e) {
    echo json_encode(array('error' => 'Database error: ' . $e->getMessage()));
  }
}

function bookRoom($data)
{
  global $conn;

  $room_type = $data['room_type'];
  $room_number = $data['room_number'];
  $price = $data['price'];
  $description = $data['description'];
  $check_in = $data['check_in'];
  $check_out = $data['check_out'];
  $total_price = $data['total_price'];
  $user_id = $_SESSION['user_id'];
  $username = $_SESSION['username'];
  $email = $_SESSION['email'];

  // Fetch room_id based on room_number
  $sql = "SELECT room_id, images FROM rooms WHERE room_number = :room_number LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
  $stmt->execute();
  $room = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($room) {
    $room_id = $room['room_id'];
  } else {
    echo "Error: Room not found!";
    return;
  }

  $booking_id = mt_rand(100000, 999999);
  $sql = "INSERT INTO room_bookings (booking_id, user_id, room_id, username, email, room_type, room_number, price, description, check_in, check_out, total_price, created_at) 
            VALUES (:booking_id, :user_id, :room_id, :username, :email, :room_type, :room_number, :price, :description, :check_in, :check_out, :total_price, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_STR);
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
  $stmt->bindParam(':username', $username, PDO::PARAM_STR);
  $stmt->bindParam(':email', $email, PDO::PARAM_STR);
  $stmt->bindParam(':room_type', $room_type, PDO::PARAM_STR);
  $stmt->bindParam(':room_number', $room_number, PDO::PARAM_STR);
  $stmt->bindParam(':price', $price, PDO::PARAM_STR);
  $stmt->bindParam(':description', $description, PDO::PARAM_STR);
  $stmt->bindParam(':check_in', $check_in, PDO::PARAM_STR);
  $stmt->bindParam(':check_out', $check_out, PDO::PARAM_STR);
  $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);

  if ($stmt->execute()) {
    echo "Booking successful!";
  } else {
    echo "Error: Could not complete your booking.";
  }
}
