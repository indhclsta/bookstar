<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<?php
$photo = !empty($user['photo'])
  ? BASE_URL . '/uploads/profile/' . $user['photo']
  : 'https://placehold.co/150x150/png';

$noRekening = $user['no_rekening'] ?? '-';

$qrisImage = (!empty($user['qris_image']) 
  && file_exists(APP_PATH . '/../public/uploads/qris/' . $user['qris_image']))
  ? BASE_URL . '/uploads/qris/' . $user['qris_image']
  : null;

$name  = $user['name'] ?? '-';
$email = $user['email'] ?? '-';
$no_tlp = $user['no_tlp'] ?? '-';
$nik = $user['nik'] ?? '-';
$role  = ucfirst($user['role_name'] ?? 'Seller');
?>

<main class="main-wrapper">
  <div class="main-content">

    <div class="page-breadcrumb mb-3">
      <h4>My Profile</h4>
    </div>

    <!-- PROFILE CARD -->
    <div class="card rounded-4 mb-4">
      <div class="card-body text-center">
        <img src="<?= htmlspecialchars($photo) ?>"
          class="rounded-circle mb-3 border"
          width="140" height="140">

        <h4 class="mb-0"><?= htmlspecialchars($name) ?></h4>
        <p class="text-muted"><?= htmlspecialchars($role) ?></p>
      </div>
    </div>

    <div class="row">
      <!-- EDIT PROFILE -->
      <div class="col-lg-8">
        <div class="card rounded-4">
          <div class="card-body">
            <h5 class="mb-3">Edit Profile</h5>

            <form method="POST"
              action="<?= BASE_URL ?>/?c=seller&m=updateProfile"
              enctype="multipart/form-data">

              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control"
                  value="<?= htmlspecialchars($name) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                  value="<?= htmlspecialchars($email) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" name="no_tlp" class="form-control"
                  value="<?= htmlspecialchars($no_tlp) ?>" required>
              </div>  

              <div class="mb-3">
                <label class="form-label">Photo</label>
                <input type="file" name="photo" class="form-control"
                  accept="image/png,image/jpeg">
              </div>

              <div class="mb-3">
                <label class="form-label">Upload QRIS</label>
                <input type="file"
                  name="qris_image"
                  id="qrisInput"
                  class="form-control"
                  accept="image/png,image/jpeg">
              </div>

              <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="password"
                  class="form-control"
                  placeholder="Leave blank if unchanged">
              </div>

              <div class="mb-3">
                <label class="form-label">No Rekening</label>
                <input type="text" name="no_rekening"
                  class="form-control"
                  value="<?= htmlspecialchars($noRekening) ?>">
              </div>

              <button class="btn btn-primary px-4">Update Profile</button>
            </form>
          </div>
        </div>
      </div>

      <!-- SIDE INFO -->
      <div class="col-lg-4">
        <div class="card rounded-4">
          <div class="card-body">
            <h5 class="mb-3">About</h5>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($no_tlp) ?></p>
            <p><strong>NIK:</strong> <?= htmlspecialchars($nik) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>
          </div>
        </div>

        <!-- QRIS CARD -->
        <div class="card rounded-4 mt-3">
          <div class="card-body text-center">
            <h5 class="mb-3">QRIS Payment</h5>

            <?php if ($qrisImage): ?>
              <img src="<?= htmlspecialchars($qrisImage) ?>"
                class="img-fluid rounded border mb-3"
                style="max-width:240px;"
                alt="QRIS Seller">
            <?php else: ?>
              <p class="text-muted mb-3">
                QRIS belum diupload oleh seller
              </p>
            <?php endif; ?>

            <p class="mb-1"><strong>No Rekening</strong></p>
            <p class="fw-semibold">
              <?= htmlspecialchars($noRekening ?: '-') ?>
            </p>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<script>
document.getElementById('qrisInput')?.addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;

  if (!file.type.startsWith('image/')) {
    alert('File harus berupa gambar');
    e.target.value = '';
    return;
  }

  const preview = document.getElementById('qrisPreview');
  preview.src = URL.createObjectURL(file);
  preview.classList.remove('d-none');
});
</script>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>
