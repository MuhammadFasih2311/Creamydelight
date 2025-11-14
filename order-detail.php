<?php
include('connect.php');
session_start();
if (!isset($_SESSION['user_logged_in'])) {
  header("Location: login.php");
  exit;
}
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;

// Fetch order
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    echo "<div style='text-align:center; margin-top:50px;'><h4>Order not found.</h4></div>";
    exit;
}

$order = $order_result->fetch_assoc();
$stmt->close();

// Fetch order items
$stmt = $conn->prepare("SELECT p.name AS product_name, p.price, ci.quantity, (p.price * ci.quantity) AS subtotal
                        FROM cart_items ci
                        JOIN products p ON ci.product_id = p.id
                        WHERE ci.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Details - Creamy Delight</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom right, #fff1f9, #e0f7fa);
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .order-box {
      background: white;
      padding: 2.5rem;
      border-radius: 1.25rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    .btn-custom {
      border-radius: 50px;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .highlight-row {
      background-color: #f0f8ff;
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100"> 
<?php include("navbar.php"); ?>

<main class="flex-grow-1 mt-4">
  <div class="container py-5">

  <div class="order-box mx-auto" style="max-width: 850px;">
    <h2 class="text-center text-primary mb-4" data-aos="fade-down">📦 Order #<?= $order_id ?> Details</h2>

    <div class="mb-4">
      <p data-aos="fade-right"><strong>👤 Name:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
      <p data-aos="fade-right" data-aos-delay="100"><strong>📩 Email:</strong> <?= htmlspecialchars($order['user_email']) ?></p>
      <p data-aos="fade-right" data-aos-delay="100"><strong>📞 Phone:</strong> <?= htmlspecialchars($order['phone']) ?></p>
      <p data-aos="fade-right" data-aos-delay="200"><strong>🏠 Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
      <p data-aos="fade-right" data-aos-delay="300"><strong>📅 Date:</strong> <?= $order['created_at'] ?></p>
    </div>

    <div class="table-responsive mb-4">
      <table class="table table-bordered text-center" data-aos="fade-up" data-aos-delay="350">
        <thead class="table-light">
          <tr>
            <th>🍨 Product</th>
            <th>💰 Price</th>
            <th>🔢 Quantity</th>
            <th>📊 Subtotal</th>
          </tr>
        </thead>
        <tbody>
          <?php $total = 0; while ($item = $items_result->fetch_assoc()): ?>
            <tr data-aos="fade-right" data-aos-delay="100">
              <td><?= htmlspecialchars($item['product_name']) ?></td>
              <td>Rs. <?= number_format($item['price']) ?></td>
              <td><?= $item['quantity'] ?></td>
              <td>Rs. <?= number_format($item['subtotal']) ?></td>
            </tr>
            <?php $total += $item['subtotal']; ?>
          <?php endwhile; ?>
          <tr class="fw-bold highlight-row" data-aos="fade-left" data-aos-delay="100">
            <td colspan="3" class="text-end">Total:</td>
            <td>Rs. <?= number_format($total) ?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-center" data-aos="fade-up">
    <a href="my-orders.php" class="btn btn-outline-primary btn-custom">
        &larr; Back to Orders
    </a>
    </div>
  </div>
</div>
  </div> 
</main>

<?php
include("footer.php");
?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000 });
  </script>
</body>
</html>
