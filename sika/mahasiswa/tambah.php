<?php
// mahasiswa/tambah.php - CREATE
require_once '../config/database.php';
require_once '../config/helpers.php';

$errors = [];
$form   = ['nim' => '', 'nama' => '', 'email' => '', 'angkatan' => '', 'id_prodi' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = [
        'nim'      => trim($_POST['nim'] ?? ''),
        'nama'     => trim($_POST['nama'] ?? ''),
        'email'    => trim($_POST['email'] ?? ''),
        'angkatan' => trim($_POST['angkatan'] ?? ''),
        'id_prodi' => trim($_POST['id_prodi'] ?? ''),
    ];

    try {
        $errors = validasiMahasiswa($pdo, $form, true);

        if (!$errors) {
            $sql = "INSERT INTO mahasiswa (nim, nama, email, angkatan, id_prodi)
                    VALUES (:nim, :nama, :email, :angkatan, :id_prodi)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nim'      => $form['nim'],
                'nama'     => $form['nama'],
                'email'    => $form['email'],
                'angkatan' => $form['angkatan'],
                'id_prodi' => $form['id_prodi'],
            ]);

            header('Location: index.php?status=tambah'); // Post/Redirect/Get
            exit;
        }
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $errors[] = 'NIM atau email sudah digunakan.';
        } else {
            error_log($e->getMessage());
            $errors[] = 'Data gagal disimpan.';
        }
    }
}

try {
    $prodi = daftarProdi($pdo);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $prodi    = [];
    $errors[] = 'Daftar program studi gagal dimuat.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Mahasiswa - SIKA</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container narrow">
    <h1>Tambah Mahasiswa</h1>

    <?php if ($errors): ?>
        <div class="alert error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= e($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Validasi HTML5 hanya membantu UX; keputusan akhir ada di server -->
    <form method="post" action="tambah.php">
        <label for="nim">NIM</label>
        <input type="text" id="nim" name="nim" required maxlength="20"
               value="<?= e($form['nim']) ?>">

        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" required minlength="3" maxlength="100"
               value="<?= e($form['nama']) ?>">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required maxlength="120"
               value="<?= e($form['email']) ?>">

        <label for="angkatan">Angkatan</label>
        <input type="number" id="angkatan" name="angkatan" required min="2000" max="2100"
               value="<?= e($form['angkatan']) ?>">

        <label for="id_prodi">Program Studi</label>
        <select id="id_prodi" name="id_prodi" required>
            <option value="">-- Pilih program studi --</option>
            <?php foreach ($prodi as $p): ?>
                <option value="<?= e((string) $p['id_prodi']) ?>"
                    <?= $form['id_prodi'] === (string) $p['id_prodi'] ? 'selected' : '' ?>>
                    <?= e($p['kode_prodi'] . ' - ' . $p['nama_prodi']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="form-actions">
            <button type="submit" class="btn">Simpan</button>
            <a class="btn btn-secondary" href="index.php">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
