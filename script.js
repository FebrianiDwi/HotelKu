// Data Dummy untuk Aplikasi
const dummyData = {
    users: [
        { id: 1, firstName: "John", lastName: "Doe", email: "john.doe@example.com", phone: "08123456789", joinDate: "2023-03-15", status: "active" },
        { id: 2, firstName: "Jane", lastName: "Smith", email: "jane.smith@example.com", phone: "08234567890", joinDate: "2023-04-22", status: "active" },
        { id: 3, firstName: "Robert", lastName: "Johnson", email: "robert.j@example.com", phone: "08345678901", joinDate: "2023-05-10", status: "inactive" }
    ],
    reservations: [
        { id: "RS2023ABC123", userId: 1, roomType: "deluxe", roomCount: 1, checkin: "2023-10-15", checkout: "2023-10-18", fullName: "John Doe", email: "john.doe@example.com", phone: "08123456789", status: "confirmed", price: 1800000 },
        { id: "RS2023DEF456", userId: 2, roomType: "suite", roomCount: 2, checkin: "2023-10-20", checkout: "2023-10-25", fullName: "Jane Smith", email: "jane.smith@example.com", phone: "08234567890", status: "pending", price: 4500000 },
        { id: "RS2023GHI789", userId: 1, roomType: "standard", roomCount: 1, checkin: "2023-09-10", checkout: "2023-09-12", fullName: "John Doe", email: "john.doe@example.com", phone: "08123456789", status: "completed", price: 800000 },
        { id: "RS2023JKL012", userId: 3, roomType: "executive", roomCount: 1, checkin: "2023-11-05", checkout: "2023-11-08", fullName: "Robert Johnson", email: "robert.j@example.com", phone: "08345678901", status: "cancelled", price: 2400000 }
    ],
    cancellations: [
        { bookingCode: "RS2023JKL012", submissionDate: "2023-10-28", reason: "change_plans", details: "Perubahan jadwal bisnis", status: "approved", response: "Pembatalan telah disetujui. Dana akan dikembalikan dalam 5-7 hari kerja." }
    ],
    blogPosts: [
        { id: 1, title: "5 Tips Memilih Kamar Hotel yang Tepat", excerpt: "Pelajari cara memilih kamar hotel yang sesuai dengan kebutuhan dan anggaran Anda untuk pengalaman menginap yang lebih nyaman.", image: "bed" },
        { id: 2, title: "Manfaat Check-in Online untuk Perjalanan Bisnis", excerpt: "Tingkatkan efisiensi perjalanan bisnis Anda dengan memanfaatkan fitur check-in online yang menghemat waktu dan tenaga.", image: "calendar" },
        { id: 3, title: "Tren Reservasi Akomodasi 2023", excerpt: "Simak tren terbaru dalam industri reservasi akomodasi dan bagaimana teknologi mengubah cara kita memesan penginapan.", image: "chart" }
    ],
    roomPrices: {
        standard: 500000,
        deluxe: 850000,
        suite: 1500000,
        executive: 1200000
    },
    currentUser: { id: 1, firstName: "John", lastName: "Doe", email: "john.doe@example.com", phone: "08123456789", joinDate: "2023-03-15" }
};

// Fungsi untuk menangani perubahan hash
function handleHashChange() {
    const pageId = window.location.hash.substring(1);
    if (pageId) {
        showPage(pageId);
    }
}

// Fungsi untuk Navigasi
function initNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinksContainer = document.getElementById('navLinks');
    
    // Handle klik pada nav link
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const pageId = this.getAttribute('href').substring(1);
            
            // Update URL hash
            window.location.hash = pageId;
            
            // Update active state
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            // Tampilkan halaman yang dipilih
            showPage(pageId);
            
            // Tutup menu mobile jika terbuka
            navLinksContainer.classList.remove('active');
        });
    });
    
    // Handle tombol menu mobile
    mobileMenuBtn.addEventListener('click', function() {
        navLinksContainer.classList.toggle('active');
    });
    
    // Handle klik di luar menu untuk menutup menu mobile
    document.addEventListener('click', function(e) {
        if (!navLinksContainer.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
            navLinksContainer.classList.remove('active');
        }
    });
}

// Fungsi untuk menampilkan halaman
function showPage(pageId) {
    // Sembunyikan semua halaman
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });
    
    // Tampilkan halaman yang dipilih
    const activePage = document.getElementById(pageId);
    if (activePage) {
        activePage.classList.add('active');
        
        // Scroll ke atas halaman
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Trigger animasi untuk elemen fade-in
        setTimeout(() => {
            triggerAnimations();
        }, 100);
        
        // Update navigasi
        updateNavigation(pageId);
    }
}

