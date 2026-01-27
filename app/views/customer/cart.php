<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<?php
$cart = $cart ?? [];
$total = 0;
?>
<?php
function rupiah($angka)
{
  return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>


<main class="main-wrapper">
  <div class="main-content">

    <div class="page-breadcrumb mb-3">
      <h5 class="fw-semibold">🛒 My Cart</h5>
    </div>

    <?php if (empty($cart)) : ?>
      <div class="alert alert-info rounded-4">
        Cart kamu masih kosong 🥲
      </div>
    <?php else : ?>

      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">

          <div class="table-responsive">
            <table class="table align-middle table-borderless">
              <thead class="border-bottom">
                <tr class="text-muted">
                  <th>Produk</th>
                  <th>Harga</th>
                  <th class="text-center">Jumlah</th>
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
                        <img src="<?= !empty($item['image'])
                                    ? BASE_URL . '/uploads/products/' . $item['image']
                                    : 'https://placehold.co/80x80/png' ?>"
                          width="70" class="rounded-4 border">

                        <div>
                          <div class="fw-semibold"><?= htmlspecialchars($item['name']) ?></div>
                        </div>
                      </div>
                    </td>

                    <td class="fw-semibold"><?= rupiah($item['price']) ?></td>

                    <td class="text-center">
                      <div class="d-inline-flex align-items-center border rounded-3 px-2">
                        <a href="<?= BASE_URL ?>/?c=cart&m=decrease&id=<?= $item['product_id'] ?>"
                          class="btn btn-sm btn-light px-2">−</a>

                        <span class="mx-2 fw-semibold"><?= $item['quantity'] ?></span>

                        <a href="<?= ($item['quantity'] >= $item['stock'])
                                    ? '#'
                                    : BASE_URL . '/?c=cart&m=increase&id=' . $item['product_id'] ?>"
                          class="btn btn-sm btn-light px-2 <?= ($item['quantity'] >= $item['stock']) ? 'disabled' : '' ?>"
                          aria-disabled="<?= ($item['quantity'] >= $item['stock']) ? 'true' : 'false' ?>">
                          +
                        </a>

                      </div>
                    </td>

                    <td class="fw-bold"><?= rupiah($subtotal) ?></td>

                    <td>
                      <a href="<?= BASE_URL ?>/?c=cart&m=remove&id=<?= $item['product_id'] ?>"
                        class="btn btn-sm btn-outline-danger rounded-circle"
                        onclick="return confirm('Hapus produk ini?')">
                        <i class="material-icons-outlined fs-6">delete</i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <hr>

          <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-semibold">Total Belanja</h5>
            <h4 class="text-primary fw-bold"><?= rupiah($total) ?></h4>
          </div>

          <div class="text-end mt-4">
            <a href="<?= BASE_URL ?>/?c=customer&m=order"
              class="btn btn-outline-secondary rounded-pill px-4 me-2">
              ← Belanja Lagi
            </a>
            <a href="<?= BASE_URL ?>/?c=checkout&m=index"
              class="btn btn-primary rounded-pill px-4">
              Checkout →
            </a>
          </div>

        </div>
      </div>

    <?php endif; ?>
  </div>
</main>


<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>