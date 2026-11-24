<?php
// config/helpers.php
// Fungsi bantu: output encoding dan validasi server-side.

/** Output encoding untuk konteks teks/atribut HTML. */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Validasi server-side data mahasiswa (dipakai CREATE dan UPDATE
 * agar aturan validasinya sama).
 *
 * @param bool $cekNim true untuk CREATE, false untuk UPDATE (NIM tidak diubah)
 * @return string[] daftar pesan error (kosong jika valid)
 */
function validasiMahasiswa(PDO $pdo, array $in, bool $cekNim = true): array
{
    $errors = [];

    if ($cekNim) {
        if ($in['nim'] === '') {
            $errors[] = 'NIM wajib diisi.';
        } elseif (!preg_match('/^[A-Za-z0-9]{6,20}$/', $in['nim'])) {
            $errors[] = 'NIM harus 6-20 karakter huruf/angka.';
        }
    }

    if ($in['nama'] === '' || mb_strlen($in['nama']) < 3) {
        $errors[] = 'Nama minimal 3 karakter.';
    } elseif (mb_strlen($in['nama']) > 100) {
        $errors[] = 'Nama maksimal 100 karakter.';
    }

    if (!filter_var($in['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    } elseif (mb_strlen($in['email']) > 120) {
        $errors[] = 'Email maksimal 120 karakter.';
    }

    if (!filter_var($in['angkatan'], FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 2000, 'max_range' => 2100],
    ])) {
        $errors[] = 'Angkatan tidak valid (2000-2100).';
    }

    if (!ctype_digit((string) $in['id_prodi'])) {
        $errors[] = 'Program studi tidak valid.';
    } else {
        // Business rule: prodi harus ada di database.
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM prodi WHERE id_prodi = :id');
        $stmt->execute(['id' => $in['id_prodi']]);
        if ((int) $stmt->fetchColumn() === 0) {
            $errors[] = 'Program studi tidak ditemukan.';
        }
    }

    return $errors;
}

/** Ambil daftar prodi untuk <select>. */
function daftarProdi(PDO $pdo): array
{
    return $pdo->query(
        'SELECT id_prodi, kode_prodi, nama_prodi FROM prodi ORDER BY nama_prodi'
    )->fetchAll();
}
