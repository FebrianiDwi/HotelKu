<?php
$pageTitle = 'ReservaStay - Reservasi';
$selectedRoomType = isset($_GET['room_type']) ? $_GET['room_type'] : '';
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
    <!-- Main Content Container -->
    <main id="mainContent">
        <!-- Reservation Page -->
        <section id="reservation" class="page">
            <div class="container">
                <div class="page">
                    <div class="form-container">
                        <h2 class="form-title">Formulir Reservasi</h2>
                        <form id="reservationForm" action="../functions/reservasi_process.php" method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="roomType" class="form-label">Tipe Kamar</label>
                                    <select id="roomType" name="room_type" class="form-select" required>
                                        <option value="">Pilih Tipe Kamar</option>
                                        <option value="standard" <?php echo ($selectedRoomType == 'standard') ? 'selected' : ''; ?>>Standard Room</option>
                                        <option value="deluxe" <?php echo ($selectedRoomType == 'deluxe') ? 'selected' : ''; ?>>Deluxe Room</option>
                                        <option value="suite" <?php echo ($selectedRoomType == 'suite') ? 'selected' : ''; ?>>Suite Room</option>
                                        <option value="executive" <?php echo ($selectedRoomType == 'executive') ? 'selected' : ''; ?>>Executive Room</option>
                                    </select>
                                    <div class="form-feedback" id="roomTypeFeedback"></div>
                                </div>
                                <div class="form-group">
                                    <label for="roomCount" class="form-label">Jumlah Kamar</label>
                                    <input type="number" id="roomCount" name="room_count" class="form-input" min="1" max="10" value="1" required>
                                    <div class="form-feedback" id="roomCountFeedback"></div>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="checkin" class="form-label">Tanggal Check-in</label>
                                    <input type="date" id="checkin" name="checkin_date" class="form-input" required>
                                    <div class="form-feedback" id="checkinFeedback"></div>
                                </div>
                                <div class="form-group">
                                    <label for="checkout" class="form-label">Tanggal Check-out</label>
                                    <input type="date" id="checkout" name="checkout_date" class="form-input" required>
                                    <div class="form-feedback" id="checkoutFeedback"></div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="fullName" class="form-label">Nama Lengkap</label>
                                <input type="text" id="fullName" name="guest_name" class="form-input" placeholder="Masukkan nama lengkap" required>
                                <div class="form-feedback" id="fullNameFeedback"></div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="guest_email" class="form-input" placeholder="email@contoh.com" required>
                                    <div class="form-feedback" id="emailFeedback"></div>
                                </div>
                                <div class="form-group">
                                    <label for="phone" class="form-label">Telepon</label>
                                    <input type="tel" id="phone" name="guest_phone" class="form-input" placeholder="08123456789" required>
                                    <div class="form-feedback" id="phoneFeedback"></div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="specialRequests" class="form-label">Permintaan Khusus (Opsional)</label>
                                <textarea id="specialRequests" name="special_requests" class="form-textarea" rows="4" placeholder="Masukkan permintaan khusus seperti makanan, aksesibilitas, dll."></textarea>
                            </div>
                            
                            <div class="form-group">
                                <div id="priceSummary" class="card" style="background-color: var(--primary-light);">
                                    <h3 style="margin-bottom: 15px;">Ringkasan Harga</h3>
                                    <div id="priceDetails" style="line-height: 1.8;">
                                        <div>Pilih tipe kamar untuk melihat harga</div>
                                    </div>
                                    <div id="totalPrice" style="font-size: 1.5rem; font-weight: 700; margin-top: 15px; color: var(--primary-color);"></div>
                                </div>
                            </div>
                            
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem;">Buat Reservasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

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
        
        // Set tanggal minimum untuk input tanggal
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('checkin').min = today;
        document.getElementById('checkout').min = today;
    });
    </script>
</body>
</html>

