<?php
// mahasiswa/edit.php - READ satu data + UPDATE
require_once '../config/database.php';
require_once '../config/helpers.php';

$isPost = ($_SERVER['REQUEST_METHOD'] === 'POST');

// NIM = identifier tetap: dari GET (buka form) atau hidden field (simpan)
$nim = trim($isPost ? ($_POST['nim'] ?? '') : ($_GET['nim'] ?? ''));

try {
    $stmt = $pdo->prepare(
        'SELECT nim, nama, email, angkatan, id_prodi
         FROM mahasiswa WHERE nim = :nim'
    );
    $stmt->execute(['nim' => $nim]);
    $mahasiswa = $stmt->fetch();
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Terjadi kesalahan pada server.');
}

if (!$mahasiswa) {
    http_response_code(404);
    exit('Data mahasiswa tidak ditemukan.');
}

$errors = [];
$form = [
    'nim'      => $mahasiswa['nim'],
    'nama'     => $mahasiswa['nama'],
    'email'    => $mahasiswa['email'],
    'angkatan' => (string) $mahasiswa['angkatan'],
    'id_prodi' => (string) $mahasiswa['id_prodi'],
];

if ($isPost) {
    $form['nama']     = trim($_POST['nama'] ?? '');
    $form['email']    = trim($_POST['email'] ?? '');
    $form['angkatan'] = trim($_POST['angkatan'] ?? '');
    $form['id_prodi'] = trim($_POST['id_prodi'] ?? '');

    try {
        $errors = validasiMahasiswa($pdo, $form, false); // NIM tidak diubah

        if (!$errors) {
            $sql = "UPDATE mahasiswa
                    SET nama = :nama,
                        email = :email,
                        angkatan = :angkatan,
                        id_prodi = :id_prodi
                    WHERE nim = :nim";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nama'     => $form['nama'],
                'email'    => $form['email'],
                'angkatan' => $form['angkatan'],
                'id_prodi' => $form['id_prodi'],
                'nim'      => $nim,
            ]);

            header('Location: index.php?status=ubah');
            exit;
        }
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            $errors[] = 'Email sudah digunakan mahasiswa lain.';
        } else {
            error_log($e->getMessage());
            $errors[] = 'Data gagal diperbarui.';
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
    <title>Edit Mahasiswa - SIKA</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container narrow">
    <h1>Edit Mahasiswa</h1>

    <?php if ($errors): ?>
        <div class="alert error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= e($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="edit.php">
        <input type="hidden" name="nim" value="<?= e($nim) ?>">

        <label for="nim_view">NIM (tidak dapat diubah)</label>
        <input type="text" id="nim_view" value="<?= e($form['nim']) ?>" readonly>

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
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a class="btn btn-secondary" href="index.php">Batal</a>
        </div>
    </form>
</div>
</body>
</html>
