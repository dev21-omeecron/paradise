<?php
require_once('../dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $inquiry_id = $_POST['inquiry_id'];
  $admin_reply = $_POST['reply'];

  if (!empty($inquiry_id) && !empty($admin_reply)) {
    $sql = "UPDATE inquiry SET admin_reply = :admin_reply WHERE inquiry_id = :inquiry_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':admin_reply', $admin_reply);
    $stmt->bindParam(':inquiry_id', $inquiry_id);

    if ($stmt->execute()) {
      echo json_encode(['status' => 'success', 'message' => 'Reply sent successfully!']);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Failed to send reply.']);
    }
  } else {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
  }
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
