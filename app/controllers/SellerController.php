<?php

require_once '../app/core/Auth.php';

class SellerController
{
    public function __construct()
    {
        Auth::check();
        Auth::role('seller');
    }

    public function dashboard()
    {
        require '../app/views/seller/dashboard.php';
    }
}
