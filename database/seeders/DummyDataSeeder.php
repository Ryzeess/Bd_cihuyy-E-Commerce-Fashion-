<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan;
use App\Models\Kategori;
use App\Models\Warna;
use App\Models\Ukuran;
use App\Models\Produk;
use App\Models\VarianProduk;
use App\Models\Voucher;
use App\Models\Kurir;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;

class DummyDataSeeder extends Seeder
{
    /**
     * Konfigurasi jumlah data otomatis.
     */
    public int $jumlahPelanggan = 100000;
    public int $jumlahProduk = 50;
    public int $varianPerProdukMin = 3;
    public int $varianPerProdukMax = 5;
    public int $jumlahVoucher = 20;
    public int $jumlahPesanan = 100;

    public function run(): void
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        DB::disableQueryLog();

        $this->command?->info("🚀 Memulai proses seeding data otomatis skala besar...");

        // 1. ENTITAS MASTER: Kategori, Warna, Ukuran, Kurir
        $this->command?->info("📦 [1/6] Menyiapkan Master Data (Kategori, Warna, Ukuran, Kurir)...");

        $kategoriCount = Kategori::count();
        if ($kategoriCount < 15) {
            Kategori::factory()->count(15 - $kategoriCount)->create();
        }
        $kategoris = Kategori::all();

        $warnaCount = Warna::count();
        if ($warnaCount < 15) {
            Warna::factory()->count(15 - $warnaCount)->create();
        }
        $warnas = Warna::all();

        $ukuranCount = Ukuran::count();
        if ($ukuranCount < 13) {
            Ukuran::factory()->count(13 - $ukuranCount)->create();
        }
        $ukurans = Ukuran::all();

        $kurirCount = Kurir::count();
        if ($kurirCount < 12) {
            Kurir::factory()->count(12 - $kurirCount)->create();
        }
        $kurirs = Kurir::all();

        // 2. ENTITAS VOUCHER
        $this->command?->info("🎟️ [2/6] Menyiapkan Data Voucher Promo...");
        $voucherCount = Voucher::count();
        if ($voucherCount < $this->jumlahVoucher) {
            Voucher::factory()->count($this->jumlahVoucher - $voucherCount)->create();
        }
        $vouchers = Voucher::where('status', 'aktif')->get();

        // 3. ENTITAS PELANGGAN (Batch Insert Super Cepat: 100.000 data dalam hitungan detik)
        $existingPelanggan = Pelanggan::count();
        $targetPelanggan = $this->jumlahPelanggan;
        $needed = max(0, $targetPelanggan - $existingPelanggan);

        $this->command?->info("👥 [3/6] Memproses Data Pelanggan (Target: {$targetPelanggan}, Saat ini: {$existingPelanggan}, Menambahkan: {$needed})...");

        if ($needed > 0) {
            $hashedPassword = Hash::make('password123');
            $batchPelanggan = [];
            $batchSize = 2500;
            $now = now();
            $domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'icloud.com', 'hotmail.com'];

            for ($i = 1; $i <= $needed; $i++) {
                $uniqueSequence = $existingPelanggan + $i;
                $email = 'user' . $uniqueSequence . '_' . Str::lower(Str::random(4)) . '@' . $domains[$uniqueSequence % 5];

                $batchPelanggan[] = [
                    'nama' => fake()->name(),
                    'email' => $email,
                    'password' => $hashedPassword,
                    'no_telepon' => '08' . rand(11, 99) . rand(1000000, 9999999),
                    'alamat' => fake()->address(),
                    'tanggal_daftar' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($batchPelanggan) >= $batchSize) {
                    Pelanggan::insert($batchPelanggan);
                    $batchPelanggan = [];
                    $totalNow = $existingPelanggan + $i;
                    $persen = round(($totalNow / $targetPelanggan) * 100);
                    $this->command?->info("   -> Pelanggan tersimpan: {$totalNow} / {$targetPelanggan} ({$persen}%)");
                }
            }

