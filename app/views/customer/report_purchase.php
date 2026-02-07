<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<main class="main-wrapper py-4">
    <div class="container">
        <h4 class="fw-bold mb-3">Laporan Pembelian</h4>

        <!-- FILTER -->
        <form class="row g-2 mb-3">
            <input type="hidden" name="c" value="customerReport">
            <input type="hidden" name="m" value="index">

            <div class="col-md-3">
                <select name="month" class="form-select">
                    <option value="">Semua Bulan</option>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= ($_GET['month'] ?? '') == $m ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="year" class="form-select">
                    <option value="">Semua Tahun</option>
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                        <option value="<?= $y ?>" <?= ($_GET['year'] ?? '') == $y ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3">
                <button class="btn btn-primary">Filter</button>
                <a href="<?= BASE_URL ?>/?c=customerReport&m=exportPdf&month=<?= $_GET['month'] ?? '' ?>&year=<?= $_GET['year'] ?? '' ?>"
                    class="btn btn-danger">
                    Export PDF
                </a>
            </div>
        </form>
<!-- 
        <div class="card mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    Grafik Pembelian Tahun <?= htmlspecialchars($year) ?>
                </h6>
                <div class="chart-wrapper">
                <canvas id="purchaseChart" height="70"></canvas>
                </div>
            </div>
        </div> -->
        <!-- TABLE -->
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Metode</th>
                            <th>Total</th>
                            <th>Bukti</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php $grandTotal = 0; ?>

                        <?php foreach ($reports as $r): ?>
                            <?php $grandTotal += $r['total_price']; ?>
                            <tr>
                                <td><?= $r['order_code'] ?></td>
                                <td><?= htmlspecialchars($r['product_title']) ?></td>
                                <td><?= $r['quantity'] ?></td>
                                <td><?= strtoupper($r['payment_method']) ?></td>
                                <td class="fw-bold text-primary">
                                    Rp <?= number_format($r['total_price'], 0, ',', '.') ?>
                                </td>
                                <td>
                                    <?php if ($r['payment_proof']): ?>
                                        <img src="<?= BASE_URL ?>/uploads/payments/<?= $r['payment_proof'] ?>" width="40">
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <th colspan="4" class="text-end">Grand Total</th>
                            <th colspan="2" class="text-primary fs-5">
                                Rp <?= number_format($grandTotal, 0, ',', '.') ?>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <style>
    .chart-wrapper {
        max-width: 100%;
        height: 280px;
    }
</style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const chartData = <?= json_encode($chart) ?>;

        const labels = [];
        const totals = [];

        chartData.forEach(item => {
            labels.push(
                new Date(0, item.month - 1).toLocaleString('id-ID', {
                    month: 'short'
                })
            );
            totals.push(item.total);
        });

        const ctx = document.getElementById('purchaseChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Pembelian',
                    data: totals,
                    backgroundColor: 'rgba(78, 115, 223, 0.7)',
                    hoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    borderRadius: 10,
                    barThickness: 32
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            borderDash: [5, 5],
                            color: '#e5e7eb'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>


</main>


<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>