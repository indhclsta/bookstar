<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>



<main class="main-wrapper">
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mt-5">
            <?= $_SESSION['error'];
            unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show mt-5">
            <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <div class="main-content">


        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Manage Category</div>

            <div class="ms-auto">

                <div class="input-group">
                    <form method="POST"
                        action="<?= BASE_URL ?>/?c=sellerCategory&m=store"
                        class="d-flex w-100 gap-2">

                        <input type="text"
                            name="name"
                            class="form-control"
                            placeholder="Input category name..."
                            required>

                        <button type="submit" class="btn btn-grd-danger">
                            Save
                        </button>

                    </form>
                </div>


            </div>
        </div>

        <!-- ================= TABLE ================= -->
        <div class="card">
            <div class="card-body">

                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">No</th>
                            <th>Category Name</th>
                            <th width="160" class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $i => $cat): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($cat['name']) ?></td>
                                    <td class="text-center">

                                        <?php if ($cat['owner_role'] === 'seller'): ?>

                                            <!-- EDIT -->
                                            <button class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCategory<?= $cat['id'] ?>">
                                                Update
                                            </button>

                                            <!-- DELETE -->
                                            <a href="<?= BASE_URL ?>/?c=sellerCategory&m=delete&id=<?= $cat['id'] ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Hapus category ini?')">
                                                Delete
                                            </a>

                                            <!-- ================= MODAL EDIT (SELLER ONLY) ================= -->
                                            <div class="modal fade" id="editCategory<?= $cat['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <form method="POST"
                                                        action="<?= BASE_URL ?>/?c=sellerCategory&m=update"
                                                        class="modal-content">

                                                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">

                                                        <div class="modal-header bg-warning">
                                                            <h5 class="modal-title">Update Category</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <label class="form-label">Category Name</label>
                                                            <input type="text"
                                                                name="name"
                                                                class="form-control"
                                                                value="<?= htmlspecialchars($cat['name']) ?>"
                                                                required>
                                                            <small class="text-muted">
                                                                Category name harus unik (per seller)
                                                            </small>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" data-bs-dismiss="modal">
                                                                Cancel
                                                            </button>
                                                            <button class="btn btn-warning">
                                                                Update
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        <?php else: ?>

                                            <!-- KATEGORI ADMIN (READ ONLY) -->
                                            <span class="badge bg-secondary">
                                                Main Category
                                            </span>

                                        <?php endif; ?>

                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    No categories found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</main>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>