// Fungsi untuk update navigasi aktif
function updateNavigation(activePageId) {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${activePageId}`) {
            link.classList.add('active');
        }
    });
}

// Fungsi untuk Inisialisasi Form
function initForms() {
    // Form Reservasi
    const reservationForm = document.getElementById('reservationForm');
    if (reservationForm) {
        reservationForm.addEventListener('submit', handleReservationSubmit);
        
        // Real-time validation dan perhitungan harga
        const roomTypeSelect = document.getElementById('roomType');
        const roomCountInput = document.getElementById('roomCount');
        const checkinInput = document.getElementById('checkin');
        const checkoutInput = document.getElementById('checkout');
        
        // Set tanggal minimum untuk input tanggal
        const today = new Date().toISOString().split('T')[0];
        if (checkinInput) checkinInput.min = today;
        if (checkoutInput) checkoutInput.min = today;
        
        // Event listeners untuk update harga
        if (roomTypeSelect) {
            roomTypeSelect.addEventListener('change', updatePriceSummary);
        }
        if (roomCountInput) {
            roomCountInput.addEventListener('input', updatePriceSummary);
        }
        if (checkinInput && checkoutInput) {
            checkinInput.addEventListener('change', function() {
                checkoutInput.min = this.value;
                updatePriceSummary();
            });
            checkoutInput.addEventListener('change', updatePriceSummary);
        }
        
        // Inisialisasi perhitungan harga
        updatePriceSummary();
    }
    
    // Form Login/Register
    const showRegisterLink = document.getElementById('showRegister');
    const showLoginLink = document.getElementById('showLogin');
    const loginFormContainer = document.getElementById('loginFormContainer');
    const registerFormContainer = document.getElementById('registerFormContainer');
    
    if (showRegisterLink) {
        showRegisterLink.addEventListener('click', function(e) {
            e.preventDefault();
            loginFormContainer.classList.add('hidden');
            registerFormContainer.classList.remove('hidden');
        });
    }
    
    if (showLoginLink) {
        showLoginLink.addEventListener('click', function(e) {
            e.preventDefault();
            registerFormContainer.classList.add('hidden');
            loginFormContainer.classList.remove('hidden');
        });
    }
    
    // Form Login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const result = handleLoginSubmit(e);
            if (result === false) {
                e.preventDefault();
            }
            // Jika result === true, biarkan form submit normal
        });
    }
    
    // Form Register - DISABLED, biarkan form submit langsung ke PHP tanpa JavaScript
    // const registerForm = document.getElementById('registerForm');
    // if (registerForm) {
    //     registerForm.addEventListener('submit', function(e) {
    //         const result = handleRegisterSubmit(e);
    //         if (result === false) {
    //             e.preventDefault();
    //         }
    //     });
    // }
    
    // Form Check-in
    const checkinForm = document.getElementById('checkinForm');
    if (checkinForm) {
        checkinForm.addEventListener('submit', handleCheckinSubmit);
    }
    
    // Form Pembatalan
    const cancellationForm = document.getElementById('cancellationForm');
    if (cancellationForm) {
        cancellationForm.addEventListener('submit', handleCancellationSubmit);
    }
    
    // Tombol Logout
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', handleLogout);
    }
}

// Fungsi untuk Handle Submit Form Reservasi
function handleReservationSubmit(e) {
    const isValid = validateReservationForm();
    
    if (!isValid) {
        e.preventDefault();
        return false;
    }
    
    // Biarkan form submit normal ke backend
    return true;
}

// Fungsi untuk Validasi Form Reservasi
function validateReservationForm() {
    let isValid = true;
    
    // Validasi tipe kamar
    const roomType = document.getElementById('roomType');
    const roomTypeFeedback = document.getElementById('roomTypeFeedback');
    if (!roomType.value) {
        roomType.classList.add('error');
        roomTypeFeedback.textContent = 'Harap pilih tipe kamar';
        roomTypeFeedback.className = 'form-feedback error-message';
        isValid = false;
    } else {
        roomType.classList.remove('error');
        roomTypeFeedback.textContent = '';
    }
    
    // Validasi jumlah kamar
    const roomCount = document.getElementById('roomCount');
    const roomCountFeedback = document.getElementById('roomCountFeedback');
    if (!roomCount.value || roomCount.value < 1 || roomCount.value > 10) {
        roomCount.classList.add('error');
        roomCountFeedback.textContent = 'Jumlah kamar harus antara 1-10';
        roomCountFeedback.className = 'form-feedback error-message';
        isValid = false;
    } else {
        roomCount.classList.remove('error');
        roomCountFeedback.textContent = '';
    }
    
    // Validasi tanggal check-in
    const checkin = document.getElementById('checkin');
    const checkinFeedback = document.getElementById('checkinFeedback');
    if (!checkin.value) {
        checkin.classList.add('error');
        checkinFeedback.textContent = 'Harap pilih tanggal check-in';
        checkinFeedback.className = 'form-feedback error-message';
        isValid = false;
    } else {
        checkin.classList.remove('error');
        checkinFeedback.textContent = '';
    }
    
    // Validasi tanggal check-out
    const checkout = document.getElementById('checkout');
    const checkoutFeedback = document.getElementById('checkoutFeedback');
    if (!checkout.value) {
        checkout.classList.add('error');
        checkoutFeedback.textContent = 'Harap pilih tanggal check-out';
        checkoutFeedback.className = 'form-feedback error-message';
        isValid = false;
    } else if (checkin.value && checkout.value <= checkin.value) {
        checkout.classList.add('error');
        checkoutFeedback.textContent = 'Tanggal check-out harus setelah tanggal check-in';
        checkoutFeedback.className = 'form-feedback error-message';
        isValid = false;
    } else {
        checkout.classList.remove('error');
        checkoutFeedback.textContent = '';
    }
    
    // Validasi nama lengkap
    const fullName = document.getElementById('fullName');
    const fullNameFeedback = document.getElementById('fullNameFeedback');
    if (!fullName.value.trim()) {
        fullName.classList.add('error');
        fullNameFeedback.textContent = 'Harap masukkan nama lengkap';
        fullNameFeedback.className = 'form-feedback error-message';
        isValid = false;
    } else {
        fullName.classList.remove('error');
        fullNameFeedback.textContent = '';
    }
    
    // Validasi email
    const email = document.getElementById('email');
    const emailFeedback = document.getElementById('emailFeedback');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.value.trim()) {
        email.classList.add('error');
        emailFeedback.textContent = 'Harap masukkan email';
        emailFeedback.className = 'form-feedback error-message';
        isValid = false;
    } else if (!emailRegex.test(email.value)) {
        email.classList.add('error');
        emailFeedback.textContent = 'Format email tidak valid';
        emailFeedback.className = 'form-feedback error-message';
        isValid = false;
    } else {
        email.classList.remove('error');
        emailFeedback.textContent = '';
    }
    
    // Validasi telepon
    const phone = document.getElementById('phone');
    const phoneFeedback = document.getElementById('phoneFeedback');
    const phoneRegex = /^[0-9]{10,13}$/;
    if (!phone.value.trim()) {
        phone.classList.add('error');
        phoneFeedback.textContent = 'Harap masukkan nomor telepon';
        phoneFeedback.className = 'form-feedback error-message';
        isValid = false;
    } else if (!phoneRegex.test(phone.value.replace(/\D/g, ''))) {
        phone.classList.add('error');
        phoneFeedback.textContent = 'Format telepon tidak valid (10-13 digit)';
        phoneFeedback.className = 'form-feedback error-message';
        isValid = false;
    } else {
        phone.classList.remove('error');
        phoneFeedback.textContent = '';
    }
    
    return isValid;
}

// Fungsi untuk Update Ringkasan Harga
function updatePriceSummary() {
    const roomTypeSelect = document.getElementById('roomType');
    const roomCountInput = document.getElementById('roomCount');
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');
    const priceDetails = document.getElementById('priceDetails');
    const totalPrice = document.getElementById('totalPrice');
    
    if (!roomTypeSelect || !priceDetails || !totalPrice) return;
    
    const roomType = roomTypeSelect.value;
    const roomCount = parseInt(roomCountInput.value) || 1;
    let nights = 1;
    
    // Hitung jumlah malam
    if (checkinInput.value && checkoutInput.value) {
        const checkinDate = new Date(checkinInput.value);
        const checkoutDate = new Date(checkoutInput.value);
        const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
        nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        if (nights < 1) nights = 1;
    }
    
    if (roomType && dummyData.roomPrices[roomType]) {
        const pricePerNight = dummyData.roomPrices[roomType];
        const total = pricePerNight * roomCount * nights;
        
        // Update tampilan
        priceDetails.innerHTML = `
            <div>Tipe Kamar: <strong>${getRoomTypeName(roomType)}</strong></div>
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

// Fungsi untuk Hitung Total Harga
function calculateTotalPrice() {
    const roomTypeSelect = document.getElementById('roomType');
    const roomCountInput = document.getElementById('roomCount');
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');
    
    const roomType = roomTypeSelect.value;
    const roomCount = parseInt(roomCountInput.value) || 1;
    let nights = 1;
    
    // Hitung jumlah malam
    if (checkinInput.value && checkoutInput.value) {
        const checkinDate = new Date(checkinInput.value);
        const checkoutDate = new Date(checkoutInput.value);
        const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
        nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
        if (nights < 1) nights = 1;
    }
    
    if (roomType && dummyData.roomPrices[roomType]) {
        const pricePerNight = dummyData.roomPrices[roomType];
        return pricePerNight * roomCount * nights;
    }
    
    return 0;
}

// Fungsi untuk Get Nama Tipe Kamar
function getRoomTypeName(roomType) {
    const roomNames = {
        standard: 'Standard Room',
        deluxe: 'Deluxe Room',
        suite: 'Suite Room',
        executive: 'Executive Room'
    };
    return roomNames[roomType] || roomType;
}

// Fungsi untuk Handle Submit Form Login
function handleLoginSubmit(e) {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    // Validasi client-side
    if (!email || !password) {
        e.preventDefault();
        showToast('Harap isi semua field', 'error');
        return false;
    }
    
    // Jika validasi berhasil, biarkan form submit normal ke server
    // Form akan submit ke action="../controllers/login.php" method="POST"
    return true;
}

// Fungsi untuk Handle Submit Form Register
function handleRegisterSubmit(e) {
    const firstName = document.getElementById('registerFirstName').value;
    const lastName = document.getElementById('registerLastName').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('registerConfirmPassword').value;
    
    // Validasi client-side
    let isValid = true;
    
    // Clear previous errors
    document.querySelectorAll('#registerForm .error').forEach(el => el.classList.remove('error'));
    document.querySelectorAll('#registerForm .form-feedback').forEach(el => {
        el.textContent = '';
        el.className = 'form-feedback';
    });
    
    if (!firstName) {
        document.getElementById('registerFirstName').classList.add('error');
        document.getElementById('registerFirstNameFeedback').textContent = 'Harap masukkan nama depan';
        document.getElementById('registerFirstNameFeedback').className = 'form-feedback error-message';
        isValid = false;
    }
    
    if (!lastName) {
        document.getElementById('registerLastName').classList.add('error');
        document.getElementById('registerLastNameFeedback').textContent = 'Harap masukkan nama belakang';
        document.getElementById('registerLastNameFeedback').className = 'form-feedback error-message';
        isValid = false;
    }
    
    if (!email) {
        document.getElementById('registerEmail').classList.add('error');
        document.getElementById('registerEmailFeedback').textContent = 'Harap masukkan email';
        document.getElementById('registerEmailFeedback').className = 'form-feedback error-message';
        isValid = false;
    }
    
    if (!password || password.length < 8) {
        document.getElementById('registerPassword').classList.add('error');
        document.getElementById('registerPasswordFeedback').textContent = 'Kata sandi minimal 8 karakter';
        document.getElementById('registerPasswordFeedback').className = 'form-feedback error-message';
        isValid = false;
    }
    
    if (password !== confirmPassword) {
        document.getElementById('registerConfirmPassword').classList.add('error');
        document.getElementById('registerConfirmPasswordFeedback').textContent = 'Kata sandi tidak cocok';
        document.getElementById('registerConfirmPasswordFeedback').className = 'form-feedback error-message';
        isValid = false;
    }
    
    // Jika validasi gagal, prevent submit
    if (!isValid) {
        e.preventDefault();
        return false;
    }
    
    // Jika validasi berhasil, biarkan form submit normal ke server
    return true;
}

// Fungsi untuk Handle Submit Form Check-in
function handleCheckinSubmit(e) {
    e.preventDefault();
    
    const bookingCode = document.getElementById('bookingCode').value;
    const checkinResult = document.getElementById('checkinResult');
    const checkinResultTitle = document.getElementById('checkinResultTitle');
    const checkinResultContent = document.getElementById('checkinResultContent');
    
    if (!bookingCode) {
        showToast('Harap masukkan kode booking', 'error');
        return;
    }
    
    // Cari reservasi berdasarkan kode booking
    const reservation = dummyData.reservations.find(r => r.id === bookingCode);
    
    showLoading();
    
    setTimeout(() => {
        hideLoading();
        
        if (reservation) {
            // Tampilkan detail reservasi
            checkinResultTitle.textContent = 'Check-in Berhasil!';
            checkinResultContent.innerHTML = `
                <p>Check-in online berhasil untuk reservasi berikut:</p>
                <div style="margin-top: 15px;">
                    <p><strong>Kode Booking:</strong> ${reservation.id}</p>
                    <p><strong>Nama:</strong> ${reservation.fullName}</p>
                    <p><strong>Tipe Kamar:</strong> ${getRoomTypeName(reservation.roomType)}</p>
                    <p><strong>Check-in:</strong> ${formatDate(reservation.checkin)}</p>
                    <p><strong>Check-out:</strong> ${formatDate(reservation.checkout)}</p>
                </div>
                <div style="margin-top: 20px; padding: 15px; background-color: var(--primary-light); border-radius: var(--border-radius);">
                    <p><strong>Instruksi Check-in:</strong></p>
                    <p>1. Datang ke resepsionis dengan menunjukkan kode booking ini</p>
                    <p>2. Tunjukkan identitas asli (KTP/Paspor)</p>
                    <p>3. Kamar akan siap pada pukul 14:00 WIB</p>
                </div>
            `;
            
            // Update status reservasi
            reservation.status = 'confirmed';
        } else {
            checkinResultTitle.textContent = 'Check-in Gagal';
            checkinResultContent.innerHTML = `
                <p>Kode booking <strong>${bookingCode}</strong> tidak ditemukan.</p>
                <p>Pastikan kode booking yang Anda masukkan benar atau hubungi layanan pelanggan kami.</p>
            `;
        }
        
        checkinResult.classList.remove('hidden');
        
        // Scroll ke hasil
        checkinResult.scrollIntoView({ behavior: 'smooth' });
    }, 1000);
}

// Fungsi untuk Handle Submit Form Pembatalan
function handleCancellationSubmit(e) {
    e.preventDefault();
    
    const bookingCode = document.getElementById('cancellationBookingCode').value;
    const reason = document.getElementById('cancellationReason').value;
    const details = document.getElementById('cancellationDetails').value;
    
    if (!bookingCode) {
        showToast('Harap masukkan kode booking', 'error');
        return;
    }
    
    if (!reason) {
        showToast('Harap pilih alasan pembatalan', 'error');
        return;
    }
    
    // Cari reservasi berdasarkan kode booking
    const reservation = dummyData.reservations.find(r => r.id === bookingCode);
    
    if (!reservation) {
        showToast('Kode booking tidak ditemukan', 'error');
        return;
    }
    
    showLoading();
    
    setTimeout(() => {
        hideLoading();
        
        // Buat pengajuan pembatalan
        const cancellation = {
            bookingCode: bookingCode,
            submissionDate: new Date().toISOString().split('T')[0],
            reason: reason,
            details: details,
            status: 'pending',
            response: 'Pengajuan Anda sedang diproses. Kami akan menghubungi Anda dalam 1-2 hari kerja.'
        };
        
        // Tambahkan ke data dummy
        dummyData.cancellations.push(cancellation);
        
        // Update status reservasi
        reservation.status = 'cancelled';
        
        // Tampilkan hasil
        const cancellationResult = document.getElementById('cancellationResult');
        const cancellationResultTitle = document.getElementById('cancellationResultTitle');
        const cancellationResultContent = document.getElementById('cancellationResultContent');
        
        cancellationResultTitle.textContent = 'Pengajuan Pembatalan Berhasil';
        cancellationResultContent.innerHTML = `
            <p>Pengajuan pembatalan untuk kode booking <strong>${bookingCode}</strong> telah berhasil dikirim.</p>
            <div style="margin-top: 15px;">
                <p><strong>Status:</strong> <span class="status-badge status-pending">Diproses</span></p>
                <p><strong>Tanggal Pengajuan:</strong> ${formatDate(cancellation.submissionDate)}</p>
                <p><strong>Tanggapan:</strong> ${cancellation.response}</p>
            </div>
            <div style="margin-top: 20px; padding: 15px; background-color: #fff5f5; border-radius: var(--border-radius);">
                <p><strong>Informasi Penting:</strong></p>
                <p>1. Pembatalan mungkin dikenakan biaya sesuai dengan kebijakan pembatalan</p>
                <p>2. Dana akan dikembalikan dalam 5-7 hari kerja jika disetujui</p>
                <p>3. Anda dapat melacak status pengajuan di halaman ini</p>
            </div>
        `;
        
        cancellationResult.classList.remove('hidden');
        
        // Update tabel status pembatalan
        loadCancellationStatus();
        
        // Scroll ke hasil
        cancellationResult.scrollIntoView({ behavior: 'smooth' });
        
        // Reset form
        document.getElementById('cancellationForm').reset();
    }, 1500);
}

// Fungsi untuk Handle Logout
function handleLogout() {
    showToast('Berhasil keluar dari akun', 'success');
    
    // Kembali ke halaman login
    setTimeout(() => {
        window.location.hash = 'login';
        
        // Update navigasi
        document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
        const loginLink = document.querySelector('a[href="#login"]');
        if (loginLink) loginLink.classList.add('active');
    }, 1000);
}

// Fungsi untuk Load Data Dashboard
function loadDashboardData() {
    // Update statistik
    const totalBookings = document.getElementById('totalBookings');
    const activeBookings = document.getElementById('activeBookings');
    const occupancyRate = document.getElementById('occupancyRate');
    const cancellationRate = document.getElementById('cancellationRate');
    
    if (totalBookings) totalBookings.textContent = dummyData.reservations.length;
    
    // Hitung reservasi aktif (check-in hari ini atau mendatang)
    const today = new Date().toISOString().split('T')[0];
    const activeReservations = dummyData.reservations.filter(r => r.checkin >= today && r.status !== 'cancelled').length;
    if (activeBookings) activeBookings.textContent = activeReservations;
    
    // Hitung tingkat okupansi (dummy)
    if (occupancyRate) occupancyRate.textContent = '78%';
    
    // Hitung tingkat pembatalan
    const cancelledReservations = dummyData.reservations.filter(r => r.status === 'cancelled').length;
    const cancellationRateValue = dummyData.reservations.length > 0 ? Math.round((cancelledReservations / dummyData.reservations.length) * 100) : 0;
    if (cancellationRate) cancellationRate.textContent = `${cancellationRateValue}%`;
    
    // Load tabel reservasi admin
    const adminReservationTable = document.getElementById('adminReservationTable');
    if (adminReservationTable) {
        adminReservationTable.innerHTML = '';
        
        dummyData.reservations.forEach(reservation => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${reservation.id}</td>
                <td>${reservation.fullName}</td>
                <td>${getRoomTypeName(reservation.roomType)}</td>
                <td>${formatDate(reservation.checkin)}</td>
                <td>${formatDate(reservation.checkout)}</td>
                <td><span class="status-badge status-${reservation.status}">${getStatusText(reservation.status)}</span></td>
                <td>
                    <button class="btn btn-secondary btn-small" onclick="editReservation('${reservation.id}')">Edit</button>
                    <button class="btn btn-danger btn-small" onclick="deleteReservation('${reservation.id}')">Hapus</button>
                </td>
            `;
            adminReservationTable.appendChild(row);
        });
    }
    
    // Load tabel user admin
    const adminUserTable = document.getElementById('adminUserTable');
    if (adminUserTable) {
        adminUserTable.innerHTML = '';
        
        dummyData.users.forEach(user => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.firstName} ${user.lastName}</td>
                <td>${user.email}</td>
                <td>${user.phone}</td>
                <td><span class="status-badge ${user.status === 'active' ? 'status-confirmed' : 'status-pending'}">${user.status === 'active' ? 'Aktif' : 'Nonaktif'}</span></td>
                <td>
                    <button class="btn btn-secondary btn-small" onclick="editUser(${user.id})">Edit</button>
                    <button class="btn btn-danger btn-small" onclick="deleteUser(${user.id})">Hapus</button>
                </td>
            `;
            adminUserTable.appendChild(row);
        });
    }
    
    // Load tabel status pembatalan
    loadCancellationStatus();
}

