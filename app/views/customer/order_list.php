<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<main class="main-wrapper">
    <div class="main-content">
        <!-- Breadcrumb -->
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <h4 class="fw-semibold mb-1">Daftar Pesanan Saya</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item active" aria-current="page">My Orders</li>
                    </ol>
                </nav>
            </div>
        </div>

        <?php if (empty($orders)): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="text-secondary">
                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-0">Belum ada pesanan</p>
                        <small>Pesanan akan muncul ketika Anda melakukan pembelian</small>
                    </div>
                </div>
            </div>
        <?php else: ?>

            <?php
            // Pagination setup
            $perPage = 10;
            $totalData = count($orders);
            $totalPages = ceil($totalData / $perPage);
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $page = max(1, min($page, $totalPages));
            $start = ($page - 1) * $perPage;
            $ordersPaged = array_slice($orders, $start, $perPage);
            ?>

            <!-- Info dan Statistik -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="small text-secondary">
                    Menampilkan <span class="fw-semibold"><?= $start + 1 ?></span> –
                    <span class="fw-semibold"><?= min($start + $perPage, $totalData) ?></span>
                    dari <span class="fw-semibold"><?= $totalData ?></span> pesanan
                </div>

                <!-- Filter badges -->
                <div class="d-flex gap-2">
                    <span class="badge bg-light text-secondary px-3 py-2 rounded-pill">
                        <i class="bi bi-clock me-1"></i>Pending
                    </span>
                    <span class="badge bg-light text-secondary px-3 py-2 rounded-pill">
                        <i class="bi bi-check-circle me-1"></i>Approved
                    </span>
                    <span class="badge bg-light text-secondary px-3 py-2 rounded-pill">
                        <i class="bi bi-x-circle me-1"></i>Rejected
                    </span>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-secondary small">
                                    <th class="ps-4 py-3">Kode</th>
                                    <th class="py-3">Produk</th>
                                    <th class="py-3">Penjual</th>
                                    <th class="py-3 text-center">Qty</th>
                                    <th class="py-3 text-center">Approval</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="py-3 text-center">Metode</th>
                                    <th class="py-3 text-center">Bukti</th>
                                    <th class="py-3 text-center">Resi</th>
                                    <th class="py-3 text-center">Tracking</th>
                                    <th class="pe-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ordersPaged as $o): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="fw-medium"><?= $o['order_code'] ?></span>
                                        </td>

                                        <td>
                                            <span class="fw-medium"><?= htmlspecialchars($o['product_title']) ?></span>
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    <i class="bi bi-shop text-secondary"></i>
                                                </div>
                                                <span><?= htmlspecialchars($o['seller_name']) ?></span>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <span class="fw-medium"><?= $o['quantity'] ?>x</span>
                                        </td>

                                        <td class="text-center">
                                            <?php
                                            $status = $o['approval_status'] ?? 'pending';
                                            if ($status === 'approved'): ?>
                                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                                    <i class="bi bi-check-circle me-1 small"></i>Approved
                                                </span>
                                            <?php elseif ($status === 'rejected'): ?>
                                                <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                    <i class="bi bi-x-circle me-1 small"></i>Rejected
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                                    <i class="bi bi-clock me-1 small"></i>Pending
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if (!empty($o['order_status'])): ?>
                                                <span class="badge bg-light text-secondary px-3 py-2 rounded-pill">
                                                    <?= ucfirst($o['order_status']) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-secondary">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-light text-secondary px-3 py-2 rounded-pill">
                                                <?= strtoupper($o['payment_method'] ?? '-') ?>
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <?php if (!empty($o['payment_proof'])): ?>
                                                <a href="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?>" target="_blank">
                                                    <img src="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?>"
                                                        width="40"
                                                        height="40"
                                                        class="rounded-3 object-fit-cover border"
                                                        style="object-fit: cover;"
                                                        title="Klik untuk lihat bukti">
                                                </a>
                                            <?php else: ?>
                                                <span class="text-secondary">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if (!empty($o['resi'])): ?>
                                                <span class="fw-medium"><?= $o['resi'] ?></span>
                                            <?php else: ?>
                                                <span class="text-secondary">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if (!empty($o['tracking_url'])): ?>
                                                <a href="<?= htmlspecialchars($o['tracking_url']) ?>"
                                                    target="_blank"
                                                    class="text-decoration-none">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i>Lacak
                                                </a>
                                            <?php else: ?>
                                                <span class="text-secondary">-</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="pe-4 text-center">
                                            <div class="d-flex gap-1 justify-content-center align-items-center">
                                                <!-- Detail Button -->
                                                <button class="btn btn-light btn-sm rounded-circle p-0 d-inline-flex align-items-center justify-content-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailModal<?= $o['id'] ?>"
                                                    title="Detail Pesanan"
                                                    data-bs-toggle="tooltip"
                                                    style="width: 32px; height: 32px;">
                                                    <i class="bi bi-eye" style="font-size: 14px;"></i>
                                                </button>

                                                <!-- Chat Button -->
                                                <a href="<?= BASE_URL ?>/?c=customerChat&m=index&sellerId=<?= $o['seller_id'] ?>"
                                                    class="btn btn-light btn-sm rounded-circle p-0 d-inline-flex align-items-center justify-content-center"
                                                    title="Chat Penjual"
                                                    data-bs-toggle="tooltip"
                                                    style="width: 32px; height: 32px;">
                                                    <i class="bi bi-chat" style="font-size: 14px;"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- MODAL DETAIL PESANAN (Modern seperti di order.php) -->
                                    <div class="modal fade" id="detailModal<?= $o['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title fw-semibold">Detail Pesanan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body pt-3">
                                                    <!-- Order Info -->
                                                    <div class="bg-light rounded-3 p-3 mb-3">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <small class="text-secondary">Kode Pesanan</small>
                                                            <span class="fw-semibold"><?= $o['order_code'] ?></span>
                                                        </div>

                                                        <!-- Status Badges -->
                                                        <div class="d-flex gap-2 mt-2">
                                                            <?php
                                                            $status = $o['approval_status'] ?? 'pending';
                                                            if ($status === 'approved'): ?>
                                                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                                                    <i class="bi bi-check-circle me-1"></i>Approved
                                                                </span>
                                                            <?php elseif ($status === 'rejected'): ?>
                                                                <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                                    <i class="bi bi-x-circle me-1"></i>Rejected
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                                                    <i class="bi bi-clock me-1"></i>Pending
                                                                </span>
                                                            <?php endif; ?>

                                                            <?php if (!empty($o['order_status'])): ?>
                                                                <span class="badge bg-light text-secondary px-3 py-2 rounded-pill">
                                                                    <?= ucfirst($o['order_status']) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>

                                                        <?php if ($status === 'rejected' && !empty($o['reject_reason'])): ?>
                                                            <div class="mt-2 p-2 bg-danger bg-opacity-10 rounded-2">
                                                                <small class="text-danger d-block">
                                                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                                                    Alasan: <?= htmlspecialchars($o['reject_reason']) ?>
                                                                </small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Product Info -->
                                                    <div class="mb-3">
                                                        <small class="text-secondary d-block mb-2">Produk</small>
                                                        <div class="bg-light rounded-3 p-3">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <span class="fw-semibold"><?= htmlspecialchars($o['product_title']) ?></span>
                                                                    <div class="small text-secondary mt-1">
                                                                        Rp <?= number_format($o['price'], 0, ',', '.') ?> × <?= $o['quantity'] ?>
                                                                    </div>
                                                                </div>
                                                                <div class="fw-bold text-primary">
                                                                    Rp <?= number_format($o['price'] * $o['quantity'], 0, ',', '.') ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Seller Info -->
                                                    <div class="mb-3">
                                                        <small class="text-secondary d-block mb-2">Informasi Penjual</small>
                                                        <div class="bg-light rounded-3 p-3">
                                                            <div class="d-flex align-items-center">
                                                                <i class="bi bi-shop me-2 text-secondary"></i>
                                                                <span class="fw-semibold"><?= htmlspecialchars($o['seller_name']) ?></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Payment Info -->
                                                    <div class="row g-3 mb-3">
                                                        <div class="col-6">
                                                            <small class="text-secondary d-block mb-2">Metode Pembayaran</small>
                                                            <div class="bg-light rounded-3 p-3">
                                                                <span class="badge bg-light text-secondary px-3 py-2">
                                                                    <?= strtoupper($o['payment_method'] ?? '-') ?>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <?php if (!empty($o['resi'])): ?>
                                                            <div class="col-6">
                                                                <small class="text-secondary d-block mb-2">Nomor Resi</small>
                                                                <div class="bg-light rounded-3 p-3">
                                                                    <span class="fw-semibold"><?= $o['resi'] ?></span>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Payment Proof -->
                                                    <?php if (!empty($o['payment_proof'])): ?>
                                                        <div class="mb-3">
                                                            <small class="text-secondary d-block mb-2">Bukti Pembayaran</small>
                                                            <div class="text-center bg-light rounded-3 p-3">
                                                                <img src="<?= BASE_URL ?>/uploads/payments/<?= $o['payment_proof'] ?>"
                                                                    class="img-fluid rounded-3"
                                                                    style="max-height: 200px; object-fit: contain;">
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <!-- Total -->
                                                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="fw-semibold">Total Pembayaran</span>
                                                            <span class="fw-bold text-primary fs-5">
                                                                Rp <?= number_format($o['price'] * $o['quantity'], 0, ',', '.') ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>

                                                    <?php if (!empty($o['tracking_url'])): ?>
                                                        <a href="<?= htmlspecialchars($o['tracking_url']) ?>"
                                                            target="_blank"
                                                            class="btn btn-primary">
                                                            <i class="bi bi-box-arrow-up-right me-1"></i>
                                                            Lacak Paket
                                                        </a>
                                                    <?php endif; ?>

                                                    <a href="<?= BASE_URL ?>/?c=customerChat&m=index&sellerId=<?= $o['seller_id'] ?>"
                                                        class="btn btn-success">
                                                        <i class="bi bi-chat me-1"></i>
                                                        Chat Penjual
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link border-0 bg-light text-secondary"
                                href="<?= BASE_URL ?>/?c=customerOrder&m=index&page=<?= $page - 1 ?>"
                                aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                <a class="page-link border-0 <?= $page == $i ? 'bg-primary text-white' : 'bg-light text-secondary' ?>"
                                    href="<?= BASE_URL ?>/?c=customerOrder&m=index&page=<?= $i ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link border-0 bg-light text-secondary"
                                href="<?= BASE_URL ?>/?c=customerOrder&m=index&page=<?= $page + 1 ?>"
                                aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</main>

