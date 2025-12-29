<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/RoomTypeModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/log_activity.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

$userModel = new UserModel($conn);
$user = $userModel->findById($_SESSION['user_id']);

if (!$user || ($user['role'] ?? '') !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Admin access required']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
    exit;
}

$action = isset($_POST['action']) ? trim($_POST['action']) : '';
$roomTypeModel = new RoomTypeModel($conn);

// Handle file upload
function uploadRoomTypeImage($file, $uploadDir = '../res/')
{
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $file['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type. Allowed: JPEG, PNG, GIF, WebP'];
    }
    
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'File size too large. Maximum 5MB'];
    }
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'roomtype_' . time() . '_' . uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return 'res/' . $filename;
    }
    
    return null;
}

header('Content-Type: application/json');

switch ($action) {
    case 'create':
        $typeCode = isset($_POST['type_code']) ? trim($_POST['type_code']) : '';
        $typeName = isset($_POST['type_name']) ? trim($_POST['type_name']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $pricePerNight = isset($_POST['price_per_night']) ? (float)$_POST['price_per_night'] : 0;
        $maxOccupancy = isset($_POST['max_occupancy']) ? (int)$_POST['max_occupancy'] : 2;
        $features = isset($_POST['features']) ? trim($_POST['features']) : '';
        $imageUrl = null;
        
        if (empty($typeCode) || empty($typeName) || $pricePerNight <= 0) {
            echo json_encode(['success' => false, 'error' => 'Type code, name, and price are required']);
            exit;
        }
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadRoomTypeImage($_FILES['image']);
            if (is_array($uploadResult)) {
                echo json_encode($uploadResult);
                exit;
            }
            $imageUrl = $uploadResult;
        } elseif (isset($_POST['image_url']) && !empty(trim($_POST['image_url']))) {
            $imageUrl = trim($_POST['image_url']);
        }
        
        $result = $roomTypeModel->createRoomType(
            $typeCode,
            $typeName,
            $description,
            $pricePerNight,
            $maxOccupancy,
            $features,
            $imageUrl
        );
        
        if ($result['success']) {
            logAdminActivity('create', 'room_type', $result['room_type_id'] ?? null, "Created room type: $typeName");
        }
        
        echo json_encode($result);
        break;
        
    case 'update':
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $typeCode = isset($_POST['type_code']) ? trim($_POST['type_code']) : '';
        $typeName = isset($_POST['type_name']) ? trim($_POST['type_name']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $pricePerNight = isset($_POST['price_per_night']) ? (float)$_POST['price_per_night'] : 0;
        $maxOccupancy = isset($_POST['max_occupancy']) ? (int)$_POST['max_occupancy'] : 2;
        $features = isset($_POST['features']) ? trim($_POST['features']) : '';
        $status = isset($_POST['status']) ? trim($_POST['status']) : null;
        $imageUrl = null;
        $updateImage = isset($_POST['update_image']) ? $_POST['update_image'] : 'keep';
        
        if ($id <= 0 || empty($typeCode) || empty($typeName) || $pricePerNight <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid data']);
            exit;
        }
        
        if ($updateImage === 'upload' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadRoomTypeImage($_FILES['image']);
            if (is_array($uploadResult)) {
                echo json_encode($uploadResult);
                exit;
            }
            $imageUrl = $uploadResult;
        } elseif ($updateImage === 'url' && isset($_POST['image_url']) && !empty(trim($_POST['image_url']))) {
            $imageUrl = trim($_POST['image_url']);
        } elseif ($updateImage === 'remove') {
            $imageUrl = '';
        }
        
        $result = $roomTypeModel->updateRoomType($id, $typeCode, $typeName, $description, $pricePerNight, $maxOccupancy, $features, $imageUrl, $status);
        if ($result['success']) {
            logAdminActivity('update', 'room_type', $id, "Updated room type: $typeName");
        }
        echo json_encode($result);
        break;
        
    case 'delete':
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid ID']);
            exit;
        }
        
        $result = $roomTypeModel->deleteRoomType($id);
        if ($result['success']) {
            logAdminActivity('delete', 'room_type', $id, "Deleted room type ID: $id");
        }
        echo json_encode($result);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}

exit;

