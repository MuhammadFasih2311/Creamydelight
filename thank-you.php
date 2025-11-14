<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Creamy Delight - Thank You</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #fdf2f8, #e0f7fa);
      font-family: 'Poppins', sans-serif;
    }
    .thank-card {
      background: white;
      border-radius: 25px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
      padding: 40px;
      max-width: 600px;
      margin: auto;
      transition: transform 0.3s ease;
    }
    .thank-card:hover {
      transform: translateY(-5px);
    }
    .thank-icon {
      font-size: 70px;
      color: #ff6f91;
    }
    .btn-pastel {
      background-color: #ffb6b9;
      border: none;
      color: #fff;
      font-weight: 600;
      border-radius: 30px;
      transition: all 0.3s ease;
    }
    .btn-pastel:hover {
      background-color: #ff92a6;
    }
  </style>
</head>
<?php
session_start();
$order_id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null;
?>
<body class="d-flex flex-column min-vh-100">
<?php include('navbar.php'); ?>

<div class="container text-center py-5 flex-grow-1 mt-4" data-aos="zoom-in">
  <div class="thank-card">
    <div class="thank-icon mb-3">
      <i class="bi bi-emoji-heart-eyes"></i>
    </div>
    <h1 class="text-success mb-3">Yay! Your Order is Confirmed 🍨</h1>
    <?php if ($order_id): ?>
      <p class="lead">Your Order ID: <strong>#<?= $order_id ?></strong></p>
    <?php else: ?>
      <p class="lead text-danger">Order ID not found.</p>
    <?php endif; ?>
    <p class="text-muted">We’re preparing your ice creams with love and care.  
      You’ll get your sweet treats soon! 🍦</p>
    <a href="index.php" class="btn btn-pastel mt-3 px-4 py-2">
      <i class="bi bi-house-door"></i> Back to Home
    </a>
  </div>
</div>

<?php include('footer.php'); ?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000 });

  document.querySelectorAll('.btn-outline-primary').forEach(btn => {
  btn.addEventListener('click', function() {
    this.innerHTML = '<i class="bi bi-check-circle"></i> Added';
    this.classList.remove('btn-outline-primary');
    this.classList.add('btn-success');
  });
});
</script>
</body>
</html>