// Fungsi untuk Load Data Profil
function loadProfileData() {
    // Update informasi profil
    const profileName = document.getElementById('profileName');
    const profileEmail = document.getElementById('profileEmail');
    const profilePhone = document.getElementById('profilePhone');
    const profileJoinDate = document.getElementById('profileJoinDate');
    
    if (profileName) profileName.textContent = `${dummyData.currentUser.firstName} ${dummyData.currentUser.lastName}`;
    if (profileEmail) profileEmail.textContent = dummyData.currentUser.email;
    if (profilePhone) profilePhone.textContent = dummyData.currentUser.phone;
    if (profileJoinDate) profileJoinDate.textContent = formatDate(dummyData.currentUser.joinDate);
    
    // Update statistik
    const userReservations = dummyData.reservations.filter(r => r.userId === dummyData.currentUser.id);
    const totalReservations = document.getElementById('totalReservations');
    const activeReservations = document.getElementById('activeReservations');
    const completedReservations = document.getElementById('completedReservations');
    const cancelledReservations = document.getElementById('cancelledReservations');
    
    if (totalReservations) totalReservations.textContent = userReservations.length;
    
    const today = new Date().toISOString().split('T')[0];
    const activeCount = userReservations.filter(r => r.checkin >= today && r.status !== 'cancelled').length;
    if (activeReservations) activeReservations.textContent = activeCount;
    
    const completedCount = userReservations.filter(r => r.checkout < today && r.status !== 'cancelled').length;
    if (completedReservations) completedReservations.textContent = completedCount;
    
    const cancelledCount = userReservations.filter(r => r.status === 'cancelled').length;
    if (cancelledReservations) cancelledReservations.textContent = cancelledCount;
    
    // Load riwayat reservasi
    const reservationHistory = document.getElementById('reservationHistory');
    if (reservationHistory) {
        reservationHistory.innerHTML = '';
        
        userReservations.forEach(reservation => {
            const row = document.createElement('tr');
            
            // Hitung durasi
            const checkinDate = new Date(reservation.checkin);
            const checkoutDate = new Date(reservation.checkout);
            const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
            const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
            
            row.innerHTML = `
                <td>${reservation.id}</td>
                <td>${getRoomTypeName(reservation.roomType)}</td>
                <td>${formatDate(reservation.checkin)} - ${formatDate(reservation.checkout)}</td>
                <td>${nights} malam</td>
                <td><span class="status-badge status-${reservation.status}">${getStatusText(reservation.status)}</span></td>
                <td>
                    <button class="btn btn-secondary btn-small" onclick="viewReservationDetail('${reservation.id}')">Detail</button>
                </td>
            `;
            reservationHistory.appendChild(row);
        });
    }
}

