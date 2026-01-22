<?php require APP_PATH . '/views/layouts/admin/header.php'; ?>
<?php require APP_PATH . '/views/layouts/admin/sidebar.php'; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<main class="main-wrapper">
<div class="main-content">

    <!-- Breadcrumb -->
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Manage Category</div>

        <div class="ms-auto">
            <button class="btn btn-grd-primary px-4"
                    data-bs-toggle="modal"
                    data-bs-target="#createCategoryModal">
                Create
            </button>
        </div>
    </div>

    <!-- ================= MODAL CREATE ================= -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST"
                  action="<?= BASE_URL ?>/?c=adminCategory&m=store"
                  class="modal-content">

                <div class="modal-header bg-grd-info">
                    <h5 class="modal-title">Create Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Category Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           required>
                    <small class="text-muted">Category name harus unik</small>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-grd-danger">Save</button>
                </div>
            </form>
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

                                <!-- EDIT -->
                                <button class="btn btn-warning btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editCategory<?= $cat['id'] ?>">
                                    Edit
                                </button>

                                <!-- DELETE -->
                                <a href="<?= BASE_URL ?>/?c=adminCategory&m=delete&id=<?= $cat['id'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Hapus category ini?')">
                                    Delete
                                </a>
                            </td>
                        </tr>

                        <!-- ================= MODAL EDIT ================= -->
                        <div class="modal fade" id="editCategory<?= $cat['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <form method="POST"
                                      action="<?= BASE_URL ?>/?c=adminCategory&m=update"
                                      class="modal-content">

                                    <input type="hidden" name="id" value="<?= $cat['id'] ?>">

                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title">Edit Category</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <label class="form-label">Category Name</label>
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               value="<?= htmlspecialchars($cat['name']) ?>"
                                               required>
                                        <small class="text-muted">Category name harus unik</small>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button class="btn btn-warning">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>

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

<?php require APP_PATH . '/views/layouts/admin/footer.php'; ?>
