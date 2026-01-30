<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil 🎉',
            text: '<?= addslashes($_SESSION['success']) ?>',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal ❌',
            text: '<?= addslashes($_SESSION['error']) ?>',
            confirmButtonText: 'OK'
        });
    </script>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>


<main class="main-wrapper">
    <div class="main-content">

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

        <div class="row row-cols-1 row-cols-xl-2 g-4">

            <?php foreach ($sellers as $seller): ?>
                <?php
                $photo = !empty($seller['photo'])
                    ? BASE_URL . '/uploads/profile/' . $seller['photo']
                    : 'https://placehold.co/400x300/png';

                $isOnline = false;
                if (!empty($seller['last_activity'])) {
                    $isOnline = (time() - strtotime($seller['last_activity'])) <= 180;
                }

                $badgeBg = $isOnline ? 'bg-success' : 'bg-danger';
                $statusText = $isOnline ? 'ONLINE' : 'OFFLINE';
                ?>
                


                <div class="col">
                    <div class="card rounded-4 h-100 shadow-sm">
                        <div class="row g-0 h-100 align-items-center">

                            <div class="col-md-4 border-end p-3">
                                <img src="<?= htmlspecialchars($photo) ?>" class="w-100 rounded">
                            </div>

                            <div class="col-md-8">
                                <div class="card-body">

                                    <!-- HEADER -->
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="mb-0"><?= htmlspecialchars($seller['name']) ?></h5>

                                        <span class="badge <?= $badgeBg ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </div>

                                    <!-- JUMLAH PRODUK -->
                                    <div class="mb-2">
                                        <span class="badge bg-info">
                                            <?= (int)$seller['product_count'] ?> Produk
                                        </span>

                                        <a href="<?= BASE_URL ?>/?c=admin&m=sellerProducts&id=<?= $seller['id'] ?>"
                                            class="ms-2 small text-decoration-none">
                                            🔗 Lihat produk
                                        </a>
                                    </div>

                                    <hr class="my-2">

                                    <!-- INFO SELLER -->
                                    <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($seller['email']) ?></p>
                                    <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($seller['no_tlp'] ?? '-') ?></p>
                                    <p class="mb-1"><strong>NIK:</strong> <?= htmlspecialchars($seller['nik'] ?? '-') ?></p>
                                    <p class="mb-1"><strong>Alamat:</strong> <?= htmlspecialchars($seller['address'] ?? '-') ?></p>
                                    <p class="mb-2"><strong>No Rekening:</strong> <?= htmlspecialchars($seller['no_rekening'] ?? '-') ?></p>

                                    <!-- ACTION -->
                                    <div class="mt-3 d-flex gap-2">
                                        <button class="btn btn-outline-primary btn-sm btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editSellerModal"
                                            data-id="<?= $seller['id'] ?>"
                                            data-name="<?= htmlspecialchars($seller['name']) ?>"
                                            data-email="<?= htmlspecialchars($seller['email']) ?>"
                                            data-no-tlp="<?= htmlspecialchars($seller['no_tlp']) ?>"
                                            data-nik="<?= htmlspecialchars($seller['nik']) ?>"
                                            data-address="<?= htmlspecialchars($seller['address']) ?>"
                                            data-no-rekening="<?= htmlspecialchars($seller['no_rekening']) ?>"
                                            data-photo="<?= htmlspecialchars($seller['photo']) ?>">
                                            Edit
                                        </button>

                                        <?php if (!$isOnline): ?>
                                            <a href="#"
                                                class="btn btn-outline-danger btn-sm btn-delete-seller"
                                                data-url="<?= BASE_URL ?>/?c=admin&m=sellerDelete&id=<?= $seller['id'] ?>"
                                                data-name="<?= htmlspecialchars($seller['name']) ?>">
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
                        <label class="form-label">Phone</label>
                        <input type="text"
                            name="no_tlp"
                            class="form-control"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Foto (opsional)</label>
                        <input type="file"
                            name="photo"
                            class="form-control"
                            accept="image/*">
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

                    <div class="col-md-6">
                        <label class="form-label">No Rekening</label>
                        <input type="text"
                            name="no_rekening"
                            class="form-control"
                            placeholder="Contoh: 1234567890">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">QRIS Image</label>
                        <input type="file"
                            name="qris_image"
                            class="form-control"
                            accept="image/png,image/jpeg">
                    </div>



                </div>
            </div>

            <div class="modal-footer">

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
                        <label>Nama</label>
                        <input type="text" name="name" id="edit-name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" id="edit-email" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Phone</label>
                        <input type="text" name="no_tlp" id="edit-phone" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>NIK</label>
                        <input type="text" name="nik" id="edit-nik" class="form-control">
                    </div>

                    <!-- ✅ NO REKENING DI MODAL EDIT -->
                    <div class="col-md-6">
                        <label>No Rekening</label>
                        <input type="text" name="no_rekening" id="edit-no-rekening" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Foto</label>
                        <input type="file" name="photo" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Alamat</label>
                        <textarea name="address" id="edit-address" class="form-control"></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Update</button>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ================= DELETE SELLER =================
        document.querySelectorAll('.btn-delete-seller').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const url = this.dataset.url;
                const name = this.dataset.name;

                Swal.fire({
                    title: 'Yakin hapus seller?',
                    text: `Seller "${name}" akan dihapus permanen`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });

        // ================= EDIT SELLER =================
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-id').value = this.dataset.id;
                document.getElementById('edit-name').value = this.dataset.name;
                document.getElementById('edit-email').value = this.dataset.email;
                document.getElementById('edit-phone').value = this.dataset.noTlp;
                document.getElementById('edit-nik').value = this.dataset.nik;
                document.getElementById('edit-address').value = this.dataset.address;
                document.getElementById('edit-no-rekening').value = this.dataset.noRekening;
                document.getElementById('edit-old-photo').value = this.dataset.photo;
            });
        });

    });
</script>


<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>