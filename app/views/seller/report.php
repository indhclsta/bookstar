<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper">
    <div class="main-content">
        <!-- HEADER SECTION -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h3 fw-semibold text-light mb-2">Laporan Penjualan</h1>
                <p class="text mb-0">Analisis performa penjualan dan keuntungan</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-light text-light border px-3 py-2">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?= date('F Y') ?>
                </span>
            </div>
        </div>

        <!-- SUMMARY CARDS -->
        <div class="row mb-5">
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-cash-stack text-primary fs-4"></i>
                            </div>
                            <div>
                                <p class="text mb-1">Total Pendapatan</p>
                                <h3 class="fw-bold text-light mb-0">Rp <?= number_format($totalIncome, 0, ',', '.') ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-graph-up-arrow text-success fs-4"></i>
                            </div>
                            <div>
                                <p class="text mb-1">Total Keuntungan</p>
                                <h3 class="fw-bold text-light mb-0">Rp <?= number_format(array_sum(array_column($reports, 'total_keuntungan')), 0, ',', '.') ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="bi bi-box-seam text-info fs-4"></i>
                            </div>
                            <div>
                                <p class="text mb-1">Total Transaksi</p>
                                <h3 class="fw-bold text-light mb-0"><?= count($reports) ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER & EXPORT -->
        <div class="card border-0 shadow-sm rounded-3 mb-5">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-center">
                    <input type="hidden" name="c" value="sellerReport">
                    <input type="hidden" name="m" value="index">
                    
                    <div class="col-md-3">
                        <label class="form-label text small fw-semibold">Bulan</label>
                        <select name="month" class="form-select border-1 border-opacity-25">
                            <option value="">Semua Bulan</option>
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= $i ?>" <?= ($_GET['month'] ?? '') == $i ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label text small fw-semibold">Tahun</label>
                        <select name="year" class="form-select border-1 border-opacity-25">
                            <option value="">Semua Tahun</option>
                            <?php for ($y = date('Y'); $y >= 2024; $y--): ?>
                                <option value="<?= $y ?>" <?= ($_GET['year'] ?? '') == $y ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 mt-4">
                            <i class="bi bi-funnel me-1"></i> Filter Data
                        </button>
                    </div>

                    <div class="col-md-3">
                        <button type="button" id="exportPdf" class="btn btn-outline-danger w-100 mt-4">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- CHART SECTION -->
<div class="card border-0 shadow-sm rounded-3 mb-5">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-semibold mb-0 text-light">Grafik Keuntungan</h5>
            <span class="badge bg-light text-light border">
                <i class="bi bi-bar-chart me-1"></i> Bar Chart
            </span>
        </div>
        <div id="chart" style="min-height: 350px;"></div>
    </div>
