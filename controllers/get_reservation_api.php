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

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
    exit;
}

$bookingCode = isset($_GET['booking_code']) ? trim($_GET['booking_code']) : '';

if (empty($bookingCode)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Booking code is required']);
    exit;
}

$reservationModel = new ReservationModel($conn);
$reservation = $reservationModel->getReservationByBookingCode($bookingCode);

if (!$reservation) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Reservation not found']);
    exit;
}

// Get all room types for the form
$roomTypes = $reservationModel->getRoomTypes();

header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'reservation' => $reservation,
    'room_types' => $roomTypes
]);
exit;

