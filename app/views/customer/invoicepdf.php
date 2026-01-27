<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2 { margin-bottom: 5px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
        }
        th {
            background: #f3f3f3;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

<h2>Invoice</h2>
<p>
    <strong>Checkout Code:</strong> <?= $orders[0]['checkout_code'] ?><br>
    <strong>Customer:</strong> <?= $_SESSION['user']['name'] ?><br>
    <strong>Date:</strong> <?= date('d M Y', strtotime($orders[0]['created_at'])) ?><br>
</p>

<?php
$grandTotal = 0;
foreach ($orders as $order):
    $subtotal = 0;
    foreach ($order['items'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $grandTotal += $subtotal;
?>

<strong>Seller:</strong> <?= $orders[0]['seller_name'] ?? '-' ?>

<table>
    <thead>
        <tr>
            <th>Product</th>
            <th class="text-center">Qty</th>
            <th class="text-right">Price</th>
            <th class="text-right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($order['items'] as $item): ?>
        <tr>
            <td><?= $item['product_title'] ?></td>
            <td class="text-center"><?= $item['quantity'] ?></td>
            <td class="text-right">Rp <?= number_format($item['price'],0,',','.') ?></td>
            <td class="text-right">
                Rp <?= number_format($item['price'] * $item['quantity'],0,',','.') ?>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"><strong>Subtotal</strong></td>
            <td class="text-right"><strong>Rp <?= number_format($subtotal,0,',','.') ?></strong></td>
        </tr>
    </tbody>
</table>

<?php endforeach; ?>

<h3>Total: Rp <?= number_format($grandTotal,0,',','.') ?></h3>

</body>
</html>
