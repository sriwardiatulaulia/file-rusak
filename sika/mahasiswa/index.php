<?php
// mahasiswa/index.php - READ
require_once '../config/database.php';
require_once '../config/helpers.php';

$mahasiswa = [];
$errorDb   = '';

try {
    $sql = "SELECT
                m.nim, m.nama, m.email, m.angkatan,
                p.kode_prodi, p.nama_prodi
            FROM mahasiswa AS m
            JOIN prodi AS p ON m.id_prodi = p.id_prodi
            ORDER BY m.nim";
    $mahasiswa = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $errorDb = 'Data mahasiswa gagal dimuat.';
}

// Allow-list pesan status (bukan mencetak parameter langsung)
$messages = [
    'tambah'    => 'Data mahasiswa berhasil ditambahkan.',
    'ubah'      => 'Data mahasiswa berhasil diperbarui.',
    'hapus'     => 'Data mahasiswa berhasil dihapus.',
    'tidak_ada' => 'Data mahasiswa tidak ditemukan.',
    'gagal'     => 'Operasi gagal diproses.',
];
$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Mahasiswa - SIKA</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>SIKA - Data Mahasiswa</h1>

    <?php if (is_string($status) && isset($messages[$status])): ?>
        <p class="alert <?= in_array($status, ['tidak_ada', 'gagal'], true) ? 'error' : 'success' ?>">
            <?= e($messages[$status]) ?>
        </p>
    <?php endif; ?>

    <?php if ($errorDb !== ''): ?>
        <p class="alert error"><?= e($errorDb) ?></p>
    <?php endif; ?>

    <p><a class="btn" href="tambah.php">+ Tambah Mahasiswa</a></p>

    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>NIM</th><th>Nama</th><th>Email</th>
                <th>Angkatan</th><th>Program Studi</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!$mahasiswa): ?>
            <tr><td colspan="6" class="empty">Belum ada data mahasiswa.</td></tr>
        <?php else: ?>
            <?php foreach ($mahasiswa as $m): ?>
            <tr>
                <td><?= e($m['nim']) ?></td>
                <td><?= e($m['nama']) ?></td>
                <td><?= e($m['email']) ?></td>
                <td><?= e((string) $m['angkatan']) ?></td>
                <td><?= e($m['nama_prodi']) ?></td>
                <td class="aksi">
                    <a class="btn btn-small" href="edit.php?nim=<?= urlencode($m['nim']) ?>">Edit</a>
                    <form method="post" action="hapus.php" class="inline"
                          onsubmit="return confirm('Hapus data mahasiswa ini? KRS terkait ikut terhapus.');">
                        <input type="hidden" name="nim" value="<?= e($m['nim']) ?>">
                        <button type="submit" class="btn btn-small btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>
