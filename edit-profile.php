<?php
include('connect.php');
session_start();

if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['user_email'];
$stmt = $conn->prepare("SELECT id, name, email, phone FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    // --- Server-side Validation ---
    if (!preg_match("/^[a-zA-Z\s]{3,30}$/", $name)) {
        $error = "❌ Name must be at least 3 letters long and contain only alphabets!";
    } elseif (!preg_match("/^[0-9]{11}$/", $phone)) {
        $error = "❌ Phone number must be exactly 11 digits!";
    } else {
    $update = $conn->prepare("UPDATE users SET name=?, phone=? WHERE email=?");
    $update->bind_param("sss", $name, $phone, $email);
    if ($update->execute()) {
        $_SESSION['user_name'] = $name; // 🔥 Update navbar name instantly
        $_SESSION['success'] = "✅ Profile updated successfully!";
        header("Location: edit-profile.php");
        exit;
    } else {
        $error = "❌ Update failed, please try again!";
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profile - Creamy Delight</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body { background: linear-gradient(to bottom right,#ffeefb,#e0f7ff); }
    .edit-header {
      background: linear-gradient(to right,#ffd1dc,#c2f0fc);
      border-radius: 1rem;
      text-align:center;
      padding:2rem;
    }
    .edit-card {
      background: #fff;
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
      padding: 2rem;
      max-width: 600px;
      margin: -60px auto 0;
    }
    .alert {
      position: relative;
      transition: opacity 0.4s ease;
    }
    .alert.fade-out { opacity: 0; }
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
  <div class="edit-header" data-aos="zoom-in">
    <h1 class="text-info fw-bold"><i class="bi bi-pencil-square"></i> Edit Profile</h1>
    <p class="text-muted">Update your details 🍦</p>
  </div>

  <div class="edit-card mt-1" data-aos="fade-up">
    <?php if(!empty($error)): ?>
      <div class="alert alert-danger" id="alertBox"><?= $error; ?>
        <button type="button" class="close-btn" onclick="closeAlert()">×</button>
      </div>
    <?php endif; ?>
    <?php if(!empty($_SESSION['success'])): ?>
      <div class="alert alert-success" id="alertBox"><?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="close-btn" onclick="closeAlert()">×</button>
      </div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validateForm()">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" 
               id="name"
               name="name" 
               value="<?= htmlspecialchars($user['name']); ?>" 
               class="form-control" 
               required 
               maxlength="30"
               minlength="3"
               pattern="[A-Za-z\s]{3,30}"
               title="Only alphabets and spaces allowed, minimum 3 letters">
      </div>

      <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" 
               id="phone"
               name="phone" 
               value="<?= htmlspecialchars($user['phone']); ?>" 
               class="form-control" 
               maxlength="11"
               minlength="11"
               pattern="[0-9]{11}"
               title="Enter exactly 11 digits">
      </div>

      <div class="mb-3">
        <label class="form-label">Email (Read Only)</label>
        <input type="email" value="<?= htmlspecialchars($user['email']); ?>" class="form-control" readonly>
      </div>

      <div class="d-flex justify-content-between">
        <a href="profile.php" class="btn btn-outline-secondary rounded-pill">Cancel</a>
        <button type="submit" class="btn btn-primary rounded-pill">💾 Save Changes</button>
      </div>
    </form>
  </div>
</div>

<?php include('footer.php'); ?>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 1000 });

// Auto-hide alert after 5 seconds
function closeAlert() {
  const alert = document.getElementById("alertBox");
  if (alert) {
    alert.classList.add("fade-out");
    setTimeout(() => alert.remove(), 400);
  }
}
setTimeout(closeAlert, 5000);

// --- Allow only alphabets for name ---
const nameInput = document.getElementById("name");
nameInput.addEventListener("keypress", function(e) {
  if (!/[a-zA-Z\s]/.test(e.key)) e.preventDefault();
});
nameInput.addEventListener("input", function() {
  this.value = this.value.replace(/[^a-zA-Z\s]/g, "").slice(0, 30);
});

// --- Allow only digits for phone ---
const phoneInput = document.getElementById("phone");
phoneInput.addEventListener("keypress", function(e) {
  if (!/[0-9]/.test(e.key)) e.preventDefault();
});
phoneInput.addEventListener("input", function() {
  this.value = this.value.replace(/[^0-9]/g, "").slice(0, 11);
});

// --- Final client-side validation before submit ---
function validateForm() {
  const name = nameInput.value.trim();
  const phone = phoneInput.value.trim();

  if (name.length < 3) {
    alert("❌ Name must be at least 3 letters long!");
    return false;
  }

  if (!/^[a-zA-Z\s]+$/.test(name)) {
    alert("❌ Name can only contain letters and spaces!");
    return false;
  }

  if (phone.length !== 11) {
    alert("❌ Phone number must be exactly 11 digits!");
    return false;
  }

  if (!/^[0-9]+$/.test(phone)) {
    alert("❌ Phone number can only contain digits!");
    return false;
  }

  return true;
}
</script>
</body>
</html>
