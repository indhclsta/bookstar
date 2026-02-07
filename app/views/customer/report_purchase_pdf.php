<!DOCTYPE html>
<html>

<head>
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h3 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f0f0f0;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>

    <h3>Laporan Pembelian Customer</h3>

    <table>
        <tr>
            <th>Kode Pesanan</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Metode</th>
            <th>Total Harga</th>
        </tr>

        <?php $grandTotal = 0; ?>
        <?php foreach ($reports as $r): ?>
            <?php $grandTotal += $r['total_price']; ?>
            <tr>
                <td><?= $r['order_code'] ?></td>
                <td><?= $r['product_title'] ?></td>
                <td class="right"><?= $r['quantity'] ?></td>
                <td><?= strtoupper($r['payment_method']) ?></td>
                <td class="right">Rp <?= number_format($r['total_price'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>

        <tr>
            <th colspan="4">Grand Total</th>
            <th class="right">Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
        </tr>
    </table>

</body>

</html>