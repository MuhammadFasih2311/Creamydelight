<?php
// delete-product.php
include('connect.php');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $conn->query("DELETE FROM products WHERE id = $id");
}

header('Location: admin-panel.php');
exit;
?>