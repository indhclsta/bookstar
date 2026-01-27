<?php

require_once APP_PATH . '/models/Database.php';

class CartModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByUser($userId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            c.product_id,
            c.quantity,
            p.name,
            p.price,
            p.image,
            p.stock
        FROM carts c
        JOIN products p ON p.id = c.product_id
        WHERE c.user_id = ?
    ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function add($userId, $productId, $qty)
    {
        $stmt = $this->db->prepare("
            INSERT INTO carts (user_id, product_id, quantity)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE quantity = quantity + ?
        ");
        return $stmt->execute([$userId, $productId, $qty, $qty]);
    }

    public function remove($userId, $productId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM carts WHERE user_id = ? AND product_id = ?
        ");
        return $stmt->execute([$userId, $productId]);
    }

    public function clear($userId)
    {
        $stmt = $this->db->prepare("DELETE FROM carts WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function clearCart($userId)
    {
        $stmt = $this->db->prepare("DELETE FROM carts WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    public function updateQty($userId, $productId, $qty)
    {
        $stmt = $this->db->prepare("
        UPDATE carts 
        SET quantity = ? 
        WHERE user_id = ? AND product_id = ?
    ");
        return $stmt->execute([$qty, $userId, $productId]);
    }

    public function getItem($userId, $productId)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM carts 
        WHERE user_id = ? AND product_id = ?
    ");
        $stmt->execute([$userId, $productId]);
        return $stmt->fetch();
    }
    public function countByUser($userId)
    {
        $stmt = $this->db->prepare("
        SELECT COALESCE(SUM(quantity), 0) AS total
        FROM carts
        WHERE user_id = ?
    ");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }
}
