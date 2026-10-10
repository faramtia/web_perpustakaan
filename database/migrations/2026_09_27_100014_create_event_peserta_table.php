<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('event_peserta')) {
            Schema::create('event_peserta', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('user_id');
                $table->integer('events_id');
                $table->string('status_pendaftaran', 50)->default('Terdaftar');
                $table->date('tanggal_daftar')->nullable();
                $table->primary('id');
                $table->foreign('user_id')->references('id')->on('user')->cascadeOnDelete()->cascadeOnUpdate();
                $table->foreign('events_id')->references('id')->on('event')->cascadeOnDelete()->cascadeOnUpdate();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('event_peserta');
    }
};
