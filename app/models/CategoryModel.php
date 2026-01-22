<?php

require_once APP_PATH . '/models/Database.php';

class CategoryModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($name)
    {
        $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (:name)");
        return $stmt->execute(['name' => $name]);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name)
    {
        $stmt = $this->db->prepare(
            "UPDATE categories SET name = :name WHERE id = :id"
        );
        return $stmt->execute([
            'id'   => $id,
            'name' => $name
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function existsByName($name, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM categories 
             WHERE LOWER(name) = LOWER(:name) AND id != :id"
            );
            $stmt->execute([
                'name' => $name,
                'id'   => $excludeId
            ]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM categories 
             WHERE LOWER(name) = LOWER(:name)"
            );
            $stmt->execute(['name' => $name]);
        }

        return $stmt->fetchColumn() > 0;
    }
}
