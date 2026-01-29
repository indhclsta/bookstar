<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/UserModel.php';
require_once APP_PATH . '/models/ProductModel.php';

class AdminController
{
    private $userModel;
    private $productModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('admin');

        $this->userModel = new UserModel();
        $this->productModel = new ProductModel();
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
            'no_tlp'   => trim($_POST['no_tlp']),
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
        if (!$id) {
            echo json_encode([
                'success' => false,
                'message' => 'ID customer tidak ditemukan'
            ]);
            exit;
        }

        $check = $this->userModel->canDeleteCustomer($id);

        if (!$check['can_delete']) {
            echo json_encode([
                'success' => false,
                'message' => $check['reason']
            ]);
            exit;
        }

        if ($this->userModel->softDeleteCustomer($id)) {
            echo json_encode([
                'success' => true,
                'message' => 'Customer berhasil dihapus.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus customer, coba lagi.'
            ]);
        }

        exit;
    }

    /* ===================== SELLER ===================== */
    public function seller()
    {
        $sellers = $this->userModel->getAllSeller(); // sudah termasuk product_count
        require APP_PATH . '/views/admin/seller_acc.php';
    }


    public function sellerIndex()
    {
        $sellers = $this->userModel->getAllSellerWithProductCount();

        require APP_PATH . '/views/admin/seller/index.php';
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
            'no_tlp'   => trim($_POST['no_tlp']),
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

        /* ================= FOTO ================= */
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

        /* ================= QRIS ================= */
        $qrisName = $_POST['old_qris'] ?? $seller['qris_image'];

        if (!empty($_FILES['qris_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['qris_image']['name'], PATHINFO_EXTENSION));
            $qrisName = 'qris_' . time() . '.' . $ext;

            move_uploaded_file(
                $_FILES['qris_image']['tmp_name'],
                APP_PATH . '/../public/uploads/qris/' . $qrisName
            );

            if (!empty($seller['qris_image'])) {
                $old = APP_PATH . '/../public/uploads/qris/' . $seller['qris_image'];
                if (file_exists($old)) unlink($old);
            }
        }

        /* ================= DATA ================= */
        $data = [
            'id'          => $id,
            'name'        => trim($_POST['name']),
            'email'       => trim($_POST['email']),
            'no_tlp'   => trim($_POST['no_tlp']),
            'nik'         => trim($_POST['nik']),
            'address'     => trim($_POST['address']),
            'no_rekening' => trim($_POST['no_rekening']),
            'qris_image'  => $qrisName,
            'photo'       => $photoName
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }


        $this->userModel->updateSeller($data);

        $_SESSION['success'] = 'Data seller berhasil diperbarui';
        header('Location: ' . BASE_URL . '/?c=admin&m=seller');
        exit;
    }

    public function sellerProducts()
    {
        $sellerId = $_GET['id'] ?? null;

        if (!$sellerId) {
            die('Seller ID tidak ditemukan');
        }

        // pastikan model sudah dipanggil di constructor
        $products = $this->productModel->getBySeller($sellerId);

        require APP_PATH . '/views/admin/seller_products.php';
    }



    public function sellerDelete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = 'ID seller tidak valid';
            header('Location: ' . BASE_URL . '/?c=admin&m=seller');
            exit;
        }

        // ❗ CEK APAKAH SELLER MASIH PUNYA PRODUK
        if ($this->userModel->sellerHasProducts($id)) {
            $_SESSION['error'] = 'Seller tidak bisa dihapus karena masih memiliki produk';
            header('Location: ' . BASE_URL . '/?c=admin&m=seller');
            exit;
        }

        $deleted = $this->userModel->deleteSellerIfOffline($id);

        if ($deleted) {
            $_SESSION['success'] = 'Seller berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Seller sedang online atau tidak ditemukan';
        }

        header('Location: ' . BASE_URL . '/?c=admin&m=seller');
        exit;
    }



    public function sellerStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        if ($this->userModel->emailExists($_POST['email'])) {
            $_SESSION['error'] = 'Email sudah terdaftar';
            header('Location: ' . BASE_URL . '/?c=admin&m=seller');
            exit;
        }

        /* Upload FOTO */
        $photoName = null;
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $photoName = uniqid('seller_') . '.' . $ext;
            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                APP_PATH . '/../public/uploads/profile/' . $photoName
            );
        }

        /* Upload QRIS */
        $qrisName = null;
        if (!empty($_FILES['qris_image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['qris_image']['name'], PATHINFO_EXTENSION));
            $qrisName = 'qris_' . time() . '.' . $ext;
            move_uploaded_file(
                $_FILES['qris_image']['tmp_name'],
                APP_PATH . '/../public/uploads/qris/' . $qrisName
            );
        }

        $this->userModel->createSeller([
            'name'        => trim($_POST['name']),
            'email'       => trim($_POST['email']),
            'no_tlp'      => trim($_POST['no_tlp']),
            'password'    => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'nik'         => trim($_POST['nik']),
            'address'     => trim($_POST['address']),
            'no_rekening' => trim($_POST['no_rekening']),
            'qris_image'  => $qrisName,
            'photo'       => $photoName,
            'role_id'     => 2,        // penting!
            'is_online'   => 0          // tambahkan kalau di DB ada kolom NOT NULL
        ]);


        $_SESSION['success'] = 'Seller berhasil ditambahkan';
        header('Location: ' . BASE_URL . '/?c=admin&m=seller');
        exit;
    }
}
