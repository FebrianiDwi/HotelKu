<?php
$pageTitle = 'ReservaStay - Profil';
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
                        <a href="../functions/logout.php" class="btn btn-danger">Keluar</a>
                    </div>
                </div>
                
                <div class="card">
                    <div class="form-row">
                        <div class="form-group">
                            <h3 style="margin-bottom: 20px;">Informasi Pribadi</h3>
                            <p><strong>Nama:</strong> <span id="profileName">-</span></p>
                            <p><strong>Email:</strong> <span id="profileEmail">-</span></p>
                            <p><strong>Telepon:</strong> <span id="profilePhone">-</span></p>
                            <p><strong>Member sejak:</strong> <span id="profileJoinDate">-</span></p>
                        </div>
                        <div class="form-group">
                            <h3 style="margin-bottom: 20px;">Statistik Reservasi</h3>
                            <p><strong>Total Reservasi:</strong> <span id="totalReservations">0</span></p>
                            <p><strong>Reservasi Aktif:</strong> <span id="activeReservations">0</span></p>
                            <p><strong>Reservasi Selesai:</strong> <span id="completedReservations">0</span></p>
                            <p><strong>Pembatalan:</strong> <span id="cancelledReservations">0</span></p>
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
                            <!-- Data akan diisi oleh JavaScript atau PHP -->
                            <tr>
                                <td colspan="6" class="text-center">Belum ada reservasi</td>
                            </tr>
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
    // Inisialisasi Aplikasi
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi komponen
        loadProfileData();
    });
    </script>
</body>
</html>

