<?php

namespace Database\Seeders;

use App\Models\JenisUser;
use Illuminate\Database\Seeder;

class JenisUserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nama_role' => 'admin',     'deskripsi' => 'Kepala perpustakaan / kontrol penuh'],
            ['nama_role' => 'petugas',   'deskripsi' => 'Pustakawan, operasional harian'],
            ['nama_role' => 'mahasiswa', 'deskripsi' => 'Anggota dari kalangan mahasiswa'],
            ['nama_role' => 'dosen',     'deskripsi' => 'Anggota dari kalangan dosen'],
        ];

        foreach ($roles as $role) {
            JenisUser::firstOrCreate(['nama_role' => $role['nama_role']], $role);
        }
    }
}
