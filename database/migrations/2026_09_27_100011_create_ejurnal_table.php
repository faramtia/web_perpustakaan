<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ejurnal')) {
            Schema::create('ejurnal', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('kategori_id');
                $table->string('judul', 200);
                $table->string('penulis', 150)->nullable();
                $table->text('abstrak')->nullable();
                $table->string('file_jurnal', 255)->nullable();
                $table->year('tahun_terbit')->nullable();
                $table->primary('id');
                $table->foreign('kategori_id')->references('id')->on('kategori')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ejurnal');
    }
};
