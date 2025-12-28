<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ReservationModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/BlogModel.php';

$pageTitle = 'ReservaStay - Dashboard Admin';

$reservationModel = new ReservationModel($conn);
$userModel = new UserModel($conn);
$blogModel = new BlogModel($conn);
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
$allReservationsQuery = "SELECT r.*, rt.type_name, rt.type_code FROM reservations r 
                         LEFT JOIN room_types rt ON r.room_type_id = rt.id 
                         ORDER BY r.created_at DESC LIMIT 50";
$allReservationsResult = mysqli_query($conn, $allReservationsQuery);
$allReservations = [];
while ($row = mysqli_fetch_assoc($allReservationsResult)) {
    $allReservations[] = $row;
}

$allUsersQuery = "SELECT id, first_name, last_name, email, phone, status FROM users ORDER BY created_at DESC LIMIT 50";
$allUsersResult = mysqli_query($conn, $allUsersQuery);
$allUsers = [];
while ($row = mysqli_fetch_assoc($allUsersResult)) {
    $allUsers[] = $row;
}

$allBlogPosts = $blogModel->getAllPosts();

$pendingCancellations = $cancellationModel->getPendingCancellations();

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
    <section id="dashboard" class="page">
        <div class="container">
            <div class="page">
                <div class="dashboard-header">
                    <h2 class="dashboard-title">Dashboard Admin</h2>
                    <div>
                        <a href="admin_dashboard.php" class="btn btn-primary">Refresh Data</a>
                    </div>
                </div>
                
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-value"><?php echo number_format($stats['total_reservations']); ?></div>
                        <div class="stat-label">Total Reservasi</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?php echo number_format($stats['active_reservations']); ?></div>
                        <div class="stat-label">Reservasi Aktif</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $occupancyRate; ?>%</div>
                        <div class="stat-label">Tingkat Okupansi</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $cancellationRate; ?>%</div>
                        <div class="stat-label">Tingkat Pembatalan</div>
                    </div>
                </div>
                
                <div class="card">
                    <h3 style="margin-bottom: 20px;">Grafik & Infografis</h3>
                    <div class="charts-container">
                        <div class="chart-card">
                            <h4 class="chart-title">Reservasi per Bulan</h4>
                            <div class="chart-placeholder">
                                <i class="fas fa-chart-line" style="font-size: 4rem;"></i>
                            </div>
                        </div>
                        <div class="chart-card">
                            <h4 class="chart-title">Distribusi Tipe Kamar</h4>
                            <div class="chart-placeholder">
                                <i class="fas fa-chart-pie" style="font-size: 4rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="dashboard-header">
                        <h3>Manajemen Reservasi</h3>
                        <button id="addReservationBtn" class="btn btn-primary btn-small">Tambah Reservasi</button>
                    </div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Tipe Kamar</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="adminReservationTable">
                                <?php if (empty($allReservations)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data reservasi</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($allReservations as $res): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($res['booking_code']); ?></td>
                                        <td><?php echo htmlspecialchars($res['guest_name']); ?></td>
                                        <td><?php echo htmlspecialchars($res['type_name'] ?? 'N/A'); ?></td>
                                        <td><?php echo date('d M Y', strtotime($res['checkin_date'])); ?></td>
                                        <td><?php echo date('d M Y', strtotime($res['checkout_date'])); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo htmlspecialchars($res['status']); ?>">
                                                <?php echo formatStatus($res['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-secondary btn-small" onclick="editReservation('<?php echo htmlspecialchars($res['booking_code']); ?>')">Edit</button>
                                            <button class="btn btn-danger btn-small" onclick="deleteReservation('<?php echo htmlspecialchars($res['booking_code']); ?>')">Hapus</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card">
                    <div class="dashboard-header">
                        <h3>Manajemen Pengguna</h3>
                        <button id="addUserBtn" class="btn btn-primary btn-small">Tambah Pengguna</button>
                    </div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="adminUserTable">
                                <?php if (empty($allUsers)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data pengguna</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($allUsers as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name'])); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $user['status'] == 'active' ? 'status-confirmed' : 'status-pending'; ?>">
                                                <?php echo $user['status'] == 'active' ? 'Aktif' : 'Nonaktif'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-secondary btn-small" onclick="editUser(<?php echo $user['id']; ?>)">Edit</button>
                                            <button class="btn btn-danger btn-small" onclick="deleteUser(<?php echo $user['id']; ?>)">Hapus</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card">
                    <div class="dashboard-header">
                        <h3>Manajemen Blog/Artikel</h3>
                        <button id="addArticleBtn" class="btn btn-primary btn-small">Tambah Artikel</button>
                    </div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Judul</th>
                                    <th>Penulis</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="adminArticleTable">
                                <?php if (empty($allBlogPosts)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data artikel</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($allBlogPosts as $article): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($article['id']); ?></td>
                                        <td><?php echo htmlspecialchars($article['title']); ?></td>
                                        <td><?php echo htmlspecialchars($article['author_name'] ?? 'Admin'); ?></td>
                                        <td><?php echo !empty($article['published_at']) ? date('d M Y', strtotime($article['published_at'])) : date('d M Y', strtotime($article['created_at'])); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $article['status'] == 'published' ? 'status-confirmed' : 'status-pending'; ?>">
                                                <?php echo ucfirst($article['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-secondary btn-small" onclick="editArticle(<?php echo $article['id']; ?>)">Edit</button>
                                            <button class="btn btn-danger btn-small" onclick="deleteArticle(<?php echo $article['id']; ?>)">Hapus</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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
    // Inisialisasi Aplikasi
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi komponen
        initModals();
    });
    
    // Fungsi placeholder untuk edit/delete (bisa diimplementasikan lebih lanjut)
    function editReservation(bookingCode) {
        alert('Edit reservasi: ' + bookingCode);
    }
    
    function deleteReservation(bookingCode) {
        if (confirm('Apakah Anda yakin ingin menghapus reservasi ' + bookingCode + '?')) {
            alert('Reservasi akan dihapus: ' + bookingCode);
        }
    }
    
    function editUser(userId) {
        alert('Edit user: ' + userId);
    }
    
    function deleteUser(userId) {
        if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
            alert('User akan dihapus: ' + userId);
        }
    }
    
    function editArticle(articleId) {
        alert('Edit artikel: ' + articleId);
    }
    
    function deleteArticle(articleId) {
        if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
            alert('Artikel akan dihapus: ' + articleId);
        }
    }
    
    window.editReservation = editReservation;
    window.deleteReservation = deleteReservation;
    window.editUser = editUser;
    window.deleteUser = deleteUser;
    window.editArticle = editArticle;
    window.deleteArticle = deleteArticle;
    </script>
</body>
</html>

