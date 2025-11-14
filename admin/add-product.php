<?php
session_start();
include('connect.php');

// Handle product add
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $flavor = $_POST['flavor'];

    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    move_uploaded_file($image_tmp, 'uploads/' . $image);

    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image, flavor) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $name, $price, $description, $image, $flavor);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('✅ Product added successfully!'); window.location.href='admin-panel.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product - Admin</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(to right, #fce4ec, #e0f7fa);
      min-height: 100vh;
    }

    .form-box {
      background: #fff;
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      transition: all 0.4s ease;
    }

    .form-box:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    }

    .btn-hover:hover {
      transform: scale(1.05);
      transition: 0.3s ease;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    @media (max-width: 576px) {
      .form-box {
        padding: 1.5rem;
      }
    }

    .admin-footer {
      background: linear-gradient(to right, #ffd1dc, #a0e7e5);
      box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- ✅ Admin Navbar -->
  <?php include('admin-navbar.php'); ?>

  <main class="container py-5 mt-5 flex-grow-1">
    <div class="form-box mx-auto" style="max-width: 600px;" data-aos="fade-up">
      <h2 class="mb-4 text-center text-info"  data-aos="zoom-in">🍦 Add New Ice Cream</h2>

      <form method="post" enctype="multipart/form-data">
        <div class="mb-3" data-aos="fade-right">
          <label class="form-label fw-semibold">Name</label>
          <input type="text" name="name" class="form-control" placeholder="e.g. Chocolate Delight" required maxlength="30">
        </div>

        <div class="mb-3" data-aos="fade-right" data-aos-delay="100">
          <label class="form-label fw-semibold">Price (Rs.)</label>
          <input type="number" step="0.01" name="price" class="form-control" placeholder="e.g. 250" required>
        </div>

        <div class="mb-3" data-aos="fade-right" data-aos-delay="200">
          <label class="form-label fw-semibold">Description</label>
          <textarea name="description" class="form-control" rows="3" placeholder="Enter short product description..." required maxlength="300"></textarea>
        </div>

        <div class="mb-3"data-aos="fade-right" data-aos-delay="300">
          <label class="form-label fw-semibold">Flavor</label>
          <input type="text" name="flavor" class="form-control" placeholder="e.g. Vanilla, Strawberry" required maxlength="30">
        </div>

        <div class="mb-3" data-aos="fade-right" data-aos-delay="400">
          <label class="form-label fw-semibold">Image</label>
          <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-success btn-hover px-4" data-aos="zoom-in">
            <i class="bi bi-plus-circle me-1"></i> Add Product
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
