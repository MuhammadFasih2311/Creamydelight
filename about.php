<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="Learn about Creamy Delight – our journey, mission, and passion for delivering the finest ice creams to you.">
  <title>About Us - Creamy Delight</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php
  session_start();
  if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}
 include("navbar.php"); ?>
<br><br>

<!-- Header Section -->
<section class="py-5" style="background: linear-gradient(120deg, #ffd1dc, #d4fc79, #b2f7ef);" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-offset="0">
  <div class="container text-center" >
    <div class="row">
      <div class="col-12 col-sm-12 col-md-12">
    <h1 class="display-2 fw-bold text-dark">About <span style="color:#ff69b4;">Creamy Delight</span></h1>
    <p class="lead mt-3">Spoonfuls of joy since day one! Learn our story and what makes us special.</p>
      </div>
   </div>
</div>
</section>

<!-- Our Story -->
<section class="container py-5">
  <div class="row align-items-center">
    <div class="col-md-6 col-sm-12 mb-4" data-aos="fade-right">
      <img src="images/story.jpg" alt="Our Story" class="img-fluid rounded-4 shadow" style="height:350px" loading="lazy"/>
    </div>
    <div class="col-md-6 col-sm-12" data-aos="fade-left">
      <h2 class="fw-bold mb-3 text-info">Our Journey</h2>
      <p class="text-muted">Creamy Delight began as a passion project – a dream to spread happiness through flavors. What started with a single cart is now a beloved ice cream spot across the city!</p>
      <p class="text-muted">We use only the finest ingredients to craft our unique blends. Whether it’s a classic vanilla or a zesty tropical scoop, every bite is made with love and joy.</p>
    </div>
  </div>
</section>

<!-- Why Choose Us -->
<section class="py-5" style="background-color: #e0f7fa;">
  <div class="container">
    <h2 class="text-center fw-bold text-dark mb-5" data-aos="fade-up">Why Choose Creamy Delight?</h2>
    <div class="row text-center">
      <div class="col-md-4 col-sm-12 mb-4" data-aos="flip-left">
        <i class="bi bi-stars fs-1 text-warning"></i>
        <h5 class="mt-3">Premium Ingredients</h5>
        <p class="text-muted">We pick only the best and freshest ingredients for a delightful taste.</p>
      </div>
      <div class="col-md-4 col-sm-12 mb-4" data-aos="flip-up">
        <i class="bi bi-heart-fill fs-1 text-danger"></i>
        <h5 class="mt-3">Made with Love</h5>
        <p class="text-muted">Every scoop is handcrafted with care and passion for perfection.</p>
      </div>
      <div class="col-md-4 col-sm-12 mb-4" data-aos="flip-right">
        <i class="bi bi-emoji-smile fs-1 text-success"></i>
        <h5 class="mt-3">Customer Happiness</h5>
        <p class="text-muted">We’re here to bring a smile with every serving, for every customer!</p>
      </div>
    </div>
  </div>
</section>

<!-- Team or Extra Section (Optional) -->
<section class="container py-5">
  <div class="text-center mb-5" data-aos="fade-down">
    <h2 class="fw-bold text-info">Meet the Team</h2>
    <p class="text-muted">Behind every scoop is a team full of creativity and care.</p>
  </div>
  <div class="row text-center">
    <div class="col-md-4 col-sm-12" data-aos="zoom-in">
      <img src="images/founder.jpg" class="rounded-circle shadow mb-3" style="width: 150px;" alt="Founder">
      <h6 class="fw-semibold">Ahmed Faraz</h6>
      <p class="text-muted">Founder & Taster-in-Chief</p>
    </div>
    <div class="col-md-4 col-sm-12" data-aos="zoom-in" data-aos-delay="100">
      <img src="images/chef.jpeg" class="rounded-circle shadow mb-3" style="width: 150px;" alt="Chef">
      <h6 class="fw-semibold">Ali Raza</h6>
      <p class="text-muted">Master Ice Cream Chef</p>
    </div>
    <div class="col-md-4 col-sm-12" data-aos="zoom-in" data-aos-delay="200">
      <img src="images/manager.jpeg" class="rounded-circle shadow mb-3" style="width: 150px;" alt="Manager">
      <h6 class="fw-semibold">Sana Malik</h6>
      <p class="text-muted">Customer Happiness Manager</p>
    </div>
  </div>
</section>

<!-- Customer Love Section -->
<section style="background: linear-gradient(to right, rgb(252, 172, 233), rgb(220, 255, 138), rgb(162, 248, 238));" class="py-5">
  <div class="container" data-aos="fade-up">
    <h2 class="text-center fw-bold text-dark mb-5">They Loved Every Scoop!</h2>

    <div id="testimonialSlider" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
      <div class="carousel-inner">

        <!-- Testimonial 1 -->
        <div class="carousel-item active">
          <div class="testimonial-card mx-auto text-center p-4 shadow-lg rounded-4">
            <h5 class="fw-bold text-primary mb-1">Sana Ali</h5>
            <p class="text-muted small">Lahore, PK</p>
            <p class="fst-italic">"It’s like happiness in a cone. Their flavors are always fresh and fun!"</p>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="carousel-item">
          <div class="testimonial-card mx-auto text-center p-4 shadow-lg rounded-4">
            <h5 class="fw-bold text-primary mb-1">Ahmed Raza</h5>
            <p class="text-muted small">Karachi, PK</p>
            <p class="fst-italic">"Creamy Delight made my weekend! The taste, vibe, and service — perfect!"</p>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="carousel-item">
          <div class="testimonial-card mx-auto text-center p-4 shadow-lg rounded-4">
            <h5 class="fw-bold text-primary mb-1">Mehwish Tariq</h5>
            <p class="text-muted small">Islamabad, PK</p>
            <p class="fst-italic">"From the colors to the taste — everything feels magical!"</p>
          </div>
        </div>

      </div>

      <!-- Controls Inside -->
      <div class="d-flex justify-content-center mt-4">
        <button class="btn btn-outline-dark me-3 rounded-circle" type="button" data-bs-target="#testimonialSlider" data-bs-slide="prev">
          <i class="bi bi-chevron-left"></i>
        </button>
        <button class="btn btn-outline-dark rounded-circle" type="button" data-bs-target="#testimonialSlider" data-bs-slide="next">
          <i class="bi bi-chevron-right"></i>
        </button>
      </div>
    </div>
  </div>
</section>
<br><br>


<?php include("footer.php"); ?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 1000 });</script>


</body>
</html>
