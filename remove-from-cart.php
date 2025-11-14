<?php
session_start();
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$id = intval($_GET['id']);

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $id) {
            unset($_SESSION['cart'][$index]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// ✅ Recalculate total and product count (not quantity)
$total = 0;
$count = 0;

if (!empty($_SESSION['cart'])) {
    include('connect.php');
    foreach ($_SESSION['cart'] as $item) {
        $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->bind_param("i", $item['id']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        if ($result) {
            $total += $result['price'] * $item['qty'];
        }
        $stmt->close();
    }
    $conn->close();

    // ✅ Count only unique products
    $count = count($_SESSION['cart']);
}

echo json_encode([
    'success' => true,
    'total' => number_format($total),
    'count' => $count
]);
?>
