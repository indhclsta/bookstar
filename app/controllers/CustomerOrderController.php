<?php
require_once APP_PATH . '/core/auth.php';
require_once APP_PATH . '/models/OrderModel.php';
require_once APP_PATH . '/models/OrderItemModel.php';

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

        // ambil cart yang sudah dikelompokkan per seller
        $groupedCart = $this->orderModel->getCartGroupedBySeller($customerId);

        foreach ($groupedCart as $sellerId => $items) {

            $uploadedFileName = null;

            if (!empty($_FILES['payment_proof']['name'])) {
                $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
                $uploadedFileName = 'payment_' . uniqid() . '.' . $ext;

                move_uploaded_file(
                    $_FILES['payment_proof']['tmp_name'],
                    APP_PATH . '/../public/uploads/payments/' . $uploadedFileName
                );
            }


            $orderData = [
                'order_code' => 'ORD' . strtoupper(uniqid()), // 🔥 PINDAH KE SINI
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

            $orderId = $this->orderModel->create($orderData);

            foreach ($items as $item) {
                $this->orderModel->addOrderItem(
                    $orderId,
                    $item['product_id'],
                    $item['name'],
                    $item['quantity'],
                    $item['price']
                );
            }

            // hapus cart seller ini
            $this->orderModel->clearCartBySeller($customerId, $sellerId);
        }

        $_SESSION['success'] = 'Pesanan berhasil dibuat';
        header("Location: " . BASE_URL . "/?c=customerOrder&m=index");
    }
}
