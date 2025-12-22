<?php
$pageTitle = 'ReservaStay - Check-in Online';
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
    <!-- Check-in Page -->
    <section id="checkin" class="page">
        <div class="container">
            <div class="page">
                <div class="form-container">
                    <h2 class="form-title">Check-in Online</h2>
                    <p class="text-center" style="margin-bottom: 30px; color: var(--gray-dark);">Masukkan kode booking Anda untuk melakukan check-in online sebelum kedatangan.</p>
                    
                    <form id="checkinForm" action="../functions/checkin_process.php" method="POST">
                        <div class="form-group">
                            <label for="bookingCode" class="form-label">Kode Booking</label>
                            <input type="text" id="bookingCode" name="booking_code" class="form-input" placeholder="Masukkan kode booking (contoh: RS2023ABC123)" required>
                            <div class="form-feedback" id="bookingCodeFeedback"></div>
                        </div>
                        
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem;">Verifikasi & Check-in</button>
                        </div>
                    </form>
                    
                    <div id="checkinResult" class="card hidden" style="margin-top: 30px;">
                        <h3 id="checkinResultTitle" style="margin-bottom: 15px;"></h3>
                        <div id="checkinResultContent"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
        initForms();
        initModals();
    });
    </script>
</body>
</html>

