<?php
session_start();
include('connect.php');

// Restrict access to admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin-login.php");
    exit;
}

// --- FILTERS ---
$nameFilter = isset($_GET['name']) ? trim($_GET['name']) : '';
$emailFilter = isset($_GET['email']) ? trim($_GET['email']) : '';
$dateFilter = isset($_GET['date']) ? trim($_GET['date']) : '';

// Build WHERE conditions
$where = [];
if ($nameFilter !== '') $where[] = "name LIKE '%" . $conn->real_escape_string($nameFilter) . "%'";
if ($emailFilter !== '') $where[] = "email LIKE '%" . $conn->real_escape_string($emailFilter) . "%'";
if ($dateFilter !== '') $where[] = "DATE(created_at) = '" . $conn->real_escape_string($dateFilter) . "'";

$whereSQL = count($where) > 0 ? "WHERE " . implode(' AND ', $where) : '';

// --- Pagination ---
$limit = 8;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Count total rows with filter
$totalResult = $conn->query("SELECT COUNT(*) as total FROM contact_messages $whereSQL");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch messages with filter + pagination
$result = $conn->query("SELECT * FROM contact_messages $whereSQL ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Contact Messages</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background: linear-gradient(to right, #e0f7fa, #fce4ec);
      min-height: 100vh;
    }
    .message-box {
      background: white;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .admin-footer {
      background: linear-gradient(to right, #ffd1dc, #a0e7e5);
      box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
      transition: all 0.4s ease;
    }
    .filter-box {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 1rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    
/* 🌈 Responsive & neat table styles */
.table th, .table td {
  vertical-align: middle;
  text-align: center;
  font-size: 14px;
  white-space: nowrap;
}

.table td.message {
  white-space: normal !important;
  word-wrap: break-word;
  max-width: 250px;
  text-align: left;
  font-size: 13px;
}

@media (max-width: 992px) {
  .table td.message {
    max-width: 200px;
    font-size: 12px;
  }
}
@media (max-width: 768px) {
  .table th, .table td {
    font-size: 12px;
    padding: 6px;
  }
  .table td.message {
    max-width: 160px;
  }
}
@media (max-width: 576px) {
  .table th, .table td {
    font-size: 11px;
  }
  .table td.message {
    max-width: 130px;
  }
}

  </style>
</head>
<body class="d-flex flex-column min-vh-100">

<?php include('admin-navbar.php'); ?>

<div class="d-flex flex-column min-vh-100">
  <main class="flex-grow-1">
    <div class="container py-5">
      <div class="message-box mx-auto" style="max-width: 1100px;" data-aos-duration="1000">
        <h2 class="text-center text-info mb-4" data-aos="fade-down">📬 Contact Messages</h2>

            <!-- ✅ FILTER FORM -->
    <form method="GET" class="filter-box" data-aos="fade-up" data-aos-delay="100">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label class="form-label fw-semibold">Name</label>
          <input type="text" name="name" id="name" class="form-control" placeholder="Search by name" value="<?= htmlspecialchars($nameFilter) ?>" maxlength="30"
           oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')">
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Email</label>
          <input type="text" name="email" class="form-control" placeholder="Search by email" value="<?= htmlspecialchars($emailFilter) ?>" maxlength="30">
        </div>

        <div class="col-md-3">
          <label class="form-label fw-semibold">Date</label>
          <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($dateFilter) ?>">
        </div>

        <div class="col-md-1 text-center d-flex gap-2">
          <button type="submit" class="btn btn-info w-100">
            <i class="bi bi-search"></i>
          </button>

          <?php if ($nameFilter || $emailFilter || $dateFilter): ?>
            <a href="admin-messages.php" class="btn btn-outline-secondary w-100" title="Clear Filters">
              <i class="bi bi-x-circle"></i>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </form>
        <!-- ✅ MESSAGE TABLE -->
        <?php if ($result->num_rows > 0): ?>
          <div class="table-responsive" data-aos="fade-up" data-aos-delay="200">
            <table class="table table-bordered table-hover align-middle">
              <thead class="table-primary text-center">
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Message</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php $delay = 0; while ($row = $result->fetch_assoc()): $delay += 100; ?>
                <tr data-aos="fade-right" data-aos-delay="<?= $delay ?>">
                  <td class="text-center"><?= $row['id'] ?></td>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                  <td><?= date('d M Y - h:i A', strtotime($row['created_at'])) ?></td>
                  <td class="text-center">
                    <a href="delete-message.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('Are you sure you want to delete this message?');">
                      <i class="bi bi-trash-fill"></i> Delete
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="alert alert-warning text-center" data-aos="zoom-in" data-aos-delay="300">📭 No contact messages found.</div>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <!-- ✅ Pagination -->
  <nav>
    <ul class="pagination justify-content-center mb-5" data-aos="fade-up">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page - 1])) ?>">« Prev</a>
        </li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
          <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $totalPages): ?>
        <li class="page-item">
          <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $page + 1])) ?>">Next »</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>

  <?php include('admin-footer.php'); ?>
</div>

<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });
</script>
</body>
</html>
