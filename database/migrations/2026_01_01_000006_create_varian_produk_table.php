<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('varian_produk', function (Blueprint $table) {
            $table->id('id_varian');
            $table->foreignId('id_produk')->constrained('produk', 'id_produk')->onDelete('cascade');
            $table->foreignId('id_warna')->constrained('warna', 'id_warna')->onDelete('restrict');
            $table->foreignId('id_ukuran')->constrained('ukuran', 'id_ukuran')->onDelete('restrict');
            $table->string('kode_barang', 50)->unique();
            $table->integer('stok')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('varian_produk');
    }
};
