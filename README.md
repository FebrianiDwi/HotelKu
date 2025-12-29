# ReservaStay - Aplikasi Manajemen Reservasi Hotel

Aplikasi web full-stack untuk manajemen reservasi akomodasi/hotel dengan fitur lengkap untuk user dan admin. Dibangun dengan PHP, MySQL, HTML, CSS, dan JavaScript.

## 🚀 Instalasi & Setup

### Requirements
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web Server (Apache/Nginx) - Laragon/XAMPP/WAMP
- Browser modern (Chrome, Firefox, Edge, Safari)

### Langkah Instalasi

1. **Clone atau Download Project**
   ```bash
   # Letakkan di folder web server
   # Contoh: C:\laragon\www\HotelKu
   # Atau: C:\xampp\htdocs\HotelKu
   ```

2. **Setup Database**
   - Buka phpMyAdmin: `http://localhost/phpmyadmin`
   - Buat database baru: `db_hotel` (atau sesuaikan di `config/koneksi.php`)
   - Import file `db_hotel.sql` ke database tersebut
   - Pastikan semua tabel berhasil dibuat

3. **Konfigurasi Database**
   - Edit file `config/koneksi.php` jika perlu (default sudah sesuai untuk Laragon):
     ```php
     $dbHost = 'localhost';
     $dbUser = 'root';
     $dbPass = '';
     $dbName = 'db_hotel';
     ```

4. **Setup Folder Upload**
   - Pastikan folder `res/` ada dan memiliki permission write
   - Folder ini digunakan untuk menyimpan gambar blog dan room types

5. **Akses Aplikasi**
   - URL: `http://localhost/HotelKu/`
   - Atau: `http://127.0.0.1/HotelKu/`
   - Pastikan web server (Apache) sudah running

## 👤 Akun Demo / Dummy Account

### Admin Account
- **Email:** `User@gmail.com`
- **Password:** `admin123`
- **Role:** Admin
- **Akses:** Full access ke semua fitur admin

### User Account
- **Email:** `geger@gma`
- **Password:** `21212121`
- **Role:** User
- **Akses:** Fitur user biasa (reservasi, blog, profil)

> **Catatan:** Akun-akun ini sudah tersedia di database setelah import `db_hotel.sql`. Jika tidak bisa login, pastikan database sudah di-import dengan benar.

## 📁 Struktur Folder

```
HotelKu/
├── config/
│   └── koneksi.php          # Konfigurasi database connection
├── controllers/              # Controller untuk handling request
│   ├── blog_process.php      # CRUD blog posts
│   ├── get_blog_api.php      # API get blog by ID
│   ├── room_type_process.php # CRUD room types
│   ├── get_room_type_api.php # API get room type by ID
│   ├── user_process.php      # CRUD users
│   ├── get_user_api.php      # API get user by ID
│   └── log_activity.php      # Helper untuk activity logging
├── models/                   # Model untuk database operations
│   ├── ReservationModel.php  # Model untuk reservasi
│   ├── UserModel.php         # Model untuk users
│   ├── BlogModel.php         # Model untuk blog posts
│   ├── RoomTypeModel.php     # Model untuk room types
│   ├── CancellationModel.php # Model untuk cancellation
│   └── ActivityLogModel.php  # Model untuk activity logs
├── views/                    # Halaman PHP (View)
│   ├── beranda.php          # Landing page
│   ├── login_register.php   # Login & Register
│   ├── reservasi_form.php   # Form reservasi
│   ├── blog.php             # List blog posts
│   ├── blog_detail.php      # Detail blog post
│   ├── admin_dashboard.php  # Dashboard admin
│   ├── profil.php           # Profil user
│   ├── checkin_online.php   # Check-in online
│   └── cancel_reservasi.php # Form pembatalan
├── includes/                 # Komponen yang bisa di-include
│   ├── navbar.php           # Navigation bar
│   ├── footer.php           # Footer
│   └── admin/               # Admin dashboard components
│       ├── dashboard_data.php      # Data preparation
│       ├── dashboard_stats.php    # Statistics cards
│       ├── dashboard_charts.php   # Charts section
│       ├── dashboard_scripts.php  # Chart.js scripts
│       └── dashboard_crud_scripts.php # CRUD JavaScript
├── res/                      # Folder untuk upload gambar
│   └── (uploaded images)     # Blog images, room type images
├── style.css                 # Stylesheet utama
├── script.js                 # JavaScript utama
├── index.php                 # Entry point / redirect
├── db_hotel.sql              # Database schema & data
└── README.md                 # Dokumentasi ini
```

## ✨ Fitur Lengkap

### Untuk User/Pengunjung

#### 1. Landing Page (`beranda.php`)
- ✅ Ringkasan layanan hotel
- ✅ Fitur utama yang ditawarkan
- ✅ Cara melakukan reservasi
- ✅ Informasi kontak lengkap
- ✅ Design responsive dan modern

