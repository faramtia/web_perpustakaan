<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('jenis_user_id')
                ->nullable()
                ->after('id')
                ->constrained('jenis_user')
                ->nullOnDelete();

            $table->string('nim_nip', 30)->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('jenis_user_id');
            $table->dropColumn('nim_nip');
        });
    }
};
