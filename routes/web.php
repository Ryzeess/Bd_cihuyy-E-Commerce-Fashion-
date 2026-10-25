<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    // Jalankan schema setup jika tabel pelanggan belum ada di database
    if (!Schema::hasTable('pelanggan')) {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
    }

    $pelangganCount = DB::table('pelanggan')->count();
    $produkCount = DB::table('produk')->count();
    $varianCount = DB::table('varian_produk')->count();
    $pesananCount = DB::table('pesanan')->count();
    $kategoriCount = DB::table('kategori')->count();
    $kurirCount = DB::table('kurir')->count();

    // Query Alur Pesanan Lengkap
    $recentOrders = DB::table('pesanan as p')
        ->join('pelanggan as c', 'p.id_pelanggan', '=', 'c.id_pelanggan')
        ->leftJoin('voucher as v', 'p.id_voucher', '=', 'v.id_voucher')
        ->leftJoin('pembayaran as byr', 'p.id_pesanan', '=', 'byr.id_pesanan')
        ->leftJoin('pengiriman as kirim', 'p.id_pesanan', '=', 'kirim.id_pesanan')
        ->leftJoin('kurir as k', 'kirim.id_kurir', '=', 'k.id_kurir')
        ->select(
            'p.nomor_pesanan',
            'c.nama as customer_name',
            'c.email as customer_email',
            'c.no_telepon',
            'v.kode_voucher',
            'p.total_harga',
            'p.potongan_diskon',
            'p.total_bayar',
            'p.status_pesanan',
            'p.alamat_pengiriman',
            'byr.metode_pembayaran',
            'byr.status_pembayaran',
            'k.nama_kurir',
            'k.layanan as layanan_kurir',
            'kirim.nomor_resi',
            'kirim.status_pengiriman',
            'p.tanggal_pesanan'
        )
        ->get();

    // Query Rincian Item Pesanan
    $orderItems = DB::table('detail_pesanan as dp')
        ->join('pesanan as p', 'dp.id_pesanan', '=', 'p.id_pesanan')
        ->join('varian_produk as vp', 'dp.id_varian', '=', 'vp.id_varian')
        ->join('produk as pr', 'vp.id_produk', '=', 'pr.id_produk')
        ->join('kategori as kat', 'pr.id_kategori', '=', 'kat.id_kategori')
        ->join('warna as w', 'vp.id_warna', '=', 'w.id_warna')
        ->join('ukuran as u', 'vp.id_ukuran', '=', 'u.id_ukuran')
        ->select(
            'p.nomor_pesanan',
            'kat.nama_kategori as category_name',
            'pr.nama_produk as product_name',
            'w.nama_warna as color_name',
            'u.nama_ukuran as size_name',
            'vp.kode_barang as sku',
            'dp.jumlah as quantity',
            'dp.harga_satuan as unit_price',
            'dp.subtotal'
        )
        ->get();

    $dbmlContent = file_exists(database_path('schema.dbml')) ? file_get_contents(database_path('schema.dbml')) : '';
    $sqlContent = file_exists(database_path('schema.sql')) ? file_get_contents(database_path('schema.sql')) : '';
    $svgContent = file_exists(database_path('dbdiagram.svg')) ? file_get_contents(database_path('dbdiagram.svg')) : '';

    return view('welcome', compact(
        'pelangganCount',
        'produkCount',
        'varianCount',
        'pesananCount',
        'kategoriCount',
        'kurirCount',
        'recentOrders',
        'orderItems',
        'dbmlContent',
        'sqlContent',
        'svgContent'
    ));
});

// Endpoint untuk melihat raw SVG fullscreen di tab baru
Route::get('/erd-svg', function () {
    $path = database_path('dbdiagram.svg');
    if (!file_exists($path)) {
        abort(404);
    }
    return response(file_get_contents($path), 200, [
        'Content-Type' => 'image/svg+xml',
        'Cache-Control' => 'no-cache, private'
    ]);
});
