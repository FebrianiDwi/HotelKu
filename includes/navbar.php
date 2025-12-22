<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!-- Header -->
<header class="header">
    <div class="container">
        <div class="header-content">
            <a href="beranda.php" class="logo">Hotel<span>Ku</span></a>
            <nav>
                <ul class="nav-links" id="navLinks">
                    <li><a href="beranda.php" class="nav-link <?php echo ($currentPage == 'beranda') ? 'active' : ''; ?>">Beranda</a></li>
                    <li><a href="reservasi_form.php" class="nav-link <?php echo ($currentPage == 'reservasi_form') ? 'active' : ''; ?>">Reservasi</a></li>
                    <li><a href="login_register.php" class="nav-link <?php echo ($currentPage == 'login_register') ? 'active' : ''; ?>">Masuk</a></li>
                    <li><a href="profil.php" class="nav-link <?php echo ($currentPage == 'profil') ? 'active' : ''; ?>">Profil</a></li>
                    <li><a href="checkin_online.php" class="nav-link <?php echo ($currentPage == 'checkin_online') ? 'active' : ''; ?>">Check-in</a></li>
                    <li><a href="cancel_reservasi.php" class="nav-link <?php echo ($currentPage == 'cancel_reservasi') ? 'active' : ''; ?>">Pembatalan</a></li>
                    <li><a href="blog.php" class="nav-link <?php echo ($currentPage == 'blog') ? 'active' : ''; ?>">Blog</a></li>
                    <li><a href="admin_dashboard.php" class="nav-link <?php echo ($currentPage == 'admin_dashboard') ? 'active' : ''; ?>">Dashboard</a></li>
                </ul>
            </nav>
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>

