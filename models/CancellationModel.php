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

    public function updateCancellationStatus($bookingCode, $status, $adminResponse = '')
    {
        $codeEsc = mysqli_real_escape_string($this->conn, $bookingCode);
        $statusEsc = mysqli_real_escape_string($this->conn, $status);
        $responseEsc = mysqli_real_escape_string($this->conn, $adminResponse);

        // Start transaction
        mysqli_begin_transaction($this->conn);

        try {
            // Check if admin_response column exists
            $checkColumn = mysqli_query($this->conn, "SHOW COLUMNS FROM cancellations LIKE 'admin_response'");
            $hasAdminResponse = $checkColumn && mysqli_num_rows($checkColumn) > 0;
            
            // Update cancellation status
            if ($hasAdminResponse) {
                $sql = "UPDATE cancellations SET status = '$statusEsc', admin_response = '$responseEsc'";
            } else {
                $sql = "UPDATE cancellations SET status = '$statusEsc'";
            }
            
            // Check if updated_at column exists
            $checkUpdatedAt = mysqli_query($this->conn, "SHOW COLUMNS FROM cancellations LIKE 'updated_at'");
            $hasUpdatedAt = $checkUpdatedAt && mysqli_num_rows($checkUpdatedAt) > 0;
            
            if ($hasUpdatedAt) {
                $sql .= ", updated_at = NOW()";
            }
            
            $sql .= " WHERE booking_code = '$codeEsc'";
            
            if (!mysqli_query($this->conn, $sql)) {
                throw new Exception(mysqli_error($this->conn));
            }

            // If approved, update reservation status to cancelled
            if ($statusEsc == 'approved') {
                $updateReservationSql = "UPDATE reservations SET status = 'cancelled'";
                
                // Check if updated_at exists in reservations table
                $checkResUpdatedAt = mysqli_query($this->conn, "SHOW COLUMNS FROM reservations LIKE 'updated_at'");
                $hasResUpdatedAt = $checkResUpdatedAt && mysqli_num_rows($checkResUpdatedAt) > 0;
                
                if ($hasResUpdatedAt) {
                    $updateReservationSql .= ", updated_at = NOW()";
                }
                
                $updateReservationSql .= " WHERE booking_code = '$codeEsc'";
                
                if (!mysqli_query($this->conn, $updateReservationSql)) {
                    throw new Exception(mysqli_error($this->conn));
                }
            }

            mysqli_commit($this->conn);
            return ['success' => true];
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getPendingCancellations()
    {
        $sql = "SELECT c.*, r.guest_name, r.guest_email, r.checkin_date, r.checkout_date, r.total_price
                FROM cancellations c 
                INNER JOIN reservations r ON c.reservation_id = r.id
                WHERE c.status = 'pending'
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
}
