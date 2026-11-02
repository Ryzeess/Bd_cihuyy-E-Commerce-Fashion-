-- ========================================================
-- ROUND 1: PRAKTIKUM DML DENGAN EXPLAIN / ANALYZE
-- Sesuai Instruksi Papan Tulis
-- ========================================================

USE cihuy_store;

-- --------------------------------------------------------
-- 1. QUERY UTAMA: SELECT (WHERE & GROUP BY)
-- --------------------------------------------------------
SELECT '=== 1. HASIL QUERY SELECT (WHERE & GROUP BY) ===' AS info;
SELECT DATE(tanggal_daftar) AS tanggal_daftar,
       COUNT(*) AS jumlah_pelanggan
FROM pelanggan
WHERE id_pelanggan > 0
GROUP BY DATE(tanggal_daftar);

-- --------------------------------------------------------
-- 2. OPERASI INSERT (5 DATA) : BEFORE & AFTER (EXPLAIN ANALYZE)
-- --------------------------------------------------------

-- [BEFORE INSERT] Explain Analyze Query Select
SELECT '=== [INSERT] BEFORE: EXPLAIN ANALYZE SELECT ===' AS info;
ANALYZE
SELECT DATE(tanggal_daftar) AS tanggal_daftar,
       COUNT(*) AS jumlah_pelanggan
FROM pelanggan
WHERE id_pelanggan > 0
GROUP BY DATE(tanggal_daftar);

-- EKSEKUSI INSERT 5 DATA
SELECT '=== EKSEKUSI INSERT 5 DATA ===' AS info;
INSERT INTO pelanggan (nama, email, password, no_telepon, alamat, tanggal_daftar) VALUES
('Budi Santoso', 'budi.santoso2026@gmail.com', '$2y$12$password123', '081234567891', 'Jakarta', NOW()),
('Siti Aminah', 'siti.aminah2026@yahoo.com', '$2y$12$password123', '081234567892', 'Bandung', NOW()),
('Ahmad Fauzi', 'ahmad.fauzi2026@gmail.com', '$2y$12$password123', '081234567893', 'Surabaya', NOW()),
('Rina Wijaya', 'rina.wijaya2026@outlook.com', '$2y$12$password123', '081234567894', 'Yogyakarta', NOW()),
('Doni Pratama', 'doni.pratama2026@gmail.com', '$2y$12$password123', '081234567895', 'Semarang', NOW());

-- [AFTER INSERT] Explain Analyze Query Select (Lihat r_rows bertambah)
SELECT '=== [INSERT] AFTER: EXPLAIN ANALYZE SELECT ===' AS info;
ANALYZE
SELECT DATE(tanggal_daftar) AS tanggal_daftar,
       COUNT(*) AS jumlah_pelanggan
FROM pelanggan
WHERE id_pelanggan > 0
GROUP BY DATE(tanggal_daftar);

-- --------------------------------------------------------
-- 3. OPERASI UPDATE (WHERE) : BEFORE & AFTER (EXPLAIN ANALYZE)
-- --------------------------------------------------------

-- [BEFORE UPDATE] Cek data target
SELECT '=== [UPDATE] BEFORE: EXPLAIN TARGET RECORD ===' AS info;
EXPLAIN SELECT id_pelanggan, nama, no_telepon, alamat FROM pelanggan WHERE email = 'budi.santoso2026@gmail.com';
SELECT id_pelanggan, nama, no_telepon, alamat FROM pelanggan WHERE email = 'budi.santoso2026@gmail.com';

-- EKSEKUSI UPDATE
SELECT '=== EKSEKUSI UPDATE WHERE email = ... ===' AS info;
UPDATE pelanggan 
SET no_telepon = '089999888777', alamat = 'Jl. Sudirman Kav. 25, Jakarta Selatan' 
WHERE email = 'budi.santoso2026@gmail.com';

-- [AFTER UPDATE] Cek perubahan
SELECT '=== [UPDATE] AFTER: DATA HASIL UPDATE ===' AS info;
SELECT id_pelanggan, nama, no_telepon, alamat FROM pelanggan WHERE email = 'budi.santoso2026@gmail.com';

-- --------------------------------------------------------
-- 4. OPERASI DELETE (WHERE) : BEFORE & AFTER (EXPLAIN ANALYZE)
-- --------------------------------------------------------

-- [BEFORE DELETE] Cek data target
SELECT '=== [DELETE] BEFORE: CEK DATA SEBELUM DIHAPUS ===' AS info;
SELECT id_pelanggan, nama, email FROM pelanggan WHERE email = 'doni.pratama2026@gmail.com';

-- EKSEKUSI DELETE
SELECT '=== EKSEKUSI DELETE WHERE email = ... ===' AS info;
DELETE FROM pelanggan WHERE email = 'doni.pratama2026@gmail.com';

-- [AFTER DELETE] Cek data target (harus kosong / 0 rows)
SELECT '=== [DELETE] AFTER: CEK DATA SESUDAH DIHAPUS ===' AS info;
SELECT id_pelanggan, nama, email FROM pelanggan WHERE email = 'doni.pratama2026@gmail.com';

-- --------------------------------------------------------
-- 5. VERIFIKASI AKHIR STATUS TABEL PELANGGAN
-- Catatan: Query read-only untuk memastikan integritas data setelah praktikum
-- --------------------------------------------------------
SELECT '=== HASIL AKHIR: TOTAL RECORD PELANGGAN ===' AS info;
SELECT COUNT(*) AS total_pelanggan_aktif FROM pelanggan;

