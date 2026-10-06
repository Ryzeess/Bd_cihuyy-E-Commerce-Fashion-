-- ========================================================
-- SQL SCRIPT: DATABASE E-COMMERCE FASHION (12 ENTITAS)
-- Sesuai Dokumen Spesifikasi BRD & Perancangan Basis Data
-- Kompatibel dengan MySQL / MariaDB (dan SQLite dengan sedikit penyesuaian)
-- Berisi: DDL (12 Tabel) + DML (Data Sampel) + Query Analisis Multi-Join
-- ========================================================

-- 1. PILIH / BUAT DATABASE
CREATE DATABASE IF NOT EXISTS cihuy_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cihuy_store;

-- Hapus tabel lama jika ingin reset ulang (Urutan child ke parent)
DROP TABLE IF EXISTS pengiriman;
DROP TABLE IF EXISTS pembayaran;
DROP TABLE IF EXISTS detail_pesanan;
DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS voucher;
DROP TABLE IF EXISTS varian_produk;
DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS ukuran;
DROP TABLE IF EXISTS warna;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS pelanggan;
DROP TABLE IF EXISTS kurir;

-- ========================================================
-- BAGIAN 1: DDL (CREATE 12 TABEL)
-- ========================================================

-- 1. ENTITAS PELANGGAN
CREATE TABLE pelanggan (
    id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    no_telepon VARCHAR(20),
    alamat TEXT,
    tanggal_daftar DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. ENTITAS KATEGORI
CREATE TABLE kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL,
    deskripsi TEXT
) ENGINE=InnoDB;

-- 3. ENTITAS WARNA
CREATE TABLE warna (
    id_warna INT AUTO_INCREMENT PRIMARY KEY,
    nama_warna VARCHAR(50) NOT NULL,
    kode_warna VARCHAR(10) -- Contoh HEX: #000000
) ENGINE=InnoDB;

-- 4. ENTITAS UKURAN
CREATE TABLE ukuran (
    id_ukuran INT AUTO_INCREMENT PRIMARY KEY,
    nama_ukuran VARCHAR(20) NOT NULL, -- S, M, L, XL, XXL
    keterangan VARCHAR(100)
) ENGINE=InnoDB;

-- 5. ENTITAS PRODUK (Relasi N:1 ke Kategori)
CREATE TABLE produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori INT NOT NULL,
    nama_produk VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    bahan VARCHAR(50),
    gaya VARCHAR(50),
    berat INT DEFAULT 250, -- Satuan gram
    CONSTRAINT fk_produk_kategori FOREIGN KEY (id_kategori)
        REFERENCES kategori(id_kategori) ON DELETE RESTRICT,
    INDEX idx_produk_kategori (id_kategori),
    INDEX idx_produk_harga (harga)
) ENGINE=InnoDB;

-- 6. ENTITAS VARIAN PRODUK (Relasi N:1 ke Produk, Warna, Ukuran)
CREATE TABLE varian_produk (
    id_varian INT AUTO_INCREMENT PRIMARY KEY,
    id_produk INT NOT NULL,
    id_warna INT NOT NULL,
    id_ukuran INT NOT NULL,
    kode_barang VARCHAR(50) NOT NULL UNIQUE,
    stok INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_varian_produk FOREIGN KEY (id_produk)
        REFERENCES produk(id_produk) ON DELETE CASCADE,
    CONSTRAINT fk_varian_warna FOREIGN KEY (id_warna)
        REFERENCES warna(id_warna) ON DELETE RESTRICT,
    CONSTRAINT fk_varian_ukuran FOREIGN KEY (id_ukuran)
        REFERENCES ukuran(id_ukuran) ON DELETE RESTRICT,
    INDEX idx_varian_produk (id_produk),
    INDEX idx_varian_warna (id_warna),
    INDEX idx_varian_ukuran (id_ukuran)
) ENGINE=InnoDB;

