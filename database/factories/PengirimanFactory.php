<?php

namespace Database\Factories;

use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\Kurir;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengiriman>
 */
class PengirimanFactory extends Factory
{
    protected $model = Pengiriman::class;

    public function definition(): array
    {
        $status = fake()->randomElement(['diproses', 'dalam_perjalanan', 'terkirim', 'gagal']);
        $tglKirim = in_array($status, ['dalam_perjalanan', 'terkirim']) ? fake()->dateTimeBetween('-2 months', 'now') : null;
        $tglDiterima = ($status === 'terkirim' && $tglKirim) ? (clone $tglKirim)->modify('+' . fake()->numberBetween(1, 4) . ' days') : null;

        $kurir = Kurir::inRandomOrder()->first();
        $kodeKurir = $kurir ? $kurir->kode_kurir : 'EXP';
        $ongkir = $kurir ? (float) $kurir->tarif_per_kg : 12000.00;

        return [
            'id_pesanan' => Pesanan::factory(),
            'id_kurir' => $kurir?->id_kurir ?? Kurir::factory(),
            'nomor_resi' => strtoupper(explode('-', $kodeKurir)[0]) . '-' . fake()->unique()->numerify('##########'),
            'status_pengiriman' => $status,
            'biaya_ongkir' => $ongkir,
            'tanggal_kirim' => $tglKirim,
            'tanggal_diterima' => $tglDiterima,
        ];
    }

    public function terkirim(): static
    {
        return $this->state(function () {
            $tglKirim = fake()->dateTimeBetween('-1 month', '-5 days');
            $tglDiterima = (clone $tglKirim)->modify('+' . fake()->numberBetween(1, 3) . ' days');

            return [
                'status_pengiriman' => 'terkirim',
                'tanggal_kirim' => $tglKirim,
                'tanggal_diterima' => $tglDiterima,
            ];
        });
    }
}