#### 2. Halaman Reservasi (`reservasi_form.php`)
- ✅ Form pemesanan kamar lengkap
- ✅ Pilih tipe kamar (Standard, Deluxe, Suite)
- ✅ Pilih tanggal check-in dan check-out
- ✅ Input jumlah tamu/kamar
- ✅ Data kontak (nama, email, telepon)
- ✅ **Requires Login** - Harus login terlebih dahulu
- ✅ Validasi form client-side dan server-side

#### 3. Blog & Artikel (`blog.php` & `blog_detail.php`)
- ✅ List artikel blog dengan pagination
- ✅ Minimal 3 artikel (informasi layanan, tips perjalanan/akomodasi)
- ✅ Klik artikel untuk melihat detail lengkap
- ✅ Gambar thumbnail untuk setiap artikel
- ✅ Informasi penulis dan tanggal publish
- ✅ Slug-based URL untuk SEO friendly

#### 4. Check-in Online (`checkin_online.php`)
- ✅ Halaman untuk melakukan check-in
- ✅ Input data booking (booking code)
- ✅ Verifikasi reservasi
- ✅ Update status reservasi

#### 5. Pengajuan Pembatalan (`cancel_reservasi.php`)
- ✅ Form pengajuan pembatalan
- ✅ Input alasan pembatalan
- ✅ Status permohonan dapat dilihat
- ✅ Tracking status (pending, approved, rejected)

#### 6. Infografis / Rekap Data
- ✅ Halaman visual dengan grafik
- ✅ Grafik ringkasan reservasi
- ✅ Grafik pembatalan
- ✅ Grafik okupansi kamar
- ✅ Dashboard ringkas untuk user atau publik

#### 7. Autentikasi (`login_register.php`)
- ✅ Registrasi user baru (email + password)
- ✅ Login dengan email dan password
- ✅ Password hashing dengan bcrypt
- ✅ Session management
- ✅ Remember me functionality
- ✅ Redirect setelah login

#### 8. Profil Pengguna (`profil.php`)
- ✅ Lihat riwayat reservasi
- ✅ Status pembatalan
- ✅ Edit data pribadi
- ✅ Update password
- ✅ Statistik reservasi pribadi

### Untuk Admin

#### 1. Dashboard Admin (`admin_dashboard.php`)
- ✅ Statistik lengkap:
  - Total Reservasi
  - Reservasi Aktif
  - Tingkat Okupansi (%)
  - Tingkat Pembatalan (%)
- ✅ Grafik & Infografis:
  - Line Chart: Reservasi per Bulan (12 bulan terakhir)
  - Doughnut Chart: Distribusi Tipe Kamar
  - Menggunakan Chart.js untuk visualisasi
- ✅ Tabel transaksi dan data real-time
- ✅ Quick actions untuk manajemen

#### 2. Manajemen User & Userlevel
- ✅ Tabel semua users dengan pagination
- ✅ Tambah user baru (Create)
- ✅ Edit user (Update)
- ✅ Hapus user (Delete)
- ✅ Set role: Admin, Staff, User
- ✅ Set status: Active, Inactive
- ✅ Update password user
- ✅ Validasi sebelum delete (cek active reservations)

#### 3. Manajemen Reservasi
- ✅ Tabel semua reservasi
- ✅ Filter dan sorting
- ✅ Edit detail reservasi
- ✅ Update status reservasi
- ✅ Hapus reservasi (dengan validasi)

#### 4. Manajemen Pembatalan
- ✅ Tabel pembatalan yang menunggu persetujuan
- ✅ Approve pembatalan:
  - Update status reservasi menjadi 'cancelled'
  - Record admin response
  - Optional: catatan refund
- ✅ Reject pembatalan:
  - Update status cancellation menjadi 'rejected'
  - Berikan alasan penolakan
- ✅ History semua pembatalan

#### 5. Manajemen Konten Blog
- ✅ CRUD lengkap untuk artikel blog:
  - **Create:** Tambah artikel baru
  - **Read:** List semua artikel dengan gambar
  - **Update:** Edit artikel (judul, konten, gambar, status)
  - **Delete:** Hapus artikel
- ✅ Upload gambar artikel (disimpan di `res/`)
- ✅ Atau gunakan URL gambar eksternal
- ✅ Status: Draft, Published, Archived
- ✅ Auto-generate slug untuk URL
- ✅ View counter untuk setiap artikel

#### 6. Manajemen Layanan/Inventori (Room Types)
- ✅ CRUD lengkap untuk tipe kamar:
  - **Create:** Tambah tipe kamar baru
  - **Read:** List semua tipe kamar
  - **Update:** Edit tipe kamar (harga, fasilitas, gambar, status)
  - **Delete:** Hapus tipe kamar (dengan validasi)
- ✅ Field yang tersedia:
  - Kode Tipe (type_code)
  - Nama Tipe (type_name)
  - Deskripsi
  - Harga per Malam
  - Max Occupancy
  - Fasilitas (features)
  - Gambar (upload atau URL)
  - Status (Active/Inactive)
- ✅ Upload gambar room type (disimpan di `res/`)
- ✅ Validasi sebelum delete (cek apakah digunakan di reservasi)

