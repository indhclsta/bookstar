<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/AdminDashboardModel.php';

class AdminDashboardController
{
    private $model;

    public function __construct()
    {
        Auth::check();
        Auth::role('admin');
        $this->model = new AdminDashboardModel();
    }

    public function index()
    {
        /* ===== GRAFIK ORDERS ===== */
        $monthlyRaw = $this->model->ordersPerMonth();
        $orderMonthly = [];

        foreach ($monthlyRaw as $row) {
            $orderMonthly[$row['bulan']] = (int)$row['total'];
        }

        /* ===== DISTRIBUSI USER ===== */
        $userRaw = $this->model->userDistribution();
        $userStats = [
            'Admin'    => 0,
            'Seller'   => 0,
            'Customer' => 0
        ];

        foreach ($userRaw as $row) {
            if ($row['role_id'] == 1) $userStats['Admin'] = $row['total'];
            if ($row['role_id'] == 2) $userStats['Seller'] = $row['total'];
            if ($row['role_id'] == 3) $userStats['Customer'] = $row['total'];
        }

        $data = [
            'totalUsers'     => $this->model->totalUsers(),
            'totalSellers'   => $this->model->totalSellers(),
            'totalCustomers' => $this->model->totalCustomers(),
            'totalProducts'  => $this->model->totalProducts(),
            'totalOrders'    => $this->model->totalOrders(),
            'totalRevenue'   => $this->model->totalRevenue(),

            'recentOrders'   => $this->model->recentOrders(),
            'pendingOrders'  => $this->model->pendingOrders(),

            'orderMonthly'   => $orderMonthly,
            'userStats'      => $userStats
        ];

        require APP_PATH . '/views/admin/dashboard.php';
    }
}