            if (!empty($batchPelanggan)) {
                Pelanggan::insert($batchPelanggan);
                $this->command?->info("   -> Pelanggan tersimpan: {$targetPelanggan} / {$targetPelanggan} (100%)");
            }
        }

        // 4. ENTITAS PRODUK & VARIAN PRODUK
        $existingProduk = Produk::count();
        $targetProduk = $this->jumlahProduk;
        $neededProduk = max(0, $targetProduk - $existingProduk);
        $this->command?->info("👕 [4/6] Memproses Data Produk ({$this->jumlahProduk} produk) & Varian SKU...");

        if ($neededProduk > 0) {
            Produk::factory()->count($neededProduk)->create();
        }

        // Pastikan setiap produk memiliki varian produk
        $allProduks = Produk::all();
        $warnaIds = $warnas->pluck('id_warna')->toArray();
        $ukuranIds = $ukurans->pluck('id_ukuran')->toArray();
        $now = now();
        $batchVarian = [];

        foreach ($allProduks as $pr) {
            if (VarianProduk::where('id_produk', $pr->id_produk)->exists()) {
                continue;
            }

            $countVar = rand($this->varianPerProdukMin, $this->varianPerProdukMax);
            shuffle($warnaIds);
            shuffle($ukuranIds);

            for ($v = 0; $v < $countVar; $v++) {
                $wId = $warnaIds[$v % count($warnaIds)];
                $uId = $ukuranIds[$v % count($ukuranIds)];
                $sku = 'SKU-' . $pr->id_produk . '-' . $wId . '-' . $uId . '-' . strtoupper(Str::random(3));

                $batchVarian[] = [
                    'id_produk' => $pr->id_produk,
                    'id_warna' => $wId,
                    'id_ukuran' => $uId,
                    'kode_barang' => $sku,
                    'stok' => rand(15, 100),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($batchVarian) >= 1000) {
                    VarianProduk::insert($batchVarian);
                    $batchVarian = [];
                }
            }
        }
        if (!empty($batchVarian)) {
            VarianProduk::insert($batchVarian);
        }

        // 5. ENTITAS TRANSAKSI: Pesanan, Detail, Pembayaran, Pengiriman
        $existingPesanan = Pesanan::count();
        $neededPesanan = max(0, $this->jumlahPesanan - $existingPesanan);
        $this->command?->info("🛒 [5/6] Memproses Transaksi Pesanan (Target: {$this->jumlahPesanan}, Baru: {$neededPesanan})...");

        if ($neededPesanan > 0) {
            $samplePelanggans = Pelanggan::inRandomOrder()->limit(min(100, Pelanggan::count()))->get();
            $sampleVarians = VarianProduk::with('produk')->inRandomOrder()->limit(200)->get();
            $statusList = ['menunggu_pembayaran', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];

            for ($ord = 1; $ord <= $neededPesanan; $ord++) {
                $pel = $samplePelanggans->random();
                $tgl = fake()->dateTimeBetween('-4 months', 'now');
                $status = $statusList[array_rand($statusList)];
                $noPesanan = 'ORD-' . $tgl->format('Ym') . '-' . strtoupper(Str::random(6));

                $pesanan = Pesanan::create([
                    'nomor_pesanan' => $noPesanan,
                    'id_pelanggan' => $pel->id_pelanggan,
                    'id_voucher' => null,
                    'tanggal_pesanan' => $tgl,
                    'total_harga' => 0.00,
                    'potongan_diskon' => 0.00,
                    'total_bayar' => 0.00,
                    'status_pesanan' => $status,
                    'alamat_pengiriman' => $pel->alamat ?? 'Jl. Merdeka No. 45',
                ]);

                $items = rand(1, 4);
                $totalHarga = 0.00;
                $pickedVarians = $sampleVarians->random(min($items, $sampleVarians->count()));

                foreach ($pickedVarians as $vItem) {
                    $hargaSatuan = $vItem->produk ? (float) $vItem->produk->harga : 75000.00;
                    $qty = rand(1, 3);
                    $subtotal = $hargaSatuan * $qty;
                    $totalHarga += $subtotal;

                    DetailPesanan::create([
                        'id_pesanan' => $pesanan->id_pesanan,
                        'id_varian' => $vItem->id_varian,
                        'jumlah' => $qty,
                        'harga_satuan' => $hargaSatuan,
                        'subtotal' => $subtotal,
                    ]);
                }

                $diskon = 0.00;
                $voucherUsed = null;
                if ($vouchers->isNotEmpty() && rand(1, 100) <= 35) {
                    $cand = $vouchers->filter(fn ($v) => $totalHarga >= (float) $v->minimal_belanja)->first();
                    if ($cand) {
                        $voucherUsed = $cand;
                        $diskon = ($cand->tipe_diskon === 'persen')
                            ? round(($totalHarga * (float) $cand->nilai_diskon) / 100, 2)
                            : min((float) $cand->nilai_diskon, $totalHarga);
                    }
                }

                $totalBayar = max(0.00, $totalHarga - $diskon);
                $pesanan->update([
                    'id_voucher' => $voucherUsed?->id_voucher,
                    'total_harga' => $totalHarga,
                    'potongan_diskon' => $diskon,
                    'total_bayar' => $totalBayar,
                ]);

                // Pembayaran (1:1)
                $statusBayar = match ($status) {
                    'selesai', 'dikirim' => 'lunas',
                    'diproses' => rand(1, 2) === 1 ? 'lunas' : 'menunggu',
                    'menunggu_pembayaran' => 'menunggu',
                    default => 'gagal',
                };

                Pembayaran::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'metode_pembayaran' => fake()->randomElement(['transfer_bank', 'qris', 'e_wallet', 'kartu_kredit', 'cod']),
                    'jumlah_bayar' => $totalBayar,
                    'waktu_bayar' => ($statusBayar === 'lunas') ? $tgl : null,
                    'status_pembayaran' => $statusBayar,
                    'bukti_pembayaran' => ($statusBayar === 'lunas') ? 'uploads/bukti_' . Str::random(8) . '.jpg' : null,
                ]);

                // Pengiriman (1:1)
                if (in_array($status, ['diproses', 'dikirim', 'selesai'])) {
                    $kurir = $kurirs->random();
                    $statusKirim = match ($status) {
                        'selesai' => 'terkirim',
                        'dikirim' => 'dalam_perjalanan',
                        default => 'diproses',
                    };
                    $kodeKurir = explode('-', $kurir->kode_kurir)[0];

                    Pengiriman::create([
                        'id_pesanan' => $pesanan->id_pesanan,
                        'id_kurir' => $kurir->id_kurir,
                        'nomor_resi' => $kodeKurir . '-' . rand(1000000000, 9999999999),
                        'status_pengiriman' => $statusKirim,
                        'biaya_ongkir' => $kurir->tarif_per_kg,
                        'tanggal_kirim' => in_array($statusKirim, ['dalam_perjalanan', 'terkirim']) ? $tgl : null,
                        'tanggal_diterima' => ($statusKirim === 'terkirim') ? (clone $tgl)->modify('+2 days') : null,
                    ]);
                }
            }
        }

        $this->command?->info("🎉 [6/6] Selesai! Berhasil mencapai 100.000 pelanggan dan seluruh data transaksi lengkap.");
    }
}