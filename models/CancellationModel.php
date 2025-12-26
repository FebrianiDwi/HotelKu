<?php

class CancellationModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getUserCancellations($userId)
    {
        $userIdEsc = (int)$userId;
        $sql = "SELECT c.*, r.guest_name, r.checkin_date, r.checkout_date
                FROM cancellations c INNER JOIN reservations r ON c.reservation_id = r.id
                WHERE r.user_id = $userIdEsc ORDER BY c.created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        $cancellations = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $cancellations[] = $row;
            }
        }
        return $cancellations;
    }

    public function getCancellationByBookingCode($bookingCode, $userId = null)
    {
        $codeEsc = mysqli_real_escape_string($this->conn, $bookingCode);
        $sql = "SELECT c.*, r.guest_name, r.checkin_date, r.checkout_date, r.user_id
                FROM cancellations c INNER JOIN reservations r ON c.reservation_id = r.id
                WHERE c.booking_code = '$codeEsc'";
        if ($userId) {
            $userIdEsc = (int)$userId;
            $sql .= " AND r.user_id = $userIdEsc";
        }
        $sql .= " LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function getAllCancellations()
    {
        $sql = "SELECT c.*, r.guest_name, r.guest_email, r.checkin_date, r.checkout_date
                FROM cancellations c INNER JOIN reservations r ON c.reservation_id = r.id
                ORDER BY c.created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        $cancellations = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $cancellations[] = $row;
            }
        }
        return $cancellations;
    }

    public function getCancellationReasonText($reason)
    {
        $reasonMap = [
            'change_plans' => 'Perubahan rencana',
            'found_cheaper' => 'Menemukan harga lebih murah',
            'emergency' => 'Keadaan darurat',
            'dissatisfied' => 'Tidak puas dengan layanan',
            'other' => 'Lainnya'
        ];
        return $reasonMap[$reason] ?? $reason;
    }

    public function createCancellation($reservationId, $bookingCode, $reason, $details = '')
    {
        $reservationIdEsc = (int)$reservationId;
        $bookingCodeEsc = mysqli_real_escape_string($this->conn, $bookingCode);
        $reasonEsc = mysqli_real_escape_string($this->conn, $reason);
        $detailsEsc = mysqli_real_escape_string($this->conn, $details);

        $sql = "INSERT INTO cancellations (reservation_id, booking_code, submission_date, reason, details, status, created_at) 
                VALUES ($reservationIdEsc, '$bookingCodeEsc', CURDATE(), '$reasonEsc', '$detailsEsc', 'pending', NOW())";

        if (mysqli_query($this->conn, $sql)) {
            return [
                'success' => true,
                'cancellation_id' => mysqli_insert_id($this->conn)
            ];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }
}
