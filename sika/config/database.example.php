<?php
// config/database.example.php
// Salin file ini menjadi config/database.php lalu isi password lokal Anda.
// JANGAN menaruh password asli di file example ini.

$host    = 'localhost';
$db      = 'sika_db';
$user    = 'sika_app';
$pass    = 'GANTI_PASSWORD_LOKAL';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Detail error tidak ditampilkan ke pengguna; cukup masuk log.
ini_set('display_errors', '0');

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Koneksi database gagal.');
}
