<?php
if (PHP_SAPI === "cli-server") {
  $requestedFile = __DIR__ . parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH);
  if (is_file($requestedFile)) {
    return false;
  }
}

require __DIR__ . "/data.php";

$uri = parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH);
$path = trim($uri, "/");
$page = $path === "" ? "home" : explode("/", $path)[0];
$segments = $path === "" ? [] : explode("/", $path);
$productId = $page === "product" ? (int) ($segments[1] ?? 0) : 0;
$productSlug = $page === "products" ? (string) ($segments[1] ?? "") : "";
$adminSubpage = $page === "admin" ? (string) ($segments[1] ?? "") : "";
$currentProduct = null;
foreach ($products as $product) {
  if (($productId && (int) $product["id"] === $productId) || ($productSlug && ($product["slug"] ?? "") === $productSlug)) {
    $currentProduct = $product;
    $productId = (int) $product["id"];
    $page = "product";
    break;
  }
}
$navLinks = [
  "shop" => "Shop",
  "wishlist" => "Watchlist",
  "orders" => "Orders",
  "auth" => "Account",
  "support" => "Support",
  "contact" => "Contact",
  "about" => "About",
  "admin" => "Admin",
];

$pageTitle = $currentProduct
  ? $currentProduct["name"] . " - Aso Oke Palacez"
  : "Aso Oke Palacez - Premium Aso Oke Store";
$pageDescription = $currentProduct
  ? $currentProduct["description"]
  : "Handwoven Aso Oke, fila, bridal sets, accessories, and ceremonial collections from Ilorin, Nigeria.";

