-- Akun database khusus aplikasi SIKA (least privilege, bukan root).
-- Jalankan SETELAH sika.sql, sebagai root, di tab SQL phpMyAdmin.
-- Ganti GANTI_PASSWORD_LOKAL dengan password pilihanmu,
-- lalu isi password yang sama di config/database.php.

CREATE USER IF NOT EXISTS 'sika_app'@'localhost' IDENTIFIED BY 'GANTI_PASSWORD_LOKAL';
GRANT SELECT, INSERT, UPDATE, DELETE ON sika_db.* TO 'sika_app'@'localhost';
FLUSH PRIVILEGES;
