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
    }

    public function dashboard()
    {
        $sellerId = $_SESSION['user']['id'];

        // kalau kamu punya kolom status online/offline di users:
        // $this->userModel->updateStatus($sellerId, 'online');

        require APP_PATH . '/views/seller/dashboard.php';
    }

    public function product()
    {
        require APP_PATH . '/views/seller/product.php';
    }

    public function addProduct()
    {
        require APP_PATH . '/views/seller/add_product.php';
    }

    public function faq()
    {
        require APP_PATH . '/views/seller/faq.php';
    }

    public function profile()
    {
        $sellerId = $_SESSION['user']['id'];
        $user = $this->userModel->findById($sellerId);

        require APP_PATH . '/views/seller/profile.php';
    }



    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Invalid request');

        $sellerId = $_SESSION['user']['id'];

        $data = [
            'name'    => trim($_POST['name'] ?? ''),
            'email'   => trim($_POST['email'] ?? ''), // optional kalau email boleh diganti
            'nik'     => trim($_POST['nik'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'password' => !empty($_POST['password']) ? $_POST['password'] : null,
        ];

        // upload photo
        if (!empty($_FILES['photo']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $_SESSION['error'] = 'Format foto tidak valid';
                header('Location: ' . BASE_URL . '/?c=seller&m=profile');
                exit;
            }

            $uploadDir = APP_PATH . '/../public/uploads/profile/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $photoName = 'seller_' . $sellerId . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName);

            $data['photo'] = $photoName;

            // hapus foto lama kecuali default
            $oldPhoto = $_SESSION['user']['photo'] ?? null;
            if ($oldPhoto && !in_array($oldPhoto, ['admin.png', 'seller.png', 'customer.png'])) {
                $oldPath = $uploadDir . $oldPhoto;
                if (file_exists($oldPath)) unlink($oldPath);
            }
        }

        $ok = $this->userModel->updateProfile($sellerId, $data);

        if ($ok) {
            $_SESSION['user']['name'] = $data['name'];
            if (!empty($data['photo'])) $_SESSION['user']['photo'] = $data['photo'];
            $_SESSION['success'] = 'Profile berhasil diperbarui';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui profile';
        }

        header('Location: ' . BASE_URL . '/?c=seller&m=profile');
        exit;
    }
}
