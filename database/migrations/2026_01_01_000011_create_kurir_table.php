<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kurir', function (Blueprint $table) {
            $table->id('id_kurir');
            $table->string('nama_kurir', 100);
            $table->string('kode_kurir', 20)->unique();
            $table->string('layanan', 50);
            $table->decimal('tarif_per_kg', 12, 2)->default(10000.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kurir');
    }
};
