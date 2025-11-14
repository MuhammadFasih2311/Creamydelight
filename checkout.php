<?php 
include('connect.php');
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}


// Fetch user info
$user_email = $_SESSION['user_email'] ?? null;
$user_name = '';
$user_phone = '';

if ($user_email) {
  $stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE email = ?");
  $stmt->bind_param("s", $user_email);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_name = $user['name'];
    $user_phone = $user['phone'] ?? '';
  }
  $stmt->close();
}

// Check how many orders placed in last 30 minutes
$order_limit_reached = false;
if ($user_email) {
  $stmt = $conn->prepare("SELECT COUNT(*) AS order_count 
                          FROM orders 
                          WHERE user_email = ? 
                          AND created_at >= (NOW() - INTERVAL 30 MINUTE)");
  $stmt->bind_param("s", $user_email);
  $stmt->execute();
  $result = $stmt->get_result()->fetch_assoc();
  if ($result['order_count'] >= 3) {
    $order_limit_reached = true;
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Securely place your order at Creamy Delight. Fast checkout and reliable delivery for your favorite ice creams.">
  <title>Creamy Delight - Checkout</title>
  <link rel="icon" href="images/logo.png" type="image/png">

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">

  <style>
    body {
      opacity: 0;
      transition: opacity 0.4s ease-in;
      background: linear-gradient(to bottom right, #fef6ff, #e0f7fa);
    }
    body.loaded {
      opacity: 1;
    }
    .form-control, textarea {
      border-radius: 1rem;
    }
    .shadow-rounded {
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      border-radius: 1.5rem;
    }
    .checkout-header {
      background: linear-gradient(to right, #ffd1dc, #c2f0fc);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    .btn-lg {
      border-radius: 50px;
    }
  </style>
</head>
<body>
  <?php 
  include('navbar.php');
  ?>
<br><br>

<div class="container py-5" data-aos="fade-up">
  <div class="checkout-header text-center mb-5">
    <h1 class="text-info fw-bold" data-aos="zoom-in">🧾 Checkout</h1>
    <p class="text-muted mb-0">We’re almost ready to deliver your happiness!</p>
  </div>

  <?php if ($order_limit_reached): ?>
    <div class="alert alert-warning text-center fw-semibold" role="alert">
      ⚠️ You can only place up to 3 orders within 30 minutes. Please try again later.
    </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
      <?= $_SESSION['error']; ?>
    </div>
    <?php unset($_SESSION['error']); endif; ?>

  <form method="post" action="process-order.php" onsubmit="return validateForm()">
    <div class="row g-4">
      <!-- Left: Delivery Form -->
      <div class="col-md-6" data-aos="flip-right">
        <div class="p-4 bg-white shadow-rounded">
          <h4 class="mb-4 text-primary fw-semibold">Delivery Details</h4>

          <div class="mb-3">
            <label class="form-label fw-semibold">Full Name</label>
            <input type="text" name="name" id="name" class="form-control shadow-sm"
                   value="<?= htmlspecialchars($user_name) ?>"
                   placeholder="e.g. Ayesha Khan"
                   oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')"
                   required maxlength="30" minlength="3">
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control shadow-sm"
                   value="<?= htmlspecialchars($user_email) ?>" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Address</label>
            <textarea name="address" id="address" class="form-control shadow-sm" rows="4" required maxlength="200" minlength="5"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Phone</label>
            <input type="tel" name="phone" id="phone" class="form-control shadow-sm"
                   placeholder="e.g. 03001234567"
                   value="<?= htmlspecialchars($user_phone) ?>"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                   minlength="11" maxlength="11"
                   required>
          </div>
        </div>
      </div>

      <!-- Right: Order Summary -->
      <div class="col-md-6" data-aos="flip-left">
        <div class="p-4 bg-white shadow-rounded">
          <h4 class="mb-4 text-success fw-semibold">Order Summary</h4>

          <?php
          $total = 0;
          if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0):
            foreach ($_SESSION['cart'] as $item):
              $product_id = (int)$item['id'];
              $qty = (int)$item['qty'];

              $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
              $stmt->bind_param("i", $product_id);
              $stmt->execute();
              $result = $stmt->get_result();

              if ($result->num_rows > 0):
                $product = $result->fetch_assoc();
                $subtotal = $product['price'] * $qty;
                $total += $subtotal;
          ?>
          <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
            <span><i class="bi bi-ice-cream me-1"></i><?= htmlspecialchars($product['name']) ?> × <?= $qty ?></span>
            <span class="fw-semibold text-muted">Rs. <?= number_format($subtotal) ?></span>
          </div>
          <?php
              endif;
              $stmt->close();
            endforeach;
          ?>
          <hr>
          <div class="d-flex justify-content-between fw-bold fs-5">
            <span>Total:</span>
            <span class="text-success">Rs. <?= number_format($total) ?></span>
          </div>
          <?php else: ?>
            <div class="alert alert-warning mt-3">Your cart is empty.</div>
            <div class="text-center">
              <a class="btn btn-info mt-3 text-light px-4 rounded-pill" href="icecreams.php">Checkout our ice-creams 🍨</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="text-center mt-5" data-aos="zoom-in-up">
      <button type="submit" class="btn btn-lg btn-primary px-5 shadow" 
              <?= ($order_limit_reached || empty($_SESSION['cart'])) ? 'disabled' : '' ?>>
        <i class="bi bi-bag-check-fill"></i> Place Order
      </button>
    </div>
  </form>
</div>

<?php include('footer.php'); ?>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  document.body.classList.add('loaded'); // smooth fade-in after load
  AOS.init({ duration: 1000 });

  // Auto hide alert
  setTimeout(() => {
    const alertBox = document.getElementById('errorAlert');
    if (alertBox) {
      alertBox.classList.remove('show');
      alertBox.classList.add('fade');
    }
  }, 10000);
});

function validateForm() {
  const name = document.getElementById('name').value.trim();
  const phone = document.getElementById('phone').value.trim();

  if (name.length < 3) {
    alert('⚠️ Please enter at least 3 characters for your name.');
    return false;
  }
  if (phone.length !== 11 || !/^[0-9]+$/.test(phone)) {
    alert('⚠️ Phone number must be exactly 11 digits.');
    return false;
  }
  return true;
}
</script>
</body>
</html>
