<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
  header("Location: login.php");
  exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('connect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare cart products with quantity and subtotal
$cart_products = [];
$total = 0;

if (isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $item) {
        $product_id = (int)$item['id'];
        $qty = (int)$item['qty'];

        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $product = $result->fetch_assoc();
            $product['qty'] = $qty;
            $product['subtotal'] = $product['price'] * $qty;
            $cart_products[] = $product;
            $total += $product['subtotal'];
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Cart - Creamy Delight</title>
  <link rel="icon" href="images/logo.png" type="image/png">
  <link rel="stylesheet" href="style.css"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
  <style>
    /* Sticky checkout section */
.sticky-checkout {
  position: sticky;
  bottom: 0;
  background: white;
  padding: 1rem 0;
  border-top: 1px solid #ddd;
  z-index: 10;
}

/* Mobile responsive cart items */
@media (max-width: 768px) {
  .cart-item-card .row {
    flex-direction: column;
  }
  .cart-item-card img {
    height: auto !important;
  }
}
 .checkout-header {
      background: linear-gradient(to right, #ffd1dc, #c2f0fc);
      padding: 2rem;
      border-radius: 1rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    </style>
<?php include('navbar.php'); ?>
<br><br>
<main class="flex-grow-1">
  <div class="container py-5">
    <h2 class="mb-4 text-center fw-bold checkout-header text-info" data-aos="fade-down" data-aos-delay="300" data-aos-duration="1200">🛒 Your Cart</h2>

    <?php if (empty($cart_products)): ?>
  <div id="empty-cart-message" class="text-center py-5" data-aos="fade-up">
    <h3 class="text-muted">Your cart is empty</h3>
    <a class="btn btn-info mt-3 text-light px-4 rounded-pill" href="icecreams.php">Browse ice-creams</a>
    </div>
<?php else: ?>
  <div id="empty-cart-message" class="text-center py-5 d-none" data-aos="fade-up">
    <h3 class="text-muted">Your cart is empty</h3>
    <a class="btn btn-info mt-3 text-light px-4 rounded-pill" href="icecreams.php">
      Browse ice-creams</a>
            
  </div>
<?php endif; ?>

    <?php if (!empty($cart_products)): ?>
      <?php foreach ($cart_products as $index => $product): ?>
        <div class="card mb-4 shadow-sm cart-item-card" data-aos="fade-up">
          <div class="row g-0 align-items-center">
            <div class="col-md-3">
              <img src="images/<?= htmlspecialchars($product['image']) ?>" class="img-fluid rounded-start" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 220px; width: 100%; object-fit: cover;">
            </div>
            <div class="col-md-9">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                <div class="input-group input-group-sm" style="width: 140px;">
                  <button class="btn btn-outline-secondary btn-decrease" data-id="<?= $product['id'] ?>" data-index="<?= $index ?>">−</button>
                  <input type="number" min="1" max="99" class="form-control text-center manual-qty-input" value="<?= $product['qty'] ?>" data-id="<?= $product['id'] ?>" data-index="<?= $index ?>" id="qty-<?= $index ?>">
                  <button class="btn btn-outline-secondary btn-increase" data-id="<?= $product['id'] ?>" data-index="<?= $index ?>">+</button>
                </div>
                <p class="card-text mb-1">
                  Price: Rs. <?= number_format($product['price']) ?> × 
                  <span id="show-qty-<?= $index ?>"><?= $product['qty'] ?></span> = 
                  <strong>Rs. <span id="subtotal-<?= $index ?>"><?= number_format($product['subtotal']) ?></span></strong>
                </p>
                <a href="#" class="btn btn-danger btn-sm remove-btn" data-id="<?= $product['id'] ?>" data-index="<?= $index ?>">
                  <i class="bi bi-trash"></i> Remove
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

     <div class="sticky-checkout text-end mt-4" data-aos="fade-up">
    <h5 class="fw-bold">Total: Rs. <span id="cart-total"><?= number_format($total) ?></span></h5>
    <a href="checkout.php" class="btn btn-primary btn-lg mt-2">Proceed to Checkout</a>
    <button class="btn btn-danger btn-lg mt-2" id="clear-cart-btn">Clear Cart</button>
</div>
    <?php endif; ?>
  </div>
</main>

<?php include('footer.php'); ?>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 1000 });</script>

<script>
AOS.init({ duration: 1000 });

// ✅ Update UI after cart change
function updateCartUI(index, data) {
  if (data.success) {
    document.getElementById('qty-' + index).value = data.qty;
    document.getElementById('show-qty-' + index).innerText = data.qty;
    document.getElementById('subtotal-' + index).innerText = data.subtotal;
    document.getElementById('cart-total').innerText = data.total;

    // ✅ Update cart badge in navbar
    const cartBadge = document.getElementById('cart-count');
    if (cartBadge) cartBadge.innerText = data.count ?? cartBadge.innerText;
  }
}

// ✅ Update cart via AJAX
function updateCart(id, index, qty) {
  fetch('update-cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}&action=manual&qty=${qty}`
  })
  .then(res => res.json())
  .then(data => updateCartUI(index, data));
}

// ✅ Recalculate button visibility
function checkCartStatus() {
  const cards = document.querySelectorAll('.cart-item-card');
  const checkoutSection = document.querySelector('.sticky-checkout');
  const emptyMessage = document.getElementById('empty-cart-message');

  if (cards.length === 0) {
    checkoutSection?.classList.add('d-none');
    emptyMessage?.classList.remove('d-none');
  } else {
    checkoutSection?.classList.remove('d-none');
    emptyMessage?.classList.add('d-none');
  }
}

// ✅ Pagination with AOS reload + always visible footer + scroll fix
document.addEventListener("DOMContentLoaded", () => {
  const cardsPerPage = 6;
  let currentPage = 1;
  let paginationDiv = null;

  function showPage(page) {
    const cards = document.querySelectorAll(".cart-item-card");
    const totalPages = Math.ceil(cards.length / cardsPerPage);
    const start = (page - 1) * cardsPerPage;
    const end = start + cardsPerPage;

    cards.forEach((card, i) => {
      card.style.display = (i >= start && i < end) ? "block" : "none";
    });

    const pageInfo = document.getElementById("pageInfo");
    if (pageInfo) pageInfo.textContent = `Page ${page} of ${totalPages}`;

    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");
    if (prevBtn && nextBtn) {
      prevBtn.disabled = (page === 1);
      nextBtn.disabled = (page === totalPages);
    }

    // ✅ Always show footer & buttons
    const checkoutSection = document.querySelector(".sticky-checkout");
    if (checkoutSection) checkoutSection.classList.remove("d-none");

    // ✅ Refresh AOS cleanly on every page switch
    setTimeout(() => {
      AOS.refreshHard();
    }, 300);

    // ✅ Scroll only when user switches page (not when updating qty)
    if (!window.suppressScroll) {
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  }

  function bindPaginationEvents(totalPages) {
    const prevBtn = document.getElementById("prevPage");
    const nextBtn = document.getElementById("nextPage");

    if (prevBtn && nextBtn) {
      prevBtn.onclick = () => {
        if (currentPage > 1) {
          currentPage--;
          showPage(currentPage);
        }
      };
      nextBtn.onclick = () => {
        if (currentPage < totalPages) {
          currentPage++;
          showPage(currentPage);
        }
      };
    }
  }

  function createPagination() {
    const cards = document.querySelectorAll(".cart-item-card");
    const totalPages = Math.ceil(cards.length / cardsPerPage);
    if (cards.length <= cardsPerPage) return;

    paginationDiv?.remove(); // remove old pagination if exists

    paginationDiv = document.createElement("div");
    paginationDiv.className = "text-center mt-4";
    paginationDiv.setAttribute("data-aos", "fade-up");
    paginationDiv.innerHTML = `
      <button id="prevPage" class="btn btn-outline-primary me-2">Prev</button>
      <span id="pageInfo">Page ${currentPage} of ${totalPages}</span>
      <button id="nextPage" class="btn btn-outline-primary ms-2">Next</button>
    `;

    // ✅ Place pagination right above sticky-checkout
    const checkoutSection = document.querySelector(".sticky-checkout");
    checkoutSection.insertAdjacentElement("beforebegin", paginationDiv);

    AOS.refresh();
    bindPaginationEvents(totalPages);
    showPage(currentPage);
  }

  function updatePaginationAfterChange() {
    const cards = document.querySelectorAll(".cart-item-card");
    const totalPages = Math.ceil(cards.length / cardsPerPage);

    if (cards.length <= cardsPerPage) {
      paginationDiv?.remove();
      paginationDiv = null;
      cards.forEach(card => (card.style.display = "block"));
      currentPage = 1;
      setTimeout(() => AOS.refreshHard(), 300);
      return;
    }

    if (!paginationDiv) {
      createPagination();
      return;
    }

    const pageInfo = document.getElementById("pageInfo");
    if (pageInfo) pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    bindPaginationEvents(totalPages);
    showPage(currentPage);
  }

  // Observe DOM changes for cart item updates
  const observer = new MutationObserver(() => updatePaginationAfterChange());
  observer.observe(document.querySelector(".container"), { childList: true, subtree: true });

  createPagination();
});

// ✅ Increase / Decrease buttons — prevent page jump
document.querySelectorAll('.btn-increase, .btn-decrease').forEach(btn => {
  btn.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    suppressScroll = true; // 🚫 prevent scroll temporarily

    const id = this.dataset.id;
    const index = this.dataset.index;
    const input = document.getElementById('qty-' + index);
    const currentQty = parseInt(input.value) || 1;
    let newQty = currentQty;

    if (this.classList.contains('btn-increase') && currentQty < 99) newQty++;
    if (this.classList.contains('btn-decrease') && currentQty > 1) newQty--;

    input.value = newQty;
    updateCart(id, index, newQty);

    // allow scrolling again after 400ms (after AOS refresh etc.)
    setTimeout(() => (suppressScroll = false), 400);
  });
});

