<?php
include('connect.php');
session_start();

if (!isset($_SESSION['reset_email'])) {
  header("Location: login.php");
  exit;
}

$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $new_pass = $_POST['new_password'];
  $confirm = $_POST['confirm_password'];

  // Fetch current password hash
  $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->bind_result($current_hash);
  $stmt->fetch();
  $stmt->close();

  // Check match
  if ($new_pass !== $confirm) {
    $error = "⚠️ Passwords do not match!";
  } elseif (password_verify($new_pass, $current_hash)) {
    $error = "⚠️ New password cannot be the same as your current password!";
  } else {
    $hash = password_hash($new_pass, PASSWORD_DEFAULT);

    $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->bind_param("ss", $hash, $email);

    if ($update->execute()) {
      unset($_SESSION['reset_email']);
      header("Location: login.php?reset=success");
      exit;
    } else {
      $error = "❌ Something went wrong. Please try again.";
    }
    $update->close();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password - Creamy Delight</title>
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

    .card-reset {
      background: #fff;
      border-radius: 1rem;
      padding: 2rem;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card-reset h4 {
      color: #00b894;
      font-weight: bold;
    }

    .form-control:focus {
      box-shadow: 0 0 5px #00b894;
      border-color: #00b894;
    }

    .btn-success {
      background: #00b894;
      border: none;
    }

    .btn-success:hover {
      background: #009b77;
    }

    .btn-link {
      color: #ff69b4;
      text-decoration: none;
    }

    .btn-link:hover {
      text-decoration: underline;
    }

    .eye-wrapper {
      position: relative;
    }

    .eye-btn {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      padding: 0;
      z-index: 10;
      color: #6c757d;
    }

    .tiny-hint {
      font-size: 0.9rem;
      text-align: left;
    }

    @media (max-width: 575.98px) {
      .card-reset {
        padding: 1.5rem 1rem;
      }
    }
  </style>
</head>
<body>

  <div class="card-reset" data-aos="zoom-in">
    <h4 class="text-center mb-3">🔒 Set New Password</h4>

    <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="post">
      <div class="mb-3 eye-wrapper">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" id="new_password" class="form-control" required minlength="8" maxlength='30'>
        <button type="button" class="eye-btn" onclick="togglePassword('new_password', 'eye1')">
          <i class="bi bi-eye-slash" id="eye1"></i>
        </button>
      </div>

      <div class="mb-3 eye-wrapper">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required minlength="8" maxlength='30'>
        <button type="button" class="eye-btn" onclick="togglePassword('confirm_password', 'eye2')">
          <i class="bi bi-eye-slash" id="eye2"></i>
        </button>
      </div>

      <div class="mb-3">
        <small id="passwordHint" class="tiny-hint text-muted"></small>
      </div>

      <button type="submit" class="btn btn-success w-100" id="resetBtn" disabled>Reset Password</button>

      <div class="text-center mt-3">
        <a href="login.php" class="btn btn-link">Back to Login</a>
      </div>
    </form>
  </div>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 1000 });

    function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon = document.getElementById(iconId);
      if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("bi-eye-slash", "bi-eye");
      } else {
        input.type = "password";
        icon.classList.replace("bi-eye", "bi-eye-slash");
      }
    }

    // Live password validation
    const pass1 = document.getElementById("new_password");
    const pass2 = document.getElementById("confirm_password");
    const hint = document.getElementById("passwordHint");
    const btn = document.getElementById("resetBtn");

    function validatePasswords() {
      let okLen = pass1.value.length >= 8;
      let match = pass1.value === pass2.value && okLen;

      if (!okLen) {
        hint.textContent = "❌ Password must be at least 8 characters.";
        hint.className = "tiny-hint text-danger";
      } else if (!match) {
        hint.textContent = "❌ Passwords do not match.";
        hint.className = "tiny-hint text-danger";
      } else {
        hint.textContent = "✅ Passwords match.";
        hint.className = "tiny-hint text-success";
      }

      btn.disabled = !(okLen && match);
    }

    pass1.addEventListener("input", validatePasswords);
    pass2.addEventListener("input", validatePasswords);
  </script>
</body>
</html>
