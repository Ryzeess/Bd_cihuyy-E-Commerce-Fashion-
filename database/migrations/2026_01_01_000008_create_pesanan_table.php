<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->string('nomor_pesanan', 50)->unique();
            $table->foreignId('id_pelanggan')->constrained('pelanggan', 'id_pelanggan')->onDelete('restrict');
            $table->foreignId('id_voucher')->nullable()->constrained('voucher', 'id_voucher')->onDelete('set null');
            $table->dateTime('tanggal_pesanan')->useCurrent();
            $table->decimal('total_harga', 12, 2)->default(0.00);
            $table->decimal('potongan_diskon', 12, 2)->default(0.00);
            $table->decimal('total_bayar', 12, 2)->default(0.00);
            $table->enum('status_pesanan', ['menunggu_pembayaran', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('menunggu_pembayaran');
            $table->text('alamat_pengiriman');
            $table->timestamps();

            $table->index('status_pesanan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
