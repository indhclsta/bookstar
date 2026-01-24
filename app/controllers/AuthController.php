<?php

require_once '../app/models/UserModel.php';

class AuthController
{
    /* =======================
       LOGIN
    ======================== */

    public function login()
    {
        require '../app/views/auth/login.php';
    }

    public function loginProcess()
    {
        $email    = $_POST['email'];
        $password = $_POST['password'];

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            die('Email tidak ditemukan');
        }

        if (!password_verify($password, $user['password'])) {
            die('Password salah');
        }

        $_SESSION['user'] = [
            'id'           => $user['id'],
            'name'         => $user['name'],
            'email'        => $user['email'],
            'role_id'      => $user['role_id'],
            'role_name'    => $user['role_name'],
            'photo'        => $user['photo'],
            'nik'          => $user['nik'],
            'no_rekening'  => $user['no_rekening'] ?? null
        ];

        switch ($user['role_name']) {
            case 'admin':
                header('Location: ' . BASE_URL . '/?c=admin&m=dashboard');
                break;
            case 'seller':
                header('Location: ' . BASE_URL . '/?c=seller&m=dashboard');
                break;
            case 'customer':
                header('Location: ' . BASE_URL . '/?c=customer&m=dashboard');
                break;
        }
        exit;
    }

    /* =======================
       REGISTER
    ======================== */

    public function register()
    {
        require '../app/views/auth/register.php';
    }

    public function registerProcess()
    {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            die('Password dan Confirm Password tidak sama');
        }

        if (strlen($_POST['password']) < 6) {
            die('Password minimal 6 karakter');
        }

        $qrisFileName = null;

        // 🔥 HANDLE UPLOAD QRIS (SELLER)
        if ((int)$_POST['role_id'] === 2 && isset($_FILES['qris_image'])) {

            if ($_FILES['qris_image']['error'] === 0) {
                $ext = pathinfo($_FILES['qris_image']['name'], PATHINFO_EXTENSION);
                $allowed = ['jpg', 'jpeg', 'png'];

                if (!in_array(strtolower($ext), $allowed)) {
                    die('Format QRIS harus JPG / PNG');
                }

                $qrisFileName = 'qris_' . time() . '.' . $ext;
                $path = APP_PATH . '/../public/uploads/qris/' . $qrisFileName;

                move_uploaded_file($_FILES['qris_image']['tmp_name'], $path);
            } else {
                die('QRIS wajib diupload untuk seller');
            }
        }

        $data = [
            'role_id'     => (int) $_POST['role_id'],
            'name'        => trim($_POST['name']),
            'email'       => trim($_POST['email']),
            'password'    => $_POST['password'],
            'nik'         => trim($_POST['nik']),
            'address'     => trim($_POST['address']),
            'no_rekening' => $_POST['no_rekening'] ?? null,
            'qris_image'  => $qrisFileName
        ];

        $userModel = new UserModel();

        if (!in_array($data['role_id'], [2, 3])) {
            die('Role tidak valid');
        }

        if ($data['role_id'] === 2 && empty($data['no_rekening'])) {
            die('No rekening wajib untuk seller');
        }

        if ($userModel->findByEmail($data['email'])) {
            die('Email sudah terdaftar');
        }

        if (!empty($data['nik']) && $userModel->findByNik($data['nik'])) {
            die('NIK sudah terdaftar');
        }

        $userModel->create($data);

        header('Location: ' . BASE_URL . '/?c=auth&m=login');
        exit;
    }

    /* =======================
       FORGOT PASSWORD
    ======================== */

    public function forgot()
    {
        require '../app/views/auth/forgot-password.php';
    }

    public function sendResetLink()
    {
        $email = $_POST['email'];

        $userModel = new UserModel();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            die('Email tidak ditemukan');
        }

        $token   = bin2hex(random_bytes(32));
        $expired = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $userModel->saveResetToken($email, $token, $expired);

        $link = BASE_URL . "/?c=auth&m=resetPassword&token=$token";

        require_once '../app/helpers/Mailer.php';

        $body = "
            <h3>Reset Password</h3>
            <p>Klik link berikut untuk reset password:</p>
            <a href='$link'>$link</a>
            <p>Link berlaku 1 jam.</p>
        ";

        Mailer::send($email, 'Reset Password BookStar', $body);

        echo "Link reset password telah dikirim ke email Anda.";
    }

    public function resetPassword()
    {
        $token = $_GET['token'];

        $userModel = new UserModel();
        $user = $userModel->findByResetToken($token);

        if (!$user) {
            die('Token tidak valid atau kadaluarsa');
        }

        require '../app/views/auth/reset-password.php';
    }

    public function resetPasswordProcess()
    {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            die('Password tidak sama');
        }

        $token = $_POST['token'];

        $userModel = new UserModel();
        $user = $userModel->findByResetToken($token);

        if (!$user) {
            die('Token tidak valid');
        }

        $userModel->updatePassword($user['id'], $_POST['password']);

        header('Location: ' . BASE_URL . '/?c=auth&m=login');
        exit;
    }

    /* =======================
       LOGOUT
    ======================== */

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/?c=auth&m=login');
        exit;
    }
}
