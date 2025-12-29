<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ReservationModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/BlogModel.php';
require_once __DIR__ . '/../models/RoomTypeModel.php';
require_once __DIR__ . '/../models/ActivityLogModel.php';

$pageTitle = 'ReservaStay - Dashboard Admin';

$reservationModel = new ReservationModel($conn);
$userModel = new UserModel($conn);
$blogModel = new BlogModel($conn);
$roomTypeModel = new RoomTypeModel($conn);
$activityLogModel = new ActivityLogModel($conn);

require_once __DIR__ . '/../includes/admin/dashboard_data.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
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

                <?php include '../includes/admin/dashboard_stats.php'; ?>
                <?php include '../includes/admin/dashboard_charts.php'; ?>

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
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="adminUserTable">
                                <?php if (empty($allUsers)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data pengguna</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($allUsers as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                                        <td><?php echo htmlspecialchars(trim($user['first_name'] . ' ' . $user['last_name'])); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo ($user['role'] ?? 'user') == 'admin' ? 'status-confirmed' : (($user['role'] ?? 'user') == 'staff' ? 'status-pending' : ''); ?>">
                                                <?php echo ucfirst($user['role'] ?? 'user'); ?>
                                            </span>
                                        </td>
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
                        <h3>Manajemen Tipe Kamar</h3>
                        <button id="addRoomTypeBtn" class="btn btn-primary btn-small">Tambah Tipe Kamar</button>
                    </div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Harga/Malam</th>
                                    <th>Max Occupancy</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="adminRoomTypeTable">
                                <?php if (empty($allRoomTypes)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data tipe kamar</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($allRoomTypes as $roomType): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($roomType['id']); ?></td>
                                        <td><?php echo htmlspecialchars($roomType['type_code']); ?></td>
                                        <td><?php echo htmlspecialchars($roomType['type_name']); ?></td>
                                        <td>Rp <?php echo number_format($roomType['price_per_night'], 0, ',', '.'); ?></td>
                                        <td><?php echo htmlspecialchars($roomType['max_occupancy']); ?> orang</td>
                                        <td>
                                            <span class="status-badge <?php echo $roomType['status'] == 'active' ? 'status-confirmed' : 'status-pending'; ?>">
                                                <?php echo $roomType['status'] == 'active' ? 'Aktif' : 'Nonaktif'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-secondary btn-small" onclick="editRoomType(<?php echo $roomType['id']; ?>)">Edit</button>
                                            <button class="btn btn-danger btn-small" onclick="deleteRoomType(<?php echo $roomType['id']; ?>)">Hapus</button>
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
                                    <th>Gambar</th>
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
                                        <td colspan="7" class="text-center">Belum ada data artikel</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($allBlogPosts as $article): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($article['id']); ?></td>
                                        <td>
                                            <?php if (!empty($article['image_url'])): ?>
                                                <img src="../<?php echo htmlspecialchars($article['image_url']); ?>" 
                                                     alt="<?php echo htmlspecialchars($article['title']); ?>" 
                                                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <div style="width: 60px; height: 60px; background-color: var(--gray-light); border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-image" style="color: var(--gray-dark);"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
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

                <div class="card">
                    <div class="dashboard-header">
                        <h3>Log Aktivitas</h3>
                    </div>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>User</th>
                                    <th>Aksi</th>
                                    <th>Entity</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody id="adminActivityTable">
                                <?php if (empty($recentActivities)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada aktivitas</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentActivities as $activity): ?>
                                    <tr>
                                        <td><?php echo date('d M Y H:i', strtotime($activity['created_at'])); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars(trim(($activity['first_name'] ?? '') . ' ' . ($activity['last_name'] ?? ''))); ?><br>
                                            <small style="color: var(--gray-dark);"><?php echo htmlspecialchars($activity['email'] ?? ''); ?></small>
                                        </td>
                                        <td>
                                            <span class="status-badge <?php 
                                                echo $activity['action'] === 'create' ? 'status-confirmed' : 
                                                    ($activity['action'] === 'update' ? 'status-pending' : 'status-cancelled'); 
                                            ?>">
                                                <?php echo ucfirst($activity['action']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($activity['entity_type']); ?><?php echo $activity['entity_id'] ? ' #' . $activity['entity_id'] : ''; ?></td>
                                        <td><?php echo htmlspecialchars($activity['description'] ?? '-'); ?></td>
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

    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Modal Title</h3>
                <span class="modal-close" id="modalClose">&times;</span>
            </div>
            <div class="modal-body" id="modalBody"></div>
            <div class="modal-footer" id="modalFooter"></div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <script src="../script.js"></script>
    <?php include '../includes/admin/dashboard_scripts.php'; ?>
    <?php include '../includes/admin/dashboard_crud_scripts.php'; ?>
</body>
</html>
