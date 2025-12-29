<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ReservationModel.php';
require_once __DIR__ . '/../models/UserModel.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
    exit;
}

$userModel = new UserModel($conn);
$currentUser = $userModel->findById($_SESSION['user_id']);

if (!$currentUser || ($currentUser['role'] ?? '') !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Admin access required.']);
    exit;
}

$reservationModel = new ReservationModel($conn);

$bookingCode = isset($_GET['booking_code']) ? trim($_GET['booking_code']) : '';

if (empty($bookingCode)) {
    echo json_encode(['success' => false, 'error' => 'Booking code is required.']);
    exit;
}

$reservation = $reservationModel->getReservationByBookingCode($bookingCode);

if ($reservation) {
    echo json_encode(['success' => true, 'reservation' => $reservation]);
} else {
    echo json_encode(['success' => false, 'error' => 'Reservation not found.']);
}

