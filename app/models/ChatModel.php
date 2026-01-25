<?php

class ChatModel
{
    private $db;

    public function __construct()
    {
        // ambil PDO connection
        $this->db = Database::getInstance()->getConnection();
    }

    // Ambil daftar chat berdasarkan seller
    public function getChatsBySeller($sellerId)
    {
        $sql = "
            SELECT c.*, u.name AS customer_name
            FROM chats c
            JOIN users u ON u.id = c.customer_id
            WHERE c.seller_id = ?
            ORDER BY c.created_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ambil pesan berdasarkan chat_id
    public function getMessages($chatId)
    {
        $sql = "
            SELECT *
            FROM chat_messages
            WHERE chat_id = ?
            ORDER BY created_at ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$chatId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Kirim pesan
    public function sendMessage($chatId, $senderRole, $senderId, $message)
    {
        $sql = "
            INSERT INTO chat_messages
            (chat_id, sender_role, sender_id, message)
            VALUES (?, ?, ?, ?)
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $chatId,
            $senderRole,
            $senderId,
            $message
        ]);
    }

    public function getChatsByCustomer($customerId)
{
    $sql = "
        SELECT c.*, u.name AS seller_name
        FROM chats c
        JOIN users u ON u.id = c.seller_id
        WHERE c.customer_id = ?
        ORDER BY c.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$customerId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getMessagesByCustomer($chatId, $customerId)
{
    $sql = "
        SELECT m.*
        FROM chat_messages m
        JOIN chats c ON c.id = m.chat_id
        WHERE m.chat_id = ? AND c.customer_id = ?
        ORDER BY m.created_at ASC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$chatId, $customerId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getOrCreateChat($sellerId, $customerId)
{
    $sql = "SELECT id FROM chats WHERE seller_id = ? AND customer_id = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$sellerId, $customerId]);

    $chat = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($chat) return $chat['id'];

    $sql = "INSERT INTO chats (seller_id, customer_id) VALUES (?, ?)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$sellerId, $customerId]);

    return $this->db->lastInsertId();
}


}
