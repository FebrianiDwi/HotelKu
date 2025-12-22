<?php

class UserModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Ambil user berdasarkan email (untuk login dengan password_verify)
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

    // Ambil user berdasarkan ID
    public function findById($id)
    {
        $idEsc = (int) $id;
        $sql   = "
            SELECT id, first_name, last_name, email, phone, status, role, join_date
            FROM users
            WHERE id = $idEsc
            LIMIT 1
        ";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    // Simpan user baru dengan password yang di-hash
    public function createUser($firstName, $lastName, $email, $passwordPlain, $phone)
    {
        $firstNameEsc = mysqli_real_escape_string($this->conn, $firstName);
        $lastNameEsc  = mysqli_real_escape_string($this->conn, $lastName);
        $emailEsc     = mysqli_real_escape_string($this->conn, $email);
        $phoneEsc     = mysqli_real_escape_string($this->conn, $phone);

        $passwordHash = password_hash($passwordPlain, PASSWORD_BCRYPT);
        $passwordEsc  = mysqli_real_escape_string($this->conn, $passwordHash);

        $sql = "
            INSERT INTO users (first_name, last_name, email, password, phone, status, role, join_date)
            VALUES ('$firstNameEsc', '$lastNameEsc', '$emailEsc', '$passwordEsc', '$phoneEsc', 'active', 'user', CURDATE())
        ";

        return mysqli_query($this->conn, $sql);
    }
}


