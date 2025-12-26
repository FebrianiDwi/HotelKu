<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ReservationModel.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'reservasi_form.php';
    header('Location: ../views/login_register.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/reservasi_form.php?error=invalid_method');
    exit;
}

$userId = $_SESSION['user_id'];

$roomType = isset($_POST['room_type']) ? trim($_POST['room_type']) : '';
$roomCount = isset($_POST['room_count']) ? (int)$_POST['room_count'] : 0;
$checkin = isset($_POST['checkin_date']) ? trim($_POST['checkin_date']) : '';
$checkout = isset($_POST['checkout_date']) ? trim($_POST['checkout_date']) : '';
$guestName = isset($_POST['guest_name']) ? trim($_POST['guest_name']) : '';
$guestEmail = isset($_POST['guest_email']) ? trim($_POST['guest_email']) : '';
$guestPhone = isset($_POST['guest_phone']) ? trim($_POST['guest_phone']) : '';
$specialRequests = isset($_POST['special_requests']) ? trim($_POST['special_requests']) : '';

if (empty($roomType) || $roomCount < 1 || empty($checkin) || empty($checkout) || empty($guestName) || empty($guestEmail) || empty($guestPhone)) {
    header('Location: ../views/reservasi_form.php?error=empty_fields');
    exit;
}

if (strtotime($checkin) >= strtotime($checkout)) {
    header('Location: ../views/reservasi_form.php?error=invalid_dates');
    exit;
}

if (strtotime($checkin) < strtotime(date('Y-m-d'))) {
    header('Location: ../views/reservasi_form.php?error=past_date');
    exit;
}

$reservationModel = new ReservationModel($conn);
$roomTypeData = $reservationModel->getRoomTypeByCode($roomType);

if (!$roomTypeData) {
    header('Location: ../views/reservasi_form.php?error=invalid_room_type');
    exit;
}

$totalPrice = $reservationModel->calculatePrice($roomTypeData['id'], $roomCount, $checkin, $checkout);

$result = $reservationModel->createReservation(
    $userId,
    $roomTypeData['id'],
    $roomCount,
    $checkin,
    $checkout,
    $guestName,
    $guestEmail,
    $guestPhone,
    $specialRequests,
    $totalPrice
);

if ($result['success']) {
    header('Location: ../views/reservasi_form.php?success=1&booking_code=' . urlencode($result['booking_code']));
    exit;
} else {
    header('Location: ../views/reservasi_form.php?error=create_failed');
    exit;
}

