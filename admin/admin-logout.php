<?php
session_start();
include('connect.php');

if (isset($_COOKIE['admin_remember_token'])) {
    $token = $_COOKIE['admin_remember_token'];
    $stmt = $conn->prepare("UPDATE admins SET remember_token = NULL WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    setcookie('admin_remember_token', '', time() - 3600, "/");
}

session_destroy();
header("Location: admin-login.php");
exit;
?>
