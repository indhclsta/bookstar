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
         WHERE owner_role='admin' AND is_active = 1
         ORDER BY id ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function storeAdmin($name)
    {
        $check = $this->db->prepare(
            "SELECT id FROM categories 
         WHERE name = ? 
           AND owner_role = 'admin'
           AND is_active = 1"
        );
        $check->execute([$name]);

        if ($check->rowCount() > 0) {
            return false;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO categories (name, owner_role, is_active) 
         VALUES (?, 'admin', 1)"
        );
        return $stmt->execute([$name]);
    }


    public function updateAdmin($id, $name)
    {
        $check = $this->db->prepare(
            "SELECT id FROM categories 
         WHERE name = ?
           AND owner_role = 'admin'
           AND is_active = 1
           AND id != ?"
        );
        $check->execute([$name, $id]);

        if ($check->rowCount() > 0) {
            return false;
        }

        $stmt = $this->db->prepare(
            "UPDATE categories 
         SET name = ?
         WHERE id = ? AND owner_role = 'admin'"
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
        WHERE is_active = 1
          AND (
                owner_role = 'admin'
             OR (owner_role = 'seller' AND created_by = :seller_id)
          )
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


    public function storeSeller($name, $sellerId)
    {
        // 1️⃣ Cek duplikat dengan kategori ADMIN (aktif)
        $checkAdmin = $this->db->prepare(
            "SELECT id FROM categories
         WHERE name = :name
           AND owner_role = 'admin'
           AND is_active = 1"
        );
        $checkAdmin->execute([
            'name' => $name
        ]);

        if ($checkAdmin->fetch()) {
            return false; // bentrok kategori admin
        }

        // 2️⃣ Cek duplikat dengan kategori SELLER sendiri (aktif)
        $checkSeller = $this->db->prepare(
            "SELECT id FROM categories
         WHERE name = :name
           AND owner_role = 'seller'
           AND created_by = :seller_id
           AND is_active = 1"
        );
        $checkSeller->execute([
            'name' => $name,
            'seller_id' => $sellerId
        ]);

        if ($checkSeller->fetch()) {
            return false;
        }

        // 3️⃣ Insert kategori seller
        $stmt = $this->db->prepare(
            "INSERT INTO categories (name, created_by, owner_role, is_active)
         VALUES (:name, :created_by, 'seller', 1)"
        );

        return $stmt->execute([
            'name' => $name,
            'created_by' => $sellerId
        ]);
    }



    public function updateSeller($id, $name, $sellerId)
    {
        // 1️⃣ Cek bentrok dengan ADMIN
        $checkAdmin = $this->db->prepare(
            "SELECT id FROM categories
         WHERE name = :name
           AND owner_role = 'admin'
           AND is_active = 1"
        );
        $checkAdmin->execute([
            'name' => $name
        ]);

        if ($checkAdmin->fetch()) {
            return false;
        }

        // 2️⃣ Cek duplikat dengan kategori seller sendiri
        $checkSeller = $this->db->prepare(
            "SELECT id FROM categories
         WHERE name = :name
           AND owner_role = 'seller'
           AND created_by = :seller_id
           AND is_active = 1
           AND id != :id"
        );
        $checkSeller->execute([
            'name' => $name,
            'seller_id' => $sellerId,
            'id' => $id
        ]);

        if ($checkSeller->fetch()) {
            return false;
        }

        // 3️⃣ Update
        $stmt = $this->db->prepare(
            "UPDATE categories
         SET name = :name
         WHERE id = :id
           AND created_by = :seller_id
           AND owner_role = 'seller'"
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
            "UPDATE categories
         SET is_active = 0
         WHERE id = :id
           AND created_by = :seller_id
           AND owner_role = 'seller'"
        );

        return $stmt->execute([
            'id' => $id,
            'seller_id' => $sellerId
        ]);
    }

    public function getActiveCategoriesForCustomer()
{
    $stmt = $this->db->prepare("
        SELECT DISTINCT c.*
        FROM categories c
        WHERE c.is_active = 1
        ORDER BY c.name ASC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
