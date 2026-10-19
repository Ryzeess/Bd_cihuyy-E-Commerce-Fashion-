<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use App\Models\Kurir;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Pengiriman;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Voucher
        $voucherList = [
            ['id_voucher' => 1, 'kode_voucher' => 'DISKONHEBAT20', 'tipe_diskon' => 'nominal', 'nilai_diskon' => 20000.00, 'minimal_belanja' => 150000.00, 'tanggal_mulai' => '2026-09-01 00:00:00', 'tanggal_berakhir' => '2026-12-31 23:59:59', 'status' => 'aktif'],
            ['id_voucher' => 2, 'kode_voucher' => 'GRATISONGKIR', 'tipe_diskon' => 'nominal', 'nilai_diskon' => 15000.00, 'minimal_belanja' => 100000.00, 'tanggal_mulai' => '2026-09-01 00:00:00', 'tanggal_berakhir' => '2026-12-31 23:59:59', 'status' => 'aktif'],
            ['id_voucher' => 3, 'kode_voucher' => 'NEWCUSTOMER10', 'tipe_diskon' => 'persen', 'nilai_diskon' => 10.00, 'minimal_belanja' => 50000.00, 'tanggal_mulai' => '2026-09-01 00:00:00', 'tanggal_berakhir' => '2026-12-31 23:59:59', 'status' => 'aktif'],
        ];
        foreach ($voucherList as $v) {
            Voucher::updateOrCreate(['id_voucher' => $v['id_voucher']], $v);
        }

        // 2. Kurir
        $kurirList = [
            ['id_kurir' => 1, 'nama_kurir' => 'JNE Express', 'kode_kurir' => 'JNE', 'layanan' => 'Reguler (2-3 Hari)', 'tarif_per_kg' => 12000.00],
            ['id_kurir' => 2, 'nama_kurir' => 'J&T Express', 'kode_kurir' => 'JNT', 'layanan' => 'EZ Express (1-2 Hari)', 'tarif_per_kg' => 14000.00],
            ['id_kurir' => 3, 'nama_kurir' => 'SiCepat Ekspres', 'kode_kurir' => 'SICEPAT', 'layanan' => 'BEST (Besok Sampai)', 'tarif_per_kg' => 18000.00],
        ];
        foreach ($kurirList as $k) {
            Kurir::updateOrCreate(['id_kurir' => $k['id_kurir']], $k);
        }

        // 3. Pesanan
        $pesananList = [
            [
                'id_pesanan' => 1,
                'nomor_pesanan' => 'ORD-202610-001',
                'id_pelanggan' => 1,
                'id_voucher' => 1,
                'tanggal_pesanan' => '2026-10-01 09:30:00',
                'total_harga' => 260000.00,
                'potongan_diskon' => 20000.00,
                'total_bayar' => 240000.00,
                'status_pesanan' => 'dikirim',
                'alamat_pengiriman' => 'Jl. Merdeka No. 45, Bandung',
            ],
            [
                'id_pesanan' => 2,
                'nomor_pesanan' => 'ORD-202610-002',
                'id_pelanggan' => 2,
                'id_voucher' => null,
                'tanggal_pesanan' => '2026-10-02 14:20:00',
                'total_harga' => 185000.00,
                'potongan_diskon' => 0.00,
                'total_bayar' => 185000.00,
                'status_pesanan' => 'selesai',
                'alamat_pengiriman' => 'Jl. Malioboro No. 12, Yogyakarta',
            ],
            [
                'id_pesanan' => 3,
                'nomor_pesanan' => 'ORD-202610-003',
                'id_pelanggan' => 3,
                'id_voucher' => 2,
                'tanggal_pesanan' => '2026-10-03 16:45:00',
                'total_harga' => 470000.00,
                'potongan_diskon' => 15000.00,
                'total_bayar' => 455000.00,
                'status_pesanan' => 'diproses',
                'alamat_pengiriman' => 'Jl. Sudirman No. 88, Jakarta Selatan',
            ],
        ];
        foreach ($pesananList as $ord) {
            Pesanan::updateOrCreate(['id_pesanan' => $ord['id_pesanan']], $ord);
        }

        // 4. Detail Pesanan
        $detailList = [
            ['id_detail' => 1, 'id_pesanan' => 1, 'id_varian' => 1, 'jumlah' => 1, 'harga_satuan' => 75000.00, 'subtotal' => 75000.00],
            ['id_detail' => 2, 'id_pesanan' => 1, 'id_varian' => 5, 'jumlah' => 1, 'harga_satuan' => 185000.00, 'subtotal' => 185000.00],
            ['id_detail' => 3, 'id_pesanan' => 2, 'id_varian' => 5, 'jumlah' => 1, 'harga_satuan' => 185000.00, 'subtotal' => 185000.00],
            ['id_detail' => 4, 'id_pesanan' => 3, 'id_varian' => 7, 'jumlah' => 1, 'harga_satuan' => 195000.00, 'subtotal' => 195000.00],
            ['id_detail' => 5, 'id_pesanan' => 3, 'id_varian' => 8, 'jumlah' => 1, 'harga_satuan' => 275000.00, 'subtotal' => 275000.00],
        ];
        foreach ($detailList as $d) {
            DetailPesanan::updateOrCreate(['id_detail' => $d['id_detail']], $d);
        }

        // 5. Pembayaran
        $bayarList = [
            ['id_pembayaran' => 1, 'id_pesanan' => 1, 'metode_pembayaran' => 'qris', 'jumlah_bayar' => 240000.00, 'waktu_bayar' => '2026-10-01 09:35:00', 'status_pembayaran' => 'lunas', 'bukti_pembayaran' => 'uploads/bukti_ord1.jpg'],
            ['id_pembayaran' => 2, 'id_pesanan' => 2, 'metode_pembayaran' => 'transfer_bank', 'jumlah_bayar' => 185000.00, 'waktu_bayar' => '2026-10-02 14:25:00', 'status_pembayaran' => 'lunas', 'bukti_pembayaran' => 'uploads/bukti_ord2.jpg'],
            ['id_pembayaran' => 3, 'id_pesanan' => 3, 'metode_pembayaran' => 'e_wallet', 'jumlah_bayar' => 455000.00, 'waktu_bayar' => '2026-10-03 16:50:00', 'status_pembayaran' => 'lunas', 'bukti_pembayaran' => 'uploads/bukti_ord3.jpg'],
        ];
        foreach ($bayarList as $b) {
            Pembayaran::updateOrCreate(['id_pembayaran' => $b['id_pembayaran']], $b);
        }

        // 6. Pengiriman
        $kirimList = [
            ['id_pengiriman' => 1, 'id_pesanan' => 1, 'id_kurir' => 2, 'nomor_resi' => 'JNT-9823471029', 'status_pengiriman' => 'dalam_perjalanan', 'biaya_ongkir' => 14000.00, 'tanggal_kirim' => '2026-10-01 13:00:00', 'tanggal_diterima' => null],
            ['id_pengiriman' => 2, 'id_pesanan' => 2, 'id_kurir' => 1, 'nomor_resi' => 'JNE-4512984012', 'status_pengiriman' => 'terkirim', 'biaya_ongkir' => 12000.00, 'tanggal_kirim' => '2026-10-02 16:00:00', 'tanggal_diterima' => '2026-10-03 11:00:00'],
            ['id_pengiriman' => 3, 'id_pesanan' => 3, 'id_kurir' => 3, 'nomor_resi' => 'SCP-7781290312', 'status_pengiriman' => 'diproses', 'biaya_ongkir' => 18000.00, 'tanggal_kirim' => null, 'tanggal_diterima' => null],
        ];
        foreach ($kirimList as $k) {
            Pengiriman::updateOrCreate(['id_pengiriman' => $k['id_pengiriman']], $k);
        }
    }
}
