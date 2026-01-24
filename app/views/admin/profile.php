<?php require APP_PATH . '/views/layouts/admin/header.php'; ?>
<?php require APP_PATH . '/views/layouts/admin/sidebar.php'; ?>
<?php
$photo = !empty($user['photo'])
    ? BASE_URL . '/uploads/profile/' . $user['photo']
    : 'https://placehold.co/110x110/png';
?>


<main class="main-wrapper">
    <div class="main-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">My Profile</div>
        </div>
        <!--end breadcrumb-->

        <div class="card rounded-4">
            <div class="card-body p-4">
                <div class="position-relative mb-5">
                    <img src="" class="img-fluid rounded-4 shadow" alt="">
                    <div class="profile-avatar position-absolute top-100 start-50 translate-middle">
                        <img src="<?= htmlspecialchars($photo) ?>"
                            class="img-fluid rounded-circle p-1 bg-grd-danger shadow"
                            width="170" height="170" alt="Profile Photo">
                    </div>

                </div>
                <div class="profile-info pt-5 d-flex align-items-center justify-content-between">
                    <?php
                    $name = $user['name'] ?? 'No Name';
                    $role = $user['role_name'] ?? 'No Role';
                    ?>
                    <div>
                        <h3><?= htmlspecialchars($name) ?></h3>
                        <p class="mb-0"><?= htmlspecialchars($role) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 col-xl-8">
                <div class="card rounded-4 border-top border-4 border-primary border-gradient-1">
                    <div class="card-body p-4">
                        <h5 class="mb-3 fw-bold">Edit Profile</h5>
                        <form method="POST" action="?c=admin&m=updateProfile" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($role) ?>" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Photo Profile</label>
                                <input type="file" name="photo" class="form-control"
                                    accept="image/png, image/jpeg">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Leave blank if not changing">
                            </div>

                            <button type="submit" class="btn btn-grd-primary px-4">Update Profile</button>
                            <button type="reset" class="btn btn-light px-4">Reset</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card rounded-4">
                    <div class="card-body">
                        <h5 class="mb-3 fw-bold">About</h5>
                        <div class="info-list d-flex flex-column gap-3">
                            <div class="info-list-item d-flex align-items-center gap-3">
                                <span class="material-icons-outlined">account_circle</span>
                                <p class="mb-0">Name: <?= htmlspecialchars($name) ?></p>
                            </div>
                            <div class="info-list-item d-flex align-items-center gap-3">
                                <span class="material-icons-outlined">code</span>
                                <p class="mb-0">Role: <?= htmlspecialchars($role) ?></p>
                            </div>
                            <div class="info-list-item d-flex align-items-center gap-3">
                                <span class="material-icons-outlined">email</span>
                                <p class="mb-0">Email: <?= htmlspecialchars($email) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--end row-->

    </div>
</main>

<?php require APP_PATH . '/views/layouts/admin/footer.php'; ?>