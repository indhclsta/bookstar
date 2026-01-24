<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/OrderModel.php';

class SellerOrderController
{
    private $orderModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('seller');

        $this->orderModel = new OrderModel();
    }

    // LIST PESANAN
    public function index()
    {
        $sellerId = $_SESSION['user']['id'];
        $orders = $this->orderModel->getOrdersBySeller($sellerId);

        require APP_PATH . '/views/seller/order.php';
    }

    // APPROVE / REJECT
    public function approve()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Invalid request');

        $orderId = $_POST['order_id'];
        $status  = $_POST['approve']; // approved | rejected

        $this->orderModel->updateApprove($orderId, $status);

        $_SESSION['success'] = 'Status pesanan diperbarui';
        header('Location: ' . BASE_URL . '/?c=sellerOrder&m=index');
        exit;
    }

    // INPUT RESI
    public function inputResi()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Invalid request');

        $orderId = $_POST['order_id'];
        $resi    = trim($_POST['resi']);
        $tracking = trim($_POST['tracking_url']);

        $this->orderModel->saveResi($orderId, $resi, $tracking);

        $_SESSION['success'] = 'No resi berhasil disimpan';
        header('Location: ' . BASE_URL . '/?c=sellerOrder&m=index');
        exit;
    }
}
