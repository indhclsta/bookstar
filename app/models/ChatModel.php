<?php
require_once APP_PATH . '/models/Database.php';

class ChatModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Ambil semua chat yang melibatkan seller
    public function getChatsBySeller($sellerId)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.id AS user_id, u.name AS user_name, u.photo AS user_photo
            FROM chats c
            JOIN users u ON (c.sender_id = u.id OR c.receiver_id = u.id)
            WHERE c.sender_id = :seller_id OR c.receiver_id = :seller_id
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([':seller_id' => $sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kirim pesan
    public function sendMessage($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO chats (sender_id, receiver_id, message)
            VALUES (:sender_id, :receiver_id, :message)
        ");
        return $stmt->execute([
            ':sender_id' => $data['sender_id'],
            ':receiver_id' => $data['receiver_id'],
            ':message' => $data['message']
        ]);
    }

    // Ambil percakapan antara seller dan customer tertentu
    public function getChatWithUser($sellerId, $customerId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM chats
            WHERE (sender_id = :seller_id AND receiver_id = :customer_id)
               OR (sender_id = :customer_id AND receiver_id = :seller_id)
            ORDER BY created_at ASC
        ");
        $stmt->execute([
            ':seller_id' => $sellerId,
            ':customer_id' => $customerId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil semua customer yang pernah membeli produk seller
    public function getCustomersByOrders($sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT DISTINCT u.id, u.name, u.photo
        FROM users u
        JOIN orders o ON o.customer_id = u.id
        JOIN order_items oi ON oi.order_id = o.id
        JOIN products p ON p.id = oi.product_id
        WHERE p.seller_id = :seller_id
    ");
        $stmt->execute([':seller_id' => $sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Ambil semua chat user (customer)
    public function getChatsByCustomer($customerId)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.id AS user_id, u.name AS user_name, u.photo AS user_photo
            FROM chats c
            JOIN users u ON (c.sender_id = u.id OR c.receiver_id = u.id)
            WHERE c.sender_id = :customer_id OR c.receiver_id = :customer_id
            ORDER BY c.created_at ASC
        ");
        $stmt->execute([':customer_id' => $customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getChatWithSeller($customerId, $sellerId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM chats
            WHERE (sender_id = :customer_id AND receiver_id = :seller_id)
               OR (sender_id = :seller_id AND receiver_id = :customer_id)
            ORDER BY created_at ASC
        ");
        $stmt->execute([
            ':customer_id' => $customerId,
            ':seller_id' => $sellerId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil daftar semua seller untuk customer sidebar
    // Ambil daftar semua seller untuk customer sidebar (berdasarkan produk)
    public function getAllSellers()
    {
        $stmt = $this->db->prepare("
        SELECT DISTINCT u.id, u.name, u.photo
        FROM users u
        JOIN products p ON p.seller_id = u.id
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
