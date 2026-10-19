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
        return [
            'id_produk' => Produk::inRandomOrder()->first()?->id_produk ?? Produk::factory(),
            'id_warna' => Warna::inRandomOrder()->first()?->id_warna ?? Warna::factory(),
            'id_ukuran' => Ukuran::inRandomOrder()->first()?->id_ukuran ?? Ukuran::factory(),
            'kode_barang' => 'SKU-' . strtoupper(fake()->unique()->bothify('???-####-???')),
            'stok' => fake()->numberBetween(5, 80),
        ];
    }
}
