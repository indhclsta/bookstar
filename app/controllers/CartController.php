<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/ProductModel.php';
require_once APP_PATH . '/models/CartModel.php';

class CartController
{
    private $productModel;
    private $cartModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('customer');

        $this->productModel = new ProductModel();
        $this->cartModel    = new CartModel();
    }

    // ADD TO CART
    public function add()
    {
        $userId    = $_SESSION['user']['id'];
        $productId = $_POST['product_id'];
        $qty       = $_POST['quantity'] ?? 1;

        $product = $this->productModel->findById($productId);
        if (!$product) {
            $_SESSION['error'] = 'Produk tidak ditemukan';
            header('Location: ' . BASE_URL . '/?c=customer&m=order');
            exit;
        }

        // Ambil item di cart sebelumnya
        $cartItem = $this->cartModel->getItem($userId, $productId);
        $existingQty = $cartItem['quantity'] ?? 0;

        // total quantity baru
        $totalQty = $existingQty + $qty;

        if ($totalQty > $product['stock']) {
            $_SESSION['error'] = 'Jumlah melebihi stok tersedia';
            header('Location: ' . BASE_URL . '/?c=customer&m=order');
            exit;
        }

        // Tambahkan ke cart
        $this->cartModel->add($userId, $productId, $qty);


        $_SESSION['success'] = 'Produk ditambahkan ke cart';
        header('Location: ' . BASE_URL . '/?c=customer&m=order');
    }


    // VIEW CART
    public function index()
    {
        $userId = $_SESSION['user']['id'];
        $cart = $this->cartModel->getByUser($userId);

        require APP_PATH . '/views/customer/cart.php';
    }


    // REMOVE ITEM  
    public function remove()
    {
        $userId = $_SESSION['user']['id'];
        $id     = $_GET['id'];

        $this->cartModel->remove($userId, $id);

        $_SESSION['success'] = 'Produk dihapus dari cart';
        header('Location: ' . BASE_URL . '/?c=cart&m=index');
    }

    public function increase()
    {
        $userId    = $_SESSION['user']['id'];
        $productId = $_GET['id'];

        $item = $this->cartModel->getItem($userId, $productId);
        if (!$item) return;

        $product = $this->productModel->findById($productId);
        if (!$product) return;

        // ❌ kalau sudah sama dengan stok
        if ($item['quantity'] >= $product['stock']) {
            $_SESSION['error'] = 'Stok produk tidak mencukupi';
            header('Location: ' . BASE_URL . '/?c=cart&m=index');
            return;
        }

        $newQty = $item['quantity'] + 1;
        $this->cartModel->updateQty($userId, $productId, $newQty);

        header('Location: ' . BASE_URL . '/?c=cart&m=index');
    }

    public function decrease()
    {
        $userId    = $_SESSION['user']['id'];
        $productId = $_GET['id'];

        $item = $this->cartModel->getItem($userId, $productId);
        if (!$item) return;

        if ($item['quantity'] > 1) {
            $newQty = $item['quantity'] - 1;
            $this->cartModel->updateQty($userId, $productId, $newQty);
        }

        header('Location: ' . BASE_URL . '/?c=cart&m=index');
    }
}
