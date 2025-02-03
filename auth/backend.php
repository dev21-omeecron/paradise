<?php

require_once("../dbcon.php");

$response = ["status" => "error", "message" => "Invalid request"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $action = $_POST['action'] ?? '';

  if ($action === 'register') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirmPassword'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $agreeTerms = $_POST['terms'] ?? false;

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($contact) || empty($gender)) {
      $response['message'] = "Please fill in all required fields.";
      echo json_encode($response);
      exit;
    }

    // Check if email already exists in the database
    $query = "SELECT user_id FROM user WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
      $response['message'] = "This email is already registered.";
      echo json_encode($response);
      exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $query = "INSERT INTO user (username, email, password, contact, gender, session_id) VALUES (:username, :email, :password, :contact, :gender, :session_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":password", $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(":contact", $contact, PDO::PARAM_STR);
    $stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
    $session_id = session_id();
    $stmt->bindParam(":session_id", $session_id, PDO::PARAM_STR);

    if ($stmt->execute()) {
      $response['status'] = "success";
      $response['message'] = "Registration successful. Redirecting to login page.";
    } else {
      $response['message'] = "An error occurred while saving your details. Please try again.";
    }
  } elseif ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $res = [];

    $table = "";
    $id_column = "";
    $session_column = "session_id";
    switch ($role) {
      case 'admin':
        $table = "admin";
        $id_column = "admin_id";
        break;
      case 'user':
        $table = "user";
        $id_column = "user_id";
        break;
      default:
        $res = ["status" => "error", "message" => "Invalid role"];
        echo json_encode($res);
        exit;
    }

    $sql = "SELECT * FROM {$table} WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch();
      if (password_verify($password, $user['password'])) {
        session_regenerate_id();
        $_SESSION["is_logged_in"] = true;
        $_SESSION["role"] = $role;

        if ($role === 'admin') {
          $_SESSION['admin_id'] = $user['admin_id'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['email'] = $user['email'];
        } elseif ($role === 'user') {
          $_SESSION['user_id'] = $user['user_id'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['email'] = $user['email'];
        }

        $session_id = session_id();
        $updateQuery = "UPDATE {$table} SET {$session_column} = :session_id WHERE {$id_column} = :id";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bindParam(":session_id", $session_id, PDO::PARAM_STR);
        $updateStmt->bindParam(":id", $user[$id_column], PDO::PARAM_INT);
        $updateStmt->execute();

        $response = ["status" => "success", "message" => "Login successful. Redirecting to dashboard."];
        $response['session_id'] = $session_id;
      } else {
        $response = ["status" => "error", "message" => "Invalid email or password."];
      }
    } else {
      $response = ["status" => "error", "message" => "No user found with this email"];
    }
    echo json_encode($response);
    exit;
  } elseif ($_POST['action'] === 'change_password') {
    if (!validateSession()) {
      echo json_encode(["status" => "error", "message" => "Session expired. Please login again."]);
      exit;
    }

    // Current Password, New Password and Role
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $role = $_SESSION['role'];

    if (empty($currentPassword) || empty($newPassword) || empty($role)) {
      echo json_encode(["status" => "error", "message" => "All fields are required"]);
      exit;
    }

    $table = "";
    $id_column = "";
    $userId = "";

    switch ($role) {
      case 'admin':
        $table = "admin";
        $id_column = "admin_id";
        $userId = $_SESSION['admin_id'];
        break;
      case 'user':
        $table = "user";
        $id_column = "user_id";
        $userId = $_SESSION['user_id'];
        break;
      default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid role']);
        exit;
    }

    try {
      // First verify current password
      $stmt = $conn->prepare("SELECT password FROM {$table} WHERE {$id_column} = :id");
      $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$result || !password_verify($currentPassword, $result['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Current password is incorrect']);
        exit;
      }

      // Update password
      $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("UPDATE {$table} SET password = :password WHERE {$id_column} = :id");
      $stmt->bindParam(":password", $hashedPassword, PDO::PARAM_STR);
      $stmt->bindParam(":id", $userId, PDO::PARAM_INT);

      if ($stmt->execute()) {
        // Destroy session after password change
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Password changed successfully. Please login again.']);
        exit;
      } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to change password']);
        exit;
      }
    } catch (PDOException $e) {
      echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
      exit;
    }
  }
}

if (!headers_sent()) {
  echo json_encode($response);
}
