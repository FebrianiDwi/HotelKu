<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ReservationModel.php';


// if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//     $_SESSION['redirect_after_login'] = basename($_SERVER['PHP_SELF']);
    
//     echo '<script>alert("Gas masuk ke akun Anda untuk melanjutkan."); window.location.href="login_register.php";</script>';
//     exit;
// }

$pageTitle = 'ReservaStay - Reservasi';
$selectedRoomType = isset($_GET['room_type']) ? $_GET['room_type'] : '';

$reservationModel = new ReservationModel($conn);
$roomTypes = $reservationModel->getRoomTypes();

$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$userEmail = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';

$success = isset($_GET['success']) && $_GET['success'] == '1';
$bookingCode = isset($_GET['booking_code']) ? $_GET['booking_code'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
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
    <!-- Main Content Container -->
    <main id="mainContent">
        <!-- Reservation Page -->
        <section id="reservation" class="page">
            <div class="container">
                <div class="page">
                    <div class="form-container">
                        <h2 class="form-title">Formulir Reservasi</h2>
                        
                        <?php if ($success && $bookingCode): ?>
                        <div class="alert alert-success" style="padding: 15px; background-color: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px;">
                            <strong>Reservasi Berhasil!</strong><br>
                            Kode Booking: <strong><?php echo htmlspecialchars($bookingCode); ?></strong><br>
                            Silakan cek email Anda untuk instruksi lebih lanjut.
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                        <div class="alert alert-danger" style="padding: 15px; background-color: #f8d7da; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
                            <?php
                            $errorMessages = [
                                'empty_fields' => 'Semua field wajib diisi',
                                'invalid_dates' => 'Tanggal check-out harus setelah check-in',
                                'past_date' => 'Tanggal check-in tidak boleh di masa lalu',
                                'invalid_room_type' => 'Tipe kamar tidak valid',
                                'create_failed' => 'Gagal membuat reservasi, coba lagi'
                            ];
                            echo htmlspecialchars($errorMessages[$error] ?? 'Terjadi kesalahan');
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <form id="reservationForm" action="../controllers/reservasi_process.php" method="POST">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="roomType" class="form-label">Tipe Kamar</label>
                                    <select id="roomType" name="room_type" class="form-select" required>
                                        <option value="">Pilih Tipe Kamar</option>
                                        <?php foreach ($roomTypes as $rt): ?>
                                        <option value="<?php echo htmlspecialchars($rt['type_code']); ?>" 
                                                data-price="<?php echo $rt['price_per_night']; ?>"
                                                <?php echo ($selectedRoomType == $rt['type_code']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($rt['type_name']); ?> - Rp <?php echo number_format($rt['price_per_night'], 0, ',', '.'); ?>/malam
                                        </option>
                                        <?php endforeach; ?>
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
                                <input type="text" id="fullName" name="guest_name" class="form-input" placeholder="Masukkan nama lengkap" value="<?php echo htmlspecialchars($userName); ?>" required>
                                <div class="form-feedback" id="fullNameFeedback"></div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="guest_email" class="form-input" placeholder="email@contoh.com" value="<?php echo htmlspecialchars($userEmail); ?>" required>
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
    // Update price summary
    function updatePriceSummary() {
        const roomTypeSelect = document.getElementById('roomType');
        const roomCountInput = document.getElementById('roomCount');
        const checkinInput = document.getElementById('checkin');
        const checkoutInput = document.getElementById('checkout');
        const priceDetails = document.getElementById('priceDetails');
        const totalPrice = document.getElementById('totalPrice');
        
        if (!roomTypeSelect || !priceDetails || !totalPrice) return;
        
        const selectedOption = roomTypeSelect.options[roomTypeSelect.selectedIndex];
        const pricePerNight = selectedOption ? parseFloat(selectedOption.getAttribute('data-price')) : 0;
        const roomCount = parseInt(roomCountInput.value) || 1;
        let nights = 1;
        
        if (checkinInput.value && checkoutInput.value) {
            const checkinDate = new Date(checkinInput.value);
            const checkoutDate = new Date(checkoutInput.value);
            const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
            nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
            if (nights < 1) nights = 1;
        }
        
        if (roomTypeSelect.value && pricePerNight > 0) {
            const total = pricePerNight * roomCount * nights;
            const roomTypeName = selectedOption.textContent.split(' - ')[0];
            
            priceDetails.innerHTML = `
                <div>Tipe Kamar: <strong>${roomTypeName}</strong></div>
                <div>Harga per Malam: <strong>Rp ${pricePerNight.toLocaleString('id-ID')}</strong></div>
                <div>Jumlah Kamar: <strong>${roomCount}</strong></div>
                <div>Jumlah Malam: <strong>${nights}</strong></div>
            `;
            
            totalPrice.textContent = `Total: Rp ${total.toLocaleString('id-ID')}`;
        } else {
            priceDetails.innerHTML = `<div>Pilih tipe kamar untuk melihat harga</div>`;
            totalPrice.textContent = '';
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        initForms();
        initModals();
        
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('checkin').min = today;
        document.getElementById('checkout').min = today;
        
        document.getElementById('roomType').addEventListener('change', updatePriceSummary);
        document.getElementById('roomCount').addEventListener('input', updatePriceSummary);
        document.getElementById('checkin').addEventListener('change', function() {
            const checkin = this.value;
            if (checkin) {
                const nextDay = new Date(checkin);
                nextDay.setDate(nextDay.getDate() + 1);
                document.getElementById('checkout').min = nextDay.toISOString().split('T')[0];
            }
            updatePriceSummary();
        });
        document.getElementById('checkout').addEventListener('change', updatePriceSummary);
        
        updatePriceSummary();
    });
    </script>
</body>
</html>

