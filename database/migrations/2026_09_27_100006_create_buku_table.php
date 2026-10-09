<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('buku')) {
            Schema::create('buku', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('kategori_id');
                $table->integer('lokasi_id');
                $table->integer('tipe_koleksi_id');
                $table->string('judul', 200);
                $table->string('penulis', 150)->nullable();
                $table->string('penerbit', 150)->nullable();
                $table->year('tahun_terbit')->nullable();
                $table->string('isbn', 50)->nullable();
                $table->integer('stok')->default(0);
                $table->string('cover', 400)->nullable();
                $table->primary('id');
                $table->foreign('kategori_id')->references('id')->on('kategori')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('lokasi_id')->references('id')->on('lokasi')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('tipe_koleksi_id')->references('id')->on('tipe_koleksi')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
