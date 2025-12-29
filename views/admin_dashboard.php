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

// Include data preparation
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
                                        <td><?php echo htmlspecialchars(trim(($activity['first_name'] ?? '') . ' ' . ($activity['last_name'] ?? ''))); ?><br>
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
    // Data untuk chart dari PHP
    const monthlyChartData = {
        labels: <?php echo json_encode($monthLabels); ?>,
        data: <?php echo json_encode($monthlyCounts); ?>
    };
    
    const roomTypeChartData = {
        labels: <?php echo json_encode($roomTypeLabels); ?>,
        data: <?php echo json_encode($roomTypeData); ?>,
        colors: <?php echo json_encode(array_slice($roomTypeColors, 0, count($roomTypeLabels))); ?>
    };
    
    // Inisialisasi Aplikasi
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi komponen
        initModals();
        
        // Inisialisasi Chart
        initializeCharts();
    });
    
    // Function to initialize charts
    function initializeCharts() {
        // Chart 1: Reservasi per Bulan (Line Chart)
        const monthlyCtx = document.getElementById('monthlyReservationsChart');
        if (monthlyCtx && typeof Chart !== 'undefined') {
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: monthlyChartData.labels,
                    datasets: [{
                        label: 'Jumlah Reservasi',
                        data: monthlyChartData.data,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }
        
        // Chart 2: Distribusi Tipe Kamar (Doughnut Chart)
        const roomTypeCtx = document.getElementById('roomTypeDistributionChart');
        if (roomTypeCtx && typeof Chart !== 'undefined') {
            new Chart(roomTypeCtx, {
                type: 'doughnut',
                data: {
                    labels: roomTypeChartData.labels,
                    datasets: [{
                        label: 'Jumlah Reservasi',
                        data: roomTypeChartData.data,
                        backgroundColor: roomTypeChartData.colors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    label += context.parsed + ' reservasi (' + percentage + '%)';
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    }
    
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
    
    // Fungsi untuk Tambah Artikel
    document.getElementById('addArticleBtn')?.addEventListener('click', function() {
        const modalContent = `
            <form id="addArticleForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="articleTitle" class="form-label">Judul Artikel</label>
                    <input type="text" id="articleTitle" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="articleExcerpt" class="form-label">Ringkasan</label>
                    <textarea id="articleExcerpt" class="form-input" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="articleContent" class="form-label">Konten</label>
                    <textarea id="articleContent" class="form-input" rows="8" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="articleImage" class="form-label">Gambar</label>
                    <input type="file" id="articleImage" name="image" accept="image/*" class="form-input">
                    <small style="color: var(--gray-dark);">Format: JPEG, PNG, GIF, WebP. Maksimal 5MB</small>
                </div>
                
                <div class="form-group">
                    <label for="articleImageUrl" class="form-label">Atau URL Gambar</label>
                    <input type="text" id="articleImageUrl" class="form-input" placeholder="https://example.com/image.jpg">
                </div>
                
                <div class="form-group">
                    <label for="articleStatus" class="form-label">Status</label>
                    <select id="articleStatus" class="form-select" required>
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </form>
        `;
        
        if (typeof showModal === 'function') {
            showModal(
                'Tambah Artikel',
                modalContent,
                [
                    { 
                        text: 'Simpan', 
                        class: 'btn-primary', 
                        action: function() {
                            const title = document.getElementById('articleTitle').value.trim();
                            const content = document.getElementById('articleContent').value.trim();
                            
                            if (!title || !content) {
                                alert('Judul dan konten wajib diisi!');
                                return;
                            }
                            
                            const formData = new FormData();
                            formData.append('action', 'create');
                            formData.append('title', title);
                            formData.append('excerpt', document.getElementById('articleExcerpt').value.trim());
                            formData.append('content', content);
                            formData.append('status', document.getElementById('articleStatus').value);
                            
                            if (document.getElementById('articleImage').files.length > 0) {
                                formData.append('image', document.getElementById('articleImage').files[0]);
                            } else if (document.getElementById('articleImageUrl').value.trim()) {
                                formData.append('image_url', document.getElementById('articleImageUrl').value.trim());
                            }
                            
                            fetch('../controllers/blog_process.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    alert('Artikel berhasil ditambahkan');
                                    location.reload();
                                } else {
                                    alert('Error: ' + (data.error || 'Gagal menambahkan artikel'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat menambahkan artikel: ' + error.message);
                            });
                        }
                    },
                    { 
                        text: 'Batal', 
                        class: 'btn-secondary', 
                        action: function() {
                            if (typeof closeModal === 'function') closeModal();
                        }
                    }
                ]
            );
        }
    });
    
    // Fungsi untuk Edit Artikel
    function editArticle(articleId) {
        fetch('../controllers/get_blog_api.php?id=' + articleId)
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Error: ' + (data.error || 'Gagal memuat data artikel'));
                    return;
                }
                
                const article = data.post;
                
                const modalContent = `
                    <form id="editArticleForm" enctype="multipart/form-data">
                        <input type="hidden" id="editArticleId" value="${article.id}">
                        <div class="form-group">
                            <label for="editArticleTitle" class="form-label">Judul Artikel</label>
                            <input type="text" id="editArticleTitle" class="form-input" value="${article.title}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editArticleExcerpt" class="form-label">Ringkasan</label>
                            <textarea id="editArticleExcerpt" class="form-input" rows="3">${article.excerpt || ''}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="editArticleContent" class="form-label">Konten</label>
                            <textarea id="editArticleContent" class="form-input" rows="8" required>${article.content || ''}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Gambar Saat Ini</label>
                            ${article.image_url ? '<img src="../' + article.image_url + '" style="max-width: 200px; margin-bottom: 10px; display: block;"><br>' : '<p>Tidak ada gambar</p>'}
                            <label>
                                <input type="radio" name="update_image" value="keep" checked> Pertahankan gambar saat ini
                            </label><br>
                            <label>
                                <input type="radio" name="update_image" value="upload"> Upload gambar baru
                            </label>
                            <input type="file" id="editArticleImage" name="image" accept="image/*" class="form-input" style="margin-top: 10px; display: none;">
                            <label>
                                <input type="radio" name="update_image" value="url"> Gunakan URL gambar
                            </label>
                            <input type="text" id="editArticleImageUrl" class="form-input" placeholder="https://example.com/image.jpg" style="margin-top: 10px; display: none;">
                            <label>
                                <input type="radio" name="update_image" value="remove"> Hapus gambar
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label for="editArticleStatus" class="form-label">Status</label>
                            <select id="editArticleStatus" class="form-select" required>
                                <option value="draft" ${article.status === 'draft' ? 'selected' : ''}>Draft</option>
                                <option value="published" ${article.status === 'published' ? 'selected' : ''}>Published</option>
                                <option value="archived" ${article.status === 'archived' ? 'selected' : ''}>Archived</option>
                            </select>
                        </div>
                    </form>
                `;
                
                if (typeof showModal === 'function') {
                    showModal(
                        'Edit Artikel',
                        modalContent,
                        [
                            { 
                                text: 'Simpan Perubahan', 
                                class: 'btn-primary', 
                                action: function() {
                                    const formData = new FormData();
                                    formData.append('action', 'update');
                                    formData.append('id', document.getElementById('editArticleId').value);
                                    formData.append('title', document.getElementById('editArticleTitle').value);
                                    formData.append('excerpt', document.getElementById('editArticleExcerpt').value);
                                    formData.append('content', document.getElementById('editArticleContent').value);
                                    formData.append('status', document.getElementById('editArticleStatus').value);
                                    
                                    const updateImage = document.querySelector('input[name="update_image"]:checked').value;
                                    formData.append('update_image', updateImage);
                                    
                                    if (updateImage === 'upload' && document.getElementById('editArticleImage').files.length > 0) {
                                        formData.append('image', document.getElementById('editArticleImage').files[0]);
                                    } else if (updateImage === 'url' && document.getElementById('editArticleImageUrl').value) {
                                        formData.append('image_url', document.getElementById('editArticleImageUrl').value);
                                    }
                                    
                                    fetch('../controllers/blog_process.php', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert('Artikel berhasil diperbarui');
                                            location.reload();
                                        } else {
                                            alert('Error: ' + (data.error || 'Gagal memperbarui artikel'));
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Terjadi kesalahan saat memperbarui artikel');
                                    });
                                }
                            },
                            { 
                                text: 'Batal', 
                                class: 'btn-secondary', 
                                action: function() {
                                    if (typeof closeModal === 'function') closeModal();
                                }
                            }
                        ]
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat data artikel');
            });
    }
    
    // Fungsi untuk Delete Artikel
    function deleteArticle(articleId) {
        if (!confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
            return;
        }
        
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', articleId);
        
        fetch('../controllers/blog_process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Artikel berhasil dihapus');
                location.reload();
            } else {
                alert('Error: ' + (data.error || 'Gagal menghapus artikel'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus artikel');
        });
    }
    
    // Fungsi untuk Tambah Room Type
    document.getElementById('addRoomTypeBtn')?.addEventListener('click', function() {
        const modalContent = `
            <form id="addRoomTypeForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="roomTypeCode" class="form-label">Kode Tipe</label>
                    <input type="text" id="roomTypeCode" class="form-input" required placeholder="standard, deluxe, suite, etc">
                </div>
                
                <div class="form-group">
                    <label for="roomTypeName" class="form-label">Nama Tipe Kamar</label>
                    <input type="text" id="roomTypeName" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="roomTypeDescription" class="form-label">Deskripsi</label>
                    <textarea id="roomTypeDescription" class="form-input" rows="3"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="roomTypePrice" class="form-label">Harga per Malam (Rp)</label>
                        <input type="number" id="roomTypePrice" class="form-input" min="0" step="1000" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="roomTypeOccupancy" class="form-label">Max Occupancy</label>
                        <input type="number" id="roomTypeOccupancy" class="form-input" min="1" max="10" value="2" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="roomTypeFeatures" class="form-label">Fasilitas (pisahkan dengan koma)</label>
                    <input type="text" id="roomTypeFeatures" class="form-input" placeholder="WiFi Gratis, AC, TV Kabel, Sarapan">
                </div>
                
                <div class="form-group">
                    <label for="roomTypeImage" class="form-label">Gambar</label>
                    <input type="file" id="roomTypeImage" name="image" accept="image/*" class="form-input">
                    <small style="color: var(--gray-dark);">Format: JPEG, PNG, GIF, WebP. Maksimal 5MB</small>
                </div>
                
                <div class="form-group">
                    <label for="roomTypeImageUrl" class="form-label">Atau URL Gambar</label>
                    <input type="text" id="roomTypeImageUrl" class="form-input" placeholder="https://example.com/image.jpg">
                </div>
            </form>
        `;
        
        if (typeof showModal === 'function') {
            showModal(
                'Tambah Tipe Kamar',
                modalContent,
                [
                    { 
                        text: 'Simpan', 
                        class: 'btn-primary', 
                        action: function() {
                            const formData = new FormData();
                            formData.append('action', 'create');
                            formData.append('type_code', document.getElementById('roomTypeCode').value);
                            formData.append('type_name', document.getElementById('roomTypeName').value);
                            formData.append('description', document.getElementById('roomTypeDescription').value);
                            formData.append('price_per_night', document.getElementById('roomTypePrice').value);
                            formData.append('max_occupancy', document.getElementById('roomTypeOccupancy').value);
                            formData.append('features', document.getElementById('roomTypeFeatures').value);
                            
                            if (document.getElementById('roomTypeImage').files.length > 0) {
                                formData.append('image', document.getElementById('roomTypeImage').files[0]);
                            } else if (document.getElementById('roomTypeImageUrl').value) {
                                formData.append('image_url', document.getElementById('roomTypeImageUrl').value);
                            }
                            
                            fetch('../controllers/room_type_process.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Tipe kamar berhasil ditambahkan');
                                    location.reload();
                                } else {
                                    alert('Error: ' + (data.error || 'Gagal menambahkan tipe kamar'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat menambahkan tipe kamar');
                            });
                        }
                    },
                    { 
                        text: 'Batal', 
                        class: 'btn-secondary', 
                        action: function() {
                            if (typeof closeModal === 'function') closeModal();
                        }
                    }
                ]
            );
        }
    });
    
    // Fungsi untuk Edit Room Type
    function editRoomType(id) {
        fetch('../controllers/get_room_type_api.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Error: ' + (data.error || 'Gagal memuat data tipe kamar'));
                    return;
                }
                
                const rt = data.room_type;
                
                const modalContent = `
                    <form id="editRoomTypeForm" enctype="multipart/form-data">
                        <input type="hidden" id="editRoomTypeId" value="${rt.id}">
                        <div class="form-group">
                            <label for="editRoomTypeCode" class="form-label">Kode Tipe</label>
                            <input type="text" id="editRoomTypeCode" class="form-input" value="${rt.type_code}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editRoomTypeName" class="form-label">Nama Tipe Kamar</label>
                            <input type="text" id="editRoomTypeName" class="form-input" value="${rt.type_name}" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="editRoomTypeDescription" class="form-label">Deskripsi</label>
                            <textarea id="editRoomTypeDescription" class="form-input" rows="3">${rt.description || ''}</textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="editRoomTypePrice" class="form-label">Harga per Malam (Rp)</label>
                                <input type="number" id="editRoomTypePrice" class="form-input" value="${rt.price_per_night}" min="0" step="1000" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editRoomTypeOccupancy" class="form-label">Max Occupancy</label>
                                <input type="number" id="editRoomTypeOccupancy" class="form-input" value="${rt.max_occupancy}" min="1" max="10" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="editRoomTypeFeatures" class="form-label">Fasilitas</label>
                            <input type="text" id="editRoomTypeFeatures" class="form-input" value="${rt.features || ''}">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Gambar Saat Ini</label>
                            ${rt.image_url ? '<img src="../' + rt.image_url + '" style="max-width: 200px; margin-bottom: 10px; display: block;"><br>' : '<p>Tidak ada gambar</p>'}
                            <label><input type="radio" name="update_room_image" value="keep" checked> Pertahankan gambar</label><br>
                            <label><input type="radio" name="update_room_image" value="upload"> Upload baru</label>
                            <input type="file" id="editRoomTypeImage" name="image" accept="image/*" class="form-input" style="margin-top: 10px; display: none;">
                            <label><input type="radio" name="update_room_image" value="url"> URL gambar</label>
                            <input type="text" id="editRoomTypeImageUrl" class="form-input" style="margin-top: 10px; display: none;">
                            <label><input type="radio" name="update_room_image" value="remove"> Hapus gambar</label>
                        </div>
                        
                        <div class="form-group">
                            <label for="editRoomTypeStatus" class="form-label">Status</label>
                            <select id="editRoomTypeStatus" class="form-select" required>
                                <option value="active" ${rt.status === 'active' ? 'selected' : ''}>Aktif</option>
                                <option value="inactive" ${rt.status === 'inactive' ? 'selected' : ''}>Nonaktif</option>
                            </select>
                        </div>
                    </form>
                `;
                
                // Setup room image update radio buttons
                setTimeout(function() {
                    const radios = document.querySelectorAll('input[name="update_room_image"]');
                    if (radios.length > 0) {
                        radios.forEach(radio => {
                            radio.addEventListener('change', function() {
                                const editImage = document.getElementById('editRoomTypeImage');
                                const editImageUrl = document.getElementById('editRoomTypeImageUrl');
                                if (editImage) editImage.style.display = this.value === 'upload' ? 'block' : 'none';
                                if (editImageUrl) editImageUrl.style.display = this.value === 'url' ? 'block' : 'none';
                            });
                        });
                    }
                }, 100);
                
                if (typeof showModal === 'function') {
                    showModal(
                        'Edit Tipe Kamar',
                        modalContent,
                        [
                            { 
                                text: 'Simpan Perubahan', 
                                class: 'btn-primary', 
                                action: function() {
                                    const formData = new FormData();
                                    formData.append('action', 'update');
                                    formData.append('id', document.getElementById('editRoomTypeId').value);
                                    formData.append('type_code', document.getElementById('editRoomTypeCode').value);
                                    formData.append('type_name', document.getElementById('editRoomTypeName').value);
                                    formData.append('description', document.getElementById('editRoomTypeDescription').value);
                                    formData.append('price_per_night', document.getElementById('editRoomTypePrice').value);
                                    formData.append('max_occupancy', document.getElementById('editRoomTypeOccupancy').value);
                                    formData.append('features', document.getElementById('editRoomTypeFeatures').value);
                                    formData.append('status', document.getElementById('editRoomTypeStatus').value);
                                    
                                    const updateImage = document.querySelector('input[name="update_room_image"]:checked').value;
                                    formData.append('update_image', updateImage);
                                    
                                    if (updateImage === 'upload' && document.getElementById('editRoomTypeImage').files.length > 0) {
                                        formData.append('image', document.getElementById('editRoomTypeImage').files[0]);
                                    } else if (updateImage === 'url' && document.getElementById('editRoomTypeImageUrl').value) {
                                        formData.append('image_url', document.getElementById('editRoomTypeImageUrl').value);
                                    }
                                    
                                    fetch('../controllers/room_type_process.php', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert('Tipe kamar berhasil diperbarui');
                                            location.reload();
                                        } else {
                                            alert('Error: ' + (data.error || 'Gagal memperbarui tipe kamar'));
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Terjadi kesalahan saat memperbarui tipe kamar');
                                    });
                                }
                            },
                            { 
                                text: 'Batal', 
                                class: 'btn-secondary', 
                                action: function() {
                                    if (typeof closeModal === 'function') closeModal();
                                }
                            }
                        ]
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat data tipe kamar');
            });
    }
    
    // Fungsi untuk Delete Room Type
    function deleteRoomType(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus tipe kamar ini? Tipe kamar yang sudah digunakan dalam reservasi tidak dapat dihapus.')) {
            return;
        }
        
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);
        
        fetch('../controllers/room_type_process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Tipe kamar berhasil dihapus');
                location.reload();
            } else {
                alert('Error: ' + (data.error || 'Gagal menghapus tipe kamar'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus tipe kamar');
        });
    }
    
    window.editReservation = editReservation;
    window.deleteReservation = deleteReservation;
    window.editUser = editUser;
    window.deleteUser = deleteUser;
    window.editArticle = editArticle;
    window.deleteArticle = deleteArticle;
    window.editRoomType = editRoomType;
    window.deleteRoomType = deleteRoomType;
    </script>
</body>
</html>

