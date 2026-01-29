<?php require APP_PATH . '/views/layouts/admin/header.php'; ?>
<?php require APP_PATH . '/views/layouts/admin/sidebar.php'; ?>

<main class="main-wrapper">
    <div class="main-content">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Customer Account</div>
        </div>

        <!-- Customer List -->
        <div class="row row-cols-1 row-cols-xl-2 g-4">

            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $customer): ?>

                    <?php
                    $photo = !empty($customer['photo'])
                        ? BASE_URL . '/uploads/profile/' . $customer['photo']
                        : 'https://placehold.co/400x300/png';

                    $isOnline = false;

                    if (!empty($customer['last_activity'])) {
                        $last = strtotime($customer['last_activity']);
                        $isOnline = (time() - $last) <= 180; // 3 menit
                    }

                    $badgeBg    = $isOnline ? 'bg-success' : 'bg-danger';
                    $statusText = $isOnline ? 'ONLINE' : 'OFFLINE';
                    ?>

                    <div class="col">
                        <div class="card rounded-4 h-100 shadow-sm">
                            <div class="row g-0 h-100 align-items-center">

                                <!-- Photo -->
                                <div class="col-md-4 border-end">
                                    <div class="p-3">
                                        <img src="<?= htmlspecialchars($photo) ?>"
                                            class="w-100 rounded-start"
                                            alt="Customer Photo">
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="col-md-8">
                                    <div class="card-body">

                                        <h5 class="card-title mb-2">
                                            <?= htmlspecialchars($customer['name']) ?>
                                        </h5>

                                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
                                        <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($customer['no_tlp'] ?? '-') ?></p>
                                        <p class="mb-1"><strong>NIK:</strong> <?= htmlspecialchars($customer['nik'] ?? '-') ?></p>
                                        <p class="mb-2"><strong>Alamat:</strong> <?= htmlspecialchars($customer['address'] ?? '-') ?></p>

                                        <span class="badge <?= $badgeBg ?>"><?= $statusText ?></span>

                                        <!-- Actions -->
                                        <div class="mt-4 d-flex gap-2 flex-wrap">

                                            <!-- EDIT -->
                                            <button
                                                class="btn btn-outline-primary btn-sm btn-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCustomerModal"
                                                data-id="<?= $customer['id'] ?>"
                                                data-name="<?= htmlspecialchars($customer['name']) ?>"
                                                data-email="<?= htmlspecialchars($customer['email']) ?>"
                                                data-no_tlp="<?= htmlspecialchars($customer['no_tlp']) ?>"
                                                data-nik="<?= htmlspecialchars($customer['nik']) ?>"
                                                data-address="<?= htmlspecialchars($customer['address']) ?>"
                                                data-photo="<?= htmlspecialchars($customer['photo']) ?>">
                                                Edit
                                            </button>

                                            <!-- DELETE -->
                                            <?php if (!$isOnline): ?>
                                                <a href="?c=admin&m=customerDelete&id=<?= $customer['id'] ?>"
                                                    class="btn btn-outline-danger btn-sm btn-delete-customer">
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
                        Data customer belum tersedia.
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</main>

<!-- ================= MODAL EDIT ================= -->
<div class="modal fade" id="editCustomerModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="POST"
            action="<?= BASE_URL ?>/?c=admin&m=customerUpdate"
            enctype="multipart/form-data"
            class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Akun Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" name="id" id="edit-id">

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
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" id="edit-nik" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="no_tlp" id="edit-no_tlp" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Foto</label>
                        <input type="file" name="photo" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak diganti</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" id="edit-address" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>

        </form>
    </div>
</div>

<!-- ================= SCRIPT ================= -->

<script>
    document.querySelectorAll('.btn-delete-customer').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            const card = this.closest('.col'); // ambil parent card

            Swal.fire({
                title: 'Yakin?',
                text: "Akun customer akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(href)
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                card.remove(); // hapus card dari UI
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message,
                                    timer: 3000,
                                    showConfirmButton: false
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan server',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        });
                }
            });
        });
    });


    <?php if (!empty($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '<?= $_SESSION['success'] ?>',
            timer: 2000,
            showConfirmButton: false
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= $_SESSION['error'] ?>',
            timer: 3000,
            showConfirmButton: false
        });
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</script>



<script>
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-name').value = this.dataset.name;
            document.getElementById('edit-email').value = this.dataset.email;
            document.getElementById('edit-nik').value = this.dataset.nik;
            document.getElementById('edit-no_tlp').value = this.dataset.no_tlp;
            document.getElementById('edit-address').value = this.dataset.address;
        });
    });
</script>

<?php require APP_PATH . '/views/layouts/admin/footer.php'; ?>