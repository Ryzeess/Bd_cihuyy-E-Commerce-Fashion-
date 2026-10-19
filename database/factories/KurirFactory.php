<?php

namespace Database\Factories;

use App\Models\Kurir;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kurir>
 */
class KurirFactory extends Factory
{
    protected $model = Kurir::class;

    public function definition(): array
    {
        static $index = 0;
        $presets = [
            ['JNE Express', 'JNE-REG', 'Reguler (2-3 Hari)', 12000.00],
            ['JNE Express', 'JNE-YES', 'YES (Yakin Esok Sampai)', 22000.00],
            ['J&T Express', 'JNT-EZ', 'EZ Regular (1-2 Hari)', 13000.00],
            ['J&T Express', 'JNT-FAST', 'Super Express (Next Day)', 21000.00],
            ['SiCepat Ekspres', 'SICEPAT-REG', 'SiUntung Regular (1-2 Hari)', 11000.00],
            ['SiCepat Ekspres', 'SICEPAT-BEST', 'BEST (Besok Sampai Tujuan)', 19000.00],
            ['Anteraja', 'ANTERAJA-REG', 'Regular Service (2 Hari)', 11500.00],
            ['Anteraja', 'ANTERAJA-ND', 'Next Day Express', 18000.00],
            ['Pos Indonesia', 'POS-KILAT', 'Pos Kilat Khusus', 10000.00],
            ['Ninja Xpress', 'NINJA-STD', 'Standard Delivery', 12500.00],
            ['TIKI', 'TIKI-REG', 'Regular Service', 12000.00],
            ['TIKI', 'TIKI-ONS', 'Over Night Service (1 Hari)', 20000.00],
        ];

        if ($index < count($presets)) {
            $preset = $presets[$index++];
            return [
                'nama_kurir' => $preset[0],
                'kode_kurir' => $preset[1],
                'layanan' => $preset[2],
                'tarif_per_kg' => $preset[3],
            ];
        }

        return [
            'nama_kurir' => fake()->company() . ' Logistics',
            'kode_kurir' => strtoupper(fake()->unique()->bothify('EXP-###??')),
            'layanan' => 'Standard Cargo',
            'tarif_per_kg' => fake()->randomElement([10000, 12000, 15000, 18000, 20000]),
        ];
    }
}
