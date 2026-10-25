<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher', function (Blueprint $table) {
            $table->id('id_voucher');
            $table->string('kode_voucher', 50)->unique();
            $table->enum('tipe_diskon', ['persen', 'nominal'])->default('nominal');
            $table->decimal('nilai_diskon', 12, 2)->default(0.00);
            $table->decimal('minimal_belanja', 12, 2)->default(0.00);
            $table->dateTime('tanggal_mulai')->nullable();
            $table->dateTime('tanggal_berakhir')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher');
    }
};
