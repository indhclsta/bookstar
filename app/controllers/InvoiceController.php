<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/OrderModel.php';
require_once APP_PATH . '/models/OrderItemModel.php';
require_once APP_PATH . '/models/UserModel.php';
require_once APP_PATH . '/models/ProductModel.php';

class InvoiceController
{
    private $orderModel;
    private $orderItemModel;
    private $userModel;
    private $productModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('customer');

        $this->orderModel     = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->userModel      = new UserModel();
        $this->productModel   = new ProductModel();
    }

    /**
     * SHOW INVOICE
     * url: ?c=invoice&m=show&id=ORDER_ID
     */
    public function show()
    {
        $checkoutCode = $_GET['id'] ?? null;
        $customerId   = $_SESSION['user']['id'];

        if (!$checkoutCode) {
            die('Invoice tidak ditemukan');
        }

        $orders = $this->orderModel->getInvoiceByCheckout(
            $checkoutCode,
            $customerId
        );

        if (empty($orders)) {
            die('Invoice tidak ditemukan');
        }

        require APP_PATH . '/views/customer/invoice.php';
    }
}