<style>
    /* Custom styles untuk konsistensi dengan order.php */
    .table thead th {
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        border-bottom-width: 1px;
        white-space: nowrap;
    }

    .table tbody td {
        padding: 1rem 0.5rem;
        vertical-align: middle;
        font-size: 0.875rem;
    }

    /* Action buttons */
    .btn-light.rounded-circle {
        background-color: #f8f9fa;
        border-color: #f8f9fa;
        transition: all 0.2s;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 0 2px;
        flex: 0 0 auto;
    }

    .btn-light.rounded-circle i {
        font-size: 14px;
        line-height: 1;
    }

    .btn-light.rounded-circle:hover {
        background-color: #e9ecef;
        border-color: #e9ecef;
    }

    /* Badge styles */
    .badge {
        font-weight: 500;
        font-size: 0.75rem;
    }

    .badge.bg-success {
        background-color: #28a745 !important;
    }

    .badge.bg-danger {
        background-color: #dc3545 !important;
    }

    .badge.bg-warning {
        background-color: #ffc107 !important;
    }

    /* Modal styles */
    .modal-content {
        border-radius: 1rem;
    }

    .modal-header {
        padding: 1.5rem 1.5rem 0.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 0 1.5rem 1.5rem;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .rounded-3 {
        border-radius: 0.5rem !important;
    }

    /* Pagination */
    .page-link {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 0.5rem !important;
        margin: 0 0.25rem;
    }

    .page-item.active .page-link {
        background-color: #0d6efd;
        color: white;
    }

    /* Hover effects */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, .02);
    }

    /* Tooltip */
    .tooltip {
        font-size: 0.75rem;
    }

    .tooltip .tooltip-inner {
        background-color: #212529;
        padding: 4px 8px;
        border-radius: 4px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table tbody td {
            white-space: nowrap;
        }

        .badge-filter {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>