<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper">
  <?php if (!empty($_SESSION['success'])): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: <?= json_encode($_SESSION['success']) ?>,
        timer: 2000,
        showConfirmButton: false
      });
    </script>
  <?php unset($_SESSION['success']);
  endif; ?>

  <?php if (!empty($_SESSION['error'])): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: <?= json_encode($_SESSION['error']) ?>
      });
    </script>
  <?php unset($_SESSION['error']);
  endif; ?>

  <div class="main-content">
    <!-- breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <h5 class="breadcrumb-title pe-3">My Products</h5>
    </div>

    <!-- table -->
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
              <tr>
                <th width="50" class="ps-3">No</th>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th width="100" class="text-center pe-3">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($products)): ?>
                <?php $no = 1;
                foreach ($products as $p): ?>
                  <tr>
                    <!-- NO -->
                    <td class="ps-3 fw-medium text-secondary"><?= $no++ ?></td>

                    <!-- PRODUCT -->
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div class="product-image-wrapper">
                          <img
                            src="<?= $p['image']
                                    ? BASE_URL . '/uploads/products/' . $p['image']
                                    : 'https://placehold.co/80x60/png' ?>"
                            width="60"
                            height="60"
                            class="rounded-3 object-fit-cover border"
                            style="object-fit: cover;">
                        </div>
                        <div>
                          <h6 class="fw-semibold mb-1"><?= htmlspecialchars($p['name']) ?></h6>
                          <small class="text-secondary">ID: #<?= str_pad($p['id'], 5, '0', STR_PAD_LEFT) ?></small>
                        </div>
                      </div>
                    </td>

                    <!-- CATEGORY -->
                    <td>
                      <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                        <?= htmlspecialchars($p['category_name']) ?>
                      </span>
                    </td>

                    <!-- PRICE -->
                    <td>
                      <span class="fw-semibold">Rp <?= number_format($p['price'], 0, ',', '.') ?></span>
                    </td>

                    <!-- STOCK -->
                    <td>
                      <?php if ($p['stock'] > 0): ?>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                          <i class="bi bi-check-circle-fill me-1 small"></i>
                          <?= $p['stock'] ?> in stock
                        </span>
                      <?php else: ?>
                        <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                          <i class="bi bi-x-circle-fill me-1 small"></i>
                          Out of Stock
                        </span>
                      <?php endif; ?>
                    </td>

                    <!-- ACTION -->
                    <td class="text-center pe-3">
                      <div class="dropdown">
                        <button class="btn btn-light btn-sm rounded-circle"
                          data-bs-toggle="dropdown"
                          style="width: 32px; height: 32px; padding: 0;">
                          <i class="bi bi-three-dots-vertical"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm py-2">
                          <!-- DETAIL -->
                          <li>
                            <button class="dropdown-item py-2 btn-detail"
                              data-bs-toggle="modal"
                              data-bs-target="#productDetailModal"
                              data-name="<?= htmlspecialchars($p['name']) ?>"
                              data-category="<?= htmlspecialchars($p['category_name']) ?>"
                              data-price="<?= number_format($p['price'], 0, ',', '.') ?>"
                              data-cost-price="<?= number_format($p['cost_price'], 0, ',', '.') ?>"
                              data-stock="<?= $p['stock'] ?>"
                              data-description="<?= htmlspecialchars($p['description']) ?>"
                              data-image="<?= $p['image']
                                            ? BASE_URL . '/uploads/products/' . $p['image']
                                            : 'https://placehold.co/300x200/png' ?>">
                              <i class="bi bi-eye me-2"></i>Detail
                            </button>
                          </li>

                          <!-- EDIT -->
                          <li>
                            <button class="dropdown-item py-2 btn-edit"
                              data-bs-toggle="modal"
                              data-bs-target="#productEditModal"
                              data-id="<?= $p['id'] ?>"
                              data-name="<?= htmlspecialchars($p['name']) ?>"
                              data-category="<?= $p['category_id'] ?>"
                              data-price="<?= $p['price'] ?>"
                              data-cost="<?= $p['cost_price'] ?>"
                              data-stock="<?= $p['stock'] ?>"
                              data-description="<?= htmlspecialchars($p['description']) ?>">
                              <i class="bi bi-pencil me-2"></i>Edit
                            </button>
                          </li>

                          <li>
                            <hr class="dropdown-divider my-2">
                          </li>

                          <!-- DELETE -->
                          <li>
                            <?php if ($p['stock'] > 0): ?>
                              <button class="dropdown-item py-2 text-secondary" disabled
                                title="Produk masih memiliki stock, tidak bisa dihapus">
                                <i class="bi bi-lock-fill me-2"></i>Delete
                              </button>
                            <?php else: ?>
                              <a href="#"
                                class="dropdown-item py-2 text-danger btn-delete"
                                data-id="<?= $p['id'] ?>">
                                <i class="bi bi-trash me-2"></i>Delete
                              </a>
                            <?php endif; ?>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-5">
                    <div class="text-secondary">
                      <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
                      <p class="mt-3 mb-0">Belum ada produk</p>
                      <small>Klik tombol "Add Product" untuk menambahkan produk pertama Anda</small>
                    </div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- MODAL DETAIL PRODUCT -->
