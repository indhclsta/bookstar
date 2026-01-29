<?php

require_once APP_PATH . '/models/Database.php';

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /* =======================
       AUTH & USER BASIC
    ======================== */

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.role_name
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE u.email = ?
        ");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.role_name
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE u.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByNik($nik)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE nik = ?");
        $stmt->execute([$nik]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByRekening($no_rekening)
    {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE no_rekening = ?");
        $stmt->execute([$no_rekening]);
        return $stmt->fetch();
    }


    public function updateLastActivity($userId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE users SET last_activity = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
    }



    /* ===== REGISTER (UMUM) ===== */
    public function create($data)
    {
        if ($this->phoneExists($data['no_tlp'])) {
            $_SESSION['error'] = 'Nomor HP sudah digunakan';
            return false;
        }

        $stmt = $this->db->prepare("
            INSERT INTO users 
        (role_id, name, email, no_tlp, password, nik, address, no_rekening, qris_image)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['role_id'],
            $data['name'],
            $data['email'],
            $data['no_tlp'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['nik'],
            $data['address'],
            $data['no_rekening'] ?? null,
            $data['qris_image'] ?? null
        ]);
    }

    /* =======================
       RESET PASSWORD
    ======================== */

    public function saveResetToken($email, $token, $expired)
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET reset_token = ?, reset_expired = ?
            WHERE email = ?
        ");
        return $stmt->execute([$token, $expired, $email]);
    }

    public function findByResetToken($token)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users
            WHERE reset_token = ?
            AND reset_expired > NOW()
        ");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($id, $password)
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET password = ?, reset_token = NULL, reset_expired = NULL
            WHERE id = ?
        ");
        return $stmt->execute([
            password_hash($password, PASSWORD_DEFAULT),
            $id
        ]);
    }

    /* =======================
       ONLINE / OFFLINE STATUS
    ======================== */

    public function setOnline($id)
    {
        $stmt = $this->db->prepare("UPDATE users SET is_online = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function setOffline($id)
    {
        $stmt = $this->db->prepare("UPDATE users SET is_online = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /* =======================
       CUSTOMER
    ======================== */

    public function getAllCustomer()
    {
        $stmt = $this->db->prepare("
        SELECT id, name, email, no_tlp, nik, address, photo, is_online, last_activity
        FROM users
        WHERE role_id = 3 AND is_deleted = 0
        ORDER BY id DESC
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function updateCustomer($data)
    {
        $id = $data['id'];

        // Cek email duplikat
        if ($this->emailExists($data['email'], $id)) {
            $_SESSION['error'] = 'Email sudah digunakan';
            return false;
        }

        // Cek nomor HP duplikat
        if ($this->phoneExists($data['no_tlp'], $id)) {
            $_SESSION['error'] = 'Nomor HP sudah digunakan';
            return false;
        }

        // Cek NIK duplikat
        if (!empty($data['nik'])) {
            $stmt = $this->db->prepare("
            SELECT id FROM users 
            WHERE nik = :nik AND id != :id
            LIMIT 1
        ");
            $stmt->execute([
                ':nik' => $data['nik'],
                ':id'  => $id
            ]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'NIK sudah digunakan';
                return false;
            }
        }

        $sql = "
        UPDATE users SET
            name = :name,
            email = :email,
            no_tlp = :no_tlp,
            nik = :nik,
            address = :address,
            photo = :photo
        WHERE id = :id AND role_id = 3
    ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'    => $data['name'],
            ':email'   => $data['email'],
            ':no_tlp'  => $data['no_tlp'],
            ':nik'     => $data['nik'],
            ':address' => $data['address'],
            ':photo'   => $data['photo'],
            ':id'      => $data['id']
        ]);
    }



    public function customerHasCartProducts($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM carts WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() > 0;
    }

    public function customerHasOrders($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders WHERE customer_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() > 0;
    }


    public function deleteCustomerIfOffline($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM users
            WHERE id = ? AND role_id = 3 AND is_online = 0
        ");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    // Cek apakah customer bisa dihapus
    public function canDeleteCustomer($id)
    {
        // Ambil status online
        $stmt = $this->db->prepare("SELECT is_online FROM users WHERE id = ? AND role_id = 3 AND is_deleted = 0");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) return ['can_delete' => false, 'reason' => 'Customer tidak ditemukan'];
        if ($user['is_online']) return ['can_delete' => false, 'reason' => 'Customer sedang online'];

        // Cek keranjang
        $stmt = $this->db->prepare("SELECT COUNT(*) as cart_count FROM carts WHERE user_id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) return ['can_delete' => false, 'reason' => 'Customer masih memiliki produk di keranjang'];

        // Cek transaksi pending
        $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM orders WHERE customer_id = ? AND status = 'pending'");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) return ['can_delete' => false, 'reason' => 'Customer masih memiliki transaksi pending'];

        return ['can_delete' => true, 'reason' => ''];
    }


    public function softDeleteCustomer($id)
    {
        // Cek apakah customer masih punya produk di keranjang
        if ($this->customerHasCartProducts($id)) {
            return false; // Tidak bisa dihapus
        }

        // Lakukan soft delete
        $stmt = $this->db->prepare("
        UPDATE users
        SET is_deleted = 1
        WHERE id = ? AND role_id = 3
    ");
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }



    /* =======================
       SELLER
    ======================== */

    public function getAllSeller($excludeId = null)
    {
        $sql = "
        SELECT u.*, 
               (SELECT COUNT(*) 
                FROM products p 
                WHERE p.seller_id = u.id) AS product_count
        FROM users u
        WHERE u.role_id = 2
    ";

        if ($excludeId) {
            $sql .= " AND u.id != :id";
        }

        $sql .= " ORDER BY u.id ASC";

        $stmt = $this->db->prepare($sql);
        if ($excludeId) {
            $stmt->execute([':id' => $excludeId]);
        } else {
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function createSeller($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO users 
            (name, email, no_tlp, password, role_id, nik, address, no_rekening, qris_image, photo, is_online, created_at)
            VALUES
            (:name, :email, :no_tlp, :password, 2, :nik, :address, :no_rekening, :qris_image, :photo, 0, NOW())
        ");

        return $stmt->execute([
            ':name'        => $data['name'],
            ':email'       => $data['email'],
            ':no_tlp'      => $data['no_tlp'],
            ':password'    => $data['password'],
            ':nik'         => $data['nik'],
            ':address'     => $data['address'],
            ':no_rekening' => $data['no_rekening'],
            ':qris_image'  => $data['qris_image'],
            ':photo'       => $data['photo']
        ]);
    }

    public function emailExists($email, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->db->prepare("
                SELECT id FROM users 
                WHERE email = :email AND id != :id
                LIMIT 1
            ");
            $stmt->execute([
                ':email' => $email,
                ':id'    => $excludeId
            ]);
        } else {
            $stmt = $this->db->prepare("
                SELECT id FROM users 
                WHERE email = :email
                LIMIT 1
            ");
            $stmt->execute([
                ':email' => $email
            ]);
        }

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public function phoneExists($no_tlp, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->db->prepare("
            SELECT id FROM users 
            WHERE no_tlp = :no_tlp AND id != :id
            LIMIT 1
        ");
            $stmt->execute([
                ':no_tlp' => $no_tlp,
                ':id'     => $excludeId
            ]);
        } else {
            $stmt = $this->db->prepare("
            SELECT id FROM users 
            WHERE no_tlp = :no_tlp
            LIMIT 1
        ");
            $stmt->execute([
                ':no_tlp' => $no_tlp
            ]);
        }

        return $stmt->fetch() ? true : false;
    }


    public function updateSeller($data)
    {
        $id = $data['id'];

        // Cek email duplikat
        if ($this->emailExists($data['email'], $id)) {
            $_SESSION['error'] = 'Email sudah digunakan';
            return false;
        }

        // Cek nomor HP duplikat
        if ($this->phoneExists($data['no_tlp'], $id)) {
            $_SESSION['error'] = 'Nomor HP sudah digunakan';
            return false;
        }

        // Cek NIK duplikat
        if (!empty($data['nik'])) {
            $stmt = $this->db->prepare("
            SELECT id FROM users 
            WHERE nik = :nik AND id != :id
            LIMIT 1
        ");
            $stmt->execute([
                ':nik' => $data['nik'],
                ':id'  => $id
            ]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'NIK sudah digunakan';
                return false;
            }
        }

        // Cek no_rekening duplikat (opsional, kalau ada)
        if (!empty($data['no_rekening'])) {
            $stmt = $this->db->prepare("
            SELECT id FROM users 
            WHERE no_rekening = :no_rekening AND id != :id
            LIMIT 1
        ");
            $stmt->execute([
                ':no_rekening' => $data['no_rekening'],
                ':id'          => $id
            ]);
            if ($stmt->fetch()) {
                $_SESSION['error'] = 'Nomor rekening sudah digunakan';
                return false;
            }
        }

        // Siapkan data untuk update
        $fields = [
            'name'        => $data['name'],
            'email'       => $data['email'],
            'no_tlp'      => $data['no_tlp'],
            'nik'         => $data['nik'],
            'address'     => $data['address'],
            'no_rekening' => $data['no_rekening'] ?? null,
            'qris_image'  => $data['qris_image'] ?? null,
            'photo'       => $data['photo'] ?? null
        ];

        // Jika password diisi
        if (!empty($data['password'])) {
            $fields['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));
        $sql = "UPDATE users SET $set WHERE id = :id AND role_id = 2";
        $stmt = $this->db->prepare($sql);

        foreach ($fields as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }



    public function sellerHasProducts($sellerId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM products WHERE seller_id = :seller_id"
        );
        $stmt->execute([
            'seller_id' => $sellerId
        ]);

        return $stmt->fetchColumn() > 0;
    }

    public function getAllSellerWithProductCount()
    {
        $sql = "
        SELECT u.*, 
               (SELECT COUNT(*) 
                FROM products p 
                WHERE p.seller_id = u.id) AS total_produk
        FROM users u
        WHERE u.role_id = 2
        ORDER BY u.id ASC
    ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function deleteSellerIfOffline($id)
    {
        $stmt = $this->db->prepare("
            DELETE FROM users
            WHERE id = ? AND role_id = 2 AND is_online = 0
        ");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    /* =======================
       UPDATE PROFILE (UMUM)
    ======================== */

    public function updateProfile($id, $data)
    {
        $fields = [
            'name'        => $data['name'],
            'email'       => $data['email'],
            'no_tlp'      => $data['no_tlp'],
            'nik'         => $data['nik'],
            'address'     => $data['address'],
            'no_rekening' => $data['no_rekening'] ?? null,
            'qris_image'  => $data['qris_image'] ?? null
        ];

        if (!empty($data['password'])) {
            $fields['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (!empty($data['photo'])) {
            $fields['photo'] = $data['photo'];
        }

        if (!empty($data['no_tlp'])) {
            if ($this->phoneExists($data['no_tlp'], $id)) {
                $_SESSION['error'] = 'Nomor HP sudah digunakan';
                return false;
            }
            $fields['no_tlp'] = $data['no_tlp'];
        }

        $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));
        $stmt = $this->db->prepare("UPDATE users SET $set WHERE id = :id");

        foreach ($fields as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }
}
