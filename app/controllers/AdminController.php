<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/UserModel.php';

class AdminController
{
    private $userModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('admin');

        $this->userModel = new UserModel();
    }

    public function dashboard()
    {
        require APP_PATH . '/views/admin/dashboard.php';
    }

    public function faq()
    {
        require APP_PATH . '/views/admin/faq.php';
    }

    public function profile()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?c=auth&m=login");
            exit;
        }

        $user = $_SESSION['user'];
        require APP_PATH . '/views/admin/profile.php';
    }

    public function updateProfile()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: ?c=auth&m=login");
            exit;
        }

        $id = $_SESSION['user']['id'];
        $data = [
            'name'    => trim($_POST['name']),
            'email'   => trim($_POST['email']),
            'nik'     => trim($_POST['nik']),
            'address' => trim($_POST['address']),
            'password' => !empty($_POST['password']) ? $_POST['password'] : null
        ];

        // === UPLOAD FOTO ===
        if (!empty($_FILES['photo']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $_SESSION['error'] = "Format foto tidak valid";
                header("Location: ?c=admin&m=profile");
                exit;
            }

            $uploadDir = __DIR__ . '/../../public/uploads/profile/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $photoName = 'profile_' . $id . '_' . time() . '.' . $ext;
            $target = $uploadDir . $photoName;
            move_uploaded_file($_FILES['photo']['tmp_name'], $target);

            $data['photo'] = $photoName;

            // Hapus foto lama kecuali default
            $oldPhoto = $_SESSION['user']['photo'] ?? null;
            if ($oldPhoto && !in_array($oldPhoto, ['admin.png', 'seller.png', 'customer.png'])) {
                $oldPath = $uploadDir . $oldPhoto;
                if (file_exists($oldPath)) unlink($oldPath);
            }
        }

        require_once APP_PATH . '/models/UserModel.php';
        $userModel = new UserModel();
        $userModel->updateProfile($id, $data);

        // === UPDATE SESSION ===
        $_SESSION['user']['name'] = $data['name'];
        if (!empty($data['photo'])) $_SESSION['user']['photo'] = $data['photo'];

        $_SESSION['success'] = "Profile berhasil diperbarui";
        header("Location: ?c=admin&m=profile");
        exit;
    }




    /* ===================== CUSTOMER ===================== */
    public function customer()
    {
        $customers = $this->userModel->getAllCustomer();
        require APP_PATH . '/views/admin/customer_acc.php';
    }

    public function customerUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $id = $_POST['id'];
        $customer = $this->userModel->findById($id);

        if (!$customer || $customer['role_id'] != 3) {
            $_SESSION['error'] = 'Customer tidak ditemukan';
            header('Location: ' . BASE_URL . '/?c=admin&m=customer');
            exit;
        }

        $photoName = $customer['photo'];

        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $photoName = uniqid('cust_') . '.' . $ext;

            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                APP_PATH . '/../public/uploads/profile/' . $photoName
            );

            if (!empty($customer['photo'])) {
                $old = APP_PATH . '/../public/uploads/profile/' . $customer['photo'];
                if (file_exists($old)) unlink($old);
            }
        }

        $this->userModel->updateCustomer([
            'id'      => $id,
            'name'    => trim($_POST['name']),
            'email'   => trim($_POST['email']),
            'nik'     => trim($_POST['nik']),
            'address' => trim($_POST['address']),
            'photo'   => $photoName
        ]);

        $_SESSION['success'] = 'Data customer berhasil diperbarui';
        header('Location: ' . BASE_URL . '/?c=admin&m=customer');
        exit;
    }

    public function customerDelete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) die('Invalid ID');

        $deleted = $this->userModel->deleteCustomerIfOffline($id);

        if ($deleted) {
            $_SESSION['success'] = 'Customer berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Customer sedang online dan tidak bisa dihapus';
        }

        header('Location: ' . BASE_URL . '/?c=admin&m=customer');
        exit;
    }

    /* ===================== SELLER ===================== */
    public function seller()
    {
        $sellers = $this->userModel->getAllSeller();
        require APP_PATH . '/views/admin/seller_acc.php';
    }

    public function sellerCreate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $photoName = null;

        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $photoName = uniqid('seller_') . '.' . $ext;

            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                APP_PATH . '/../public/uploads/profile/' . $photoName
            );
        }

        $this->userModel->createSeller([
            'name'     => trim($_POST['name']),
            'email'    => trim($_POST['email']),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'nik'      => trim($_POST['nik']),
            'address'  => trim($_POST['address']),
            'photo'    => $photoName,
            'role_id'  => 2,
            'status'   => 'offline'
        ]);

        $_SESSION['success'] = 'Seller berhasil ditambahkan';
        header('Location: ' . BASE_URL . '/?c=admin&m=seller');
        exit;
    }


    public function sellerUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $id     = $_POST['id'];
        $seller = $this->userModel->findById($id);

        if (!$seller || $seller['role_id'] != 2) {
            $_SESSION['error'] = 'Seller tidak ditemukan';
            header('Location: ' . BASE_URL . '/?c=admin&m=seller');
            exit;
        }

        $photoName = $seller['photo'];

        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $photoName = uniqid('seller_') . '.' . $ext;

            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                APP_PATH . '/../public/uploads/profile/' . $photoName
            );

            if (!empty($seller['photo'])) {
                $old = APP_PATH . '/../public/uploads/profile/' . $seller['photo'];
                if (file_exists($old)) unlink($old);
            }
        }

        $this->userModel->updateSeller([
            'id'      => $id,
            'name'    => trim($_POST['name']),
            'email'   => trim($_POST['email']),
            'nik'     => trim($_POST['nik']),
            'address' => trim($_POST['address']),
            'photo'   => $photoName
        ]);

        $_SESSION['success'] = 'Data seller berhasil diperbarui';
        header('Location: ' . BASE_URL . '/?c=admin&m=seller');
        exit;
    }

    public function sellerDelete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) die('Invalid ID');

        $deleted = $this->userModel->deleteSellerIfOffline($id);

        if ($deleted) {
            $_SESSION['success'] = 'Seller berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Seller sedang online dan tidak bisa dihapus';
        }

        header('Location: ' . BASE_URL . '/?c=admin&m=seller');
        exit;
    }

    public function sellerStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        // Cek email duplikat
        if ($this->userModel->emailExists($_POST['email'])) {
            $_SESSION['error'] = 'Email sudah terdaftar';
            header('Location: ' . BASE_URL . '/?c=admin&m=seller');
            exit;
        }

        /* Upload photo */
        $photoName = null;
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $photoName = uniqid('seller_') . '.' . $ext;

            $uploadDir = APP_PATH . '/../public/uploads/profile/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                $uploadDir . $photoName
            );
        }

        $this->userModel->createSeller([
            'name'     => trim($_POST['name']),
            'email'    => trim($_POST['email']),
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'nik'      => trim($_POST['nik']),
            'address'  => trim($_POST['address']),
            'photo'    => $photoName
        ]);

        $_SESSION['success'] = 'Seller berhasil ditambahkan';
        header('Location: ' . BASE_URL . '/?c=admin&m=seller');
        exit;
    }
}
