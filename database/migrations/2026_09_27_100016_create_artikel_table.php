<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artikel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penulis_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul', 255);
            $table->enum('kategori', ['book_review', 'ta_exposure', 'artikel'])->default('artikel');
            $table->text('konten');
            $table->date('tanggal_terbit')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel');
    }
};
