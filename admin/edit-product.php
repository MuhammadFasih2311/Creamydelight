<?php
session_start();
include('connect.php');

if (!isset($_GET['id'])) {
  header('Location: admin-panel.php');
  exit;
}

$id = (int) $_GET['id'];

// Fetch product
$result = $conn->query("SELECT * FROM products WHERE id = $id") or die("Error fetching product: " . $conn->error);
if ($result->num_rows === 0) {
  echo "Product not found.";
  exit;
}
$product = $result->fetch_assoc();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $conn->real_escape_string($_POST['name']);
  $price = (float) $_POST['price'];
  $description = $conn->real_escape_string($_POST['description']);
  $flavor = $conn->real_escape_string($_POST['flavor']);

  if (!empty($_FILES['image']['name'])) {
    $image = basename($_FILES['image']['name']);
    $uploadPath = 'uploads/' . $image;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
      $query = "UPDATE products 
                SET name='$name', price='$price', description='$description', flavor='$flavor', image='$image' 
                WHERE id=$id";
    } else {
      die("Image upload failed.");
    }
  } else {
    $query = "UPDATE products 
              SET name='$name', price='$price', description='$description', flavor='$flavor' 
              WHERE id=$id";
  }

  $conn->query($query) or die("Update failed: " . $conn->error);

  header('Location: admin-panel.php?updated=1');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Product - Admin</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #fce4ec, #e0f7fa);
      min-height: 100vh;
    }

    .edit-box {
      background: #fff;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      transition: all 0.4s ease;
    }

    .edit-box:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    }

    .btn-hover:hover {
      transform: scale(1.05);
      transition: 0.3s ease;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    .admin-footer {
      background: linear-gradient(to right, #ffd1dc, #a0e7e5);
      box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 576px) {
      .edit-box {
        padding: 1.5rem;
      }
    }

  </style>
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- ✅ Admin Navbar -->
  <?php include('admin-navbar.php'); ?>

  <main class="container py-5 mt-5 flex-grow-1">
    <div class="edit-box mx-auto" style="max-width: 600px;" data-aos="fade-up">
      <h2 class="text-center text-info mb-4" data-aos="zoom-in">✏️ Edit Ice Cream</h2>

      <form method="post" enctype="multipart/form-data">
        <div class="mb-3" data-aos="fade-right">
          <label class="form-label fw-semibold">Name</label>
          <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" class="form-control" required maxlength="30">
        </div>

        <div class="mb-3" data-aos="fade-right" data-aos-delay="100">
          <label class="form-label fw-semibold">Price (Rs.)</label>
          <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" class="form-control" required>
        </div>

        <div class="mb-3" data-aos="fade-right" data-aos-delay="200">
          <label class="form-label fw-semibold">Flavor</label>
          <input type="text" name="flavor" value="<?= htmlspecialchars($product['flavor']) ?>" class="form-control" required maxlength="30">
        </div>

        <div class="mb-3" data-aos="fade-right" data-aos-delay="300">
          <label class="form-label fw-semibold">Description</label>
          <textarea name="description" class="form-control" rows="3" required maxlength="300"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="mb-3" data-aos="fade-right" data-aos-delay="400">
          <label class="form-label fw-semibold">Image (optional)</label>
          <input type="file" name="image" class="form-control" accept="image/*">
          <small class="text-muted">Current: <?= htmlspecialchars($product['image']) ?></small>
          <div class="mt-2">
            <img src="images/<?= htmlspecialchars($product['image']) ?>" width="120" height="120" class="rounded shadow-sm">
          </div>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-success btn-hover px-4" data-aos="zoom-in">
            <i class="bi bi-check-circle me-1"></i> Update Product
          </button>
          <a href="admin-panel.php" class="btn btn-secondary btn-hover ms-2" data-aos="zoom-in" data-aos-delay="100">
            <i class="bi bi-x-circle me-1"></i> Cancel
          </a>
        </div>
      </form>
    </div>
  </main>

  <!-- ✅ Footer -->
  <?php include('admin-footer.php'); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 1000});
  </script>
</body>
</html>
