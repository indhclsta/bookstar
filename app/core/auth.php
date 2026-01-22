<?php

class Auth
{
    // Cek apakah user sudah login
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/?c=auth&m=login');
            exit;
        }
    }

    // Cek role user
    public static function role($roleName)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/?c=auth&m=login');
            exit;
        }

        if ($_SESSION['user']['role_name'] !== $roleName) {
            http_response_code(403);
            die('Akses ditolak');
        }
    }

    // Ambil data user login
    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }
}
