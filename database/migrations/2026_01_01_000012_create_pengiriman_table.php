<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('id_pengiriman');
            $table->foreignId('id_pesanan')->unique()->constrained('pesanan', 'id_pesanan')->onDelete('cascade');
            $table->foreignId('id_kurir')->constrained('kurir', 'id_kurir')->onDelete('restrict');
            $table->string('nomor_resi', 100)->unique();
            $table->enum('status_pengiriman', ['diproses', 'dalam_perjalanan', 'terkirim', 'gagal'])->default('diproses');
            $table->decimal('biaya_ongkir', 12, 2)->default(0.00);
            $table->dateTime('tanggal_kirim')->nullable();
            $table->dateTime('tanggal_diterima')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
