<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/UserModel.php';

class SellerController
{
    private $userModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('seller');

        $this->userModel = new UserModel();
        $this->userModel->updateLastActivity($_SESSION['user']['id']);
    }

    public function index()
    {
        $sellerId = $_SESSION['user']['id'];
        $this->userModel->updateLastActivity($sellerId);

        $sellers = $this->userModel->getAllSeller($sellerId);
        require APP_PATH . '/views/seller/list_seller.php';
    }

    public function dashboard()
    {
        $sellerId = $_SESSION['user']['id'];
        $this->userModel->updateLastActivity($sellerId);

        require APP_PATH . '/views/seller/dashboard.php';
    }

    public function product()
    {
        $sellerId = $_SESSION['user']['id'];
        $this->userModel->updateLastActivity($sellerId);

        require APP_PATH . '/views/seller/product.php';
    }

    public function addProduct()
    {
        $sellerId = $_SESSION['user']['id'];
        $this->userModel->updateLastActivity($sellerId);

        require APP_PATH . '/views/seller/add_product.php';
    }

    public function faq()
    {
        $sellerId = $_SESSION['user']['id'];
        $this->userModel->updateLastActivity($sellerId);

        require APP_PATH . '/views/seller/faq.php';
    }

    public function profile()
    {
        $sellerId = $_SESSION['user']['id'];
        $this->userModel->updateLastActivity($sellerId);

        $user = $this->userModel->findById($sellerId);
        require APP_PATH . '/views/seller/profile.php';
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Invalid request');

        $sellerId = $_SESSION['user']['id'];

        $data = [
            'name'        => trim($_POST['name'] ?? ''),
            'email'       => trim($_POST['email'] ?? ''),
            'nik'         => trim($_POST['nik'] ?? ''),
            'address'     => trim($_POST['address'] ?? ''),
            'no_rekening' => trim($_POST['no_rekening'] ?? ''),
            'password'    => !empty($_POST['password']) ? $_POST['password'] : null,
        ];

        // FOTO PROFILE
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $uploadDir = APP_PATH . '/../public/uploads/profile/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $photoName = 'seller_' . $sellerId . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName);
            $data['photo'] = $photoName;
        }

        // QRIS
        if (!empty($_FILES['qris_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['qris_image']['name'], PATHINFO_EXTENSION));
            $uploadDir = APP_PATH . '/../public/uploads/qris/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $qrisName = 'qris_' . $sellerId . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['qris_image']['tmp_name'], $uploadDir . $qrisName);
            $data['qris_image'] = $qrisName;
        }

        $ok = $this->userModel->updateProfile($sellerId, $data);

        if ($ok) {
            $_SESSION['user'] = array_merge(
                $_SESSION['user'],
                $this->userModel->findById($sellerId)
            );
            $_SESSION['success'] = 'Profile berhasil diperbarui';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui profile';
        }

        header('Location: ' . BASE_URL . '/?c=seller&m=profile');
        exit;
    }
}
