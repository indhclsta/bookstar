<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/SearchModel.php';

class SearchController
{
    private $model;

    public function __construct()
    {
        Auth::check(); // optional (kalau search hanya untuk login user)
        $this->model = new SearchModel();
    }

    public function index()
    {
        $q = trim($_GET['q'] ?? '');

        if ($q === '') {
            header('Location: ' . BASE_URL);
            exit;
        }

        $categories = $this->model->searchCategory($q);

        require APP_PATH . '/views/search/index.php';
    }
}
