<?php
$pageTitle = 'ReservaStay - Login & Register';
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
                                    <label for="registerPhone" class="form-label">Telepon</label>
                                    <input type="tel" id="registerPhone" name="phone" class="form-input" placeholder="08123456789" required>
                                    <div class="form-feedback" id="registerPhoneFeedback"></div>
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

    <script src="../script.js"></script>
    <script>
    // Inisialisasi Aplikasi
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi komponen
        initForms();
    });
    </script>
</body>
</html>

