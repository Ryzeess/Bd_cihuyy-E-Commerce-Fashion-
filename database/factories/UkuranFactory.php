<?php

namespace Database\Factories;

use App\Models\Ukuran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ukuran>
 */
class UkuranFactory extends Factory
{
    protected $model = Ukuran::class;

    public function definition(): array
    {
        static $index = 0;
        $presets = [
            ['XS', 'Extra Small - Lebar Dada 46 cm, Panjang 66 cm'],
            ['S', 'Small - Lebar Dada 48 cm, Panjang 68 cm'],
            ['M', 'Medium - Lebar Dada 50 cm, Panjang 70 cm'],
            ['L', 'Large - Lebar Dada 52 cm, Panjang 72 cm'],
            ['XL', 'Extra Large - Lebar Dada 54 cm, Panjang 74 cm'],
            ['XXL', 'Double XL - Lebar Dada 56 cm, Panjang 76 cm'],
            ['3XL', 'Triple XL - Lebar Dada 58 cm, Panjang 78 cm'],
            ['All Size', 'Fit to L - Ukuran standar fleksibel'],
            ['28', 'Ukuran Pinggang 28 inci (71 cm)'],
            ['30', 'Ukuran Pinggang 30 inci (76 cm)'],
            ['32', 'Ukuran Pinggang 32 inci (81 cm)'],
            ['34', 'Ukuran Pinggang 34 inci (86 cm)'],
            ['36', 'Ukuran Pinggang 36 inci (91 cm)'],
        ];

        if ($index < count($presets)) {
            $preset = $presets[$index++];
            return [
                'nama_ukuran' => $preset[0],
                'keterangan' => $preset[1],
            ];
        }

        return [
            'nama_ukuran' => 'SZ-' . fake()->numberBetween(38, 46),
            'keterangan' => 'Ukuran tambahan standar konveksi',
        ];
    }
}
