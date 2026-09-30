<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasi')->nullOnDelete();
            $table->foreignId('tipe_koleksi_id')->constrained('tipe_koleksi')->cascadeOnDelete();
            $table->string('judul', 255);
            $table->string('penulis', 150)->nullable();
            $table->string('penerbit', 150)->nullable();
            $table->year('tahun')->nullable();
            $table->string('isbn', 20)->nullable();
            $table->unsignedInteger('stok')->default(0);
            $table->string('cover')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
