<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ReservationModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/checkin_online.php?error=invalid_method');
    exit;
}

$bookingCode = isset($_POST['booking_code']) ? trim($_POST['booking_code']) : '';

if (empty($bookingCode)) {
    header('Location: ../views/checkin_online.php?error=empty_code');
    exit;
}

$reservationModel = new ReservationModel($conn);
$reservation = $reservationModel->getReservationByBookingCode($bookingCode);

if (!$reservation) {
    header('Location: ../views/checkin_online.php?error=not_found&booking_code=' . urlencode($bookingCode));
    exit;
}

$checkinDate = strtotime($reservation['checkin_date']);
$today = strtotime(date('Y-m-d'));
$daysDifference = ($checkinDate - $today) / 86400;

if ($reservation['status'] == 'confirmed' || $reservation['status'] == 'checked_in') {
    $checkinStatus = 'already_checked_in';
} elseif ($daysDifference <= 1) {
    $codeEsc = mysqli_real_escape_string($conn, $bookingCode);
    mysqli_query($conn, "UPDATE reservations SET status = 'checked_in', updated_at = NOW() WHERE booking_code = '$codeEsc'");
    $checkinStatus = 'success';
} else {
    $checkinStatus = 'too_early';
}

header('Location: ../views/checkin_online.php?booking_code=' . urlencode($bookingCode) . '&status=' . $checkinStatus);
exit;

