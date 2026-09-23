<?php
// ========================================================
// DEMO RUNNER SCRIPT UNTUK ANTIGRAVITY TERMINAL
// Menjalankan eksekusi SQL (DDL, DML, dan Query JOIN)
// ========================================================

$dbFile = __DIR__ . '/database.sqlite';

echo "\n" . str_repeat("=", 68) . "\n";
echo "       🚀 DEMO DATABASE CIHUY STORE DI DALAM ANTIGRAVITY\n";
echo str_repeat("=", 68) . "\n";
echo "
  [CATEGORIES] ──(1:N)──> [PRODUCTS] ──(1:N)──┐
                                               ▼
  [USERS] ───────(1:1)──> [USER_PROFILES]   [ORDER_ITEMS] (Pivot N:M)
     │                                         ▲
     └───────────(1:N)──> [ORDERS] ───(1:N)────┘
                             │
                             └────────(1:1)──> [PAYMENTS]
\n";
echo str_repeat("=", 68) . "\n\n";

try {
    $pdo = new PDO("sqlite:" . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("PRAGMA foreign_keys = ON;");

    // 1. DROP EXISTING TABLES
    echo "1️⃣  [RESET & DDL] Menyiapkan tabel skema...\n";
    $tables = ['payments', 'order_items', 'orders', 'products', 'categories', 'user_profiles', 'users'];
    foreach ($tables as $tbl) {
        $pdo->exec("DROP TABLE IF EXISTS {$tbl}");
    }

    // 2. CREATE TABLES
    $ddl = "
    CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        role TEXT CHECK(role IN ('admin', 'staff', 'customer')) DEFAULT 'customer',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE user_profiles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL UNIQUE,
        phone TEXT,
        address TEXT,
        city TEXT,
        postal_code TEXT,
        avatar_url TEXT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE categories (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE products (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category_id INTEGER NOT NULL,
        name TEXT NOT NULL,
        slug TEXT NOT NULL UNIQUE,
        description TEXT,
        price NUMERIC NOT NULL DEFAULT 0.00,
        stock INTEGER NOT NULL DEFAULT 0,
        is_active INTEGER DEFAULT 1,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
    );

    CREATE TABLE orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_code TEXT NOT NULL UNIQUE,
        user_id INTEGER NOT NULL,
        total_amount NUMERIC NOT NULL DEFAULT 0.00,
        status TEXT CHECK(status IN ('pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled')) DEFAULT 'pending',
        shipping_address TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT
    );

    CREATE TABLE order_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id INTEGER NOT NULL,
        product_id INTEGER NOT NULL,
        quantity INTEGER NOT NULL DEFAULT 1,
        unit_price NUMERIC NOT NULL,
        subtotal NUMERIC NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
    );

    CREATE TABLE payments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id INTEGER NOT NULL UNIQUE,
        payment_method TEXT CHECK(payment_method IN ('bank_transfer', 'qris', 'e_wallet', 'credit_card', 'cod')) NOT NULL,
        amount NUMERIC NOT NULL,
        payment_status TEXT CHECK(payment_status IN ('unpaid', 'paid', 'failed', 'refunded')) DEFAULT 'unpaid',
        transaction_time DATETIME,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
    );
    ";
    $pdo->exec($ddl);
    echo "    ✅ Berhasil membuat 7 Tabel (users, profiles, categories, products, orders, items, payments)\n\n";

    // 3. INSERT SAMPLE DATA (DML)
    echo "2️⃣  [DML SEEDING] Mengisi contoh data transaksi & relasi...\n";

    $pdo->exec("
    INSERT INTO users (id, name, email, password, role) VALUES
    (1, 'Admin Cihuy', 'admin@cihuy.com', 'secret_hash', 'admin'),
    (2, 'Budi Santoso', 'budi@gmail.com', 'secret_hash', 'customer'),
    (3, 'Siti Nurhaliza', 'siti@gmail.com', 'secret_hash', 'customer');

    INSERT INTO user_profiles (user_id, phone, address, city, postal_code, avatar_url) VALUES
    (1, '081234567890', 'Gedung IT Lantai 3', 'Jakarta Selatan', '12190', 'avatars/admin.png'),
    (2, '085678901234', 'Jl. Merdeka No. 45', 'Bandung', '40115', 'avatars/budi.png'),
    (3, '087890123456', 'Jl. Malioboro No. 12', 'Yogyakarta', '55271', 'avatars/siti.png');

    INSERT INTO categories (id, name, slug, description) VALUES
    (1, 'Elektronik', 'elektronik', 'Peralatan gadget dan aksesoris komputer'),
    (2, 'Pakaian Pria', 'pakaian-pria', 'Koleksi baju, kaos, celana pria'),
    (3, 'Buku & Alat Tulis', 'buku-alat-tulis', 'Buku bacaan dan modul kuliah');

    INSERT INTO products (id, category_id, name, slug, description, price, stock, is_active) VALUES
    (1, 1, 'Mouse Wireless Silent', 'mouse-wireless-silent', 'Mouse tanpa kabel hemat baterai', 125000.00, 50, 1),
    (2, 1, 'Keyboard Mechanical TKL', 'keyboard-mechanical-tkl', 'Keyboard switch red dengan RGB light', 450000.00, 25, 1),
    (3, 2, 'Kaos Polos Cotton 30s', 'kaos-polos-cotton-30s', 'Bahan adem 100% katun', 65000.00, 100, 1),
    (4, 3, 'Buku Master Database SQL', 'buku-master-database-sql', 'Panduan perancangan database', 95000.00, 40, 1);

    INSERT INTO orders (id, order_code, user_id, total_amount, status, shipping_address) VALUES
    (1, 'ORD-202609-001', 2, 575000.00, 'paid', 'Jl. Merdeka No. 45, Bandung'),
    (2, 'ORD-202609-002', 3, 160000.00, 'processing', 'Jl. Malioboro No. 12, Yogyakarta');

    INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) VALUES
    (1, 2, 1, 450000.00, 450000.00),
    (1, 1, 1, 125000.00, 125000.00),
    (2, 3, 1, 65000.00, 65000.00),
    (2, 4, 1, 95000.00, 95000.00);

    INSERT INTO payments (order_id, payment_method, amount, payment_status, transaction_time) VALUES
    (1, 'qris', 575000.00, 'paid', '2026-09-23 10:15:00'),
    (2, 'bank_transfer', 160000.00, 'paid', '2026-09-23 14:30:00');
    ");
    echo "    ✅ Berhasil seeding data Users, Products, Orders, Items, dan Payments!\n\n";

    // 4. TEST JOIN QUERY
    echo "3️⃣  [QUERY JOIN TEST] Hasil Relasi Antar Tabel (Orders -> Users -> Payments):\n";
    $query1 = "
    SELECT 
        o.order_code,
        u.name AS customer_name,
        p_profile.city,
        o.total_amount,
        o.status AS order_status,
        p.payment_method,
        p.payment_status
    FROM orders o
    JOIN users u ON o.user_id = u.id
    LEFT JOIN user_profiles p_profile ON u.id = p_profile.user_id
    LEFT JOIN payments p ON o.id = p.order_id
    ";
    $stmt1 = $pdo->query($query1);
    $rows1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    printf("+-%-15s-+-%-15s-+-%-15s-+-%-12s-+-%-10s-+-%-12s-+-%-10s-+\n", 
        str_repeat('-', 15), str_repeat('-', 15), str_repeat('-', 15), 
        str_repeat('-', 12), str_repeat('-', 10), str_repeat('-', 12), str_repeat('-', 10));
    printf("| %-15s | %-15s | %-15s | %-12s | %-10s | %-12s | %-10s |\n",
        "KODE ORDER", "CUSTOMER", "KOTA", "TOTAL (RP)", "STATUS", "METODE BAYAR", "BAYAR");
    printf("+-%-15s-+-%-15s-+-%-15s-+-%-12s-+-%-10s-+-%-12s-+-%-10s-+\n", 
        str_repeat('-', 15), str_repeat('-', 15), str_repeat('-', 15), 
        str_repeat('-', 12), str_repeat('-', 10), str_repeat('-', 12), str_repeat('-', 10));
    foreach ($rows1 as $r) {
        printf("| %-15s | %-15s | %-15s | %-12s | %-10s | %-12s | %-10s |\n",
            $r['order_code'], $r['customer_name'], $r['city'], 
            number_format($r['total_amount'], 0, ',', '.'), 
            $r['order_status'], $r['payment_method'], $r['payment_status']);
    }
    printf("+-%-15s-+-%-15s-+-%-15s-+-%-12s-+-%-10s-+-%-12s-+-%-10s-+\n\n", 
        str_repeat('-', 15), str_repeat('-', 15), str_repeat('-', 15), 
        str_repeat('-', 12), str_repeat('-', 10), str_repeat('-', 12), str_repeat('-', 10));

    echo "4️⃣  [QUERY RINCIAN ITEM] Detail Produk yang Dipesan:\n";
    $query2 = "
    SELECT 
        o.order_code,
        c.name AS kategori,
        pr.name AS nama_produk,
        oi.quantity AS qty,
        oi.unit_price,
        oi.subtotal
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    JOIN products pr ON oi.product_id = pr.id
    JOIN categories c ON pr.category_id = c.id
    ORDER BY o.order_code, oi.id
    ";
    $stmt2 = $pdo->query($query2);
    $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    printf("+-%-15s-+-%-15s-+-%-25s-+-%-4s-+-%-12s-+-%-12s-+\n", 
        str_repeat('-', 15), str_repeat('-', 15), str_repeat('-', 25), 
        str_repeat('-', 4), str_repeat('-', 12), str_repeat('-', 12));
    printf("| %-15s | %-15s | %-25s | %-4s | %-12s | %-12s |\n",
        "KODE ORDER", "KATEGORI", "PRODUK", "QTY", "HARGA SATUAN", "SUBTOTAL");
    printf("+-%-15s-+-%-15s-+-%-25s-+-%-4s-+-%-12s-+-%-12s-+\n", 
        str_repeat('-', 15), str_repeat('-', 15), str_repeat('-', 25), 
        str_repeat('-', 4), str_repeat('-', 12), str_repeat('-', 12));
    foreach ($rows2 as $r) {
        printf("| %-15s | %-15s | %-25s | %-4s | %-12s | %-12s |\n",
            $r['order_code'], $r['kategori'], substr($r['nama_produk'], 0, 25), 
            $r['qty'], number_format($r['unit_price'], 0, ',', '.'), number_format($r['subtotal'], 0, ',', '.'));
    }
    printf("+-%-15s-+-%-15s-+-%-25s-+-%-4s-+-%-12s-+-%-12s-+\n\n", 
        str_repeat('-', 15), str_repeat('-', 15), str_repeat('-', 25), 
        str_repeat('-', 4), str_repeat('-', 12), str_repeat('-', 12));

    echo "✨ DEMO SELESAI DENGAN SUKSES! Database SQLite lokal sudah terisi penuh data riil.\n\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
