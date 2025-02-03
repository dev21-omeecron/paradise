<?php
require_once('../dbcon.php');

$role = $_SESSION['role'];
session_unset();
session_destroy();
$_SESSION = array();
switch ($role) {
  case "admin";
    header("Location: " . BASE_URL . "/auth/login.php");
    exit();
  case "user";
    header("Location: " . BASE_URL . "/auth/login.php");
    exit();
  default:
    return false;
}
