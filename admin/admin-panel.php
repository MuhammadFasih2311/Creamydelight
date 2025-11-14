<?php
session_start();
include('connect.php');

// Auto-login with remember_token if session expired
if (!isset($_SESSION['admin_logged_in']) && isset($_COOKIE['admin_remember_token'])) {
    $token = $_COOKIE['admin_remember_token'];
    $stmt = $conn->prepare("SELECT email FROM admins WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $admin['email'];
    } else {
        setcookie('admin_remember_token', '', time() - 3600, "/");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Admin panel to manage customer orders on Creamy Delight. Update statuses and view details efficiently.">
  <title>Admin - Products</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    html, body {
  overflow-x: hidden;
}
    body {
      background: linear-gradient(to right, #fce4ec, #e0f7fa);
    }
    .btn-hover:hover {
      transform: scale(1.05);
      transition: 0.3s ease;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .logout-btn {
      position: absolute;
      top: 15px;
      right: 15px;
    }
    .admin-footer {
  background: linear-gradient(to right, #ffd1dc, #a0e7e5);
  box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
  transition: all 0.4s ease;
}

@media (max-width: 768px) {
  #filter-form {
    flex-wrap: wrap;
  }
}
@media (max-width: 576px) {
  .table td .d-flex {
    flex-wrap: nowrap;
  }
  .table td img {
    width: 50px;
    height: 50px;
  }
}
.table td, .table th {
    font-size: 13px;
    white-space: nowrap; /* keeps price like "Rs. 120" in one line */
  }

  .table td img {
    width: 45px;
    height: 45px;
  }

  /* --- Let description wrap cleanly without breaking layout --- */
  .table td.description {
    white-space: normal;
    max-width: 120px;
    word-wrap: break-word;
    font-size: 12px;
  }
}
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

<?php include('admin-navbar.php'); 
?>
<br>
<?php if (isset($_GET['updated'])): ?>
  <div class="alert alert-success alert-dismissible fade show mt-5" role="alert" id="autoDismissAlert">
    ✅ Product updated successfully!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="container py-5 mt-5">
  <h2 class="mb-5 text-center text-info" data-aos="fade-down">🍨 Manage Ice Cream Products</h2>

  <!-- 🔍 Filter + Add Product (Improved Row) -->
<form id="filter-form" class="row align-items-end g-3 mb-4">
  <div class="col-6 col-sm-3 col-md-2">
    <label class="form-label fw-semibold" data-aos="fade-right" data-aos-delay="200">Name</label>
    <input type="text" id="search-name" name="name" class="form-control"
      placeholder="e.g. Choco" data-aos="zoom-in" maxlength="30">
  </div>

  <div class="col-6 col-sm-3 col-md-2">
    <label class="form-label fw-semibold" data-aos="fade-right" data-aos-delay="100">Flavor</label>
    <input type="text" id="search-flavor" name="flavor" class="form-control"
      placeholder="e.g. Vanilla" data-aos="zoom-in" data-aos-delay="100" maxlength="30">
  </div>

  <div class="col-6 col-sm-3 col-md-2">
    <label class="form-label fw-semibold" data-aos="fade-right">Price</label>
    <input type="number" id="search-price" name="price" class="form-control"
      placeholder="e.g. 250" data-aos="zoom-in" data-aos-delay="200">
  </div>

  <!-- Buttons: Search + Reset -->
  <div class="col-6 col-sm-3 col-md-3 d-flex gap-2 justify-content-start">
    <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-2"
      data-aos="zoom-in" data-aos-delay="300">
      <i class="bi bi-search"></i> Search
    </button>

    <button type="button" id="reset-btn"
      class="btn btn-secondary w-100 d-flex align-items-center justify-content-center gap-2"
      data-aos="zoom-in" data-aos-delay="400">
      <i class="bi bi-arrow-clockwise"></i> Reset
    </button>
  </div>

  <!-- Add Product Button -->
  <div class="col-md-3 text-md-end text-center mt-3 mt-md-0">
    <a href="add-product.php"
      class="btn btn-success px-4 d-flex align-items-center justify-content-center gap-2 mx-auto mx-md-0"
      data-aos="fade-left" data-aos-delay="100">
      <i class="bi bi-plus-circle"></i> Add Product
    </a>
  </div>
</form>


  <!-- Products Table + Pagination -->
  <div id="products-container">
    <div class="table-responsive" data-aos="fade-up" data-aos-delay="200">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-success text-center">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Flavor</th>
            <th>Image</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="product-table-body" class="fade-right">
          <?php
            $limit = 9;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $offset = ($page - 1) * $limit;

            // ✅ Filters
            $name = $_GET['name'] ?? '';
            $flavor = $_GET['flavor'] ?? '';
            $price = $_GET['price'] ?? '';

            $where = "WHERE 1";
            if ($name !== '') $where .= " AND name LIKE '%" . $conn->real_escape_string($name) . "%'";
            if ($flavor !== '') $where .= " AND flavor LIKE '%" . $conn->real_escape_string($flavor) . "%'";
            if ($price !== '') $where .= " AND price = '" . (float)$price . "'";

            // Pagination + filters
            $totalQuery = $conn->query("SELECT COUNT(*) as total FROM products $where");
            $totalRows = $totalQuery->fetch_assoc()['total'];
            $totalPages = ceil($totalRows / $limit);

            $result = $conn->query("SELECT * FROM products $where ORDER BY id DESC LIMIT $limit OFFSET $offset");
            if ($result->num_rows > 0):
              while ($row = $result->fetch_assoc()):
          ?>
            <tr data-aos="fade-right" data-aos-delay="100">
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td>Rs. <?= number_format($row['price']) ?></td>
              <td><?= htmlspecialchars($row['flavor']) ?></td>
              <td><img src="images/<?= htmlspecialchars($row['image']) ?>" width="60" height="60" class="rounded"></td>
              <td class="description"><?= htmlspecialchars(substr($row['description'], 0, 50)) ?>...</td>
              <td class="text-center">
                <div class="d-flex justify-content-center gap-2">
                  <a href="edit-product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="delete-product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?');">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="7" class="text-center text-muted">No products found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <nav>
      <ul class="pagination justify-content-center my-4" id="pagination-links" data-aos="fade-up">
        <?php if ($page > 1): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">« Prev</a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next »</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</div>

<?php include('admin-footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- AOS Animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 1000 });

// Auto-hide alert after 3 seconds
setTimeout(() => {
  const alert = document.getElementById('autoDismissAlert');
  if (alert) {
    const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
    bsAlert.close();
  }
}, 5000);

// =============================
// AJAX Filter + Pagination
// =============================
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('filter-form');
  const container = document.getElementById('products-container');
  const resetBtn = document.getElementById('reset-btn');

  // Handle search submit
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    const params = new URLSearchParams(new FormData(form)).toString();
    loadFilteredData(`admin-panel.php?${params}`);
  });

  // Handle reset
  resetBtn.addEventListener('click', function () {
    form.reset();
    loadFilteredData('admin-panel.php');
  });

  // Handle pagination clicks
  container.addEventListener('click', function (e) {
    if (e.target.tagName === 'A' && e.target.closest('.pagination')) {
      e.preventDefault();
      const url = e.target.getAttribute('href');
      loadFilteredData(url);
    }
  });

  function loadFilteredData(url) {
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(res => res.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContainer = doc.querySelector('#products-container');
        container.innerHTML = newContainer.innerHTML;
        AOS.refresh();
      })
      .catch(err => console.error('Filter load failed:', err));
  }
});
</script>

</body>
</html>
