<?php

class ReservationModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getRoomTypes()
    {
        $sql = "SELECT id, type_code, type_name, price_per_night, max_occupancy FROM room_types WHERE status = 'active'";
        $result = mysqli_query($this->conn, $sql);
        $roomTypes = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $roomTypes[] = $row;
            }
        }
        return $roomTypes;
    }

    public function getRoomTypeByCode($typeCode)
    {
        $codeEsc = mysqli_real_escape_string($this->conn, $typeCode);
        $sql = "SELECT * FROM room_types WHERE type_code = '$codeEsc' AND status = 'active' LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function generateBookingCode()
    {
        do {
            $code = 'RS' . date('Y') . strtoupper(substr(md5(uniqid(rand(), true)), 0, 3)) . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
            $check = mysqli_query($this->conn, "SELECT COUNT(*) as cnt FROM reservations WHERE booking_code = '$code'");
            $row = mysqli_fetch_assoc($check);
        } while ($row['cnt'] > 0);
        return $code;
    }

    public function calculatePrice($roomTypeId, $roomCount, $checkin, $checkout)
    {
        $typeId = (int)$roomTypeId;
        $count = (int)$roomCount;
        $checkinEsc = mysqli_real_escape_string($this->conn, $checkin);
        $checkoutEsc = mysqli_real_escape_string($this->conn, $checkout);

        $sql = "SELECT price_per_night FROM room_types WHERE id = $typeId";
        $result = mysqli_query($this->conn, $sql);
        if (!$result || mysqli_num_rows($result) === 0) {
            return 0;
        }
        
        $roomType = mysqli_fetch_assoc($result);
        $pricePerNight = $roomType['price_per_night'];
        
        $nights = (strtotime($checkoutEsc) - strtotime($checkinEsc)) / 86400;
        if ($nights < 1) $nights = 1;
        
        return $pricePerNight * $count * $nights;
    }

    public function createReservation($userId, $roomTypeId, $roomCount, $checkin, $checkout, $guestName, $guestEmail, $guestPhone, $specialRequests, $totalPrice)
    {
        $bookingCode = $this->generateBookingCode();
        $userIdEsc = $userId ? (int)$userId : 'NULL';
        $typeIdEsc = (int)$roomTypeId;
        $countEsc = (int)$roomCount;
        $checkinEsc = mysqli_real_escape_string($this->conn, $checkin);
        $checkoutEsc = mysqli_real_escape_string($this->conn, $checkout);
        $nameEsc = mysqli_real_escape_string($this->conn, $guestName);
        $emailEsc = mysqli_real_escape_string($this->conn, $guestEmail);
        $phoneEsc = mysqli_real_escape_string($this->conn, $guestPhone);
        $requestsEsc = mysqli_real_escape_string($this->conn, $specialRequests);
        $priceEsc = (float)$totalPrice;

        $sql = "INSERT INTO reservations (booking_code, user_id, room_type_id, room_count, checkin_date, checkout_date, guest_name, guest_email, guest_phone, special_requests, total_price, status, payment_status) 
                VALUES ('$bookingCode', $userIdEsc, $typeIdEsc, $countEsc, '$checkinEsc', '$checkoutEsc', '$nameEsc', '$emailEsc', '$phoneEsc', '$requestsEsc', $priceEsc, 'pending', 'pending')";

        if (mysqli_query($this->conn, $sql)) {
            return [
                'success' => true,
                'booking_code' => $bookingCode,
                'reservation_id' => mysqli_insert_id($this->conn)
            ];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }

    public function getUserReservations($userId)
    {
        $userIdEsc = (int)$userId;
        $sql = "SELECT r.*, rt.type_name, rt.type_code 
                FROM reservations r 
                LEFT JOIN room_types rt ON r.room_type_id = rt.id 
                WHERE r.user_id = $userIdEsc 
                ORDER BY r.created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        $reservations = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $reservations[] = $row;
            }
        }
        return $reservations;
    }

    public function getReservationByBookingCode($bookingCode, $userId = null)
    {
        $bookingCode = trim($bookingCode);
        $codeEsc = mysqli_real_escape_string($this->conn, $bookingCode);
        $sql = "SELECT r.*, rt.type_name, rt.type_code FROM reservations r 
                LEFT JOIN room_types rt ON r.room_type_id = rt.id 
                WHERE r.booking_code = '$codeEsc'";
        if ($userId !== null) {
            $userIdEsc = (int)$userId;
            $sql .= " AND r.user_id = $userIdEsc";
        }
        $sql .= " LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function updateReservation($bookingCode, $roomTypeId, $roomCount, $checkin, $checkout, $guestName, $guestEmail, $guestPhone, $specialRequests, $status = null, $totalPrice = null)
    {
        $codeEsc = mysqli_real_escape_string($this->conn, $bookingCode);
        $typeIdEsc = (int)$roomTypeId;
        $countEsc = (int)$roomCount;
        $checkinEsc = mysqli_real_escape_string($this->conn, $checkin);
        $checkoutEsc = mysqli_real_escape_string($this->conn, $checkout);
        $nameEsc = mysqli_real_escape_string($this->conn, $guestName);
        $emailEsc = mysqli_real_escape_string($this->conn, $guestEmail);
        $phoneEsc = mysqli_real_escape_string($this->conn, $guestPhone);
        $requestsEsc = mysqli_real_escape_string($this->conn, $specialRequests);

        $updates = [
            "room_type_id = $typeIdEsc",
            "room_count = $countEsc",
            "checkin_date = '$checkinEsc'",
            "checkout_date = '$checkoutEsc'",
            "guest_name = '$nameEsc'",
            "guest_email = '$emailEsc'",
            "guest_phone = '$phoneEsc'",
            "special_requests = '$requestsEsc'"
        ];

        if ($totalPrice !== null) {
            $priceEsc = (float)$totalPrice;
            $updates[] = "total_price = $priceEsc";
        }

        if ($status !== null) {
            $statusEsc = mysqli_real_escape_string($this->conn, $status);
            $updates[] = "status = '$statusEsc'";
        }

        $sql = "UPDATE reservations SET " . implode(', ', $updates) . " WHERE booking_code = '$codeEsc'";

        if (mysqli_query($this->conn, $sql)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }

    public function deleteReservation($bookingCode)
    {
        $codeEsc = mysqli_real_escape_string($this->conn, $bookingCode);
        
        // Hapus cancellation terkait terlebih dahulu (cascade delete)
        $deleteCancellationSql = "DELETE FROM cancellations WHERE booking_code = '$codeEsc'";
        mysqli_query($this->conn, $deleteCancellationSql);

        // Hapus reservasi
        $sql = "DELETE FROM reservations WHERE booking_code = '$codeEsc'";
        
        if (mysqli_query($this->conn, $sql)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }
}

