<?php
// Entry point untuk proses login (dipanggil dari login_register.php)
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/AuthController.php';

$controller = new AuthController($conn);
$controller->login();


