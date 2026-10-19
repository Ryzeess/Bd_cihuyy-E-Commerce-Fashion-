<?php
// ========================================================
// DEMO RUNNER SCRIPT UNTUK ANTIGRAVITY TERMINAL
// Menjalankan eksekusi SQL (DDL 12 Tabel, DML Seeding, dan Query Multi-JOIN)
// ========================================================

$dbFile = __DIR__ . '/database.sqlite';

echo "\n" . str_repeat("=", 78) . "\n";
echo "       🚀 DEMO DATABASE E-COMMERCE FASHION (12 ENTITAS BRD) DI ANTIGRAVITY\n";
echo str_repeat("=", 78) . "\n";
echo "
   [KATEGORI] ──(1:N)──> [PRODUK] ──(1:N)──┐
                            ▲               ▼
   [WARNA]    ──(1:N)───────┼───────> [VARIAN_PRODUK]
                            │               │
   [UKURAN]   ──(1:N)───────┘               ▼ (1:N)
                                      [DETAIL_PESANAN]
   [PELANGGAN] ──(1:N)──> [PESANAN] ────────┘
                             │
       ┌─────────────────────┼─────────────────────┐
       ▼ (1:N)               ▼ (1:1)               ▼ (1:1)
   [VOUCHER]            [PEMBAYARAN]          [PENGIRIMAN] ──(N:1)──> [KURIR]
\n";
echo str_repeat("=", 78) . "\n\n";

