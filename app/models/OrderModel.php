<?php

class OrderModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getOrdersForSeller($sellerId)
    {
        // judul & qty ringkas ambil dari item pertama + total qty
        $stmt = $this->db->prepare("
            SELECT 
                o.*,
                (SELECT oi.title_snapshot FROM order_items oi WHERE oi.order_id=o.id LIMIT 1) AS title,
                (SELECT SUM(oi.qty) FROM order_items oi WHERE oi.order_id=o.id) AS qty
            FROM orders o
            WHERE o.seller_id = ?
            ORDER BY o.id DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderDetailForSeller($orderId, $sellerId)
    {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id=? AND seller_id=? LIMIT 1");
        $stmt->execute([$orderId, $sellerId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) return null;

        $stmt2 = $this->db->prepare("SELECT * FROM order_items WHERE order_id=?");
        $stmt2->execute([$orderId]);
        $order['items'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }

    public function setApproved($orderId, $sellerId)
    {
        $stmt = $this->db->prepare("
            UPDATE orders
            SET status='approved', approved_at=NOW()
            WHERE id=? AND seller_id=? AND status IN ('pending_payment','pending')
        ");
        return $stmt->execute([$orderId, $sellerId]);
    }

    public function setRejected($orderId, $sellerId)
    {
        $stmt = $this->db->prepare("
            UPDATE orders
            SET status='rejected', rejected_at=NOW(), resi_number=NULL, tracking_url=NULL
            WHERE id=? AND seller_id=? AND status IN ('pending_payment','pending','approved')
        ");
        return $stmt->execute([$orderId, $sellerId]);
    }

    public function setResiAndShipped($orderId, $sellerId, $resi, $trackingUrl)
    {
        $stmt = $this->db->prepare("
            UPDATE orders
            SET resi_number=?, tracking_url=?, status='shipped', shipped_at=NOW()
            WHERE id=? AND seller_id=? AND status='approved'
        ");
        return $stmt->execute([$resi, $trackingUrl, $orderId, $sellerId]);
    }

    public function countOrdersWithResi($sellerId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) AS total
            FROM orders
            WHERE seller_id=? AND resi_number IS NOT NULL AND resi_number <> ''
        ");
        $stmt->execute([$sellerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    // delete jika status refund/rejected dan sudah lewat N menit
    public function deleteIfRejectedOrRefundAfterDelay($orderId, $sellerId, $delayMinutes = 2)
    {
        // ambil order
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id=? AND seller_id=? LIMIT 1");
        $stmt->execute([$orderId, $sellerId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) return false;

        if (!in_array($order['status'], ['rejected','refund'])) return false;

        $timeField = $order['status'] === 'rejected' ? $order['rejected_at'] : ($order['refund_at'] ?? $order['rejected_at']);
        if (!$timeField) return false;

        $allowedAt = strtotime($timeField) + ($delayMinutes * 60);
        if (time() < $allowedAt) return false;

        // hapus items dulu
        $stmt2 = $this->db->prepare("DELETE FROM order_items WHERE order_id=?");
        $stmt2->execute([$orderId]);

        // hapus order
        $stmt3 = $this->db->prepare("DELETE FROM orders WHERE id=? AND seller_id=?");
        return $stmt3->execute([$orderId, $sellerId]);
    }
}
