<?php require APP_PATH . '/views/layouts/admin/header.php'; ?>
<?php require APP_PATH . '/views/layouts/admin/sidebar.php'; ?>

<main class="main-wrapper">
    <div class="main-content">

        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Produk Seller</div>

            <div class="ms-auto">
                <a href="<?= BASE_URL ?>/?c=admin&m=seller"
                   class="btn btn-secondary btn-sm">
                    ← Kembali ke Seller
                </a>
            </div>
        </div>

        <!-- Card -->
        <div class="card">
            <div class="card-body">

                <h5 class="mb-3">
                    Total Produk: <strong><?= count($products) ?></strong>
                </h5>

                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th width="150">Harga</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $i => $p): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($p['name']) ?></td>
                                    <td><?= htmlspecialchars($p['category_name']) ?></td>
                                    <td>
                                        Rp <?= number_format($p['price'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Seller belum memiliki produk
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
