<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>
<?php
$user = $_SESSION['user'] ?? [];

$name = $user['name'] ?? 'User';

$photo = !empty($user['photo'])
  ? BASE_URL . '/uploads/profile/' . $user['photo']
  : 'https://placehold.co/110x110/png';
?>
<?php if (!empty($_SESSION['success'])): ?>
  <script>
    Swal.fire({
      icon: 'success',
      title: 'Berhasil',
      text: '<?= $_SESSION['success']; ?>',
      timer: 2500,
      timerProgressBar: true,
      showConfirmButton: false
    });
  </script>
<?php unset($_SESSION['success']);
endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: '<?= $_SESSION['error']; ?>'
    });
  </script>
<?php unset($_SESSION['error']);
endif; ?>

<main class="main-wrapper">
  <div class="main-content">

    <!-- WELCOME CARD WITH IMPROVED DESIGN -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
          <div class="card-body p-4">
            <div class="d-flex align-items-center gap-4">
              <div class="position-relative">
                <img src="<?= htmlspecialchars($photo) ?>" width="80" height="80"
                     class="rounded-circle border border-3 border-white shadow-sm">
                <div class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-2 border-white">
                  <i class="ti ti-circle-check text-white" style="font-size: 12px;"></i>
                </div>
              </div>
              <div class="flex-grow-1">
                <p class="mb-1 text small">Selamat datang kembali di BookStar 👋</p>
                <h3 class="fw-bold mb-2">Halo, <?= htmlspecialchars($name) ?>!</h3>
                <p class="mb-0 text">Kami senang melihat Anda kembali. Ayo lanjutkan perjalanan membaca Anda!</p>
              </div>
              <div class="d-none d-lg-block">
                <i class="ti ti-books fs-1 text-primary opacity-25"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- STATISTICS CARDS WITH IMPROVED LAYOUT -->
    <div class="row g-4 mb-4">
      <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                <i class="ti ti-shopping-cart fs-3 text-primary"></i>
              </div>
              <span class="badge bg-primary bg-opacity-25 text-primary">Hari Ini</span>
            </div>
            <h2 class="fw-bold mb-1"><?= $stats['cart_items'] ?></h2>
            <p class="text mb-0">Produk di Keranjang</p>
            <div class="progress mt-3" style="height: 4px;">
              <div class="progress-bar bg-primary" style="width: 75%"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="bg-info bg-opacity-10 p-3 rounded-3">
                <i class="ti ti-package fs-3 text-info"></i>
              </div>
              <span class="badge bg-info bg-opacity-25 text-info">Total</span>
            </div>
            <h2 class="fw-bold mb-1"><?= $stats['total_orders'] ?></h2>
            <p class="text mb-0">Total Pesanan</p>
            <div class="progress mt-3" style="height: 4px;">
              <div class="progress-bar bg-info" style="width: 85%"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                <i class="ti ti-clock fs-3 text-warning"></i>
              </div>
              <span class="badge bg-warning bg-opacity-25 text-warning">Menunggu</span>
            </div>
            <h2 class="fw-bold mb-1"><?= $stats['pending_orders'] ?></h2>
            <p class="text mb-0">Pesanan Pending</p>
            <div class="progress mt-3" style="height: 4px;">
              <div class="progress-bar bg-warning" style="width: 45%"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift">
          <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <div class="bg-success bg-opacity-10 p-3 rounded-3">
                <i class="ti ti-check fs-3 text-success"></i>
              </div>
              <span class="badge bg-success bg-opacity-25 text-success">Selesai</span>
            </div>
            <h2 class="fw-bold mb-1"><?= $stats['completed_orders'] ?></h2>
            <p class="text mb-0">Pesanan Selesai</p>
            <div class="progress mt-3" style="height: 4px;">
              <div class="progress-bar bg-success" style="width: 95%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- QUICK ACTION CARD WITH IMPROVED DESIGN -->
<div class="row mt-2">
  <div class="col-12">
    <div class="card border-0 rounded-4 overflow-hidden bg-primary text-white">
      <div class="card-body p-4 p-lg-5">
        <div class="row align-items-center">
          <div class="col-lg-8">
            <h4 class="fw-bold mb-2">Mau belanja lagi? 🛍️</h4>
            <p class="mb-3 mb-lg-0 opacity-90">
              Jelajahi koleksi buku terbaru kami. Temukan buku favoritmu dan mulai petualangan membaca yang baru!
            </p>
          </div>
          <div class="col-lg-4 text-lg-end">
            <a href="<?= BASE_URL ?>/?c=customer&m=order" 
               class="btn btn-light btn-lg rounded-pill px-4 shadow-sm hover-lift text-primary fw-semibold">
              <i class="ti ti-shopping-cart me-2"></i>Mulai Belanja
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

    <!-- QUICK LINKS SECTION -->
    <div class="row mt-5">
      <div class="col-12">
        <h5 class="fw-bold mb-4">Akses Cepat</h5>
        <div class="row g-3">
          <div class="col-md-4">
            <a href="<?= BASE_URL ?>/?c=customer&m=cart" class="text-decoration-none">
              <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                      <i class="ti ti-shopping-cart fs-4 text-primary"></i>
                    </div>
                    <div>
                      <h6 class="fw-bold mb-1">Keranjang Belanja</h6>
                      <p class="text small mb-0">Lihat dan kelola produk di keranjang</p>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>
          
          <div class="col-md-4">
            <a href="<?= BASE_URL ?>/?c=customer&m=orders" class="text-decoration-none">
              <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 p-3 rounded-3">
                      <i class="ti ti-package fs-4 text-info"></i>
                    </div>
                    <div>
                      <h6 class="fw-bold mb-1">Status Pesanan</h6>
                      <p class="text small mb-0">Cek status pesanan Anda</p>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>
          
          <div class="col-md-4">
            <a href="<?= BASE_URL ?>/?c=customer&m=chat" class="text-decoration-none">
              <div class="card border-0 shadow-sm rounded-4 h-100 hover-lift">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 p-3 rounded-3">
                      <i class="ti ti-message-circle fs-4 text-success"></i>
                    </div>
                    <div>
                      <h6 class="fw-bold mb-1">Chat & Bantuan</h6>
                      <p class="text small mb-0">Butuh bantuan? Hubungi kami</p>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<style>
.hover-lift {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
  transform: translateY(-4px);
  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
.bg-opacity-10 {
  background-color: rgba(var(--bs-primary-rgb), 0.1);
}
.bg-opacity-25 {
  background-color: rgba(var(--bs-primary-rgb), 0.25);
}
.text-primary {
  --bs-primary-rgb: 13, 110, 253;
}
.text-info {
  --bs-info-rgb: 13, 202, 240;
}
.text-warning {
  --bs-warning-rgb: 255, 193, 7;
}
.text-success {
  --bs-success-rgb: 25, 135, 84;
}
</style>

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>