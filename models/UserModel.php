<?php

class UserModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }   

    public function findByEmail($email)
    {
        $emailEsc = mysqli_real_escape_string($this->conn, $email);

        $sql = "
            SELECT id, first_name, last_name, email, password
            FROM users
            WHERE email = '$emailEsc'
            LIMIT 1
        ";

        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }

    public function findById($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return null;
        }
        
        $idEsc = (int) $id;
        $sql   = "
            SELECT id, first_name, last_name, email, phone, status, role, join_date
            FROM users
            WHERE id = $idEsc
            LIMIT 1
        ";
        
        $result = mysqli_query($this->conn, $sql);
        
        if (!$result) {
            error_log('UserModel::findById Error: ' . mysqli_error($this->conn));
            return null;
        }
        
        if (mysqli_num_rows($result) === 1) {
            return mysqli_fetch_assoc($result);
        }
        
        return null;
    }

    public function createUser($firstName, $lastName, $email, $passwordPlain, $phone)
    {
        if (empty($firstName) || empty($lastName) || empty($email) || empty($passwordPlain) || empty($phone)) {
            error_log('UserModel::createUser Error: Semua field harus diisi');
            return false;
        }

        $firstNameEsc = mysqli_real_escape_string($this->conn, $firstName);
        $lastNameEsc  = mysqli_real_escape_string($this->conn, $lastName);
        $emailEsc     = mysqli_real_escape_string($this->conn, $email);
        $phoneEsc     = mysqli_real_escape_string($this->conn, $phone);

        $passwordHash = password_hash($passwordPlain, PASSWORD_BCRYPT);
        if (!$passwordHash) {
            error_log('UserModel::createUser Error: Gagal hash password');
            return false;
        }
        
        $passwordEsc  = mysqli_real_escape_string($this->conn, $passwordHash);

        $sql = "
            INSERT INTO users (first_name, last_name, email, password, phone, status, role, join_date)
            VALUES ('$firstNameEsc', '$lastNameEsc', '$emailEsc', '$passwordEsc', '$phoneEsc', 'active', 'user', CURDATE())
        ";

        $result = mysqli_query($this->conn, $sql);
        
        if (!$result) {
            $error = mysqli_error($this->conn);
            error_log('UserModel::createUser SQL Error: ' . $error);
            error_log('UserModel::createUser SQL Query: ' . $sql);
            return false;
        }

        return true;
    }
}


