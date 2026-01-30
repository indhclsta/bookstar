<?php
require_once APP_PATH . '/models/Database.php';
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
        SELECT 
            p.*,
            c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
        WHERE p.seller_id = ?
        AND p.is_active = 1
        ORDER BY p.id ASC
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

    public function findForSeller($productId, $sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT *
        FROM products
        WHERE id = ?
        AND seller_id = ?
        AND is_active = 1
        LIMIT 1
    ");
        $stmt->execute([$productId, $sellerId]);
        return $stmt->fetch();
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

    public function softDelete($id, $sellerId)
    {
        $stmt = $this->db->prepare("
        UPDATE products
        SET is_active = 0
        WHERE id = ? AND seller_id = ?
    ");
        return $stmt->execute([$id, $sellerId]);
    }


    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countBySeller($sellerId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) AS total FROM products WHERE seller_id = ?");
        $stmt->execute([$sellerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // KURANGI STOK PRODUK
    public function reduceStock($productId, $qty)
    {
        $stmt = $this->db->prepare("
        UPDATE products
        SET stock = stock - ?
        WHERE id = ? AND stock >= ?
    ");
        return $stmt->execute([$qty, $productId, $qty]);
    }

    // FILTER + SEARCH PRODUK (UNTUK CUSTOMER)
    public function getFilteredProducts($search = null, $categoryId = null)
    {
        $sql = "
        SELECT p.*, c.name AS category_name
        FROM products p
        JOIN categories c ON c.id = p.category_id
        WHERE 1=1
    ";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND p.name LIKE :search ";
            $params['search'] = '%' . $search . '%';
        }

        if (!empty($categoryId)) {
            $sql .= " AND p.category_id = :category ";
            $params['category'] = $categoryId;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
