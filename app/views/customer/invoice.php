<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<!--start main wrapper-->
<main class="main-wrapper invoice-print">
    <div class="main-content">

        <!-- breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Invoice</div>
            <div class="ps-3">
                <nav>
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="<?= BASE_URL ?>/customer/dashboard"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/customer/orders">Orders</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- end breadcrumb -->

        <div class="card radius-10">
            <!-- HEADER -->
            <div class="card-header py-3 border-bottom">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h5 class="mb-1">Invoice Details</h5>
                        <div>
                            Checkout Code: <strong><?= htmlspecialchars($orders[0]['checkout_code'] ?? '-') ?></strong>
                        </div>
                    </div>
                    <div class="col-lg-6 text-lg-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= BASE_URL ?>/?c=customerinvoice&m=pdf&checkout=<?= urlencode($orders[0]['checkout_code']) ?>"
                                class="btn btn-danger btn-sm me-2">
                                Export PDF
                            </a>
                            <button onclick="window.print()" class="btn btn-dark btn-sm">
                                <i class="bi bi-printer me-1"></i>Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CUSTOMER & DATE -->
            <div class="card-header py-3 border-bottom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div>
                            <div class="mb-1">Customer</div>
                            <div class="fw-medium"><?= htmlspecialchars($_SESSION['user']['name']) ?></div>
                            <div><?= htmlspecialchars($_SESSION['user']['email']) ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <div class="mb-1">Order Date</div>
                            <div class="fw-medium"><?= date('d M Y') ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div>
                            <div class="mb-1">Status</div>
                            <div>
                                <span class="badge bg-secondary px-3 py-1">
                                    <?= ucfirst($orders[0]['order_status']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <?php
                $grandTotal = 0;
                $orderCount = count($orders);
                foreach ($orders as $index => $order):
                    $subtotalSeller = 0;
                    foreach ($order['items'] as $item) {
                        $subtotalSeller += $item['price'] * $item['quantity'];
                    }
                    $grandTotal += $subtotalSeller;
                ?>

                    <!-- SELLER INFO -->
                    <div class="seller-section mb-4 <?= $index > 0 ? 'pt-4 border-top' : '' ?>">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="mb-1"><?= htmlspecialchars($order['seller']['name'] ?? 'Unknown Seller') ?></h6>
                                <div>
                                    Invoice ID: INV-<?= $order['id'] ?> |
                                    Payment: <?= htmlspecialchars($order['payment_method']) ?>
                                </div>
                            </div>
                        </div>

                        <!-- TABLE -->
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th class="ps-0">Product</th>
                                        <th class="text-center" style="width:120px">Price</th>
                                        <th class="text-center" style="width:80px">Qty</th>
                                        <th class="text-end pe-0" style="width:140px">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <tr>
                                            <td class="ps-0"><?= htmlspecialchars($item['product_title']) ?></td>
                                            <td class="text-center">
                                                Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                            </td>
                                            <td class="text-center"><?= $item['quantity'] ?></td>
                                            <td class="text-end pe-0 fw-medium">
                                                Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- SUBTOTAL -->
                        <div class="row justify-content-end mt-3">
                            <div class="col-md-4">
                                <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                                    <span>Subtotal:</span>
                                    <div class="text-end">
                                        <h6 class="mb-0">
                                            Rp <?= number_format($subtotalSeller, 0, ',', '.') ?>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PAYMENT PROOF -->
                        <?php if (!empty($order['payment_proof'])): ?>
                            <div class="mt-4">
                                <h6 class="mb-2">Payment Proof</h6>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="border rounded p-2">
                                        <img src="<?= BASE_URL ?>/uploads/payments/<?= $order['payment_proof'] ?>"
                                            class="img-fluid"
                                            style="max-width:180px; height:auto;">
                                    </div>
                                    <div>
                                        <a href="<?= BASE_URL ?>/uploads/payments/<?= $order['payment_proof'] ?>"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-secondary me-2">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <a href="<?= BASE_URL ?>/uploads/payments/<?= $order['payment_proof'] ?>"
                                            download
                                            class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                <?php endforeach; ?>

                <!-- GRAND TOTAL -->
                <div class="border-top pt-4 mt-4">
                    <div class="row justify-content-end">
                        <div class="col-md-4">
                            <div class="p-4 border rounded">
                                <div class="text-center mb-3">
                                    <div>Total Orders</div>
                                    <div class="fs-5 fw-medium"><?= $orderCount ?> seller(s)</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div>Grand Total</div>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="mb-0">
                                            Rp <?= number_format($grandTotal, 0, ',', '.') ?>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="card-footer text-center py-3 border-top">
                <div class="mb-2">
                    <p class="mb-1 fw-medium">Thank you for your order</p>
                    <div>Order has been received and is being processed</div>
                </div>
                <div class="mt-2">
                    <a href="<?= BASE_URL ?>/?c=cart&m=order" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left me-1"></i>Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
<!--end main wrapper-->

<style>
    .invoice-print .table th {
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .seller-section {
        page-break-inside: avoid;
    }

    @media print {

        .btn,
        .no-print {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>