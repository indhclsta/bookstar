<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header .subtitle {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        .info-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .summary-card {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            text-align: center;
        }
        .summary-card h3 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        .summary-card p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .chart-container {
            margin: 20px 0;
            text-align: center;
        }
        .chart-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 11px;
            border-radius: 3px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <div class="subtitle">
            <?php 
            $period = '';
            if ($month && $year) {
                $period = date('F', mktime(0, 0, 0, $month, 1)) . ' ' . $year;
            } elseif ($year) {
                $period = 'Tahun ' . $year;
            } else {
                $period = 'Semua Waktu';
            }
            echo "Periode: " . $period;
            ?>
        </div>
        <div class="subtitle">Dicetak: <?= date('d/m/Y H:i:s') ?></div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card">
            <h3>Rp <?= number_format($totalIncome, 0, ',', '.') ?></h3>
            <p>Total Pendapatan</p>
        </div>
        <div class="summary-card">
            <h3>Rp <?= number_format(array_sum(array_column($reports, 'total_keuntungan')), 0, ',', '.') ?></h3>
            <p>Total Keuntungan</p>
        </div>
        <div class="summary-card">
            <h3><?= count($reports) ?></h3>
            <p>Total Transaksi</p>
        </div>
    </div>

    <!-- Chart -->
    <?php if (!empty($chartImgHtml)): ?>
    <div class="chart-container">
        <div class="chart-title">Grafik Keuntungan Bulanan</div>
        <?= $chartImgHtml ?>
    </div>
    <?php endif; ?>

    <!-- Transactions Table -->
    <h3>Detail Transaksi (<?= count($reports) ?> transaksi)</h3>
    <table class="table">
        <thead>
            <tr>
                <th width="10%">Order ID</th>
                <th width="20%">Produk</th>
                <th width="5%" class="text-center">Qty</th>
                <th width="10%" class="text-right">Harga</th>
                <th width="10%" class="text-right">Total</th>
                <th width="10%" class="text-right">Modal</th>
                <th width="10%" class="text-right">Keuntungan</th>
                <th width="10%">Metode</th>
                <th width="10%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reports)): ?>
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data transaksi</td>
                </tr>
            <?php else: ?>
                <?php foreach ($reports as $r): ?>
                <tr>
                    <td><span class="badge"><?= $r['order_code'] ?></span></td>
                    <td><?= htmlspecialchars($r['product_title']) ?></td>
                    <td class="text-center"><?= $r['quantity'] ?></td>
                    <td class="text-right">Rp <?= number_format($r['price'], 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($r['total_penjualan'], 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($r['total_modal'], 0, ',', '.') ?></td>
                    <td class="text-right">Rp <?= number_format($r['total_keuntungan'], 0, ',', '.') ?></td>
                    <td><?= strtoupper($r['payment_method']) ?></td>
                    <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Totals -->
    <?php if (!empty($reports)): ?>
    <div style="margin-top: 20px; padding: 10px; background: #f8f9fa; border-radius: 4px;">
        <table style="width: 300px; margin-left: auto;">
            <tr>
                <td><strong>Total Penjualan:</strong></td>
                <td class="text-right"><strong>Rp <?= number_format(array_sum(array_column($reports, 'total_penjualan')), 0, ',', '.') ?></strong></td>
            </tr>
            <tr>
                <td><strong>Total Modal:</strong></td>
                <td class="text-right"><strong>Rp <?= number_format(array_sum(array_column($reports, 'total_modal')), 0, ',', '.') ?></strong></td>
            </tr>
            <tr>
                <td><strong>Total Keuntungan:</strong></td>
                <td class="text-right"><strong>Rp <?= number_format(array_sum(array_column($reports, 'total_keuntungan')), 0, ',', '.') ?></strong></td>
            </tr>
        </table>
    </div>
    <?php endif; ?>

    <div class="footer">
        Laporan ini dibuat secara otomatis oleh sistem.<br>
        Hak Cipta © <?= date('Y') ?> - Sistem Penjualan
    </div>
</body>
</html>