<?php require APP_PATH . '/views/layouts/admin/header.php'; ?>
<?php require APP_PATH . '/views/layouts/admin/sidebar.php'; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<main class="main-wrapper">
  <div class="main-content">

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">FAQ Super Admin</div>
    </div>

    <div class="row">
      <div class="col-12 col-lg-9 mx-auto">
        <div class="text-center">
          <h5 class="mb-0 text-uppercase">Frequently Asked Questions</h5>
          <hr>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="accordion" id="accordionFAQ">

              <!-- Q1 -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="q1">
                  <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#a1">
                    Apa peran Super Admin dalam sistem BookStar?
                  </button>
                </h2>
                <div id="a1" class="accordion-collapse collapse show">
                  <div class="accordion-body">
                    Super Admin memiliki hak akses tertinggi untuk mengelola seluruh sistem, termasuk akun pengguna, kategori, dan pengaturan aplikasi.
                  </div>
                </div>
              </div>

              <!-- Q2 -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="q2">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#a2">
                    Apakah Super Admin bisa mendaftar akun baru?
                  </button>
                </h2>
                <div id="a2" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Tidak. Akun Super Admin hanya tersedia satu dan dibuat oleh sistem, tanpa fitur registrasi.
                  </div>
                </div>
              </div>

              <!-- Q3 -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="q3">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#a3">
                    Apa saja akun yang dapat dikelola Super Admin?
                  </button>
                </h2>
                <div id="a3" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Super Admin dapat mengelola akun Penjual dan Pembeli, termasuk melihat, mengedit, dan menghapus akun.
                  </div>
                </div>
              </div>

              <!-- Q4 -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="q4">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#a4">
                    Apakah Super Admin bisa menambah akun Pembeli?
                  </button>
                </h2>
                <div id="a4" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Tidak. Akun Pembeli hanya dapat dibuat melalui proses registrasi oleh pengguna itu sendiri.
                  </div>
                </div>
              </div>

              <!-- Q5 -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="q5">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#a5">
                    Bagaimana cara menghapus akun Penjual atau Pembeli?
                  </button>
                </h2>
                <div id="a5" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Akun dapat dihapus melalui menu manajemen pengguna apabila status akun tidak aktif atau melanggar ketentuan.
                  </div>
                </div>
              </div>

              <!-- Q6 -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="q6">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#a6">
                    Apa fungsi indikator status online dan offline?
                  </button>
                </h2>
                <div id="a6" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Warna hijau menandakan akun aktif (online) dan warna merah menandakan akun tidak aktif (offline).
                  </div>
                </div>
              </div>

              <!-- Q7 -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="q7">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#a7">
                    Apakah Super Admin bisa mengelola kategori buku?
                  </button>
                </h2>
                <div id="a7" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Ya. Super Admin dapat menambah, mengedit, dan menghapus kategori buku.
                  </div>
                </div>
              </div>

              <!-- Q8 -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="q8">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#a8">
                    Apakah Super Admin dapat melihat laporan transaksi?
                  </button>
                </h2>
                <div id="a8" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Super Admin dapat memantau data pengguna dan aktivitas sistem untuk memastikan aplikasi berjalan dengan baik.
                  </div>
                </div>
              </div>

              <!-- Q9 -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="q9">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#a9">
                    Bagaimana jika terjadi masalah pada sistem?
                  </button>
                </h2>
                <div id="a9" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Super Admin dapat melakukan pengecekan sistem atau menghubungi pengembang untuk perbaikan.
                  </div>
                </div>
              </div>

              <!-- Q10 -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="q10">
                  <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#a10">
                    Apakah Super Admin bisa mengelola FAQ?
                  </button>
                </h2>
                <div id="a10" class="accordion-collapse collapse">
                  <div class="accordion-body">
                    Ya. Super Admin bertanggung jawab mengelola FAQ agar informasi tetap akurat dan membantu pengguna.
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</main>

<?php require APP_PATH . '/views/layouts/admin/footer.php'; ?>
