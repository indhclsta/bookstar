<?php require APP_PATH . '/views/layouts/seller/header.php'; ?>
<?php require APP_PATH . '/views/layouts/seller/sidebar.php'; ?>

<main class="main-wrapper">
    <div class="main-content">

        <!-- ALERT -->
        <?php if (!empty($_SESSION['error'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: <?= json_encode($_SESSION['error']) ?>,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3b82f6'
                    });
                });
            </script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- PAGE TITLE -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <h5 class="breadcrumb-title pe-3">Add Product</h5>
        </div>

        <form method="POST"
            action="<?= BASE_URL ?>/?c=sellerProduct&m=store"
            enctype="multipart/form-data">

            <div class="row g-4">

                <!-- LEFT -->
                <div class="col-lg-8">

                    <!-- BASIC INFO -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">

                            <div class="mb-4">
                                <h5 class="mb-3">Product Name</h5>
                                <input type="text"
                                    name="name"
                                    class="form-control form-control-lg"
                                    placeholder="Input product name..."
                                    required>
                            </div>

                            <div>
                                <h5 class="mb-3">Product Description</h5>
                                <textarea name="description"
                                    class="form-control"
                                    rows="5"
                                    placeholder="Describe your product clearly..."
                                    required></textarea>
                            </div>

                        </div>
                    </div>

                    <!-- IMAGE -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">

                            <h5 class="mb-3">Product Image</h5>

                            <div class="upload-box text-center rounded-4 p-4">
                                <img id="imagePreview"
                                    class="img-fluid rounded mb-3 d-none"
                                    style="max-height:260px">

                                <input type="file"
                                    name="image"
                                    class="form-control"
                                    accept="image/*"
                                    onchange="previewImage(this)"
                                    required>

                                <small class="text d-block mt-2">
                                    JPG / PNG • Max 2MB
                                </small>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- RIGHT -->
                <div class="col-lg-4">

                    <!-- DETAILS -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">

                            <div class="mb-3">
                                <h5 class="mb-3">Category</h5>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select category</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>">
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <h5 class="mb-3">Stock</h5>
                                <input type="number"
                                    name="stock"
                                    class="form-control"
                                    min="0"
                                    placeholder="0"
                                    required>
                            </div>

                            <div class="mb-3">
                                <h5 class="mb-3">Cost Price</h5>

                                <!-- Input tampilan -->
                                <input type="text"
                                    id="costPriceView"
                                    class="form-control"
                                    placeholder="Rp 0"
                                    autocomplete="off"
                                    required>

                                <!-- Value asli ke backend -->
                                <input type="hidden"
                                    name="cost_price"
                                    id="costPriceValue">
                            </div>


                            <div>
                                <h5 class="mb-3">Sale Price</h5>

                                <!-- Input tampilan -->
                                <input type="text"
                                    id="salePriceView"
                                    class="form-control form-control-lg fw-semibold"
                                    placeholder="Rp 0"
                                    autocomplete="off"
                                    required>

                                <!-- Value asli ke backend -->
                                <input type="hidden"
                                    name="price"
                                    id="salePriceValue">
                            </div>


                        </div>
                    </div>

                    <!-- ACTION -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4 d-grid gap-2">
                            <button class="btn btn-primary btn-lg fw-semibold">
                                Publish Product
                            </button>

                            <button type="button" id="btnReset" class="btn btn-light">
                                Cancel
                            </button>

                        </div>
                    </div>

                </div>

            </div>
        </form>

    </div>
</main>

<style>
    .upload-box {
        background: rgba(59, 130, 246, 0.08);
        border: 2px dashed rgba(59, 130, 246, 0.35);
        color: #e5e7eb;
        transition: .2s ease;
    }

    .upload-box:hover {
        background: rgba(59, 130, 246, 0.12);
        border-color: rgba(59, 130, 246, 0.6);
    }

    .upload-box.dragover {
        background: rgba(99, 102, 241, 0.15);
        border-color: #6366f1;
    }
</style>
<script>
    function formatRupiahInput(el, hiddenInput) {
        el.addEventListener('input', () => {
            let value = el.value.replace(/[^0-9]/g, '');

            hiddenInput.value = value;

            if (value) {
                el.value = 'Rp ' + Number(value).toLocaleString('id-ID');
            } else {
                el.value = '';
            }
        });
    }

    // COST PRICE
    formatRupiahInput(
        document.getElementById('costPriceView'),
        document.getElementById('costPriceValue')
    );

    // SALE PRICE
    formatRupiahInput(
        document.getElementById('salePriceView'),
        document.getElementById('salePriceValue')
    );
</script>
<script>
    document.getElementById('btnReset').addEventListener('click', () => {
        Swal.fire({
            title: 'Cancel product?',
            text: 'All entered data will be cleared.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, reset',
            cancelButtonText: 'No',
            reverseButtons: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#9ca3af'
        }).then((result) => {
            if (result.isConfirmed) {

                const form = document.querySelector('form');
                form.reset();

                // reset image preview
                const img = document.getElementById('imagePreview');
                img.classList.add('d-none');
                img.removeAttribute('src');

                // reset rupiah inputs
                document.getElementById('costPriceView').value = '';
                document.getElementById('costPriceValue').value = '';

                document.getElementById('salePriceView').value = '';
                document.getElementById('salePriceValue').value = '';

                Swal.fire({
                    title: 'Reset!',
                    text: 'Form has been cleared.',
                    icon: 'success',
                    timer: 1200,
                    showConfirmButton: false
                });
            }
        });
    });
</script>



<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const file = input.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
        }
    }
</script>

<?php require APP_PATH . '/views/layouts/seller/footer.php'; ?>