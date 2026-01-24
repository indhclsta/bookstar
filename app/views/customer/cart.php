<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<?php
$cart = $cart ?? [];
$total = 0;
?>

<main class="main-wrapper">
  <div class="main-content">

    <div class="page-breadcrumb mb-3">
      <h5>My Cart</h5>
    </div>

    <?php if (empty($cart)) : ?>
      <div class="alert alert-info">Cart kamu masih kosong 🛒</div>
    <?php else : ?>

      <div class="card rounded-4">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table align-middle">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Price</th>
                  <th>Qty</th>
                  <th>Subtotal</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>

                <?php foreach ($cart as $item) : 
                  $subtotal = $item['price'] * $item['quantity'];
                  $total += $subtotal;
                ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <img src="<?= !empty($item['image']) ? BASE_URL.'/uploads/products/'.$item['image'] : 'https://placehold.co/80x80/png' ?>"
                             width="60" class="rounded-3">
                        <strong><?= htmlspecialchars($item['name']) ?></strong>
                      </div>
                    </td>

                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><strong>$<?= number_format($subtotal, 2) ?></strong></td>
                    <td>
                      <a href="<?= BASE_URL ?>/?c=cart&m=remove&id=<?= $item['product_id'] ?>"
                         class="btn btn-sm btn-danger"
                         onclick="return confirm('Hapus produk ini?')">
                        <i class="material-icons-outlined">delete</i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>

              </tbody>
            </table>
          </div>

          <hr>

          <div class="d-flex justify-content-between align-items-center">
            <h5>Total</h5>
            <h4 class="text-primary">$<?= number_format($total, 2) ?></h4>
          </div>

          <div class="text-end mt-3">
            <a href="<?= BASE_URL ?>/?c=customer&m=order" class="btn btn-outline-secondary me-2">
              Continue Shopping
            </a>
            <a href="<?= BASE_URL ?>/?c=checkout&m=index" class="btn btn-primary">
              Checkout
            </a>
          </div>

        </div>
      </div>

    <?php endif; ?>

  </div>
</main>

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>
