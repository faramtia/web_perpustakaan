<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipe_koleksi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tipe', 50); // buku, e-book, e-jurnal, e-TGA
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipe_koleksi');
    }
};
