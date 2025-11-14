<?php
include('connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $password = $_POST['password'];
  $confirm = $_POST['confirm'];

  // Validation
  if (strlen($name) < 3) {
    $error = "⚠️ Name must be at least 3 characters long!";
  } elseif (!preg_match('/^[0-9]{11}$/', $phone)) {
    $error = "⚠️ Please enter a valid 11-digit phone number!";
  } elseif ($password !== $confirm) {
    $error = "⚠️ Passwords do not match!";
  } else {
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $error = "❌ Email or phone already registered!";
    } else {
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $insert = $conn->prepare("INSERT INTO users (name, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())");
      $insert->bind_param("ssss", $name, $email, $phone, $hashed);

      if ($insert->execute()) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_phone'] = $phone;
        header("Location: index.php");
        exit;
      } else {
        $error = "Something went wrong. Please try again.";
      }
      $insert->close();
    }
    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Signup - Creamy Delight</title>
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
      font-family: 'Segoe UI', sans-serif;
      padding: 1rem;
    }
    .signup-card {
      background: #fff;
      border-radius: 1rem;
      padding: 2rem;
      width: 100%;
      max-width: 450px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .signup-card h2 {
      color: #00b894;
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
    .tiny-hint {
      font-size: 0.9rem;
      text-align: left;
    }
  </style>
</head>
<body>

  <div class="signup-card" data-aos="zoom-in">
    <h2 class="mb-4 text-center">🎉 Create Account</h2>

    <?php if(!empty($error)): ?>
      <div class="alert alert-danger" id="alertBox"><?= $error; ?>
        <button type="button" class="close-btn" onclick="closeAlert()">×</button>
      </div>
    <?php endif; ?>

    <form id="signupForm" method="post" action="signup.php">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" id="fullname" class="form-control" placeholder="Your full name" required minlength="3" maxlength="30" pattern="[A-Za-z\s]+">
        <small class="text-muted">Name must be at least 3 letters</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="you@example.com" required maxlength="40" minlength="4">
      </div>

      <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" id="phone" class="form-control" placeholder="03XXXXXXXXX" required pattern="[0-9]{11}" maxlength="11" minlength="11" inputmode="numeric">
        <small class="text-muted">Phone must be exactly 11 digits</small>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input type="password" name="password" id="password1" class="form-control" required minlength="8" maxlength='30'>
          <button type="button" class="btn btn-outline-secondary" id="togglePassword1">
            <i class="bi bi-eye-slash" id="eyeIcon1"></i>
          </button>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <div class="input-group">
          <input type="password" name="confirm" id="password2" class="form-control" required minlength="8" maxlength='30'>
          <button type="button" class="btn btn-outline-secondary" id="togglePassword2">
            <i class="bi bi-eye-slash" id="eyeIcon2"></i>
          </button>
        </div>
      </div>

      <div class="mb-3">
        <small id="passwordHint" class="tiny-hint text-muted"></small>
      </div>

      <button type="submit" class="btn btn-success w-100" id="signupBtn" disabled>Sign Up</button>

      <div class="text-center mt-3">
        <a href="login.php" class="btn btn-link">Already have an account? Login</a>
      </div>
    </form>
  </div>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 1000 });

    // Toggle password visibility
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

    document.getElementById('togglePassword1').onclick = () => togglePassword('password1', 'eyeIcon1');
    document.getElementById('togglePassword2').onclick = () => togglePassword('password2', 'eyeIcon2');

    // Password validation
    const pass1 = document.getElementById('password1');
    const pass2 = document.getElementById('password2');
    const hint = document.getElementById('passwordHint');
    const btn = document.getElementById('signupBtn');

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

    // Allow only alphabets in name
    const nameInput = document.getElementById("fullname");
    nameInput.addEventListener("keypress", function(e) {
      if (!/[a-zA-Z\s]/.test(e.key)) e.preventDefault();
    });
    nameInput.addEventListener("input", function() {
      this.value = this.value.replace(/[^a-zA-Z\s]/g, "");
    });

    // Phone validation: only 11 digits allowed
    const phoneInput = document.getElementById("phone");
    phoneInput.addEventListener("input", function() {
      this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
    });
    // Auto-hide alert after 5 seconds
function closeAlert() {
  const alert = document.getElementById("alertBox");
  if (alert) {
    alert.classList.add("fade-out");
    setTimeout(() => alert.remove(), 400);
  }
}
setTimeout(closeAlert, 8000);
  </script>

</body>
</html>
