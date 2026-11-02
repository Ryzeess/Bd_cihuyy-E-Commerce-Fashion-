<?php

ini_set('memory_limit', '1024M');
set_time_limit(0);

$dbNames = ['cihuy_store', 'bd_cihuyy'];

$modifiers = [
    'Deep', 'Light', 'Dark', 'Soft', 'Pastel', 'Bright', 'Vibrant', 'Matte', 'Glossy', 'Neon',
    'Electric', 'Muted', 'Vintage', 'Dusty', 'Midnight', 'Warm', 'Cool', 'Smoky', 'Pale', 'Royal',
    'Classic', 'Metallic', 'Golden', 'Silver', 'Ocean', 'Forest', 'Sunset', 'Autumn', 'Winter', 'Spring',
    'Muda', 'Tua', 'Terang', 'Pucat', 'Gelap', 'Cerah', 'Lembut', 'Pekat', 'Mengkilap', 'Alami'
];

$baseColors = [
    'Red', 'Blue', 'Green', 'Yellow', 'Black', 'White', 'Gray', 'Purple', 'Pink', 'Orange',
    'Brown', 'Teal', 'Cyan', 'Magenta', 'Maroon', 'Navy', 'Olive', 'Coral', 'Amber', 'Turquoise',
    'Violet', 'Indigo', 'Emerald', 'Ruby', 'Sapphire', 'Beige', 'Khaki', 'Lavender', 'Burgundy', 'Charcoal',
    'Merah', 'Biru', 'Hijau', 'Kuning', 'Abu-Abu', 'Cokelat', 'Ungu', 'Oranye', 'Toska', 'Nila'
];

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

    $currentCount = (int)$pdo->query("SELECT COUNT(*) FROM warna")->fetchColumn();
    echo "Jumlah data saat ini di {$dbName}: {$currentCount}\n";

    $needed = $totalTarget - $currentCount;
    if ($needed <= 0) {
        echo "Data di {$dbName} sudah mencapai/melebihi {$totalTarget} baris. Lewati.\n";
        continue;
    }

    echo "Menambahkan {$needed} data warna ke {$dbName}...\n";
    $startTime = microtime(true);

    $pdo->beginTransaction();

    $batch = [];
    $totalInserted = 0;

    for ($i = 1; $i <= $needed; $i++) {
        $idx = $currentCount + $i;
        $mod = $modifiers[array_rand($modifiers)];
        $base = $baseColors[array_rand($baseColors)];
        
        // Nama warna rapi, tetap di bawah batas varchar(50)
        $namaWarna = "{$mod} {$base} {$idx}";
        if (strlen($namaWarna) > 50) {
            $namaWarna = substr($namaWarna, 0, 50);
        }

        // Kode HEX acak yang valid (#RRGGBB)
        $hex = sprintf("#%06X", mt_rand(0, 0xFFFFFF));

        $batch[] = [
            'nama_warna' => $namaWarna,
            'kode_warna' => $hex,
        ];

        if (count($batch) >= $batchSize) {
            insertWarnaBatch($pdo, $batch);
            $totalInserted += count($batch);
            $batch = [];
            $persen = round(($totalInserted / $needed) * 100);
            echo "Progress: {$totalInserted} / {$needed} ({$persen}%)\n";
        }
    }

    if (!empty($batch)) {
        insertWarnaBatch($pdo, $batch);
        $totalInserted += count($batch);
    }

    $pdo->commit();

    $duration = round(microtime(true) - $startTime, 2);
    $finalCount = (int)$pdo->query("SELECT COUNT(*) FROM warna")->fetchColumn();
    echo "\nSelesai untuk {$dbName} dalam {$duration} detik! Total data warna sekarang: {$finalCount}\n";
}

function insertWarnaBatch(PDO $pdo, array $rows)
{
    if (empty($rows)) return;

    $placeholders = [];
    $values = [];

    foreach ($rows as $row) {
        $placeholders[] = '(?, ?)';
        $values[] = $row['nama_warna'];
        $values[] = $row['kode_warna'];
    }

    $sql = "INSERT INTO warna (nama_warna, kode_warna) VALUES " . implode(',', $placeholders);
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
}
