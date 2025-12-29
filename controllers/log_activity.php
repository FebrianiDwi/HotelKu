<?php
// Helper function to log activities
// Include this file where needed

if (!isset($_SESSION)) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ActivityLogModel.php';

function logAdminActivity($action, $entityType, $entityId = null, $description = null)
{
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        return false;
    }
    
    global $conn;
    $activityLogModel = new ActivityLogModel($conn);
    return $activityLogModel->logActivity($_SESSION['user_id'], $action, $entityType, $entityId, $description);
}

