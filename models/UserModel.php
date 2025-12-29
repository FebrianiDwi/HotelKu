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

        $sql = "SELECT id, first_name, last_name, email, password
                FROM users
                WHERE email = '$emailEsc'
                LIMIT 1";

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
        $sql = "SELECT id, first_name, last_name, email, phone, status, role, join_date
                FROM users
                WHERE id = $idEsc
                LIMIT 1";
        
        $result = mysqli_query($this->conn, $sql);
        
        if (!$result) {
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
            return false;
        }

        $firstNameEsc = mysqli_real_escape_string($this->conn, $firstName);
        $lastNameEsc = mysqli_real_escape_string($this->conn, $lastName);
        $emailEsc = mysqli_real_escape_string($this->conn, $email);
        $phoneEsc = mysqli_real_escape_string($this->conn, $phone);
        $passwordHash = password_hash($passwordPlain, PASSWORD_BCRYPT);
        
        if (!$passwordHash) {
            return false;
        }
        
        $passwordEsc = mysqli_real_escape_string($this->conn, $passwordHash);
        $sql = "INSERT INTO users (first_name, last_name, email, password, phone, status, role, join_date)
                VALUES ('$firstNameEsc', '$lastNameEsc', '$emailEsc', '$passwordEsc', '$phoneEsc', 'active', 'user', CURDATE())";
        $result = mysqli_query($this->conn, $sql);
        
        return $result ? true : false;
    }

    public function getAllUsers()
    {
        $sql = "SELECT id, first_name, last_name, email, phone, status, role, created_at FROM users ORDER BY created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        $users = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
        }
        return $users;
    }

    public function updateUser($id, $firstName, $lastName, $email, $phone, $status = null, $role = null, $password = null)
    {
        $idEsc = (int)$id;
        $firstNameEsc = mysqli_real_escape_string($this->conn, $firstName);
        $lastNameEsc = mysqli_real_escape_string($this->conn, $lastName);
        $emailEsc = mysqli_real_escape_string($this->conn, $email);
        $phoneEsc = mysqli_real_escape_string($this->conn, $phone);
        
        $updates = [];
        $updates[] = "first_name = '$firstNameEsc'";
        $updates[] = "last_name = '$lastNameEsc'";
        $updates[] = "email = '$emailEsc'";
        $updates[] = "phone = '$phoneEsc'";
        
        if ($status !== null) {
            $statusEsc = mysqli_real_escape_string($this->conn, $status);
            $updates[] = "status = '$statusEsc'";
        }
        
        if ($role !== null) {
            $roleEsc = mysqli_real_escape_string($this->conn, $role);
            $updates[] = "role = '$roleEsc'";
        }
        
        if ($password !== null && !empty($password)) {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
            $passwordEsc = mysqli_real_escape_string($this->conn, $passwordHash);
            $updates[] = "password = '$passwordEsc'";
        }
        
        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = $idEsc";
        
        if (mysqli_query($this->conn, $sql)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }

    public function deleteUser($id)
    {
        $idEsc = (int)$id;
        
        if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === $idEsc) {
            return ['success' => false, 'error' => 'Cannot delete your own account'];
        }
        
        $sql = "DELETE FROM users WHERE id = $idEsc";
        
        if (mysqli_query($this->conn, $sql)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }

    public function createUserByAdmin($firstName, $lastName, $email, $password, $phone, $role = 'user', $status = 'active')
    {
        $existing = $this->findByEmail($email);
        if ($existing) {
            return ['success' => false, 'error' => 'Email already exists'];
        }
        
        $firstNameEsc = mysqli_real_escape_string($this->conn, $firstName);
        $lastNameEsc = mysqli_real_escape_string($this->conn, $lastName);
        $emailEsc = mysqli_real_escape_string($this->conn, $email);
        $phoneEsc = mysqli_real_escape_string($this->conn, $phone);
        $roleEsc = mysqli_real_escape_string($this->conn, $role);
        $statusEsc = mysqli_real_escape_string($this->conn, $status);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $passwordEsc = mysqli_real_escape_string($this->conn, $passwordHash);
        
        $sql = "INSERT INTO users (first_name, last_name, email, password, phone, status, role, join_date) 
                VALUES ('$firstNameEsc', '$lastNameEsc', '$emailEsc', '$passwordEsc', '$phoneEsc', '$statusEsc', '$roleEsc', CURDATE())";
        
        if (mysqli_query($this->conn, $sql)) {
            return [
                'success' => true,
                'user_id' => mysqli_insert_id($this->conn)
            ];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }
}

