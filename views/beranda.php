<?php
$pageTitle = 'ReservaStay - Beranda';
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
    <main id="mainContent">
        <section id="home" class="page active">
            <div class="hero">
                <div class="container">
                    <div class="hero-content">
                        <div class="hero-text slide-in-left">
                            <h1 class="hero-title">Reservasi Akomodasi Jadi Lebih Mudah</h1>
                            <p class="hero-subtitle">Platform digital terpercaya dengan check-in online, pembayaran aman, dan layanan 24 jam</p>
                            <a href="reservasi_form.php" class="btn btn-primary">Reservasi Sekarang</a>
                        </div>
                        <div class="hero-image slide-in-right">
                            <div class="hero-image-placeholder" style="width: 100%; height: 300px; background-color: var(--primary-light); border-radius: var(--border-radius); display: flex; align-items: center; justify-content: center; color: var(--primary-color);">
                                <i class="fas fa-hotel" style="font-size: 10rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="stats-section">
                <div class="container">
                    <div class="stats-container">
                        <div class="stat-item">
                            <div class="stat-number">10K+</div>
                            <div class="stat-label">Tamu Puas</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">4.9</div>
                            <div class="stat-label">Rating Google</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">Layanan</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Features Section -->
            <div class="features">
                <div class="container">
                    <h2 class="section-title">Mengapa Memilih HotelKu?</h2>
                    <p class="text-center" style="margin-bottom: 50px; color: var(--gray-dark); max-width: 700px; margin-left: auto; margin-right: auto;">Kami menghadirkan pengalaman menginap yang modern dengan teknologi terkini</p>
                    <div class="features-grid">
                        <div class="feature-card fade-in">
                            <div class="feature-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <h3 class="feature-title">Check-in Online 24/7</h3>
                            <p>Proses check-in cepat dan mudah tanpa antrean, kapan saja dan di mana saja.</p>
                        </div>
                        <div class="feature-card fade-in">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3 class="feature-title">Pembayaran Aman</h3>
                            <p>Sistem pembayaran terenkripsi dengan berbagai metode pembayaran.</p>
                        </div>
                        <div class="feature-card fade-in">
                            <div class="feature-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3 class="feature-title">Lokasi Strategis</h3>
                            <p>Dekat dengan pusat kota dan tempat wisata utama.</p>
                        </div>
                        <div class="feature-card fade-in">
                            <div class="feature-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="feature-title">Layanan 24 Jam</h3>
                            <p>Tim kami siap membantu Anda kapan saja dengan respon cepat.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Booking Steps -->
            <div class="booking-steps">
                <div class="container">
                    <h2 class="section-title">Alur Reservasi yang Simpel</h2>
                    <p class="text-center" style="margin-bottom: 50px; color: var(--gray-dark); max-width: 700px; margin-left: auto; margin-right: auto;">Hanya 4 langkah mudah untuk mendapatkan kamar impian Anda</p>
                    <div class="steps-container">
                        <div class="step fade-in">
                            <div class="step-number">1</div>
                            <h3 class="step-title">Pilih Kamar</h3>
                            <p>Browse berbagai tipe kamar sesuai kebutuhan dan budget Anda</p>
                        </div>
                        <div class="step fade-in">
                            <div class="step-number">2</div>
                            <h3 class="step-title">Isi Data</h3>
                            <p>Lengkapi informasi tamu dan detail reservasi dengan mudah</p>
                        </div>
                        <div class="step fade-in">
                            <div class="step-number">3</div>
                            <h3 class="step-title">Konfirmasi</h3>
                            <p>Dapatkan kode booking dan konfirmasi instan via email</p>
                        </div>
                        <div class="step fade-in">
                            <div class="step-number">4</div>
                            <h3 class="step-title">Check-in</h3>
                            <p>Lakukan check-in online sebelum kedatangan untuk pengalaman seamless</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Room Types -->
            <div class="room-types">
                <div class="container">
                    <h2 class="section-title">Pilihan Kamar Kami</h2>
                    <p class="text-center" style="margin-bottom: 50px; color: var(--gray-dark); max-width: 700px; margin-left: auto; margin-right: auto;">Berbagai tipe kamar dengan fasilitas lengkap untuk kenyamanan Anda</p>
                    <div class="rooms-grid">
                        <div class="room-card fade-in">
                            <div class="room-image">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div class="room-content">
                                <h3 class="room-title">Standard Room</h3>
                                <div class="room-price">Rp 500.000/malam</div>
                                <div class="room-features">
                                    <div class="room-feature"><i class="fas fa-wifi"></i> WiFi Gratis</div>
                                    <div class="room-feature"><i class="fas fa-snowflake"></i> AC</div>
                                    <div class="room-feature"><i class="fas fa-tv"></i> TV Kabel</div>
                                    <div class="room-feature"><i class="fas fa-coffee"></i> Sarapan</div>
                                </div>
                                <a href="reservasi_form.php?room_type=standard" class="btn btn-primary" style="width: 100%;">Pilih Kamar</a>
                            </div>
                        </div>
                        
                        <div class="room-card fade-in">
                            <div class="room-image">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div class="room-content">
                                <h3 class="room-title">Deluxe Room</h3>
                                <div class="room-price">Rp 850.000/malam</div>
                                <div class="room-features">
                                    <div class="room-feature"><i class="fas fa-wifi"></i> WiFi Gratis</div>
                                    <div class="room-feature"><i class="fas fa-snowflake"></i> AC</div>
                                    <div class="room-feature"><i class="fas fa-tv"></i> TV Kabel</div>
                                    <div class="room-feature"><i class="fas fa-coffee"></i> Sarapan</div>
                                    <div class="room-feature"><i class="fas fa-glass-whiskey"></i> Mini Bar</div>
                                    <div class="room-feature"><i class="fas fa-bath"></i> Bathtub</div>
                                </div>
                                <a href="reservasi_form.php?room_type=deluxe" class="btn btn-primary" style="width: 100%;">Pilih Kamar</a>
                            </div>
                        </div>
                        
                        <div class="room-card fade-in">
                            <div class="room-image">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div class="room-content">
                                <h3 class="room-title">Suite Room</h3>
                                <div class="room-price">Rp 1.500.000/malam</div>
                                <div class="room-features">
                                    <div class="room-feature"><i class="fas fa-wifi"></i> WiFi Gratis</div>
                                    <div class="room-feature"><i class="fas fa-snowflake"></i> AC</div>
                                    <div class="room-feature"><i class="fas fa-tv"></i> TV Kabel</div>
                                    <div class="room-feature"><i class="fas fa-coffee"></i> Sarapan</div>
                                    <div class="room-feature"><i class="fas fa-couch"></i> Ruang Tamu</div>
                                    <div class="room-feature"><i class="fas fa-city"></i> City View</div>
                                    <div class="room-feature"><i class="fas fa-hot-tub"></i> Jacuzzi</div>
                                </div>
                                <a href="reservasi_form.php?room_type=suite" class="btn btn-primary" style="width: 100%;">Pilih Kamar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Testimonials -->
            <div class="testimonials">
                <div class="container">
                    <h2 class="section-title">Apa Kata Tamu Kami</h2>
                    <p class="text-center" style="margin-bottom: 50px; color: var(--gray-dark); max-width: 700px; margin-left: auto; margin-right: auto;">Kepuasan tamu adalah prioritas utama kami</p>
                    <div class="testimonials-grid">
                        <div class="testimonial-card fade-in">
                            <p class="testimonial-text">"Pengalaman menginap yang luar biasa! Check-in online sangat memudahkan dan kamarnya bersih & nyaman."</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="author-info">
                                    <h4>Sarah Johnson</h4>
                                    <p>2 minggu lalu</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="testimonial-card fade-in">
                            <p class="testimonial-text">"Pelayanan sangat memuaskan, lokasi strategis, dan harga terjangkau. Sangat direkomendasikan!"</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="author-info">
                                    <h4>Michael Chen</h4>
                                    <p>1 bulan lalu</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="testimonial-card fade-in">
                            <p class="testimonial-text">"Sistem reservasi mudah dipahami, staff ramah, fasilitas lengkap. Pasti akan kembali lagi!"</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="author-info">
                                    <h4>Diana Putri</h4>
                                    <p>3 minggu lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- CTA Section -->
            <div class="cta-section">
                <div class="container">
                    <h2 class="cta-title">Siap untuk Pengalaman Menginap Terbaik?</h2>
                    <p class="cta-subtitle">Dapatkan penawaran spesial dengan reservasi hari ini!</p>
                    <a href="reservasi_form.php" class="btn btn-secondary" style="font-size: 1.1rem; padding: 15px 40px;">Reservasi Sekarang</a>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script src="../script.js"></script>
    <script>
    // Inisialisasi Aplikasi
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi komponen
        initModals();
        initAnimations();
    });
    </script>
</body>
</html>

