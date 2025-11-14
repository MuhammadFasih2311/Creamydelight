<?php
session_start();
include('connect.php');

if (isset($_POST['product_id']) && isset($_POST['qty'])) {
    $id = (int)$_POST['product_id'];
    $qty = (int)$_POST['qty'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;

    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $id) {
            $item['qty'] += $qty;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = ['id' => $id, 'qty' => $qty];
    }

    // Return updated count of unique items
    echo count($_SESSION['cart']);
}
?>
