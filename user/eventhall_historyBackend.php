<?php

require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

try {
  $sql = "SELECT hb.booking_id, hb.hall_type, hb.hall_number, hb.capacity, hb.price_per_hour, hb.description, hb.check_in, hb.check_out, hb.total_price, hb.booking_status, hb.payment_status, e.images
          FROM hall_bookings hb
          JOIN event_halls e ON hb.hall_number = e.hall_number
          WHERE hb.user_id = :user_id";

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
  $stmt->execute();
  $hall_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo json_encode(["status" => "error", "message" => "Error fetching booking data: " . $e->getMessage()]);
  exit;
}

if (isset($_GET['delete'])) {
  $booking_id = $_GET['delete'];

  try {
    $sql = "DELETE FROM hall_bookings WHERE booking_id = :booking_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":booking_id", $booking_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: " . BASE_URL . "/user/eventhall_history.php");
    exit;
  } catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Error deleting booking: " . $e->getMessage()]);
  }
}
