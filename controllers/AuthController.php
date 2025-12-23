<?php

class AuthController
{
    private $conn;
    private $userModel;

    public function __construct($conn)
    {
        session_start();
        $this->conn      = $conn;
        require_once __DIR__ . '/../models/UserModel.php';
        $this->userModel = new UserModel($this->conn);
    }

    public function login()
    {
        $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';

        if ($email === '' || $password === '') {
            header('Location: ../views/login_register.php?error=empty');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
            $_SESSION['user_email'] = $user['email'];

            // Redirect ke halaman yang diminta atau profil
            if (isset($_SESSION['redirect_after_login'])) {
                $redirect = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header('Location: ../views/' . $redirect);
            } else {
                header('Location: ../views/profil.php');
            }
            exit;
        }

        header('Location: ../views/login_register.php?error=invalid');
        exit;
    }

    public function register()
    {
        $firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $lastName  = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $email     = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password  = isset($_POST['password']) ? trim($_POST['password']) : '';
        $confirm   = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';
        $phone     = isset($_POST['phone']) ? trim($_POST['phone']) : '';

        if ($firstName === '' || $lastName === '' || $email === '' || $password === '' || $confirm === '' || $phone === '') {
            header('Location: ../views/login_register.php?register_error=empty');
            exit;
        }

        if ($password !== $confirm) {
            header('Location: ../views/login_register.php?register_error=nomatch');
            exit;
        }

        if ($this->userModel->findByEmail($email)) {
            header('Location: ../views/login_register.php?register_error=exists');
            exit;
        }

        $createResult = $this->userModel->createUser($firstName, $lastName, $email, $password, $phone);
        
        if ($createResult) {
            $user = $this->userModel->findByEmail($email);
            if ($user) {
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
                $_SESSION['user_email'] = $user['email'];

                // Redirect ke halaman yang diminta atau profil
                if (isset($_SESSION['redirect_after_login'])) {
                    $redirect = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    header('Location: ../views/' . $redirect);
                } else {
                    header('Location: ../views/profil.php');
                }
                exit;
            } else {
                error_log('AuthController::register Error: User berhasil dibuat tapi tidak ditemukan setelah insert');
                header('Location: ../views/login_register.php?register_error=failed');
                exit;
            }
        }

        error_log('AuthController::register Error: Gagal membuat user baru');
        header('Location: ../views/login_register.php?register_error=failed');
        exit;
    }
}


