<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelangganList = [
            [
                'id_pelanggan' => 1,
                'nama' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@gmail.com',
                'password' => Hash::make('password123'),
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 45, Bandung',
                'tanggal_daftar' => '2026-09-01 10:00:00',
            ],
            [
                'id_pelanggan' => 2,
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti.nur@gmail.com',
                'password' => Hash::make('password123'),
                'no_telepon' => '085678901234',
                'alamat' => 'Jl. Malioboro No. 12, Yogyakarta',
                'tanggal_daftar' => '2026-09-05 11:30:00',
            ],
            [
                'id_pelanggan' => 3,
                'nama' => 'Budi Santoso',
                'email' => 'budi.santoso@yahoo.com',
                'password' => Hash::make('password123'),
                'no_telepon' => '087890123456',
                'alamat' => 'Jl. Sudirman No. 88, Jakarta Selatan',
                'tanggal_daftar' => '2026-09-10 14:15:00',
            ],
        ];

        foreach ($pelangganList as $data) {
            Pelanggan::updateOrCreate(['id_pelanggan' => $data['id_pelanggan']], $data);
        }
    }
}
