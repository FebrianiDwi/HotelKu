# ReservaStay - Aplikasi Manajemen Reservasi Hotel

Aplikasi web full-stack untuk manajemen reservasi akomodasi/hotel dengan fitur lengkap untuk user dan admin.

## 🚀 Instalasi & Setup

### Requirements
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web Server (Apache/Nginx) - Laragon/XAMPP/WAMP
- Browser modern

### Langkah Instalasi

1. **Clone atau Download Project**
   ```bash
   # Letakkan di folder web server
   # Contoh: C:\laragon\www\Reserveasbnd
   ```

2. **Setup Database**
   - Buka phpMyAdmin: `http://localhost/phpmyadmin`
   - Buat database baru: `db_hotel` (atau sesuaikan di `config/koneksi.php`)
   - Import file `database_schema.sql` ke database tersebut

3. **Konfigurasi Database**
   - Edit file `config/koneksi.php` jika perlu (default sudah sesuai untuk Laragon):
     ```php
     $dbHost = 'localhost';
     $dbUser = 'root';
     $dbPass = '';
     $dbName = 'db_hotel';
     ```

4. **Akses Aplikasi**
   - URL: `http://localhost/Hotelku/`
   - Atau: `http://127.0.0.1/Hotelku/`

## 👤 Akun Demo

### Admin
- Email: `User@gmail.com
- Password: `admin123`

### User
- Daftar melalui halaman Register
- Atau buat manual melalui database

## 📁 Struktur Folder

```
Hotelku/
├── config/          # Konfigurasi database
├── controllers/     # Controller untuk handling request
├── models/          # Model untuk database operations
├── views/           # Halaman PHP (View)
├── includes/        # Komponen yang bisa di-include
├── style.css        # Stylesheet utama
├── script.js        # JavaScript utama
├── index.php        # Entry point
└── database_schema.sql  # Schema database
```

## ✨ Fitur

### Untuk User/Pengunjung
- ✅ Landing Page dengan informasi lengkap
- ✅ Halaman Reservasi (Form pemesanan kamar)
- ✅ Blog & Artikel (Minimal 3 artikel)
- ✅ Check-in Online
- ✅ Pengajuan Pembatalan & Status
- ✅ Infografis / Rekap Data (Grafik & Statistik)
- ✅ Autentikasi (Login & Register)
- ✅ Profil Pengguna (Riwayat reservasi, statistik)

### Untuk Admin
- ✅ Dashboard Admin (Statistik lengkap)
- ✅ Manajemen User & Userlevel
- ✅ Manajemen Pembatalan (Approve/Reject)
- ✅ Manajemen Konten Blog (CRUD)
- ✅ Manajemen Layanan/Inventori (Room Types)
- ✅ Tabel transaksi & data

## 📝 Catatan

- Semua data sekarang sudah terhubung dengan database (tidak lagi dummy data)
- Password di-hash menggunakan bcrypt
- Responsive design (mobile-friendly)
- Session management untuk autentikasi

## 🐛 Troubleshooting

### Database Connection Error
- Pastikan MySQL sudah running di Laragon
- Cek konfigurasi di `config/koneksi.php`
- Pastikan database `db_hotel` sudah dibuat

### Page Not Found
- Pastikan folder project berada di `www` folder Laragon
- Cek URL: `http://localhost/Hotelku/` (sesuai nama folder)

### Session Issues
- Pastikan `session_start()` ada di file yang membutuhkan session
- Clear browser cache dan cookies

**Happy Coding! 🚀**

