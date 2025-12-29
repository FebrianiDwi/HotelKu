<?php
// Data preparation untuk admin dashboard
// File ini berisi semua query dan data preparation

$statsQuery = "SELECT (SELECT COUNT(*) FROM reservations) AS total_reservations,
    (SELECT COUNT(*) FROM reservations WHERE status IN ('confirmed', 'checked_in') AND checkin_date >= CURDATE()) AS active_reservations,
    (SELECT COUNT(*) FROM rooms WHERE status = 'occupied') AS occupied_rooms,
    (SELECT COUNT(*) FROM rooms WHERE status = 'available') AS available_rooms,
    (SELECT COUNT(*) FROM reservations WHERE status = 'cancelled') AS cancelled_reservations,
    (SELECT COUNT(*) FROM users WHERE status = 'active') AS total_users";
$statsResult = mysqli_query($conn, $statsQuery);
$stats = mysqli_fetch_assoc($statsResult);

$totalRooms = ($stats['occupied_rooms'] + $stats['available_rooms']);
$occupancyRate = $totalRooms > 0 ? round(($stats['occupied_rooms'] / $totalRooms) * 100) : 0;
$cancellationRate = $stats['total_reservations'] > 0 ? round(($stats['cancelled_reservations'] / $stats['total_reservations']) * 100) : 0;

// Data Reservasi
$allReservationsQuery = "SELECT r.*, rt.type_name, rt.type_code FROM reservations r 
                         LEFT JOIN room_types rt ON r.room_type_id = rt.id 
                         ORDER BY r.created_at DESC LIMIT 50";
$allReservationsResult = mysqli_query($conn, $allReservationsQuery);
$allReservations = [];
while ($row = mysqli_fetch_assoc($allReservationsResult)) {
    $allReservations[] = $row;
}

// Data Users
$allUsersQuery = "SELECT id, first_name, last_name, email, phone, status, role FROM users ORDER BY created_at DESC LIMIT 50";
$allUsersResult = mysqli_query($conn, $allUsersQuery);
$allUsers = [];
while ($row = mysqli_fetch_assoc($allUsersResult)) {
    $allUsers[] = $row;
}

// Data Blog Posts
$allBlogPosts = $blogModel->getAllPosts();

// Data Room Types
$allRoomTypes = $roomTypeModel->getAllRoomTypes();

// Data Activity Logs
$recentActivities = $activityLogModel->getRecentActivities(30);

// Data untuk Chart: Reservasi per Bulan (12 bulan terakhir)
$monthLabels = [];
$monthlyCounts = [];

// Generate labels for last 12 months
$monthDataMap = [];
for ($i = 11; $i >= 0; $i--) {
    $monthKey = date('Y-m', strtotime("-$i months"));
    $monthLabel = date('M Y', strtotime("-$i months"));
    $monthLabels[] = $monthLabel;
    $monthDataMap[$monthKey] = 0;
}

// Get actual data from database
$monthlyReservationsQuery = "SELECT 
    DATE_FORMAT(created_at, '%Y-%m') as month,
    COUNT(*) as count
    FROM reservations 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month ASC";
$monthlyReservationsResult = mysqli_query($conn, $monthlyReservationsQuery);

// Fill in actual data
if ($monthlyReservationsResult) {
    while ($row = mysqli_fetch_assoc($monthlyReservationsResult)) {
        if (isset($monthDataMap[$row['month']])) {
            $monthDataMap[$row['month']] = (int)$row['count'];
        }
    }
}

// Convert map to array in correct order
foreach ($monthLabels as $index => $label) {
    $monthKey = date('Y-m', strtotime($label));
    $monthlyCounts[] = isset($monthDataMap[$monthKey]) ? $monthDataMap[$monthKey] : 0;
}

// Data untuk Chart: Distribusi Tipe Kamar
$roomTypeDistributionQuery = "SELECT 
    rt.type_name,
    rt.type_code,
    COUNT(r.id) as reservation_count
    FROM room_types rt
    LEFT JOIN reservations r ON rt.id = r.room_type_id
    WHERE rt.status = 'active'
    GROUP BY rt.id, rt.type_name, rt.type_code
    ORDER BY reservation_count DESC";
$roomTypeDistributionResult = mysqli_query($conn, $roomTypeDistributionQuery);
$roomTypeLabels = [];
$roomTypeData = [];
$roomTypeColors = ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'];

while ($row = mysqli_fetch_assoc($roomTypeDistributionResult)) {
    $roomTypeLabels[] = $row['type_name'];
    $roomTypeData[] = (int)$row['reservation_count'];
}

function formatStatus($status) {
    $statusMap = [
        'pending' => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'checked_in' => 'Check-in',
        'checked_out' => 'Check-out',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan'
    ];
    return $statusMap[$status] ?? ucfirst($status);
}

