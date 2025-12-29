function handleHashChange() {
    const pageId = window.location.hash.substring(1);
    if (pageId) showPage(pageId);
}

function initNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const navLinksContainer = document.getElementById('navLinks');
    
    if (!navLinks || navLinks.length === 0) return;
    
    // Hanya handle link dengan hash (#) untuk single-page navigation
    // Link PHP (beranda.php, profil.php, dll) TIDAK diintervensi - biarkan browser redirect normal
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        // Hanya attach event listener untuk link dengan hash (#)
        if (href && href.trim().startsWith('#')) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const pageId = href.substring(1);
                window.location.hash = pageId;
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                if (typeof showPage === 'function') showPage(pageId);
                if (navLinksContainer) navLinksContainer.classList.remove('active');
            });
        }
        // Link PHP tidak perlu event listener - browser akan handle redirect secara normal
    });
    
    if (mobileMenuBtn && navLinksContainer) {
        mobileMenuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            navLinksContainer.classList.toggle('active');
        });
    }
    
    if (navLinksContainer && mobileMenuBtn) {
        document.addEventListener('click', (e) => {
            if (!navLinksContainer.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                navLinksContainer.classList.remove('active');
            }
        });
    }
}

function showPage(pageId) {
    document.querySelectorAll('.page').forEach(page => page.classList.remove('active'));
    const activePage = document.getElementById(pageId);
    if (activePage) {
        activePage.classList.add('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => triggerAnimations(), 100);
        updateNavigation(pageId);
    }
}

function updateNavigation(activePageId) {
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${activePageId}`) link.classList.add('active');
    });
}

function initForms() {
    const reservationForm = document.getElementById('reservationForm');
    if (reservationForm) {
        reservationForm.addEventListener('submit', handleReservationSubmit);
        const roomTypeSelect = document.getElementById('roomType');
        const roomCountInput = document.getElementById('roomCount');
        const checkinInput = document.getElementById('checkin');
        const checkoutInput = document.getElementById('checkout');
        
        const today = new Date().toISOString().split('T')[0];
        if (checkinInput) checkinInput.min = today;
        if (checkoutInput) checkoutInput.min = today;
        
        if (roomTypeSelect) roomTypeSelect.addEventListener('change', updatePriceSummary);
        if (roomCountInput) roomCountInput.addEventListener('input', updatePriceSummary);
        if (checkinInput && checkoutInput) {
            checkinInput.addEventListener('change', function() {
                checkoutInput.min = this.value;
                updatePriceSummary();
            });
            checkoutInput.addEventListener('change', updatePriceSummary);
        }
        updatePriceSummary();
    }
    
    const showRegisterLink = document.getElementById('showRegister');
    const showLoginLink = document.getElementById('showLogin');
    const loginFormContainer = document.getElementById('loginFormContainer');
    const registerFormContainer = document.getElementById('registerFormContainer');
    
    if (showRegisterLink) {
        showRegisterLink.addEventListener('click', (e) => {
            e.preventDefault();
            loginFormContainer.classList.add('hidden');
            registerFormContainer.classList.remove('hidden');
        });
    }
    
    if (showLoginLink) {
        showLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            registerFormContainer.classList.add('hidden');
            loginFormContainer.classList.remove('hidden');
        });
    }
    
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            if (!handleLoginSubmit(e)) e.preventDefault();
        });
    }
    
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) logoutBtn.addEventListener('click', handleLogout);
}

function handleReservationSubmit(e) {
    if (!validateReservationForm()) {
        e.preventDefault();
        return false;
    }
    return true;
}

function validateReservationForm() {
    const fields = [
        { id: 'roomType', msg: 'Harap pilih tipe kamar' },
        { id: 'roomCount', msg: 'Jumlah kamar harus antara 1-10', validate: (v) => v >= 1 && v <= 10 },
        { id: 'checkin', msg: 'Harap pilih tanggal check-in' },
        { id: 'checkout', msg: 'Harap pilih tanggal check-out', 
          validate: (v, checkin) => v && (!checkin || v > checkin) },
        { id: 'fullName', msg: 'Harap masukkan nama lengkap' },
        { id: 'email', msg: 'Harap masukkan email', 
          validate: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) },
        { id: 'phone', msg: 'Format telepon tidak valid (10-13 digit)',
          validate: (v) => /^[0-9]{10,13}$/.test(v.replace(/\D/g, '')) }
    ];
    
    let isValid = true;
    const checkin = document.getElementById('checkin')?.value;
    
    fields.forEach(field => {
        const el = document.getElementById(field.id);
        const feedback = document.getElementById(field.id + 'Feedback');
        const value = el?.value?.trim();
        
        if (!value || (field.validate && !field.validate(value, checkin))) {
            el?.classList.add('error');
            if (feedback) {
                feedback.textContent = field.msg;
                feedback.className = 'form-feedback error-message';
            }
            isValid = false;
        } else {
            el?.classList.remove('error');
            if (feedback) feedback.textContent = '';
        }
    });
    
    return isValid;
}

function updatePriceSummary() {
    const roomTypeSelect = document.getElementById('roomType');
    const roomCountInput = document.getElementById('roomCount');
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');
    const priceDetails = document.getElementById('priceDetails');
    const totalPrice = document.getElementById('totalPrice');
    
    if (!roomTypeSelect || !priceDetails || !totalPrice) return;
    
    const selectedOption = roomTypeSelect.options[roomTypeSelect.selectedIndex];
    const pricePerNight = selectedOption?.dataset?.price;
    
    if (!pricePerNight) {
        priceDetails.innerHTML = '<div>Pilih tipe kamar untuk melihat harga</div>';
        totalPrice.textContent = '';
        return;
    }
    
    const roomCount = parseInt(roomCountInput?.value) || 1;
    let nights = 1;
    
    if (checkinInput?.value && checkoutInput?.value) {
        const timeDiff = new Date(checkoutInput.value) - new Date(checkinInput.value);
        nights = Math.max(1, Math.ceil(timeDiff / (1000 * 3600 * 24)));
    }
    
    const total = parseFloat(pricePerNight) * roomCount * nights;
    priceDetails.innerHTML = `
        <div>Tipe Kamar: <strong>${selectedOption.text.split(' - ')[0]}</strong></div>
        <div>Harga per Malam: <strong>Rp ${parseFloat(pricePerNight).toLocaleString('id-ID')}</strong></div>
        <div>Jumlah Kamar: <strong>${roomCount}</strong></div>
        <div>Jumlah Malam: <strong>${nights}</strong></div>
    `;
    totalPrice.textContent = `Total: Rp ${total.toLocaleString('id-ID')}`;
}

function handleLoginSubmit(e) {
    const email = document.getElementById('loginEmail')?.value;
    const password = document.getElementById('loginPassword')?.value;
    
    if (!email || !password) {
        showToast('Harap isi semua field', 'error');
        return false;
    }
    return true;
}

function handleLogout() {
    showToast('Berhasil keluar dari akun', 'success');
    setTimeout(() => {
        window.location.hash = 'login';
        document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
        const loginLink = document.querySelector('a[href="#login"]');
        if (loginLink) loginLink.classList.add('active');
    }, 1000);
}


function initModals() {
    const modalOverlay = document.getElementById('modalOverlay');
    const modalClose = document.getElementById('modalClose');
    
    if (modalClose) modalClose.addEventListener('click', closeModal);
    if (modalOverlay) {
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) closeModal();
        });
    }
    
    const addReservationBtn = document.getElementById('addReservationBtn');
    if (addReservationBtn) addReservationBtn.addEventListener('click', showAddReservationModal);
    
    const addUserBtn = document.getElementById('addUserBtn');
    if (addUserBtn) addUserBtn.addEventListener('click', showAddUserModal);
    
    const refreshDataBtn = document.getElementById('refreshDataBtn');
    if (refreshDataBtn) {
        refreshDataBtn.addEventListener('click', () => {
            showLoading();
            setTimeout(() => { hideLoading(); location.reload(); }, 800);
        });
    }
}

function showModal(title, body, buttons) {
    const modalOverlay = document.getElementById('modalOverlay');
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');
    const modalFooter = document.getElementById('modalFooter');
    
    if (!modalOverlay || !modalTitle || !modalBody || !modalFooter) return;
    
    modalTitle.textContent = title;
    modalBody.innerHTML = body;
    modalFooter.innerHTML = '';
    
    if (buttons?.length > 0) {
        buttons.forEach(btn => {
            const button = document.createElement('button');
            button.className = `btn ${btn.class}`;
            button.textContent = btn.text;
            button.addEventListener('click', btn.action);
            modalFooter.appendChild(button);
        });
    } else {
        const closeBtn = document.createElement('button');
        closeBtn.className = 'btn btn-secondary';
        closeBtn.textContent = 'Tutup';
        closeBtn.addEventListener('click', closeModal);
        modalFooter.appendChild(closeBtn);
    }
    
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modalOverlay = document.getElementById('modalOverlay');
    if (modalOverlay) {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

function showAddReservationModal() {
    const roomTypes = (window.dashboardRoomTypes || []).filter(rt => rt.status === 'active');
    const roomTypeOptions = '<option value="">Pilih Tipe Kamar</option>' + 
        roomTypes.map(rt => `<option value="${rt.id}">${rt.type_name} (${rt.type_code}) - Rp ${parseInt(rt.price_per_night).toLocaleString('id-ID')}/malam</option>`).join('');

    const modalContent = `
        <form id="addReservationModalForm">
            <div class="form-group">
                <label for="modalRoomType" class="form-label">Tipe Kamar</label>
                <select id="modalRoomType" class="form-select" required>${roomTypeOptions}</select>
            </div>
            <div class="form-group">
                <label for="modalRoomCount" class="form-label">Jumlah Kamar</label>
                <input type="number" id="modalRoomCount" class="form-input" min="1" max="10" value="1" required>
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
                <label for="modalSpecialRequests" class="form-label">Permintaan Khusus (Opsional)</label>
                <textarea id="modalSpecialRequests" class="form-textarea" rows="3" placeholder="Masukkan permintaan khusus"></textarea>
            </div>
            <div class="form-group">
                <label for="modalStatus" class="form-label">Status</label>
                <select id="modalStatus" class="form-select" required>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="checked_in">Check-in</option>
                    <option value="checked_out">Check-out</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </form>
    `;
    
    showModal('Tambah Reservasi Baru', modalContent, [
        { text: 'Simpan', class: 'btn-primary', action: () => submitReservation() },
        { text: 'Batal', class: 'btn-secondary', action: closeModal }
    ]);
    
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

function submitReservation() {
    const getValue = (id) => document.getElementById(id)?.value || '';
    
    const data = {
        roomTypeId: getValue('modalRoomType'),
        roomCount: getValue('modalRoomCount'),
        checkin: getValue('modalCheckin'),
        checkout: getValue('modalCheckout'),
        guestName: getValue('modalGuestName'),
        guestEmail: getValue('modalGuestEmail'),
        guestPhone: getValue('modalGuestPhone'),
        specialRequests: getValue('modalSpecialRequests'),
        status: getValue('modalStatus')
    };
    
    if (!data.roomTypeId || !data.checkin || !data.checkout || !data.guestName || !data.guestEmail || !data.guestPhone) {
        showToast('Harap isi semua field yang wajib', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'create');
    formData.append('room_type_id', data.roomTypeId);
    formData.append('room_count', data.roomCount);
    formData.append('checkin_date', data.checkin);
    formData.append('checkout_date', data.checkout);
    formData.append('guest_name', data.guestName);
    formData.append('guest_email', data.guestEmail);
    formData.append('guest_phone', data.guestPhone);
    formData.append('special_requests', data.specialRequests);
    formData.append('status', data.status);

    fetch('../controllers/reservation_process.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Reservasi berhasil ditambahkan', 'success');
                closeModal();
                location.reload();
            } else {
                showToast('Error: ' + (data.error || 'Gagal menambahkan reservasi'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menambahkan reservasi', 'error');
        });
}

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
                <label for="modalUserPassword" class="form-label">Password</label>
                <input type="password" id="modalUserPassword" class="form-input" placeholder="Minimal 6 karakter" required minlength="6">
            </div>
            <div class="form-group">
                <label for="modalUserRole" class="form-label">Role</label>
                <select id="modalUserRole" class="form-select" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
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
    
    showModal('Tambah Pengguna Baru', modalContent, [
        { text: 'Simpan', class: 'btn-primary', action: () => submitUser() },
        { text: 'Batal', class: 'btn-secondary', action: closeModal }
    ]);
}

function submitUser() {
    const getValue = (id) => document.getElementById(id)?.value || '';
    
    const data = {
        firstName: getValue('modalFirstName'),
        lastName: getValue('modalLastName'),
        email: getValue('modalUserEmail'),
        phone: getValue('modalUserPhone'),
        password: getValue('modalUserPassword'),
        role: getValue('modalUserRole'),
        status: getValue('modalUserStatus')
    };
    
    if (!data.firstName || !data.lastName || !data.email || !data.phone || !data.password) {
        showToast('Harap isi semua field yang wajib', 'error');
        return;
    }
    
    if (data.password.length < 6) {
        showToast('Password minimal 6 karakter', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'create');
    formData.append('first_name', data.firstName);
    formData.append('last_name', data.lastName);
    formData.append('email', data.email);
    formData.append('phone', data.phone);
    formData.append('password', data.password);
    formData.append('role', data.role);
    formData.append('status', data.status);

    fetch('../controllers/user_process.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Pengguna berhasil ditambahkan', 'success');
                closeModal();
                location.reload();
            } else {
                showToast('Error: ' + (data.error || 'Gagal menambahkan pengguna'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menambahkan pengguna', 'error');
        });
}


function showToast(message, type = 'info') {
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;';
        document.body.appendChild(toastContainer);
    }
    
    const colors = {
        success: 'var(--success-color)',
        error: 'var(--error-color)',
        warning: 'var(--warning-color)',
        info: 'var(--info-color)'
    };
    
    const toast = document.createElement('div');
    toast.style.cssText = `padding:15px 20px;margin-bottom:10px;border-radius:var(--border-radius);color:white;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.15);transform:translateX(100%);transition:transform 0.3s ease;min-width:250px;background-color:${colors[type] || colors.info};`;
    toast.textContent = message;
    toastContainer.appendChild(toast);
    
    setTimeout(() => toast.style.transform = 'translateX(0)', 10);
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.parentNode?.removeChild(toast), 300);
    }, 3000);
}

function showLoading() {
    let loadingEl = document.getElementById('loadingOverlay');
    if (!loadingEl) {
        loadingEl = document.createElement('div');
        loadingEl.id = 'loadingOverlay';
        loadingEl.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(255,255,255,0.8);display:flex;align-items:center;justify-content:center;z-index:9999;flex-direction:column;';
        
        const spinner = document.createElement('div');
        spinner.style.cssText = 'width:50px;height:50px;border:5px solid var(--gray-light);border-top:5px solid var(--primary-color);border-radius:50%;animation:spin 1s linear infinite;';
        
        const text = document.createElement('p');
        text.textContent = 'Memproses...';
        text.style.cssText = 'margin-top:15px;color:var(--primary-color);font-weight:600;';
        
        loadingEl.appendChild(spinner);
        loadingEl.appendChild(text);
        document.body.appendChild(loadingEl);
        
        if (!document.getElementById('spinStyle')) {
            const style = document.createElement('style');
            style.id = 'spinStyle';
            style.textContent = '@keyframes spin{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}';
            document.head.appendChild(style);
        }
    } else {
        loadingEl.style.display = 'flex';
    }
}

function hideLoading() {
    const loadingEl = document.getElementById('loadingOverlay');
    if (loadingEl) loadingEl.style.display = 'none';
}

function triggerAnimations() {
    const fadeElements = document.querySelectorAll('.fade-in, .slide-in-left, .slide-in-right');
    fadeElements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        if (elementTop < window.innerHeight - 150) {
            element.classList.add('visible');
        }
    });
}

function initAnimations() {
    window.addEventListener('scroll', triggerAnimations);
    setTimeout(() => triggerAnimations(), 300);
}


document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initForms();
    initModals();
    initAnimations();
    handleHashChange();
    window.addEventListener('hashchange', handleHashChange);
});
