<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper">
    <div class="main-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Seller List</div>
        </div>

        <div class="row row-cols-1 row-cols-xl-2 g-4">

            <?php if (!empty($sellers)): ?>
                <?php foreach ($sellers as $seller): ?>
                    <?php
                    $photo = !empty($seller['photo'])
                        ? BASE_URL . '/uploads/profile/' . $seller['photo']
                        : 'https://placehold.co/400x300/png';

                    $isOnline = false;
                    if (!empty($seller['last_activity'])) {
                        $isOnline = (time() - strtotime($seller['last_activity'])) <= 180;
                    }

                    $badgeBg = $isOnline ? 'bg-success' : 'bg-secondary';
                    $statusText = $isOnline ? 'ONLINE' : 'OFFLINE';
                    ?>

                    <div class="col">
                        <div class="card rounded-4 h-100 shadow-sm">
                            <div class="row g-0 h-100 align-items-center">

                                <!-- FOTO -->
                                <div class="col-md-4 border-end p-3">
                                    <img src="<?= htmlspecialchars($photo) ?>"
                                         class="w-100 rounded">
                                </div>

                                <!-- DATA -->
                                <div class="col-md-8">
                                    <div class="card-body">

                                        <h5><?= htmlspecialchars($seller['name']) ?></h5>

                                        <p><strong>Email:</strong>
                                            <?= htmlspecialchars($seller['email']) ?>
                                        </p>

                                        <p><strong>NIK:</strong>
                                            <?= htmlspecialchars($seller['nik'] ?? '-') ?>
                                        </p>

                                        <p><strong>Alamat:</strong>
                                            <?= htmlspecialchars($seller['address'] ?? '-') ?>
                                        </p>

                                        <p><strong>No Rekening:</strong>
                                            <?= htmlspecialchars($seller['no_rekening'] ?? '-') ?>
                                        </p>

                                        <span class="badge <?= $badgeBg ?>">
                                            <?= $statusText ?>
                                        </span>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col">
                    <div class="alert alert-warning text-center">
                        Belum ada seller
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</main>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>
