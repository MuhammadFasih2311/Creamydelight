<?php
session_start();
include('connect.php');

// Agar cookie hai toh usko clear bhi karo aur DB se token delete karo
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("UPDATE users SET remember_token = NULL WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    setcookie("remember_token", "", time() - 3600, "/");
}

session_destroy();
header("Location: login.php");
exit;
?>
