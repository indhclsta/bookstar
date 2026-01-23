<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper">
    <div class="main-content">

        <!-- ALERT -->
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- BREADCRUMB -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">E-commerce</div>
            <div class="ps-3">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/?c=sellerProduct">Products</a></li>
                    <li class="breadcrumb-item active">Add Product</li>
                </ol>
            </div>
        </div>

        <!-- FORM -->
        <form method="POST"
            action="<?= BASE_URL ?>/?c=sellerProduct&m=store"
            enctype="multipart/form-data">

            <div class="row">
                <!-- LEFT -->
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-body">

                            <!-- PRODUCT NAME -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Product Name</label>
                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    required>
                            </div>

                            <!-- DESCRIPTION -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description"
                                    class="form-control"
                                    rows="5"
                                    required></textarea>
                            </div>

                            <!-- IMAGE -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Product Image</label>
                                <input type="file"
                                    name="image"
                                    class="form-control"
                                    accept="image/png,image/jpeg"
                                    required>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-12 col-lg-4">

                    <!-- ACTION -->
                    <div class="card mb-3">
                        <div class="card-body d-flex gap-2">
                            <a href="<?= BASE_URL ?>/?c=sellerProduct"
                                class="btn btn-outline-danger flex-fill">
                                Cancel
                            </a>
                            <button type="submit"
                                class="btn btn-primary flex-fill">
                                Publish
                            </button>
                        </div>
                    </div>

                    <!-- ORGANIZE -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-3">Product Data</h5>

                            <!-- CATEGORY -->
                            <div class="mb-3">
                                <label class="form-label">Category</label>

                                <select name="category_id"
                                    class="form-select"
                                    required>
                                    <option value="">-- Select Category --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>">
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- STOCK -->
                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number"
                                    name="stock"
                                    class="form-control"
                                    min="0"
                                    required>
                            </div>

                            <!-- COST PRICE -->
                            <div class="mb-3">
                                <label class="form-label">Regular Price (Harga Modal)</label>
                                <input type="number"
                                    name="cost_price"
                                    class="form-control"
                                    step="0.01"
                                    required>
                            </div>

                            <!-- SALE PRICE -->
                            <div class="mb-3">
                                <label class="form-label">Sale Price (Harga Jual)</label>
                                <input type="number"
                                    name="price"
                                    class="form-control"
                                    step="0.01"
                                    required>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </form>

    </div>
</main>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>