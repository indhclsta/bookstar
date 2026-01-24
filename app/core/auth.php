<?php

require_once APP_PATH . '/models/UserModel.php';

class Auth
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/?c=auth&m=login');
            exit;
        }

        // 🔥 UPDATE LAST ACTIVITY SETIAP REQUEST
        $userId = $_SESSION['user']['id'];
        $userModel = new UserModel();
        $userModel->updateLastActivity($userId);
    }

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

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }
}
