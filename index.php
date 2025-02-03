<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once('dbcon.php');

if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == true) {
  if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];
    if ($role == 'admin') {
      header("Location: " . BASE_URL . "/dashboard.php");
    } elseif ($role == 'user') {
      header("Location: " . BASE_URL . "/dashboard.php");
    }
  }
} else {
  header("Location: " . BASE_URL . "/auth/login.php");
}

exit();