-- 7. ENTITAS VOUCHER
CREATE TABLE voucher (
    id_voucher INT AUTO_INCREMENT PRIMARY KEY,
    kode_voucher VARCHAR(50) NOT NULL UNIQUE,
    tipe_diskon ENUM('persen', 'nominal') DEFAULT 'nominal',
    nilai_diskon DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    minimal_belanja DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    tanggal_mulai DATETIME,
    tanggal_berakhir DATETIME,
    status ENUM('aktif', 'nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB;

-- 8. ENTITAS PESANAN (Relasi N:1 ke Pelanggan & Voucher)
CREATE TABLE pesanan (
    id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
    nomor_pesanan VARCHAR(50) NOT NULL UNIQUE,
    id_pelanggan INT NOT NULL,
    id_voucher INT NULL,
    tanggal_pesanan DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_harga DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    potongan_diskon DECIMAL(12, 2) DEFAULT 0.00,
    total_bayar DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    status_pesanan ENUM('menunggu_pembayaran', 'diproses', 'dikirim', 'selesai', 'dibatalkan') DEFAULT 'menunggu_pembayaran',
    alamat_pengiriman TEXT NOT NULL,
    CONSTRAINT fk_pesanan_pelanggan FOREIGN KEY (id_pelanggan)
        REFERENCES pelanggan(id_pelanggan) ON DELETE RESTRICT,
    CONSTRAINT fk_pesanan_voucher FOREIGN KEY (id_voucher)
        REFERENCES voucher(id_voucher) ON DELETE SET NULL,
    INDEX idx_pesanan_pelanggan (id_pelanggan),
    INDEX idx_pesanan_status (status_pesanan),
    INDEX idx_pesanan_nomor (nomor_pesanan)
) ENGINE=InnoDB;

-- 9. ENTITAS DETAIL PESANAN (Relasi N:1 ke Pesanan & Varian Produk)
CREATE TABLE detail_pesanan (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL,
    id_varian INT NOT NULL,
    jumlah INT NOT NULL DEFAULT 1,
    harga_satuan DECIMAL(12, 2) NOT NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    CONSTRAINT fk_detail_pesanan FOREIGN KEY (id_pesanan)
        REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
    CONSTRAINT fk_detail_varian FOREIGN KEY (id_varian)
        REFERENCES varian_produk(id_varian) ON DELETE RESTRICT,
    INDEX idx_detail_pesanan (id_pesanan),
    INDEX idx_detail_varian (id_varian)
) ENGINE=InnoDB;

-- 10. ENTITAS PEMBAYARAN (Relasi 1:1 ke Pesanan)
CREATE TABLE pembayaran (
    id_pembayaran INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL UNIQUE,
    metode_pembayaran ENUM('transfer_bank', 'qris', 'e_wallet', 'kartu_kredit', 'cod') NOT NULL,
    jumlah_bayar DECIMAL(12, 2) NOT NULL,
    waktu_bayar DATETIME,
    status_pembayaran ENUM('menunggu', 'lunas', 'gagal', 'kedaluwarsa') DEFAULT 'menunggu',
    bukti_pembayaran VARCHAR(255),
    CONSTRAINT fk_pembayaran_pesanan FOREIGN KEY (id_pesanan)
        REFERENCES pesanan(id_pesanan) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 11. ENTITAS KURIR
CREATE TABLE kurir (
    id_kurir INT AUTO_INCREMENT PRIMARY KEY,
    nama_kurir VARCHAR(100) NOT NULL,
    kode_kurir VARCHAR(20) NOT NULL UNIQUE,
    layanan VARCHAR(50) NOT NULL,
    tarif_per_kg DECIMAL(12, 2) NOT NULL DEFAULT 10000.00
) ENGINE=InnoDB;

-- 12. ENTITAS PENGIRIMAN (Relasi 1:1 ke Pesanan, N:1 ke Kurir)
CREATE TABLE pengiriman (
    id_pengiriman INT AUTO_INCREMENT PRIMARY KEY,
    id_pesanan INT NOT NULL UNIQUE,
    id_kurir INT NOT NULL,
    nomor_resi VARCHAR(100) NOT NULL UNIQUE,
    status_pengiriman ENUM('diproses', 'dalam_perjalanan', 'terkirim', 'gagal') DEFAULT 'diproses',
    biaya_ongkir DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    tanggal_kirim DATETIME,
    tanggal_diterima DATETIME,
    CONSTRAINT fk_pengiriman_pesanan FOREIGN KEY (id_pesanan)
        REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
    CONSTRAINT fk_pengiriman_kurir FOREIGN KEY (id_kurir)
        REFERENCES kurir(id_kurir) ON DELETE RESTRICT,
    INDEX idx_pengiriman_kurir (id_kurir),
    INDEX idx_pengiriman_resi (nomor_resi)
) ENGINE=InnoDB;


-- ========================================================
-- BAGIAN 2: DML (SEED DATA DUMMY REALISTIS)
-- ========================================================

-- 1. Data Pelanggan
INSERT INTO pelanggan (id_pelanggan, nama, email, password, no_telepon, alamat, tanggal_daftar) VALUES
(1, 'Ahmad Fauzi', 'ahmad.fauzi@gmail.com', '$2y$12$e6x...hashahmad', '081234567890', 'Jl. Merdeka No. 45, Bandung', '2026-09-01 10:00:00'),
(2, 'Siti Nurhaliza', 'siti.nur@gmail.com', '$2y$12$f8y...hashsiti', '085678901234', 'Jl. Malioboro No. 12, Yogyakarta', '2026-09-05 11:30:00'),
(3, 'Budi Santoso', 'budi.santoso@yahoo.com', '$2y$12$k9z...hashbudi', '087890123456', 'Jl. Sudirman No. 88, Jakarta Selatan', '2026-09-10 14:15:00');

-- 2. Data Kategori
INSERT INTO kategori (id_kategori, nama_kategori, deskripsi) VALUES
(1, 'Kaos', 'Koleksi t-shirt casual santai harian'),
(2, 'Kemeja', 'Kemeja formal dan kasual lengan panjang/pendek'),
(3, 'Celana', 'Celana chinos, jeans, dan formal'),
(4, 'Jaket', 'Outerwear hangat dan windbreaker stylish'),
(5, 'Dress', 'Gaun wanita anggun untuk berbagai acara');

-- 3. Data Warna
INSERT INTO warna (id_warna, nama_warna, kode_warna) VALUES
(1, 'Hitam', '#000000'),
(2, 'Putih', '#FFFFFF'),
(3, 'Navy', '#000080'),
(4, 'Merah', '#FF0000'),
(5, 'Cream', '#FFFDD0');

-- 4. Data Ukuran
INSERT INTO ukuran (id_ukuran, nama_ukuran, keterangan) VALUES
(1, 'S', 'Small - Lebar Dada 48 cm, Panjang 68 cm'),
(2, 'M', 'Medium - Lebar Dada 50 cm, Panjang 70 cm'),
(3, 'L', 'Large - Lebar Dada 52 cm, Panjang 72 cm'),
(4, 'XL', 'Extra Large - Lebar Dada 54 cm, Panjang 74 cm'),
(5, 'XXL', 'Double XL - Lebar Dada 56 cm, Panjang 76 cm');

-- 5. Data Produk
INSERT INTO produk (id_produk, id_kategori, nama_produk, deskripsi, harga, bahan, gaya, berat) VALUES
(1, 1, 'Kaos Polos Combed 30s', 'Kaos bahan sejuk nyaman menyerap keringat', 75000.00, 'Cotton Combed 30s', 'Casual', 200),
(2, 2, 'Kemeja Oxford Slim Fit', 'Kemeja formal modern cocok untuk kerja maupun hangout', 185000.00, 'Katun Oxford', 'Formal/Smart Casual', 300),
(3, 3, 'Celana Chino Panjang Reguler', 'Celana chino stretch elastis fleksibel', 195000.00, 'Twill Stretch', 'Casual Modern', 450),
(4, 4, 'Jaket Bomber Harrington', 'Jaket tahan angin dengan lapisan furing lembut', 275000.00, 'Taslan Despo', 'Streetwear', 500);

-- 6. Data Varian Produk
INSERT INTO varian_produk (id_varian, id_produk, id_warna, id_ukuran, kode_barang, stok) VALUES
(1, 1, 1, 2, 'TSHIRT-BLK-M', 50),  -- Kaos Hitam M
(2, 1, 1, 3, 'TSHIRT-BLK-L', 40),  -- Kaos Hitam L
(3, 1, 2, 2, 'TSHIRT-WHT-M', 35),  -- Kaos Putih M
(4, 1, 3, 3, 'TSHIRT-NVY-L', 25),  -- Kaos Navy L
(5, 2, 2, 3, 'SHIRT-WHT-L', 20),   -- Kemeja Putih L
(6, 2, 3, 4, 'SHIRT-NVY-XL', 15),  -- Kemeja Navy XL
(7, 3, 5, 3, 'CHINO-CRM-L', 30),   -- Chino Cream L
(8, 4, 1, 3, 'BOMBER-BLK-L', 18);  -- Bomber Hitam L

-- 7. Data Voucher
INSERT INTO voucher (id_voucher, kode_voucher, tipe_diskon, nilai_diskon, minimal_belanja, tanggal_mulai, tanggal_berakhir, status) VALUES
(1, 'DISKONHEBAT20', 'nominal', 20000.00, 150000.00, '2026-09-01 00:00:00', '2026-12-31 23:59:59', 'aktif'),
(2, 'GRATISONGKIR', 'nominal', 15000.00, 100000.00, '2026-09-01 00:00:00', '2026-12-31 23:59:59', 'aktif'),
(3, 'NEWCUSTOMER10', 'persen', 10.00, 50000.00, '2026-09-01 00:00:00', '2026-12-31 23:59:59', 'aktif');

-- 8. Data Kurir
INSERT INTO kurir (id_kurir, nama_kurir, kode_kurir, layanan, tarif_per_kg) VALUES
(1, 'JNE Express', 'JNE', 'Reguler (2-3 Hari)', 12000.00),
(2, 'J&T Express', 'JNT', 'EZ Express (1-2 Hari)', 14000.00),
(3, 'SiCepat Ekspres', 'SICEPAT', 'BEST (Besok Sampai)', 18000.00);

-- 9. Data Pesanan
INSERT INTO pesanan (id_pesanan, nomor_pesanan, id_pelanggan, id_voucher, tanggal_pesanan, total_harga, potongan_diskon, total_bayar, status_pesanan, alamat_pengiriman) VALUES
(1, 'ORD-202610-001', 1, 1, '2026-10-01 09:30:00', 260000.00, 20000.00, 240000.00, 'dikirim', 'Jl. Merdeka No. 45, Bandung'),
(2, 'ORD-202610-002', 2, NULL, '2026-10-02 14:20:00', 185000.00, 0.00, 185000.00, 'selesai', 'Jl. Malioboro No. 12, Yogyakarta'),
(3, 'ORD-202610-003', 3, 2, '2026-10-03 16:45:00', 470000.00, 15000.00, 455000.00, 'diproses', 'Jl. Sudirman No. 88, Jakarta Selatan');

-- 10. Data Detail Pesanan
INSERT INTO detail_pesanan (id_detail, id_pesanan, id_varian, jumlah, harga_satuan, subtotal) VALUES
(1, 1, 1, 1, 75000.00, 75000.00),    -- 1x Kaos Hitam M
(2, 1, 5, 1, 185000.00, 185000.00),  -- 1x Kemeja Putih L
(3, 2, 5, 1, 185000.00, 185000.00),  -- 1x Kemeja Putih L
(4, 3, 7, 1, 195000.00, 195000.00),  -- 1x Chino Cream L
(5, 3, 8, 1, 275000.00, 275000.00);  -- 1x Jaket Bomber L

-- 11. Data Pembayaran (1:1 dengan Pesanan)
INSERT INTO pembayaran (id_pembayaran, id_pesanan, metode_pembayaran, jumlah_bayar, waktu_bayar, status_pembayaran, bukti_pembayaran) VALUES
(1, 1, 'qris', 240000.00, '2026-10-01 09:35:00', 'lunas', 'uploads/bukti_ord1.jpg'),
(2, 2, 'transfer_bank', 185000.00, '2026-10-02 14:25:00', 'lunas', 'uploads/bukti_ord2.jpg'),
(3, 3, 'e_wallet', 455000.00, '2026-10-03 16:50:00', 'lunas', 'uploads/bukti_ord3.jpg');

-- 12. Data Pengiriman (1:1 dengan Pesanan, Relasi ke Kurir)
INSERT INTO pengiriman (id_pengiriman, id_pesanan, id_kurir, nomor_resi, status_pengiriman, biaya_ongkir, tanggal_kirim, tanggal_diterima) VALUES
(1, 1, 2, 'JNT-9823471029', 'dalam_perjalanan', 14000.00, '2026-10-01 13:00:00', NULL),
(2, 2, 1, 'JNE-4512984012', 'terkirim', 12000.00, '2026-10-02 16:00:00', '2026-10-03 11:00:00'),
(3, 3, 3, 'SCP-7781290312', 'diproses', 18000.00, NULL, NULL);


-- ========================================================
-- BAGIAN 3: CONTOH QUERY ANALISIS MULTI-JOIN
-- ========================================================

-- Query 1: Alur Lengkap Transaksi Pesanan (Pelanggan -> Pesanan -> Voucher -> Pembayaran -> Pengiriman -> Kurir)
SELECT 
    p.nomor_pesanan,
    c.nama AS nama_pelanggan,
    c.no_telepon,
    v.kode_voucher,
    p.total_harga,
    p.potongan_diskon,
    p.total_bayar,
    byr.metode_pembayaran,
    byr.status_pembayaran,
    k.nama_kurir,
    k.layanan,
    kirim.nomor_resi,
    kirim.status_pengiriman
FROM pesanan p
JOIN pelanggan c ON p.id_pelanggan = c.id_pelanggan
LEFT JOIN voucher v ON p.id_voucher = v.id_voucher
LEFT JOIN pembayaran byr ON p.id_pesanan = byr.id_pesanan
LEFT JOIN pengiriman kirim ON p.id_pesanan = kirim.id_pesanan
LEFT JOIN kurir k ON kirim.id_kurir = k.id_kurir;

-- Query 2: Rincian Lengkap Barang (Pesanan -> Detail Pesanan -> Varian -> Produk -> Warna -> Ukuran -> Kategori)
SELECT 
    p.nomor_pesanan,
    kat.nama_kategori,
    pr.nama_produk,
    w.nama_warna,
    u.nama_ukuran,
    vp.kode_barang AS sku,
    dp.jumlah,
    dp.harga_satuan,
    dp.subtotal
FROM detail_pesanan dp
JOIN pesanan p ON dp.id_pesanan = p.id_pesanan
JOIN varian_produk vp ON dp.id_varian = vp.id_varian
JOIN produk pr ON vp.id_produk = pr.id_produk
JOIN kategori kat ON pr.id_kategori = kat.id_kategori
JOIN warna w ON vp.id_warna = w.id_warna
JOIN ukuran u ON vp.id_ukuran = u.id_ukuran
ORDER BY p.nomor_pesanan, dp.id_detail;
