<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper">
    <div class="main-content">
        <h4 class="mb-3">📊 Laporan Penjualan</h4>

        <!-- FILTER -->
        <form method="GET" class="row g-3 align-items-end mb-4">
            <input type="hidden" name="c" value="sellerReport">
            <input type="hidden" name="m" value="index">

            <div class="col-md-3">
                <label class="form-label text-muted">Bulan</label>
                <select name="month" class="form-select">
                    <option value="">Semua</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>" <?= ($_GET['month'] ?? '') == $i ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label text-muted">Tahun</label>
                <select name="year" class="form-select">
                    <option value="">Semua</option>
                    <?php for ($y = date('Y'); $y >= 2024; $y--): ?>
                        <option value="<?= $y ?>" <?= ($_GET['year'] ?? '') == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit"
                    class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="material-icons-outlined">search</i> Filter
                </button>
            </div>

            <div class="col-md-2">
                <button type="button" id="exportPdf"
                    class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </button>
            </div>
        </form>

        <!-- TOTAL -->
        <div class="alert alert-success">
            💰 <strong>Total Pendapatan:</strong> Rp <?= number_format($totalIncome, 0, ',', '.') ?>
        </div>

        <!-- CHART -->
        <div class="card rounded-4 mb-4">
            <div class="card-header py-3">
                <h5 class="mb-0">Area Chart</h5>
            </div>
            <div class="card-body">
                <div id="chart"></div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Kode Order</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Total Jual</th>
                        <th>Total Modal</th>
                        <th>Keuntungan</th>
                        <th>Pembayaran</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reports)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">Tidak ada data</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($reports as $r): ?>
                        <tr>
                            <td><?= $r['order_code'] ?></td>
                            <td><?= $r['product_title'] ?></td>
                            <td><?= $r['quantity'] ?></td>
                            <td>Rp <?= number_format($r['price'], 0, ',', '.') ?></td>
                            <td class="fw-bold text-success">Rp <?= number_format($r['total_penjualan'], 0, ',', '.') ?></td>
                            <td class="text-warning">Rp <?= number_format($r['total_modal'], 0, ',', '.') ?></td>
                            <td class="fw-bold text-primary">Rp <?= number_format($r['total_keuntungan'], 0, ',', '.') ?></td>
                            <td><?= strtoupper($r['payment_method']) ?></td>
                            <td><?= date('d M Y', strtotime($r['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="<?= BASE_URL ?>/assets/plugins/apexchart/apexcharts.min.js"></script>
<script>
    let chart; // global

    document.addEventListener("DOMContentLoaded", function() {

        document.querySelector("#chart").innerHTML = "";

        const options = {
            series: [{
                name: 'Keuntungan',
                data: <?= json_encode(array_values($profits)) ?>
            }],
            chart: {
                type: 'area',
                height: 400,
                width: 1000,
                background: 'transparent',
                toolbar: {
                    show: false
                }
            },
            colors: ['#ffc107'],
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 6,
                colors: ['#ffc107'],
                strokeColors: '#4b4b4b',
                strokeWidth: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    opacityFrom: 0.5,
                    opacityTo: 0.05
                }
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                borderColor: '#e0e0e0',
                strokeDashArray: 5,
                padding: {
                    bottom: 50
                }
            },
            xaxis: {
                categories: <?= json_encode(array_values($months)) ?>,
                labels: {
                    show: true,
                    rotate: -45,
                    style: {
                        fontSize: '12px',
                        colors: '#6c757d'
                    }
                },
                tickPlacement: 'on'
            },
            yaxis: {
                labels: {
                    formatter: val => 'Rp ' + val.toLocaleString('id-ID')
                }
            },
            tooltip: {
                theme: 'dark', // 🔹 ubah menjadi dark
                y: {
                    formatter: val => 'Rp ' + val.toLocaleString('id-ID')
                }
            },
            legend: {
                show: false
            },
        };

        chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    });

    // EXPORT PDF
    document.getElementById('exportPdf').addEventListener('click', function() {
        chart.updateOptions({
            chart: {
                width: 1000,
                height: 400
            }
        }).then(() => {
            chart.dataURI().then(({
                imgURI
            }) => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= BASE_URL ?>/?c=sellerReport&m=exportPdf';

                const imgInput = document.createElement('input');
                imgInput.type = 'hidden';
                imgInput.name = 'chart_image';
                imgInput.value = imgURI;
                form.appendChild(imgInput);

                ['month', 'year'].forEach(name => {
                    const el = document.querySelector(`[name="${name}"]`);
                    if (el) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = name;
                        input.value = el.value;
                        form.appendChild(input);
                    }
                });

                document.body.appendChild(form);
                form.submit();
            });
        });
    });
</script>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>