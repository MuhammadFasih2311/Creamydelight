<?php
include('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows == 1) {
    $_SESSION['reset_email'] = $email;
    header("Location: reset-password.php");
    exit;
  } else {
    $error = "❌ Email not found!";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password - Creamy Delight</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #ffd1dc, #b2f7ef, #a0e7e5);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
      font-family: 'Segoe UI', sans-serif;
    }

    .card-forgot {
      background: #fff;
      border-radius: 1rem;
      padding: 2rem;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card-forgot h4 {
      color: #ff69b4;
      font-weight: bold;
    }

    .form-control:focus {
      box-shadow: 0 0 5px #00b894;
      border-color: #00b894;
    }

    .btn-primary {
      background: #00b894;
      border: none;
    }

    .btn-primary:hover {
      background: #009b77;
    }

    .btn-link {
      color: #ff69b4;
      text-decoration: none;
    }

    .btn-link:hover {
      text-decoration: underline;
    }

    @media (max-width: 575.98px) {
      .card-forgot {
        padding: 1.5rem 1rem;
      }
    }
  </style>
</head>
<body>

  <div class="card-forgot" data-aos="zoom-in">
    <h4 class="text-center mb-3">🔐 Forgot Password</h4>
    
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Enter your email</label>
        <input type="email" name="email" class="form-control" placeholder="yourname@example.com" required maxlength='30' minlength="4">
      </div>

      <button type="submit" class="btn btn-primary w-100">Continue</button>

      <div class="text-center mt-3">
        <a href="login.php" class="btn btn-link">Back to Login</a>
      </div>
    </form>
  </div>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>AOS.init({ duration: 1000 });</script>

</body>
</html>
