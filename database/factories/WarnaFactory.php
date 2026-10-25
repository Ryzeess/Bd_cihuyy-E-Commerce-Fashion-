<?php

namespace Database\Factories;

use App\Models\Warna;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warna>
 */
class WarnaFactory extends Factory
{
    protected $model = Warna::class;

    public function definition(): array
    {
        static $index = 0;
        $presets = [
            ['Hitam', '#000000'],
            ['Putih', '#FFFFFF'],
            ['Navy / Biru Dongker', '#000080'],
            ['Merah Marun', '#800000'],
            ['Abu-Abu Tua', '#4A4A4A'],
            ['Abu-Abu Muda', '#D3D3D3'],
            ['Cream / Beige', '#F5F5DC'],
            ['Hijau Army', '#4B5320'],
            ['Hijau Sage', '#9CAF88'],
            ['Cokelat Mocca', '#967969'],
            ['Cokelat Tua', '#5C4033'],
            ['Mustard', '#FFDB58'],
            ['Biru Denim', '#1560BD'],
            ['Dusty Pink', '#DCAE96'],
            ['Lilac / Ungu Muda', '#C8A2C8'],
        ];

        if ($index < count($presets)) {
            $preset = $presets[$index++];
            return [
                'nama_warna' => $preset[0],
                'kode_warna' => $preset[1],
            ];
        }

        return [
            'nama_warna' => ucfirst(fake()->safeColorName()),
            'kode_warna' => fake()->hexColor(),
        ];
    }
}
