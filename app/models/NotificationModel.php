<?php

class NotificationModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($data)
{
    $sql = "INSERT INTO notifications 
            (user_id, title, message, link, is_read)
            VALUES (:user_id, :title, :message, :link, :is_read)";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':user_id' => $data['user_id'],
        ':title'   => $data['title'],
        ':message' => $data['message'],
        ':link'    => $data['link'],
        ':is_read' => $data['is_read'] ?? 0
    ]);
}

    public function getUnreadByUser($userId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM notifications
            WHERE user_id = :user_id AND is_read = 0
            ORDER BY created_at DESC
            LIMIT 5
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUnread($userId)
{
    $sql = "SELECT COUNT(*) FROM notifications
            WHERE user_id = ? AND is_read = 0";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}


    public function markAsRead($id)
    {
        $stmt = $this->db->prepare("
            UPDATE notifications SET is_read = 1 WHERE id = :id
        ");
        return $stmt->execute(['id' => $id]);
    }

    public function getByUser($userId, $limit = 5)
{
    $limit = (int)$limit; // 🔒 safety

    $sql = "SELECT *
            FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT $limit";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function markAllAsReadByUser($userId)
{
    $sql = "UPDATE notifications
            SET is_read = 1
            WHERE user_id = ? AND is_read = 0";

    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$userId]);
}


}
