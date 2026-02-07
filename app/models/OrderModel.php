<?php

require_once APP_PATH . '/models/Database.php';

class OrderModel
{

    private $db;

    public function __construct()
    {
        // Ambil koneksi PDO dari Database singleton
        $this->db = Database::getInstance()->getConnection();
    }

    // CREATE ORDER
    public function create($data)
    {
        $stmt = $this->db->prepare("
        INSERT INTO orders 
(order_code, checkout_code, customer_id, seller_id, total_price, payment_method, payment_proof, shipping_address, order_status, approval_status) 
VALUES (?,?,?,?,?,?,?,?,?,?)
    ");
        $stmt->execute([
            $data['order_code'],      // unik per seller
            $data['checkout_code'],   // sama untuk 1 checkout
            $data['customer_id'],
            $data['seller_id'],
            $data['total_price'],
            $data['payment_method'],
            $data['payment_proof'],
            $data['shipping_address'],
            $data['order_status'],
            $data['approval_status']
        ]);

        return $this->db->lastInsertId();
    }


    // UPDATE ORDER
    public function update($orderId, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE orders 
            SET approval_status=?, order_status=?, updated_at=CURRENT_TIMESTAMP 
            WHERE id=?
        ");
        $stmt->execute([$data['approval_status'], $data['order_status'], $orderId]);
    }

    // FIND ORDER BY ID
    public function findById($orderId)
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id=?");
        $stmt->execute([$orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // GET CART ITEMS BY SELLER
    public function getCartItemsBySeller($customerId, $sellerId)
    {
        $stmt = $this->db->prepare("
            SELECT c.product_id, c.quantity, p.name, p.price
            FROM carts c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id=? AND p.seller_id=?
        ");
        $stmt->execute([$customerId, $sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CLEAR CART BY SELLER
    public function clearCartBySeller($customerId, $sellerId)
    {
        $stmt = $this->db->prepare("
            DELETE c FROM carts c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id=? AND p.seller_id=?
        ");
        $stmt->execute([$customerId, $sellerId]);
    }

    // GET CART GROUPED BY SELLER
    public function getCartGroupedBySeller($customerId)
    {
        $stmt = $this->db->prepare("
            SELECT p.seller_id, c.product_id, c.quantity, p.name, p.price
            FROM carts c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id=?
        ");
        $stmt->execute([$customerId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];
        foreach ($items as $item) {
            $grouped[$item['seller_id']][] = $item;
        }
        return $grouped;
    }

    // ADD NOTIFICATION
    public function addNotification($userId, $message)
    {
        $stmt = $this->db->prepare("INSERT INTO notifications (user_id, message) VALUES (?,?)");
        $stmt->execute([$userId, $message]);
    }

    // GET ORDERS WITH ITEMS FOR CUSTOMER
    public function getOrdersWithItems($customerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.*,

            -- SELLER
            u.name    AS seller_name,
            u.address AS seller_address,

            -- ITEM
            oi.product_title,
            oi.quantity,
            oi.price

        FROM orders o
        JOIN users u ON o.seller_id = u.id
        JOIN order_items oi ON oi.order_id = o.id

        WHERE o.customer_id = ?
        ORDER BY o.created_at DESC
    ");

        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getOrdersBySeller($sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.*, 
            u.name AS customer_name
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        WHERE o.seller_id = ?
        ORDER BY o.id DESC
    ");
        $stmt->execute([$sellerId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // AMBIL ITEMS SESUAI ORDER ID
        foreach ($orders as &$order) {
            $stmtItems = $this->db->prepare("
            SELECT product_title, quantity, price
            FROM order_items
            WHERE order_id = ?
        ");
            $stmtItems->execute([$order['id']]);
            $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        }

        return $orders;
    }

    public function getApprovedOrdersByCustomer($customerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.order_code,
            oi.product_title,
            oi.quantity,
            oi.price,
            o.payment_proof,
            o.payment_method
        FROM orders o
        JOIN order_items oi ON oi.order_id = o.id
        WHERE 
            o.customer_id = ?
            AND o.approval_status = 'approved'
        ORDER BY o.created_at DESC
    ");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function addOrderItem($orderId, $productId, $title, $qty, $price)
    {
        $stmt = $this->db->prepare("
        INSERT INTO order_items 
        (order_id, product_id, product_title, quantity, price)
        VALUES (?,?,?,?,?)
    ");
        $stmt->execute([
            $orderId,
            $productId,
            $title,
            $qty,
            $price
        ]);
    }

    // ================== UPDATE APPROVAL ==================
    public function updateApproval($orderId, $status)
    {
        $stmt = $this->db->prepare("
        UPDATE orders 
        SET approval_status = ?, 
            order_status = ?, 
            updated_at = CURRENT_TIMESTAMP
        WHERE id = ?
    ");

        // kalau approve → lanjut proses
        // kalau reject → otomatis cancelled
        $orderStatus = ($status === 'approved') ? 'process' : 'cancelled';

        $stmt->execute([
            $status,
            $orderStatus,
            $orderId
        ]);
    }

    // ================== INPUT RESI ==================
    public function inputResi($orderId, $resi, $trackingUrl = null)
    {
        $stmt = $this->db->prepare("
        UPDATE orders 
        SET resi = :resi,
            tracking_url = :tracking_url,
            order_status = 'shipped',
            updated_at = CURRENT_TIMESTAMP
        WHERE id = :order_id
    ");

        return $stmt->execute([
            ':resi' => $resi,
            ':tracking_url' => $trackingUrl,
            ':order_id' => $orderId
        ]);
    }

    public function deleteOrder($orderId)
    {
        $stmt = $this->db->prepare("DELETE FROM orders WHERE id = ?");
        return $stmt->execute([$orderId]);
    }

    public function getByCustomer($customerId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM orders
        WHERE customer_id = ?
        ORDER BY created_at DESC
    ");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByOrderCode($orderCode, $customerId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM orders 
        WHERE order_code = ? AND customer_id = ?
    ");
        $stmt->execute([$orderCode, $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByCheckoutCode($code, $customerId)
    {
        $stmt = $this->db->prepare("
        SELECT *
        FROM orders
        WHERE checkout_code = ? AND customer_id = ?
        ORDER BY id ASC
    ");
        $stmt->execute([$code, $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInvoiceByCheckout($checkoutCode, $customerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.*,
            u.name AS seller_name
        FROM orders o
        JOIN users u ON o.seller_id = u.id
        WHERE o.checkout_code = ? AND o.customer_id = ?
        ORDER BY o.id ASC
    ");
        $stmt->execute([$checkoutCode, $customerId]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $stmtItems = $this->db->prepare("
            SELECT product_title, quantity, price
            FROM order_items
            WHERE order_id = ?
        ");
            $stmtItems->execute([$order['id']]);
            $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
        }

        return $orders;
    }
}
