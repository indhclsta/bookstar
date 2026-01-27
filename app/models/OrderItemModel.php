<?php
require_once APP_PATH . '/models/Database.php';

class OrderItemModel
{

    private $db;

    public function __construct()
    {
        // Ambil koneksi PDO dari Database singleton
        $this->db = Database::getInstance()->getConnection();
    }

    // CREATE ORDER ITEM
    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO order_items 
            (order_id, product_id, product_title, quantity, price) 
            VALUES (?,?,?,?,?)
        ");
        $stmt->execute([
            $data['order_id'],
            $data['product_id'],
            $data['product_title'],
            $data['quantity'],
            $data['price']
        ]);
    }

    // GET ORDER ITEMS BY ORDER ID
    public function getByOrderId($orderId)
    {
        $stmt = $this->db->prepare("
        SELECT oi.*, p.seller_id
        FROM order_items oi
        LEFT JOIN products p ON p.id = oi.product_id
        WHERE oi.order_id = ?
    ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
