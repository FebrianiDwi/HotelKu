<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ReservationModel.php';

$pageTitle = 'ReservaStay - Profil';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login_register.php');
    exit;
}

$userModel = new UserModel($conn);
$user = $userModel->findById($_SESSION['user_id']);

if (!$user || empty($user)) {
    session_destroy();
    header('Location: login_register.php');
    exit;
}

$reservationModel = new ReservationModel($conn);
$reservations = $reservationModel->getUserReservations($_SESSION['user_id']);
$totalReservations = count($reservations);
$today = date('Y-m-d');
$activeReservations = 0;
$completedReservations = 0;
$cancelledReservations = 0;

foreach ($reservations as $res) {
    if ($res['status'] == 'cancelled') {
        $cancelledReservations++;
    } elseif (in_array($res['status'], ['confirmed', 'checked_in']) && $res['checkin_date'] >= $today) {
        $activeReservations++;
    } elseif ($res['checkout_date'] < $today && $res['status'] != 'cancelled') {
        $completedReservations++;
    }
}

$joinDate = isset($user['join_date']) ? date('d F Y', strtotime($user['join_date'])) : '-';

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

function getRoomTypeName($typeCode) {
    $typeMap = [
        'standard' => 'Standard Room',
        'deluxe' => 'Deluxe Room',
        'suite' => 'Suite Room',
        'executive' => 'Executive Room'
    ];
    return $typeMap[$typeCode] ?? $typeCode;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    <!-- Profile Page -->
    <section id="profile" class="page">
        <div class="container">
            <div class="page">
                <div class="dashboard-header">
                    <h2 class="dashboard-title">Profil Pengguna</h2>
                    <div>
                        <a href="../controllers/logout.php" class="btn btn-danger">Keluar</a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="form-row">
                        <div class="form-group">
                            <h3 style="margin-bottom: 20px;">Informasi Pribadi</h3>
                            <p><strong>Nama:</strong> <?php echo htmlspecialchars(trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''))); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email'] ?? '-'); ?></p>
                            <p><strong>Telepon:</strong> <?php echo htmlspecialchars($user['phone'] ?? '-'); ?></p>
                            <p><strong>Member sejak:</strong> <?php echo htmlspecialchars($joinDate); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($user['status'] ?? '-')); ?></p>
                            <p><strong>Role:</strong> <?php echo htmlspecialchars(ucfirst($user['role'] ?? '-')); ?></p>
                        </div>
                        <div class="form-group">
                            <h3 style="margin-bottom: 20px;">Statistik Reservasi</h3>
                            <p><strong>Total Reservasi:</strong> <?php echo $totalReservations; ?></p>
                            <p><strong>Reservasi Aktif:</strong> <?php echo $activeReservations; ?></p>
                            <p><strong>Reservasi Selesai:</strong> <?php echo $completedReservations; ?></p>
                            <p><strong>Pembatalan:</strong> <?php echo $cancelledReservations; ?></p>
                        </div>
                    </div>
                </div>
                
                <h3 style="margin: 30px 0 20px;">Riwayat Reservasi</h3>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID Reservasi</th>
                                <th>Tipe Kamar</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="reservationHistory">
                            <?php if (empty($reservations)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada reservasi</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reservations as $res): 
                                    $nights = (strtotime($res['checkout_date']) - strtotime($res['checkin_date'])) / 86400;
                                    if ($nights < 1) $nights = 1;
                                    $checkinFormatted = date('d M Y', strtotime($res['checkin_date']));
                                    $checkoutFormatted = date('d M Y', strtotime($res['checkout_date']));
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($res['booking_code']); ?></td>
                                    <td><?php echo htmlspecialchars($res['type_name'] ?? getRoomTypeName($res['type_code'] ?? '')); ?></td>
                                    <td><?php echo $checkinFormatted; ?> - <?php echo $checkoutFormatted; ?></td>
                                    <td><?php echo $nights; ?> malam</td>
                                    <td>
                                        <span class="status-badge status-<?php echo htmlspecialchars($res['status']); ?>">
                                            <?php echo formatStatus($res['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-secondary btn-small" onclick="viewReservationDetail('<?php echo htmlspecialchars($res['booking_code']); ?>')">Detail</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <?php include '../includes/footer.php'; ?>

    <!-- Modal untuk CRUD Operations -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Modal Title</h3>
                <span class="modal-close" id="modalClose">&times;</span>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Konten modal akan diisi oleh JavaScript -->
            </div>
            <div class="modal-footer" id="modalFooter">
                <!-- Footer modal akan diisi oleh JavaScript -->
            </div>
        </div>
    </div>

    <script src="../script.js"></script>
    <script>
    // Fungsi untuk View Detail Reservasi (jika diperlukan)
    function viewReservationDetail(bookingCode) {
        alert('Detail reservasi: ' + bookingCode);
        // Bisa diimplementasikan modal atau redirect ke halaman detail
    }
    window.viewReservationDetail = viewReservationDetail;
    </script>
</body>
</html>

