<?php
session_start();
include('connect.php');

// Agar user login nahi hai
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}

// User info fetch
$user_email = $_SESSION['user_email'];
$user_name = '';
$user_phone = '';

$stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $user = $result->fetch_assoc();
  $user_name = $user['name'];
  $user_phone = $user['phone'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="description" content="Get in touch with Creamy Delight. We’re here to help with your questions, feedback, or bulk order inquiries.">
  <title>Contact Us - Creamy Delight</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
</head>
<body>

<?php include("navbar.php"); ?>
<br><br>

<!-- Header -->
<section class="py-5 text-center" style="background: linear-gradient(to right, #ffd1dc,#d4fc79, #b2f7ef);" data-aos="fade-down">
  <div class="container">
    <h1 class="fw-bold display-4 text-dark">Get in <span style="color:#ff69b4;">Touch</span></h1>
    <p class="lead text-muted">We'd love to hear from you! Send us your thoughts, questions or feedback.</p>
  </div>
</section>

<!-- Contact Form & Info -->
<section class="container py-5">
  <div class="row g-4 align-items-start">
    
    <!-- Contact Form -->
    <div class="col-md-7" data-aos="flip-right">
      <div class="p-4 shadow rounded-4 h-100" style="background-color: #f7fcff;">
        <form action="contact_process.php" method="POST" onsubmit="return validateContactForm()">
          <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Your Name</label>
            <input type="text" name="name" id="name" 
                   class="form-control" 
                   value="<?= htmlspecialchars($user_name) ?>"
                   placeholder="e.g. Ayesha Khan" 
                   oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '')" 
                   required maxlength="30" minlength="3">
          </div>

          <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" name="email" id="email" 
                   class="form-control" 
                   value="<?= htmlspecialchars($user_email) ?>" readonly>
          </div>

          <div class="mb-3">
            <label for="phone" class="form-label fw-semibold">Phone</label>
            <input type="tel" name="phone" id="phone" 
                   class="form-control" 
                   value="<?= htmlspecialchars($user_phone) ?>"
                   placeholder="e.g. 03001234567"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,11)" 
                   minlength="11" maxlength="11"
                   required>
          </div>

          <div class="mb-3">
            <label for="message" class="form-label fw-semibold">Message</label>
            <textarea name="message" id="message" 
                      class="form-control" rows="5" 
                      placeholder="Write your message..." required maxlength="400" minlength="10"></textarea>
          </div>

          <button type="submit" class="btn btn-primary px-4 rounded-pill">
            Send Message <i class="bi bi-send ms-1"></i>
          </button>
        </form>
      </div>
    </div>

    <!-- Contact Info -->
    <div class="col-md-5" data-aos="flip-left">
      <div class="p-4 shadow rounded-4 h-100" style="background-color: #e0f7fa;">
        <h5 class="fw-bold mb-3 text-info">Contact Details</h5>
        <p><i class="bi bi-geo-alt-fill text-danger me-2"></i>123 Ice Cream Street, Lahore, Pakistan</p>
        <p><i class="bi bi-telephone-fill text-success me-2"></i>+92 300 1234567</p>
        <p><i class="bi bi-envelope-fill text-warning me-2"></i>support@creamydelight.pk</p>
        <p><i class="bi bi-clock-fill text-primary me-2"></i>Mon - Sun: 11AM to 11PM</p>
      </div>
    </div>

  </div>
</section>

<!-- Google Map -->
<section class="py-5" data-aos="fade-up">
  <div class="container-fluid px-0">
    <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13611.506000445327!2d74.3560254!3d31.5203699!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3919058db121b899%3A0x6e8f6e41a29499db!2sLahore%2C%20Pakistan!5e0!3m2!1sen!2s!4v1700000000000"
      width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
  </div>
</section>

<?php include("footer.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
AOS.init({ duration: 1000 });

function validateContactForm() {
  const name = document.getElementById('name').value.trim();
  const phone = document.getElementById('phone').value.trim();
  const message = document.getElementById('message').value.trim();

  if (name.length < 3) {
    alert("⚠️ Name must be at least 3 characters.");
    return false;
  }
  if (phone.length !== 11 || !/^[0-9]+$/.test(phone)) {
    alert("⚠️ Phone must be exactly 11 digits.");
    return false;
  }
  if (message.length < 10 || message.length > 400) {
    alert("⚠️ Message must be between 10 and 400 characters.");
    return false;
  }
  return true;
}
</script>
</body>
</html>
