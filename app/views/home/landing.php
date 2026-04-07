<?php
// VIEW ONLY - DATA dari Controller: $products, $categories
?>

<!doctype html>
<html lang="id" data-bs-theme="blue-theme">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="BookStar - Toko Buku Online Modern dengan koleksi buku terbaik">
  <title>BookStar | Toko Buku Online</title>

  <link href="<?= BASE_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/bootstrap-extended.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/css/main.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">

  <style>
    * {
      font-family: 'Inter', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #0f172a, #1e293b);
      color: #fff;
    }

    /* NAVBAR */
    .navbar {
      background: rgba(15, 23, 42, 0.95);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(59, 130, 246, 0.2);
      padding: 1rem 0;
    }

    .navbar-brand {
      font-size: 1.6rem;
      font-weight: 800;
      background: linear-gradient(135deg, #60a5fa, #818cf8);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent !important;
      letter-spacing: -0.5px;
    }

    .nav-link {
      color: #cbd5f5 !important;
      font-weight: 500;
      transition: 0.3s;
      margin: 0 0.5rem;
    }

    .nav-link:hover {
      color: #60a5fa !important;
    }

    /* BUTTONS */
    .btn-main {
      background: linear-gradient(90deg, #3b82f6, #6366f1);
      border: none;
      color: white;
      border-radius: 12px;
      padding: 10px 24px;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-main:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
      background: linear-gradient(90deg, #2563eb, #4f46e5);
      color: white;
    }

    .btn-outline-light {
      border-radius: 12px;
      padding: 10px 24px;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-outline-light:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateY(-2px);
    }

    /* HERO SECTION */
    .hero-section {
      padding: 140px 0 80px;
      position: relative;
      overflow: hidden;
    }

    .hero-badge {
      display: inline-block;
      background: rgba(59, 130, 246, 0.2);
      padding: 6px 16px;
      border-radius: 40px;
      font-size: 0.85rem;
      color: #60a5fa;
      margin-bottom: 24px;
      border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .hero-title {
      font-size: 3.5rem;
      font-weight: 800;
      line-height: 1.2;
      letter-spacing: -1px;
      margin-bottom: 20px;
    }

    .hero-subtitle {
      color: #94a3b8;
      font-size: 1.1rem;
      line-height: 1.6;
      margin-bottom: 30px;
    }

    .hero-stats {
      display: flex;
      gap: 40px;
      margin-top: 40px;
    }

    .stat-item h3 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #60a5fa;
      margin-bottom: 5px;
    }

    .stat-item p {
      color: #94a3b8;
      font-size: 0.85rem;
      margin: 0;
    }

    .hero-image {
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-15px); }
    }

    /* SECTION TITLE */
    .section-header {
      text-align: center;
      margin-bottom: 50px;
    }

    .section-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 15px;
      position: relative;
      display: inline-block;
    }

    .section-title:after {
      content: '';
      position: absolute;
      bottom: -12px;
      left: 50%;
      transform: translateX(-50%);
      width: 70px;
      height: 3px;
      background: linear-gradient(90deg, #3b82f6, #6366f1);
      border-radius: 3px;
    }

    .section-subtitle {
      color: #94a3b8;
      font-size: 1rem;
    }

    /* CATEGORY CARDS */
    .category-wrapper {
      margin-bottom: 20px;
    }

    .category-card {
      background: linear-gradient(135deg, #1e293b, #0f172a);
      padding: 25px 15px;
      text-align: center;
      border-radius: 20px;
      transition: all 0.3s ease;
      cursor: pointer;
      border: 1px solid rgba(148, 163, 184, 0.1);
    }

    .category-card:hover {
      transform: translateY(-8px);
      border-color: #3b82f6;
      box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
    }

    .category-icon {
      font-size: 2.5rem;
      margin-bottom: 12px;
      display: inline-block;
    }

    .category-name {
      font-weight: 600;
      font-size: 1rem;
      margin: 0;
    }

    .category-count {
      font-size: 0.75rem;
      color: #60a5fa;
      margin-top: 8px;
    }

    /* PRODUCT CARDS */
    .product-card {
      background: #1e293b;
      border-radius: 20px;
      overflow: hidden;
      transition: all 0.3s ease;
      border: 1px solid rgba(148, 163, 184, 0.1);
      height: 100%;
    }

    .product-card:hover {
      transform: translateY(-8px);
      border-color: #3b82f6;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }

    .product-img-wrapper {
      position: relative;
      overflow: hidden;
      height: 220px;
      background: linear-gradient(135deg, #334155, #1e293b);
    }

    .product-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .product-card:hover .product-img {
      transform: scale(1.05);
    }

    .product-badge {
      position: absolute;
      top: 12px;
      right: 12px;
      background: linear-gradient(90deg, #3b82f6, #6366f1);
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.7rem;
      font-weight: 600;
    }

    .product-body {
      padding: 18px;
    }

    .product-title {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 5px;
      line-height: 1.4;
    }

    .product-category {
      font-size: 0.75rem;
      color: #94a3b8;
      display: inline-block;
      background: rgba(59, 130, 246, 0.15);
      padding: 3px 10px;
      border-radius: 20px;
      margin-bottom: 10px;
    }

    .product-price {
      font-size: 1.25rem;
      font-weight: 700;
      color: #60a5fa;
      margin: 10px 0;
    }

    .btn-detail {
      width: 100%;
      background: rgba(59, 130, 246, 0.15);
      border: 1px solid rgba(59, 130, 246, 0.3);
      color: #60a5fa;
      border-radius: 10px;
      padding: 8px;
      font-weight: 600;
      transition: 0.3s;
    }

    .btn-detail:hover {
      background: linear-gradient(90deg, #3b82f6, #6366f1);
      color: white;
      border-color: transparent;
    }

    /* CTA SECTION */
    .cta-section {
      background: linear-gradient(135deg, #1e293b, #0f172a);
      border-radius: 30px;
      padding: 60px 40px;
      margin: 40px auto;
      border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .cta-title {
      font-size: 2rem;
      font-weight: 800;
      margin-bottom: 15px;
    }

    /* FEATURE SECTION */
    .feature-card {
      background: rgba(30, 41, 59, 0.5);
      backdrop-filter: blur(10px);
      padding: 30px 20px;
      border-radius: 20px;
      text-align: center;
      border: 1px solid rgba(148, 163, 184, 0.1);
      transition: 0.3s;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      border-color: #3b82f6;
    }

    .feature-icon {
      font-size: 2.5rem;
      margin-bottom: 15px;
    }

    .feature-title {
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .feature-desc {
      font-size: 0.85rem;
      color: #94a3b8;
    }

    /* FOOTER */
    .footer {
      background: rgba(15, 23, 42, 0.95);
      border-top: 1px solid rgba(59, 130, 246, 0.2);
      padding: 50px 0 20px;
      margin-top: 60px;
    }

    .footer-brand {
      font-size: 1.5rem;
      font-weight: 800;
      background: linear-gradient(135deg, #60a5fa, #818cf8);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      margin-bottom: 15px;
    }

    .footer-text {
      color: #94a3b8;
      font-size: 0.85rem;
      line-height: 1.6;
    }

    .footer-title {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 20px;
    }

    .footer-links {
      list-style: none;
      padding: 0;
    }

    .footer-links li {
      margin-bottom: 10px;
    }

    .footer-links a {
      color: #94a3b8;
      text-decoration: none;
      font-size: 0.85rem;
      transition: 0.3s;
    }

    .footer-links a:hover {
      color: #60a5fa;
      padding-left: 5px;
    }

    .social-icons a {
      display: inline-block;
      width: 35px;
      height: 35px;
      background: rgba(59, 130, 246, 0.15);
      border-radius: 50%;
      text-align: center;
      line-height: 35px;
      margin-right: 10px;
      transition: 0.3s;
    }

    .social-icons a:hover {
      background: #3b82f6;
      transform: translateY(-3px);
    }

    .copyright {
      border-top: 1px solid rgba(148, 163, 184, 0.1);
      padding-top: 20px;
      margin-top: 40px;
      text-align: center;
      color: #64748b;
      font-size: 0.8rem;
    }

    @media (max-width: 768px) {
      .hero-title {
        font-size: 2.2rem;
      }
      .hero-stats {
        gap: 20px;
      }
      .cta-section {
        padding: 40px 20px;
      }
      .cta-title {
        font-size: 1.5rem;
      }
    }
  </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>">BookStar</a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="#categories">Kategori</a></li>
        <li class="nav-item"><a class="nav-link" href="#products">Buku</a></li>
        <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
      </ul>
      <div>
        <a href="<?= BASE_URL ?>/?c=auth&m=login" class="btn btn-outline-light me-2">Login</a>
        <a href="<?= BASE_URL ?>/?c=auth&m=register" class="btn btn-main">Daftar</a>
      </div>
    </div>
  </div>
</nav>

<!-- HERO SECTION -->
<section id="home" class="hero-section">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <span class="hero-badge">✨ #1 Toko Buku Online Terpercaya</span>
        <h1 class="hero-title">
          Dunia Buku dalam<br>
          Genggamanmu
        </h1>
        <p class="hero-subtitle">
          Temukan ribuan buku terbaik dari berbagai kategori. Nikmati pengalaman belanja 
          buku yang mudah, cepat, dan menyenangkan hanya di BookStar.
        </p>
        <div class="d-flex gap-3">
          <a href="<?= BASE_URL ?>/?c=auth&m=register" class="btn btn-main px-4 py-3">
            Mulai Sekarang →
          </a>
          <a href="#products" class="btn btn-outline-light px-4 py-3">
            Lihat Buku
          </a>
        </div>
        
        <div class="hero-stats">
          <div class="stat-item">
            <h3>10K+</h3>
            <p>Buku Tersedia</p>
          </div>
          <div class="stat-item">
            <h3>5K+</h3>
            <p>Member Aktif</p>
          </div>
          <div class="stat-item">
            <h3>100%</h3>
            <p>Kepuasan</p>
          </div>
        </div>
      </div>

      <div class="col-lg-6 text-center hero-image">
        <img src="<?= BASE_URL ?>/assets/images/auth/login1.png" class="img-fluid" style="max-height: 450px;">
      </div>
    </div>
  </div>
</section>

<!-- FEATURE SECTION -->
<section id="features" class="container py-5">
  <div class="section-header">
    <h4 class="section-title">Kenapa BookStar?</h4>
    <p class="section-subtitle">Nikmati pengalaman belanja buku terbaik bersama kami</p>
  </div>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="feature-card">
        <div class="feature-icon">🚀</div>
        <h5 class="feature-title">Cepat & Mudah</h5>
        <p class="feature-desc">Proses belanja simpel tanpa ribet, pesanan diproses cepat</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-card">
        <div class="feature-icon">📚</div>
        <h5 class="feature-title">Koleksi Lengkap</h5>
        <p class="feature-desc">Ribuan judul buku dari berbagai kategori tersedia</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-card">
        <div class="feature-icon">🔒</div>
        <h5 class="feature-title">Aman & Terpercaya</h5>
        <p class="feature-desc">Transaksi aman dengan sistem pembayaran terenkripsi</p>
      </div>
    </div>
  </div>
</section>

<!-- KATEGORI SECTION -->
<section id="categories" class="container py-5">
  <div class="section-header">
    <h4 class="section-title">Kategori Populer</h4>
    <p class="section-subtitle">Temukan buku favoritmu berdasarkan kategori</p>
  </div>

  <div class="row g-4">
    <?php foreach ($categories as $index => $cat): ?>
      <div class="col-md-3 col-6 category-wrapper">
        <div class="category-card">
          <div class="category-icon">
            <?php 
              $icons = ['📖', '📘', '📚', '📗', '📕', '📙', '📓', '📔'];
              echo $icons[$index % count($icons)];
            ?>
          </div>
          <div class="category-name"><?= htmlspecialchars($cat['name']) ?></div>
          <div class="category-count"><?= isset($cat['count']) ? $cat['count'] . ' Buku' : 'Tersedia' ?></div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- PRODUK SECTION -->
<section id="products" class="container py-5">
  <div class="section-header">
    <h4 class="section-title">Buku Terbaru 🔥</h4>
    <p class="section-subtitle">Rekomendasi buku terbaik untuk para pecinta buku</p>
  </div>

  <div class="row g-4">
    <?php foreach ($products as $p): ?>
      <div class="col-md-3 col-6">
        <div class="product-card">
          <div class="product-img-wrapper">
            <?php if (!empty($p['image'])): ?>
              <img src="<?= BASE_URL ?>/uploads/products/<?= $p['image'] ?>" class="product-img" alt="<?= htmlspecialchars($p['name']) ?>">
            <?php else: ?>
              <div class="d-flex align-items-center justify-content-center h-100" style="background: linear-gradient(135deg, #334155, #1e293b);">
                <span style="font-size: 3rem;">📘</span>
              </div>
            <?php endif; ?>
            <span class="product-badge">New</span>
          </div>
          
          <div class="product-body">
            <div class="product-category"><?= htmlspecialchars($p['category_name'] ?? 'Buku') ?></div>
            <h6 class="product-title"><?= htmlspecialchars($p['name']) ?></h6>
            <div class="product-price">Rp <?= number_format($p['price'], 0, ',', '.') ?></div>
            <a href="<?= BASE_URL ?>/?c=auth&m=login" class="btn btn-detail">
              Lihat Detail →
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- CTA SECTION -->
<section class="container">
  <div class="cta-section text-center">
    <h2 class="cta-title">Mulai Perjalanan Membaca Kamu 🚀</h2>
    <p class="hero-subtitle mb-4" style="max-width: 600px; margin: 0 auto 30px;">
      Ribuan buku menunggu untuk kamu jelajahi. Daftar sekarang!
    </p>
    <a href="<?= BASE_URL ?>/?c=auth&m=register" class="btn btn-main px-5 py-3">
      Daftar Sekarang →
    </a>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-md-4 mb-4">
        <div class="footer-brand">📚 BookStar</div>
        <p class="footer-text">
          Toko Buku Online Modern dengan koleksi terlengkap dan pelayanan terbaik untuk para pecinta buku di Indonesia.
        </p>
        <div class="social-icons mt-3">
          <a href="#" class="d-inline-block text-white">📘</a>
          <a href="#" class="d-inline-block text-white">📷</a>
          <a href="#" class="d-inline-block text-white">🐦</a>
          <a href="#" class="d-inline-block text-white">💬</a>
        </div>
      </div>
      <div class="col-md-2 mb-4">
        <h6 class="footer-title">Tentang</h6>
        <ul class="footer-links">
          <li><a href="#">Tentang Kami</a></li>
          <li><a href="#">Karir</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Press Kit</a></li>
        </ul>
      </div>
      <div class="col-md-3 mb-4">
        <h6 class="footer-title">Bantuan</h6>
        <ul class="footer-links">
          <li><a href="#">FAQ</a></li>
          <li><a href="#">Kebijakan Privasi</a></li>
          <li><a href="#">Syarat & Ketentuan</a></li>
          <li><a href="#">Pengembalian Barang</a></li>
        </ul>
      </div>
      <div class="col-md-3 mb-4">
        <h6 class="footer-title">Kontak</h6>
        <ul class="footer-links">
          <li><a href="#">📧 cs@bookstar.com</a></li>
          <li><a href="#">📞 (021) 1234-5678</a></li>
          <li><a href="#">💬 Live Chat 24/7</a></li>
          <li><a href="#">📍 Jakarta, Indonesia</a></li>
        </ul>
      </div>
    </div>
    <div class="copyright">
      <p class="mb-0">© 2026 BookStar — Toko Buku Online Modern. All rights reserved.</p>
    </div>
  </div>
</footer>

<script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>

<script>
  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // Navbar background change on scroll
  window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
      navbar.style.background = 'rgba(15, 23, 42, 0.98)';
      navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,0.2)';
    } else {
      navbar.style.background = 'rgba(15, 23, 42, 0.95)';
      navbar.style.boxShadow = 'none';
    }
  });
</script>

</body>
</html>