// Fungsi untuk Load Status Pembatalan
function loadCancellationStatus() {
    const cancellationStatusTable = document.getElementById('cancellationStatusTable');
    if (cancellationStatusTable) {
        cancellationStatusTable.innerHTML = '';
        
        dummyData.cancellations.forEach(cancellation => {
            const row = document.createElement('tr');
            
            // Cari reservasi terkait
            const reservation = dummyData.reservations.find(r => r.id === cancellation.bookingCode);
            
            row.innerHTML = `
                <td>${cancellation.bookingCode}</td>
                <td>${formatDate(cancellation.submissionDate)}</td>
                <td>${getCancellationReasonText(cancellation.reason)}</td>
                <td><span class="status-badge ${cancellation.status === 'approved' ? 'status-confirmed' : cancellation.status === 'rejected' ? 'status-pending' : 'status-pending'}">${cancellation.status === 'approved' ? 'Disetujui' : cancellation.status === 'rejected' ? 'Ditolak' : 'Diproses'}</span></td>
                <td>${cancellation.response || '-'}</td>
            `;
            cancellationStatusTable.appendChild(row);
        });
    }
}

// Fungsi untuk Inisialisasi Modal
function initModals() {
    const modalOverlay = document.getElementById('modalOverlay');
    const modalClose = document.getElementById('modalClose');
    
    // Close modal ketika klik tombol close
    if (modalClose) {
        modalClose.addEventListener('click', closeModal);
    }
    
    // Close modal ketika klik di luar modal
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });
    }
    
    // Tombol tambah reservasi di dashboard
    const addReservationBtn = document.getElementById('addReservationBtn');
    if (addReservationBtn) {
        addReservationBtn.addEventListener('click', function() {
            showAddReservationModal();
        });
    }
    
    // Tombol tambah user di dashboard
    const addUserBtn = document.getElementById('addUserBtn');
    if (addUserBtn) {
        addUserBtn.addEventListener('click', function() {
            showAddUserModal();
        });
    }
    
    // Tombol refresh data
    const refreshDataBtn = document.getElementById('refreshDataBtn');
    if (refreshDataBtn) {
        refreshDataBtn.addEventListener('click', function() {
            showLoading();
            setTimeout(() => {
                hideLoading();
                loadDashboardData();
                showToast('Data berhasil direfresh', 'success');
            }, 800);
        });
    }
}

