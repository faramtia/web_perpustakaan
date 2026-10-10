<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('jenis_user')) {
            Schema::create('jenis_user', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('nama_role', 20)->nullable();
                $table->integer('lama_pinjam_hari')->default(90);
                $table->integer('denda_per_hari')->default(1000);
                $table->primary('id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_user');
    }
};
