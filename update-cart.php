<?php
session_start();
header('Content-Type: application/json');
require 'connect.php';

$id = (int)$_POST['id'];
$action = $_POST['action'];
$qty_posted = isset($_POST['qty']) ? (int)$_POST['qty'] : null;

$response = ['success' => false, 'qty' => 1, 'subtotal' => 0, 'total' => 0];

// Update quantity in session
foreach ($_SESSION['cart'] as &$item) {
    if ($item['id'] == $id) {
        if ($action === 'increase') {
            $item['qty'] += 1;
        } elseif ($action === 'decrease' && $item['qty'] > 1) {
            $item['qty'] -= 1;
        } elseif ($action === 'manual' && $qty_posted >= 1) {
            $item['qty'] = $qty_posted;
        }
        $response['qty'] = $item['qty'];
        break;
    }
}
unset($item); // break reference

// Now calculate subtotal and total
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $pid = $item['id'];
    $qty = $item['qty'];

    $res = $conn->query("SELECT price FROM products WHERE id = $pid");
    if ($res && $res->num_rows > 0) {
        $price = $res->fetch_assoc()['price'];
        $subtotal = $price * $qty;

        if ($pid == $id) {
            $response['subtotal'] = $subtotal;
        }

        $total += $subtotal;
    }
}

$response['total'] = $total;
$response['success'] = true;

echo json_encode($response);
exit;
?>