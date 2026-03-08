<?php require APP_PATH . '/views/layouts/customer/header.php'; ?>
<?php require APP_PATH . '/views/layouts/customer/sidebar.php'; ?>
<?php if (!empty($_SESSION['error'])): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Gagal',
      text: '<?= $_SESSION['error']; ?>'
    });
  </script>
<?php unset($_SESSION['error']);
endif; ?>
<main class="main-wrapper">
  <div class="main-content">

    <div class="d-flex flex-wrap align-items-end justify-content-between mb-4 gap-3">
      <div class="breadcrumb-title pe-3 fw-bold">Produk</div>
      <!-- FILTER -->
      <form method="GET" action="<?= BASE_URL ?>" class="d-flex gap-2 flex-wrap">
        <input type="hidden" name="c" value="customer">
        <input type="hidden" name="m" value="order">

        <!-- SEARCH -->
        <input type="text"
          name="search"
          class="form-control"
          style="width:220px"
          placeholder="Cari produk..."
          value="<?= $_GET['search'] ?? '' ?>">

        <!-- CATEGORY -->
        <select name="category" class="form-select" style="width:180px">
          <option value="">Semua Kategori</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"
              <?= (($_GET['category'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>

        <!-- BUTTON -->
        <button class="btn btn-primary d-flex align-items-center gap-1">
          <i class="material-icons-outlined fs-6">search</i>
          Cari
        </button>
      </form>

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
                class="w-100 h-100 object-fit-cover product-image"
                data-product-id="<?= $product['id'] ?>"
                data-product-name="<?= htmlspecialchars($product['name']) ?>"
                data-product-category="<?= htmlspecialchars($product['category_name'] ?? '') ?>"
                data-product-description="<?= htmlspecialchars($product['description'] ?? '-') ?>"
                data-product-price="<?= $product['price'] ?>"
                data-product-stock="<?= $product['available_stock'] ?>"
                data-product-seller="<?= htmlspecialchars($product['seller_name'] ?? '') ?>"
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
                <span class="badge bg-<?= $product['available_stock'] > 0 ? 'success' : 'danger' ?>">
                  Stock: <?= max(0, $product['available_stock']) ?>
                </span>
                <span class="fw-bold text-primary">
                  Rp <?= number_format($product['price'], 0, ',', '.') ?>
                </span>
              </div>

              <!-- ACTION -->
              <div class="d-flex align-items-center gap-3 mt-auto pt-3 border-top">

                <a href="<?= BASE_URL ?>/?c=customerChat&m=index&userId=<?= $product['seller_id'] ?>"
                  class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center"
                  style="width:44px;height:44px"
                  title="Chat Seller">
                  <i class="material-icons-outlined fs-5">chat</i>
                </a>


                <form action="<?= BASE_URL ?>/?c=cart&m=add" method="POST" class="flex-grow-1">
                  <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                  <div class="d-flex align-items-center gap-2">
                    <!-- Quantity input -->
                    <div class="qty-wrapper">
                      <input type="number"
                        name="quantity"
                        value="1"
                        min="1"
                        max="<?= max(0, $product['available_stock']) ?>"
                        class="form-control qty-input text-center"
                        <?= $product['available_stock'] == 0 ? 'disabled' : '' ?>>
                    </div>

                    <!-- Add button -->
                    <button type="submit"
                      class="btn btn-primary flex-grow-1 d-flex justify-content-center align-items-center gap-1 rounded-3"
                      style="padding:6px 10px;font-size:0.8rem"
                      <?= $product['available_stock'] == 0 ? 'disabled' : '' ?>>
                      <i class="material-icons-outlined" style="font-size:17px">shopping_basket</i>
                      Add
                    </button>
                  </div>

                </form>


              </div>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  </div>
  
  <!-- Modal Detail Produk -->
  <div class="modal fade" id="productDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Produk</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <!-- Kolom Kiri - Gambar -->
            <div class="col-md-6 mb-3 mb-md-0">
              <img src="" id="modalProductImage" class="img-fluid rounded" alt="Product Image">
            </div>
            
            <!-- Kolom Kanan - Detail Produk -->
            <div class="col-md-6">
              <table class="table table-borderless">
                <tr>
                  <th style="width: 100px;">Nama</th>
                  <td><strong id="modalProductName"></strong></td>
                </tr>
                <tr>
                  <th>Kategori</th>
                  <td><span class="badge bg-secondary" id="modalProductCategory"></span></td>
                </tr>
                <tr>
                  <th>Stok</th>
                  <td><span class="badge" id="modalProductStock"></span></td>
                </tr>
                <tr>
                  <th>Harga</th>
                  <td class="text-primary fw-bold" id="modalProductPrice"></td>
                </tr>
                <tr>
                  <th>Penjual</th>
                  <td id="modalProductSeller"></td>
                </tr>
                <tr>
                  <th>Deskripsi</th>
                  <td><p class="mb-0" id="modalProductDescription" style="text-align: justify;"></p></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Ambil semua gambar produk dan tambahkan event listener
    document.querySelectorAll('.product-image').forEach(img => {
      img.style.cursor = 'pointer';
      img.addEventListener('click', function() {
        // Ambil data dari atribut gambar
        const productId = this.dataset.productId;
        const productName = this.dataset.productName;
        const productCategory = this.dataset.productCategory;
        const productDescription = this.dataset.productDescription;
        const productPrice = this.dataset.productPrice;
        const productStock = this.dataset.productStock;
        const productSeller = this.dataset.productSeller;
        
        // Format harga
        const formattedPrice = 'Rp ' + new Intl.NumberFormat('id-ID').format(productPrice);
        
        // Set gambar modal
        const modalImg = document.getElementById('modalProductImage');
        modalImg.src = this.src;
        
        // Set detail produk
        document.getElementById('modalProductName').textContent = productName;
        document.getElementById('modalProductCategory').textContent = productCategory || 'Tanpa Kategori';
        
        // Set stock badge
        const stockBadge = document.getElementById('modalProductStock');
        stockBadge.textContent = 'Stok: ' + (parseInt(productStock) || 0);
        stockBadge.className = 'badge bg-' + (parseInt(productStock) > 0 ? 'success' : 'danger');
        
        document.getElementById('modalProductPrice').textContent = formattedPrice;
        document.getElementById('modalProductDescription').textContent = productDescription || 'Tidak ada deskripsi';
        document.getElementById('modalProductSeller').textContent = productSeller || '-';
        
        // Tampilkan modal
        const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
        modal.show();
      });
    });
  </script>
</main>

<?php require APP_PATH . '/views/layouts/customer/footer.php'; ?>