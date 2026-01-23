<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/CategoryModel.php';

class AdminCategoryController
{
    private $categoryModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('admin');

        $this->categoryModel = new CategoryModel();
    }

    // ================= LIST =================
    public function index()
    {
        $categories = $this->categoryModel->getAdminCategories();
        require APP_PATH . '/views/admin/category.php';
    }

    // ================= CREATE =================
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $name = trim($_POST['name']);

        if ($name === '') {
            $_SESSION['error'] = 'Nama kategori wajib diisi';
            header('Location: ' . BASE_URL . '/?c=adminCategory&m=index');
            exit;
        }

        $created = $this->categoryModel->storeAdmin($name);

        if (!$created) {
            $_SESSION['error'] = 'Nama kategori sudah ada';
        } else {
            $_SESSION['success'] = 'Kategori berhasil ditambahkan';
        }

        header('Location: ' . BASE_URL . '/?c=adminCategory&m=index');
        exit;
    }

    // ================= UPDATE =================
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $id   = $_POST['id'];
        $name = trim($_POST['name']);

        if ($name === '') {
            $_SESSION['error'] = 'Nama kategori wajib diisi';
            header('Location: ' . BASE_URL . '/?c=adminCategory&m=index');
            exit;
        }

        $updated = $this->categoryModel->updateAdmin($id, $name);

        if (!$updated) {
            $_SESSION['error'] = 'Nama kategori sudah ada';
        } else {
            $_SESSION['success'] = 'Kategori berhasil diperbarui';
        }

        header('Location: ' . BASE_URL . '/?c=adminCategory&m=index');
        exit;
    }

    // ================= DELETE =================
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) die('Invalid ID');

        $this->categoryModel->deleteAdmin($id);

        $_SESSION['success'] = 'Kategori berhasil dihapus';
        header('Location: ' . BASE_URL . '/?c=adminCategory&m=index');
        exit;
    }
}
