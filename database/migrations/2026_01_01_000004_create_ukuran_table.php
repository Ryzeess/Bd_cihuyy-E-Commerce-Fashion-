<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ukuran', function (Blueprint $table) {
            $table->id('id_ukuran');
            $table->string('nama_ukuran', 20);
            $table->string('keterangan', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ukuran');
    }
};
