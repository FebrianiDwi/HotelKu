<?php

class RoomTypeModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllRoomTypes()
    {
        $sql = "SELECT * FROM room_types ORDER BY created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        $roomTypes = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $roomTypes[] = $row;
            }
        }
        return $roomTypes;
    }

    public function getRoomTypeById($id)
    {
        $idEsc = (int)$id;
        $sql = "SELECT * FROM room_types WHERE id = $idEsc LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($result && mysqli_num_rows($result) === 1) {
            return mysqli_fetch_assoc($result);
        }
        return null;
    }

    public function createRoomType($typeCode, $typeName, $description, $pricePerNight, $maxOccupancy, $features, $imageUrl = null)
    {
        $codeEsc = mysqli_real_escape_string($this->conn, $typeCode);
        $nameEsc = mysqli_real_escape_string($this->conn, $typeName);
        $descEsc = mysqli_real_escape_string($this->conn, $description);
        $priceEsc = (float)$pricePerNight;
        $occupancyEsc = (int)$maxOccupancy;
        $featuresEsc = mysqli_real_escape_string($this->conn, $features);
        $imageUrlEsc = $imageUrl ? mysqli_real_escape_string($this->conn, $imageUrl) : 'NULL';
        
        // Check if type_code already exists
        $checkSql = "SELECT COUNT(*) as count FROM room_types WHERE type_code = '$codeEsc'";
        $checkResult = mysqli_query($this->conn, $checkSql);
        if ($checkResult) {
            $row = mysqli_fetch_assoc($checkResult);
            if ($row['count'] > 0) {
                return ['success' => false, 'error' => 'Type code already exists'];
            }
        }
        
        $sql = "INSERT INTO room_types (type_code, type_name, description, price_per_night, max_occupancy, features, image_url, status) 
                VALUES ('$codeEsc', '$nameEsc', '$descEsc', $priceEsc, $occupancyEsc, '$featuresEsc', " . ($imageUrlEsc !== 'NULL' ? "'$imageUrlEsc'" : 'NULL') . ", 'active')";

        if (mysqli_query($this->conn, $sql)) {
            return [
                'success' => true,
                'room_type_id' => mysqli_insert_id($this->conn)
            ];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }

    public function updateRoomType($id, $typeCode, $typeName, $description, $pricePerNight, $maxOccupancy, $features, $imageUrl = null, $status = null)
    {
        $idEsc = (int)$id;
        $codeEsc = mysqli_real_escape_string($this->conn, $typeCode);
        $nameEsc = mysqli_real_escape_string($this->conn, $typeName);
        $descEsc = mysqli_real_escape_string($this->conn, $description);
        $priceEsc = (float)$pricePerNight;
        $occupancyEsc = (int)$maxOccupancy;
        $featuresEsc = mysqli_real_escape_string($this->conn, $features);
        
        // Check if type_code already exists for other records
        $checkSql = "SELECT COUNT(*) as count FROM room_types WHERE type_code = '$codeEsc' AND id != $idEsc";
        $checkResult = mysqli_query($this->conn, $checkSql);
        if ($checkResult) {
            $row = mysqli_fetch_assoc($checkResult);
            if ($row['count'] > 0) {
                return ['success' => false, 'error' => 'Type code already exists'];
            }
        }
        
        $updates = [];
        $updates[] = "type_code = '$codeEsc'";
        $updates[] = "type_name = '$nameEsc'";
        $updates[] = "description = '$descEsc'";
        $updates[] = "price_per_night = $priceEsc";
        $updates[] = "max_occupancy = $occupancyEsc";
        $updates[] = "features = '$featuresEsc'";
        
        if ($imageUrl !== null) {
            $imageUrlEsc = $imageUrl ? mysqli_real_escape_string($this->conn, $imageUrl) : 'NULL';
            $updates[] = "image_url = " . ($imageUrlEsc !== 'NULL' ? "'$imageUrlEsc'" : 'NULL');
        }
        
        if ($status !== null) {
            $statusEsc = mysqli_real_escape_string($this->conn, $status);
            $updates[] = "status = '$statusEsc'";
        }
        
        $updates[] = "updated_at = NOW()";
        
        $sql = "UPDATE room_types SET " . implode(', ', $updates) . " WHERE id = $idEsc";
        
        if (mysqli_query($this->conn, $sql)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }

    public function deleteRoomType($id)
    {
        $idEsc = (int)$id;
        
        // Check if room type is being used in reservations
        $checkSql = "SELECT COUNT(*) as count FROM reservations WHERE room_type_id = $idEsc";
        $checkResult = mysqli_query($this->conn, $checkSql);
        if ($checkResult) {
            $row = mysqli_fetch_assoc($checkResult);
            if ($row['count'] > 0) {
                return ['success' => false, 'error' => 'Cannot delete room type that is being used in reservations'];
            }
        }
        
        $sql = "DELETE FROM room_types WHERE id = $idEsc";
        
        if (mysqli_query($this->conn, $sql)) {
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => mysqli_error($this->conn)];
    }
}

