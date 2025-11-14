<?php
include('connect.php');
session_start();

// pehle se define kar dete hain
$remembered_email = '';
$remembered_pass  = '';
$remember_checked = '';

// Agar cookie me token hai, toh auto login
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $conn->prepare("SELECT id, name, email FROM users WHERE remember_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_name'] = $row['name'];
        $_SESSION['user_email'] = $row['email'];
        header("Location: index.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;

            if ($remember) {
    $token = bin2hex(random_bytes(16));
    setcookie("remember_token", $token, time() + (86400 * 30), "/", "", false, true);

    $stmt2 = $conn->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
    $stmt2->bind_param("si", $token, $id);
    $stmt2->execute();

    $remember_checked = 'checked'; // <- yaha tick remember ho jayega
}


            header("Location: index.php");
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
  <title>Login - Creamy Delight</title>
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

    .login-card {
      background: #fff;
      border-radius: 1rem;
      padding: 2rem;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .login-card h2 {
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

    .input-group .btn {
      border: 1px solid #ced4da;
      border-left: none;
    }

    @media (max-width: 575.98px) {
      .login-card {
        padding: 1.5rem 1rem;
      }
    }
  </style>
</head>
<body>

  <div class="login-card" data-aos="zoom-in">
    <h2 class="mb-4 text-center">🍦 User Login</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($remembered_email) ?>" required maxlength='30' minlength="4">
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <input type="password" name="password" id="password" class="form-control" value="<?= htmlspecialchars($remembered_pass) ?>" required maxlength='30' minlength="8">
          <button type="button" class="btn btn-outline-secondary" id="togglePassword">
            <i class="bi bi-eye-slash" id="eyeIcon"></i>
          </button>
        </div>
      </div>
      <div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="remember" id="remember" 
          <?= $remember_checked ? 'checked' : 'checked' ?>>
    <label class="form-check-label" for="remember">
      Remember Me
    </label>
  </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>

      <div class="text-center mt-3">
        <a href="signup.php" class="btn btn-link">Don’t have an account? Signup</a>
      </div>

      <div class="text-center mt-2">
        <a href="forgot-password.php" class="btn btn-link">Forgot Password?</a>
      </div>
    </form>
  </div>

  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 1000 });

    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordInput = document.getElementById('password');
      const eyeIcon = document.getElementById('eyeIcon');

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
      } else {
        passwordInput.type = 'password';
        eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
      }
    });
  </script>

</body>
</html>
