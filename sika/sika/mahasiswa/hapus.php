<?php
// mahasiswa/hapus.php - DELETE (hanya melalui POST)
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Method tidak diizinkan.');
}

$nim = trim($_POST['nim'] ?? '');

if ($nim === '') {
    http_response_code(400);
    exit('NIM tidak valid.');
}

try {
    // Perhatian: FK krs -> mahasiswa memakai ON DELETE CASCADE,
    // sehingga KRS milik mahasiswa ini ikut terhapus. Gunakan data uji.
    $stmt = $pdo->prepare('DELETE FROM mahasiswa WHERE nim = :nim');
    $stmt->execute(['nim' => $nim]);

    $status = ($stmt->rowCount() > 0) ? 'hapus' : 'tidak_ada';
} catch (PDOException $e) {
    error_log($e->getMessage());
    $status = 'gagal';
}

header('Location: index.php?status=' . $status);
exit;
