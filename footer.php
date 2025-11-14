<div class="footer-wrapper">
  <footer style="background: linear-gradient(to right, #ffd1dc, #b2f7ef);" class="text-dark pt-4 pb-3">
  <div class="container">
    <div class="row g-4">
      
      <!-- Brand Section (Animated) -->
      <div class="col-lg-4 mb-4" data-aos="fade-right" data-aos-delay="100">
        <div class="footer-brand">
          <h2 class="fw-bold mb-3">
            <img src="images\logo.png" alt="" width="45px">
            <span class="cream">Creamy</span>
            <span class="text-primary"> Delight</span>
          </h2>
          <p class="mb-3">Scoops of joy and colorful treats. Your favorite ice cream spot!</p>
          
          <!-- Social Media Icons (Animated) -->
          <div class="social-icons">
            <a href="#" class="social-icon" data-aos="zoom-in" data-aos-delay="200"><i class="bi bi-facebook"></i></a>
            <a href="#" class="social-icon" data-aos="zoom-in" data-aos-delay="250"><i class="bi bi-instagram"></i></a>
            <a href="#" class="social-icon" data-aos="zoom-in" data-aos-delay="300"><i class="bi bi-whatsapp"></i></a>
            <a href="#" class="social-icon" data-aos="zoom-in" data-aos-delay="350"><i class="bi bi-tiktok"></i></a>
          </div>
        </div>
      </div>

      <!-- Quick Links (Improved) -->
      <div class="col-lg-4 mb-4" data-aos="zoom-in" data-aos-delay="150">
        <h5 class="fw-bold mb-3">Quick Links</h5>
        <ul class="footer-links list-unstyled">
          <li class="mb-2">
            <a href="index.php" class="footer-link">
              <i class="bi bi-house-door-fill me-2"></i> 
              <span>Home</span>
              <i class="bi bi-arrow-right-short link-arrow"></i>
            </a>
          </li>
          <li class="mb-2">
            <a href="about.php" class="footer-link">
              <i class="bi bi-info-circle-fill me-2"></i>
              <span>About Us</span>
              <i class="bi bi-arrow-right-short link-arrow"></i>
            </a>
          </li>
          <li class="mb-2">
            <a href="icecreams.php" class="footer-link">
              <i class="bi bi-basket-fill me-2"></i>
              <span>Our Ice Creams</span>
              <i class="bi bi-arrow-right-short link-arrow"></i>
            </a>
          </li>
          <li class="mb-2">
            <a href="checkout.php" class="footer-link">
              <i class="bi bi-cart-fill me-2"></i>
              <span>Buy Now</span>
              <i class="bi bi-arrow-right-short link-arrow"></i>
            </a>
          </li>
          <li>
            <a href="contact.php" class="footer-link">
              <i class="bi bi-envelope-fill me-2"></i>
              <span>Contact</span>
              <i class="bi bi-arrow-right-short link-arrow"></i>
            </a>
          </li>
        </ul>
      </div>

      <!-- Contact Info (With Click-to-Call) -->
      <div class="col-lg-4 mb-4" data-aos="fade-left" data-aos-delay="200">
        <h5 class="fw-bold mb-3">Reach Us</h5>
        <ul class="contact-info list-unstyled">
          <li class="mb-3">
            <a href="https://maps.google.com" target="_blank" class="text-decoration-none text-dark">
              <i class="bi bi-geo-alt-fill me-2"></i> 
              Ice Cream Street, Chill Town
            </a>
          </li>
          <li class="mb-3">
            <a href="mailto:creamy@delight.com" class="text-decoration-none text-dark">
              <i class="bi bi-envelope-fill me-2"></i>
              creamy@delight.com
            </a>
          </li>
          <li class="mb-3">
            <a href="tel:+923001234567" class="text-decoration-none text-dark">
              <i class="bi bi-phone-fill me-2"></i>
              +92 300 1234567
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Copyright (With Back-to-Top Button) -->
      <div class="text-center mt-3 pt-3 border-top" style="color: #333;">
      <small class="d-block">© <?= date('Y') ?> Creamy Delight. All rights reserved.</small>
    </div>
  </div>
</footer>
</div>

<!-- Add this CSS -->
<style>
  /* Footer Links Hover Effect */
  .footer-link {
    color: #333;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    text-decoration: none;
  }
  
  .footer-link:hover {
    color: #ff69b4;
    transform: translateX(5px);
  }
  
  .footer-link .link-arrow {
    opacity: 0;
    transition: all 0.3s ease;
  }
  
  .footer-link:hover .link-arrow {
    opacity: 1;
    margin-left: 5px;
  }
  
  /* Social Icons */
  .social-icons {
    display: flex;
    gap: 15px;
  }
  
  .social-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    transition: all 0.3s ease;
  }
  
  .social-icon:hover {
    background: #ff69b4;
    color: white;
    transform: translateY(-3px);
  }
  
  /* Back to Top Button */
  .back-to-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 99;
  }
  
  .back-to-top.active {
    opacity: 1;
    visibility: visible;
  }
  @media (max-width: 990px) {
  .footer-brand, .footer-links, .contact-info, .social-icons {
    text-align: center !important;
    justify-content: center !important;
  }

  .social-icons {
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
  }

  .footer-link {
    justify-content: center;
  }

  .contact-info li,
  .footer-links li {
    display: flex;
    justify-content: center;
  }

  .footer-brand h2,
  .footer-brand p 
  {
    text-align: center;
  }
  h5{
    text-align: center;
  }
}
</style>

<!-- Add this JavaScript -->
<script>
  // Back to Top Button
  const backToTopButton = document.querySelector('.back-to-top');
  
  window.addEventListener('scroll', function() {
    if (window.pageYOffset > 300) {
      backToTopButton.classList.add('active');
    } else {
      backToTopButton.classList.remove('active');
    }
  });
  
  backToTopButton.addEventListener('click', function(e) {
    e.preventDefault();
    window.scrollTo({top: 0, behavior: 'smooth'});
  });
</script>
