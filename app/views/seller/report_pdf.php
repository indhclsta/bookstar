<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>

    <h3>Laporan Keuntungan Penjualan</h3>
    <p>Total Keuntungan: <strong>Rp <?= number_format($totalIncome, 0, ',', '.') ?></strong></p>

    <?php if (!empty($chartImgHtml)): ?>
        <h4>Grafik Keuntungan</h4>
        <?= $chartImgHtml ?>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Order</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Total Jual</th>
                <th>Total Modal</th>
                <th>Keuntungan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reports as $r): ?>
                <tr>
                    <td><?= $r['order_code'] ?></td>
                    <td><?= $r['product_title'] ?></td>
                    <td><?= $r['quantity'] ?></td>
                    <td>Rp <?= number_format($r['total_penjualan'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($r['total_modal'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($r['total_keuntungan'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</body>

</html>