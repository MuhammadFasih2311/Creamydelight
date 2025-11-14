<?php
session_start();
include('connect.php');

// Restrict access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit;
}

// Pagination
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filters
$where = "WHERE 1";
$params = [];

if (!empty($_GET['customer_name'])) {
    $where .= " AND customer_name LIKE ?";
    $params[] = "%" . $_GET['customer_name'] . "%";
}
if (!empty($_GET['order_id'])) {
    $where .= " AND id = ?";
    $params[] = $_GET['order_id'];
}
if (!empty($_GET['status'])) {
    $where .= " AND status = ?";
    $params[] = $_GET['status'];
}
if (!empty($_GET['date'])) {
    $where .= " AND DATE(created_at) = ?";
    $params[] = $_GET['date'];
}

// Count total for pagination
$countStmt = $conn->prepare("SELECT COUNT(*) as total FROM orders $where");
if ($params) $countStmt->bind_param(str_repeat("s", count($params)), ...$params);
$countStmt->execute();
$totalResult = $countStmt->get_result()->fetch_assoc();
$total_orders = $totalResult['total'];
$countStmt->close();

$total_pages = ceil($total_orders / $limit);

// Fetch orders
$sql = "SELECT * FROM orders $where ORDER BY created_at DESC LIMIT ?, ?";
$params[] = $offset;
$params[] = $limit;

