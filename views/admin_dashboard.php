<?php
$pageTitle = 'ReservaStay - Dashboard Admin';
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
    <!-- Dashboard Page -->
    <section id="dashboard" class="page">
        <div class="container">
            <div class="page">
                <div class="dashboard-header">
                    <h2 class="dashboard-title">Dashboard Admin</h2>
                    <div>
                        <button id="refreshDataBtn" class="btn btn-primary">Refresh Data</button>
                    </div>
                </div>
                
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-value" id="totalBookings">0</div>
                        <div class="stat-label">Total Reservasi</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value" id="activeBookings">0</div>
                        <div class="stat-label">Reservasi Aktif</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value" id="occupancyRate">0%</div>
                        <div class="stat-label">Tingkat Okupansi</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value" id="cancellationRate">0%</div>
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
                                <!-- Data akan diisi oleh JavaScript atau PHP -->
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data reservasi</td>
                                </tr>
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
                                <!-- Data akan diisi oleh JavaScript atau PHP -->
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data pengguna</td>
                                </tr>
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
                                <!-- Data akan diisi oleh JavaScript atau PHP -->
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data artikel</td>
                                </tr>
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
        loadDashboardData();
        loadDummyArticles();
    });
    </script>
</body>
</html>

