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

    public function updateLastActivity($userId)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE users SET last_activity = NOW() WHERE id = ?");
        $stmt->execute([$userId]);
    }



    /* ===== REGISTER (UMUM) ===== */
    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO users 
            (role_id, name, email, password, nik, address, no_rekening, qris_image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['role_id'],
            $data['name'],
            $data['email'],
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
        SELECT id, name, email, nik, address, photo, is_online, last_activity
        FROM users
        WHERE role_id = 3
        ORDER BY id DESC
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateCustomer($data)
    {
        $sql = "
            UPDATE users SET
                name = :name,
                email = :email,
                nik = :nik,
                address = :address,
                photo = :photo
            WHERE id = :id AND role_id = 3
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'    => $data['name'],
            ':email'   => $data['email'],
            ':nik'     => $data['nik'],
            ':address' => $data['address'],
            ':photo'   => $data['photo'],
            ':id'      => $data['id']
        ]);
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

    /* =======================
       SELLER
    ======================== */

    public function getAllSeller($excludeId = null)
    {
        $sql = "
            SELECT id, name, email, nik, address, no_rekening, qris_image, photo, is_online, last_activity
            FROM users
            WHERE role_id = 2
        ";

        if ($excludeId) $sql .= " AND id != :id";
        $sql .= " ORDER BY id ASC";

        $stmt = $this->db->prepare($sql);
        if ($excludeId) $stmt->execute([':id' => $excludeId]);
        else $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createSeller($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO users 
            (name, email, password, role_id, nik, address, no_rekening, qris_image, photo, is_online, created_at)
            VALUES
            (:name, :email, :password, 2, :nik, :address, :no_rekening, :qris_image, :photo, 0, NOW())
        ");

        return $stmt->execute([
            ':name'        => $data['name'],
            ':email'       => $data['email'],
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

    public function updateSeller($data)
    {
        $sql = "
            UPDATE users SET
                name = :name,
                email = :email,
                nik = :nik,
                address = :address,
                no_rekening = :no_rekening,
                qris_image = :qris_image,
                photo = :photo
            WHERE id = :id AND role_id = 2
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'        => $data['name'],
            ':email'       => $data['email'],
            ':nik'         => $data['nik'],
            ':address'     => $data['address'],
            ':no_rekening' => $data['no_rekening'],
            ':qris_image'  => $data['qris_image'],
            ':photo'       => $data['photo'],
            ':id'          => $data['id']
        ]);
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

        $set = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));
        $stmt = $this->db->prepare("UPDATE users SET $set WHERE id = :id");

        foreach ($fields as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }
}
