<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/CancellationModel.php';
require_once __DIR__ . '/../models/ReservationModel.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'cancel_reservasi.php';
    header('Location: login_register.php');
    exit;
}

$pageTitle = 'ReservaStay - Pembatalan Reservasi';

$cancellationModel = new CancellationModel($conn);
$cancellations = $cancellationModel->getUserCancellations($_SESSION['user_id']);

$reservationModel = new ReservationModel($conn);
$userReservations = $reservationModel->getUserReservations($_SESSION['user_id']);
$availableReservations = array_filter($userReservations, function($res) {
    return $res['status'] != 'cancelled' && $res['status'] != 'checked_out' && $res['status'] != 'completed';
});
$availableReservations = array_values($availableReservations);

$success = isset($_GET['success']) && $_GET['success'] == '1';
$bookingCode = isset($_GET['booking_code']) ? $_GET['booking_code'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

function formatCancellationStatus($status) {
    $statusMap = [
        'pending' => 'Diproses',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak'
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
    <!-- Cancellation Page -->
    <section id="cancellation" class="page">
        <div class="container">
            <div class="page">
                <div class="form-container">
                    <h2 class="form-title">Pengajuan Pembatalan Reservasi</h2>
                    <p class="text-center" style="margin-bottom: 30px; color: var(--gray-dark);">Ajukan pembatalan reservasi beserta alasan pembatalan.</p>
                    
                    <?php if ($success && $bookingCode): ?>
                    <div class="alert alert-success" style="padding: 15px; background-color: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px;">
                        <strong>Pengajuan Pembatalan Berhasil!</strong><br>
                        Kode Booking: <strong><?php echo htmlspecialchars($bookingCode); ?></strong><br>
                        Pengajuan Anda sedang diproses. Kami akan menghubungi Anda dalam 1-2 hari kerja.
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                    <div class="alert alert-danger" style="padding: 15px; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
                        <?php
                        $errorMessages = [
                            'empty_booking_code' => 'Kode booking wajib diisi',
                            'empty_reason' => 'Alasan pembatalan wajib dipilih',
                            'invalid_reason' => 'Alasan pembatalan tidak valid',
                            'booking_not_found' => 'Kode booking tidak ditemukan',
                            'unauthorized' => 'Anda tidak memiliki akses untuk membatalkan reservasi ini',
                            'already_cancelled' => 'Reservasi ini sudah dibatalkan',
                            'already_submitted' => 'Sudah ada pengajuan pembatalan yang sedang diproses untuk reservasi ini',
                            'create_failed' => 'Gagal membuat pengajuan pembatalan, coba lagi',
                            'invalid_method' => 'Metode request tidak valid'
                        ];
                        echo htmlspecialchars($errorMessages[$error] ?? 'Terjadi kesalahan');
                        ?>
                    </div>
                    <?php endif; ?>
                    
                    <form id="cancellationForm" action="../controllers/pembatalan_process.php" method="POST">
                        <div class="form-group">
                            <label for="cancellationBookingCode" class="form-label">Kode Booking</label>
                            <?php if (!empty($availableReservations)): ?>
                                <select id="cancellationBookingCode" name="booking_code" class="form-select" required>
                                    <option value="">Pilih Kode Booking</option>
                                    <?php foreach ($availableReservations as $res): ?>
                                        <option value="<?php echo htmlspecialchars($res['booking_code'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php echo htmlspecialchars($res['booking_code']); ?> - 
                                            <?php echo htmlspecialchars($res['type_name'] ?? 'N/A'); ?> - 
                                            Check-in: <?php echo date('d/m/Y', strtotime($res['checkin_date'])); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small style="color: #666; display: block; margin-top: 5px;">Pilih reservasi yang ingin dibatalkan</small>
                            <?php else: ?>
                                <input type="text" id="cancellationBookingCode" name="booking_code" class="form-input" placeholder="Masukkan kode booking yang akan dibatalkan" required>
                                <small style="color: #d32f2f; display: block; margin-top: 5px;">Anda tidak memiliki reservasi yang dapat dibatalkan</small>
                            <?php endif; ?>
                            <div class="form-feedback" id="cancellationBookingCodeFeedback"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="cancellationReason" class="form-label">Alasan Pembatalan</label>
                            <select id="cancellationReason" name="reason" class="form-select" required>
                                <option value="">Pilih alasan pembatalan</option>
                                <option value="change_plans">Perubahan rencana</option>
                                <option value="found_cheaper">Menemukan harga lebih murah</option>
                                <option value="emergency">Keadaan darurat</option>
                                <option value="dissatisfied">Tidak puas dengan layanan</option>
                                <option value="other">Lainnya</option>
                            </select>
                            <div class="form-feedback" id="cancellationReasonFeedback"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="cancellationDetails" class="form-label">Detail Tambahan (Opsional)</label>
                            <textarea id="cancellationDetails" name="details" class="form-textarea" rows="4" placeholder="Jelaskan lebih detail alasan pembatalan"></textarea>
                        </div>
                        
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-danger" style="padding: 15px 40px; font-size: 1.1rem;">Ajukan Pembatalan</button>
                        </div>
                    </form>
                    
                    <div id="cancellationResult" class="card hidden" style="margin-top: 30px;">
                        <h3 id="cancellationResultTitle" style="margin-bottom: 15px;"></h3>
                        <div id="cancellationResultContent"></div>
                    </div>
                    
                    <div class="card" style="margin-top: 40px;">
                        <h3 style="margin-bottom: 15px;">Status Pengajuan Pembatalan</h3>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Kode Booking</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                        <th>Tanggapan</th>
                                    </tr>
                                </thead>
                                <tbody id="cancellationStatusTable">
                                    <?php if (empty($cancellations)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada pengajuan pembatalan</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($cancellations as $cancel): 
                                            $submissionDate = date('d F Y', strtotime($cancel['submission_date'] ?? $cancel['created_at']));
                                            $reasonText = $cancellationModel->getCancellationReasonText($cancel['reason']);
                                            $statusText = formatCancellationStatus($cancel['status']);
                                            $statusClass = $cancel['status'] == 'approved' ? 'status-confirmed' : ($cancel['status'] == 'rejected' ? 'status-pending' : 'status-pending');
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($cancel['booking_code']); ?></td>
                                            <td><?php echo $submissionDate; ?></td>
                                            <td><?php echo htmlspecialchars($reasonText); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo $statusClass; ?>">
                                                    <?php echo $statusText; ?>
                                                </span>
                                            </td>
                                            <td><?php echo !empty($cancel['admin_response']) ? htmlspecialchars($cancel['admin_response']) : '-'; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        initForms();
        initModals();
    });
    </script>
</body>
</html>

