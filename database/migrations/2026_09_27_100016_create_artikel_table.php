<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('artikel')) {
            Schema::create('artikel', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('user_id');
                $table->integer('kategori_id');
                $table->string('judul', 250);
                $table->text('isi_artikel')->nullable();
                $table->date('tanggal_upload')->nullable();
                $table->primary('id');
                $table->foreign('user_id')->references('id')->on('user')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('kategori_id')->references('id')->on('kategori')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
