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
          <table class="table align-middle">
            <thead class="table-light">
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Category</th>
                <th>Stock</th>
                <th width="80">Action</th>
              </tr>
            </thead>
            <tbody>

              <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <img src="<?= $p['image'] 
                          ? BASE_URL . '/uploads/products/' . $p['image'] 
                          : 'https://placehold.co/80x60/png' ?>"
                          width="70" class="rounded-3">

                        <div>
                          <div class="fw-semibold"><?= htmlspecialchars($p['name']) ?></div>
                        </div>
                      </div>
                    </td>

                    <td>Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($p['category_name']) ?></td>
                    <td><?= $p['stock'] ?></td>

                    <td>
                      <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle"
                          data-bs-toggle="dropdown">
                          <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li>
                            <a class="dropdown-item"
                              href="<?= BASE_URL ?>/?c=sellerProduct&m=edit&id=<?= $p['id'] ?>">
                              Edit
                            </a>
                          </li>
                          <li>
                            <a class="dropdown-item text-danger"
                              onclick="return confirm('Hapus produk ini?')"
                              href="<?= BASE_URL ?>/?c=sellerProduct&m=delete&id=<?= $p['id'] ?>">
                              Delete
                            </a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5" class="text-center text-muted">
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

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>
