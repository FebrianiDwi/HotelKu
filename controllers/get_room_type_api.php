<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/RoomTypeModel.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

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

$roomTypeModel = new RoomTypeModel($conn);
$roomType = $roomTypeModel->getRoomTypeById($id);

if (!$roomType) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Room type not found']);
    exit;
}

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'room_type' => $roomType
]);
exit;

