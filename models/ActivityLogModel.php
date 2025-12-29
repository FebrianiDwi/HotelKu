<?php

class ActivityLogModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->ensureTableExists();
    }

    private function ensureTableExists()
    {
        // Check if table exists, if not create it
        $checkTable = mysqli_query($this->conn, "SHOW TABLES LIKE 'activity_logs'");
        if (!$checkTable || mysqli_num_rows($checkTable) == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS activity_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                action VARCHAR(100) NOT NULL,
                entity_type VARCHAR(50) NOT NULL,
                entity_id INT DEFAULT NULL,
                description TEXT,
                ip_address VARCHAR(45),
                user_agent TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_user_id (user_id),
                INDEX idx_created_at (created_at),
                INDEX idx_entity (entity_type, entity_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            mysqli_query($this->conn, $sql);
        }
    }

    public function logActivity($userId, $action, $entityType, $entityId = null, $description = null)
    {
        $userIdEsc = (int)$userId;
        $actionEsc = mysqli_real_escape_string($this->conn, $action);
        $entityTypeEsc = mysqli_real_escape_string($this->conn, $entityType);
        $entityIdEsc = $entityId !== null ? (int)$entityId : 'NULL';
        $descriptionEsc = $description ? mysqli_real_escape_string($this->conn, $description) : 'NULL';
        
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $ipAddressEsc = mysqli_real_escape_string($this->conn, $ipAddress);
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $userAgentEsc = mysqli_real_escape_string($this->conn, $userAgent);
        
        $sql = "INSERT INTO activity_logs (user_id, action, entity_type, entity_id, description, ip_address, user_agent) 
                VALUES ($userIdEsc, '$actionEsc', '$entityTypeEsc', " . ($entityIdEsc !== 'NULL' ? $entityIdEsc : 'NULL') . ", 
                " . ($descriptionEsc !== 'NULL' ? "'$descriptionEsc'" : 'NULL') . ", '$ipAddressEsc', '$userAgentEsc')";
        
        return mysqli_query($this->conn, $sql);
    }

    public function getRecentActivities($limit = 50, $userId = null)
    {
        $limitEsc = (int)$limit;
        $sql = "SELECT al.*, u.first_name, u.last_name, u.email 
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id";
        
        if ($userId !== null) {
            $userIdEsc = (int)$userId;
            $sql .= " WHERE al.user_id = $userIdEsc";
        }
        
        $sql .= " ORDER BY al.created_at DESC LIMIT $limitEsc";
        
        $result = mysqli_query($this->conn, $sql);
        $activities = [];
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $activities[] = $row;
            }
        }
        
        return $activities;
    }

    public function getActivitiesByEntity($entityType, $entityId)
    {
        $entityTypeEsc = mysqli_real_escape_string($this->conn, $entityType);
        $entityIdEsc = (int)$entityId;
        
        $sql = "SELECT al.*, u.first_name, u.last_name, u.email 
                FROM activity_logs al 
                LEFT JOIN users u ON al.user_id = u.id
                WHERE al.entity_type = '$entityTypeEsc' AND al.entity_id = $entityIdEsc
                ORDER BY al.created_at DESC";
        
        $result = mysqli_query($this->conn, $sql);
        $activities = [];
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $activities[] = $row;
            }
        }
        
        return $activities;
    }
}

