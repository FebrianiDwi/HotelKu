<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ReservationModel.php';
require_once __DIR__ . '/../models/CancellationModel.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'cancel_reservasi.php';
    header('Location: ../views/login_register.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/cancel_reservasi.php?error=invalid_method');
    exit;
}

$userId = $_SESSION['user_id'];
$bookingCode = isset($_POST['booking_code']) ? trim($_POST['booking_code']) : '';
$reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
$details = isset($_POST['details']) ? trim($_POST['details']) : '';

if (empty($bookingCode)) {
    header('Location: ../views/cancel_reservasi.php?error=empty_booking_code');
    exit;
}

if (empty($reason)) {
    header('Location: ../views/cancel_reservasi.php?error=empty_reason');
    exit;
}

$validReasons = ['change_plans', 'found_cheaper', 'emergency', 'dissatisfied', 'other'];
if (!in_array($reason, $validReasons)) {
    header('Location: ../views/cancel_reservasi.php?error=invalid_reason');
    exit;
}

$codeEsc = mysqli_real_escape_string($conn, $bookingCode);
$sql = "SELECT * FROM reservations WHERE booking_code = '$codeEsc' LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    header('Location: ../views/cancel_reservasi.php?error=booking_not_found');
    exit;
}

$reservation = mysqli_fetch_assoc($result);

if ((int)$reservation['user_id'] != (int)$userId) {
    header('Location: ../views/cancel_reservasi.php?error=unauthorized');
    exit;
}

if ($reservation['status'] == 'cancelled') {
    header('Location: ../views/cancel_reservasi.php?error=already_cancelled');
    exit;
}

$cancellationModel = new CancellationModel($conn);
$existingCancellation = $cancellationModel->getCancellationByBookingCode($bookingCode, $userId);

if ($existingCancellation && $existingCancellation['status'] == 'pending') {
    header('Location: ../views/cancel_reservasi.php?error=already_submitted');
    exit;
}

$result = $cancellationModel->createCancellation(
    $reservation['id'],
    $bookingCode,
    $reason,
    $details
);

if ($result['success']) {
    header('Location: ../views/cancel_reservasi.php?success=1&booking_code=' . urlencode($bookingCode));
    exit;
} else {
    header('Location: ../views/cancel_reservasi.php?error=create_failed');
    exit;
}
