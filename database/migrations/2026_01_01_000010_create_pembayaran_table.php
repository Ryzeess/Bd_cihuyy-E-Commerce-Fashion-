<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_pesanan')->unique()->constrained('pesanan', 'id_pesanan')->onDelete('cascade');
            $table->enum('metode_pembayaran', ['transfer_bank', 'qris', 'e_wallet', 'kartu_kredit', 'cod']);
            $table->decimal('jumlah_bayar', 12, 2);
            $table->dateTime('waktu_bayar')->nullable();
            $table->enum('status_pembayaran', ['menunggu', 'lunas', 'gagal', 'kedaluwarsa'])->default('menunggu');
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
