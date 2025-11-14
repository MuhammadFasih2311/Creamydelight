<?php
include('connect.php');
session_start();

// ======= Login Check =======
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

function redirectWithError($msg) {
    $_SESSION['error'] = $msg;
    header("Location: checkout.php");
    exit;
}

$user_email = $_SESSION['user_email'];
$name = trim($_POST['name']);
$address = trim($_POST['address']);
$phone = trim($_POST['phone']);
$total = 0;

// ===== Backend Validation =====
if (!preg_match("/^[A-Za-z\s]+$/", $name)) {
    redirectWithError("❌ Invalid name! Only letters and spaces allowed.");
}

if (!preg_match("/^\d{11}$/", $phone)) {
    redirectWithError("❌ Invalid phone number! Must be exactly 11 digits.");
}

if (empty($address) || strlen($address) < 5) {
    redirectWithError("❌ Address cannot be empty or too short.");
}

// ===== Check 3 orders / 30 mins limit =====
$stmt = $conn->prepare("SELECT COUNT(*) AS order_count FROM orders WHERE user_email = ? AND created_at >= (NOW() - INTERVAL 30 MINUTE)");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($result['order_count'] >= 3) {
    redirectWithError("⚠️ You can only place up to 3 orders within 30 minutes. Please try again later.");
}

// ===== Cart Check =====
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    redirectWithError("Your cart is empty.");
}

// ===== Calculate Total =====
$cart_items = [];
foreach ($_SESSION['cart'] as $item) {
    $product_id = (int)$item['id'];
    $qty = (int)$item['qty'];

    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $price = (float)$row['price'];
        $subtotal = $qty * $price;
        $total += $subtotal;

        $cart_items[] = [
            'id' => $product_id,
            'qty' => $qty,
            'price' => $price
        ];
    }
    $stmt->close();
}

// ===== Insert Order =====
$stmt = $conn->prepare("INSERT INTO orders (user_email, customer_name, address, phone, total, status, created_at) VALUES (?, ?, ?, ?, ?, 'Pending', NOW())");
$stmt->bind_param("ssssd", $user_email, $name, $address, $phone, $total);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// ===== Insert Order Items =====
foreach ($cart_items as $item) {
    $stmt2 = $conn->prepare("INSERT INTO cart_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiid", $order_id, $item['id'], $item['qty'], $item['price']);
    $stmt2->execute();
    $stmt2->close();
}

// ===== Clear Cart & Redirect =====
unset($_SESSION['cart']);
header("Location: thank-you.php?id=" . $order_id);
exit;
?>
