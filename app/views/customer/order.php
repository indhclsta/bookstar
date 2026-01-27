<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>

<main class="main-wrapper">
  <div class="main-content">

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
      <div class="breadcrumb-title pe-3 fw-bold">Produk</div>
    </div>

    <div class="row g-4">
      <?php foreach ($products as $product) : ?>
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="card product-card h-100 border-0 shadow-sm rounded-4">

            <!-- IMAGE -->
            <div class="ratio ratio-4x3 rounded-top overflow-hidden">
              <img
                src="<?= !empty($product['image'])
                        ? BASE_URL . '/uploads/products/' . $product['image']
                        : 'https://placehold.co/400x300/png' ?>"
                class="w-100 h-100 object-fit-cover"
                alt="<?= htmlspecialchars($product['name']) ?>">
            </div>

            <!-- BODY -->
            <div class="card-body d-flex flex-column p-3">
              <h6 class="fw-semibold mb-1 text-truncate">
                <?= htmlspecialchars($product['name']) ?>
              </h6>

              <p class="text small mb-2 text-truncate">
                <?= htmlspecialchars($product['description'] ?? '-') ?>
              </p>

              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-<?= $product['stock'] > 0 ? 'success' : 'danger' ?>">
                  Stock: <?= $product['stock'] ?>
                </span>
                <span class="fw-bold text-primary">
                  Rp <?= number_format($product['price'], 0, ',', '.') ?>
                </span>
              </div>

              <!-- ACTION -->
              <div class="d-flex align-items-center gap-3 mt-auto pt-3 border-top">

                <!-- CHAT SELLER -->
                <a href="<?= BASE_URL ?>/?c=customerChat&m=index&seller_id=<?= $product['seller_id'] ?>"
                  class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
                  style="width:44px;height:44px"
                  title="Chat Seller">
                  <i class="material-icons-outlined fs-5">chat</i>
                </a>

                <!-- ADD TO CART -->
                <form action="<?= BASE_URL ?>/?c=cart&m=add" method="POST" class="flex-grow-1">
                  <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                  <input type="hidden" name="quantity" value="1">

                  <button type="submit"
                    class="btn btn-primary w-100 d-flex justify-content-center align-items-center gap-1 rounded-3"
                    style="padding:6px 10px;font-size:0.8rem"
                    <?= $product['stock'] == 0 ? 'disabled' : '' ?>>
                    <i class="material-icons-outlined" style="font-size:17px">shopping_basket</i>
                    Add
                  </button>

                </form>

              </div>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
</main>

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>