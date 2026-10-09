<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('feedback')) {
            Schema::create('feedback', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('user_id');
                $table->string('jenis', 50)->default('tanya_pustakawan');
                $table->text('isi');
                $table->text('balasan')->nullable();
                $table->string('status', 50)->default('baru');
                $table->primary('id');
                $table->foreign('user_id')->references('id')->on('user')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
