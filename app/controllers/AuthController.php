<?php

require_once '../app/models/UserModel.php';

class AuthController
{

    public function login()
    {
        require '../app/views/auth/login.php';
    }

    public function loginProcess()
    {
        $email = $_POST['email'];
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
            'id'        => $user['id'],
            'name'      => $user['name'],
            'email'     => $user['email'],
            'role_id'   => $user['role_id'],
            'role_name' => $user['role_name'],
            'photo' => $user['photo']
        ];  


        switch ($_SESSION['user']['role_name']) {
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

    public function register()
    {
        require '../app/views/auth/register.php';
    }

    public function registerProcess()
    {
        session_start();

        if ($_POST['password'] !== $_POST['confirm_password']) {
            die('Password dan Confirm Password tidak sama');
        }

        if (strlen($_POST['password']) < 6) {
            die('Password minimal 6 karakter');
        }

        $data = [
            'role_id'  => $_POST['role_id'],
            'name'     => $_POST['name'],
            'email'    => $_POST['email'],
            'password' => $_POST['password'],
            'nik'      => $_POST['nik'] ?? null,
            'address'  => $_POST['address']
        ];


        $userModel = new UserModel();

        // Role hanya seller & customer
        if (!in_array($data['role_id'], [2, 3])) {
            die('Role tidak valid');
        }

        // Cek email
        if ($userModel->findByEmail($data['email'])) {
            die('Email sudah terdaftar');
        }

        // Cek NIK
        if ($data['nik'] && $userModel->findByNik($data['nik'])) {
            die('NIK sudah terdaftar');
        }

        // Simpan user
        $userModel->create($data);

        header('Location: ' . BASE_URL . '/?c=auth&m=login');
        exit;
    }

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

        $token = bin2hex(random_bytes(32));
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
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $userModel = new UserModel();
        $user = $userModel->findByResetToken($token);

        if (!$user) {
            die('Token tidak valid');
        }

        $userModel->updatePassword($user['id'], $password);

        header('Location: ' . BASE_URL . '/?c=auth&m=login');
    }


    // Logout
    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/?c=auth&m=login');
        exit;
    }
}
