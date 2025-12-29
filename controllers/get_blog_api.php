<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/BlogModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
    exit;
}

$blogModel = new BlogModel($conn);
$post = $blogModel->getPostById($id);

if (!$post) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Post not found']);
    exit;
}

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'post' => $post
]);
exit;

