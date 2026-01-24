<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/ProductModel.php';

class CartController
{
    private $productModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('customer');

        $this->productModel = new ProductModel();
    }

    // ADD TO CART
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $productId = $_POST['product_id'];
        $qty       = $_POST['quantity'] ?? 1;

        // ambil produk
        $product = $this->productModel->findById($productId);
        if (!$product) {
            $_SESSION['error'] = 'Produk tidak ditemukan';
            header('Location: ' . BASE_URL . '/?c=customer&m=order');
            exit;
        }

        // cek stock
        if ($qty > $product['stock']) {
            $_SESSION['error'] = 'Stock tidak mencukupi';
            header('Location: ' . BASE_URL . '/?c=customer&m=order');
            exit;
        }

        // inisialisasi cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // kalau produk sudah ada di cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $qty;
        } else {
            $_SESSION['cart'][$productId] = [
                'product_id' => $product['id'],
                'name'       => $product['name'],
                'price'      => $product['price'],
                'image'      => $product['image'],
                'quantity'   => $qty
            ];
        }

        $_SESSION['success'] = 'Produk ditambahkan ke cart';
        header('Location: ' . BASE_URL . '/?c=customer&m=order');
        exit;
    }

    // VIEW CART
    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];
        require APP_PATH . '/views/customer/cart.php';
    }

    // REMOVE ITEM
    public function remove()
    {
        $id = $_GET['id'];
        unset($_SESSION['cart'][$id]);

        $_SESSION['success'] = 'Produk dihapus dari cart';
        header('Location: ' . BASE_URL . '/?c=cart&m=index');
        exit;
    }
}
