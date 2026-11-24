# Secure Coding Checklist - Bab 6 (CRUD Mahasiswa SIKA)

| Kontrol | Ya/Tidak | Evidence |
|---|---|---|
| PDO memakai akun aplikasi terbatas, bukan root | Ya | `config/database.php` memakai user `sika_app` |
| Query dinamis memakai prepare/execute | Ya | `tambah.php`, `edit.php`, `hapus.php`, `config/helpers.php` memakai placeholder `:nama` |
| Server-side validation diterapkan | Ya | `validasiMahasiswa()` di `config/helpers.php` (dipakai CREATE dan UPDATE) |
| Output HTML memakai encoding sesuai konteks | Ya | fungsi `e()` (`htmlspecialchars`, `ENT_QUOTES`, UTF-8) di semua output |
| Mutasi data memakai POST pada modul ini | Ya | `tambah.php`, `edit.php`, `hapus.php` (hapus menolak non-POST dengan 405) |
| Credential tidak dipublikasikan ke repository | Ya | `config/database.php` ada di `.gitignore`; hanya `database.example.php` yang di-commit |
| Error internal tidak ditampilkan ke user | Ya | `try/catch PDOException` + `error_log()`; `display_errors` dimatikan; pesan ke user generik |
| Test case valid/invalid/security terdokumentasi | (isi setelah uji) | `docs/04-testing/crud-test-result.md` |
| Defect telah diretest setelah perbaikan | (isi setelah uji) | `docs/04-testing/defect-log.md` |

## Catatan batasan
- POST bukan pengganti proteksi CSRF dan authorization. Proteksi tersebut belum
  diterapkan pada Bab 6 dan dibahas pada integrasi akhir.
- `ON DELETE CASCADE` pada tabel `krs` berarti menghapus mahasiswa juga menghapus
  KRS terkait. Pengujian hapus wajib memakai data uji.
