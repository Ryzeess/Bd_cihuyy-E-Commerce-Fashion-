<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\Warna;
use App\Models\Ukuran;
use App\Models\Produk;
use App\Models\VarianProduk;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kategori
        $kategoriList = [
            ['id_kategori' => 1, 'nama_kategori' => 'Kaos', 'deskripsi' => 'Koleksi t-shirt casual santai harian'],
            ['id_kategori' => 2, 'nama_kategori' => 'Kemeja', 'deskripsi' => 'Kemeja formal dan kasual lengan panjang/pendek'],
            ['id_kategori' => 3, 'nama_kategori' => 'Celana', 'deskripsi' => 'Celana chinos, jeans, dan formal'],
            ['id_kategori' => 4, 'nama_kategori' => 'Jaket', 'deskripsi' => 'Outerwear hangat dan windbreaker stylish'],
            ['id_kategori' => 5, 'nama_kategori' => 'Dress', 'deskripsi' => 'Gaun wanita anggun untuk berbagai acara'],
        ];
        foreach ($kategoriList as $k) {
            Kategori::updateOrCreate(['id_kategori' => $k['id_kategori']], $k);
        }

        // 2. Warna
        $warnaList = [
            ['id_warna' => 1, 'nama_warna' => 'Hitam', 'kode_warna' => '#000000'],
            ['id_warna' => 2, 'nama_warna' => 'Putih', 'kode_warna' => '#FFFFFF'],
            ['id_warna' => 3, 'nama_warna' => 'Navy', 'kode_warna' => '#000080'],
            ['id_warna' => 4, 'nama_warna' => 'Merah', 'kode_warna' => '#FF0000'],
            ['id_warna' => 5, 'nama_warna' => 'Cream', 'kode_warna' => '#FFFDD0'],
        ];
        foreach ($warnaList as $w) {
            Warna::updateOrCreate(['id_warna' => $w['id_warna']], $w);
        }

        // 3. Ukuran
        $ukuranList = [
            ['id_ukuran' => 1, 'nama_ukuran' => 'S', 'keterangan' => 'Small - Lebar Dada 48 cm, Panjang 68 cm'],
            ['id_ukuran' => 2, 'nama_ukuran' => 'M', 'keterangan' => 'Medium - Lebar Dada 50 cm, Panjang 70 cm'],
            ['id_ukuran' => 3, 'nama_ukuran' => 'L', 'keterangan' => 'Large - Lebar Dada 52 cm, Panjang 72 cm'],
            ['id_ukuran' => 4, 'nama_ukuran' => 'XL', 'keterangan' => 'Extra Large - Lebar Dada 54 cm, Panjang 74 cm'],
            ['id_ukuran' => 5, 'nama_ukuran' => 'XXL', 'keterangan' => 'Double XL - Lebar Dada 56 cm, Panjang 76 cm'],
        ];
        foreach ($ukuranList as $u) {
            Ukuran::updateOrCreate(['id_ukuran' => $u['id_ukuran']], $u);
        }

        // 4. Produk
        $produkList = [
            [
                'id_produk' => 1,
                'id_kategori' => 1,
                'nama_produk' => 'Kaos Polos Combed 30s',
                'deskripsi' => 'Kaos bahan sejuk nyaman menyerap keringat',
                'harga' => 75000.00,
                'bahan' => 'Cotton Combed 30s',
                'gaya' => 'Casual',
                'berat' => 200,
            ],
            [
                'id_produk' => 2,
                'id_kategori' => 2,
                'nama_produk' => 'Kemeja Oxford Slim Fit',
                'deskripsi' => 'Kemeja formal modern cocok untuk kerja maupun hangout',
                'harga' => 185000.00,
                'bahan' => 'Katun Oxford',
                'gaya' => 'Formal/Smart Casual',
                'berat' => 300,
            ],
            [
                'id_produk' => 3,
                'id_kategori' => 3,
                'nama_produk' => 'Celana Chino Panjang Reguler',
                'deskripsi' => 'Celana chino stretch elastis fleksibel',
                'harga' => 195000.00,
                'bahan' => 'Twill Stretch',
                'gaya' => 'Casual Modern',
                'berat' => 450,
            ],
            [
                'id_produk' => 4,
                'id_kategori' => 4,
                'nama_produk' => 'Jaket Bomber Harrington',
                'deskripsi' => 'Jaket tahan angin dengan lapisan furing lembut',
                'harga' => 275000.00,
                'bahan' => 'Taslan Despo',
                'gaya' => 'Streetwear',
                'berat' => 500,
            ],
        ];
        foreach ($produkList as $p) {
            Produk::updateOrCreate(['id_produk' => $p['id_produk']], $p);
        }

        // 5. Varian Produk
        $varianList = [
            ['id_varian' => 1, 'id_produk' => 1, 'id_warna' => 1, 'id_ukuran' => 2, 'kode_barang' => 'TSHIRT-BLK-M', 'stok' => 50],
            ['id_varian' => 2, 'id_produk' => 1, 'id_warna' => 1, 'id_ukuran' => 3, 'kode_barang' => 'TSHIRT-BLK-L', 'stok' => 40],
            ['id_varian' => 3, 'id_produk' => 1, 'id_warna' => 2, 'id_ukuran' => 2, 'kode_barang' => 'TSHIRT-WHT-M', 'stok' => 35],
            ['id_varian' => 4, 'id_produk' => 1, 'id_warna' => 3, 'id_ukuran' => 3, 'kode_barang' => 'TSHIRT-NVY-L', 'stok' => 25],
            ['id_varian' => 5, 'id_produk' => 2, 'id_warna' => 2, 'id_ukuran' => 3, 'kode_barang' => 'SHIRT-WHT-L', 'stok' => 20],
            ['id_varian' => 6, 'id_produk' => 2, 'id_warna' => 3, 'id_ukuran' => 4, 'kode_barang' => 'SHIRT-NVY-XL', 'stok' => 15],
            ['id_varian' => 7, 'id_produk' => 3, 'id_warna' => 5, 'id_ukuran' => 3, 'kode_barang' => 'CHINO-CRM-L', 'stok' => 30],
            ['id_varian' => 8, 'id_produk' => 4, 'id_warna' => 1, 'id_ukuran' => 3, 'kode_barang' => 'BOMBER-BLK-L', 'stok' => 18],
        ];
        foreach ($varianList as $v) {
            VarianProduk::updateOrCreate(['id_varian' => $v['id_varian']], $v);
        }
    }
}
