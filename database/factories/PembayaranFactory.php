<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembayaran>
 */
class PembayaranFactory extends Factory
{
    protected $model = Pembayaran::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['lunas', 'lunas', 'lunas', 'menunggu', 'gagal', 'kedaluwarsa']);
        $waktuBayar = ($status === 'lunas') ? fake()->dateTimeBetween('-3 months', 'now') : null;
        $bukti = ($status === 'lunas' && fake()->boolean(65)) ? 'uploads/bukti_' . fake()->lexify('??????') . '.jpg' : null;

        return [
            'id_pesanan' => Pesanan::factory(),
            'metode_pembayaran' => fake()->randomElement(['transfer_bank', 'qris', 'e_wallet', 'kartu_kredit', 'cod']),
            'jumlah_bayar' => fake()->randomFloat(2, 50000, 750000),
            'waktu_bayar' => $waktuBayar,
            'status_pembayaran' => $status,
            'bukti_pembayaran' => $bukti,
        ];
    }

    public function lunas(): static
    {
        return $this->state(fn () => [
            'status_pembayaran' => 'lunas',
            'waktu_bayar' => now(),
            'bukti_pembayaran' => 'uploads/bukti_' . fake()->lexify('??????') . '.jpg',
        ]);
    }
}
