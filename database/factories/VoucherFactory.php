<?php

namespace Database\Factories;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition(): array
    {
        $tipe = fake()->randomElement(['persen', 'nominal']);
        $nilai = ($tipe === 'persen')
            ? fake()->randomElement([5, 10, 15, 20, 25, 30])
            : fake()->randomElement([10000, 15000, 20000, 25000, 50000]);

        $tanggalMulai = fake()->dateTimeBetween('-2 months', 'now');
        $tanggalBerakhir = fake()->dateTimeBetween('now', '+6 months');

        return [
            'kode_voucher' => strtoupper(fake()->unique()->bothify('PROMO-###???')),
            'tipe_diskon' => $tipe,
            'nilai_diskon' => $nilai,
            'minimal_belanja' => fake()->randomElement([50000, 75000, 100000, 150000, 200000]),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_berakhir' => $tanggalBerakhir,
            'status' => fake()->randomElement(['aktif', 'aktif', 'aktif', 'nonaktif']),
        ];
    }

    public function aktif(): static
    {
        return $this->state(fn () => [
            'status' => 'aktif',
            'tanggal_mulai' => now()->subDays(10),
            'tanggal_berakhir' => now()->addMonths(2),
        ]);
    }

    public function nonaktif(): static
    {
        return $this->state(fn () => [
            'status' => 'nonaktif',
        ]);
    }
}
