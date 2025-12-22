<?php
$pageTitle = 'ReservaStay - Manajemen Reservasi Akomodasi';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Reset dan Variabel */
        :root {
            --primary-color: #1452F1;
            --primary-light: #e8efff;
            --primary-dark: #0d3bb0;
            --secondary-color: #2d3748;
            --light-color: #f8f9fa;
            --dark-color: #1a202c;
            --gray-light: #e2e8f0;
            --gray-medium: #a0aec0;
            --gray-dark: #4a5568;
            --success-color: #38a169;
            --warning-color: #d69e2e;
            --error-color: #e53e3e;
            --info-color: #3182ce;
            --border-radius: 8px;
            --transition-speed: 0.3s;
            --font-main: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-main);
            line-height: 1.6;
            color: var(--dark-color);
            background-color: var(--light-color);
            overflow-x: hidden;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        button, input, select, textarea {
            font-family: inherit;
            font-size: inherit;
            border: none;
            outline: none;
            background: none;
        }
        
        button {
            cursor: pointer;
        }
        
        ul {
            list-style: none;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Utility Classes */
        .hidden {
            display: none !important;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-primary {
            color: var(--primary-color);
        }
        
        .bg-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .bg-light {
            background-color: var(--light-color);
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: all var(--transition-speed) ease;
            text-align: center;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }
        
        .btn-secondary:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-danger {
            background-color: var(--error-color);
            color: white;
        }
        
        .btn-small {
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        
        .card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 24px;
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 2rem;
            margin-bottom: 30px;
            color: var(--secondary-color);
            text-align: center;
        }
        
        /* Header & Navigation */
        .header {
            background-color: white;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .logo span {
            color: var(--secondary-color);
        }
        
        .nav-links {
            display: flex;
            gap: 30px;
        }
        
        .nav-link {
            font-weight: 600;
            color: var(--secondary-color);
            position: relative;
            padding: 8px 0;
            transition: color var(--transition-speed) ease;
        }
        
        .nav-link:hover {
            color: var(--primary-color);
        }
        
        .nav-link.active {
            color: var(--primary-color);
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 3px;
        }
        
        .mobile-menu-btn {
            display: none;
            font-size: 1.5rem;
            color: var(--secondary-color);
        }
        
        /* Page Styling */
        .page {
            min-height: calc(100vh - 200px);
            padding: 60px 0;
        }
        
        /* Home/Landing Page Styles - Diperbarui sesuai Figma */
        .hero {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
        }
        
        .hero-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .hero-text {
            flex: 1;
            min-width: 300px;
            margin-bottom: 40px;
        }
        
        .hero-title {
            font-size: 3rem;
            line-height: 1.2;
            margin-bottom: 20px;
            color: var(--secondary-color);
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--gray-dark);
            margin-bottom: 30px;
            max-width: 500px;
        }
        
        .hero-image {
            flex: 1;
            min-width: 300px;
            text-align: center;
        }
        
        .hero-image img {
            max-width: 100%;
            border-radius: var(--border-radius);
        }
        
        /* Stats Section - Baru dari Figma */
        .stats-section {
            padding: 60px 0;
            background-color: white;
        }
        
        .stats-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 30px;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1.1rem;
            color: var(--gray-dark);
        }
        
        /* Features Section - Diperbarui sesuai Figma */
        .features {
            padding: 80px 0;
            background-color: var(--light-color);
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            text-align: center;
            padding: 30px 20px;
            border: 1px solid var(--gray-light);
            border-radius: var(--border-radius);
            transition: transform var(--transition-speed) ease;
            background-color: white;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary-color);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .feature-title {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }
        
        /* Booking Steps - Diperbarui sesuai Figma */
        .booking-steps {
            padding: 80px 0;
            background-color: white;
        }
        
        .steps-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 40px;
        }
        
        .step {
            flex: 1;
            min-width: 200px;
            text-align: center;
            position: relative;
        }
        
        .step-number {
            width: 60px;
            height: 60px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 20px;
        }
        
        .step-title {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }
        
        /* Room Types - Baru dari Figma */
        .room-types {
            padding: 80px 0;
            background-color: var(--light-color);
        }
        
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .room-card {
            background-color: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: transform var(--transition-speed) ease;
        }
        
        .room-card:hover {
            transform: translateY(-10px);
        }
        
        .room-image {
            height: 200px;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 3rem;
        }
        
        .room-content {
            padding: 24px;
        }
        
        .room-title {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }
        
        .room-price {
            font-size: 1.8rem;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .room-features {
            margin-bottom: 20px;
        }
        
        .room-feature {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            color: var(--gray-dark);
        }
        
        .room-feature i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        /* Testimonials - Baru dari Figma */
        .testimonials {
            padding: 80px 0;
            background-color: white;
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .testimonial-card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 30px;
            border: 1px solid var(--gray-light);
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            color: var(--gray-dark);
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-right: 15px;
        }
        
        .author-info h4 {
            margin-bottom: 5px;
            color: var(--secondary-color);
        }
        
        .author-info p {
            color: var(--gray-medium);
            font-size: 0.9rem;
        }
        
        /* CTA Section - Baru dari Figma */
        .cta-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
        }
        
        .cta-title {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        
        .cta-subtitle {
            font-size: 1.2rem;
            margin-bottom: 40px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.9;
        }
        
        /* Form Styling */
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
            color: var(--secondary-color);
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-light);
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: border-color var(--transition-speed) ease;
        }
        
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--primary-color);
        }
        
        .form-input.error, .form-select.error, .form-textarea.error {
            border-color: var(--error-color);
        }
        
        .form-input.success, .form-select.success, .form-textarea.success {
            border-color: var(--success-color);
        }
        
        .form-feedback {
            display: block;
            margin-top: 6px;
            font-size: 0.85rem;
        }
        
        .error-message {
            color: var(--error-color);
        }
        
        .success-message {
            color: var(--success-color);
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .form-row .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        /* Dashboard & Table Styling */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .dashboard-title {
            font-size: 1.8rem;
            color: var(--secondary-color);
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 24px;
            text-align: center;
            border-left: 5px solid var(--primary-color);
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1rem;
            color: var(--gray-dark);
        }
        
        .table-container {
            overflow-x: auto;
            margin-bottom: 40px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }
        
        .data-table th {
            background-color: var(--primary-light);
            color: var(--secondary-color);
            font-weight: 600;
            text-align: left;
            padding: 16px;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .data-table td {
            padding: 16px;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .data-table tr:hover {
            background-color: var(--primary-light);
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-confirmed {
            background-color: #c6f6d5;
            color: #22543d;
        }
        
        .status-pending {
            background-color: #fed7d7;
            color: #742a2a;
        }
        
        .status-completed {
            background-color: #bee3f8;
            color: #1a365d;
        }
        
        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-speed) ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .modal {
            background-color: white;
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(20px);
            transition: transform var(--transition-speed) ease;
        }
        
        .modal-overlay.active .modal {
            transform: translateY(0);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--gray-light);
        }
        
        .modal-title {
            font-size: 1.5rem;
            color: var(--secondary-color);
        }
        
        .modal-close {
            font-size: 1.5rem;
            color: var(--gray-dark);
            cursor: pointer;
            transition: color var(--transition-speed) ease;
        }
        
        .modal-close:hover {
            color: var(--error-color);
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 20px;
            border-top: 1px solid var(--gray-light);
            text-align: right;
        }
        
        /* Blog & Articles */
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .article-card {
            background-color: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            transition: transform var(--transition-speed) ease;
        }
        
        .article-card:hover {
            transform: translateY(-5px);
        }
        
        .article-image {
            height: 200px;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 3rem;
        }
        
        .article-content {
            padding: 24px;
        }
        
        .article-title {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }
        
        .article-excerpt {
            color: var(--gray-dark);
            margin-bottom: 20px;
        }
        
        /* Charts */
        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .chart-card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 24px;
        }
        
        .chart-title {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: var(--secondary-color);
        }
        
        .chart-placeholder {
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-light);
            color: var(--primary-color);
            border-radius: var(--border-radius);
        }
        
        /* Footer */
        .footer {
            background-color: var(--secondary-color);
            color: white;
            padding: 50px 0 20px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .footer-section h3 {
            font-size: 1.3rem;
            margin-bottom: 20px;
            color: white;
        }
        
        .footer-section p, .footer-section a {
            color: var(--gray-light);
            margin-bottom: 10px;
            display: block;
        }
        
        .footer-section a:hover {
            color: white;
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid var(--gray-dark);
            color: var(--gray-medium);
            font-size: 0.9rem;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .section-title {
                font-size: 1.8rem;
            }
            
            .cta-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 768px) {
            .nav-links {
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                background-color: white;
                flex-direction: column;
                align-items: center;
                padding: 20px 0;
                gap: 20px;
                transform: translateY(-100%);
                opacity: 0;
                visibility: hidden;
                transition: all var(--transition-speed) ease;
                border-bottom: 1px solid var(--gray-light);
            }
            
            .nav-links.active {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-content {
                flex-direction: column;
            }
            
            .steps-container {
                flex-direction: column;
                align-items: center;
            }
            
            .step::before {
                display: none;
            }
        }
        
        @media (max-width: 576px) {
            .btn {
                padding: 10px 20px;
                width: 100%;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .hero {
                padding: 50px 0;
            }
            
            .features, .booking-steps, .room-types, .testimonials {
                padding: 50px 0;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
        
        /* Animation Classes */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        
        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        .slide-in-left {
            transform: translateX(-50px);
            opacity: 0;
            transition: transform 0.5s ease, opacity 0.5s ease;
        }
        
        .slide-in-left.visible {
            transform: translateX(0);
            opacity: 1;
        }
        
        .slide-in-right {
            transform: translateX(50px);
            opacity: 0;
            transition: transform 0.5s ease, opacity 0.5s ease;
        }
        
        .slide-in-right.visible {
            transform: translateX(0);
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="#home" class="logo">Hotel<span>Ku</span></a>
                <nav>
                    <ul class="nav-links" id="navLinks">
                        <li><a href="#home" class="nav-link active">Beranda</a></li>
                        <li><a href="#reservation" class="nav-link">Reservasi</a></li>
                        <li><a href="#login" class="nav-link">Masuk</a></li>
                        <li><a href="#profile" class="nav-link">Profil</a></li>
                        <li><a href="#checkin" class="nav-link">Check-in</a></li>
                        <li><a href="#cancellation" class="nav-link">Pembatalan</a></li>
                        <li><a href="#blog" class="nav-link">Blog</a></li>
                        <li><a href="#dashboard" class="nav-link">Dashboard</a></li>
                    </ul>
                </nav>
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main id="mainContent">
        <!-- Home/Landing Page -->
        <section id="home" class="page active">
            <div class="hero">
                <div class="container">
                    <div class="hero-content">
                        <div class="hero-text slide-in-left">
                            <h1 class="hero-title">Reservasi Akomodasi Jadi Lebih Mudah</h1>
                            <p class="hero-subtitle">Platform digital terpercaya dengan check-in online, pembayaran aman, dan layanan 24 jam</p>
                            <a href="#reservation" class="btn btn-primary">Reservasi Sekarang</a>
                        </div>
                        <div class="hero-image slide-in-right">
                            <div class="hero-image-placeholder" style="width: 100%; height: 300px; background-color: var(--primary-light); border-radius: var(--border-radius); display: flex; align-items: center; justify-content: center; color: var(--primary-color);">
                                <i class="fas fa-hotel" style="font-size: 10rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Section -->
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
                                <a href="#reservation" class="btn btn-primary" style="width: 100%;">Pilih Kamar</a>
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
                                <a href="#reservation" class="btn btn-primary" style="width: 100%;">Pilih Kamar</a>
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
                                <a href="#reservation" class="btn btn-primary" style="width: 100%;">Pilih Kamar</a>
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
                    <a href="#reservation" class="btn btn-secondary" style="font-size: 1.1rem; padding: 15px 40px;">Reservasi Sekarang</a>
                </div>
            </div>
        </section>

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
                                        <option value="standard">Standard Room</option>
                                        <option value="deluxe">Deluxe Room</option>
                                        <option value="suite">Suite Room</option>
                                        <option value="executive">Executive Room</option>
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

        <!-- Login Page -->
        <section id="login" class="page">
            <div class="container">
                <div class="page">
                    <div class="form-container">
                        <div id="loginFormContainer">
                            <h2 class="form-title">Masuk ke Akun Anda</h2>
                            <form id="loginForm" action="../functions/login_process.php" method="POST">
                                <div class="form-group">
                                    <label for="loginEmail" class="form-label">Email</label>
                                    <input type="email" id="loginEmail" name="email" class="form-input" placeholder="email@contoh.com" required>
                                    <div class="form-feedback" id="loginEmailFeedback"></div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="loginPassword" class="form-label">Kata Sandi</label>
                                    <input type="password" id="loginPassword" name="password" class="form-input" placeholder="Masukkan kata sandi" required>
                                    <div class="form-feedback" id="loginPasswordFeedback"></div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk</button>
                                </div>
                                
                                <div class="form-group text-center">
                                    <p>Belum punya akun? <a href="#" id="showRegister">Daftar di sini</a></p>
                                </div>
                            </form>
                        </div>
                        
                        <div id="registerFormContainer" class="hidden">
                            <h2 class="form-title">Buat Akun Baru</h2>
                            <form id="registerForm" action="../functions/register_process.php" method="POST">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="registerFirstName" class="form-label">Nama Depan</label>
                                        <input type="text" id="registerFirstName" name="first_name" class="form-input" placeholder="Nama depan" required>
                                        <div class="form-feedback" id="registerFirstNameFeedback"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registerLastName" class="form-label">Nama Belakang</label>
                                        <input type="text" id="registerLastName" name="last_name" class="form-input" placeholder="Nama belakang" required>
                                        <div class="form-feedback" id="registerLastNameFeedback"></div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="registerEmail" class="form-label">Email</label>
                                    <input type="email" id="registerEmail" name="email" class="form-input" placeholder="email@contoh.com" required>
                                    <div class="form-feedback" id="registerEmailFeedback"></div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="registerPassword" class="form-label">Kata Sandi</label>
                                        <input type="password" id="registerPassword" name="password" class="form-input" placeholder="Minimal 8 karakter" required>
                                        <div class="form-feedback" id="registerPasswordFeedback"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="registerConfirmPassword" class="form-label">Konfirmasi Kata Sandi</label>
                                        <input type="password" id="registerConfirmPassword" name="confirm_password" class="form-input" placeholder="Ulangi kata sandi" required>
                                        <div class="form-feedback" id="registerConfirmPasswordFeedback"></div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="width: 100%;">Daftar</button>
                                </div>
                                
                                <div class="form-group text-center">
                                    <p>Sudah punya akun? <a href="#" id="showLogin">Masuk di sini</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Profile Page -->
        <section id="profile" class="page">
            <div class="container">
                <div class="page">
                    <div class="dashboard-header">
                        <h2 class="dashboard-title">Profil Pengguna</h2>
                        <div>
                            <a href="../functions/logout.php" class="btn btn-danger">Keluar</a>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="form-row">
                            <div class="form-group">
                                <h3 style="margin-bottom: 20px;">Informasi Pribadi</h3>
                                <p><strong>Nama:</strong> <span id="profileName">John Doe</span></p>
                                <p><strong>Email:</strong> <span id="profileEmail">john.doe@example.com</span></p>
                                <p><strong>Telepon:</strong> <span id="profilePhone">08123456789</span></p>
                                <p><strong>Member sejak:</strong> <span id="profileJoinDate">15 Maret 2023</span></p>
                            </div>
                            <div class="form-group">
                                <h3 style="margin-bottom: 20px;">Statistik Reservasi</h3>
                                <p><strong>Total Reservasi:</strong> <span id="totalReservations">5</span></p>
                                <p><strong>Reservasi Aktif:</strong> <span id="activeReservations">2</span></p>
                                <p><strong>Reservasi Selesai:</strong> <span id="completedReservations">3</span></p>
                                <p><strong>Pembatalan:</strong> <span id="cancelledReservations">0</span></p>
                            </div>
                        </div>
                    </div>
                    
                    <h3 style="margin: 30px 0 20px;">Riwayat Reservasi</h3>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID Reservasi</th>
                                    <th>Tipe Kamar</th>
                                    <th>Tanggal</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="reservationHistory">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

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
                                        <!-- Data akan diisi oleh JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Blog Page -->
        <section id="blog" class="page">
            <div class="container">
                <div class="page">
                    <h2 class="section-title">Blog & Artikel</h2>
                    <p class="text-center" style="margin-bottom: 50px; color: var(--gray-dark); max-width: 700px; margin-left: auto; margin-right: auto;">Temukan tips, tren, dan informasi terbaru seputar akomodasi dan perjalanan.</p>
                    
                    <div class="articles-grid">
                        <div class="article-card">
                            <div class="article-image">
                                <i class="fas fa-bed"></i>
                            </div>
                            <div class="article-content">
                                <h3 class="article-title">5 Tips Memilih Kamar Hotel yang Tepat</h3>
                                <p class="article-excerpt">Pelajari cara memilih kamar hotel yang sesuai dengan kebutuhan dan anggaran Anda untuk pengalaman menginap yang lebih nyaman.</p>
                                <a href="#" class="btn btn-secondary btn-small">Baca Selengkapnya</a>
                            </div>
                        </div>
                        
                        <div class="article-card">
                            <div class="article-image">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="article-content">
                                <h3 class="article-title">Manfaat Check-in Online untuk Perjalanan Bisnis</h3>
                                <p class="article-excerpt">Tingkatkan efisiensi perjalanan bisnis Anda dengan memanfaatkan fitur check-in online yang menghemat waktu dan tenaga.</p>
                                <a href="#" class="btn btn-secondary btn-small">Baca Selengkapnya</a>
                            </div>
                        </div>
                        
                        <div class="article-card">
                            <div class="article-image">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="article-content">
                                <h3 class="article-title">Tren Reservasi Akomodasi 2023</h3>
                                <p class="article-excerpt">Simak tren terbaru dalam industri reservasi akomodasi dan bagaimana teknologi mengubah cara kita memesan penginapan.</p>
                                <a href="#" class="btn btn-secondary btn-small">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Dashboard Page -->
        <section id="dashboard" class="page">
            <div class="container">
                <div class="page">
                    <div class="dashboard-header">
                        <h2 class="dashboard-title">Dashboard Admin</h2>
                        <div>
                            <button id="refreshDataBtn" class="btn btn-primary">Refresh Data</button>
                        </div>
                    </div>
                    
                    <div class="stats-container">
                        <div class="stat-card">
                            <div class="stat-value" id="totalBookings">142</div>
                            <div class="stat-label">Total Reservasi</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" id="activeBookings">24</div>
                            <div class="stat-label">Reservasi Aktif</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" id="occupancyRate">78%</div>
                            <div class="stat-label">Tingkat Okupansi</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" id="cancellationRate">5%</div>
                            <div class="stat-label">Tingkat Pembatalan</div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <h3 style="margin-bottom: 20px;">Grafik & Infografis</h3>
                        <div class="charts-container">
                            <div class="chart-card">
                                <h4 class="chart-title">Reservasi per Bulan</h4>
                                <div class="chart-placeholder">
                                    <i class="fas fa-chart-line" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                            <div class="chart-card">
                                <h4 class="chart-title">Distribusi Tipe Kamar</h4>
                                <div class="chart-placeholder">
                                    <i class="fas fa-chart-pie" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="dashboard-header">
                            <h3>Manajemen Reservasi</h3>
                            <button id="addReservationBtn" class="btn btn-primary btn-small">Tambah Reservasi</button>
                        </div>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Tipe Kamar</th>
                                        <th>Check-in</th>
                                        <th>Check-out</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="adminReservationTable">
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="dashboard-header">
                            <h3>Manajemen Pengguna</h3>
                            <button id="addUserBtn" class="btn btn-primary btn-small">Tambah Pengguna</button>
                        </div>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="adminUserTable">
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>HotelKu</h3>
                    <p>Platform manajemen reservasi akomodasi terintegrasi untuk pengalaman menginap yang lebih baik.</p>
                </div>
                <div class="footer-section">
                    <h3>Tautan Cepat</h3>
                    <a href="#home">Beranda</a>
                    <a href="#reservation">Reservasi</a>
                    <a href="#blog">Blog</a>
                    <a href="#dashboard">Dashboard</a>
                </div>
                <div class="footer-section">
                    <h3>Kontak</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Jl. Akomodasi No. 123, Jakarta</p>
                    <p><i class="fas fa-phone"></i> (021) 1234-5678</p>
                    <p><i class="fas fa-envelope"></i> info@hotelku.com</p>
                </div>
                <div class="footer-section">
                    <h3>Ikuti Kami</h3>
                    <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
                    <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
                    <a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a>
                </div>
            </div>
            <div class="copyright">
                &copy; <?php echo date('Y'); ?> HotelKu. Semua hak dilindungi.
            </div>
        </div>
    </footer>

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
    initNavigation();
    initForms();
    initModals();
    initAnimations();
    loadDashboardData();
    loadProfileData();
    
    // Tampilkan halaman beranda secara default
    showPage('home');
    
    // Handle hash change untuk navigasi
    window.addEventListener('hashchange', handleHashChange);
    
    // Check initial hash
    if (window.location.hash) {
        const pageId = window.location.hash.substring(1);
        showPage(pageId);
    }
});
    </script>
</body>
</html>