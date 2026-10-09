<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('users');
    }

    public function down(): void
    {
        // Tabel users tidak digunakan oleh aplikasi ini.
    }
};
