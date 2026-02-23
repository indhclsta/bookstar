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

        $noRekening = trim($data['no_rekening'] ?? '');
        $noRekening = $noRekening === '' ? null : $noRekening;

        return $stmt->execute([
            $data['role_id'],
            $data['name'],
            $data['email'],
            $data['no_tlp'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['nik'],
            $data['address'],
            $noRekening,
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
        $stmt = $this->db->prepare("
            SELECT COUNT(*) 
            FROM orders 
            WHERE customer_id = ? 
            AND order_status IN ('pending','paid')
        ");
        $stmt->execute([$id]);

        if ($stmt->fetchColumn() > 0) {
            return [
                'can_delete' => false,
                'reason' => 'Customer masih memiliki transaksi aktif'
            ];
        }

        return [
            'can_delete' => true,
            'reason' => ''
        ];
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
        WHERE u.role_id = 2 AND u.is_deleted = 0
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
        // ✅ Normalisasi no_rekening (kosong → NULL)
        $noRekening = trim($data['no_rekening'] ?? '');
        $noRekening = $noRekening === '' ? null : $noRekening;

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
            ':no_rekening' => $noRekening,      // 🔥 ini kuncinya
            ':qris_image'  => $data['qris_image'] ?? null,
            ':photo'       => $data['photo'] ?? null
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
        $stmt = $this->db->prepare("
        SELECT COUNT(*) 
        FROM products 
        WHERE seller_id = :seller_id
        AND is_active = 1
    ");

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

    public function softDeleteSeller($id)
    {
        $stmt = $this->db->prepare("
        UPDATE users 
        SET is_deleted = 1 
        WHERE id = ? 
        AND role_id = 2
    ");
        return $stmt->execute([$id]);
    }
    /* =======================
       UPDATE PROFILE (UMUM)
    ======================== */

    public function updateProfile($id, $data)
    {
        $fields = [];

        /* ================= BASIC ================= */
        if (isset($data['name'])) {
            $fields['name'] = $data['name'];
        }

        if (isset($data['email'])) {
            $fields['email'] = $data['email'];
        }

        /* ================= OPTIONAL ================= */
        if (!empty($data['nik'])) {
            $fields['nik'] = $data['nik'];
        }

        if (!empty($data['address'])) {
            $fields['address'] = $data['address'];
        }

        if (!empty($data['no_tlp'])) {
            if ($this->phoneExists($data['no_tlp'], $id)) {
                $_SESSION['error'] = 'Nomor HP sudah digunakan';
                return false;
            }
            $fields['no_tlp'] = $data['no_tlp'];
        }

        if (!empty($data['no_rekening'])) {
            $fields['no_rekening'] = $data['no_rekening'];
        }

        if (!empty($data['qris_image'])) {
            $fields['qris_image'] = $data['qris_image'];
        }

        if (!empty($data['password'])) {
            $fields['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (!empty($data['photo'])) {
            $fields['photo'] = $data['photo'];
        }

        /* ================= SAFETY ================= */
        if (empty($fields)) {
            return false;
        }

        $set = implode(', ', array_map(
            fn($k) => "$k = :$k",
            array_keys($fields)
        ));

        $stmt = $this->db->prepare("UPDATE users SET $set WHERE id = :id");

        foreach ($fields as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getUserPhoto($userId)
    {
        $stmt = $this->db->prepare("
            SELECT photo 
            FROM users 
            WHERE id = :id
        ");
        $stmt->execute([':id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['photo'] ?? null;
    }

    // Ambil semua data user berdasarkan ID
    public function getUserById($userId)
    {
        $stmt = $this->db->prepare("
            SELECT * 
            FROM users 
            WHERE id = :id
        ");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTodaySales($sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT COALESCE(SUM(total_price),0) 
        FROM orders
        WHERE seller_id = ?
        AND DATE(created_at) = CURDATE()
        AND approval_status = 'approved'
    ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchColumn();
    }

    public function getOrderStatusStats($sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT approval_status, COUNT(*) as total
        FROM orders
        WHERE seller_id = ?
        GROUP BY approval_status
    ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function getMonthlySales($sellerId)
    {
        $stmt = $this->db->prepare("
        SELECT MONTH(created_at) as bulan, SUM(total_price) as total
        FROM orders
        WHERE seller_id = ?
        AND approval_status = 'approved'
        AND YEAR(created_at) = YEAR(CURDATE())
        GROUP BY MONTH(created_at)
        ORDER BY bulan
    ");
        $stmt->execute([$sellerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCustomerDashboardStats($customerId)
    {
        // total pesanan
        $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM orders 
        WHERE customer_id = ?
    ");
        $stmt->execute([$customerId]);
        $totalOrders = $stmt->fetchColumn();

        // pesanan pending
        $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM orders 
        WHERE customer_id = ? AND approval_status = 'pending'
    ");
        $stmt->execute([$customerId]);
        $pendingOrders = $stmt->fetchColumn();

        // pesanan selesai (approved)
        $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM orders 
        WHERE customer_id = ? AND approval_status = 'approved'
    ");
        $stmt->execute([$customerId]);
        $completedOrders = $stmt->fetchColumn();

        // total item di cart
        $stmt = $this->db->prepare("
        SELECT COALESCE(SUM(quantity),0)
        FROM carts
        WHERE user_id = ?
    ");
        $stmt->execute([$customerId]);
        $cartItems = $stmt->fetchColumn();

        return [
            'total_orders'     => $totalOrders,
            'pending_orders'   => $pendingOrders,
            'completed_orders' => $completedOrders,
            'cart_items'       => $cartItems
        ];
    }
    /* ===========================
   DASHBOARD ADMIN
=========================== */

    public function getDashboardStats()
    {
        $stats = [];

        // Total Users (semua role non-deleted)
        $stmt = $this->db->prepare("SELECT COUNT(*) AS totalUsers FROM users WHERE is_deleted = 0");
        $stmt->execute();
        $stats['totalUsers'] = $stmt->fetch(PDO::FETCH_ASSOC)['totalUsers'];

        // Total Sellers
        $stmt = $this->db->prepare("SELECT COUNT(*) AS totalSellers FROM users WHERE role_id = 2 AND is_deleted = 0");
        $stmt->execute();
        $stats['totalSellers'] = $stmt->fetch(PDO::FETCH_ASSOC)['totalSellers'];

        // Total Customers
        $stmt = $this->db->prepare("SELECT COUNT(*) AS totalCustomers FROM users WHERE role_id = 3 AND is_deleted = 0");
        $stmt->execute();
        $stats['totalCustomers'] = $stmt->fetch(PDO::FETCH_ASSOC)['totalCustomers'];

        // Total Orders
        $stmt = $this->db->prepare("SELECT COUNT(*) AS totalOrders FROM orders");
        $stmt->execute();
        $stats['totalOrders'] = $stmt->fetch(PDO::FETCH_ASSOC)['totalOrders'];

        // Total Products
        $stmt = $this->db->prepare("SELECT COUNT(*) AS totalProducts FROM products WHERE is_active = 1");
        $stmt->execute();
        $stats['totalProducts'] = $stmt->fetch(PDO::FETCH_ASSOC)['totalProducts'];

        // Total Revenue
        $stmt = $this->db->prepare("
        SELECT IFNULL(SUM(total_price), 0) AS totalRevenue
        FROM orders
        WHERE approval_status = 'approved' AND order_status IN ('paid','shipped')
    ");
        $stmt->execute();
        $stats['totalRevenue'] = $stmt->fetch(PDO::FETCH_ASSOC)['totalRevenue'];

        return $stats;
    }


    public function getRecentOrders($limit = 5)
    {
        $stmt = $this->db->prepare("
        SELECT o.*, u.name as customer
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        ORDER BY o.created_at DESC
        LIMIT ?
    ");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingOrders($limit = 5)
    {
        $stmt = $this->db->prepare("
        SELECT o.*, u.name as customer
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        WHERE o.approval_status = 'pending'
        ORDER BY o.created_at DESC
        LIMIT ?
    ");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrdersPerMonth()
    {
        $stmt = $this->db->prepare("
        SELECT DATE_FORMAT(created_at, '%b %Y') as month, COUNT(*) as total
        FROM orders
        GROUP BY YEAR(created_at), MONTH(created_at)
        ORDER BY created_at ASC
    ");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($data as $row) {
            $result[$row['month']] = (int)$row['total'];
        }

        return $result;
    }

    public function getUserStats()
    {
        $stmt = $this->db->prepare("
        SELECT role_id, COUNT(*) as total
        FROM users
        WHERE is_deleted = 0
        GROUP BY role_id
    ");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [
            'Admin' => 0,
            'Seller' => 0,
            'Customer' => 0
        ];

        foreach ($data as $row) {
            if ($row['role_id'] == 1) $result['Admin'] = (int)$row['total'];
            if ($row['role_id'] == 2) $result['Seller'] = (int)$row['total'];
            if ($row['role_id'] == 3) $result['Customer'] = (int)$row['total'];
        }

        return $result;
    }
}
