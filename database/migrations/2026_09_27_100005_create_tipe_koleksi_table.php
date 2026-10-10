<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tipe_koleksi')) {
            Schema::create('tipe_koleksi', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('nama_tipe', 100);
                $table->primary('id');
            });
        }
    }
    public function down(): void { Schema::dropIfExists('tipe_koleksi'); }
};