</div>

        <!-- DATA TABLE -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-semibold mb-0 text-light">Detail Transaksi</h5>
                    <span class="text small"><?= count($reports) ?> transaksi ditemukan</span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr class="border-bottom">
                                <th class="border-0 py-3 text small fw-semibold">ORDER</th>
                                <th class="border-0 py-3 text small fw-semibold">PRODUK</th>
                                <th class="border-0 py-3 text small fw-semibold text-center">QTY</th>
                                <th class="border-0 py-3 text small fw-semibold">HARGA</th>
                                <th class="border-0 py-3 text small fw-semibold">TOTAL</th>
                                <th class="border-0 py-3 text small fw-semibold">MODAL</th>
                                <th class="border-0 py-3 text small fw-semibold">KEUNTUNGAN</th>
                                <th class="border-0 py-3 text small fw-semibold">METODE</th>
                                <th class="border-0 py-3 text small fw-semibold">TANGGAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reports)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="text">
                                            <i class="bi bi-inbox fs-1 opacity-50 mb-3 d-block"></i>
                                            <p class="mb-0">Tidak ada data transaksi</p>
                                            <small class="text">Coba filter dengan bulan atau tahun lain</small>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            
                            <?php foreach ($reports as $r): ?>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <span class="badge bg-light text-light border"><?= $r['order_code'] ?></span>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <i class="bi bi-box text"></i>
                                            </div>
                                            <span class="text-truncate" style="max-width: 150px;"><?= $r['product_title'] ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary"><?= $r['quantity'] ?></span>
                                    </td>
                                    <td class="py-3">
                                        <span class="text">Rp <?= number_format($r['price'], 0, ',', '.') ?></span>
                                    </td>
                                    <td class="py-3">
                                        <span class="fw-semibold text-success">Rp <?= number_format($r['total_penjualan'], 0, ',', '.') ?></span>
                                    </td>
                                    <td class="py-3">
                                        <span class="text-warning">Rp <?= number_format($r['total_modal'], 0, ',', '.') ?></span>
                                    </td>
                                    <td class="py-3">
                                        <span class="fw-bold text-primary">Rp <?= number_format($r['total_keuntungan'], 0, ',', '.') ?></span>
                                    </td>
                                    <td class="py-3">
                                        <span class="badge bg-light text-light border"><?= strtoupper($r['payment_method']) ?></span>
                                    </td>
                                    <td class="py-3">
                                        <small class="text"><?= date('d M Y', strtotime($r['created_at'])) ?></small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="<?= BASE_URL ?>/assets/plugins/apexchart/apexcharts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Clear previous chart
        document.querySelector("#chart").innerHTML = "";

        // Chart options
        const options = {
            series: [{
                name: 'Keuntungan',
                data: <?= json_encode(array_values($profits)) ?>
            }],
            chart: {
                type: 'bar',
                height: 350,
                width: '100%',
                background: 'transparent',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            colors: ['#0d6efd'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    columnWidth: '60%',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return 'Rp ' + val.toLocaleString('id-ID');
                },
                offsetY: -20,
                style: {
                    fontSize: '11px',
                    colors: ['#6c757d']
                }
            },
            grid: {
                borderColor: '#e9ecef',
                strokeDashArray: 3,
                padding: {
                    top: 30
                }
            },
            xaxis: {
                categories: <?= json_encode(array_values($months)) ?>,
                labels: {
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    },
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            }
        };

        // Render chart
        const chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Export PDF functionality - SIMPLIFIED VERSION
        document.getElementById('exportPdf').addEventListener('click', function() {
            const btn = this;
            const originalHTML = btn.innerHTML;
            
            // Show loading state
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';
            btn.disabled = true;
            
            // Generate chart image
            setTimeout(() => {
                chart.dataURI().then(({ imgURI }) => {
                    // Create form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= BASE_URL ?>/index.php?c=sellerReport&m=exportPdf';
                    
                    // Add chart image
                    const chartInput = document.createElement('input');
                    chartInput.type = 'hidden';
                    chartInput.name = 'chart_image';
                    chartInput.value = imgURI;
                    form.appendChild(chartInput);
                    
                    // Add month filter
                    const monthInput = document.createElement('input');
                    monthInput.type = 'hidden';
                    monthInput.name = 'month';
                    monthInput.value = document.querySelector('select[name="month"]').value;
                    form.appendChild(monthInput);
                    
                    // Add year filter
                    const yearInput = document.createElement('input');
                    yearInput.type = 'hidden';
                    yearInput.name = 'year';
                    yearInput.value = document.querySelector('select[name="year"]').value;
                    form.appendChild(yearInput);
                    
                    // Submit form
                    document.body.appendChild(form);
                    form.submit();
                    
                    // Reset button after delay
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                    }, 3000);
                    
                }).catch(error => {
                    console.error('Error generating chart image:', error);
                    alert('Gagal membuat gambar grafik. Silakan coba lagi.');
                    
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                });
            }, 500);
            
        });
    });
</script>

<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
    
    .table tbody tr {
        transition: background-color 0.15s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.02);
    }
    
    .form-select:focus, .btn:focus {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        border-color: #86b7fe;
    }
    
    .badge {
        font-weight: 500;
    }
</style>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>