#### 7. Log Aktivitas
- ✅ Tabel log aktivitas admin
- ✅ Record semua tindakan penting:
  - Create, Update, Delete operations
  - Entity type dan ID
  - Deskripsi action
  - User yang melakukan
  - Waktu dan tanggal
  - IP Address dan User Agent
- ✅ Audit trail untuk keamanan

## 🔐 Keamanan

- ✅ Password hashing menggunakan bcrypt
- ✅ SQL injection protection (prepared statements / mysqli_real_escape_string)
- ✅ XSS protection (htmlspecialchars)
- ✅ Session management untuk autentikasi
- ✅ Role-based access control (Admin, Staff, User)
- ✅ Admin route protection
- ✅ File upload validation (type, size)
- ✅ CSRF protection (dapat ditambahkan)

## 🎨 Design & UI/UX

- ✅ Responsive design (mobile-friendly)
- ✅ Modern dan clean UI
- ✅ Color palette yang konsisten
- ✅ Font awesome icons
- ✅ Smooth transitions dan animations
- ✅ Modal dialogs untuk CRUD operations
- ✅ Loading states dan feedback
- ✅ Error handling yang user-friendly

## 📊 Database Schema

### Tabel Utama:
- `users` - Data pengguna (admin, staff, user)
- `reservations` - Data reservasi
- `room_types` - Tipe-tipe kamar
- `blog_posts` - Artikel blog
- `cancellations` - Data pembatalan
- `activity_logs` - Log aktivitas admin
- `rooms` - Data kamar (opsional)

### Relasi:
- `reservations.user_id` → `users.id`
- `reservations.room_type_id` → `room_types.id`
- `blog_posts.author_id` → `users.id`
- `cancellations.reservation_id` → `reservations.id`
- `activity_logs.user_id` → `users.id`

## 🛠️ Teknologi yang Digunakan

- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript (ES6+)
- **Libraries:**
  - Chart.js 4.4.0 (untuk grafik)
  - Font Awesome 6.4.0 (untuk icons)
- **Architecture:** MVC Pattern (Models, Views, Controllers)
- **Server:** Apache/Nginx (via Laragon/XAMPP)

## 📝 Catatan Penting

- ✅ Semua data sekarang sudah terhubung dengan database (tidak lagi dummy data)
- ✅ Password di-hash menggunakan bcrypt (PASSWORD_BCRYPT)
- ✅ Responsive design (desktop + mobile)
- ✅ Session management untuk autentikasi
- ✅ File upload disimpan di folder `res/`
- ✅ Image validation: JPEG, PNG, GIF, WebP (max 5MB)
- ✅ Slug generation untuk blog posts (SEO friendly)
- ✅ Activity logging untuk audit trail
- ✅ Backward compatibility untuk kolom database yang optional

## 🐛 Troubleshooting

### Database Connection Error
- ✅ Pastikan MySQL sudah running di Laragon/XAMPP
- ✅ Cek konfigurasi di `config/koneksi.php`
- ✅ Pastikan database `db_hotel` sudah dibuat
- ✅ Pastikan file `db_hotel.sql` sudah di-import

### Page Not Found / 404 Error
- ✅ Pastikan folder project berada di `www` folder Laragon atau `htdocs` XAMPP
- ✅ Cek URL: `http://localhost/HotelKu/` (sesuai nama folder)
- ✅ Pastikan Apache web server sudah running
- ✅ Cek file `.htaccess` jika ada

### Session Issues / Logout Terus
- ✅ Pastikan `session_start()` ada di file yang membutuhkan session
- ✅ Clear browser cache dan cookies
- ✅ Cek PHP session configuration di `php.ini`
- ✅ Pastikan folder session writable

### Upload Image Error
- ✅ Pastikan folder `res/` ada dan memiliki permission write (chmod 755 atau 777)
- ✅ Cek `php.ini` untuk `upload_max_filesize` dan `post_max_size`
- ✅ Pastikan file type sesuai (JPEG, PNG, GIF, WebP)
- ✅ Pastikan file size tidak melebihi 5MB

### Chart Tidak Muncul
- ✅ Pastikan Chart.js CDN ter-load (cek network tab di browser)
- ✅ Pastikan data dari PHP tidak kosong
- ✅ Cek console browser untuk error JavaScript
- ✅ Pastikan canvas element ada di DOM

### Login Tidak Bisa
- ✅ Pastikan database sudah di-import dengan benar
- ✅ Cek email dan password sesuai dengan dummy account
- ✅ Pastikan password di database sudah di-hash dengan bcrypt
- ✅ Cek error log PHP untuk detail error

### CRUD Operations Tidak Bekerja
- ✅ Pastikan user sudah login sebagai admin
- ✅ Cek browser console untuk error JavaScript
- ✅ Cek network tab untuk melihat response API
- ✅ Pastikan file controller ada dan accessible
- ✅ Cek permission folder `res/` untuk upload

## 📄 License

Proyek ini dibuat untuk keperluan edukasi dan pembelajaran.

---

**Happy Coding! 🚀**

*Last Updated: 2024*
