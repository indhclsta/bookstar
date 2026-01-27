<?php
require_once APP_PATH.'/core/auth.php';
require_once APP_PATH.'/models/OrderModel.php';
require_once APP_PATH.'/models/OrderItemModel.php';
require_once APP_PATH.'/models/UserModel.php';

class CheckoutController {

    private $orderModel;
    private $orderItemModel;
    private $userModel;

    public function __construct() {
        Auth::check();
        Auth::role('customer');

        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->userModel = new UserModel();
    }

    public function index() {
        $customerId = $_SESSION['user']['id'];
        $groupedCart = $this->orderModel->getCartGroupedBySeller($customerId);
        require APP_PATH.'/views/customer/checkout.php';
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $customerId = $_SESSION['user']['id'];
        $sellerIds  = $_POST['seller_id'] ?? [];

        foreach ($sellerIds as $sellerId) {

            $paymentMethod = $_POST['payment_method'][$sellerId] ?? 'transfer';
            $paymentProof  = null;

            // ================= UPLOAD BUKTI PEMBAYARAN =================
            if (!empty($_FILES['payment_proof']['name'][$sellerId])) {

                $uploadDir = __DIR__ . '/../../public/uploads/payments/';

                // pastikan folder ada
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $ext = pathinfo(
                    $_FILES['payment_proof']['name'][$sellerId],
                    PATHINFO_EXTENSION
                );

                $paymentProof = 'payment_' . uniqid() . '_' . time() . '.' . $ext;

                $targetPath = $uploadDir . $paymentProof;

                if (!move_uploaded_file(
                    $_FILES['payment_proof']['tmp_name'][$sellerId],
                    $targetPath
                )) {
                    die('❌ Upload bukti pembayaran gagal');
                }
            }
            // ===========================================================

            $orderCode = 'ORD' . strtoupper(uniqid());

            $cartItems = $this->orderModel->getCartItemsBySeller(
                $customerId,
                $sellerId
            );

            $totalPrice = 0;
            foreach ($cartItems as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }

            $orderId = $this->orderModel->create([
                'order_code'       => $orderCode,
                'customer_id'      => $customerId,
                'seller_id'        => $sellerId,
                'total_price'      => $totalPrice,
                'payment_method'   => $paymentMethod,
                'payment_proof'    => $paymentProof,
                'shipping_address' => $_SESSION['user']['address'] ?? '-',
                'order_status'     => 'pending',
                'approval_status'  => 'pending'
            ]);

            // simpan order items
            foreach ($cartItems as $item) {
                $this->orderItemModel->create([
                    'order_id'      => $orderId,
                    'product_id'    => $item['product_id'],
                    'product_title' => $item['name'],
                    'quantity'      => $item['quantity'],
                    'price'         => $item['price']
                ]);
            }

            // hapus cart seller ini
            $this->orderModel->clearCartBySeller($customerId, $sellerId);
        }

        header('Location: ' . BASE_URL . '/?c=customerOrder&m=index');
        exit;
    }
}
