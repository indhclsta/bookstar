<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/OrderModel.php';
require_once APP_PATH . '/models/OrderItemModel.php';
require_once APP_PATH . '/models/ProductModel.php';
require_once APP_PATH . '/models/NotificationModel.php'; 

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

        foreach ($orders as &$o) {
            $o['buyer_name'] = $o['customer_name'] ?? '-';
            $o['buyer_address'] = $o['shipping_address'] ?? $o['customer_address'] ?? '-';
        }

        require APP_PATH . '/views/seller/order.php';
    }

    public function approve()
    {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];

            $orderItemModel = new OrderItemModel();
            $productModel   = new ProductModel();

            $items = $orderItemModel->getByOrderId($orderId);

            foreach ($items as $item) {
                $productModel->reduceStock(
                    $item['product_id'],
                    $item['quantity']
                );
            }

            // update status
            $this->orderModel->updateApproval($orderId, 'approved');

            // 🔔 NOTIFIKASI KE CUSTOMER
            $order = $this->orderModel->findById($orderId);
            $notificationModel = new NotificationModel();
            $notificationModel->create([
                'user_id' => $order['customer_id'],
                'title'   => 'Pesanan Disetujui',
                'message' => 'Pesanan dengan kode ' . $order['order_code'] . ' telah disetujui oleh penjual.',
                'link'    => BASE_URL . '/?c=customerOrder&m=index'
            ]);

            $_SESSION['success'] = "Pesanan disetujui & stok otomatis berkurang";
            header("Location: " . BASE_URL . "/?c=sellerOrder&m=index");
        }
    }

    public function reject()
    {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            $reason  = $_GET['reason'] ?? null;

            $this->orderModel->updateApproval($orderId, 'rejected', $reason);

            // 🔔 NOTIFIKASI KE CUSTOMER
            $order = $this->orderModel->findById($orderId);
            $notificationModel = new NotificationModel();
            $notificationModel->create([
                'user_id' => $order['customer_id'],
                'title'   => 'Pesanan Ditolak',
                'message' => 'Pesanan dengan kode ' . $order['order_code'] .
                             ' ditolak oleh penjual.' .
                             ($reason ? ' Alasan: ' . $reason : ''),
                'link'    => BASE_URL . '/?c=customerOrder&m=index'
            ]);

            $_SESSION['success'] = "Pesanan ditolak" . ($reason ? " dengan alasan: $reason" : "");
            header("Location: " . BASE_URL . "/?c=sellerOrder&m=index");
        }
    }

    public function inputResi()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'];
            $resi    = $_POST['resi'];
            $trackingUrl = $_POST['tracking_url'] ?? null;

            $this->orderModel->inputResi($orderId, $resi, $trackingUrl);
            $_SESSION['success'] = "Nomor resi berhasil disimpan";
            header("Location: " . BASE_URL . "/?c=sellerOrder&m=index");
        }
    }

    public function delete()
    {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];

            if ($this->orderModel->deleteOrder($orderId)) {
                $_SESSION['success'] = "Pesanan berhasil dihapus";
            } else {
                $_SESSION['error'] = "Pesanan hanya bisa dihapus dalam 1 menit setelah ditolak";
            }

            header("Location: " . BASE_URL . "/?c=sellerOrder&m=index");
        }
    }
}