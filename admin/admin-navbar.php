<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg shadow-sm fixed-top px-3" style="background: linear-gradient(to right, #ffd1dc, #a0e7e5); transition: all 0.4s ease;">
  <div class="container">
    <a class="navbar-brand fw-bold" href="admin-panel.php" style="color: #ff69b4; font-size: 1.5rem;" data-aos="fade-right">
      <img src="images/logo.png" alt="" width="40px">
      <span style="color: #0d6efd;">Admin</span> Panel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav" aria-label="Toggle navigation">
      <span class="animated-toggler">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item" data-aos="fade-down" data-aos-delay="50">
          <a class="nav-link fw-semibold px-3 py-2 rounded-pill position-relative <?= ($currentPage == 'admin-orders.php') ? 'active' : '' ?>" href="admin-orders.php">
            <span class="nav-text">Orders</span>
            <span class="nav-underline"></span>
          </a>
        </li>
        <li class="nav-item" data-aos="fade-down" data-aos-delay="100">
          <a class="nav-link fw-semibold px-3 py-2 rounded-pill position-relative <?= ($currentPage == 'admin-panel.php') ? 'active' : '' ?>" href="admin-panel.php">
            <span class="nav-text">Products</span>
            <span class="nav-underline"></span>
          </a>
        </li>
        <li class="nav-item" data-aos="fade-down" data-aos-delay="150">
          <a class="nav-link fw-semibold px-3 py-2 rounded-pill position-relative <?= ($currentPage == 'admin-messages.php') ? 'active' : '' ?>" href="admin-messages.php">
            <span class="nav-text">Messages</span>
            <span class="nav-underline"></span>
          </a>
        </li>
        <li class="nav-item" data-aos="fade-down" data-aos-delay="200">
          <a class="nav-link fw-semibold px-3 py-2 rounded-pill position-relative text-danger <?= ($currentPage == 'admin-logout.php') ? 'active' : '' ?>" href="admin-logout.php">
            <span class="nav-text">Logout</span>
            <span class="nav-underline"></span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
  nav.navbar.scrolled {
    background: rgba(255, 255, 255, 0.95) !important;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  }

  .nav-underline {
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 2px;
    background: linear-gradient(to right, #ff69b4, #0d6efd);
    transition: all 0.3s ease;
    transform: translateX(-50%);
  }

  .nav-link:hover .nav-underline,
  .nav-link.active .nav-underline {
    width: 70%;
  }

  .animated-toggler {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    width: 24px;
    height: 18px;
  }
  .animated-toggler .bar {
    height: 2px;
    width: 100%;
    background: #0d6efd;
    transition: all 0.3s ease;
  }
  .navbar-toggler[aria-expanded="true"] .bar:nth-child(1) {
    transform: translateY(8px) rotate(45deg);
  }
  .navbar-toggler[aria-expanded="true"] .bar:nth-child(2) {
    opacity: 0;
  }
  .navbar-toggler[aria-expanded="true"] .bar:nth-child(3) {
    transform: translateY(-8px) rotate(-45deg);
  }

  .nav-link {
    transition: all 0.3s ease;
    position: relative;
  }
  .nav-link:hover {
    background: linear-gradient(to right, #fcdde1, #c0f7ea);
    color: #000 !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
  }

  .nav-link.active {
    background: linear-gradient(to right, #ffb6c1, #a0e7e5);
    color: #000 !important;
    font-weight: 700;
    box-shadow: 0 3px 10px rgba(0,0,0,0.15);
  }

  .nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 0;
    background: linear-gradient(to right, #ff69b4, #5eee5e);
    transition: width 0.3s ease;
  }
  .nav-link:hover::after,
  .nav-link.active::after {
    width: 100%;
  }

  @media (max-width: 991px) {
    .navbar-collapse {
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      background: linear-gradient(to bottom right, #ffd1dc, #b2f7ef);
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.4s ease, opacity 0.3s ease;
      opacity: 0;
      z-index: 999;
    }
    .navbar-collapse.show {
      max-height: 1000px;
      opacity: 1;
      padding: 1rem 0;
    }
    .navbar-nav .nav-item {
      margin: 10px 0;
      text-align: center;
    }
    .navbar-nav .nav-link {
      background-color: rgba(255, 255, 255, 0.7);
      padding: 10px 20px;
      border-radius: 30px;
      transition: all 0.3s ease;
      color: #0d6efd;
      font-weight: 600;
    }
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
      background-color: #0d6efd;
      color: #fff !important;
      transform: translateY(-2px);
    }

    .animated-toggler .bar {
      background-color: #0d6efd;
    }
    .navbar-toggler.open .bar {
      background-color: #ff69b4;
    }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const navbar = document.querySelector('nav.navbar');
  const toggler = document.querySelector('.navbar-toggler');
  const collapse = document.getElementById('adminNav');

  // Scroll background change
  window.addEventListener('scroll', function () {
    if (navbar) {
      navbar.classList.toggle('scrolled', window.scrollY > 50);
    }
  });

  // Add/remove "open" class for toggler on collapse events
  collapse.addEventListener('show.bs.collapse', function () {
    toggler.classList.add('open');
  });

  collapse.addEventListener('hide.bs.collapse', function () {
    toggler.classList.remove('open');
  });
});
</script>