try {
    $pdo = new PDO("sqlite:" . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("PRAGMA foreign_keys = ON;");

    // 1. DROP EXISTING TABLES (Reverse Dependency Order)
    echo "1️⃣  [RESET & DDL] Menyiapkan 12 tabel skema basis data...\n";
    $tables = [
        'pengiriman', 'pembayaran', 'detail_pesanan', 'pesanan', 'voucher',
        'varian_produk', 'produk', 'ukuran', 'warna', 'kategori', 'pelanggan', 'kurir'
    ];
    foreach ($tables as $tbl) {
        $pdo->exec("DROP TABLE IF EXISTS {$tbl}");
    }

    // 2. CREATE 12 TABLES
    $ddl = "
    CREATE TABLE pelanggan (
        id_pelanggan INTEGER PRIMARY KEY AUTOINCREMENT,
        nama TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        no_telepon TEXT,
        alamat TEXT,
        tanggal_daftar DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE kategori (
        id_kategori INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_kategori TEXT NOT NULL,
        deskripsi TEXT
    );

    CREATE TABLE warna (
        id_warna INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_warna TEXT NOT NULL,
        kode_warna TEXT
    );

    CREATE TABLE ukuran (
        id_ukuran INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_ukuran TEXT NOT NULL,
        keterangan TEXT
    );

    CREATE TABLE produk (
        id_produk INTEGER PRIMARY KEY AUTOINCREMENT,
        id_kategori INTEGER NOT NULL,
        nama_produk TEXT NOT NULL,
        deskripsi TEXT,
        harga NUMERIC NOT NULL DEFAULT 0.00,
        bahan TEXT,
        gaya TEXT,
        berat INTEGER DEFAULT 250,
        FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori) ON DELETE RESTRICT
    );

    CREATE TABLE varian_produk (
        id_varian INTEGER PRIMARY KEY AUTOINCREMENT,
        id_produk INTEGER NOT NULL,
        id_warna INTEGER NOT NULL,
        id_ukuran INTEGER NOT NULL,
        kode_barang TEXT NOT NULL UNIQUE,
        stok INTEGER NOT NULL DEFAULT 0,
        FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE CASCADE,
        FOREIGN KEY (id_warna) REFERENCES warna(id_warna) ON DELETE RESTRICT,
        FOREIGN KEY (id_ukuran) REFERENCES ukuran(id_ukuran) ON DELETE RESTRICT
    );

    CREATE TABLE voucher (
        id_voucher INTEGER PRIMARY KEY AUTOINCREMENT,
        kode_voucher TEXT NOT NULL UNIQUE,
        tipe_diskon TEXT CHECK(tipe_diskon IN ('persen', 'nominal')) DEFAULT 'nominal',
        nilai_diskon NUMERIC NOT NULL DEFAULT 0.00,
        minimal_belanja NUMERIC NOT NULL DEFAULT 0.00,
        tanggal_mulai DATETIME,
        tanggal_berakhir DATETIME,
        status TEXT CHECK(status IN ('aktif', 'nonaktif')) DEFAULT 'aktif'
    );

    CREATE TABLE pesanan (
        id_pesanan INTEGER PRIMARY KEY AUTOINCREMENT,
        nomor_pesanan TEXT NOT NULL UNIQUE,
        id_pelanggan INTEGER NOT NULL,
        id_voucher INTEGER NULL,
        tanggal_pesanan DATETIME DEFAULT CURRENT_TIMESTAMP,
        total_harga NUMERIC NOT NULL DEFAULT 0.00,
        potongan_diskon NUMERIC DEFAULT 0.00,
        total_bayar NUMERIC NOT NULL DEFAULT 0.00,
        status_pesanan TEXT CHECK(status_pesanan IN ('menunggu_pembayaran', 'diproses', 'dikirim', 'selesai', 'dibatalkan')) DEFAULT 'menunggu_pembayaran',
        alamat_pengiriman TEXT NOT NULL,
        FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan) ON DELETE RESTRICT,
        FOREIGN KEY (id_voucher) REFERENCES voucher(id_voucher) ON DELETE SET NULL
    );

    CREATE TABLE detail_pesanan (
        id_detail INTEGER PRIMARY KEY AUTOINCREMENT,
        id_pesanan INTEGER NOT NULL,
        id_varian INTEGER NOT NULL,
        jumlah INTEGER NOT NULL DEFAULT 1,
        harga_satuan NUMERIC NOT NULL,
        subtotal NUMERIC NOT NULL,
        FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
        FOREIGN KEY (id_varian) REFERENCES varian_produk(id_varian) ON DELETE RESTRICT
    );

    CREATE TABLE pembayaran (
        id_pembayaran INTEGER PRIMARY KEY AUTOINCREMENT,
        id_pesanan INTEGER NOT NULL UNIQUE,
        metode_pembayaran TEXT NOT NULL,
        jumlah_bayar NUMERIC NOT NULL,
        waktu_bayar DATETIME,
        status_pembayaran TEXT CHECK(status_pembayaran IN ('menunggu', 'lunas', 'gagal', 'kedaluwarsa')) DEFAULT 'menunggu',
        bukti_pembayaran TEXT,
        FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE
    );

    CREATE TABLE kurir (
        id_kurir INTEGER PRIMARY KEY AUTOINCREMENT,
        nama_kurir TEXT NOT NULL,
        kode_kurir TEXT NOT NULL UNIQUE,
        layanan TEXT NOT NULL,
        tarif_per_kg NUMERIC NOT NULL DEFAULT 10000.00
    );

    CREATE TABLE pengiriman (
        id_pengiriman INTEGER PRIMARY KEY AUTOINCREMENT,
        id_pesanan INTEGER NOT NULL UNIQUE,
        id_kurir INTEGER NOT NULL,
        nomor_resi TEXT NOT NULL UNIQUE,
        status_pengiriman TEXT CHECK(status_pengiriman IN ('diproses', 'dalam_perjalanan', 'terkirim', 'gagal')) DEFAULT 'diproses',
        biaya_ongkir NUMERIC NOT NULL DEFAULT 0.00,
        tanggal_kirim DATETIME,
        tanggal_diterima DATETIME,
        FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan) ON DELETE CASCADE,
        FOREIGN KEY (id_kurir) REFERENCES kurir(id_kurir) ON DELETE RESTRICT
    );
    ";
    $pdo->exec($ddl);
    echo "    ✅ Berhasil membuat 12 Tabel (pelanggan, kategori, warna, ukuran, produk, varian, voucher, pesanan, detail, pembayaran, kurir, pengiriman)!\n\n";

    // 3. INSERT SAMPLE DATA (DML)
    echo "2️⃣  [DML SEEDING] Mengisi data sampel 12 entitas...\n";

    $pdo->exec("
    INSERT INTO pelanggan (id_pelanggan, nama, email, password, no_telepon, alamat, tanggal_daftar) VALUES
    (1, 'Ahmad Fauzi', 'ahmad.fauzi@gmail.com', 'secret_hash1', '081234567890', 'Jl. Merdeka No. 45, Bandung', '2026-09-01 10:00:00'),
    (2, 'Siti Nurhaliza', 'siti.nur@gmail.com', 'secret_hash2', '085678901234', 'Jl. Malioboro No. 12, Yogyakarta', '2026-09-05 11:30:00'),
    (3, 'Budi Santoso', 'budi.santoso@yahoo.com', 'secret_hash3', '087890123456', 'Jl. Sudirman No. 88, Jakarta', '2026-09-10 14:15:00');

    INSERT INTO kategori (id_kategori, nama_kategori, deskripsi) VALUES
    (1, 'Kaos', 'Koleksi t-shirt casual santai harian'),
    (2, 'Kemeja', 'Kemeja formal dan kasual lengan panjang/pendek'),
    (3, 'Celana', 'Celana chinos, jeans, dan formal'),
    (4, 'Jaket', 'Outerwear hangat dan windbreaker stylish'),
    (5, 'Dress', 'Gaun wanita anggun untuk berbagai acara');

    INSERT INTO warna (id_warna, nama_warna, kode_warna) VALUES
    (1, 'Hitam', '#000000'),
    (2, 'Putih', '#FFFFFF'),
    (3, 'Navy', '#000080'),
    (4, 'Merah', '#FF0000'),
    (5, 'Cream', '#FFFDD0');

    INSERT INTO ukuran (id_ukuran, nama_ukuran, keterangan) VALUES
    (1, 'S', 'Small - Lebar Dada 48 cm, Panjang 68 cm'),
    (2, 'M', 'Medium - Lebar Dada 50 cm, Panjang 70 cm'),
    (3, 'L', 'Large - Lebar Dada 52 cm, Panjang 72 cm'),
    (4, 'XL', 'Extra Large - Lebar Dada 54 cm, Panjang 74 cm'),
    (5, 'XXL', 'Double XL - Lebar Dada 56 cm, Panjang 76 cm');

    INSERT INTO produk (id_produk, id_kategori, nama_produk, deskripsi, harga, bahan, gaya, berat) VALUES
    (1, 1, 'Kaos Polos Combed 30s', 'Kaos bahan sejuk nyaman menyerap keringat', 75000.00, 'Cotton Combed 30s', 'Casual', 200),
    (2, 2, 'Kemeja Oxford Slim Fit', 'Kemeja formal modern cocok untuk kerja maupun hangout', 185000.00, 'Katun Oxford', 'Formal/Smart Casual', 300),
    (3, 3, 'Celana Chino Panjang Reguler', 'Celana chino stretch elastis fleksibel', 195000.00, 'Twill Stretch', 'Casual Modern', 450),
    (4, 4, 'Jaket Bomber Harrington', 'Jaket tahan angin dengan lapisan furing lembut', 275000.00, 'Taslan Despo', 'Streetwear', 500);

    INSERT INTO varian_produk (id_varian, id_produk, id_warna, id_ukuran, kode_barang, stok) VALUES
    (1, 1, 1, 2, 'TSHIRT-BLK-M', 50),
    (2, 1, 1, 3, 'TSHIRT-BLK-L', 40),
    (3, 1, 2, 2, 'TSHIRT-WHT-M', 35),
    (4, 1, 3, 3, 'TSHIRT-NVY-L', 25),
    (5, 2, 2, 3, 'SHIRT-WHT-L', 20),
    (6, 2, 3, 4, 'SHIRT-NVY-XL', 15),
    (7, 3, 5, 3, 'CHINO-CRM-L', 30),
    (8, 4, 1, 3, 'BOMBER-BLK-L', 18);

    INSERT INTO voucher (id_voucher, kode_voucher, tipe_diskon, nilai_diskon, minimal_belanja, tanggal_mulai, tanggal_berakhir, status) VALUES
    (1, 'DISKONHEBAT20', 'nominal', 20000.00, 150000.00, '2026-09-01 00:00:00', '2026-12-31 23:59:59', 'aktif'),
    (2, 'GRATISONGKIR', 'nominal', 15000.00, 100000.00, '2026-09-01 00:00:00', '2026-12-31 23:59:59', 'aktif'),
    (3, 'NEWCUSTOMER10', 'persen', 10.00, 50000.00, '2026-09-01 00:00:00', '2026-12-31 23:59:59', 'aktif');

    INSERT INTO kurir (id_kurir, nama_kurir, kode_kurir, layanan, tarif_per_kg) VALUES
    (1, 'JNE Express', 'JNE', 'Reguler (2-3 Hari)', 12000.00),
    (2, 'J&T Express', 'JNT', 'EZ Express (1-2 Hari)', 14000.00),
    (3, 'SiCepat Ekspres', 'SICEPAT', 'BEST (Besok Sampai)', 18000.00);

    INSERT INTO pesanan (id_pesanan, nomor_pesanan, id_pelanggan, id_voucher, tanggal_pesanan, total_harga, potongan_diskon, total_bayar, status_pesanan, alamat_pengiriman) VALUES
    (1, 'ORD-202610-001', 1, 1, '2026-10-01 09:30:00', 260000.00, 20000.00, 240000.00, 'dikirim', 'Jl. Merdeka No. 45, Bandung'),
    (2, 'ORD-202610-002', 2, NULL, '2026-10-02 14:20:00', 185000.00, 0.00, 185000.00, 'selesai', 'Jl. Malioboro No. 12, Yogyakarta'),
    (3, 'ORD-202610-003', 3, 2, '2026-10-03 16:45:00', 470000.00, 15000.00, 455000.00, 'diproses', 'Jl. Sudirman No. 88, Jakarta Selatan');

    INSERT INTO detail_pesanan (id_detail, id_pesanan, id_varian, jumlah, harga_satuan, subtotal) VALUES
    (1, 1, 1, 1, 75000.00, 75000.00),
    (2, 1, 5, 1, 185000.00, 185000.00),
    (3, 2, 5, 1, 185000.00, 185000.00),
    (4, 3, 7, 1, 195000.00, 195000.00),
    (5, 3, 8, 1, 275000.00, 275000.00);

    INSERT INTO pembayaran (id_pembayaran, id_pesanan, metode_pembayaran, jumlah_bayar, waktu_bayar, status_pembayaran, bukti_pembayaran) VALUES
    (1, 1, 'qris', 240000.00, '2026-10-01 09:35:00', 'lunas', 'uploads/bukti_ord1.jpg'),
    (2, 2, 'transfer_bank', 185000.00, '2026-10-02 14:25:00', 'lunas', 'uploads/bukti_ord2.jpg'),
    (3, 3, 'e_wallet', 455000.00, '2026-10-03 16:50:00', 'lunas', 'uploads/bukti_ord3.jpg');

    INSERT INTO pengiriman (id_pengiriman, id_pesanan, id_kurir, nomor_resi, status_pengiriman, biaya_ongkir, tanggal_kirim, tanggal_diterima) VALUES
    (1, 1, 2, 'JNT-9823471029', 'dalam_perjalanan', 14000.00, '2026-10-01 13:00:00', NULL),
    (2, 2, 1, 'JNE-4512984012', 'terkirim', 12000.00, '2026-10-02 16:00:00', '2026-10-03 11:00:00'),
    (3, 3, 3, 'SCP-7781290312', 'diproses', 18000.00, NULL, NULL);
    ");
    echo "    ✅ Berhasil mengisi data relasi lengkap (Pelanggan, Produk, Varian, Pesanan, Pembayaran, Kurir, Pengiriman)!\n\n";

    // 4. TEST MULTI-JOIN QUERY 1: Alur Transaksi Lengkap
    echo "3️⃣  [QUERY JOIN TEST 1] Alur Pesanan -> Pelanggan -> Voucher -> Bayar -> Pengiriman -> Kurir:\n";
    $query1 = "
    SELECT 
        p.nomor_pesanan,
        c.nama AS nama_pelanggan,
        COALESCE(v.kode_voucher, '-') AS voucher,
        p.total_bayar,
        p.status_pesanan,
        byr.metode_pembayaran,
        byr.status_pembayaran,
        k.nama_kurir,
        kirim.nomor_resi,
        kirim.status_pengiriman
    FROM pesanan p
    JOIN pelanggan c ON p.id_pelanggan = c.id_pelanggan
    LEFT JOIN voucher v ON p.id_voucher = v.id_voucher
    LEFT JOIN pembayaran byr ON p.id_pesanan = byr.id_pesanan
    LEFT JOIN pengiriman kirim ON p.id_pesanan = kirim.id_pesanan
    LEFT JOIN kurir k ON kirim.id_kurir = k.id_kurir
    ORDER BY p.id_pesanan ASC
    ";
    $stmt1 = $pdo->query($query1);
    $rows1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    printf("+-%-15s-+-%-15s-+-%-14s-+-%-12s-+-%-12s-+-%-12s-+-%-14s-+-%-15s-+\n", 
        str_repeat('-', 15), str_repeat('-', 15), str_repeat('-', 14), 
        str_repeat('-', 12), str_repeat('-', 12), str_repeat('-', 12), str_repeat('-', 14), str_repeat('-', 15));
    printf("| %-15s | %-15s | %-14s | %-12s | %-12s | %-12s | %-14s | %-15s |\n",
        "NO PESANAN", "PELANGGAN", "VOUCHER", "TOTAL (RP)", "STATUS ORDER", "METODE BAYAR", "KURIR", "NO RESI");
    printf("+-%-15s-+-%-15s-+-%-14s-+-%-12s-+-%-12s-+-%-12s-+-%-14s-+-%-15s-+\n", 
        str_repeat('-', 15), str_repeat('-', 15), str_repeat('-', 14), 
        str_repeat('-', 12), str_repeat('-', 12), str_repeat('-', 12), str_repeat('-', 14), str_repeat('-', 15));
    foreach ($rows1 as $r) {
        printf("| %-15s | %-15s | %-14s | %-12s | %-12s | %-12s | %-14s | %-15s |\n",
            $r['nomor_pesanan'], $r['nama_pelanggan'], $r['voucher'], 
            number_format($r['total_bayar'], 0, ',', '.'), 
            $r['status_pesanan'], $r['metode_pembayaran'], $r['nama_kurir'], $r['nomor_resi']);
    }
    printf("+-%-15s-+-%-15s-+-%-14s-+-%-12s-+-%-12s-+-%-12s-+-%-14s-+-%-15s-+\n\n", 
        str_repeat('-', 15), str_repeat('-', 15), str_repeat('-', 14), 
        str_repeat('-', 12), str_repeat('-', 12), str_repeat('-', 12), str_repeat('-', 14), str_repeat('-', 15));

    // 5. TEST MULTI-JOIN QUERY 2: Detail Pesanan -> Varian -> Produk -> Warna -> Ukuran
    echo "4️⃣  [QUERY JOIN TEST 2] Rincian Item: Detail Pesanan -> Varian -> Warna -> Ukuran -> Kategori:\n";
    $query2 = "
    SELECT 
        p.nomor_pesanan,
        kat.nama_kategori AS kategori,
        pr.nama_produk,
        w.nama_warna AS warna,
        u.nama_ukuran AS ukuran,
        vp.kode_barang AS sku,
        dp.jumlah AS qty,
        dp.subtotal
    FROM detail_pesanan dp
    JOIN pesanan p ON dp.id_pesanan = p.id_pesanan
    JOIN varian_produk vp ON dp.id_varian = vp.id_varian
    JOIN produk pr ON vp.id_produk = pr.id_produk
    JOIN kategori kat ON pr.id_kategori = kat.id_kategori
    JOIN warna w ON vp.id_warna = w.id_warna
    JOIN ukuran u ON vp.id_ukuran = u.id_ukuran
    ORDER BY p.nomor_pesanan, dp.id_detail
    ";
    $stmt2 = $pdo->query($query2);
    $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    printf("+-%-15s-+-%-10s-+-%-24s-+-%-8s-+-%-6s-+-%-13s-+-%-4s-+-%-12s-+\n", 
        str_repeat('-', 15), str_repeat('-', 10), str_repeat('-', 24), 
        str_repeat('-', 8), str_repeat('-', 6), str_repeat('-', 13), str_repeat('-', 4), str_repeat('-', 12));
    printf("| %-15s | %-10s | %-24s | %-8s | %-6s | %-13s | %-4s | %-12s |\n",
        "NO PESANAN", "KATEGORI", "NAMA PRODUK", "WARNA", "SIZE", "SKU", "QTY", "SUBTOTAL");
    printf("+-%-15s-+-%-10s-+-%-24s-+-%-8s-+-%-6s-+-%-13s-+-%-4s-+-%-12s-+\n", 
        str_repeat('-', 15), str_repeat('-', 10), str_repeat('-', 24), 
        str_repeat('-', 8), str_repeat('-', 6), str_repeat('-', 13), str_repeat('-', 4), str_repeat('-', 12));
    foreach ($rows2 as $r) {
        printf("| %-15s | %-10s | %-24s | %-8s | %-6s | %-13s | %-4s | %-12s |\n",
            $r['nomor_pesanan'], $r['kategori'], substr($r['nama_produk'], 0, 24), 
            $r['warna'], $r['ukuran'], $r['sku'], $r['qty'], number_format($r['subtotal'], 0, ',', '.'));
    }
    printf("+-%-15s-+-%-10s-+-%-24s-+-%-8s-+-%-6s-+-%-13s-+-%-4s-+-%-12s-+\n\n", 
        str_repeat('-', 15), str_repeat('-', 10), str_repeat('-', 24), 
        str_repeat('-', 8), str_repeat('-', 6), str_repeat('-', 13), str_repeat('-', 4), str_repeat('-', 12));

    echo "✨ DEMO 12 ENTITAS SELESAI DENGAN SUKSES! Seluruh relasi terhubung sempurna di SQLite.\n\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
