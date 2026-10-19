<?php

namespace Database\Factories;

use App\Models\Pesanan;
use App\Models\Pelanggan;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pesanan>
 */
class PesananFactory extends Factory
{
    protected $model = Pesanan::class;

    public function definition(): array
    {
        $totalHarga = fake()->randomFloat(2, 50000, 750000);
        $pakaiVoucher = fake()->boolean(35);
        $voucher = $pakaiVoucher ? Voucher::where('status', 'aktif')->inRandomOrder()->first() : null;

        $potonganDiskon = 0.00;
        if ($voucher) {
            if ($voucher->tipe_diskon === 'persen') {
                $potonganDiskon = round(($totalHarga * $voucher->nilai_diskon) / 100, 2);
            } else {
                $potonganDiskon = min((float) $voucher->nilai_diskon, $totalHarga);
            }
        }

        $totalBayar = max(0, $totalHarga - $potonganDiskon);

        return [
            'nomor_pesanan' => 'ORD-' . date('Ym') . '-' . strtoupper(fake()->unique()->bothify('??#####')),
            'id_pelanggan' => Pelanggan::inRandomOrder()->first()?->id_pelanggan ?? Pelanggan::factory(),
            'id_voucher' => $voucher?->id_voucher,
            'tanggal_pesanan' => fake()->dateTimeBetween('-4 months', 'now'),
            'total_harga' => $totalHarga,
            'potongan_diskon' => $potonganDiskon,
            'total_bayar' => $totalBayar,
            'status_pesanan' => fake()->randomElement(['menunggu_pembayaran', 'diproses', 'dikirim', 'selesai', 'dibatalkan']),
            'alamat_pengiriman' => fake()->address(),
        ];
    }
}
