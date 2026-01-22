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

    public function index()
    {
        $categories = $this->categoryModel->getAll();
        require APP_PATH . '/views/admin/category.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $name = trim($_POST['name']);

        if ($this->categoryModel->existsByName($name)) {
            $_SESSION['error'] = 'Category sudah ada';
            header('Location: ?c=adminCategory&m=index');
            exit;
        }

        $this->categoryModel->create($name);

        $_SESSION['success'] = 'Category berhasil ditambahkan';
        header('Location: ?c=adminCategory&m=index');
        exit;
    }
}
