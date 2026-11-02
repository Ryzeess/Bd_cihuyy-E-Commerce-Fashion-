<?php

namespace Database\Factories;

use App\Models\VarianProduk;
use App\Models\Produk;
use App\Models\Warna;
use App\Models\Ukuran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VarianProduk>
 */
class VarianProdukFactory extends Factory
{
    protected $model = VarianProduk::class;

    public function definition(): array
    {
        // Mengambil ID secara acak dari database jika ada
        $randomProduk = Produk::inRandomOrder()->value('id_produk');
        $randomWarna  = Warna::inRandomOrder()->value('id_warna');
        $randomUkuran = Ukuran::inRandomOrder()->value('id_ukuran');

        return [
            'id_produk'   => $randomProduk ?: Produk::factory(),
            'id_warna'    => $randomWarna ?: Warna::factory(),
            'id_ukuran'   => $randomUkuran ?: Ukuran::factory(),
            'kode_barang' => 'SKU-' . strtoupper(fake()->unique()->bothify('???-####-???')),
            'stok'        => fake()->numberBetween(5, 80),
        ];
    }
}