// Fungsi untuk Show Modal
function showModal(title, body, buttons) {
    const modalOverlay = document.getElementById('modalOverlay');
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');
    const modalFooter = document.getElementById('modalFooter');
    
    if (!modalOverlay || !modalTitle || !modalBody || !modalFooter) return;
    
    // Set konten modal
    modalTitle.textContent = title;
    modalBody.innerHTML = body;
    
    // Set tombol modal
    modalFooter.innerHTML = '';
    if (buttons && buttons.length > 0) {
        buttons.forEach(button => {
            const btn = document.createElement('button');
            btn.className = `btn ${button.class}`;
            btn.textContent = button.text;
            btn.addEventListener('click', button.action);
            modalFooter.appendChild(btn);
        });
    } else {
        // Tombol default jika tidak ada tombol yang ditentukan
        const closeBtn = document.createElement('button');
        closeBtn.className = 'btn btn-secondary';
        closeBtn.textContent = 'Tutup';
        closeBtn.addEventListener('click', closeModal);
        modalFooter.appendChild(closeBtn);
    }
    
    // Tampilkan modal
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk Close Modal
function closeModal() {
    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay) {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Fungsi untuk Show Modal Tambah Reservasi
function showAddReservationModal() {
    const modalContent = `
        <form id="addReservationModalForm">
            <div class="form-group">
                <label for="modalRoomType" class="form-label">Tipe Kamar</label>
                <select id="modalRoomType" class="form-select" required>
                    <option value="">Pilih Tipe Kamar</option>
                    <option value="standard">Standard Room</option>
                    <option value="deluxe">Deluxe Room</option>
                    <option value="suite">Suite Room</option>
                    <option value="executive">Executive Room</option>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="modalCheckin" class="form-label">Check-in</label>
                    <input type="date" id="modalCheckin" class="form-input" required>
                </div>
                <div class="form-group">
                    <label for="modalCheckout" class="form-label">Check-out</label>
                    <input type="date" id="modalCheckout" class="form-input" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="modalGuestName" class="form-label">Nama Tamu</label>
                <input type="text" id="modalGuestName" class="form-input" placeholder="Nama lengkap tamu" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="modalGuestEmail" class="form-label">Email Tamu</label>
                    <input type="email" id="modalGuestEmail" class="form-input" placeholder="email@contoh.com" required>
                </div>
                <div class="form-group">
                    <label for="modalGuestPhone" class="form-label">Telepon Tamu</label>
                    <input type="tel" id="modalGuestPhone" class="form-input" placeholder="08123456789" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="modalStatus" class="form-label">Status</label>
                <select id="modalStatus" class="form-select" required>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </form>
    `;
    
    showModal(
        'Tambah Reservasi Baru',
        modalContent,
        [
            { 
                text: 'Simpan', 
                class: 'btn-primary', 
                action: () => {
                    // Validasi dan simpan data
                    const roomType = document.getElementById('modalRoomType').value;
                    const checkin = document.getElementById('modalCheckin').value;
                    const checkout = document.getElementById('modalCheckout').value;
                    const guestName = document.getElementById('modalGuestName').value;
                    const guestEmail = document.getElementById('modalGuestEmail').value;
                    const guestPhone = document.getElementById('modalGuestPhone').value;
                    const status = document.getElementById('modalStatus').value;
                    
                    if (!roomType || !checkin || !checkout || !guestName || !guestEmail || !guestPhone) {
                        showToast('Harap isi semua field yang wajib', 'error');
                        return;
                    }
                    
                    // Generate ID reservasi
                    const reservationId = 'RS' + new Date().getFullYear() + Math.random().toString(36).substring(2, 5).toUpperCase() + Math.floor(Math.random() * 1000);
                    
                    // Hitung harga
                    const checkinDate = new Date(checkin);
                    const checkoutDate = new Date(checkout);
                    const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
                    const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    const price = dummyData.roomPrices[roomType] * nights;
                    
                    // Tambahkan reservasi baru
                    dummyData.reservations.push({
                        id: reservationId,
                        userId: 0, // Guest user
                        roomType: roomType,
                        roomCount: 1,
                        checkin: checkin,
                        checkout: checkout,
                        fullName: guestName,
                        email: guestEmail,
                        phone: guestPhone,
                        status: status,
                        price: price
                    });
                    
                    // Refresh data dashboard
                    loadDashboardData();
                    
                    // Tampilkan notifikasi
                    showToast('Reservasi berhasil ditambahkan', 'success');
                    
                    // Tutup modal
                    closeModal();
                }
            },
            { 
                text: 'Batal', 
                class: 'btn-secondary', 
                action: closeModal 
            }
        ]
    );
    
    // Set tanggal minimum untuk input tanggal
    const today = new Date().toISOString().split('T')[0];
    const modalCheckin = document.getElementById('modalCheckin');
    const modalCheckout = document.getElementById('modalCheckout');
    
    if (modalCheckin) modalCheckin.min = today;
    if (modalCheckout) modalCheckout.min = today;
    
    if (modalCheckin && modalCheckout) {
        modalCheckin.addEventListener('change', function() {
            modalCheckout.min = this.value;
        });
    }
}

// Fungsi untuk Show Modal Tambah User
function showAddUserModal() {
    const modalContent = `
        <form id="addUserModalForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="modalFirstName" class="form-label">Nama Depan</label>
                    <input type="text" id="modalFirstName" class="form-input" placeholder="Nama depan" required>
                </div>
                <div class="form-group">
                    <label for="modalLastName" class="form-label">Nama Belakang</label>
                    <input type="text" id="modalLastName" class="form-input" placeholder="Nama belakang" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="modalUserEmail" class="form-label">Email</label>
                <input type="email" id="modalUserEmail" class="form-input" placeholder="email@contoh.com" required>
            </div>
            
            <div class="form-group">
                <label for="modalUserPhone" class="form-label">Telepon</label>
                <input type="tel" id="modalUserPhone" class="form-input" placeholder="08123456789" required>
            </div>
            
            <div class="form-group">
                <label for="modalUserStatus" class="form-label">Status</label>
                <select id="modalUserStatus" class="form-select" required>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
        </form>
    `;
    
    showModal(
        'Tambah Pengguna Baru',
        modalContent,
        [
            { 
                text: 'Simpan', 
                class: 'btn-primary', 
                action: () => {
                    // Validasi dan simpan data
                    const firstName = document.getElementById('modalFirstName').value;
                    const lastName = document.getElementById('modalLastName').value;
                    const email = document.getElementById('modalUserEmail').value;
                    const phone = document.getElementById('modalUserPhone').value;
                    const status = document.getElementById('modalUserStatus').value;
                    
                    if (!firstName || !lastName || !email || !phone) {
                        showToast('Harap isi semua field yang wajib', 'error');
                        return;
                    }
                    
                    // Generate ID user
                    const userId = dummyData.users.length > 0 ? Math.max(...dummyData.users.map(u => u.id)) + 1 : 1;
                    
                    // Tambahkan user baru
                    dummyData.users.push({
                        id: userId,
                        firstName: firstName,
                        lastName: lastName,
                        email: email,
                        phone: phone,
                        joinDate: new Date().toISOString().split('T')[0],
                        status: status
                    });
                    
                    // Refresh data dashboard
                    loadDashboardData();
                    
                    // Tampilkan notifikasi
                    showToast('Pengguna berhasil ditambahkan', 'success');
                    
                    // Tutup modal
                    closeModal();
                }
            },
            { 
                text: 'Batal', 
                class: 'btn-secondary', 
                action: closeModal 
            }
        ]
    );
}

// Fungsi untuk Edit Reservasi
function editReservation(reservationId) {
    const reservation = dummyData.reservations.find(r => r.id === reservationId);
    if (!reservation) return;
    
    const modalContent = `
        <form id="editReservationModalForm">
            <div class="form-group">
                <label for="editModalRoomType" class="form-label">Tipe Kamar</label>
                <select id="editModalRoomType" class="form-select" required>
                    <option value="standard" ${reservation.roomType === 'standard' ? 'selected' : ''}>Standard Room</option>
                    <option value="deluxe" ${reservation.roomType === 'deluxe' ? 'selected' : ''}>Deluxe Room</option>
                    <option value="suite" ${reservation.roomType === 'suite' ? 'selected' : ''}>Suite Room</option>
                    <option value="executive" ${reservation.roomType === 'executive' ? 'selected' : ''}>Executive Room</option>
                </select>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="editModalCheckin" class="form-label">Check-in</label>
                    <input type="date" id="editModalCheckin" class="form-input" value="${reservation.checkin}" required>
                </div>
                <div class="form-group">
                    <label for="editModalCheckout" class="form-label">Check-out</label>
                    <input type="date" id="editModalCheckout" class="form-input" value="${reservation.checkout}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="editModalGuestName" class="form-label">Nama Tamu</label>
                <input type="text" id="editModalGuestName" class="form-input" value="${reservation.fullName}" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="editModalGuestEmail" class="form-label">Email Tamu</label>
                    <input type="email" id="editModalGuestEmail" class="form-input" value="${reservation.email}" required>
                </div>
                <div class="form-group">
                    <label for="editModalGuestPhone" class="form-label">Telepon Tamu</label>
                    <input type="tel" id="editModalGuestPhone" class="form-input" value="${reservation.phone}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="editModalStatus" class="form-label">Status</label>
                <select id="editModalStatus" class="form-select" required>
                    <option value="pending" ${reservation.status === 'pending' ? 'selected' : ''}>Pending</option>
                    <option value="confirmed" ${reservation.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                    <option value="completed" ${reservation.status === 'completed' ? 'selected' : ''}>Completed</option>
                    <option value="cancelled" ${reservation.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                </select>
            </div>
        </form>
    `;
    
    showModal(
        'Edit Reservasi',
        modalContent,
        [
            { 
                text: 'Simpan Perubahan', 
                class: 'btn-primary', 
                action: () => {
                    // Update data reservasi
                    reservation.roomType = document.getElementById('editModalRoomType').value;
                    reservation.checkin = document.getElementById('editModalCheckin').value;
                    reservation.checkout = document.getElementById('editModalCheckout').value;
                    reservation.fullName = document.getElementById('editModalGuestName').value;
                    reservation.email = document.getElementById('editModalGuestEmail').value;
                    reservation.phone = document.getElementById('editModalGuestPhone').value;
                    reservation.status = document.getElementById('editModalStatus').value;
                    
                    // Hitung ulang harga
                    const checkinDate = new Date(reservation.checkin);
                    const checkoutDate = new Date(reservation.checkout);
                    const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
                    const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    reservation.price = dummyData.roomPrices[reservation.roomType] * nights * reservation.roomCount;
                    
                    // Refresh data dashboard
                    loadDashboardData();
                    loadProfileData();
                    
                    // Tampilkan notifikasi
                    showToast('Reservasi berhasil diperbarui', 'success');
                    
                    // Tutup modal
                    closeModal();
                }
            },
            { 
                text: 'Batal', 
                class: 'btn-secondary', 
                action: closeModal 
            }
        ]
    );
}

// Fungsi untuk Delete Reservasi
function deleteReservation(reservationId) {
    showModal(
        'Konfirmasi Hapus',
        `<p>Apakah Anda yakin ingin menghapus reservasi dengan ID <strong>${reservationId}</strong>?</p>
            <p>Tindakan ini tidak dapat dibatalkan.</p>`,
        [
            { 
                text: 'Hapus', 
                class: 'btn-danger', 
                action: () => {
                    // Hapus reservasi dari data
                    const index = dummyData.reservations.findIndex(r => r.id === reservationId);
                    if (index !== -1) {
                        dummyData.reservations.splice(index, 1);
                    }
                    
                    // Refresh data dashboard
                    loadDashboardData();
                    loadProfileData();
                    
                    // Tampilkan notifikasi
                    showToast('Reservasi berhasil dihapus', 'success');
                    
                    // Tutup modal
                    closeModal();
                }
            },
            { 
                text: 'Batal', 
                class: 'btn-secondary', 
                action: closeModal 
            }
        ]
    );
}

// Fungsi untuk Edit User
function editUser(userId) {
    const user = dummyData.users.find(u => u.id === userId);
    if (!user) return;
    
    const modalContent = `
        <form id="editUserModalForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="editModalFirstName" class="form-label">Nama Depan</label>
                    <input type="text" id="editModalFirstName" class="form-input" value="${user.firstName}" required>
                </div>
                <div class="form-group">
                    <label for="editModalLastName" class="form-label">Nama Belakang</label>
                    <input type="text" id="editModalLastName" class="form-input" value="${user.lastName}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="editModalUserEmail" class="form-label">Email</label>
                <input type="email" id="editModalUserEmail" class="form-input" value="${user.email}" required>
            </div>
            
            <div class="form-group">
                <label for="editModalUserPhone" class="form-label">Telepon</label>
                    <input type="tel" id="editModalUserPhone" class="form-input" value="${user.phone}" required>
            </div>
            
            <div class="form-group">
                <label for="editModalUserStatus" class="form-label">Status</label>
                <select id="editModalUserStatus" class="form-select" required>
                    <option value="active" ${user.status === 'active' ? 'selected' : ''}>Aktif</option>
                    <option value="inactive" ${user.status === 'inactive' ? 'selected' : ''}>Nonaktif</option>
                </select>
            </div>
        </form>
    `;
    
    showModal(
        'Edit Pengguna',
        modalContent,
        [
            { 
                text: 'Simpan Perubahan', 
                class: 'btn-primary', 
                action: () => {
                    // Update data user
                    user.firstName = document.getElementById('editModalFirstName').value;
                    user.lastName = document.getElementById('editModalLastName').value;
                    user.email = document.getElementById('editModalUserEmail').value;
                    user.phone = document.getElementById('editModalUserPhone').value;
                    user.status = document.getElementById('editModalUserStatus').value;
                    
                    // Refresh data dashboard
                    loadDashboardData();
                    
                    // Tampilkan notifikasi
                    showToast('Pengguna berhasil diperbarui', 'success');
                    
                    // Tutup modal
                    closeModal();
                }
            },
            { 
                text: 'Batal', 
                class: 'btn-secondary', 
                action: closeModal 
            }
        ]
    );
}

// Fungsi untuk Delete User
function deleteUser(userId) {
    showModal(
        'Konfirmasi Hapus',
        `<p>Apakah Anda yakin ingin menghapus pengguna ini?</p>
            <p>Tindakan ini tidak dapat dibatalkan.</p>`,
        [
            { 
                text: 'Hapus', 
                class: 'btn-danger', 
                action: () => {
                    // Hapus user dari data
                    const index = dummyData.users.findIndex(u => u.id === userId);
                    if (index !== -1) {
                        dummyData.users.splice(index, 1);
                    }
                    
                    // Refresh data dashboard
                    loadDashboardData();
                    
                    // Tampilkan notifikasi
                    showToast('Pengguna berhasil dihapus', 'success');
                    
                    // Tutup modal
                    closeModal();
                }
            },
            { 
                text: 'Batal', 
                class: 'btn-secondary', 
                action: closeModal 
            }
        ]
    );
}

// Fungsi untuk View Detail Reservasi
function viewReservationDetail(reservationId) {
    const reservation = dummyData.reservations.find(r => r.id === reservationId);
    if (!reservation) return;
    
    // Hitung durasi
    const checkinDate = new Date(reservation.checkin);
    const checkoutDate = new Date(reservation.checkout);
    const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
    const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
    
    const modalContent = `
        <div>
            <p><strong>Kode Booking:</strong> ${reservation.id}</p>
            <p><strong>Status:</strong> <span class="status-badge status-${reservation.status}">${getStatusText(reservation.status)}</span></p>
            <hr style="margin: 15px 0;">
            <p><strong>Tipe Kamar:</strong> ${getRoomTypeName(reservation.roomType)}</p>
            <p><strong>Jumlah Kamar:</strong> ${reservation.roomCount}</p>
            <p><strong>Check-in:</strong> ${formatDate(reservation.checkin)}</p>
            <p><strong>Check-out:</strong> ${formatDate(reservation.checkout)}</p>
            <p><strong>Durasi:</strong> ${nights} malam</p>
            <hr style="margin: 15px 0;">
            <p><strong>Nama Tamu:</strong> ${reservation.fullName}</p>
            <p><strong>Email:</strong> ${reservation.email}</p>
            <p><strong>Telepon:</strong> ${reservation.phone}</p>
            ${reservation.specialRequests ? `<p><strong>Permintaan Khusus:</strong> ${reservation.specialRequests}</p>` : ''}
            <hr style="margin: 15px 0;">
            <p><strong>Total Harga:</strong> Rp ${reservation.price.toLocaleString('id-ID')}</p>
        </div>
    `;
    
    showModal(
        'Detail Reservasi',
        modalContent,
        [
            { 
                text: 'Tutup', 
                class: 'btn-secondary', 
                action: closeModal 
            }
        ]
    );
}

// Fungsi untuk Inisialisasi Animasi
function initAnimations() {
    // Trigger animasi saat scroll
    window.addEventListener('scroll', triggerAnimations);
    
    // Trigger animasi awal
    setTimeout(() => {
        triggerAnimations();
    }, 300);
}

// Fungsi untuk Trigger Animasi
function triggerAnimations() {
    const fadeElements = document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right');
    
    fadeElements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < window.innerHeight - elementVisible) {
            element.classList.add('visible');
        }
    });
}

