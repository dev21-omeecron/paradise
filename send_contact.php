<?php
require_once('dbcon.php');

if (!validateSession()) {
  header("Location: " . BASE_URL . "/auth/login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$login_username = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $visitor_name = trim($_POST['visitor_name']);
  $email = trim($_POST['email']);
  $contact = trim($_POST['contact']);
  $subject = trim($_POST['subject']);
  $message = trim($_POST['message']);

  if (!empty($visitor_name) && !empty($email) && !empty($contact) && !empty($subject) && !empty($message)) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      header("Location: contactus.php?status=invalid_email");
      exit();
    }

    if (!preg_match("/^[0-9]{10}$/", $contact)) {
      header("Location: contactus.php?status=invalid_contact");
      exit();
    }

    $sql = "INSERT INTO inquiry (user_id, login_username, visitor_name, email, contact, subject, message)
            VALUES (:user_id, :login_username, :visitor_name, :email, :contact, :subject, :message)";

    if ($stmt = $conn->prepare($sql)) {
      $stmt->bindParam(":user_id", $user_id);
      $stmt->bindParam(":login_username", $login_username);
      $stmt->bindParam(":visitor_name", $visitor_name);
      $stmt->bindParam(":email", $email);
      $stmt->bindParam(":contact", $contact);
      $stmt->bindParam(":subject", $subject);
      $stmt->bindParam(":message", $message);
      // $admin_reply = null;
      // $stmt->bindParam(":admin_reply", $admin_reply);

      if ($stmt->execute()) {
        header("Location: contactus.php?status=success");
        exit();
      } else {
        error_log("Database Error: " . implode(", ", $stmt->errorInfo()));
        header("Location: contactus.php?status=error");
        exit();
      }
    } else {
      error_log("SQL Preparation Error: " . implode(", ", $conn->errorInfo()));
      header("Location: contactus.php?status=error");
      exit();
    }
  } else {
    header("Location: contactus.php?status=empty");
    exit();
  }
}
