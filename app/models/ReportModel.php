<?php

class ReportModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // ringkas summary (tolak tidak masuk)
    public function getSummary($sellerId, $month, $year)
    {
        $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) AS total_order,
                SUM((SELECT SUM(oi.qty * oi.price_snapshot) FROM order_items oi WHERE oi.order_id=o.id)) AS omzet
            FROM orders o
            WHERE o.seller_id=?
              AND o.status IN ('approved','shipped','refund')
              AND MONTH(o.created_at)=?
              AND YEAR(o.created_at)=?
        ");
        $stmt->execute([$sellerId, $month, $year]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTable($sellerId, $month, $year)
    {
        $stmt = $this->db->prepare("
            SELECT 
                o.created_at,
                o.order_code,
                o.payment_method,
                o.status,
                (SELECT oi.title_snapshot FROM order_items oi WHERE oi.order_id=o.id LIMIT 1) AS title,
                (SELECT SUM(oi.qty) FROM order_items oi WHERE oi.order_id=o.id) AS qty,
                (SELECT SUM(oi.qty * oi.price_snapshot) FROM order_items oi WHERE oi.order_id=o.id) AS total
            FROM orders o
            WHERE o.seller_id=?
              AND o.status IN ('approved','shipped','refund')
              AND MONTH(o.created_at)=?
              AND YEAR(o.created_at)=?
            ORDER BY o.id DESC
        ");
        $stmt->execute([$sellerId, $month, $year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChartDaily($sellerId, $month, $year)
    {
        $stmt = $this->db->prepare("
            SELECT 
                DATE(o.created_at) AS tgl,
                SUM((SELECT SUM(oi.qty * oi.price_snapshot) FROM order_items oi WHERE oi.order_id=o.id)) AS total
            FROM orders o
            WHERE o.seller_id=?
              AND o.status IN ('approved','shipped','refund')
              AND MONTH(o.created_at)=?
              AND YEAR(o.created_at)=?
            GROUP BY DATE(o.created_at)
            ORDER BY DATE(o.created_at) ASC
        ");
        $stmt->execute([$sellerId, $month, $year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
