-- ========================================================
-- SQL SCRIPT: DATABASE CIHUY STORE
-- Kompatibel dengan MySQL / MariaDB (dan mudah disesuaikan ke PostgreSQL/SQLite)
-- Berisi: DDL (Struktur Tabel) + DML (Contoh Data Jadi) + Contoh Query Join
-- ========================================================

-- 1. PILIH / BUAT DATABASE (Opsional)
CREATE DATABASE IF NOT EXISTS cihuy_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cihuy_store;

-- Hapus tabel lama jika ingin reset (urutan child ke parent)
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS user_profiles;
DROP TABLE IF EXISTS users;

-- ========================================================
-- BAGIAN 1: DDL (CREATE TABLES)
-- ========================================================

-- 1.1 TABEL USERS
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff', 'customer') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 1.2 TABEL USER_PROFILES (Relasi 1:1 dengan users)
CREATE TABLE user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    postal_code VARCHAR(10),
    avatar_url VARCHAR(255),
    CONSTRAINT fk_profile_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 1.3 TABEL CATEGORIES
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 1.4 TABEL PRODUCTS (Relasi N:1 dengan categories)
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    stock INT NOT NULL DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_category FOREIGN KEY (category_id) 
        REFERENCES categories(id) ON DELETE RESTRICT,
    INDEX idx_product_category (category_id),
    INDEX idx_product_price (price),
    INDEX idx_product_active (is_active)
) ENGINE=InnoDB;

-- 1.5 TABEL ORDERS (Relasi N:1 dengan users)
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_code VARCHAR(30) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    total_amount DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    status ENUM('pending', 'paid', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    shipping_address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_order_user (user_id),
    INDEX idx_order_status (status),
    INDEX idx_order_code (order_code)
) ENGINE=InnoDB;

-- 1.6 TABEL ORDER_ITEMS (Relasi Many-to-Many antara orders & products)
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(12, 2) NOT NULL,
    subtotal DECIMAL(12, 2) NOT NULL,
    CONSTRAINT fk_item_order FOREIGN KEY (order_id) 
        REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_item_product FOREIGN KEY (product_id) 
        REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_item_order (order_id),
    INDEX idx_item_product (product_id)
) ENGINE=InnoDB;

-- 1.7 TABEL PAYMENTS (Relasi 1:1 dengan orders)
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL UNIQUE,
    payment_method ENUM('bank_transfer', 'qris', 'e_wallet', 'credit_card', 'cod') NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    payment_status ENUM('unpaid', 'paid', 'failed', 'refunded') DEFAULT 'unpaid',
    transaction_time TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_payment_order FOREIGN KEY (order_id) 
        REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- ========================================================
-- BAGIAN 2: DML (CONTOH DATA JADI / SEED DUMMY)
-- ========================================================

-- Data Users
INSERT INTO users (id, name, email, password, role) VALUES
(1, 'Admin Cihuy', 'admin@cihuy.com', '$2y$12$e6x...hashadmin', 'admin'),
(2, 'Budi Santoso', 'budi@gmail.com', '$2y$12$f8y...hashbudi', 'customer'),
(3, 'Siti Nurhaliza', 'siti@gmail.com', '$2y$12$k9z...hashsiti', 'customer');

-- Data Profil Pengguna
INSERT INTO user_profiles (user_id, phone, address, city, postal_code, avatar_url) VALUES
(1, '081234567890', 'Gedung IT Lantai 3', 'Jakarta Selatan', '12190', 'avatars/admin.png'),
(2, '085678901234', 'Jl. Merdeka No. 45', 'Bandung', '40115', 'avatars/budi.png'),
(3, '087890123456', 'Jl. Malioboro No. 12', 'Yogyakarta', '55271', 'avatars/siti.png');

-- Data Kategori
INSERT INTO categories (id, name, slug, description) VALUES
(1, 'Elektronik', 'elektronik', 'Peralatan gadget dan aksesoris komputer'),
(2, 'Pakaian Pria', 'pakaian-pria', 'Koleksi baju, kaos, celana pria'),
(3, 'Buku & Alat Tulis', 'buku-alat-tulis', 'Buku bacaan, novel, perlengkapan belajar');

-- Data Produk
INSERT INTO products (id, category_id, name, slug, description, price, stock, is_active) VALUES
(1, 1, 'Mouse Wireless Silent Click', 'mouse-wireless-silent-click', 'Mouse ergonomis hemat baterai', 125000.00, 50, TRUE),
(2, 1, 'Keyboard Mechanical TKL', 'keyboard-mechanical-tkl', 'Keyboard switch red dengan RGB light', 450000.00, 25, TRUE),
(3, 2, 'Kaos Polos Cotton Combed 30s', 'kaos-polos-cotton-30s', 'Bahan adem, nyaman dipakai sehari-hari', 65000.00, 100, TRUE),
(4, 3, 'Buku Master Database SQL', 'buku-master-database-sql', 'Panduan lengkap merancang basis data modern', 95000.00, 40, TRUE);

-- Data Transaksi Pesanan (Orders)
INSERT INTO orders (id, order_code, user_id, total_amount, status, shipping_address) VALUES
(1, 'ORD-202609-001', 2, 575000.00, 'paid', 'Jl. Merdeka No. 45, Bandung'),
(2, 'ORD-202609-002', 3, 160000.00, 'processing', 'Jl. Malioboro No. 12, Yogyakarta');

-- Rincian Item Pesanan (Order Items)
-- Order 1: 1 Keyboard (450.000) + 1 Mouse (125.000) = 575.000
INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) VALUES
(1, 2, 1, 450000.00, 450000.00),
(1, 1, 1, 125000.00, 125000.00);

-- Order 2: 1 Kaos (65.000) + 1 Buku (95.000) = 160.000
INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) VALUES
(2, 3, 1, 65000.00, 65000.00),
(2, 4, 1, 95000.00, 95000.00);

-- Data Pembayaran (Payments)
INSERT INTO payments (order_id, payment_method, amount, payment_status, transaction_time) VALUES
(1, 'qris', 575000.00, 'paid', '2026-09-23 10:15:00'),
(2, 'bank_transfer', 160000.00, 'paid', '2026-09-23 14:30:00');


-- ========================================================
-- BAGIAN 3: CONTOH QUERY ANALISIS & JOIN POPULER
-- ========================================================

-- Query 1: Menampilkan rekap belanja lengkap customer beserta status pembayarannya
SELECT 
    o.order_code,
    u.name AS customer_name,
    u.email,
    p_profile.phone,
    o.total_amount,
    o.status AS order_status,
    p.payment_method,
    p.payment_status,
    o.created_at AS order_date
FROM orders o
JOIN users u ON o.user_id = u.id
LEFT JOIN user_profiles p_profile ON u.id = p_profile.user_id
LEFT JOIN payments p ON o.id = p.order_id;

-- Query 2: Menampilkan rincian barang apa saja yang dibeli pada pesanan tertentu
SELECT 
    o.order_code,
    c.name AS category,
    pr.name AS product_name,
    oi.quantity,
    oi.unit_price,
    oi.subtotal
FROM order_items oi
JOIN orders o ON oi.order_id = o.id
JOIN products pr ON oi.product_id = pr.id
JOIN categories c ON pr.category_id = c.id
WHERE o.order_code = 'ORD-202609-001';
