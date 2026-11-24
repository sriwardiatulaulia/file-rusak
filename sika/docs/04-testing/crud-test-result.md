# Hasil Pengujian CRUD - SIKA Bab 6

Lingkungan uji: lokal (PHP + MariaDB, database `sika_db`).
Isi kolom **Hasil Aktual** dan **Status** (PASS/FAIL) setelah menjalankan tiap skenario.

## A. Pengujian Fungsional

| No | Skenario | Langkah / Data Uji | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|
| 1 | Buka index.php | Akses `/sika/mahasiswa/index.php` | Data mahasiswa + prodi tampil dari DB | | |
| 2 | Create valid | NIM `24010001`, Nama `Andi Saputra`, `andi@kampus.ac.id`, 2024, pilih prodi | Data tersimpan, redirect ke index, pesan sukses | | |
| 3 | Create email invalid | Email `andi@` (kirim dengan HTML5 validation dilewati) | Server menolak, pesan error tampil | | |
| 4 | Create duplikat | NIM / email yang sudah ada | Ditolak tanpa merusak data lama | | |
| 5 | Edit data valid | Ubah nama/email/angkatan/prodi | Perubahan tersimpan | | |
| 6 | Edit NIM tidak ada | `edit.php?nim=tidakada` | 404 / pesan terkontrol | | |
| 7 | Delete data uji | Klik Hapus pada data uji, konfirmasi | Data terhapus melalui POST | | |
| 8 | GET ke hapus.php | Buka `/mahasiswa/hapus.php?nim=24010001` di browser | 405 / request ditolak | | |

## B. Pengujian Keamanan

| ID | Skenario | Input / Aksi | Hasil Diharapkan | Hasil Aktual | Status |
|---|---|---|---|---|---|
| SEC-01 | SQL-like text | Nama: `Rina' OR '1'='1` | Diperlakukan sebagai data; query tidak berubah | | |
| SEC-02 | HTML/script-like text | Nama: `<script>alert(1)</script>` | Tampil sebagai teks, tidak dieksekusi | | |
| SEC-03 | Bypass client validation | Kirim email invalid langsung ke endpoint (mis. hapus atribut `required`/`type=email` lewat DevTools) | Server tetap menolak | | |
| SEC-04 | Wrong HTTP method | Akses `hapus.php` via GET | 405 | | |
| SEC-05 | Duplicate key | NIM/email sudah ada | Ditolak secara terkontrol | | |
| SEC-06 | DB unavailable | Matikan service MariaDB di lingkungan uji | User tidak melihat stack trace/credential | | |
| SEC-07 | Credential repo | Periksa isi repository | Tidak ada password asli | | |

> Gunakan payload hanya pada aplikasi SIKA milik sendiri / laboratorium.
