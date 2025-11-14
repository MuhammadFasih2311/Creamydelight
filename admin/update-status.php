<?php
include('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        echo "Order #$order_id status updated to '$status'.";
    } else {
        http_response_code(500);
        echo "Error updating order status.";
    }

    $stmt->close();
    $conn->close();
}
?>
