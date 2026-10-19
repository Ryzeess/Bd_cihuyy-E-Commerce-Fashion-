<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori')->onDelete('restrict');
            $table->string('nama_produk', 150);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2)->default(0.00);
            $table->string('bahan', 50)->nullable();
            $table->string('gaya', 50)->nullable();
            $table->integer('berat')->default(250);
            $table->timestamps();

            $table->index('harga');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
