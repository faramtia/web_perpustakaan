<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('detail_peminjaman')) {
            Schema::create('detail_peminjaman', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('peminjaman_id');
                $table->integer('buku_id');
                $table->integer('jumlah')->default(1);
                $table->primary('id');
                $table->foreign('peminjaman_id')->references('id')->on('peminjaman')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('buku_id')->references('id')->on('buku')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_peminjaman');
    }
};
