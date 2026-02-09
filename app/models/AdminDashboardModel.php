<?php

require_once APP_PATH . '/models/Database.php';

class AdminDashboardModel
{
    private $db;

    public function __construct()
    {
        // ⚠️ HARUS getConnection(), bukan getInstance()
        $this->db = Database::getInstance()->getConnection();
    }

    /* ================= STATISTIK ================= */

    public function totalUsers()
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM users WHERE is_deleted = 0"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function totalSellers()
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM users WHERE role_id = 2 AND is_deleted = 0"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function totalCustomers()
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM users WHERE role_id = 3 AND is_deleted = 0"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function totalProducts()
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM products WHERE is_active = 1"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function totalOrders()
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM orders"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function totalRevenue()
    {
        $stmt = $this->db->prepare(
            "SELECT SUM(total_price) AS total 
             FROM orders 
             WHERE approval_status = 'approved'"
        );
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* ================= RECENT ORDERS ================= */

    public function recentOrders($limit = 5)
    {
        $limit = (int)$limit;

        $stmt = $this->db->prepare("
            SELECT 
                o.order_code,
                u.name AS customer,
                o.total_price,
                o.approval_status,
                o.created_at
            FROM orders o
            JOIN users u ON o.customer_id = u.id
            ORDER BY o.created_at DESC
            LIMIT $limit
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ================= PENDING ORDERS ================= */

    public function pendingOrders()
    {
        $stmt = $this->db->prepare("
            SELECT 
                o.order_code,
                u.name AS customer,
                o.total_price,
                o.created_at
            FROM orders o
            JOIN users u ON o.customer_id = u.id
            WHERE o.approval_status = 'pending'
            ORDER BY o.created_at ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ================= GRAFIK ================= */

    public function ordersPerMonth()
    {
        $stmt = $this->db->prepare("
            SELECT 
                MONTH(created_at) AS month,
                COUNT(*) AS total
            FROM orders
            WHERE YEAR(created_at) = YEAR(CURDATE())
            GROUP BY MONTH(created_at)
            ORDER BY month
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
