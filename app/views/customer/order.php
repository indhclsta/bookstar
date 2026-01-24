<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<?php
// Ambil data user dari session
$user = $_SESSION['user'] ?? [];
$name = $user['name'] ?? 'User';

// Ambil semua produk dari database (dari controller seharusnya)
$products = $products ?? []; // Pastikan controller mengirim $products
?>

<main class="main-wrapper">
  <div class="main-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Order</div>
    </div>
    <!--end breadcrumb-->

    <div class="row g-3">
      <?php foreach ($products as $product) : ?>
        <div class="col-md-4">
          <div class="card rounded-4 h-100">
            <img src="<?= !empty($product['image']) ? BASE_URL.'/uploads/products/'.$product['image'] : 'https://placehold.co/400x300/png' ?>" class="card-img-top" alt="<?= $product['name'] ?>">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
              <p class="card-text text-truncate"><?= htmlspecialchars($product['description'] ?? '-') ?></p>
              <p class="mb-1"><strong>Stock:</strong> <?= $product['stock'] ?></p>
              <h6 class="text-primary mb-3">Price: $<?= number_format($product['price'], 2) ?></h6>
              <form action="<?= BASE_URL ?>/?c=cart&m=add" method="POST" class="mt-auto">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-grd btn-grd-info w-100 border-0 d-flex justify-content-center gap-2">
                  <i class="material-icons-outlined">shopping_basket</i>Add to Cart
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</main>

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>
