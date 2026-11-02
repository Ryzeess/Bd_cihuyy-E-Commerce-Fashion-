<?php

ini_set('memory_limit', '1024M');
set_time_limit(0);

$dbNames = ['cihuy_store', 'bd_cihuyy'];

$firstNames = [
    'Ahmad', 'Budi', 'Siti', 'Rina', 'Eko', 'Dewi', 'Andi', 'Putri', 'Rizky', 'Maya',
    'Dian', 'Fajar', 'Mega', 'Bayu', 'Intan', 'Dimas', 'Nita', 'Aris', 'Tri', 'Wahyu',
    'Agus', 'Hendra', 'Nurul', 'Indah', 'Yusuf', 'Ilham', 'Taufik', 'Rahmat', 'Anisa', 'Citra',
    'Doni', 'Gita', 'Hadi', 'Irfan', 'Joko', 'Kartika', 'Lestari', 'Maulana', 'Nadia', 'Oki',
    'Panji', 'Qori', 'Rendra', 'Satria', 'Tari', 'Utami', 'Vina', 'Wawan', 'Yoga', 'Zahra'
];

$lastNames = [
    'Pratama', 'Santoso', 'Saputra', 'Wijaya', 'Kusuma', 'Hidayat', 'Lestari', 'Nugroho', 'Wulandari', 'Siregar',
    'Utami', 'Permana', 'Firmansyah', 'Gunawan', 'Suryono', 'Purnomo', 'Setiawan', 'Prasetyo', 'Ramadhan', 'Mahendra',
    'Hakim', 'Nasution', 'Batubara', 'Harahap', 'Simanjuntak', 'Sitorus', 'Pasaribu', 'Tambunan', 'Hutapea', 'Siahaan'
];

$streets = [
    'Jl. Sudirman', 'Jl. Thamrin', 'Jl. Gatot Subroto', 'Jl. Merdeka', 'Jl. Malioboro',
    'Jl. Diponegoro', 'Jl. Asia Afrika', 'Jl. Gajah Mada', 'Jl. Hayam Wuruk', 'Jl. Pemuda',
    'Jl. Ahmad Yani', 'Jl. Pahlawan', 'Jl. R.E. Martadinata', 'Jl. Dago', 'Jl. Riau'
];

$cities = ['Jakarta', 'Bandung', 'Surabaya', 'Semarang', 'Yogyakarta', 'Medan', 'Makassar', 'Denpasar', 'Malang', 'Solo'];

$domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'icloud.com', 'hotmail.com'];

// Precomputed password hash untuk 'password123'
$defaultPassword = password_hash('password123', PASSWORD_BCRYPT, ['cost' => 10]);

$totalTarget = 100000;
$batchSize = 2500;

foreach ($dbNames as $dbName) {
    echo "========================================\n";
    echo "Memeriksa database: {$dbName}...\n";

    try {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname={$dbName}", 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
    } catch (Exception $e) {
        echo "Gagal konek ke {$dbName}: " . $e->getMessage() . "\n";
        continue;
    }

    $currentCount = (int)$pdo->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
    echo "Jumlah data saat ini di {$dbName}: {$currentCount}\n";

    $needed = $totalTarget - $currentCount;
    if ($needed <= 0) {
        echo "Data di {$dbName} sudah mencapai/melebihi {$totalTarget} baris. Lewati.\n";
        continue;
    }

    echo "Menambahkan {$needed} data ke {$dbName}...\n";
    $startTime = microtime(true);

    $pdo->beginTransaction();

    $batch = [];
    $totalInserted = 0;

    for ($i = 1; $i <= $needed; $i++) {
        $idx = $currentCount + $i;
        $fn = $firstNames[array_rand($firstNames)];
        $ln = $lastNames[array_rand($lastNames)];
        $nama = $fn . ' ' . $ln;
        $email = 'user_' . $idx . '_' . substr(md5(uniqid(mt_rand(), true)), 0, 5) . '@' . $domains[$idx % count($domains)];
        $telepon = '08' . rand(11, 99) . rand(1000000, 9999999);
        $alamat = $streets[array_rand($streets)] . ' No. ' . rand(1, 150) . ', ' . $cities[array_rand($cities)];
        $tanggal = date('Y-m-d H:i:s', time() - rand(0, 31536000));

        $batch[] = [
            'nama' => $nama,
            'email' => $email,
            'password' => $defaultPassword,
            'no_telepon' => $telepon,
            'alamat' => $alamat,
            'tanggal_daftar' => $tanggal,
        ];

        if (count($batch) >= $batchSize) {
            insertBatch($pdo, $batch);
            $totalInserted += count($batch);
            $batch = [];
            $persen = round(($totalInserted / $needed) * 100);
            echo "Progress: {$totalInserted} / {$needed} ({$persen}%)\n";
        }
    }

    if (!empty($batch)) {
        insertBatch($pdo, $batch);
        $totalInserted += count($batch);
    }

    $pdo->commit();

    $duration = round(microtime(true) - $startTime, 2);
    $finalCount = (int)$pdo->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();
    echo "\nSelesai untuk {$dbName} dalam {$duration} detik! Total data sekarang: {$finalCount}\n";
}

function insertBatch(PDO $pdo, array $rows)
{
    if (empty($rows)) return;

    $placeholders = [];
    $values = [];

    foreach ($rows as $row) {
        $placeholders[] = '(?, ?, ?, ?, ?, ?)';
        $values[] = $row['nama'];
        $values[] = $row['email'];
        $values[] = $row['password'];
        $values[] = $row['no_telepon'];
        $values[] = $row['alamat'];
        $values[] = $row['tanggal_daftar'];
    }

    $sql = "INSERT INTO pelanggan (nama, email, password, no_telepon, alamat, tanggal_daftar) VALUES " . implode(',', $placeholders);
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
}
