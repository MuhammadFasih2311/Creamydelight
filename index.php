<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Buy fresh and delicious ice creams from Creamy Delight. Explore a variety of flavors, all at great prices!">
  <title>Creamy Delight - Ice Cream Heaven</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
  <?php 
include('connect.php'); // Database connection
session_start(); 
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}

?>
  <?php include('navbar.php'); ?>
  <br><br>

  <!-- Hero Section -->
<section style="background: linear-gradient(to right, #ffd1dc, #d4fc79, #b2f7ef);" class="text-dark py-5" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-offset="0" >
  <div class="container text-center">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-lg-12">
    <h1 class="display-1 fw-bold mb-3" style="color: #4b4453;">🍦 Welcome to <span style="color:#ff69b4;">Creamy Delight</span></h1>
    <p class="lead mb-4" style="font-size: 1.25rem;">
      Scoops of happiness, swirls of joy. <br class="d-none d-md-block"> Discover your favorite frozen treat today!
    </p>
    <a href="checkout.php" class="btn-gradient buy-btn m-2">
  🍨 Buy Now
</a>

<a href="icecreams.php" class="btn-gradient explore-btn m-2">
  🍧 Explore Flavors
</a>
      </div>
    </div>
  </div>
</section>

<!-- Welcome Section -->
<section class="container-fluid py-5 px-3">
    <div class="container">
  <div class="row align-items-center">
    <div class="col-md-6 col-sm-12 col-lg-6 mb-4" data-aos="fade-right">
      <h2 class="fw-bold mb-3 text-info text-center">Welcome to Creamy Delight</h2>
      <p class="text-muted text-center">Step into a world of colors, flavors, and happiness! Creamy Delight isn't just an ice cream shop — it's a joyful experience in every scoop.</p>
      <p class="text-muted text-center">From creamy classics to fun fruity swirls, we serve happiness chilled to perfection. Taste the love, feel the joy, and come back for more!</p>
    </div>
    <div class="col-md-6 col-sm-12 col-lg-6" data-aos="fade-left">
       <img src="images/shop1.jpg" alt="Our Story" class="img-fluid rounded-4 shadow" style="height:350px" loading="lazy"/>
    </div>
  </div>
</div>
</section>


<!-- Best Sellers Section -->

<section class="container py-5" data-aos="fade-up">
  <div class="text-center mb-5">
    <h2 class="fw-bold display-5" style="color: #ff69b4;">🍦 Our Best Sellers</h2>
    <p class="lead text-muted">Customer favorites that never disappoint!</p>
  </div>

  <div class="row g-4">
    <?php


    $baseImagePath = 'images/';
    $defaultImage = 'default.jpg';

    $result = $conn->query("SELECT * FROM products LIMIT 3");
    while ($row = $result->fetch_assoc()):
      $productName = htmlspecialchars($row['name']);
      $productDesc = htmlspecialchars($row['description']);
      $productPrice = htmlspecialchars($row['price']);
      $productId = (int)$row['id'];
      $imgFile = !empty($row['image']) ? $row['image'] : $defaultImage;
      $productImg = $baseImagePath . $imgFile;
    ?>
    <div class="col-lg-4 col-sm-12 col-md-4">
      <div class="card h-100 shadow rounded-4 border-0">
        <img src="<?= $productImg ?>" class="card-img-top rounded-top-4" alt="<?= $productName ?>" style="height: 300px; object-fit: cover;">
        <div class="card-body d-flex flex-column justify-content-between">
          <div>
            <h5 class="card-title fw-bold"><?= $productName ?></h5>
            <p class="card-text text-muted small"><?= $productDesc ?></p>
          </div>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <span class="fw-bold text-success">Rs. <?= $productPrice ?></span>
            <form class="add-to-cart-form m-0" data-id="<?= $productId ?>">
  <button type="submit" class="btn btn-sm btn-outline-primary btn-add-to-cart rounded-pill px-3">Add to Cart</button>
</form>

          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
</section>

<div class="text-center my-4" data-aos="zoom-in-up" data-aos-duration="1200">
  <a href="icecreams.php" class="btn btn-outline-info btn-lg rounded-pill px-4">
    🍨 View All Ice Creams
  </a>
</div>

<!-- Call to Action Section -->
<section style="background: linear-gradient(to right, #b2f7ef);" class="py-5 text-center" data-aos="zoom-in-up" data-aos-delay="300" data-aos-duration="1200">
  <div class="container">
    <h2 class="fw-bold text-dark mb-3">🍨 Ready to Taste Happiness?</h2>
    <p class="mb-4 lead text-secondary">Order online or drop by — we’re scooping smiles every day!</p>
   <a href="icecreams.php" class="order-btn">
  🛒 Order Now
</a>

    <a href="contact.php" class="contact-btn">
  📍 Contact Us
</a>
  </div>
</section>

<section class="container my-5">
  <h2 class="text-center text-info fw-bold mb-4">🌟 What Our Customers Say</h2>
  <div class="row text-center">
    <div class="col-md-4 mb-3" data-aos="flip-left" data-aos-delay="300" data-aos-duration="1200">
      <div class="p-3 colo rounded shadow-sm">
        <p class="mb-1">“Absolutely delicious! My kids love it!”</p>
        <h6 >– Ayesha K.</h6>
      </div>
    </div>
    <div class="col-md-4 mb-3" data-aos="flip-left" data-aos-delay="300" data-aos-duration="1200">
      <div class="p-3 colo rounded shadow-sm">
        <p class="mb-1">“Best strawberry swirl in town!”</p>
        <h6 >– Hamza R.</h6>
      </div>
    </div> 
    <div class="col-md-4 mb-3" data-aos="flip-left" data-aos-delay="300" data-aos-duration="1200">
      <div class="p-3 colo rounded shadow-sm">
        <p class="mb-1">“Creamy and fresh — 10/10!”</p>
        <h6 >– Sana F.</h6>
      </div>
    </div>
  </div>
</section>


<?php include("footer.php"); ?>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 1000,  // smooth timing
  });


document.querySelectorAll('.add-to-cart-form').forEach(form => {
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const productId = this.dataset.id;
    const button = this.querySelector('button');

    fetch('add-to-cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `product_id=${productId}&qty=1`
    })
    .then(res => res.text())
    .then(cartCount => {
      button.innerHTML = '<i class="bi bi-check-circle-fill"></i> Added';
      button.classList.remove('btn-outline-primary');
      button.classList.add('btn-success');

      // 🔥 Update cart badge
      const cartBadge = document.querySelector('.bi-cart + .badge');
      if (cartBadge) {
        cartBadge.textContent = cartCount;
      }
    });
  });
});


</script>
</body>
</html>
