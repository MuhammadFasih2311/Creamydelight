<?php
session_start();
include('connect.php');

// ========================
// Auto-login if cookie found
// ========================
if (isset($_COOKIE['admin_remember_token'])) {
    $token = $_COOKIE['admin_remember_token'];

    $stmt = $conn->prepare("SELECT id, email FROM admins WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_email'] = $admin['email'];
        header("Location: admin-panel.php");
        exit;
    }
}

// ========================
// Login Process
// ========================
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $stmt = $conn->prepare("SELECT id, email, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_email'] = $admin['email'];

            // ===== Remember Me logic (secure token) =====
            if ($remember) {
                $token = bin2hex(random_bytes(16));
                setcookie('admin_remember_token', $token, time() + (86400 * 30), "/", "", false, true);

                $stmt2 = $conn->prepare("UPDATE admins SET remember_token = ? WHERE id = ?");
                $stmt2->bind_param("si", $token, $admin['id']);
                $stmt2->execute();
            } else {
                setcookie('admin_remember_token', '', time() - 3600, "/");
                $stmt2 = $conn->prepare("UPDATE admins SET remember_token = NULL WHERE id = ?");
                $stmt2->bind_param("i", $admin['id']);
                $stmt2->execute();
            }

            header("Location: admin-panel.php");
            exit;
        } else {
            $error = "❌ Incorrect password!";
        }
    } else {
        $error = "⚠️ Email not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Creamy Delight</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0f7fa, #ffe0f0);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-box {
      background: #fff;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 400px;
    }
    .form-control {
      border-radius: 10px;
    }
    .btn-primary {
      border-radius: 10px;
      transition: 0.3s ease;
    }
    .btn-primary:hover {
      transform: scale(1.03);
    }
    .eye-btn {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      background: transparent;
      border: none;
    }
  </style>
</head>
<body>

<div class="login-box">
  <h3 class="text-center text-primary mb-4"><i class="bi bi-person-lock"></i> Admin Login</h3>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" placeholder="Enter admin email" required>
    </div>

    <div class="mb-3 position-relative">
      <label class="form-label">Password</label>
      <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
      <button type="button" class="eye-btn" onclick="togglePassword()">
        <i class="bi bi-eye-slash" id="eyeIcon"></i>
      </button>
    </div>

    <div class="mb-3 form-check">
      <input type="checkbox" class="form-check-input" name="remember" id="remember">
      <label class="form-check-label" for="remember">Remember Me</label>
    </div>

    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right"></i> Login</button>
  </form>
</div>

<script>
function togglePassword() {
  const pass = document.getElementById('password');
  const icon = document.getElementById('eyeIcon');
  if (pass.type === 'password') {
    pass.type = 'text';
    icon.classList.replace('bi-eye-slash', 'bi-eye');
  } else {
    pass.type = 'password';
    icon.classList.replace('bi-eye', 'bi-eye-slash');
  }
}
</script>

</body>
</html>
