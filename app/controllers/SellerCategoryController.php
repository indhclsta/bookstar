<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/CategoryModel.php';

class SellerCategoryController
{
    private $model;
    private $sellerId;

    public function __construct()
    {
        Auth::check();
        Auth::role('seller');

        $this->model    = new CategoryModel();
        $this->sellerId = Auth::user()['id'];
    }

    // 🔹 Tampilkan kategori admin + kategori seller login
    public function index()
    {
        $categories = $this->model->getSellerCategories($this->sellerId);
        require APP_PATH . '/views/seller/category.php';
    }

    // 🔹 Simpan kategori seller
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $name = trim($_POST['name'] ?? '');

        if ($name === '') {
            $_SESSION['error'] = 'Nama kategori wajib diisi';
            header('Location: ' . BASE_URL . '/?c=sellerCategory&m=index');
            exit;
        }

        $result = $this->model->storeSeller($name, $this->sellerId);

        if (!$result) {
            $_SESSION['error'] = 'Kategori sudah ada';
        } else {
            $_SESSION['success'] = 'Kategori berhasil ditambahkan';
        }

        header('Location: ' . BASE_URL . '/?c=sellerCategory&m=index');
        exit;
    }

    // 🔹 Update kategori seller
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $id   = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');

        // cek kepemilikan kategori
        if (!$this->model->isSellerCategory($id, $this->sellerId)) {
            $_SESSION['error'] = 'Tidak boleh mengubah kategori admin';
            header('Location: ' . BASE_URL . '/?c=sellerCategory&m=index');
            exit;
        }

        if ($id <= 0 || $name === '') {
            $_SESSION['error'] = 'Data tidak valid';
            header('Location: ' . BASE_URL . '/?c=sellerCategory&m=index');
            exit;
        }

        $result = $this->model->updateSeller($id, $name, $this->sellerId);

        if (!$result) {
            $_SESSION['error'] = 'Kategori sudah ada atau gagal update';
        } else {
            $_SESSION['success'] = 'Kategori berhasil diupdate';
        }

        header('Location: ' . BASE_URL . '/?c=sellerCategory&m=index');
        exit;
    }


    // 🔹 Hapus kategori seller
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID tidak valid';
            header('Location: ' . BASE_URL . '/?c=sellerCategory&m=index');
            exit;
        }

        $result = $this->model->deleteSeller($id, $this->sellerId);

        if ($result) {
            $_SESSION['success'] = 'Kategori berhasil dihapus';
        } else {
            $_SESSION['error'] = 'Kategori gagal dihapus';
        }

        header('Location: ' . BASE_URL . '/?c=sellerCategory&m=index');
        exit;
    }
}
