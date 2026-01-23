<?php

class ProductModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getBySeller($sellerId)
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.seller_id = ?
            ORDER BY p.id DESC
        ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function nameExists($sellerId, $name, $excludeId = null)
    {
        $sql = "SELECT id FROM products WHERE seller_id = ? AND name = ?";
        $params = [$sellerId, $name];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO products 
            (seller_id, category_id, name, description, cost_price, price, stock, image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['seller_id'],
            $data['category_id'],
            $data['name'],
            $data['description'],
            $data['cost_price'],
            $data['price'],
            $data['stock'],
            $data['image']
        ]);
    }

    public function findForSeller($id, $sellerId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM products
            WHERE id = ? AND seller_id = ?
            LIMIT 1
        ");
        $stmt->execute([$id, $sellerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data)
    {
        $stmt = $this->db->prepare("
            UPDATE products
            SET category_id=?, name=?, description=?, cost_price=?, price=?, stock=?, image=?
            WHERE id=? AND seller_id=?
        ");

        return $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['description'],
            $data['cost_price'],
            $data['price'],
            $data['stock'],
            $data['image'],
            $data['id'],
            $data['seller_id']
        ]);
    }

    public function delete($id, $sellerId)
    {
        $stmt = $this->db->prepare("
            DELETE FROM products
            WHERE id=? AND seller_id=?
        ");
        return $stmt->execute([$id, $sellerId]);
    }
}
