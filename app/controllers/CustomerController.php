<?php

require_once '../app/core/Auth.php';

class CustomerController
{
    public function __construct()
    {
        Auth::check();
        Auth::role('customer');
    }

    public function dashboard()
    {
        require '../app/views/customer/dashboard.php';
    }
}
