<?php

require_once '../app/core/Auth.php';
require_once APP_PATH . '/models/UserModel.php';
require_once APP_PATH . '/models/ProductModel.php';

class CustomerController
{
    private $userModel;
    private $productModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('customer');

        $this->userModel = new UserModel(); // inisialisasi model
        $this->productModel = new ProductModel(); // inisialisasi model
    }

    public function dashboard()
    {
        require APP_PATH . '/views/customer/dashboard.php';
    }

    public function profile()
    {
        $customerId = $_SESSION['user']['id'];
        $user = $this->userModel->findById($customerId);

        require APP_PATH . '/views/customer/profile.php';
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Invalid request');

        $customerId = $_SESSION['user']['id'];

        $data = [
            'name'     => trim($_POST['name'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'password' => !empty($_POST['password']) ? $_POST['password'] : null,
            'photo'    => null, // default null, akan diisi kalau upload ada
        ];

        // upload photo
        if (!empty($_FILES['photo']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $_SESSION['error'] = 'Format foto tidak valid';
                header('Location: ' . BASE_URL . '/?c=customer&m=profile');
                exit;
            }

            $uploadDir = APP_PATH . '/../public/uploads/profile/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $photoName = 'customer_' . $customerId . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName);

            $data['photo'] = $photoName;

            // hapus foto lama kecuali default
            $oldPhoto = $_SESSION['user']['photo'] ?? null;
            if ($oldPhoto && !in_array($oldPhoto, ['admin.png', 'customer.png'])) {
                $oldPath = $uploadDir . $oldPhoto;
                if (file_exists($oldPath)) unlink($oldPath);
            }
        }

        // update profile (NIK tidak ikut diupdate)
        $ok = $this->userModel->updateProfile($customerId, $data);

        if ($ok) {
            $_SESSION['user']['name'] = $data['name'];
            if (!empty($data['photo'])) $_SESSION['user']['photo'] = $data['photo'];
            $_SESSION['success'] = 'Profile berhasil diperbarui';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui profile';
        }

        header('Location: ' . BASE_URL . '/?c=customer&m=profile');
        exit;
    }

    public function order()
{
    // ambil semua produk
    $products = $this->productModel->getAll();

    require APP_PATH . '/views/customer/order.php';
}


    public function addToCart()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . '/?c=customer&m=order');
        exit;
    }

    $productId = (int) $_POST['product_id'];
    $qty = (int) ($_POST['qty'] ?? 1);

    $product = $this->productModel->findById($productId);

    if (!$product || $product['stock'] <= 0) {
        $_SESSION['error'] = 'Produk tidak tersedia';
        header('Location: ' . BASE_URL . '/?c=customer&m=order');
        exit;
    }

    // cart pakai session
    $_SESSION['cart'] ??= [];

    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$productId] = [
            'product_id' => $product['id'],
            'name'       => $product['name'],
            'price'      => $product['price'],
            'image'      => $product['image'],
            'qty'        => $qty,
            'seller_id'  => $product['seller_id']
        ];
    }

    $_SESSION['success'] = 'Produk ditambahkan ke keranjang';
    header('Location: ' . BASE_URL . '/?c=customer&m=order');
    exit;
}

}
