<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

try {
  $sql = "SELECT rb.booking_id, rb.room_type, rb.room_number, rb.price, rb.description, rb.booking_status, rb.payment_status, rb.check_in, rb.check_out, rb.total_price, r.images
          FROM room_bookings rb
          JOIN rooms r ON rb.room_number = r.room_number
          WHERE rb.user_id = :user_id";

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
  $stmt->execute();
  $room_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo json_encode(["status" => "error", "message" => "Error fetching booking data: " . $e->getMessage()]);
  exit;
}

if (isset($_GET['delete'])) {
  $booking_id = $_GET['delete'];

  try {
    $sql = "DELETE FROM room_bookings WHERE booking_id = :booking_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: room_history.php");
    exit;
  } catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error deleting booking: " . $e->getMessage()]);
  }
}
