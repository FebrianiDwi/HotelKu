<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ReservationModel.php';
require_once __DIR__ . '/../models/RoomTypeModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/log_activity.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Silakan login terlebih dahulu']);
    exit;
}

$userModel = new UserModel($conn);
$currentUser = $userModel->findById($_SESSION['user_id']);

if (!$currentUser || ($currentUser['role'] ?? '') !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Akses admin diperlukan. Pastikan Anda sudah login sebagai admin.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
    exit;
}

$action = isset($_POST['action']) ? trim($_POST['action']) : '';
$reservationModel = new ReservationModel($conn);
$roomTypeModel = new RoomTypeModel($conn);

header('Content-Type: application/json');

switch ($action) {
    case 'create':
        $roomTypeId = isset($_POST['room_type_id']) ? (int)$_POST['room_type_id'] : 0;
        $roomCount = isset($_POST['room_count']) ? (int)$_POST['room_count'] : 1;
        $checkin = isset($_POST['checkin_date']) ? trim($_POST['checkin_date']) : '';
        $checkout = isset($_POST['checkout_date']) ? trim($_POST['checkout_date']) : '';
        $guestName = isset($_POST['guest_name']) ? trim($_POST['guest_name']) : '';
        $guestEmail = isset($_POST['guest_email']) ? trim($_POST['guest_email']) : '';
        $guestPhone = isset($_POST['guest_phone']) ? trim($_POST['guest_phone']) : '';
        $specialRequests = isset($_POST['special_requests']) ? trim($_POST['special_requests']) : '';
        $status = isset($_POST['status']) ? trim($_POST['status']) : 'pending';
        $userId = isset($_POST['user_id']) && !empty($_POST['user_id']) ? (int)$_POST['user_id'] : null;

        if ($roomTypeId <= 0 || $roomCount < 1 || empty($checkin) || empty($checkout) || empty($guestName) || empty($guestEmail) || empty($guestPhone)) {
            echo json_encode(['success' => false, 'error' => 'Semua field wajib diisi']);
            exit;
        }

        if (strtotime($checkin) >= strtotime($checkout)) {
            echo json_encode(['success' => false, 'error' => 'Tanggal check-out harus setelah check-in']);
            exit;
        }

        if (strtotime($checkin) < strtotime(date('Y-m-d'))) {
            echo json_encode(['success' => false, 'error' => 'Tanggal check-in tidak boleh di masa lalu']);
            exit;
        }

        $totalPrice = $reservationModel->calculatePrice($roomTypeId, $roomCount, $checkin, $checkout);

        $result = $reservationModel->createReservation(
            $userId,
            $roomTypeId,
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
            // Update status if provided
            if ($status !== 'pending') {
                $reservationModel->updateReservation($result['booking_code'], $roomTypeId, $roomCount, $checkin, $checkout, $guestName, $guestEmail, $guestPhone, $specialRequests, $status, $totalPrice);
            }
            logAdminActivity('create', 'reservation', $result['reservation_id'] ?? null, "Created reservation: " . $result['booking_code']);
        }
        
        echo json_encode($result);
        break;

    case 'update':
        $bookingCode = isset($_POST['booking_code']) ? trim($_POST['booking_code']) : '';
        $roomTypeId = isset($_POST['room_type_id']) ? (int)$_POST['room_type_id'] : 0;
        $roomCount = isset($_POST['room_count']) ? (int)$_POST['room_count'] : 1;
        $checkin = isset($_POST['checkin_date']) ? trim($_POST['checkin_date']) : '';
        $checkout = isset($_POST['checkout_date']) ? trim($_POST['checkout_date']) : '';
        $guestName = isset($_POST['guest_name']) ? trim($_POST['guest_name']) : '';
        $guestEmail = isset($_POST['guest_email']) ? trim($_POST['guest_email']) : '';
        $guestPhone = isset($_POST['guest_phone']) ? trim($_POST['guest_phone']) : '';
        $specialRequests = isset($_POST['special_requests']) ? trim($_POST['special_requests']) : '';
        $status = isset($_POST['status']) ? trim($_POST['status']) : null;

        if (empty($bookingCode) || $roomTypeId <= 0 || $roomCount < 1 || empty($checkin) || empty($checkout) || empty($guestName) || empty($guestEmail) || empty($guestPhone)) {
            echo json_encode(['success' => false, 'error' => 'Semua field wajib diisi']);
            exit;
        }

        if (strtotime($checkin) >= strtotime($checkout)) {
            echo json_encode(['success' => false, 'error' => 'Tanggal check-out harus setelah check-in']);
            exit;
        }

        $totalPrice = $reservationModel->calculatePrice($roomTypeId, $roomCount, $checkin, $checkout);

        $result = $reservationModel->updateReservation($bookingCode, $roomTypeId, $roomCount, $checkin, $checkout, $guestName, $guestEmail, $guestPhone, $specialRequests, $status, $totalPrice);
        
        if ($result['success']) {
            logAdminActivity('update', 'reservation', null, "Updated reservation: $bookingCode");
        }
        
        echo json_encode($result);
        break;

    case 'delete':
        $bookingCode = isset($_POST['booking_code']) ? trim($_POST['booking_code']) : '';

        if (empty($bookingCode)) {
            echo json_encode(['success' => false, 'error' => 'Booking code is required']);
            exit;
        }

        $result = $reservationModel->deleteReservation($bookingCode);
        
        if ($result['success']) {
            logAdminActivity('delete', 'reservation', null, "Deleted reservation: $bookingCode");
        }
        
        echo json_encode($result);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        break;
}

exit;

