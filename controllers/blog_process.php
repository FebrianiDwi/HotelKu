<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/BlogModel.php';
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
$blogModel = new BlogModel($conn);

// Handle file upload
function uploadImage($file, $uploadDir = '../res/')
{
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    // Check file type
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $file['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type. Allowed: JPEG, PNG, GIF, WebP'];
    }
    
    // Check file size (max 5MB)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'error' => 'File size too large. Maximum 5MB'];
    }
    
    // Create upload directory if not exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'blog_' . time() . '_' . uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return 'res/' . $filename; // Return relative path
    }
    
    return null;
}

header('Content-Type: application/json');

switch ($action) {
    case 'create':
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $excerpt = isset($_POST['excerpt']) ? trim($_POST['excerpt']) : '';
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';
        $status = isset($_POST['status']) ? trim($_POST['status']) : 'draft';
        $imageUrl = null;
        
        if (empty($title) || empty($content)) {
            echo json_encode(['success' => false, 'error' => 'Title and content are required']);
            exit;
        }
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadImage($_FILES['image']);
            if (is_array($uploadResult)) {
                echo json_encode($uploadResult);
                exit;
            }
            $imageUrl = $uploadResult;
        } elseif (isset($_POST['image_url']) && !empty(trim($_POST['image_url']))) {
            // If URL provided instead of file upload
            $imageUrl = trim($_POST['image_url']);
        }
        
        $result = $blogModel->createPost(
            $_SESSION['user_id'],
            $title,
            $excerpt,
            $content,
            $imageUrl,
            $status
        );
        
        if ($result['success']) {
            logAdminActivity('create', 'blog_post', $result['post_id'] ?? null, "Created blog post: $title");
        }
        
        echo json_encode($result);
        break;
        
    case 'update':
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $excerpt = isset($_POST['excerpt']) ? trim($_POST['excerpt']) : '';
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';
        $status = isset($_POST['status']) ? trim($_POST['status']) : null;
        $imageUrl = null;
        $updateImage = isset($_POST['update_image']) ? $_POST['update_image'] : 'keep';
        
        if ($id <= 0 || empty($title) || empty($content)) {
            echo json_encode(['success' => false, 'error' => 'Invalid data']);
            exit;
        }
        
        // Handle image update
        if ($updateImage === 'upload' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = uploadImage($_FILES['image']);
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
        // If 'keep', $imageUrl stays null and won't be updated
        
        $result = $blogModel->updatePost($id, $title, $excerpt, $content, $imageUrl, $status);
        if ($result['success']) {
            logAdminActivity('update', 'blog_post', $id, "Updated blog post: $title");
        }
        echo json_encode($result);
        break;
        
    case 'delete':
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'Invalid ID']);
            exit;
        }
        
        $result = $blogModel->deletePost($id);
        if ($result['success']) {
            logAdminActivity('delete', 'blog_post', $id, "Deleted blog post ID: $id");
        }
        echo json_encode($result);
        break;
        
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}

exit;

