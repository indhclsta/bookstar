<?php

require_once '../app/models/ProductModel.php';
require_once '../app/models/CategoryModel.php';

class HomeController
{
    public function index()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        // Ambil data
        $products = $productModel->getPopularProducts(); // limit nanti di model
        $categories = $categoryModel->getAll();

        // Kirim ke view
        require '../app/views/home/landing.php';
    }
}