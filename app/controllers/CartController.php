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

        if ($qty > $product['stock']) {
            $_SESSION['error'] = 'Stock tidak mencukupi';
            header('Location: ' . BASE_URL . '/?c=customer&m=order');
            exit;
        }

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
}
