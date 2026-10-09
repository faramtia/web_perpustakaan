<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('lokasi')) {
            Schema::create('lokasi', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('nama_ruang', 100);
                $table->text('keterangan')->nullable();
                $table->primary('id');
            });
        }
    }
    public function down(): void { Schema::dropIfExists('lokasi'); }
};
