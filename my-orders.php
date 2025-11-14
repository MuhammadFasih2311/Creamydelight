<?php
include('connect.php');
session_start();
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['user_email'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_email = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $email);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="View your past ice cream orders with Creamy Delight. Track delivery and reorder your favorites.">
  <title>My Orders - Creamy Delight</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      background: linear-gradient(to bottom right, rgb(255, 231, 249), rgb(213, 249, 253));
    }
    .order-card {
      border-radius: 1.2rem;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease;
    }
    .order-card:hover {
      transform: translateY(-4px);
    }
    .order-header {
      background: linear-gradient(to right, #ffd1dc, #c2f0fc);
      border-radius: 1rem;
      padding: 2rem;
    }
  </style>
</head>
<body>
<?php include('navbar.php'); ?>
<br><br>

<div class="container py-5">
  <div class="order-header text-center mb-5" data-aos="zoom-in">
    <h1 class="text-info fw-bold"><i class="bi bi-bag-check-fill"></i> My Orders</h1>
    <p class="text-muted">Track your delicious deliveries 🍨</p>
  </div>

  <?php if ($orders->num_rows > 0): ?>
    <div id="orders-container">
      <?php while ($order = $orders->fetch_assoc()): ?>
        <div class="card order-card mb-4" data-aos="fade-up">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <div>
                <h5 class="card-title mb-1">
                  Order #<?= $order['id'] ?> 
                  <span class="badge bg-secondary"><?= htmlspecialchars($order['status']) ?></span>
                </h5>
                <p class="mb-0 text-muted small">Placed on <?= date('F d, Y', strtotime($order['created_at'])) ?></p>
                <p class="mb-0 text-success fw-semibold">Total: Rs. <?= number_format($order['total']) ?></p>
              </div>
              <a href="order-detail.php?order_id=<?= $order['id'] ?>" class="btn btn-outline-primary rounded-pill mt-2 mt-sm-0">
                View Details
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <!-- ✅ Pagination section -->
    <div id="pagination-container" class="text-center mt-4" data-aos="fade-up"></div>

  <?php else: ?>
    <div class="alert alert-info text-center" data-aos="fade-up">
      <i class="bi bi-info-circle"></i> You haven't placed any orders yet.
      <br>
      <a href="icecreams.php" class="btn btn-sm btn-primary mt-3">Explore Ice Creams</a>
    </div>
  <?php endif; ?>
</div>

<?php include('footer.php'); ?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 1000 });

document.addEventListener("DOMContentLoaded", () => {
  const cardsPerPage = 6; // Show 6 orders per page
  let currentPage = 1;

  const orders = document.querySelectorAll(".order-card");
  const totalPages = Math.ceil(orders.length / cardsPerPage);
  const paginationContainer = document.getElementById("pagination-container");

  function showPage(page) {
    const start = (page - 1) * cardsPerPage;
    const end = start + cardsPerPage;

    orders.forEach((order, index) => {
      order.style.display = (index >= start && index < end) ? "block" : "none";
    });

    // Update pagination info
    paginationContainer.innerHTML = `
      <button id="prevPage" class="btn btn-outline-primary me-2" ${page === 1 ? "disabled" : ""}>Prev</button>
      <span id="pageInfo">Page ${page} of ${totalPages}</span>
      <button id="nextPage" class="btn btn-outline-primary ms-2" ${page === totalPages ? "disabled" : ""}>Next</button>
    `;

    // ✅ Smooth scroll & refresh animations
    window.scrollTo({ top: 0, behavior: "smooth" });
    setTimeout(() => AOS.refreshHard(), 300);

    // ✅ Rebind navigation buttons
    document.getElementById("prevPage")?.addEventListener("click", () => {
      if (currentPage > 1) {
        currentPage--;
        showPage(currentPage);
      }
    });
    document.getElementById("nextPage")?.addEventListener("click", () => {
      if (currentPage < totalPages) {
        currentPage++;
        showPage(currentPage);
      }
    });
  }

  if (orders.length > cardsPerPage) {
    showPage(currentPage);
  } else {
    orders.forEach(order => (order.style.display = "block"));
  }
});
</script>
</body>
</html>
