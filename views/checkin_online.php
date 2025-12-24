<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ReservationModel.php';

$pageTitle = 'ReservaStay - Check-in Online';

$error = isset($_GET['error']) ? $_GET['error'] : '';
$bookingCode = isset($_GET['booking_code']) ? $_GET['booking_code'] : '';
$checkinStatus = isset($_GET['status']) ? $_GET['status'] : '';
$reservation = null;

if (!empty($bookingCode)) {
    $reservationModel = new ReservationModel($conn);
    $reservation = $reservationModel->getReservationByBookingCode($bookingCode);
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
    <!-- Check-in Page -->
    <section id="checkin" class="page">
        <div class="container">
            <div class="page">
                <div class="form-container">
                    <h2 class="form-title">Check-in Online</h2>
                    <p class="text-center" style="margin-bottom: 30px; color: var(--gray-dark);">Masukkan kode booking Anda untuk melakukan check-in online sebelum kedatangan.</p>
                    
                    <?php if ($error == 'not_found'): ?>
                        <div class="alert alert-danger" style="padding: 15px; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
                            <strong>Kode booking tidak ditemukan!</strong><br>
                            Kode booking <strong><?php echo htmlspecialchars($bookingCode); ?></strong> tidak ditemukan dalam sistem.<br>
                            Pastikan kode booking yang Anda masukkan benar atau hubungi layanan pelanggan kami.
                        </div>
                    <?php elseif ($error == 'empty_code'): ?>
                        <div class="alert alert-danger" style="padding: 15px; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
                            <strong>Kode booking harus diisi!</strong>
                        </div>
                    <?php endif; ?>
                    
                    <form id="checkinForm" action="../controllers/checkin_process.php" method="POST">
                        <div class="form-group">
                            <label for="bookingCode" class="form-label">Kode Booking</label>
                            <input type="text" id="bookingCode" name="booking_code" class="form-input" placeholder="Masukkan kode booking (contoh: RS2025E6E045)" value="<?php echo htmlspecialchars($bookingCode); ?>" required>
                            <div class="form-feedback" id="bookingCodeFeedback"></div>
                        </div>
                        
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem;">Verifikasi & Check-in</button>
                        </div>
                    </form>
                    
                    <?php if ($reservation && $checkinStatus): ?>
                        <div class="card" style="margin-top: 30px;">
                            <?php if ($checkinStatus == 'success'): ?>
                                <h3 style="margin-bottom: 15px; color: var(--success-color);">✓ Check-in Berhasil!</h3>
                                <p>Check-in online berhasil untuk reservasi berikut:</p>
                                <div style="margin-top: 15px;">
                                    <p><strong>Kode Booking:</strong> <?php echo htmlspecialchars($reservation['booking_code']); ?></p>
                                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($reservation['guest_name']); ?></p>
                                    <p><strong>Tipe Kamar:</strong> <?php echo htmlspecialchars($reservation['type_name'] ?? getRoomTypeName($reservation['type_code'] ?? '')); ?></p>
                                    <p><strong>Check-in:</strong> <?php echo date('d F Y', strtotime($reservation['checkin_date'])); ?></p>
                                    <p><strong>Check-out:</strong> <?php echo date('d F Y', strtotime($reservation['checkout_date'])); ?></p>
                                    <p><strong>Status:</strong> <span class="status-badge status-checked_in"><?php echo formatStatus($reservation['status']); ?></span></p>
                                </div>
                                <div style="margin-top: 20px; padding: 15px; background-color: var(--primary-light); border-radius: var(--border-radius);">
                                    <p><strong>Instruksi Check-in:</strong></p>
                                    <p>1. Datang ke resepsionis dengan menunjukkan kode booking ini</p>
                                    <p>2. Tunjukkan identitas asli (KTP/Paspor)</p>
                                    <p>3. Kamar akan siap pada pukul 14:00 WIB</p>
                                </div>
                            <?php elseif ($checkinStatus == 'already_checked_in'): ?>
                                <h3 style="margin-bottom: 15px; color: var(--info-color);">Check-in Sudah Dilakukan</h3>
                                <p>Reservasi dengan kode booking <strong><?php echo htmlspecialchars($reservation['booking_code']); ?></strong> sudah melakukan check-in.</p>
                                <div style="margin-top: 15px;">
                                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($reservation['guest_name']); ?></p>
                                    <p><strong>Tipe Kamar:</strong> <?php echo htmlspecialchars($reservation['type_name'] ?? getRoomTypeName($reservation['type_code'] ?? '')); ?></p>
                                    <p><strong>Check-in:</strong> <?php echo date('d F Y', strtotime($reservation['checkin_date'])); ?></p>
                                    <p><strong>Check-out:</strong> <?php echo date('d F Y', strtotime($reservation['checkout_date'])); ?></p>
                                    <p><strong>Status:</strong> <span class="status-badge status-<?php echo htmlspecialchars($reservation['status']); ?>"><?php echo formatStatus($reservation['status']); ?></span></p>
                                </div>
                            <?php elseif ($checkinStatus == 'too_early'): ?>
                                <h3 style="margin-bottom: 15px; color: var(--warning-color);">Check-in Belum Tersedia</h3>
                                <p>Reservasi ditemukan, namun check-in online hanya dapat dilakukan maksimal 1 hari sebelum tanggal check-in.</p>
                                <div style="margin-top: 15px;">
                                    <p><strong>Kode Booking:</strong> <?php echo htmlspecialchars($reservation['booking_code']); ?></p>
                                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($reservation['guest_name']); ?></p>
                                    <p><strong>Tanggal Check-in:</strong> <?php echo date('d F Y', strtotime($reservation['checkin_date'])); ?></p>
                                    <p><strong>Status:</strong> <span class="status-badge status-<?php echo htmlspecialchars($reservation['status']); ?>"><?php echo formatStatus($reservation['status']); ?></span></p>
                                </div>
                                <p style="margin-top: 15px; color: var(--gray-dark);">Silakan kembali pada tanggal yang lebih dekat dengan tanggal check-in Anda.</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div id="checkinResult" class="card hidden" style="margin-top: 30px;">
                        <h3 id="checkinResultTitle" style="margin-bottom: 15px;"></h3>
                        <div id="checkinResultContent"></div>
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

</body>
</html>

