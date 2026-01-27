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
            <div class="card mb-3 border rounded-3">
                <div class="card-body table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead class="table-light">
                            <tr class="small text-muted">
                                <th>Kode</th>
                                <th>Produk</th>
                                <th>Seller</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
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
                            <?php foreach ($orders as $o): ?>
                                <tr>
                                    <td><?= $o['order_code'] ?></td>
                                    <td><?= htmlspecialchars($o['product_title']) ?></td>
                                    <td><?= htmlspecialchars($o['seller_name']) ?></td>
                                    <td><?= $o['quantity'] ?></td>
                                    <td>Rp.<?= number_format($o['price'], 2) ?></td>
                                    <td>Rp.<?= number_format($o['price'] * $o['quantity'], 2) ?></td>
                                    <td>
                                        <span class="badge bg-<?=
                                                                $o['approval_status'] === 'approved' ? 'success' : ($o['approval_status'] === 'rejected' ? 'danger' : 'warning')
                                                                ?>">
                                            <?= ucfirst($o['approval_status'] ?? 'pending') ?>
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td><?= ucfirst($o['order_status'] ?? '-') ?></td>
                                    <td><?= strtoupper($o['payment_method'] ?? '-') ?></td>
                                    <td>
                                        <?php if (!empty($o['payment_proof'])): ?>
                                            <img src="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?>" width="50">
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
                                                Lacak Paket
                                            </a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/?c=customerOrder&m=downloadInvoice&id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-primary">Invoice</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>