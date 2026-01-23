<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper">
  <div class="main-content">

    <!-- breadcrumb -->
    <div class="page-breadcrumb d-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">My Products</div>

      <div class="ms-auto">
        <a href="<?= BASE_URL ?>/?c=sellerProduct&m=create" class="btn btn-primary">
          <i class="bi bi-plus-lg me-1"></i> Add Product
        </a>
      </div>
    </div>

    <!-- table -->
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th width="40">No</th>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th width="80" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>

              <?php if (!empty($products)): ?>
                <?php $no = 1;
                foreach ($products as $p): ?>
                  <tr>

                    <!-- NO -->
                    <td><?= $no++ ?></td>

                    <!-- PRODUCT -->
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <img
                          src="<?= $p['image']
                                  ? BASE_URL . '/uploads/products/' . $p['image']
                                  : 'https://placehold.co/80x60/png' ?>"
                          width="60"
                          class="rounded-3 border">

                        <div>
                          <div class="fw-semibold">
                            <?= htmlspecialchars($p['name']) ?>
                          </div>
                        </div>
                      </div>
                    </td>

                    <!-- CATEGORY -->
                    <td>
                      <span class="badge bg-info-subtle text-info">
                        <?= htmlspecialchars($p['category_name']) ?>
                      </span>
                    </td>

                    <!-- PRICE -->
                    <td class="fw-semibold">
                      Rp <?= number_format($p['price'], 0, ',', '.') ?>
                    </td>

                    <!-- STOCK -->
                    <td>
                      <?php if ($p['stock'] > 0): ?>
                        <span class="badge bg-success-subtle text-success">
                          <?= $p['stock'] ?>
                        </span>
                      <?php else: ?>
                        <span class="badge bg-danger-subtle text-danger">
                          Habis
                        </span>
                      <?php endif; ?>
                    </td>

                    <!-- ACTION -->
                    <td class="text-center">
                      <div class="dropdown">
                        <button class="btn btn-sm btn-light"
                          data-bs-toggle="dropdown">
                          <i class="bi bi-three-dots"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">
                          <!-- DETAIL -->
                          <li>
                            <button class="dropdown-item btn-detail"
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
                                            : 'https://placehold.co/300x200/png' ?>"><i class="bi bi-eye me-2"></i>
                              Detail
                            </button>
                          </li>

                          <!-- EDIT -->
                          <li>
                            <button class="dropdown-item btn-edit"
                              data-bs-toggle="modal"
                              data-bs-target="#productEditModal"
                              data-id="<?= $p['id'] ?>"
                              data-name="<?= htmlspecialchars($p['name']) ?>"
                              data-category="<?= $p['category_id'] ?>"
                              data-price="<?= $p['price'] ?>"
                              data-cost="<?= $p['cost_price'] ?>"
                              data-stock="<?= $p['stock'] ?>"
                              data-description="<?= htmlspecialchars($p['description']) ?>">
                              <i class="bi bi-pencil me-2"></i> Edit
                            </button>
                          </li>


                          <li>
                            <hr class="dropdown-divider">
                          </li>

                          <!-- DELETE -->
                          <li>
                            <a class="dropdown-item text-danger"
                              onclick="return confirm('Hapus produk ini?')"
                              href="<?= BASE_URL ?>/?c=sellerProduct&m=delete&id=<?= $p['id'] ?>">
                              <i class="bi bi-trash me-2"></i> Delete
                            </a>
                          </li>
                        </ul>
                      </div>
                    </td>


                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center text-muted py-4">
                    Belum ada produk
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
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Product Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row">

          <div class="col-md-5">
            <img id="detailImage"
              src=""
              class="img-fluid rounded border">
          </div>

          <div class="col-md-7">
            <h4 id="detailName"></h4>

            <span class="badge bg-info-subtle text-info mb-2"
              id="detailCategory"></span>

            <p class="mt-3 mb-1 fw-semibold">Price</p>
            <p>Rp <span id="detailPrice"></span></p>

            <p class="mb-1 fw-semibold">Cost Price</p>
            <p>Rp <span id="detailCostPrice"></span></p>


            <p class="mb-1 fw-semibold">Stock</p>
            <p id="detailStock"></p>

            <p class="mb-1 fw-semibold">Description</p>
            <p id="detailDescription" class="text"></p>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary"
          data-bs-dismiss="modal">
          Close
        </button>
      </div>

    </div>
  </div>
</div>
<!-- MODAL EDIT PRODUCT -->
<div class="modal fade" id="productEditModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <form method="POST"
      action="<?= BASE_URL ?>/?c=sellerProduct&m=update"
      class="modal-content"
      enctype="multipart/form-data">

      <input type="hidden" name="id" id="editId">

      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <input type="text" name="name" id="editName" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Category</label>
          <select name="category_id" id="editCategory" class="form-select" required>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>">
                <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Cost Price</label>
            <input type="number" step="0.01" name="cost_price" id="editCost" class="form-control">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Sale Price</label>
            <input type="number" step="0.01" name="price" id="editPrice" class="form-control" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Stock</label>
          <input type="number" name="stock" id="editStock" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" id="editDescription"
            class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Change Image (optional)</label>
          <input type="file" name="image" class="form-control">
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Update</button>
      </div>

    </form>
  </div>
</div>

<script>
  document.querySelectorAll('.btn-detail').forEach(btn => {
    btn.addEventListener('click', function() {
      document.getElementById('detailName').innerText = this.dataset.name;
      document.getElementById('detailCategory').innerText = this.dataset.category;
      document.getElementById('detailPrice').innerText = this.dataset.price;
      document.getElementById('detailCostPrice').innerText = this.dataset.costPrice;
      document.getElementById('detailStock').innerText = this.dataset.stock;
      document.getElementById('detailDescription').innerText = this.dataset.description;
      document.getElementById('detailImage').src = this.dataset.image;
    });
  });
</script>
<script>
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
</script>



<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>