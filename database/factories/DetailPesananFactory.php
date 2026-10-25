<?php

namespace Database\Factories;

use App\Models\DetailPesanan;
use App\Models\Pesanan;
use App\Models\VarianProduk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailPesanan>
 */
class DetailPesananFactory extends Factory
{
    protected $model = DetailPesanan::class;

    public function definition(): array
    {
        $varian = VarianProduk::with('produk')->inRandomOrder()->first();
        $hargaSatuan = $varian?->produk?->harga ?? fake()->randomElement([75000, 95000, 125000, 185000, 245000]);
        $jumlah = fake()->numberBetween(1, 3);
        $subtotal = $jumlah * $hargaSatuan;

        return [
            'id_pesanan' => Pesanan::inRandomOrder()->first()?->id_pesanan ?? Pesanan::factory(),
            'id_varian' => $varian?->id_varian ?? VarianProduk::factory(),
            'jumlah' => $jumlah,
            'harga_satuan' => $hargaSatuan,
            'subtotal' => $subtotal,
        ];
    }
}
