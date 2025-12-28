<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/CancellationModel.php';

// Check if user is admin (you can add proper admin check here)
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
    exit;
}

$bookingCode = isset($_POST['booking_code']) ? trim($_POST['booking_code']) : '';
$status = isset($_POST['status']) ? trim($_POST['status']) : '';
$adminResponse = isset($_POST['admin_response']) ? trim($_POST['admin_response']) : '';

if (empty($bookingCode)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Booking code is required']);
    exit;
}

$validStatuses = ['approved', 'rejected'];
if (!in_array($status, $validStatuses)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid status']);
    exit;
}

$cancellationModel = new CancellationModel($conn);
$result = $cancellationModel->updateCancellationStatus($bookingCode, $status, $adminResponse);

header('Content-Type: application/json');
if ($result['success']) {
    $message = $status === 'approved' ? 'Pembatalan berhasil disetujui' : 'Pembatalan ditolak';
    echo json_encode(['success' => true, 'message' => $message]);
} else {
    echo json_encode(['success' => false, 'error' => $result['error'] ?? 'Gagal memproses pembatalan']);
}
exit;