// Fungsi untuk Show Loading
function showLoading() {
    // Buat elemen loading jika belum ada
    let loadingEl = document.getElementById('loadingOverlay');
    if (!loadingEl) {
        loadingEl = document.createElement('div');
        loadingEl.id = 'loadingOverlay';
        loadingEl.style.position = 'fixed';
        loadingEl.style.top = '0';
        loadingEl.style.left = '0';
        loadingEl.style.width = '100%';
        loadingEl.style.height = '100%';
        loadingEl.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
        loadingEl.style.display = 'flex';
        loadingEl.style.alignItems = 'center';
        loadingEl.style.justifyContent = 'center';
        loadingEl.style.zIndex = '9999';
        loadingEl.style.flexDirection = 'column';
        
        const spinner = document.createElement('div');
        spinner.style.width = '50px';
        spinner.style.height = '50px';
        spinner.style.border = `5px solid var(--gray-light)`;
        spinner.style.borderTop = `5px solid var(--primary-color)`;
        spinner.style.borderRadius = '50%';
        spinner.style.animation = 'spin 1s linear infinite';
        
        const text = document.createElement('p');
        text.textContent = 'Memproses...';
        text.style.marginTop = '15px';
        text.style.color = 'var(--primary-color)';
        text.style.fontWeight = '600';
        
        loadingEl.appendChild(spinner);
        loadingEl.appendChild(text);
        document.body.appendChild(loadingEl);
        
        // Tambahkan style untuk animasi spin
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    } else {
        loadingEl.style.display = 'flex';
    }
}

