<?php

require_once APP_PATH . '/models/Database.php';

class SearchModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function searchCategory($keyword)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM categories WHERE name LIKE ?"
        );
        $stmt->execute(['%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