// ✅ Manual qty input
document.querySelectorAll('.manual-qty-input').forEach(input => {
  input.addEventListener('input', function () {
    suppressScroll = true; // 🚫 block scroll
    const id = this.dataset.id;
    const index = this.dataset.index;
    let qty = parseInt(this.value);
    if (isNaN(qty) || qty < 1) qty = 1;
    if (qty > 99) qty = 99;
    this.value = qty;
    updateCart(id, index, qty);
    setTimeout(() => (suppressScroll = false), 400);
  });
});

// ✅ Remove single item — no scroll + fix cart badge
document.querySelectorAll('.remove-btn').forEach(btn => {
  btn.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();
    suppressScroll = true; // block scroll temporarily

    const id = this.dataset.id;
    const card = this.closest('.card');

    fetch(`remove-from-cart.php?id=${id}`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          card.remove();
          document.getElementById('cart-total').innerText = data.total;

          // ✅ Ensure badge count updates correctly and never goes negative
          const cartBadge = document.getElementById('cart-count');
          if (data.count >= 0) {
            cartBadge.innerText = data.count;
          } else {
            // fallback: recalc from DOM
            const remaining = document.querySelectorAll('.cart-item-card').length;
            cartBadge.innerText = remaining;
          }

          checkCartStatus();
          setTimeout(() => (suppressScroll = false), 400);
        }
      });
  });
});


// ✅ Clear entire cart
document.getElementById('clear-cart-btn')?.addEventListener('click', function () {
  if (confirm('Are you sure you want to remove all items from your cart?')) {
    fetch('clear-cart.php')
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          document.querySelectorAll('.cart-item-card').forEach(card => card.remove());
          document.getElementById('cart-total').innerText = '0';
          document.getElementById('cart-count').innerText = '0';
          checkCartStatus();
        }
      });
  }
});

// ✅ Fix animations after layout changes
window.addEventListener("resize", () => AOS.refreshHard());
window.addEventListener("load", () => AOS.refresh());
</script>

</body>
</html>
