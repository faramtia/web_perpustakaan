<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user')) {
            Schema::create('user', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('jenis_user_id');
                $table->string('nama', 100);
                $table->string('nim_nip', 50)->nullable()->unique();
                $table->string('email', 100)->nullable()->unique();
                $table->string('password', 255);
                $table->string('remember_token', 100)->nullable();
                $table->primary('id');
                $table->foreign('jenis_user_id')->references('id')->on('jenis_user')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
