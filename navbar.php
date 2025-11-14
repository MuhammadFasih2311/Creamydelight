<?php 
$currentPage = basename($_SERVER['PHP_SELF']); 
?>

<style>
/* === NAV STYLING SAME AS BEFORE === */
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
.nav-link:hover .nav-underline {
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
  background-color: #0d6efd;
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
.navbar-toggler[aria-expanded="true"] .bar {
  background-color: #ff69b4;
}
.navbar-nav .nav-link {
  transition: all 0.3s ease;
  border-radius: 20px;
  padding: 8px 15px;
  color: rgb(0, 0, 0);
}
.navbar-nav .nav-link:hover {
  background-color: rgba(255, 255, 255, 0.3);
  border-radius: 30px;
  color: black !important;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}
.navbar-nav .nav-link.active {
  background-color: rgba(255, 255, 255, 0.3);
  border-radius: 30px;
  color: black !important;
}

/* USER DROPDOWN */
#userDropdown {
  transition: all 0.3s ease-in-out;
}
#userDropdown:hover {
  background-color: rgba(255, 255, 255, 0.4);
  color: black !important;
  transform: translateY(-4px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
.dropdown-arrow {
  transition: transform 0.3s ease;
  font-size: 0.8rem;
}

/* DROPDOWN MENU */
.dropdown-menu .dropdown-item {
  text-align: center;
  border-radius: 30px;
  padding: 10px 20px;
  color: #0d6efd;
  font-weight: 600;
  transition: all 0.3s ease;
}
.dropdown-menu .dropdown-item:hover,
.dropdown-menu .dropdown-item.active {
  background-color: rgba(255, 255, 255, 0.3);
  color: black;
  transform: translateY(-2px);
}

/* MOBILE MENU */
@media (max-width: 1199px) {
  .navbar-collapse {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  background: linear-gradient(to bottom right, #ffd1dc, #b2f7ef);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  z-index: 999;
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.3s ease;
}

/* ✅ When open */
.navbar-collapse.show {
  opacity: 1 !important;
  visibility: visible !important;
  transform: translateY(0);
  max-height: 80vh !important; /* Scrollable menu area */
  overflow-y: auto !important;
  backdrop-filter: blur(10px);
}

  .navbar-nav .nav-item {
    margin: 10px 0;
    text-align: center;
  }
  .navbar-nav .nav-link {
    background-color: rgba(255, 255, 255, 0.3);
    padding: 10px 20px;
    border-radius: 30px;
    color: rgb(13, 57, 253);
    font-weight: 600;
  }
  .dropdown-menu {
    position: static !important;
    background: transparent;
    border: none;
    box-shadow: none;
    padding: 0;
    margin: 0 auto;
    width: fit-content;
  }
  .dropdown-menu .dropdown-item {
    background-color: rgba(255, 255, 255, 0.3);
    border-radius: 30px;
    margin: 8px auto;
    font-weight: 600;
    color: #0d6efd;
    padding: 10px 20px;
    width: fit-content;
    text-align: center;
    transition: all 0.3s ease;
  }
  .dropdown-menu .dropdown-item:hover {
    background-color: rgba(255, 255, 255, 0.5);
    color: black;
    transform: translateY(-2px);
  }
  .dropdown-menu .dropdown-header {
    text-align: center;
    color: #0d6efd;
    font-weight: 600;
  }
}
.navbar-collapse.show {
  backdrop-filter: blur(10px);
}
@media (max-width: 1199px) {
  .navbar-collapse.show {
    max-height: 80vh !important;
    overflow-y: auto !important;
    overscroll-behavior: contain; /* Prevents parent (body) scroll */
  }
}

</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm px-3"
 style="background: linear-gradient(to right, #ffd1dc, #a0e7e5);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php" style="color: #ff69b4; font-size: 1.5rem;" data-aos="fade-right">
      <img src="images/logo.png" alt="" width="50px">
      <span style="color: #0d6efd;">Creamy</span> Delight
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" data-aos="fade-down">
      <span class="animated-toggler">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item" data-aos="fade-down" data-aos-delay="50"><a href="index.php" class="nav-link fw-semibold px-3 position-relative <?= ($currentPage == 'index.php') ? 'active' : '' ?>">Home <span class="nav-underline"></span></a></li>
        <li class="nav-item" data-aos="fade-down" data-aos-delay="100"><a href="about.php" class="nav-link fw-semibold px-3 position-relative <?= ($currentPage == 'about.php') ? 'active' : '' ?>">About Us <span class="nav-underline"></span></a></li>
        <li class="nav-item" data-aos="fade-down" data-aos-delay="150"><a href="icecreams.php" class="nav-link fw-semibold px-3 position-relative <?= ($currentPage == 'icecreams.php') ? 'active' : '' ?>">Our Ice Creams <span class="nav-underline"></span></a></li>
        <li class="nav-item" data-aos="fade-down" data-aos-delay="200"><a href="contact.php" class="nav-link fw-semibold px-3 position-relative <?= ($currentPage == 'contact.php') ? 'active' : '' ?>">Contact Us <span class="nav-underline"></span></a></li>
        <li class="nav-item" data-aos="fade-down" data-aos-delay="250"><a href="checkout.php" class="nav-link fw-semibold px-3 position-relative <?= ($currentPage == 'checkout.php') ? 'active' : '' ?>">Buy Now <span class="nav-underline"></span></a></li>

        <!-- ✅ Cart Active -->
        <li class="nav-item" data-aos="fade-down" data-aos-delay="300">
          <a href="cart.php" class="nav-link position-relative <?= ($currentPage == 'cart.php') ? 'active' : '' ?>">
            <i class="bi bi-cart"></i>
            <span class="badge bg-danger" id="cart-count">
              <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
            </span>
          </a>
        </li>

        <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in']): ?>
        <li class="nav-item dropdown">
          <a class="nav-link d-flex justify-content-center align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm"
             href="#" id="userDropdown" role="button"
             data-bs-toggle="dropdown" aria-expanded="false"
             aria-label="User menu dropdown"
             style="font-weight: 600; color:rgb(0, 0, 0);">
            <i class="bi bi-person-circle fs-5" data-aos="fade-down" data-aos-delay="400"></i>
            <span class="d-none d-md-inline" data-aos="fade-down" data-aos-delay="400">Hi,</span>
            <strong class="text-truncate" id="navbarUsername" data-aos="fade-down" data-aos-delay="400"><?= htmlspecialchars($_SESSION['user_name']) ?></strong>
            <i class="bi bi-chevron-down dropdown-arrow" data-aos="fade-down" data-aos-delay="400"></i>
          </a>

          <ul class="dropdown-menu dropdown-menu-end shadow rounded-4 mt-2" aria-labelledby="userDropdown">
            <li><h6 class="nav-item dropdown-header text-muted text-center">
              👋 Welcome, <strong id="dropdownUsername"><?= htmlspecialchars($_SESSION['user_name']) ?></strong>!
            </h6></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item <?= ($currentPage == 'profile.php') ? 'active' : '' ?>" href="profile.php"><i class="bi bi-person me-2 text-primary"></i>Profile</a></li>
            <li><a class="dropdown-item <?= ($currentPage == 'my-orders.php') ? 'active' : '' ?>" href="my-orders.php"><i class="bi bi-receipt-cutoff me-2 text-success"></i>My Orders</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
          </ul>
        </li>
        <?php else: ?>
        <li class="nav-item">
          <a href="login.php" class="btn btn-sm btn-outline-primary ms-2 rounded-pill px-3">Login</a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const navbarCollapse = document.getElementById('navbarNav');
  const navbarToggler = document.querySelector('.navbar-toggler');

  function lockBodyScroll(lock) {
    if (lock) {
      document.body.style.position = 'fixed';
      document.body.style.top = `-${window.scrollY}px`;
      document.body.style.width = '100%';
    } else {
      const scrollY = document.body.style.top;
      document.body.style.position = '';
      document.body.style.top = '';
      document.body.style.width = '';
      window.scrollTo(0, parseInt(scrollY || '0') * -1);
    }
  }

  // ✅ Navbar toggle open/close behavior + AOS refresh
  navbarToggler.addEventListener('click', function () {
    const willOpen = !navbarCollapse.classList.contains('show');

    setTimeout(() => {
      const isOpen = navbarCollapse.classList.contains('show');
      if (isOpen) {
        lockBodyScroll(true);
      } else {
        lockBodyScroll(false);
      }

      // ✅ Smoothly refresh AOS animations after menu toggle
      setTimeout(() => {
        if (typeof AOS !== "undefined") {
          AOS.refreshHard();
        }
      }, 300);
    }, 350); // Bootstrap animation delay
  });

  // ✅ When user clicks any menu link → close navbar + unlock scroll + refresh AOS
  document.querySelectorAll('.navbar-collapse a').forEach(link => {
    link.addEventListener('click', () => {
      lockBodyScroll(false);

      // Re-run AOS animations when navigating within same page
      setTimeout(() => {
        if (typeof AOS !== "undefined") {
          AOS.refreshHard();
        }
      }, 400);
    });
  });
});
</script>
