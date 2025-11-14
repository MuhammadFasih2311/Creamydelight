<?php
include('connect.php');
session_start();

if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['user_email'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if (!password_verify($current, $res['password'])) {
        $error = "❌ Current password is incorrect!";
    } elseif (strlen($new) < 8) {
        $error = "⚠️ New password must be at least 8 characters.";
    } elseif ($new === $current) {
        $error = "⚠️ New password cannot be the same as your old password!";
    } elseif ($new !== $confirm) {
        $error = "⚠️ Passwords do not match!";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $update->bind_param("ss", $hash, $email);
        if ($update->execute()) {
            $_SESSION['success'] = "✅ Password changed successfully!";
            header("Location: change-password.php");
            exit;
        } else {
            $error = "❌ Something went wrong!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password - Creamy Delight</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body {
      background: linear-gradient(to bottom right,#ffeefb,#e0f7ff);
    }
    .pw-header {
      background: linear-gradient(to right,#ffd1dc,#c2f0fc);
      border-radius: 1rem;
      text-align:center;
      padding:2rem;
    }
    .pw-card {
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      padding: 2rem;
      max-width: 600px;
      margin: -60px auto 0;
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
      color: #6c757d;
    }
    .tiny-hint {
      font-size: 0.9rem;
      text-align: left;
    }
    .alert {
      position: relative;
      transition: opacity 0.4s ease;
    }
    .alert.fade-out {
      opacity: 0;
    }
    .alert button.close-btn {
      position: absolute;
      top: 6px;
      right: 10px;
      border: none;
      background: transparent;
      font-size: 1.9rem;
      color: #333;
      cursor: pointer;
    }
  </style>
</head>
<body>
<?php include('navbar.php'); ?>
<br><br>

<div class="container py-5">
  <div class="pw-header" data-aos="zoom-in">
    <h1 class="text-info fw-bold"><i class="bi bi-lock"></i> Change Password</h1>
    <p class="text-muted">Keep your account secure 🍨</p>
  </div>
<br>
  <div class="pw-card" data-aos="fade-up">
    <?php if(!empty($error)): ?>
      <div class="alert alert-danger alert-dismissible" id="alertBox">
        <?= $error; ?>
        <button type="button" class="close-btn" onclick="closeAlert()">×</button>
      </div>
    <?php endif; ?>
    <?php if(!empty($_SESSION['success'])): ?>
      <div class="alert alert-success alert-dismissible" id="alertBox">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="close-btn" onclick="closeAlert()">×</button>
      </div>
    <?php endif; ?>

    <form method="POST" id="pwForm">
      <div class="mb-3 eye-wrapper">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" id="current_password" class="form-control" required maxlength='30' minlength="8">
        <button type="button" class="eye-btn" onclick="togglePassword('current_password','eye1')">
          <i class="bi bi-eye-slash" id="eye1"></i>
        </button>
      </div>

      <div class="mb-3 eye-wrapper">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" id="new_password" class="form-control" required maxlength='30' minlength="8">
        <button type="button" class="eye-btn" onclick="togglePassword('new_password','eye2')">
          <i class="bi bi-eye-slash" id="eye2"></i>
        </button>
      </div>

      <div class="mb-3 eye-wrapper">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required minlength="8" maxlength="30">
        <button type="button" class="eye-btn" onclick="togglePassword('confirm_password','eye3')">
          <i class="bi bi-eye-slash" id="eye3"></i>
        </button>
      </div>

      <div class="mb-3">
        <small id="passwordHint" class="tiny-hint text-muted"></small>
      </div>

      <div class="d-flex justify-content-between">
        <a href="profile.php" class="btn btn-outline-secondary rounded-pill">Cancel</a>
        <button type="submit" class="btn btn-primary rounded-pill" id="updateBtn" disabled>🔒 Update Password</button>
      </div>
    </form>
  </div>
</div>

<?php include('footer.php'); ?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 1000 });

// show/hide password toggle
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

// live password validation
const newPass = document.getElementById("new_password");
const confirmPass = document.getElementById("confirm_password");
const currentPass = document.getElementById("current_password");
const hint = document.getElementById("passwordHint");
const btn = document.getElementById("updateBtn");

function validatePasswords() {
  const lenOk = newPass.value.length >= 8;
  const match = newPass.value === confirmPass.value;
  const sameAsOld = newPass.value && currentPass.value && newPass.value === currentPass.value;

  if (sameAsOld) {
    hint.textContent = "⚠️ This is your old password — please choose a new one.";
    hint.className = "tiny-hint text-danger";
  } else if (!lenOk) {
    hint.textContent = "❌ Password must be at least 8 characters.";
    hint.className = "tiny-hint text-danger";
  } else if (!match) {
    hint.textContent = "❌ Passwords do not match.";
    hint.className = "tiny-hint text-danger";
  } else {
    hint.textContent = "✅ Passwords match.";
    hint.className = "tiny-hint text-success";
  }

  btn.disabled = !(lenOk && match && !sameAsOld);
}

newPass.addEventListener("input", validatePasswords);
confirmPass.addEventListener("input", validatePasswords);
currentPass.addEventListener("input", validatePasswords);

// auto-hide alert after 5 seconds
function closeAlert() {
  const alert = document.getElementById("alertBox");
  if (alert) {
    alert.classList.add("fade-out");
    setTimeout(() => alert.remove(), 400);
  }
}

setTimeout(closeAlert, 5000);
</script>
</body>
</html>
