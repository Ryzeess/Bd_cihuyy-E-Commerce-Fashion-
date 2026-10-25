<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Data Sampel Awal (Data Statis BRD & Demo)
        $this->call([
            UsersTableSeeder::class,
            ProductsTableSeeder::class,
            OrdersTableSeeder::class,
        ]);

        // 2. Data Dummy Otomatis Skala Besar (Semua 12 Entitas via Factory)
        $this->call(DummyDataSeeder::class);
    }
}
