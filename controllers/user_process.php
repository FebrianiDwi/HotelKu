<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/log_activity.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Silakan login terlebih dahulu']);
    exit;
}

$userModel = new UserModel($conn);
$currentUser = $userModel->findById($_SESSION['user_id']);

if (!$currentUser || ($currentUser['role'] ?? '') !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Akses admin diperlukan. Pastikan Anda sudah login sebagai admin.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
    exit;
}

$action = isset($_POST['action']) ? trim($_POST['action']) : '';

header('Content-Type: application/json');

switch ($action) {
    case 'create':
        $firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $lastName = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $role = isset($_POST['role']) ? trim($_POST['role']) : 'user';
        $status = isset($_POST['status']) ? trim($_POST['status']) : 'active';
        
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($phone)) {
            echo json_encode(['success' => false, 'error' => 'All fields are required']);
            exit;
        }
        
        $validRoles = ['admin', 'staff', 'user'];
        if (!in_array($role, $validRoles)) {
            echo json_encode(['success' => false, 'error' => 'Invalid role']);
            exit;
        }
        
        $result = $userModel->createUserByAdmin($firstName, $lastName, $email, $password, $phone, $role, $status);
        if ($result['success']) {
            logAdminActivity('create', 'user', $result['user_id'] ?? null, "Created user: $email with role: $role");
        }
        echo json_encode($result);
        break;
        
    case 'update':
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $lastName = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $role = isset($_POST['role']) ? trim($_POST['role']) : null;
        $status = isset($_POST['status']) ? trim($_POST['status']) : null;
        $password = isset($_POST['password']) ? trim($_POST['password']) : null;
        
        if ($id <= 0 || empty($firstName) || empty($lastName) || empty($email) || empty($phone)) {
            echo json_encode(['success' => false, 'error' => 'Invalid data']);
            exit;
        }
        
        if ($role !== null) {
            $validRoles = ['admin', 'staff', 'user'];
            if (!in_array($role, $validRoles)) {
                echo json_encode(['success' => false, 'error' => 'Invalid role']);
                exit;
            }
        }
        
        $result = $userModel->updateUser($id, $firstName, $lastName, $email, $phone, $status, $role, $password);
        if ($result['success']) {
            logAdminActivity('update', 'user', $id, "Updated user: $email");
        }
        echo json_encode($result);
        break;
        
    case 'delete':
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid ID']);
            exit;
        }
        
        $result = $userModel->deleteUser($id);
        if ($result['success']) {
            logAdminActivity('delete', 'user', $id, "Deleted user ID: $id");
        }
        echo json_encode($result);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}

exit;

