<?php

namespace Database\Factories;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    public function definition(): array
    {
        $namaPrefix = [
            'Kaos Polos', 'Kaos Graphic Print', 'Kaos Raglan', 'Kemeja Flannel',
            'Kemeja Oxford', 'Kemeja Katun Rayon', 'Celana Chino Slim', 'Celana Jeans Regular',
            'Celana Cargo', 'Jaket Bomber', 'Jaket Parka Hoodie', 'Jaket Denim Vintage',
            'Hoodie Jumper', 'Sweater Crewneck', 'Cardigan Rajut', 'Tunik Modern',
            'Dress Casual', 'Blazer Formal', 'Polo Shirt Classic'
        ];

        $namaSeries = [
            'Signature Edition', 'Urban Series', 'Essential Basic', 'Comfort Daily',
            'Summer Breeze', 'Monochrome Style', 'Heavyweight Edition', 'Vintage Wash',
            'Minimalist Cut', 'Premium Heritage'
        ];

        $bahanList = [
            'Cotton Combed 24s', 'Cotton Combed 30s', 'Katun Oxford', 'Twill Stretch',
            'Taslan Despo', 'Fleece Tebal 280gsm', 'Denim 14oz', 'Linen Pure',
            'Baby Terry', 'Polyester Premium', 'Rayon Twill'
        ];

        $gayaList = ['Casual', 'Streetwear', 'Formal', 'Smart Casual', 'Vintage', 'Minimalist', 'Sporty'];

        $namaProduk = fake()->randomElement($namaPrefix) . ' ' . fake()->randomElement($namaSeries);

        return [
            'id_kategori' => Kategori::inRandomOrder()->first()?->id_kategori ?? Kategori::factory(),
            'nama_produk' => $namaProduk,
            'deskripsi' => fake()->paragraph(2),
            'harga' => fake()->randomElement([55000, 75000, 89000, 110000, 145000, 175000, 195000, 245000, 275000, 320000, 395000]),
            'bahan' => fake()->randomElement($bahanList),
            'gaya' => fake()->randomElement($gayaList),
            'berat' => fake()->randomElement([180, 220, 250, 300, 350, 400, 500, 600]),
        ];
    }
}
