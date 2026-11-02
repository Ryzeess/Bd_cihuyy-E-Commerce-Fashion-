<?php
$databases = ['bd_cihuyy', 'cihuy_store'];

foreach ($databases as $db) {
    try {
        $pdo = new PDO("mysql:host=127.0.0.1;dbname={$db}", 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $countWarna = (int)$pdo->query("SELECT COUNT(*) FROM warna")->fetchColumn();
        $countVoucher = (int)$pdo->query("SELECT COUNT(*) FROM voucher")->fetchColumn();
        $countPelanggan = (int)$pdo->query("SELECT COUNT(*) FROM pelanggan")->fetchColumn();

        $sampleVoucher = $pdo->query("SELECT id_voucher, kode_voucher, tipe_diskon, nilai_diskon, minimal_belanja, status FROM voucher ORDER BY id_voucher DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);

        echo "========================================\n";
        echo "Database: {$db}\n";
        echo "  - Total Warna     : " . number_format($countWarna, 0, ',', '.') . "\n";
        echo "  - Total Voucher   : " . number_format($countVoucher, 0, ',', '.') . "\n";
        echo "  - Total Pelanggan : " . number_format($countPelanggan, 0, ',', '.') . "\n";
        echo "  - Voucher == Warna? " . ($countVoucher === $countWarna ? "YA (SEIMBANG)" : "TIDAK") . "\n";
        echo "Sample 3 Voucher Terakhir:\n";
        foreach ($sampleVoucher as $sv) {
            echo "    [#{$sv['id_voucher']}] {$sv['kode_voucher']} ({$sv['tipe_diskon']} - {$sv['nilai_diskon']}, min: {$sv['minimal_belanja']}, status: {$sv['status']})\n";
        }
    } catch (Exception $e) {
        echo "DB [{$db}] Gagal: " . $e->getMessage() . "\n";
    }
}
