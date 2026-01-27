<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<main class="main-wrapper py-4">
    <div class="container">
        <div class="page-breadcrumb mb-4">
            <h4 class="fw-bold">Checkout</h4>
        </div>

        <?php if (empty($groupedCart)) : ?>
            <div class="alert alert-info">Cart kamu masih kosong 🛒</div>
        <?php else : ?>
            <form action="<?= BASE_URL ?>/?c=checkout&m=process" method="POST" enctype="multipart/form-data">

                <?php
                $grandTotal = 0;
                foreach ($groupedCart as $sellerId => $items) :
                    $seller = $this->userModel->findById($sellerId);
                    $subtotalSeller = array_reduce($items, fn($sum, $item) => $sum + $item['price'] * $item['quantity'], 0);
                    $grandTotal += $subtotalSeller;
                ?>
                    <div class="card mb-3 border rounded-3">
                        <div class="card-body p-3">

                            <h6 class="fw-bold mb-3">Seller: <?= htmlspecialchars($seller['name']) ?></h6>

                            <div class="table-responsive mb-2">
                                <table class="table table-borderless table-sm align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted small">
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($item['name']) ?></td>
                                                <td>Rp.<?= number_format($item['price'], 2) ?></td>
                                                <td><?= $item['quantity'] ?></td>
                                                <td>Rp.<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between fw-bold mt-2 mb-3">
                                <span>Total Seller:</span>
                                <span>Rp.<?= number_format($subtotalSeller, 2) ?></span>
                            </div>

                            <input type="hidden" name="seller_id[]" value="<?= $sellerId ?>">

                            <!-- Payment Method -->
                            <div class="mb-2">
                                <label class="form-label small">Metode Pembayaran</label>
                                <select name="payment_method[<?= $sellerId ?>]" class="form-select form-select-sm payment-select" data-seller="<?= $sellerId ?>" required>
                                    <option value="">-- Pilih metode --</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>

                            <!-- Transfer Bank -->
                            <div class="transfer-info mb-2 p-2 border rounded bg-light" id="transfer-<?= $sellerId ?>" style="display:none;">
                                <small>No. Rekening: <strong><?= htmlspecialchars($seller['no_rekening'] ?? '-') ?></strong></small>
                            </div>

                            <!-- QRIS -->
                            <div class="qris-info mb-2 p-2 border rounded bg-light text-center" id="qris-<?= $sellerId ?>" style="display:none;">
                                <?php if (!empty($seller['qris_image'])) : ?>
                                    <img src="<?= BASE_URL . '/uploads/qris/' . $seller['qris_image'] ?>" alt="QRIS <?= htmlspecialchars($seller['name']) ?>" class="img-fluid" style="max-width:120px;">
                                <?php else : ?>
                                    <small class="text-muted">QRIS tidak tersedia</small>
                                <?php endif; ?>
                            </div>

                            <!-- Upload bukti pembayaran -->
                            <div class="mb-2">
                                <label class="form-label small">Bukti Pembayaran</label>
                                <input type="file"
                                    name="payment_proof[<?= $sellerId ?>]"
                                    class="form-control form-control-sm"
                                    accept="image/jpeg,image/png,image/jpg"
                                    required>
                                <small class="text-muted">Format: JPG/PNG, max 2MB</small>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Grand Total -->
                <div class="d-flex justify-content-end fw-bold mb-3">
                    <span class="me-2">Grand Total:</span>
                    <span>Rp.<?= number_format($grandTotal, 2) ?></span>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= BASE_URL ?>/?c=cart&m=index" class="btn btn-outline-secondary btn-sm">Back to Cart</a>
                    <button type="submit" class="btn btn-primary btn-sm">Pay & Checkout</button>
                </div>

            </form>
        <?php endif; ?>
    </div>
</main>

<script>
    document.querySelectorAll('.payment-select').forEach(select => {
        select.addEventListener('change', function() {
            const sellerId = this.dataset.seller;
            document.getElementById('transfer-' + sellerId).style.display = 'none';
            document.getElementById('qris-' + sellerId).style.display = 'none';

            if (this.value === 'transfer') document.getElementById('transfer-' + sellerId).style.display = 'block';
            if (this.value === 'qris') document.getElementById('qris-' + sellerId).style.display = 'block';
        });
    });
</script>

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>