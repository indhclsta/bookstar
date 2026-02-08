<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<main class="main-wrapper py-4">
    <div class="container">

        <div class="page-breadcrumb mb-4">
            <h4 class="fw-bold">Daftar Pesanan</h4>
        </div>

        <?php if (empty($orders)): ?>
            <div class="alert alert-info">Belum ada transaksi 🛒</div>
        <?php else: ?>

            <?php
            // ===============================
            // PAGINATION SETUP
            // ===============================
            $perPage = 10;
            $totalData = count($orders);
            $totalPages = ceil($totalData / $perPage);

            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $page = max(1, min($page, $totalPages));

            $start = ($page - 1) * $perPage;
            $ordersPaged = array_slice($orders, $start, $perPage);
            ?>

            <div class="card mb-3 border rounded-3">
                <div class="card-body table-responsive">

                    <div class="small text mb-2">
                        Menampilkan <?= $start + 1 ?> –
                        <?= min($start + $perPage, $totalData) ?>
                        dari <?= $totalData ?> pesanan
                    </div>

                    <table class="table table-borderless align-middle">
                        <thead class="table-light">
                            <tr class="small text">
                                <th>Kode</th>
                                <th>Produk</th>
                                <th>Seller</th>
                                <th>Qty</th>
                                <th>Approval</th>
                                <th>Status</th>
                                <th>Metode</th>
                                <th>Bukti</th>
                                <th>Resi</th>
                                <th>Tracking</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($ordersPaged as $o): ?>
                                <tr>
                                    <td><?= $o['order_code'] ?></td>
                                    <td><?= htmlspecialchars($o['product_title']) ?></td>
                                    <td><?= htmlspecialchars($o['seller_name']) ?></td>
                                    <td><?= $o['quantity'] ?></td>

                                    <td>
                                        <span class="badge bg-<?=
                                                                $o['approval_status'] === 'approved' ? 'success' : ($o['approval_status'] === 'rejected' ? 'danger' : 'warning')
                                                                ?>">
                                            <?= ucfirst($o['approval_status'] ?? 'pending') ?>
                                        </span>
                                    </td>

                                    <td><?= ucfirst($o['order_status'] ?? '-') ?></td>
                                    <td><?= strtoupper($o['payment_method'] ?? '-') ?></td>

                                    <td>
                                        <?php if (!empty($o['payment_proof'])): ?>
                                            <img src="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?>"
                                                width="45" class="rounded border">
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                    <td><?= $o['resi'] ?? '-' ?></td>

                                    <td>
                                        <?php if (!empty($o['tracking_url'])): ?>
                                            <a href="<?= htmlspecialchars($o['tracking_url']) ?>"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-success">
                                                Lacak
                                            </a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <button class="btn btn-sm btn-outline-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal<?= $o['id'] ?>">
                                            Detail
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL DETAIL -->
                                <div class="modal fade" id="detailModal<?= $o['id'] ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content rounded-4 shadow-lg">

                                            <div class="modal-header bg-grd-primary text-white border-0">
                                                <div>
                                                    <h5 class="modal-title fw-bold mb-0">Order Summary</h5>
                                                    <small class="opacity-75"><?= $o['order_code'] ?></small>
                                                </div>
                                                <button type="button"
                                                    class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">

                                                <div class="mb-3">
                                                    <?php
                                                    $status = $o['approval_status'] ?? 'pending';
                                                    if ($status === 'approved') {
                                                        echo '<span class="badge bg-success">Approved <i class="bi bi-check2 ms-2"></i></span>';
                                                    } elseif ($status === 'rejected') {
                                                        echo '<span class="badge bg-danger">Rejected <i class="bi bi-x-lg ms-2"></i></span>';
                                                        if (!empty($o['reject_reason'])) {
                                                            echo '<div class="mt-1 small text-danger">Alasan: ' . htmlspecialchars($o['reject_reason']) . '</div>';
                                                        }
                                                    } else {
                                                        echo '<span class="badge bg-warning text-dark">Pending <i class="bi bi-info-circle ms-2"></i></span>';
                                                    }
                                                    ?>

                                                    <span class="badge bg-info ms-2">
                                                        <?= ucfirst($o['order_status'] ?? '-') ?>
                                                    </span>
                                                </div>


                                                <div class="card mb-3 border-0 bg-light">
                                                    <div class="card-body">
                                                        <h6 class="fw-semibold mb-2">Produk</h6>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <div class="fw-semibold">
                                                                    <?= htmlspecialchars($o['product_title']) ?>
                                                                </div>
                                                                <small class="text">
                                                                    Rp <?= number_format($o['price'], 0, ',', '.') ?>
                                                                    × <?= $o['quantity'] ?>
                                                                </small>
                                                            </div>
                                                            <div class="fw-bold text-primary">
                                                                Rp <?= number_format($o['price'] * $o['quantity'], 0, ',', '.') ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card mb-3 border-0 bg-light">
                                                    <div class="card-body small">
                                                        <p class="mb-1">
                                                            Seller
                                                            <span class="float-end fw-semibold">
                                                                <?= htmlspecialchars($o['seller_name']) ?>
                                                            </span>
                                                        </p>

                                                        <p class="mb-1">
                                                            Metode Pembayaran
                                                            <span class="float-end fw-semibold">
                                                                <?= strtoupper($o['payment_method']) ?>
                                                            </span>
                                                        </p>

                                                        <p class="mb-1">
                                                            Nomor Resi
                                                            <span class="float-end fw-semibold">
                                                                <?= $o['resi'] ?? '-' ?>
                                                            </span>
                                                        </p>

                                                        <hr>

                                                        <p class="mb-0 fw-bold">
                                                            Total
                                                            <span class="float-end text-primary">
                                                                Rp <?= number_format($o['price'] * $o['quantity'], 0, ',', '.') ?>
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="card border-0 bg-light">
                                                    <div class="card-body">
                                                        <h6 class="fw-semibold mb-2">Bukti Pembayaran</h6>
                                                        <?php if (!empty($o['payment_proof'])): ?>
                                                            <img src="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?>"
                                                                class="img-fluid rounded-3 border"
                                                                style="max-height:250px">
                                                        <?php else: ?>
                                                            <div class="text fst-italic">
                                                                Belum ada bukti pembayaran
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="modal-footer border-0">
                                                <?php if (!empty($o['tracking_url'])): ?>
                                                    <a href="<?= htmlspecialchars($o['tracking_url']) ?>"
                                                        target="_blank"
                                                        class="btn btn-success">
                                                        Lacak Paket
                                                    </a>
                                                <?php endif; ?>

                                                <button class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Tutup
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php
                    $baseUrl = BASE_URL . "/?c=customerOrder&m=index";
                    ?>

                    <?php if ($totalPages > 1): ?>
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center pagination-sm">

                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                        href="<?= $baseUrl ?>&page=<?= $page - 1 ?>">
                                        ‹
                                    </a>
                                </li>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                        <a class="page-link"
                                            href="<?= $baseUrl ?>&page=<?= $i ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                        href="<?= $baseUrl ?>&page=<?= $page + 1 ?>">
                                        ›
                                    </a>
                                </li>

                            </ul>
                        </nav>
                    <?php endif; ?>


                </div>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>