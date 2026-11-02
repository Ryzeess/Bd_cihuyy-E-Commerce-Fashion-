<?php

ini_set('memory_limit', '1024M');
set_time_limit(0);

$dbNames = ['cihuy_store', 'bd_cihuyy'];

$prefixes = [
    'PROMO', 'DISKON', 'HEMAT', 'CASHBACK', 'FLASH', 'FASHION', 'WEEKEND', 'PAYDAY',
    'SALE', 'SUPER', 'MEGA', 'EXTRA', 'BERKAH', 'BELANJA', 'CIHUY', 'TREND',
    'OUTFIT', 'SPECIAL', 'DEAL', 'VOUCHER', 'KUPON', 'HEMATBANYAK', 'SERU', 'KEREN'
];

$persenDiskon = [5, 10, 15, 20, 25, 30, 40, 50, 70];
$persenMinBelanja = [25000, 30000, 50000, 75000, 100000, 150000, 200000];

$nominalDiskon = [10000, 15000, 20000, 25000, 30000, 50000, 75000, 100000];
$nominalMinBelanja = [50000, 75000, 100000, 150000, 200000, 250000, 300000];

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

    // Ambil target jumlah voucher sebanyak jumlah warna
    $targetWarna = (int)$pdo->query("SELECT COUNT(*) FROM warna")->fetchColumn();
    $totalTarget = $targetWarna > 0 ? $targetWarna : 100000;
    $currentCount = (int)$pdo->query("SELECT COUNT(*) FROM voucher")->fetchColumn();

    echo "Jumlah data warna di {$dbName}: {$targetWarna}\n";
    echo "Jumlah data voucher saat ini di {$dbName}: {$currentCount}\n";
    echo "Target voucher (sebanyak warna): {$totalTarget}\n";

    $needed = $totalTarget - $currentCount;
    if ($needed <= 0) {
        echo "Data voucher di {$dbName} sudah mencapai/melebihi {$totalTarget} baris. Lewati.\n";
        continue;
    }

    echo "Menambahkan {$needed} data voucher ke {$dbName}...\n";
    $startTime = microtime(true);

    // Cek ketersediaan kolom created_at / updated_at
    $hasTimestamps = false;
    $cols = $pdo->query("SHOW COLUMNS FROM voucher LIKE 'created_at'")->fetchAll();
    if (!empty($cols)) {
        $hasTimestamps = true;
    }

    $pdo->beginTransaction();

    $batch = [];
    $totalInserted = 0;

    for ($i = 1; $i <= $needed; $i++) {
        $idx = $currentCount + $i;
        $prefix = $prefixes[$i % count($prefixes)];
        $kodeVoucher = sprintf("%s-%06d", $prefix, $idx);

        $isPersen = (mt_rand(1, 100) <= 50);
        if ($isPersen) {
            $tipe = 'persen';
            $nilai = $persenDiskon[array_rand($persenDiskon)];
            $minBelanja = $persenMinBelanja[array_rand($persenMinBelanja)];
        } else {
            $tipe = 'nominal';
            $nilai = $nominalDiskon[array_rand($nominalDiskon)];
            $minMin = $nominalMinBelanja[array_rand($nominalMinBelanja)];
            $minBelanja = max($minMin, $nilai);
        }

        // Variasi tanggal mulai & berakhir
        $daysAgo = mt_rand(1, 90);
        $daysAhead = mt_rand(30, 365);
        $tglMulai = date('Y-m-d H:i:s', strtotime("-{$daysAgo} days"));
        $tglBerakhir = date('Y-m-d H:i:s', strtotime("+{$daysAhead} days"));

        $status = (mt_rand(1, 100) <= 85) ? 'aktif' : 'nonaktif';

        $row = [
            'kode_voucher' => $kodeVoucher,
            'tipe_diskon' => $tipe,
            'nilai_diskon' => $nilai,
            'minimal_belanja' => $minBelanja,
            'tanggal_mulai' => $tglMulai,
            'tanggal_berakhir' => $tglBerakhir,
            'status' => $status,
        ];

        if ($hasTimestamps) {
            $now = date('Y-m-d H:i:s');
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        $batch[] = $row;

        if (count($batch) >= $batchSize) {
            insertVoucherBatch($pdo, $batch, $hasTimestamps);
            $totalInserted += count($batch);
            $batch = [];
            $persen = round(($totalInserted / $needed) * 100);
            echo "Progress: {$totalInserted} / {$needed} ({$persen}%)\n";
        }
    }

    if (!empty($batch)) {
        insertVoucherBatch($pdo, $batch, $hasTimestamps);
        $totalInserted += count($batch);
    }

    $pdo->commit();

    $duration = round(microtime(true) - $startTime, 2);
    $finalCount = (int)$pdo->query("SELECT COUNT(*) FROM voucher")->fetchColumn();
    echo "\nSelesai untuk {$dbName} dalam {$duration} detik! Total data voucher sekarang: {$finalCount}\n";
}

function insertVoucherBatch(PDO $pdo, array $rows, bool $hasTimestamps)
{
    if (empty($rows)) return;

    $placeholders = [];
    $values = [];

    $cols = ['kode_voucher', 'tipe_diskon', 'nilai_diskon', 'minimal_belanja', 'tanggal_mulai', 'tanggal_berakhir', 'status'];
    if ($hasTimestamps) {
        $cols[] = 'created_at';
        $cols[] = 'updated_at';
    }

    $rowPlaceholder = '(' . implode(',', array_fill(0, count($cols), '?')) . ')';

    foreach ($rows as $row) {
        $placeholders[] = $rowPlaceholder;
        foreach ($cols as $col) {
            $values[] = $row[$col];
        }
    }

    $sql = "INSERT INTO voucher (" . implode(',', $cols) . ") VALUES " . implode(',', $placeholders);
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);
}