$stmt = $conn->prepare($sql);
$types = str_repeat("s", count($params) - 2) . "ii";
$stmt->bind_param($types, ...$params);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Orders - Creamy Delight</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
       background: linear-gradient(to right, #fce4ec, #e0f7fa);
      font-family: 'Segoe UI', sans-serif;
    }
    .order-card {
      background: #ffffff;
      box-shadow: 0 8px 30px rgba(0,0,0,0.08);
      border-left: 5px solid #0d6efd;
      border-radius: 15px;
      transition: 0.3s;
    }
    .order-card:hover {
      transform: scale(1.01);
      box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .pagination .page-link {
      border-radius: 10px;
    }
    .form-select, .form-control {
      border-radius: 10px;
    }
    .btn-outline-primary {
      transition: all 0.3s ease;
    }
    .btn-outline-primary:hover {
      background-color: #0d6efd;
      color: white;
    }
    .badge-status {
      font-size: 0.8rem;
    }
    .admin-footer {
  background: linear-gradient(to right, #ffd1dc, #a0e7e5);
  box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
  transition: all 0.4s ease;
}



  </style>
</head>
<body class="d-flex flex-column min-vh-100">


<?php include('admin-navbar.php'); ?>

<div class="container py-5 mt-5 flex-grow-1">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-info" data-aos="fade-down">📦 Orders</h2>
    <button class="btn btn-dark" onclick="window.print()" data-aos="fade-left"><i class="bi bi-printer"></i> Print</button>
  </div>

  <form method="get" class="row g-3 mb-4">
    <div class="col-md-2" data-aos="zoom-in"><input name="customer_name" value="<?= $_GET['customer_name'] ?? '' ?>" class="form-control" placeholder="Customer Name"></div>
    <div class="col-md-2" data-aos="zoom-in" data-aos-delay="100"><input name="order_id" value="<?= $_GET['order_id'] ?? '' ?>" class="form-control" placeholder="Order ID"></div>
    <div class="col-md-2" data-aos="zoom-in" data-aos-delay="200"><input type="date" name="date" value="<?= $_GET['date'] ?? '' ?>" class="form-control"></div>
    <div class="col-md-2" data-aos="zoom-in" data-aos-delay="300">
      <select name="status" class="form-select">
        <option value="">All Status</option>
        <?php foreach (["Pending", "Shipped", "Delivered"] as $s): ?>
          <option <?= (($_GET['status'] ?? '') === $s) ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2" data-aos="zoom-in" data-aos-delay="400"><button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i> Filter</button></div>
    <div class="col-md-2" data-aos="zoom-in" data-aos-delay="500"><a href="admin-orders.php" class="btn btn-outline-secondary w-100">Reset</a></div>
  </form>

  <?php while ($order = $orders->fetch_assoc()): ?>
    <div class="card mb-4 p-3 order-card" data-aos="fade-up">
      <div class="d-flex justify-content-between">
        <div>
          <h5 class="fw-bold mb-1">Order #<?= $order['id'] ?> - <?= $order['customer_name'] ?></h5>
          <p class="mb-0 text-muted"><i class="bi bi-telephone"></i> <?= $order['phone'] ?> | <i class="bi bi-envelope"></i> <?= $order['user_email'] ?> | <i class="bi bi-geo-alt"></i> <?= $order['address'] ?></p>
          <small class="text-muted">Ordered at: <?= $order['created_at'] ?></small>
        </div>
        <div>
          <form class="status-form" data-id="<?= $order['id'] ?>">
            <select name="status" class="form-select form-select-sm status-select">
              <option value="Pending" <?= ($order['status'] === 'Pending') ? 'selected' : '' ?>>Pending</option>
              <option value="Shipped" <?= ($order['status'] === 'Shipped') ? 'selected' : '' ?>>Shipped</option>
              <option value="Delivered" <?= ($order['status'] === 'Delivered') ? 'selected' : '' ?>>Delivered</option>
            </select>
          </form>
        </div>
      </div>

      <hr>
      <!-- <ul class="list-group list-group-flush">
        <?php
        $order_id = $order['id'];
        $prodQuery = $conn->query("SELECT ci.*, p.name FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.order_id = $order_id");
        while ($item = $prodQuery->fetch_assoc()): ?>
          <li class="list-group-item d-flex justify-content-between">
            <span><?= $item['name'] ?> × <?= $item['quantity'] ?></span>
            <span class="text-muted small"><?= date('Y-m-d H:i', strtotime($item['created_at'])) ?></span>
          </li>
        <?php endwhile; ?>
      </ul> -->

      <div class="mt-3 fw-bold text-end text-success">Total: Rs. <?= number_format($order['total']) ?></div>

      <div class="text-end mt-2">
        <a href="order-details.php?order_id=<?= $order['id'] ?>" class="btn btn-outline-primary btn-sm">
          <i class="bi bi-eye"></i> View Details
        </a>
      </div>
    </div>
  <?php endwhile; ?>

  <nav>
  <ul class="pagination justify-content-center mt-4" data-aos="fade-up">
    <?php if ($page > 1): ?>
      <li class="page-item">
        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">« Prev</a>
      </li>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
      <li class="page-item">
        <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Next »</a>
      </li>
    <?php endif; ?>
  </ul>
</nav>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 1000 });</script>
<script>
document.querySelectorAll('.status-select').forEach(select => {
  select.addEventListener('change', function () {
    const form = this.closest('.status-form');
    const orderId = form.dataset.id;
    const status = this.value;

    const formData = new URLSearchParams();
    formData.append('order_id', orderId);
    formData.append('status', status);

    fetch('update-status.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: formData
    })
    .then(res => res.text())
    .then(message => {
      document.getElementById('toastMsg').innerText = message;
      const toast = new bootstrap.Toast(document.getElementById('toastStatus'));
      toast.show();
    })
    .catch(() => {
      document.getElementById('toastMsg').innerText = "Failed to update status.";
      const toast = new bootstrap.Toast(document.getElementById('toastStatus'));
      toast.show();
    });
  });
});
</script>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
  <div id="toastStatus" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="toastMsg">Status updated!</div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>


<script>
setTimeout(() => {
  document.querySelectorAll('.alert').forEach(alert => {
    alert.classList.remove('show');
    alert.classList.add('fade');
    setTimeout(() => alert.remove(), 500);
  });
}, 4000);
</script>
<?php
include("admin-footer.php");
?>
</body>
</html>
