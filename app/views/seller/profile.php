<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<?php
$photo = !empty($user['photo'])
    ? BASE_URL . '/uploads/profile/' . $user['photo']
    : 'https://placehold.co/150x150/png';

$name  = $user['name'] ?? '-';
$email = $user['email'] ?? '-';
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
                <input type="text"
                       name="name"
                       class="form-control"
                       value="<?= htmlspecialchars($name) ?>"
                       required>
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email"
                       name="email"
                       class="form-control"
                       value="<?= htmlspecialchars($email) ?>"
                       required>
              </div>

              <div class="mb-3">
                <label class="form-label">Photo</label>
                <input type="file"
                       name="photo"
                       class="form-control"
                       accept="image/png,image/jpeg">
              </div>

              <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Leave blank if unchanged">
              </div>

              <button class="btn btn-primary px-4">Update Profile</button>
            </form>
          </div>
        </div>
      </div>

      <!-- ABOUT -->
      <div class="col-lg-4">
        <div class="card rounded-4">
          <div class="card-body">
            <h5 class="mb-3">About</h5>
            <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($role) ?></p>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>