// Fungsi untuk Hide Loading
function hideLoading() {
    const loadingEl = document.getElementById('loadingOverlay');
    if (loadingEl) {
        loadingEl.style.display = 'none';
    }
}

// Fungsi untuk Show Toast Notification
function showToast(message, type = 'info') {
    // Buat elemen toast jika belum ada
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.style.position = 'fixed';
        toastContainer.style.top = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    // Buat toast
    const toast = document.createElement('div');
    toast.style.padding = '15px 20px';
    toast.style.marginBottom = '10px';
    toast.style.borderRadius = 'var(--border-radius)';
    toast.style.color = 'white';
    toast.style.fontWeight = '500';
    toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'transform 0.3s ease';
    toast.style.minWidth = '250px';
    
    // Set warna berdasarkan jenis toast
    if (type === 'success') {
        toast.style.backgroundColor = 'var(--success-color)';
    } else if (type === 'error') {
        toast.style.backgroundColor = 'var(--error-color)';
    } else if (type === 'warning') {
        toast.style.backgroundColor = 'var(--warning-color)';
    } else {
        toast.style.backgroundColor = 'var(--info-color)';
    }
    
    toast.textContent = message;
    toastContainer.appendChild(toast);
    
    // Trigger animasi masuk
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Hapus toast setelah 3 detik
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Fungsi untuk Format Tanggal
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Fungsi untuk Get Status Text
function getStatusText(status) {
    const statusMap = {
        'pending': 'Menunggu',
        'confirmed': 'Dikonfirmasi',
        'completed': 'Selesai',
        'cancelled': 'Dibatalkan'
    };
    return statusMap[status] || status;
}

// Fungsi untuk Get Cancellation Reason Text
function getCancellationReasonText(reason) {
    const reasonMap = {
        'change_plans': 'Perubahan rencana',
        'found_cheaper': 'Menemukan harga lebih murah',
        'emergency': 'Keadaan darurat',
        'dissatisfied': 'Tidak puas dengan layanan',
        'other': 'Lainnya'
    };
    return reasonMap[reason] || reason;
}

// Export fungsi ke global scope untuk akses dari HTML
window.editReservation = editReservation;
window.deleteReservation = deleteReservation;
window.editUser = editUser;
window.deleteUser = deleteUser;
window.viewReservationDetail = viewReservationDetail;

// Fungsi untuk Load Dummy Artikel
function loadDummyArticles() {
    const adminArticleTable = document.getElementById('adminArticleTable');
    if (!adminArticleTable) return;

    adminArticleTable.innerHTML = '';
    dummyData.blogPosts.forEach(article => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${article.id}</td>
            <td>${article.title}</td>
            <td>Admin</td>
            <td>${new Date().toLocaleDateString('id-ID')}</td>
            <td><span class="status-badge status-confirmed">Publish</span></td>
            <td>
                <button class="btn btn-secondary btn-small" onclick="editArticle(${article.id})">Edit</button>
                <button class="btn btn-danger btn-small" onclick="deleteArticle(${article.id})">Hapus</button>
            </td>
        `;
        adminArticleTable.appendChild(row);
    });
}

// Fungsi untuk Edit Artikel
function editArticle(articleId) {
    const article = dummyData.blogPosts.find(a => a.id === articleId);
    if (!article) return;

    const modalContent = `
        <form id="editArticleModalForm">
            <div class="form-group">
                <label for="editArticleTitle" class="form-label">Judul</label>
                <input type="text" id="editArticleTitle" class="form-input" value="${article.title}" required>
            </div>
            <div class="form-group">
                <label for="editArticleExcerpt" class="form-label">Kutipan</label>
                <textarea id="editArticleExcerpt" class="form-input" rows="3" required>${article.excerpt}</textarea>
            </div>
        </form>
    `;

    showModal(
        'Edit Artikel',
        modalContent,
        [
            {
                text: 'Simpan Perubahan',
                class: 'btn-primary',
                action: () => {
                    article.title = document.getElementById('editArticleTitle').value;
                    article.excerpt = document.getElementById('editArticleExcerpt').value;
                    loadDummyArticles();
                    showToast('Artikel berhasil diperbarui', 'success');
                    closeModal();
                }
            },
            { text: 'Batal', class: 'btn-secondary', action: closeModal }
        ]
    );
}

// Fungsi untuk Hapus Artikel
function deleteArticle(articleId) {
    showModal(
        'Konfirmasi Hapus',
        `<p>Apakah Anda yakin ingin menghapus artikel ini?</p>`,
        [
            {
                text: 'Hapus',
                class: 'btn-danger',
                action: () => {
                    const idx = dummyData.blogPosts.findIndex(a => a.id === articleId);
                    if (idx !== -1) dummyData.blogPosts.splice(idx, 1);
                    loadDummyArticles();
                    showToast('Artikel berhasil dihapus', 'success');
                    closeModal();
                }
            },
            { text: 'Batal', class: 'btn-secondary', action: closeModal }
        ]
    );
}

// Export fungsi artikel ke global scope
window.editArticle = editArticle;
window.deleteArticle = deleteArticle;