<div class="modal fade" id="productDetailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-semibold">Product Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body pt-3">
        <div class="text-center mb-4">
          <img id="detailImage"
            src=""
            class="img-fluid rounded-3 border"
            style="max-height: 200px; object-fit: cover;">
        </div>

        <div class="mb-4">
          <h4 id="detailName" class="fw-semibold mb-2"></h4>
          <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill mb-3"
            id="detailCategory"></span>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-6">
            <div class="bg-light rounded-3 p-3">
              <small class="text-secondary d-block mb-1">Selling Price</small>
              <span class="fw-semibold fs-5">Rp <span id="detailPrice"></span></span>
            </div>
          </div>
          <div class="col-6">
            <div class="bg-light rounded-3 p-3">
              <small class="text-secondary d-block mb-1">Cost Price</small>
              <span class="fw-semibold fs-5">Rp <span id="detailCostPrice"></span></span>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <div class="bg-light rounded-3 p-3">
            <small class="text-secondary d-block mb-1">Stock Status</small>
            <span id="detailStock" class="fw-semibold"></span>
          </div>
        </div>

        <div class="mb-3">
          <small class="text-secondary d-block mb-2">Description</small>
          <p id="detailDescription" class="text-secondary bg-light rounded-3 p-3 mb-0" style="min-height: 60px;"></p>
        </div>
      </div>

      <div class="modal-footer border-0 pt-0">
        <button class="btn btn-light px-4" data-bs-dismiss="modal">
          Close
        </button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL EDIT PRODUCT -->
<div class="modal fade" id="productEditModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST"
      action="<?= BASE_URL ?>/?c=sellerProduct&m=update"
      class="modal-content border-0 shadow"
      enctype="multipart/form-data">

      <input type="hidden" name="id" id="editId">

      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-semibold">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body pt-3">
        <div class="mb-3">
          <label class="form-label small fw-semibold text-secondary">Product Name</label>
          <input type="text" name="name" id="editName" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-secondary">Category</label>
          <select name="category_id" id="editCategory" class="form-select" required>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>">
                <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label small fw-semibold text-secondary">Cost Price</label>
            <div class="input-group">
              <span class="input-group-text bg-light">Rp</span>
              <input type="number" step="0.01" name="cost_price" id="editCost" class="form-control">
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label small fw-semibold text-secondary">Selling Price</label>
            <div class="input-group">
              <span class="input-group-text bg-light">Rp</span>
              <input type="number" step="0.01" name="price" id="editPrice" class="form-control" required>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-secondary">Stock</label>
          <input type="number" name="stock" id="editStock" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-secondary">Description</label>
          <textarea name="description" id="editDescription"
            class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label small fw-semibold text-secondary">Change Image (optional)</label>
          <input type="file" name="image" class="form-control">
          <small class="text-secondary mt-1 d-block">Allowed: JPG, PNG. Max size: 2MB</small>
        </div>
      </div>

      <div class="modal-footer border-0 pt-0">
        <button class="btn btn-primary px-4">Update Product</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Detail modal
  document.querySelectorAll('.btn-detail').forEach(btn => {
    btn.addEventListener('click', function() {
      document.getElementById('detailName').innerText = this.dataset.name;
      document.getElementById('detailCategory').innerText = this.dataset.category;
      document.getElementById('detailPrice').innerText = this.dataset.price;
      document.getElementById('detailCostPrice').innerText = this.dataset.costPrice;
      document.getElementById('detailStock').innerText = this.dataset.stock + ' units available';
      document.getElementById('detailDescription').innerText = this.dataset.description || 'No description available';
      document.getElementById('detailImage').src = this.dataset.image;
    });
  });

  // Edit modal
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function() {
      document.getElementById('editId').value = this.dataset.id;
      document.getElementById('editName').value = this.dataset.name;
      document.getElementById('editCategory').value = this.dataset.category;
      document.getElementById('editPrice').value = this.dataset.price;
      document.getElementById('editCost').value = this.dataset.cost;
      document.getElementById('editStock').value = this.dataset.stock;
      document.getElementById('editDescription').value = this.dataset.description;
    });
  });

  // Delete confirmation
  document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();

      const productId = this.dataset.id;

      Swal.fire({
        title: 'Delete Product?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href =
            "<?= BASE_URL ?>/?c=sellerProduct&m=delete&id=" + productId;
        }
      });
    });
  });
</script>

<style>
  /* Custom styles for better appearance */
  .table thead th {
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    border-bottom-width: 1px;
  }

  .table tbody td {
    padding: 1rem 0.5rem;
    vertical-align: middle;
  }

  .dropdown-item {
    font-size: 0.875rem;
  }

  .dropdown-item:hover {
    background-color: #f8f9fa;
  }

  .dropdown-item.text-danger:hover {
    background-color: #dc3545;
    color: white !important;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
  }

  .btn-light {
    background-color: #f8f9fa;
    border-color: #f8f9fa;
  }

  .btn-light:hover {
    background-color: #e9ecef;
    border-color: #e9ecef;
  }

  .modal-content {
    border-radius: 1rem;
  }

  .modal-header {
    padding: 1.5rem 1.5rem 0.5rem;
  }

  .modal-body {
    padding: 1.5rem;
  }

  .modal-footer {
    padding: 0 1.5rem 1.5rem;
  }

  .bg-light {
    background-color: #f8f9fa !important;
  }

  .rounded-3 {
    border-radius: 0.5rem !important;
  }

  .object-fit-cover {
    object-fit: cover;
  }

  .product-image-wrapper {
    width: 60px;
    height: 60px;
    overflow: hidden;
    border-radius: 0.5rem;
  }
</style>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>