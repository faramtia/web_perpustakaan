<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('reservasi')) {
            Schema::create('reservasi', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('user_id');
                $table->integer('buku_id');
                $table->date('tanggal_reservasi');
                $table->string('status', 50)->default('Menunggu');
                $table->primary('id');
                $table->foreign('user_id')->references('id')->on('user')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('buku_id')->references('id')->on('buku')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasi');
    }
};
