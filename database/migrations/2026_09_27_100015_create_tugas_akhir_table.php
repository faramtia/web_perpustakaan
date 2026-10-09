<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tugas_akhir')) {
            Schema::create('tugas_akhir', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('user_id');
                $table->string('judul', 250);
                $table->string('pembimbing', 150)->nullable();
                $table->string('file_tugas', 255)->nullable();
                $table->string('status', 50)->default('Menunggu');
                $table->primary('id');
                $table->foreign('user_id')->references('id')->on('user')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_akhir');
    }
};
