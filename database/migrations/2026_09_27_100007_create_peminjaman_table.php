<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('peminjaman')) {
            Schema::create('peminjaman', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('user_id');
                $table->date('tanggal_pinjam');
                $table->date('tanggal_kembali')->nullable();
                $table->string('status', 50)->default('Dipinjam');
                $table->date('tanggal_jatuh_tempo')->nullable();
                $table->primary('id');
                $table->foreign('user_id')->references('id')->on('user')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
