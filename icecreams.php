<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Browse our wide variety of mouth-watering ice creams. From classic vanilla to exotic mango, find your favorite flavor today.">
  <title>Creamy Delight - Ice Cream Heaven</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<style>
/* Responsive fix for medium screens */
@media (min-width: 768px) and (max-width: 991px) {
  .card-body .d-flex.justify-content-between.align-items-center {
    flex-direction: column;
    gap: 12px;
    align-items: stretch !important;
  }

  .add-to-cart-form {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    flex-wrap: nowrap;
  }

  .add-to-cart-form .input-group {
    flex: 1;
    margin-right: 10px;
    width: auto !important;
  }

  .add-to-cart-form .btn {
    flex-shrink: 0;
  }
}
/* 🔥 Smooth fade-slide animation */
@keyframes fadeSlideUp {
  0% {
    opacity: 0;
    transform: translateX(-30px);
  }
  50% {
    opacity: 0.7;
    transform: translateX(0);
  }
  100% {
    opacity: 1;
    transform: translateY(-10px);
  }
}

.card[data-animated="true"] {
  animation: fadeSlideUp 1s ease forwards;
}
</style>
<body>
<?php 
include('connect.php');
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}
include('navbar.php');
?>
<br><br>
<section class="py-5" style="background: linear-gradient(120deg, #ffd1dc, #d4fc79, #b2f7ef);" data-aos="fade-down" data-aos-delay="300" data-aos-duration="1200">
    <div class="text-center mb-5">
    <h2 class="fw-bold display-5 text-dark">🍦 Our <span style="color:#ff69b4;">Ice Cream </span>Collection</h2>
    <p class="lead text-muted">Browse our full range of sweet, chilled, and colorful delights!</p>
  </div>
</section>

<section class="container py-5">
  <form class="row mb-4" method="GET" action="">
    <div class="col-md-4">
      <label class="form-label fw-semibold" data-aos="fade-right">Filter by Flavor</label>
      <select class="form-select" name="flavor" data-aos="zoom-in">
        <option value="">All Flavors</option>
        <option value="Vanilla" <?= (isset($_GET['flavor']) && $_GET['flavor'] === 'Vanilla') ? 'selected' : '' ?>>Vanilla</option>
        <option value="Chocolate" <?= (isset($_GET['flavor']) && $_GET['flavor'] === 'Chocolate') ? 'selected' : '' ?>>Chocolate</option>
        <option value="Strawberry" <?= (isset($_GET['flavor']) && $_GET['flavor'] === 'Strawberry') ? 'selected' : '' ?>>Strawberry</option>
        <option value="Mango" <?= (isset($_GET['flavor']) && $_GET['flavor'] === 'Mango') ? 'selected' : '' ?>>Mango</option>
        <option value="Butterscotch" <?= (isset($_GET['flavor']) && $_GET['flavor'] === 'Butterscotch') ? 'selected' : '' ?>>Butterscotch</option>
        <option value="Mint" <?= (isset($_GET['flavor']) && $_GET['flavor'] === 'Mint') ? 'selected' : '' ?>>Mint</option>
        <option value="Coffee" <?= (isset($_GET['flavor']) && $_GET['flavor'] === 'Coffee') ? 'selected' : '' ?>>Coffee</option>
        <option value="Berry" <?= (isset($_GET['flavor']) && $_GET['flavor'] === 'Berry') ? 'selected' : '' ?>>Berry</option>
      </select>
    </div>

    <div class="col-md-4" >
      <label class="form-label fw-semibold" data-aos="fade-right" data-aos-delay="100">Sort by Price</label>
      <select class="form-select" name="sort" data-aos="zoom-in" data-aos-delay="100">
        <option value="">Default</option>
        <option value="low" <?= (isset($_GET['sort']) && $_GET['sort'] === 'low') ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="high" <?= (isset($_GET['sort']) && $_GET['sort'] === 'high') ? 'selected' : '' ?>>Price: High to Low</option>
      </select>
    </div>

    <div class="col-md-4 d-flex align-items-end">
      <button type="submit" class="btn btn-info w-100"  data-aos="zoom-in" data-aos-delay="200"><i class="bi bi-funnel-fill"></i> Apply Filters</button>
    </div>
  </form>

  <div class="row g-4">
    <?php
    $baseImagePath = 'images/';
    $defaultImage = 'default.jpg';
    $where = "WHERE 1";
    $order = "";

    if (!empty($_GET['flavor'])) {
  $flavor = $conn->real_escape_string($_GET['flavor']);
  $where .= " AND flavor = '$flavor'";
}
    if (!empty($_GET['sort'])) {
      $sort = $_GET['sort'];
      if ($sort === 'low') {
        $order = "ORDER BY price ASC";
      } elseif ($sort === 'high') {
        $order = "ORDER BY price DESC";
      }
    }

    $query = "SELECT * FROM products $where $order";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()):
      $productName = htmlspecialchars($row['name']);
      $productDesc = htmlspecialchars($row['description']);
      $productPrice = htmlspecialchars($row['price']);
      $productId = (int)$row['id'];
      $imgFile = !empty($row['image']) ? htmlspecialchars($row['image']) : $defaultImage;
      $productImg = $baseImagePath . $imgFile;
    ?>
    <div class="col-12 col-sm-12 col-md-4">
      <div class="card shadow-lg border-0 h-100 rounded-4" data-aos="zoom-in">
        <img src="<?= $productImg ?>" class="card-img-top rounded-top-4" style="height: 350px; object-fit: cover;" alt="<?= $productName ?>">
        <div class="card-body text-center">
          <h5 class="card-title fw-bold text-primary"><?= $productName ?></h5>
          <p class="card-text small text-muted"><?= $productDesc ?></p>
          <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold text-success">Rs. <?= $productPrice ?></span>
            <form class="add-to-cart-form d-flex align-items-center" data-id="<?= $productId ?>">
              <div class="input-group input-group-sm me-2" style="width: 130px;">
                <button type="button" class="btn btn-outline-secondary btn-sm qty-minus">-</button>
                <input type="number" name="qty" class="form-control text-center qty-input" value="1" min="1" max="99">
                <button type="button" class="btn btn-outline-secondary btn-sm qty-plus">+</button>
              </div>
              <button type="submit" class="btn btn-sm btn-outline-primary">Add</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endwhile; ?>