function active($name, $page) {
  return $name === $page ? "active" : "";
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($pageDescription) ?>">
  <link rel="canonical" href="<?= e((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://" . ($_SERVER["HTTP_HOST"] ?? "localhost:8000") . ($currentProduct ? "/products/" . ($currentProduct["slug"] ?? $currentProduct["id"]) : $uri)) ?>">
  <link rel="stylesheet" href="/app/globals.css">
  <?php if ($currentProduct): ?>
    <script type="application/ld+json">
      <?= json_encode([
        "@context" => "https://schema.org",
        "@type" => "Product",
        "name" => $currentProduct["name"],
        "description" => $currentProduct["description"],
        "category" => $currentProduct["category"],
        "sku" => "AOP-" . $currentProduct["id"],
        "brand" => ["@type" => "Brand", "name" => "Aso Oke Palacez"],
        "offers" => [
          "@type" => "Offer",
          "priceCurrency" => "NGN",
          "price" => $currentProduct["price"],
          "availability" => ((int) $currentProduct["stock"] > 0) ? "https://schema.org/InStock" : "https://schema.org/OutOfStock",
          "url" => "/products/" . ($currentProduct["slug"] ?? $currentProduct["id"])
        ],
        "aggregateRating" => [
          "@type" => "AggregateRating",
          "ratingValue" => $currentProduct["rating"],
          "reviewCount" => max(8, (int) ($currentProduct["popularity"] / 4))
        ]
      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
    </script>
  <?php endif; ?>
  <script>
    window.ASO_BOOT = {
      categories: <?= json_encode($categories) ?>,
      categoryTiles: <?= json_encode($categoryTiles) ?>,
      contacts: <?= json_encode($contacts) ?>,
      orders: <?= json_encode($orders) ?>,
      products: <?= json_encode($products) ?>,
      page: <?= json_encode($page) ?>,
      productId: <?= json_encode($productId) ?>
    };
  </script>
  <script defer src="/assets/app.js"></script>
</head>
<body>
  <div class="shell">
    <div class="announce">
      <div class="container announce-inner">
        <span>Free Lagos delivery from NGN 250,000</span>
        <span>Use code PALACE10 for 10% off</span>
        <a href="/support">Talk to a stylist</a>
      </div>
    </div>
    <header>
      <div class="container topbar">
        <a href="/" class="brand">ASO OKE<span> PALACEZ</span></a>
        <nav aria-label="Main navigation">
          <ul class="nav-links">
            <?php foreach ($navLinks as $href => $label): ?>
              <li><a class="<?= active($href, $page) ?>" href="/<?= e($href) ?>"><?= e($label) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </nav>
        <div class="header-actions">
          <div class="contact-menu">
            <button class="icon-btn contact-toggle" type="button" data-contact-toggle title="Contact us" aria-label="Contact us">☎</button>
            <div class="contact-popover" data-contact-popover>
              <?php foreach ($contacts as $contact): ?>
                <div>
                  <strong><?= e($contact["display"]) ?></strong>
                  <span><a href="tel:<?= e($contact["tel"]) ?>">Call</a><a target="_blank" rel="noopener noreferrer" href="https://wa.me/<?= e($contact["wa"]) ?>">WhatsApp</a></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <button class="icon-btn theme-toggle" type="button" data-theme-toggle title="Switch theme" aria-label="Switch theme"><span class="theme-icon moon-icon" aria-hidden="true"></span></button>
          <a class="icon-btn" href="/auth" data-auth-shortcut title="Account">In</a>
          <button class="icon-btn" type="button" data-cart-open title="Cart">Bag<span class="badge" data-cart-count>0</span></button>
        </div>
      </div>
    </header>

    <main>
      <?php if ($page === "home"): ?>
        <section class="hero">
          <div class="container">
            <div class="hero-copy">
              <div class="eyebrow">Handwoven in Nigeria</div>
              <h1>ASO OKE PALACEZ</h1>
              <p>Premium Aso Oke, fila, bridal sets, accessories, and ceremonial collections for customers who want tradition to feel sharp, confident, and modern.</p>
              <div class="hero-actions">
                <a href="/shop" class="btn primary">Shop Collection</a>
                <a href="/#categories" class="btn teal">Categories</a>
                <a href="https://wa.me/2347082686649" target="_blank" rel="noopener noreferrer" class="btn">WhatsApp Order</a>
                <button class="btn teal" type="button" data-market-open>Live Market Calendar</button>
              </div>
              <div class="trust-strip">
                <div><strong>Authentic Fabrics</strong><span>Carefully selected woven pieces.</span></div>
                <div><strong>Secure Checkout</strong><span>Bank transfer, card, Paystack, Flutterwave.</span></div>
                <div><strong>Nigeria Delivery</strong><span>Delivery cost calculated at checkout.</span></div>
                <div><strong>Owner Dashboard</strong><span>Add products and manage stock.</span></div>
              </div>
            </div>
          </div>
        </section>
        <section class="section" id="categories">
          <div class="container">
            <div class="section-head">
              <div><div class="eyebrow">Shop by category</div><h2>Made for every ceremony</h2></div>
              <a class="btn" href="/shop">View All</a>
            </div>
            <div class="category-grid" data-category-grid></div>
          </div>
        </section>
        <section class="section">
          <div class="container">
            <div class="section-head">
              <div><div class="eyebrow">Featured products</div><h2>Fresh picks from the palace</h2></div>
              <a class="btn" href="/shop">New Arrivals</a>
            </div>
            <div class="products-grid" data-products="featured"></div>
          </div>
        </section>
        <section class="section recent-section" data-recent-section hidden>
          <div class="container">
            <div class="section-head">
              <div><div class="eyebrow">Continue shopping</div><h2>Recently viewed</h2></div>
              <a class="btn" href="/shop">Explore More</a>
            </div>
            <div class="products-grid" data-recent-products></div>
          </div>
        </section>
        <section class="section section-tint">
          <div class="container commerce-grid">
            <div class="panel feature-panel">
              <div class="eyebrow">Styling services</div>
              <h2>From fabric choice to final outfit plan</h2>
              <p>Request coordinated colors, bridal family bundles, cap pairings, and delivery timing before you buy.</p>
              <div class="hero-actions"><a class="btn primary" href="/support">Book Consultation</a><a class="btn" href="/shop">Browse Sets</a></div>
            </div>
            <div class="value-stack">
              <div class="panel value-card"><strong>Same-day confirmation</strong><span>WhatsApp order review before payment.</span></div>
              <div class="panel value-card"><strong>Saved wishlist</strong><span>Keep ceremony options together while deciding.</span></div>
              <div class="panel value-card"><strong>Flexible payment</strong><span>Bank transfer, Paystack, Flutterwave, or pay on delivery.</span></div>
            </div>
          </div>
        </section>
        <section class="section">
          <div class="container">
            <div class="section-head">
              <div><div class="eyebrow">Customer proof</div><h2>Trusted for ceremonies</h2></div>
            </div>
            <div class="review-grid">
              <div class="panel review-card"><strong>Wedding client, Lagos</strong><p>The bridal bundle arrived early and the colors matched our family theme perfectly.</p><span>5.0 rating</span></div>
              <div class="panel review-card"><strong>Event guest, Abuja</strong><p>The Etu weave felt premium and the support team helped me choose the right length.</p><span>4.9 rating</span></div>
              <div class="panel review-card"><strong>Family order, Ilorin</strong><p>They coordinated caps, ipele, and fabric for the whole group without stress.</p><span>5.0 rating</span></div>
            </div>
          </div>
        </section>
        <section class="section newsletter-band">
          <div class="container newsletter-wrap">
            <div><div class="eyebrow">New arrivals</div><h2>Get first access to market drops</h2></div>
            <form class="newsletter-form" data-newsletter-form><input name="email" type="email" placeholder="customer@example.com" required><button class="btn primary" type="submit">Notify Me</button></form>
          </div>
        </section>
      <?php elseif ($page === "shop"): ?>
        <section class="section">
          <div class="container">
            <div class="section-head">
              <div><div class="eyebrow">Full shop</div><h2>Browse Aso Oke styles</h2></div>
              <button class="btn" type="button" data-reset-filters>Reset Filters</button>
            </div>
            <div class="shop-layout">
              <aside class="panel filters">
                <div class="field"><label for="searchInput">Search products</label><input id="searchInput" data-filter-search list="productSuggestions" autocomplete="off" placeholder="Search Sanyan, Etu, Alari..."><datalist id="productSuggestions" data-search-suggestions></datalist><div class="suggestion-row" data-autocomplete-panel></div></div>
                <div class="field"><label for="categoryFilter">Category</label><select id="categoryFilter" data-filter-category><option value="">All categories</option><?php foreach ($categories as $category): ?><option value="<?= e($category) ?>"><?= e($category) ?></option><?php endforeach; ?></select></div>
                <div class="field"><label for="colorFilter">Color</label><select id="colorFilter" data-filter-color><option value="">All colors</option></select></div>
                <div class="field"><label for="sizeFilter">Size / fabric type</label><select id="sizeFilter" data-filter-size><option value="">All sizes</option></select></div>
                <div class="field"><label>Price range</label><div class="filter-row"><input type="number" placeholder="Min" data-filter-min><input type="number" placeholder="Max" data-filter-max></div></div>
                <div class="field"><label for="sortFilter">Sort</label><select id="sortFilter" data-filter-sort><option value="newest">Newest</option><option value="popular">Popularity</option><option value="low">Price: low to high</option><option value="high">Price: high to low</option><option value="rating">Rating</option></select></div>
                <div class="filter-note"><strong>Need help choosing?</strong><span>Send screenshots to WhatsApp for styling advice.</span><a href="https://wa.me/2347082686649" target="_blank" rel="noopener noreferrer">Chat now</a></div>
              </aside>
              <div>
                <div class="toolbar"><p data-shop-count>Showing 0 of 0 products</p><div class="mini-actions" data-quick-filters></div></div>
                <div class="compare-bar" data-compare-bar hidden></div>
                <div class="products-grid" data-products="shop"></div>
              </div>
            </div>
          </div>
        </section>
      <?php elseif ($page === "product"): ?>
        <section class="section"><div class="container" data-product-detail></div></section>
      <?php elseif ($page === "wishlist"): ?>
        <section class="section"><div class="container"><div class="section-head"><div><div class="eyebrow">Watchlist</div><h2>Saved products</h2></div></div><div class="products-grid" data-products="wishlist"></div></div></section>
      <?php elseif ($page === "orders"): ?>
        <section class="section">
          <div class="container">
            <div class="section-head"><div><div class="eyebrow">Order management</div><h2>Orders and tracking</h2></div><button class="btn" type="button" data-sample-order>Create Sample Order</button></div>
            <div class="panel admin-list" data-orders></div>
          </div>
        </section>
      <?php elseif ($page === "checkout"): ?>
        <section class="section">
          <div class="container">
            <div class="section-head"><div><div class="eyebrow">Checkout</div><h2>Complete your order</h2></div></div>
            <div class="checkout-steps" aria-label="Checkout steps">
              <span>1. Cart</span>
              <span>2. Details</span>
              <span>3. WhatsApp confirmation</span>
            </div>
            <div class="split">
              <form class="panel admin-form" data-checkout-form>
                <h3>Customer details</h3>
                <p style="margin:8px 0 16px">Submit your details and continue the order on WhatsApp.</p>
                <div class="field"><label>Name</label><input name="customer" required placeholder="Your name"></div>
                <div class="field"><label>Phone</label><input name="phone" required placeholder="080..."></div>
                <div class="field"><label>Delivery address</label><textarea name="address" required placeholder="Street, city, state"></textarea></div>
                <div class="filter-row">
                  <div class="field"><label>Delivery state</label><input name="state" placeholder="Lagos, Kwara, Abuja"></div>
                  <div class="field"><label>Delivery speed</label><select name="speed"><option value="standard">Standard delivery</option><option value="express">Express delivery</option><option value="pickup">Store pickup</option></select></div>
                </div>
                <div class="field"><label>Payment method</label><select name="method"><option>Bank Transfer</option><option>Paystack</option><option>Flutterwave</option><option>Pay on delivery</option></select></div>
                <div class="field"><label>Order note</label><textarea name="note" placeholder="Color preference, event date, sizing notes"></textarea></div>
                <button class="btn primary" type="submit">Send Order</button>
              </form>
              <div class="panel admin-list">
                <h3>Order summary</h3>
                <div class="secure-badges" aria-label="Secure checkout badges">
                  <span>SSL secure</span>
                  <span>Card</span>
                  <span>Bank transfer</span>
                  <span>Paystack</span>
                  <span>Flutterwave</span>
                </div>
                <div class="coupon-box"><label for="couponInput">Promo code</label><div class="coupon-row"><input id="couponInput" data-coupon-input placeholder="PALACE10"><button class="btn" type="button" data-apply-coupon>Apply</button></div></div>
                <div data-checkout-summary></div>
                <div class="checkout-assurance">
                  <div><strong>Secure confirmation</strong><span>Order is reviewed on WhatsApp before payment.</span></div>
                  <div><strong>Delivery estimate</strong><span>Standard, express, and pickup options available.</span></div>
                  <div><strong>Custom help</strong><span>Send event date, shade, and sizing notes.</span></div>
                </div>
              </div>
            </div>
          </div>
        </section>
      <?php elseif ($page === "auth"): ?>
        <section class="section auth-section">
          <div class="container auth-layout" data-auth-signed-out>
            <div class="auth-copy">
              <div class="eyebrow">Secure customer access</div>
              <h1>ASO OKE PALACEZ</h1>
              <p>Sign in to keep checkout details ready, review orders, and manage your Aso Oke shopping session from one place.</p>
              <div class="auth-benefits">
                <span>Fast checkout</span>
                <span>Saved contact details</span>
                <span>Order access</span>
              </div>
            </div>

            <div class="panel auth-card">
              <div class="auth-tabs" role="tablist" aria-label="Authentication mode">
                <button type="button" class="active" data-auth-tab="signin">Sign In</button>
                <button type="button" data-auth-tab="signup">Create Account</button>
              </div>

              <form data-auth-form="signin">
                <div class="auth-head compact">
                  <h2>Welcome back</h2>
                  <p>Use an account created on this browser.</p>
                </div>
                <div class="field"><label for="signInEmail">Email address</label><input id="signInEmail" name="email" type="email" autocomplete="email" placeholder="customer@example.com" required></div>
                <div class="field"><label for="signInPassword">Password</label><input id="signInPassword" name="password" type="password" autocomplete="current-password" placeholder="Enter your password" required></div>
                <button class="btn primary auth-submit" type="submit">Sign In</button>
              </form>

              <form data-auth-form="signup" hidden>
                <div class="auth-head compact">
                  <h2>Create your account</h2>
                  <p>Password must be at least 8 characters.</p>
                </div>
                <div class="field"><label for="name">Full name</label><input id="name" name="name" autocomplete="name" placeholder="Your full name" required></div>
                <div class="field"><label for="phone">Phone number</label><input id="phone" name="phone" type="tel" autocomplete="tel" placeholder="+234..."></div>
                <div class="filter-row">
                  <div class="field"><label for="whatsapp">WhatsApp number</label><input id="whatsapp" name="whatsapp" type="tel" autocomplete="tel" placeholder="+234..." required></div>
                  <div class="field"><label for="deliveryState">Delivery state</label><input id="deliveryState" name="state" autocomplete="address-level1" placeholder="Lagos, Kwara, Abuja..." required></div>
                </div>
                <div class="field"><label for="signUpEmail">Email address</label><input id="signUpEmail" name="email" type="email" autocomplete="email" placeholder="customer@example.com" required></div>
                <div class="filter-row">
                  <div class="field"><label for="signUpPassword">Password</label><input id="signUpPassword" name="password" type="password" autocomplete="new-password" placeholder="Create password" required></div>
                  <div class="field"><label for="confirmPassword">Confirm</label><input id="confirmPassword" name="confirmPassword" type="password" autocomplete="new-password" placeholder="Repeat password" required></div>
                </div>
                <label class="check-row"><input name="acceptedTerms" type="checkbox"> <span>I agree to save my account details on this browser for faster checkout.</span></label>
                <button class="btn primary auth-submit" type="submit">Create Account</button>
              </form>

              <div class="auth-foot">
                <a href="/">Back to Home</a>
                <a href="/support">Need Help?</a>
              </div>
            </div>
          </div>

          <div class="container auth-wrap" data-auth-signed-in hidden>
            <div class="auth-dashboard">
              <div class="panel auth-card">
                <div class="auth-head">
                  <div class="eyebrow">Account</div>
                  <h2>You are signed in</h2>
                  <p>Manage checkout details, delivery contact, and saved account access.</p>
                </div>
                <div class="auth-summary" data-auth-summary></div>
                <div class="auth-actions">
                  <a class="btn primary" href="/shop">Continue Shopping</a>
                  <a class="btn" href="/orders">View Orders</a>
                  <button class="btn danger" type="button" data-auth-logout>Sign Out</button>
                </div>
              </div>

              <form class="panel auth-card" data-auth-profile-form>
                <div class="auth-head compact">
                  <div class="eyebrow">Delivery profile</div>
                  <h2>Saved details</h2>
                  <p>Update the information used to pre-fill checkout and admin order tracking.</p>
                </div>
                <div class="field"><label for="profileName">Full name</label><input id="profileName" name="name" autocomplete="name" required></div>
                <div class="filter-row">
                  <div class="field"><label for="profilePhone">Phone number</label><input id="profilePhone" name="phone" type="tel" autocomplete="tel"></div>
                  <div class="field"><label for="profileWhatsapp">WhatsApp number</label><input id="profileWhatsapp" name="whatsapp" type="tel" autocomplete="tel" required></div>
                </div>
                <div class="field"><label for="profileState">Delivery state</label><input id="profileState" name="state" autocomplete="address-level1" required></div>
                <button class="btn primary auth-submit" type="submit">Save Details</button>
              </form>
            </div>
          </div>
        </section>
      <?php elseif ($page === "contact"): ?>
        <section class="section contact-page" id="contact">
          <div class="container">
            <div class="section-head"><div><div class="eyebrow">Contact us</div><h2>Call or WhatsApp Aso Oke Palacez</h2></div></div>
            <div class="contact-grid">
              <?php foreach ($contacts as $contact): ?>
                <div class="panel contact-card">
                  <span><?= e($contact["label"]) ?></span>
                  <strong>Call/WhatsApp: <?= e($contact["display"]) ?></strong>
                  <div class="contact-actions">
                    <a class="contact-link" href="tel:<?= e($contact["tel"]) ?>" aria-label="Call <?= e($contact["display"]) ?>"><span>☎</span> Call</a>
                    <a class="contact-link whatsapp" target="_blank" rel="noopener noreferrer" href="https://wa.me/<?= e($contact["wa"]) ?>" aria-label="WhatsApp <?= e($contact["display"]) ?>"><span>WA</span> WhatsApp</a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="panel policy-panel">
              <strong>Support promise</strong>
              <p>We aim to reply within a few hours during business periods and within 24 hours for all order, delivery, payment, and custom styling questions.</p>
            </div>
          </div>
        </section>
      <?php elseif ($page === "support"): ?>
        <section class="section">
          <div class="container">
            <div class="section-head"><div><div class="eyebrow">Customer support</div><h2>Fast help before and after purchase</h2></div></div>
            <div class="support-grid">
              <div class="panel support-item"><strong>WhatsApp Support</strong><p>+234 708 268 6649<br>+234 816 465 6076</p><a class="btn" target="_blank" rel="noopener noreferrer" href="https://wa.me/2347082686649">Chat</a></div>
              <div class="panel support-item"><strong>Email Support</strong><p>Send sizing, order, or delivery questions.</p><a class="btn" href="mailto:orders@asookepalacez.com">Email</a></div>
              <div class="panel support-item"><strong>FAQ</strong><p>Payment, delivery, custom orders, and product care.</p><button class="btn" type="button" data-notice="FAQ: contact WhatsApp for custom sizing and delivery timing">Open FAQ</button></div>
              <div class="panel support-item"><strong>Notifications</strong><p>Order updates, promotions, and new arrivals.</p><button class="btn teal" type="button" data-notice="Notifications enabled for this session">Enable</button></div>
            </div>
            <div class="panel faq-panel">
              <details open><summary>How long does delivery take?</summary><p>Lagos delivery is usually 1-3 business days after confirmation. Other states are confirmed by WhatsApp before dispatch.</p></details>
              <details><summary>Can I request a custom color or family bundle?</summary><p>Yes. Send your event date, preferred color family, quantity, and sizing notes through WhatsApp.</p></details>
              <details><summary>Which payment methods are supported?</summary><p>Bank transfer, Paystack, Flutterwave, and pay on delivery can be selected during checkout.</p></details>
              <details><summary>Can I reserve a product?</summary><p>Products can be saved to your watchlist and confirmed with the store before payment.</p></details>
            </div>
          </div>
        </section>
      <?php elseif ($page === "about"): ?>
        <section class="section"><div class="container split"><div class="story-img product-media empty" data-about-image><span>No image yet</span></div><div><div class="eyebrow">About us</div><h2>Yoruba heritage with a modern shopping experience</h2><p>Aso Oke Palacez brings handwoven elegance from Ilorin to customers preparing for weddings, naming ceremonies, cultural events, and premium everyday style.</p><p>Our mission is to make authentic Aso Oke easier to discover, compare, reserve, and purchase with clear product details, direct support, and reliable order tracking.</p><div class="hero-actions" style="margin-top:22px"><a class="btn primary" href="/shop">Shop Now</a><a class="btn" href="tel:+2347082686649">Call Store</a></div></div></div></section>
      <?php elseif ($page === "admin" && $adminSubpage === "orders"): ?>
        <section class="section">
          <div class="container">
            <div class="section-head">
              <div><div class="eyebrow">Admin order tracker</div><h2>Buyer details and purchased products</h2></div>
              <a class="btn" href="/admin">Back To Admin</a>
            </div>
            <div class="panel admin-list order-tracker-panel"><div class="order-tracker-list" data-admin-order-tracker></div></div>
          </div>
        </section>      <?php elseif ($page === "admin"): ?>
        <section class="section">
          <div class="container">
            <div class="section-head"><div><div class="eyebrow">Admin dashboard</div><h2>Store controls</h2></div><div class="hero-actions"><a class="btn" href="/admin/orders">Order Tracker</a><button class="btn primary" type="button" data-export-products>Export Product Data</button></div></div>
            <div class="dashboard" data-dashboard></div>
            <div class="panel admin-list active-members-panel"><div class="section-head compact-head"><div><div class="eyebrow">Active members</div><h3>Customer account information</h3></div></div><div class="active-member-list" data-admin-members></div></div>
            <div class="panel admin-list site-media-panel"><div class="section-head compact-head"><div><div class="eyebrow">Site images</div><h3>Homepage categories and About page</h3></div></div><div class="site-media-grid" data-site-media></div></div>
            <div class="admin-grid">
              <form class="panel admin-form" data-product-form>
                <h3>Add product</h3><p style="margin:8px 0 16px">Products added here appear immediately in the shop.</p>
                <div class="field"><label>Product name</label><input name="name" required placeholder="Royal Gold Sanyan"></div>
                <div class="field"><label>Price (NGN)</label><input name="price" type="number" required placeholder="145000"></div>
                <div class="field"><label>Category</label><select name="category"><?php foreach ($categories as $category): ?><option value="<?= e($category) ?>"><?= e($category) ?></option><?php endforeach; ?></select></div>
                <div class="field"><label>Upload product image</label><input name="imageFile" type="file" accept="image/*"><span class="admin-help">Stored in this browser for now. Connect database storage when you are ready.</span><div class="admin-preview" data-image-preview></div></div>
                <div class="field"><label>Image URL or local image path</label><input name="imageUrl" placeholder="/products/10.jpeg"></div>
                <div class="filter-row"><div class="field"><label>Stock</label><input name="stock" type="number" value="8"></div><div class="field"><label>Rating</label><input name="rating" type="number" min="1" max="5" step="0.1" value="4.8"></div></div>
                <div class="filter-row"><div class="field"><label>Color</label><select name="color"><option>Gold</option><option>Wine</option><option>Multi</option><option>Teal</option><option>Blue</option><option>Cream</option><option>Red</option></select></div><div class="field"><label>Sizes</label><input name="sizes" value="Couple Set, Family Pack, Custom"></div></div>
                <div class="field"><label>Description</label><textarea name="description" placeholder="Describe fabric, occasion, texture, and package."></textarea></div>
                <button class="btn primary" type="submit">Add Product</button>
              </form>
              <div class="panel admin-list"><h3>Product management</h3><div data-admin-products></div></div>
            </div>
          </div>
        </section>
      <?php else: ?>
        <section class="section"><div class="container panel empty"><h2>Page not found</h2><p>That page does not exist.</p><a class="btn primary" href="/">Go home</a></div></section>
      <?php endif; ?>
    </main>

    <footer>
      <div class="container footer-grid">
        <div><a href="/" class="brand">ASO OKE<span> PALACEZ</span></a><p style="margin-top:12px">Premium Aso Oke and traditional fashion from Ilorin, Nigeria.</p></div>
        <div><strong>Shop</strong><a href="/shop">All Products</a><a href="/wishlist">Wishlist</a><a href="/orders">Orders</a></div>
        <div class="footer-contact"><strong>Contact Us</strong>
          <?php foreach ($contacts as $contact): ?>
            <div class="footer-contact-row">
              <span><?= e($contact["display"]) ?></span>
              <a class="contact-icon" href="tel:<?= e($contact["tel"]) ?>" aria-label="Call <?= e($contact["display"]) ?>">☎</a>
              <a class="contact-icon whatsapp" href="https://wa.me/<?= e($contact["wa"]) ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp <?= e($contact["display"]) ?>">WA</a>
            </div>
          <?php endforeach; ?>
        </div>
        <div><strong>Store</strong><a href="/about">About</a><a href="/support">Support</a><a href="/contact">Contact</a><a href="/admin">Admin</a></div>
      </div>
    </footer>
  </div>

  <aside class="drawer" data-cart-drawer><div class="drawer-head"><h3>Your cart</h3><button class="icon-btn" type="button" data-cart-close>X</button></div><div class="drawer-body"><div data-free-shipping></div><div data-cart-items></div><div class="cart-recommendations" data-cart-recommendations></div></div><div class="drawer-foot"><div class="cart-total" data-cart-totals></div><a class="btn primary" href="/checkout">Checkout</a><a class="btn" href="/shop">Continue Shopping</a></div></aside>
  <div class="modal" data-market-modal><div class="modal-card"><div class="modal-head"><h3>Live Market Calendar</h3><button class="icon-btn" type="button" data-market-close>X</button></div><div class="modal-body"><div class="support-grid"><div class="panel support-item"><strong>Monday</strong><p>New Sanyan and Etu selections.</p></div><div class="panel support-item"><strong>Wednesday</strong><p>Bridal bundles and custom consultation.</p></div><div class="panel support-item"><strong>Friday</strong><p>Premium Demask and Loom arrivals.</p></div><div class="panel support-item"><strong>Saturday</strong><p>WhatsApp live market previews.</p></div></div></div></div></div>
  <div class="floating-whatsapp" data-wa-float>
    <button type="button" data-wa-toggle aria-label="Open WhatsApp contact options"><span>WA</span></button>
    <div class="wa-float-menu" data-wa-menu>
      <?php foreach ($contacts as $contact): ?>
        <a target="_blank" rel="noopener noreferrer" href="https://wa.me/<?= e($contact["wa"]) ?>">WhatsApp <?= e($contact["display"]) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="notice" data-notice-box></div>
</body>
</html>
