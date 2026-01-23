<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/ProductModel.php';
require_once APP_PATH . '/models/CategoryModel.php';

class SellerProductController
{
    private $productModel;
    private $categoryModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('seller');

        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    // LIST PRODUCT
    public function index()
    {
        $sellerId = $_SESSION['user']['id'];

        $products   = $this->productModel->getBySeller($sellerId);
        $categories = $this->categoryModel->getSellerCategories($sellerId);

        require APP_PATH . '/views/seller/product.php';
    }


    // FORM ADD PRODUCT
    public function create()
    {
        $sellerId = $_SESSION['user']['id'];

        // ambil kategori admin + seller
        $categories = $this->categoryModel->getSellerCategories($sellerId);

        // ✅ sesuai folder
        require APP_PATH . '/views/seller/add_product.php';
    }

    // STORE PRODUCT
    public function store()
    {
        $sellerId = $_SESSION['user']['id'];

        $data = [
            'seller_id'   => $sellerId,
            'category_id' => (int)$_POST['category_id'],
            'name'        => trim($_POST['name']),
            'description' => trim($_POST['description']),
            'cost_price'  => (float)$_POST['cost_price'],
            'price'       => (float)$_POST['price'],
            'stock'       => (int)$_POST['stock'],
            'image'       => null
        ];

        // validasi nama duplikat per seller
        if ($this->productModel->nameExists($sellerId, $data['name'])) {
            $_SESSION['error'] = 'Nama produk sudah digunakan';
            header('Location: ' . BASE_URL . '/?c=sellerProduct&m=create');
            exit;
        }

        // upload image
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $dir = APP_PATH . '/../public/uploads/products/';

            if (!is_dir($dir)) mkdir($dir, 0777, true);

            $data['image'] = 'prod_' . $sellerId . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $dir . $data['image']);
        }

        $this->productModel->create($data);

        $_SESSION['success'] = 'Produk berhasil ditambahkan';
        header('Location: ' . BASE_URL . '/?c=sellerProduct&m=index');
        exit;
    }
    public function update()
    {
        $sellerId = $_SESSION['user']['id'];

        $data = [
            'id'          => (int)$_POST['id'],
            'seller_id'   => $sellerId,
            'category_id' => (int)$_POST['category_id'],
            'name'        => trim($_POST['name']),
            'description' => trim($_POST['description']),
            'cost_price'  => (float)$_POST['cost_price'],
            'price'       => (float)$_POST['price'],
            'stock'       => (int)$_POST['stock'],
            'image'       => null
        ];

        $product = $this->productModel->findForSeller($data['id'], $sellerId);
        if (!$product) die('Produk tidak ditemukan');

        $data['image'] = $product['image'];

        if (!empty($_FILES['image']['name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $dir = APP_PATH . '/../public/uploads/products/';
            $new = 'prod_' . $sellerId . '_' . time() . '.' . $ext;

            move_uploaded_file($_FILES['image']['tmp_name'], $dir . $new);

            if ($product['image']) unlink($dir . $product['image']);
            $data['image'] = $new;
        }

        $this->productModel->update($data);

        $_SESSION['success'] = 'Produk berhasil diupdate';
        header('Location: ' . BASE_URL . '/?c=sellerProduct&m=index');
        exit;
    }
}
