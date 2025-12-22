<?php
// Entry point untuk proses registrasi (dipanggil dari login_register.php)
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/AuthController.php';

$controller = new AuthController($conn);
$controller->register();


