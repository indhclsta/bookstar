<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/OrderModel.php';
require_once APP_PATH . '/models/OrderItemModel.php';
require_once APP_PATH . '/models/ProductModel.php';

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

        // Tambahkan field buyer_name dan buyer_address supaya view bisa langsung pakai
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

            // ambil semua item dalam order
            $items = $orderItemModel->getByOrderId($orderId);

            // kurangi stok per produk
            foreach ($items as $item) {
                $productModel->reduceStock(
                    $item['product_id'],
                    $item['quantity']
                );
            }

            // update status order
            $this->orderModel->updateApproval($orderId, 'approved');

            $_SESSION['success'] = "Pesanan disetujui & stok otomatis berkurang";
            header("Location: " . BASE_URL . "/?c=sellerOrder&m=index");
        }
    }


    public function reject()
    {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];
            $reason  = $_GET['reason'] ?? null;

            // update approval + order_status + reject_reason
            $this->orderModel->updateApproval($orderId, 'rejected', $reason);

            $_SESSION['success'] = "Pesanan ditolak" . ($reason ? " dengan alasan: $reason" : "");
            header("Location: " . BASE_URL . "/?c=sellerOrder&m=index");
        }
    }


    // INPUT RESI
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
            $this->orderModel->deleteOrder($orderId);
            $_SESSION['success'] = "Pesanan berhasil dihapus";
            header("Location: " . BASE_URL . "/?c=sellerOrder&m=index");
        }
    }
}
