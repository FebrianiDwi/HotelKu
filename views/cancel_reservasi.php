<?php
$pageTitle = 'ReservaStay - Pembatalan Reservasi';
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
                    
                    <form id="cancellationForm" action="../functions/pembatalan_process.php" method="POST">
                        <div class="form-group">
                            <label for="cancellationBookingCode" class="form-label">Kode Booking</label>
                            <input type="text" id="cancellationBookingCode" name="booking_code" class="form-input" placeholder="Masukkan kode booking yang akan dibatalkan" required>
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
                                    <!-- Data akan diisi oleh JavaScript atau PHP -->
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada pengajuan pembatalan</td>
                                    </tr>
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
    // Inisialisasi Aplikasi
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi komponen
        loadDashboardData();
    });
    </script>
</body>
</html>

