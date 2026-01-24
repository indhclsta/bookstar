<?php

require_once APP_PATH . '/models/Database.php';

class OrderModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Ambil daftar order milik seller beserta detail item, qty, buyer, dan alamat
     */
    public function getOrdersBySeller($sellerId)
    {
        $stmt = $this->db->prepare("
            SELECT o.id, o.order_code, o.payment_method, o.payment_proof,
                   o.approval_status, o.order_status, o.tracking_number,
                   u.name AS buyer_name, u.address AS buyer_address,
                   oi.product_title, oi.quantity
            FROM orders o
            JOIN users u ON o.customer_id = u.id
            JOIN order_items oi ON oi.order_id = o.id
            JOIN products p ON p.id = oi.product_id
            WHERE o.seller_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update status approve order
     */
    public function updateApprove($orderId, $status)
    {
        $finalStatus = $status === 'approved' ? 'menunggu resi' : 'refund';
        $stmt = $this->db->prepare("
            UPDATE orders 
            SET approval_status = ?, order_status = ?
            WHERE id = ?
        ");
        return $stmt->execute([$status, $finalStatus, $orderId]);
    }

    /**
     * Simpan nomor resi dan tracking
     */
    public function saveResi($orderId, $resi, $tracking)
    {
        $stmt = $this->db->prepare("
            UPDATE orders
            SET tracking_number = ?, order_status = 'dikirim'
            WHERE id = ?
        ");
        return $stmt->execute([$resi, $orderId]);
    }
}
