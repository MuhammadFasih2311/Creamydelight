<?php
include('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['user_email'];
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);

    // Validate inputs
    if (strlen($name) < 3 || strlen($name) > 30) {
        echo "<script>alert('⚠️ Name must be between 3 and 30 characters.'); window.location.href='contact.php';</script>";
        exit;
    }
    if (!preg_match('/^[0-9]{11}$/', $phone)) {
        echo "<script>alert('⚠️ Phone must be exactly 11 digits.'); window.location.href='contact.php';</script>";
        exit;
    }
    if (strlen($message) < 10 || strlen($message) > 400) {
        echo "<script>alert('⚠️ Message must be between 10 and 400 characters.'); window.location.href='contact.php';</script>";
        exit;
    }

    // Check how many messages user sent in the last hour
    $stmt = $conn->prepare("SELECT COUNT(*) as msg_count FROM contact_messages WHERE email = ? AND created_at >= (NOW() - INTERVAL 1 HOUR)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($result['msg_count'] >= 3) {
        echo "<script>alert('⚠️ You can only send up to 3 messages per hour. Please try again later.'); window.location.href='contact.php';</script>";
        exit;
    }

    // Insert new message
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, message, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Message sent successfully!'); window.location.href='contact.php';</script>";
    } else {
        echo "<script>alert('❌ Failed to send message. Try again.'); window.location.href='contact.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: contact.php");
    exit;
}
?>
