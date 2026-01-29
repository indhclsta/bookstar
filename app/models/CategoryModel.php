<?php
require_once APP_PATH . '/models/Database.php';
class CategoryModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /* ================= ADMIN ================= */
    public function getAdminCategories()
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM categories 
             WHERE owner_role='admin' 
             ORDER BY id ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function storeAdmin($name)
    {
        $check = $this->db->prepare(
            "SELECT id FROM categories 
             WHERE name=? AND owner_role='admin'"
        );
        $check->execute([$name]);

        if ($check->rowCount() > 0) {
            return false;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO categories (name, owner_role) 
             VALUES (?, 'admin')"
        );
        return $stmt->execute([$name]);
    }

    public function updateAdmin($id, $name)
    {
        $check = $this->db->prepare(
            "SELECT id FROM categories 
             WHERE name=? AND owner_role='admin' AND id!=?"
        );
        $check->execute([$name, $id]);

        if ($check->rowCount() > 0) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE categories SET name=? 
             WHERE id=? AND owner_role='admin'"
        );
        return $stmt->execute([$name, $id]);
    }

    public function deleteAdmin($id)
    {
        $stmt = $this->db->prepare(
            "UPDATE categories 
         SET is_active = 0 
         WHERE id = ? AND owner_role = 'admin'"
        );
        return $stmt->execute([$id]);
    }


    public function hasProducts($categoryId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) 
         FROM products 
         WHERE category_id = ?"
        );
        $stmt->execute([$categoryId]);

        return $stmt->fetchColumn() > 0;
    }


    /* ================= SELLER ================= */
    public function getSellerCategories($sellerId)
    {
        $sql = "SELECT * FROM categories
            WHERE owner_role = 'admin'
               OR (owner_role = 'seller' AND created_by = :seller_id)
            ORDER BY owner_role ASC, name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'seller_id' => $sellerId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function isSellerCategory($id, $sellerId)
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM categories
         WHERE id = :id AND created_by = :seller_id"
        );
        $stmt->execute([
            'id' => $id,
            'seller_id' => $sellerId
        ]);
        return (bool)$stmt->fetch();
    }


    // Tambah kategori seller
    public function storeSeller($name, $sellerId)
    {
        // cek duplikat per seller
        $check = $this->db->prepare(
            "SELECT id FROM categories
             WHERE name = :name AND created_by = :seller_id"
        );
        $check->execute([
            'name' => $name,
            'seller_id' => $sellerId
        ]);

        if ($check->fetch()) {
            return false;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO categories (name, created_by, owner_role)
             VALUES (:name, :created_by, 'seller')"
        );

        return $stmt->execute([
            'name' => $name,
            'created_by' => $sellerId
        ]);
    }

    // Update kategori seller
    public function updateSeller($id, $name, $sellerId)
    {
        $check = $this->db->prepare(
            "SELECT id FROM categories
             WHERE name = :name
               AND created_by = :seller_id
               AND id != :id"
        );
        $check->execute([
            'name' => $name,
            'seller_id' => $sellerId,
            'id' => $id
        ]);

        if ($check->fetch()) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE categories
             SET name = :name
             WHERE id = :id AND created_by = :seller_id"
        );

        return $stmt->execute([
            'name' => $name,
            'id' => $id,
            'seller_id' => $sellerId
        ]);
    }

    public function hasSellerProducts($categoryId, $sellerId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) 
         FROM products 
         WHERE category_id = :category_id
           AND seller_id = :seller_id"
        );

        $stmt->execute([
            'category_id' => $categoryId,
            'seller_id'   => $sellerId
        ]);

        return $stmt->fetchColumn() > 0;
    }


    // Hapus kategori seller
    public function deleteSeller($id, $sellerId)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM categories
             WHERE id = :id AND created_by = :seller_id"
        );

        return $stmt->execute([
            'id' => $id,
            'seller_id' => $sellerId
        ]);
    }
}
