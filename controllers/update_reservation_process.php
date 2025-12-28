<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ReservationModel.php';

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
$roomTypeId = isset($_POST['room_type_id']) ? (int)$_POST['room_type_id'] : 0;
$roomCount = isset($_POST['room_count']) ? (int)$_POST['room_count'] : 1;
$checkin = isset($_POST['checkin_date']) ? trim($_POST['checkin_date']) : '';
$checkout = isset($_POST['checkout_date']) ? trim($_POST['checkout_date']) : '';
$guestName = isset($_POST['guest_name']) ? trim($_POST['guest_name']) : '';
$guestEmail = isset($_POST['guest_email']) ? trim($_POST['guest_email']) : '';
$guestPhone = isset($_POST['guest_phone']) ? trim($_POST['guest_phone']) : '';
$specialRequests = isset($_POST['special_requests']) ? trim($_POST['special_requests']) : '';
$status = isset($_POST['status']) ? trim($_POST['status']) : '';

if (empty($bookingCode)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Booking code is required']);
    exit;
}

$reservationModel = new ReservationModel($conn);
$result = $reservationModel->updateReservation(
    $bookingCode,
    $roomTypeId,
    $roomCount,
    $checkin,
    $checkout,
    $guestName,
    $guestEmail,
    $guestPhone,
    $specialRequests,
    $status
);

header('Content-Type: application/json');
if ($result['success']) {
    echo json_encode(['success' => true, 'message' => 'Reservasi berhasil diperbarui']);
} else {
    echo json_encode(['success' => false, 'error' => $result['error'] ?? 'Gagal memperbarui reservasi']);
}
exit;

