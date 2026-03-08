<?php
require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/OrderModel.php';
require_once APP_PATH . '/models/OrderItemModel.php';
require_once APP_PATH . '/models/NotificationModel.php';


class CustomerOrderController
{
    private $orderModel;
    private $orderItemModel;

    public function __construct()
    {
        Auth::check();
        Auth::role('customer');
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    public function index()
    {
        $customerId = $_SESSION['user']['id'];
        $orders = $this->orderModel->getOrdersWithItems($customerId);
        require APP_PATH . '/views/customer/order_list.php';
    }

    public function downloadInvoice()
    {
        $orderId = $_GET['id'];
        $order = $this->orderModel->findById($orderId);
        $items = $this->orderItemModel->getByOrderId($orderId);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="invoice_' . $order['order_code'] . '.pdf"');

        require APP_PATH . '/views/customer/invoice.php';
    }
    public function checkout()
    {
        Auth::check();
        Auth::role('customer');

        $customerId = $_SESSION['user']['id'];

        $groupedCart = $this->orderModel->getCartGroupedBySeller($customerId);

        if (empty($groupedCart)) {
            $_SESSION['error'] = "Cart kosong";
            header("Location: " . BASE_URL . "/?c=cart&m=index");
            exit;
        }

        $productModel = new ProductModel();

        foreach ($groupedCart as $sellerId => $items) {

            // =============================
            // VALIDASI STOK DULU
            // =============================
            foreach ($items as $item) {

                $availableStock = $productModel->getAvailableStock($item['product_id']);

                if ($item['quantity'] > $availableStock) {

                    $_SESSION['error'] = "Stok produk {$item['name']} tidak mencukupi";

                    header("Location: " . BASE_URL . "/?c=cart&m=index");
                    exit;
                }
            }

            // =============================
            // UPLOAD PAYMENT PROOF
            // =============================
            $uploadedFileName = null;

            if (!empty($_FILES['payment_proof']['name'])) {

                $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);

                $uploadedFileName = 'payment_' . uniqid() . '.' . $ext;

                move_uploaded_file(
                    $_FILES['payment_proof']['tmp_name'],
                    APP_PATH . '/../public/uploads/payments/' . $uploadedFileName
                );
            }

            // =============================
            // DATA ORDER
            // =============================
            $orderData = [
                'order_code' => 'ORD' . strtoupper(uniqid()),
                'customer_id' => $customerId,
                'seller_id' => $sellerId,
                'total_price' => array_sum(array_map(
                    fn($i) => $i['price'] * $i['quantity'],
                    $items
                )),
                'payment_method' => $_POST['payment_method'],
                'payment_proof' => $uploadedFileName,
                'shipping_address' => $_POST['shipping_address'],
                'order_status' => 'pending',
                'approval_status' => 'pending'
            ];

            // =============================
            // CREATE ORDER
            // =============================
            $orderId = $this->orderModel->create($orderData);

            // =============================
            // NOTIFIKASI KE SELLER
            // =============================
            $notificationModel = new NotificationModel();
            $notificationModel->create([
                'user_id' => $sellerId,
                'title'   => 'Pesanan Baru',
                'message' => 'Ada pesanan baru dari customer #' . $customerId,
                'link'    => BASE_URL . '/?c=sellerOrder&m=index'
            ]);

            // =============================
            // INSERT ORDER ITEMS
            // =============================
            foreach ($items as $item) {

                $this->orderModel->addOrderItem(
                    $orderId,
                    $item['product_id'],
                    $item['name'],
                    $item['quantity'],
                    $item['price']
                );
            }

            // =============================
            // CLEAR CART PER SELLER
            // =============================
            $this->orderModel->clearCartBySeller($customerId, $sellerId);
        }

        $_SESSION['success'] = 'Pesanan berhasil dibuat';

        header("Location: " . BASE_URL . "/?c=customerOrder&m=index");
    }
}
