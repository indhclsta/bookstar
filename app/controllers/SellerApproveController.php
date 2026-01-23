<?php

require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/OrderModel.php';

class SellerApproveController
{
    private $orderModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('seller');
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $sellerId = $_SESSION['user']['id'];

        // tampilkan semua order seller (pending/approved/rejected/shipped/refund)
        $orders = $this->orderModel->getOrdersForSeller($sellerId);

        // notif navbar: jumlah order yang sudah ada resi
        $resiCount = $this->orderModel->countOrdersWithResi($sellerId);

        require APP_PATH . '/views/seller/approve/index.php';
    }

    public function detail()
    {
        $sellerId = $_SESSION['user']['id'];
        $orderId  = (int)($_GET['order_id'] ?? 0);
        if ($orderId <= 0) die('Invalid order');

        $order = $this->orderModel->getOrderDetailForSeller($orderId, $sellerId);
        if (!$order) die('Order tidak ditemukan');

        require APP_PATH . '/views/seller/approve/detail.php';
    }

    public function approve()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Invalid request');
        $sellerId = $_SESSION['user']['id'];
        $orderId  = (int)($_POST['order_id'] ?? 0);

        $ok = $this->orderModel->setApproved($orderId, $sellerId);
        $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Pesanan disetujui' : 'Gagal approve';
        header('Location: ' . BASE_URL . '/?c=sellerApprove&m=index');
        exit;
    }

    public function reject()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Invalid request');
        $sellerId = $_SESSION['user']['id'];
        $orderId  = (int)($_POST['order_id'] ?? 0);

        $ok = $this->orderModel->setRejected($orderId, $sellerId);
        $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Pesanan ditolak' : 'Gagal menolak';
        header('Location: ' . BASE_URL . '/?c=sellerApprove&m=index');
        exit;
    }

    public function saveResi()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die('Invalid request');
        $sellerId = $_SESSION['user']['id'];

        $orderId = (int)($_POST['order_id'] ?? 0);
        $resi    = trim($_POST['resi_number'] ?? '');
        $track   = trim($_POST['tracking_url'] ?? '');

        if ($orderId <= 0 || $resi === '') {
            $_SESSION['error'] = 'No resi wajib diisi';
            header('Location: ' . BASE_URL . '/?c=sellerApprove&m=index');
            exit;
        }

        $ok = $this->orderModel->setResiAndShipped($orderId, $sellerId, $resi, $track);
        $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Resi tersimpan & status dikirim' : 'Gagal simpan resi';
        header('Location: ' . BASE_URL . '/?c=sellerApprove&m=index');
        exit;
    }

    // delete hanya kalau refund / rejected dan lewat delay beberapa menit
    public function delete()
    {
        $sellerId = $_SESSION['user']['id'];
        $orderId = (int)($_GET['order_id'] ?? 0);
        if ($orderId <= 0) die('Invalid order');

        $ok = $this->orderModel->deleteIfRejectedOrRefundAfterDelay($orderId, $sellerId, 2); // 2 menit
        $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Order berhasil dihapus' : 'Belum bisa dihapus / tidak valid';
        header('Location: ' . BASE_URL . '/?c=sellerApprove&m=index');
        exit;
    }
}
