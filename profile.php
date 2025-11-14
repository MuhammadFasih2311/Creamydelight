<?php
include('connect.php');
session_start();

if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['user_email'];
$stmt = $conn->prepare("SELECT id, name, email, phone, created_at FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile - Creamy Delight</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body { background: linear-gradient(to bottom right,rgb(255, 231, 249),rgb(213, 249, 253)); }
    .profile-header {
      background: linear-gradient(to right, #ffd1dc, #c2f0fc);
      border-radius: 1rem;
      padding: 2rem;
      text-align: center;
    }
    .profile-card {
      background: #fff;
      border-radius: 1.2rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      padding: 2rem;
      max-width: 600px;
      margin: -60px auto 0;
    }
    .profile-avatar {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      background: #c2f0fc;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 2.5rem;
      color: #007bff;
      margin: -75px auto 15px auto;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
<?php include('navbar.php'); ?>
<br><br>

<div class="container py-5">
  <div class="profile-header" data-aos="zoom-in">
    <h1 class="text-info fw-bold"><i class="bi bi-person-circle"></i> My Profile</h1>
    <p class="text-muted">Manage your Creamy Delight account 🍨</p>
  </div>

  <div class="profile-card text-center mt-1" data-aos="fade-up">
    <div class="profile-avatar"><i class="bi bi-person"></i></div>
    <h4 class="fw-bold"><?= htmlspecialchars($user['name']); ?></h4>
    <p class="text-muted mb-1"><i class="bi bi-envelope"></i> <?= htmlspecialchars($user['email']); ?></p>
    <p class="text-muted mb-1"><i class="bi bi-phone"></i> <?= htmlspecialchars($user['phone'] ?? 'Not added'); ?></p>
    <p class="text-muted"><i class="bi bi-calendar"></i> Joined on <?= date("F d, Y", strtotime($user['created_at'])); ?></p>

    <div class="mt-3 d-flex flex-wrap justify-content-center gap-2">
      <a href="edit-profile.php" class="btn btn-outline-primary rounded-pill"><i class="bi bi-pencil"></i> Edit Profile</a>
      <a href="change-password.php" class="btn btn-outline-danger rounded-pill"><i class="bi bi-lock"></i> Change Password</a>
    </div>
  </div>
</div>

<?php include('footer.php'); ?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script> AOS.init({ duration: 1000 }); </script>
</body>
</html>
