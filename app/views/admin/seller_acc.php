<?php require APP_PATH . '/views/layouts/admin/header.php'; ?>
<?php require APP_PATH . '/views/layouts/admin/sidebar.php'; ?>

<main class="main-wrapper">
    <div class="main-content">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Seller Account</div>
            <div class="ms-auto">
                <button class="btn btn-grd-primary px-4"
                    data-bs-toggle="modal"
                    data-bs-target="#createSellerModal">
                    Create
                </button>

            </div>
        </div>

        <!-- Alert -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Seller List -->
        <div class="row row-cols-1 row-cols-xl-2 g-4">

            <?php if (!empty($sellers)): ?>
                <?php foreach ($sellers as $seller): ?>

                    <?php
                    $photo = !empty($seller['photo'])
                        ? BASE_URL . '/uploads/profile/' . $seller['photo']
                        : 'https://placehold.co/400x300/png';

                    $isOnline   = (int)$seller['is_online'] === 1;
                    $badgeBg    = $isOnline ? 'bg-success' : 'bg-danger';
                    $statusText = $isOnline ? 'ONLINE' : 'OFFLINE';
                    ?>

                    <div class="col">
                        <div class="card rounded-4 h-100 shadow-sm">
                            <div class="row g-0 h-100 align-items-center">

                                <!-- Photo -->
                                <div class="col-md-4 border-end p-3">
                                    <img src="<?= htmlspecialchars($photo) ?>"
                                        class="w-100 rounded"
                                        alt="Seller Photo">
                                </div>

                                <!-- Info -->
                                <div class="col-md-8">
                                    <div class="card-body">

                                        <h5 class="card-title mb-2">
                                            <?= htmlspecialchars($seller['name']) ?>
                                        </h5>

                                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($seller['email']) ?></p>
                                        <p class="mb-1"><strong>NIK:</strong> <?= htmlspecialchars($seller['nik'] ?? '-') ?></p>
                                        <p class="mb-2"><strong>Alamat:</strong> <?= htmlspecialchars($seller['address'] ?? '-') ?></p>

                                        <span class="badge <?= $badgeBg ?>"><?= $statusText ?></span>

                                        <!-- Actions -->
                                        <div class="mt-4 d-flex gap-2 flex-wrap">

                                            <!-- EDIT -->
                                            <button class="btn btn-outline-primary btn-sm btn-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editSellerModal"
                                                data-id="<?= $seller['id'] ?>"
                                                data-name="<?= htmlspecialchars($seller['name']) ?>"
                                                data-email="<?= htmlspecialchars($seller['email']) ?>"
                                                data-nik="<?= htmlspecialchars($seller['nik']) ?>"
                                                data-address="<?= htmlspecialchars($seller['address']) ?>"
                                                data-photo="<?= htmlspecialchars($seller['photo']) ?>">
                                                Edit
                                            </button>

                                            <!-- DELETE -->
                                            <?php if (!$isOnline): ?>
                                                <a href="?c=admin&m=sellerDelete&id=<?= $seller['id'] ?>"
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Yakin hapus seller ini?')">
                                                    Delete
                                                </a>
                                            <?php endif; ?>

                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <div class="col">
                    <div class="alert alert-info text-center">
                        Data seller belum tersedia.
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<!-- ================= MODAL CREATE SELLER ================= -->
<div class="modal fade" id="createSellerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST"
            action="<?= BASE_URL ?>/?c=admin&m=sellerStore"
            enctype="multipart/form-data"
            class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Seller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text"
                            name="name"
                            class="form-control"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email"
                            name="email"
                            class="form-control"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <div class="input-group show-hide-password">
                            <input type="password"
                                name="password"
                                class="form-control border-end-0"
                                required>
                            <button type="button" class="input-group-text bg-transparent toggle-password">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <label class="form-label">NIK</label>
                        <input type="text"
                            name="nik"
                            class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="address"
                            class="form-control"
                            rows="3"></textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Foto (opsional)</label>
                        <input type="file"
                            name="photo"
                            class="form-control"
                            accept="image/*">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="submit"
                    class="btn btn-primary">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>


<!-- ================= MODAL EDIT SELLER ================= -->
<div class="modal fade" id="editSellerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST"
            action="<?= BASE_URL ?>/?c=admin&m=sellerUpdate"
            enctype="multipart/form-data"
            class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Akun Seller</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id" id="edit-id">
                <input type="hidden" name="old_photo" id="edit-old-photo">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" id="edit-name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit-email" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <div class="input-group show-hide-password">
                            <input type="password"
                                name="password"
                                class="form-control border-end-0"
                                required>
                            <button type="button" class="input-group-text bg-transparent toggle-password">
                                <i class="bi bi-eye-slash"></i>
                            </button>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" id="edit-nik" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Foto</label>
                        <input type="file" name="photo" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak diganti</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" id="edit-address" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>

        </form>
    </div>
</div>

<!--plugins-->
<script src="<?= BASE_URL ?>/assets/js/jquery.min.js"></script>

<script>
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-name').value = this.dataset.name;
            document.getElementById('edit-email').value = this.dataset.email;
            document.getElementById('edit-nik').value = this.dataset.nik;
            document.getElementById('edit-address').value = this.dataset.address;
            document.getElementById('edit-old-photo').value = this.dataset.photo;
        });
    });
    document.querySelector('input[name="photo"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const preview = document.createElement('img');
        preview.src = URL.createObjectURL(file);
        preview.className = 'img-fluid rounded mt-2';
        preview.style.maxHeight = '200px';

        this.parentElement.appendChild(preview);
    });
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    });
</script>

<?php require APP_PATH . '/views/layouts/admin/footer.php'; ?>