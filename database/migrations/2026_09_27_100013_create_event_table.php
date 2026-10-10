<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('event')) {
            Schema::create('event', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('judul', 200);
                $table->text('deskripsi')->nullable();
                $table->date('tanggal_mulai')->nullable();
                $table->date('tanggal_selesai')->nullable();
                $table->string('lokasi', 100);
                $table->integer('kuota')->nullable();
                $table->primary('id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
