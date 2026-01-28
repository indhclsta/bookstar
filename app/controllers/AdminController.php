<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/UserModel.php';
require_once APP_PATH . '/helpers/Flash.php';

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

        if (!empty($_FILES['photo']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                Flash::error("Format foto tidak valid");
                header("Location: ?c=admin&m=profile");
                exit;
            }

            $uploadDir = __DIR__ . '/../../public/uploads/profile/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $photoName = 'profile_' . $id . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $photoName);

            $data['photo'] = $photoName;

            $oldPhoto = $_SESSION['user']['photo'] ?? null;
            if ($oldPhoto && !in_array($oldPhoto, ['admin.png', 'seller.png', 'customer.png'])) {
                $oldPath = $uploadDir . $oldPhoto;
                if (file_exists($oldPath)) unlink($oldPath);
            }
        }

        $this->userModel->updateProfile($id, $data);

        $_SESSION['user']['name'] = $data['name'];
        if (!empty($data['photo'])) $_SESSION['user']['photo'] = $data['photo'];

        Flash::success("Profile berhasil diperbarui");
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
        $id = $_POST['id'];
        $customer = $this->userModel->findById($id);

        if (!$customer || $customer['role_id'] != 3) {
            Flash::error('Customer tidak ditemukan');
            header('Location: ' . BASE_URL . '/?c=admin&m=customer');
            exit;
        }

        $this->userModel->updateCustomer([
            'id'      => $id,
            'name'    => trim($_POST['name']),
            'email'   => trim($_POST['email']),
            'no_tlp'  => trim($_POST['no_tlp']),
            'nik'     => trim($_POST['nik']),
            'address' => trim($_POST['address']),
            'photo'   => $customer['photo']
        ]);

        Flash::success('Data customer berhasil diperbarui');
        header('Location: ' . BASE_URL . '/?c=admin&m=customer');
        exit;
    }

    public function customerDelete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            Flash::error('ID customer tidak valid');
            header('Location: ' . BASE_URL . '/?c=admin&m=customer');
            exit;
        }

        if ($this->userModel->deleteCustomerIfOffline($id)) {
            Flash::success('Customer berhasil dihapus');
        } else {
            Flash::error('Customer sedang online atau tidak ditemukan');
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

    public function sellerDelete()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            Flash::error('ID seller tidak valid');
            header('Location: ' . BASE_URL . '/?c=admin&m=seller');
            exit;
        }

        if ($this->userModel->deleteSellerIfOffline($id)) {
            Flash::success('Seller berhasil dihapus');
        } else {
            Flash::error('Seller sedang online atau tidak ditemukan');
        }

        header('Location: ' . BASE_URL . '/?c=admin&m=seller');
        exit;
    }
}
