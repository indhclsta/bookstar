<?php

require_once APP_PATH . '/models/Database.php';

class ReportModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPurchaseReport($customerId, $month = null, $year = null)
    {
        $sql = "
        SELECT 
            o.order_code,
            oi.product_title,
            oi.quantity,
            oi.price,
            (oi.quantity * oi.price) AS total_price,
            o.payment_method,
            o.payment_proof,
            o.created_at
        FROM orders o
        JOIN order_items oi ON oi.order_id = o.id
        WHERE 
            o.customer_id = ?
            AND o.approval_status = 'approved'
    ";

        $params = [$customerId];

        if ($month) {
            $sql .= " AND MONTH(o.created_at) = ?";
            $params[] = $month;
        }

        if ($year) {
            $sql .= " AND YEAR(o.created_at) = ?";
            $params[] = $year;
        }

        $sql .= " ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getMonthlyPurchaseChart($customerId, $year)
    {
        $stmt = $this->db->prepare("
        SELECT 
            MONTH(o.created_at) AS month,
            SUM(oi.quantity * oi.price) AS total
        FROM orders o
        JOIN order_items oi ON oi.order_id = o.id
        WHERE 
            o.customer_id = ?
            AND o.approval_status = 'approved'
            AND YEAR(o.created_at) = ?
        GROUP BY MONTH(o.created_at)
        ORDER BY MONTH(o.created_at)
    ");

        $stmt->execute([$customerId, $year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSalesReportBySeller($sellerId, $month = null, $year = null)
    {
        $sql = "
    SELECT
        o.order_code,
        o.created_at,
        o.payment_method,
        o.order_status,
        oi.product_title,
        oi.quantity,
        oi.price,

        (oi.quantity * oi.price) AS total_penjualan,
        (oi.quantity * p.cost_price) AS total_modal,
        ((oi.quantity * oi.price) - (oi.quantity * p.cost_price)) AS total_keuntungan

    FROM orders o
    JOIN order_items oi ON oi.order_id = o.id
    JOIN products p ON oi.product_id = p.id
    WHERE 
        o.seller_id = :seller_id
        AND o.approval_status = 'approved'
    ";

        if ($month) {
            $sql .= " AND MONTH(o.created_at) = :month";
        }

        if ($year) {
            $sql .= " AND YEAR(o.created_at) = :year";
        }

        $sql .= " ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':seller_id', $sellerId);

        if ($month) {
            $stmt->bindParam(':month', $month);
        }

        if ($year) {
            $stmt->bindParam(':year', $year);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getTotalIncome($sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            SUM((oi.quantity * oi.price) - (oi.quantity * p.cost_price)) AS total_profit
        FROM orders o
        JOIN order_items oi ON oi.order_id = o.id
        JOIN products p ON oi.product_id = p.id
        WHERE 
            o.seller_id = :seller_id
            AND o.approval_status = 'approved'
    ");

        $stmt->bindParam(':seller_id', $sellerId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total_profit'] ?? 0;
    }

    public function getMonthlyProfitBySeller($sellerId, $year)
    {
        $stmt = $this->db->prepare("
        SELECT 
            MONTH(o.created_at) AS month,
            SUM((oi.quantity * oi.price) - (oi.quantity * p.cost_price)) AS profit
        FROM orders o
        JOIN order_items oi ON oi.order_id = o.id
        JOIN products p ON oi.product_id = p.id
        WHERE 
            o.seller_id = ?
            AND o.approval_status = 'approved'
            AND YEAR(o.created_at) = ?
        GROUP BY MONTH(o.created_at)
        ORDER BY MONTH(o.created_at)
    ");

        $stmt->execute([$sellerId, $year]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
