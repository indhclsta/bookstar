<?php

class ChatModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getThreadsForSeller($sellerId)
    {
        // daftar buyer yang pernah chat + unread count
        $stmt = $this->db->prepare("
            SELECT 
                buyer_id,
                MAX(created_at) AS last_time,
                SUM(CASE WHEN sender_role='buyer' AND is_read=0 THEN 1 ELSE 0 END) AS unread
            FROM chat_messages
            WHERE seller_id=?
            GROUP BY buyer_id
            ORDER BY last_time DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMessages($sellerId, $buyerId)
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM chat_messages
            WHERE seller_id=? AND buyer_id=?
            ORDER BY id ASC
        ");
        $stmt->execute([$sellerId, $buyerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markReadForSeller($sellerId, $buyerId)
    {
        $stmt = $this->db->prepare("
            UPDATE chat_messages
            SET is_read=1
            WHERE seller_id=? AND buyer_id=? AND sender_role='buyer'
        ");
        return $stmt->execute([$sellerId, $buyerId]);
    }

    public function sendSellerMessage($sellerId, $buyerId, $message)
    {
        $stmt = $this->db->prepare("
            INSERT INTO chat_messages (seller_id, buyer_id, sender_role, message, is_read, created_at)
            VALUES (?, ?, 'seller', ?, 1, NOW())
        ");
        return $stmt->execute([$sellerId, $buyerId, $message]);
    }
}
