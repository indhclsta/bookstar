<?php

require_once '../app/models/UserModel.php';
require_once APP_PATH . '/helpers/Flash.php';

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

        Flash::success('Login berhasil, selamat datang ' . $user['name']);


        $_SESSION['user'] = [
            'id'        => $user['id'],
            'name'      => $user['name'],
            'email'     => $user['email'],
            'role_id'   => $user['role_id'],
            'role_name' => $user['role_name']
        ];

        $_SESSION['success'] = 'Login berhasil, selamat datang ' . $user['name'];

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
        // PASSWORD MATCH
        if ($_POST['password'] !== $_POST['confirm_password']) {
            $_SESSION['error'] = 'Password dan konfirmasi tidak sama';
            header('Location: ' . BASE_URL . '/?c=auth&m=register');
            exit;
        }

        // PASSWORD LENGTH
        if (strlen($_POST['password']) < 6) {
            $_SESSION['error'] = 'Password minimal 6 karakter';
            header('Location: ' . BASE_URL . '/?c=auth&m=register');
            exit;
        }

        $qrisFileName = null;

        // 🔥 HANDLE UPLOAD QRIS (SELLER)
        if ((int)$_POST['role_id'] === 2) {

            if (!isset($_FILES['qris_image']) || $_FILES['qris_image']['error'] !== 0) {
                $_SESSION['error'] = 'QRIS wajib diupload untuk seller';
                header('Location: ' . BASE_URL . '/?c=auth&m=register');
                exit;
            }

            $ext = pathinfo($_FILES['qris_image']['name'], PATHINFO_EXTENSION);
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array(strtolower($ext), $allowed)) {
                $_SESSION['error'] = 'Format QRIS harus JPG atau PNG';
                header('Location: ' . BASE_URL . '/?c=auth&m=register');
                exit;
            }

            $qrisFileName = 'qris_' . time() . '.' . $ext;
            $path = APP_PATH . '/../public/uploads/qris/' . $qrisFileName;

            move_uploaded_file($_FILES['qris_image']['tmp_name'], $path);
        }

        $data = [
            'role_id'     => (int) $_POST['role_id'],
            'name'        => trim($_POST['name']),
            'email'       => trim($_POST['email']),
            'no_tlp'      => trim($_POST['no_tlp']),
            'password'    => $_POST['password'],
            'nik'         => trim($_POST['nik']),
            'address'     => trim($_POST['address']),
            'no_rekening' => $_POST['no_rekening'] ?? null,
            'qris_image'  => $qrisFileName
        ];

        $userModel = new UserModel();

        // ROLE VALID
        if (!in_array($data['role_id'], [2, 3])) {
            $_SESSION['error'] = 'Role tidak valid';
            header('Location: ' . BASE_URL . '/?c=auth&m=register');
            exit;
        }

        // SELLER WAJIB REKENING
        if ($data['role_id'] === 2 && empty($data['no_rekening'])) {
            $_SESSION['error'] = 'Nomor rekening wajib diisi untuk seller';
            header('Location: ' . BASE_URL . '/?c=auth&m=register');
            exit;
        }

        // EMAIL DUPLIKAT
        if ($userModel->findByEmail($data['email'])) {
            $_SESSION['error'] = 'Email sudah terdaftar';
            header('Location: ' . BASE_URL . '/?c=auth&m=register');
            exit;
        }

        // NO HP WAJIB
        if (empty($data['no_tlp'])) {
            $_SESSION['error'] = 'Nomor telepon wajib diisi';
            header('Location: ' . BASE_URL . '/?c=auth&m=register');
            exit;
        }

        // NIK DUPLIKAT
        if (!empty($data['nik']) && $userModel->findByNik($data['nik'])) {
            $_SESSION['error'] = 'NIK sudah terdaftar';
            header('Location: ' . BASE_URL . '/?c=auth&m=register');
            exit;
        }

        // CREATE USER
        if (!$userModel->create($data)) {
            $_SESSION['error'] = 'Registrasi gagal, silakan coba lagi';
            header('Location: ' . BASE_URL . '/?c=auth&m=register');
            exit;
        }

        // SUCCESS
        $_SESSION['success'] = 'Registrasi berhasil, silakan login';
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
        session_start();

        Flash::success('Logout berhasil 👋');

        header('Location: ' . BASE_URL . '/?c=auth&m=login');
        exit;
    }
}
