<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pelanggan>
 */
class PelangganFactory extends Factory
{
    protected $model = Pelanggan::class;

    protected static ?string $password = null;
    protected static int $emailSequence = 0;

    public function definition(): array
    {
        static::$emailSequence++;
        $domain = fake()->randomElement(['gmail.com', 'yahoo.com', 'outlook.com', 'icloud.com', 'hotmail.com']);
        $uniqueEmail = 'user_' . static::$emailSequence . '_' . Str::lower(Str::random(4)) . '@' . $domain;

        return [
            'nama' => fake()->name(),
            'email' => $uniqueEmail,
            'password' => static::$password ??= Hash::make('password123'),
            'no_telepon' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'tanggal_daftar' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}