<div class="pagination-container text-center mt-4" data-aos="fade-up">
  <button id="prevPage" class="btn btn-outline-primary btn-sm me-2">Prev</button>
  <span id="pageInfo" class="fw-bold"></span>
  <button id="nextPage" class="btn btn-outline-primary btn-sm ms-2">Next</button>
</div>

  </div>
</section>

<?php include("footer.php"); ?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 1000 });</script>

<script>
function bindCartEvents() {
  document.querySelectorAll('.qty-input').forEach(input => {
    input.oninput = function () {
      let value = parseInt(this.value);
      if (isNaN(value) || value < 1) this.value = 1;
      else if (value > 99) this.value = 99;
    };
  });

  document.querySelectorAll('.qty-minus').forEach(btn => {
    btn.onclick = function () {
      const input = this.parentElement.querySelector('.qty-input');
      let value = parseInt(input.value) || 1;
      if (value > 1) input.value = value - 1;
    };
  });

  document.querySelectorAll('.qty-plus').forEach(btn => {
    btn.onclick = function () {
      const input = this.parentElement.querySelector('.qty-input');
      let value = parseInt(input.value) || 1;
      if (value < 99) input.value = value + 1;
    };
  });

  document.querySelectorAll('.add-to-cart-form').forEach(form => {
    form.onsubmit = function (e) {
      e.preventDefault();
      const productId = this.dataset.id;
      const qty = this.querySelector('.qty-input').value;
      const button = this.querySelector('button[type="submit"]');

      fetch('add-to-cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `product_id=${productId}&qty=${qty}`
      })
      .then(res => res.text())
      .then(cartCount => {
        button.innerHTML = '<i class="bi bi-check-circle-fill"></i> Added';
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-success');
        const cartBadge = document.querySelector('.bi-cart + .badge');
        if (cartBadge) cartBadge.textContent = cartCount;
      })
      .catch(() => {
        button.innerHTML = '<i class="bi bi-exclamation-circle"></i> Error';
        button.classList.add('btn-danger');
      });
    };
  });
}

document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll(".col-12.col-sm-12.col-md-4");
  const cardsPerPage = 9;
  let currentPage = 1;
  const totalPages = Math.ceil(cards.length / cardsPerPage);

  const prevBtn = document.getElementById("prevPage");
  const nextBtn = document.getElementById("nextPage");
  const pageInfo = document.getElementById("pageInfo");

  function showPage(page) {
  const start = (page - 1) * cardsPerPage;
  const end = start + cardsPerPage;

  cards.forEach((card, i) => {
    card.style.display = (i >= start && i < end) ? "block" : "none";
  });

  // ✅ Update pagination info
  pageInfo.textContent = `Page ${page} of ${totalPages}`;
  prevBtn.disabled = (page === 1);
  nextBtn.disabled = (page === totalPages);

  // ✅ Smooth scroll top
  window.scrollTo({ top: 0, behavior: "smooth" });

  // ✅ AOS smooth reload (exact same as My Orders page)
  setTimeout(() => {
    AOS.refreshHard();
  }, 300);

  // ✅ Rebind Add-to-Cart events after page change
  bindCartEvents();
}
  prevBtn?.addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage--;
      showPage(currentPage);
    }
  });

  nextBtn?.addEventListener("click", () => {
    if (currentPage < totalPages) {
      currentPage++;
      showPage(currentPage);
    }
  });

  // Initial load
  showPage(currentPage);
  bindCartEvents();
});

// AOS initial load
window.addEventListener("load", () => {
});
</script>
</body>
</html>
