# SIKA - Sistem Informasi Akademik (Bab 6: CRUD Mahasiswa)

CRUD Data Mahasiswa dengan PHP prosedural, PDO, dan MariaDB (`sika_db`),
dengan baseline secure coding (prepared statement, validasi server-side,
output encoding, kontrol method HTTP, error terkontrol).

## Struktur folder
```
sika/
├── assets/css/style.css
├── config/
│   ├── database.example.php   # template (tanpa password asli)
│   ├── database.php           # lokal, TIDAK di-commit
│   └── helpers.php            # e() dan validasiMahasiswa()
├── database/
│   ├── sika.sql               # dari Bab 5
│   └── akun_aplikasi.sql      # membuat akun sika_app (hak terbatas)
├── mahasiswa/
│   ├── index.php   (READ)
│   ├── tambah.php  (CREATE)
│   ├── edit.php    (READ satu data + UPDATE)
│   └── hapus.php   (DELETE, POST only)
├── docs/03-security/secure-coding-checklist.md
├── docs/04-testing/crud-test-result.md
├── docs/04-testing/defect-log.md
├── .gitignore
└── README.md
```

## Cara menjalankan
1. Pastikan PHP dan MariaDB aktif (mis. XAMPP).
2. Import `database/sika.sql`, lalu jalankan `database/akun_aplikasi.sql` (ganti passwordnya) untuk membuat akun `sika_app`.
   `prodi`, `mahasiswa`, `mata_kuliah`, `krs` tersedia.
3. Salin `config/database.example.php` menjadi `config/database.php`, lalu isi
   password akun `sika_app` (jangan pakai root).
4. Letakkan folder `sika/` di `htdocs` (XAMPP) atau jalankan:
   `php -S localhost:8000` dari folder `sika/`.
5. Buka `http://localhost/sika/mahasiswa/index.php`
   (atau `http://localhost:8000/mahasiswa/index.php`).

## Asumsi skema database
- `prodi(id_prodi, kode_prodi, nama_prodi)`
- `mahasiswa(nim, nama, email, angkatan, id_prodi)` dengan `nim` unik/PK,
  `email` UNIQUE, dan FK `id_prodi -> prodi`
- `krs` memiliki FK ke `mahasiswa` dengan `ON DELETE CASCADE`

Jika nama kolom pada `sika.sql` Anda berbeda, sesuaikan query di `mahasiswa/*.php`
dan `config/helpers.php`.

## Catatan keamanan
- Jangan upload `config/database.php` atau password asli ke GitHub.
- POST belum menggantikan proteksi CSRF dan authorization.
