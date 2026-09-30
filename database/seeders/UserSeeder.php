<?php

namespace Database\Seeders;

use App\Models\JenisUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Bikin 1 akun contoh untuk tiap role, supaya kelompok
     * langsung bisa login & lihat perbedaan tiap dashboard.
     *
     * Semua password: password
     */
    public function run(): void
    {
        $admin     = JenisUser::where('nama_role', 'admin')->first();
        $petugas   = JenisUser::where('nama_role', 'petugas')->first();
        $mahasiswa = JenisUser::where('nama_role', 'mahasiswa')->first();
        $dosen     = JenisUser::where('nama_role', 'dosen')->first();

        $akun = [
            ['jenis_user_id' => $admin->id,     'name' => 'Admin Perpustakaan', 'nim_nip' => 'A001', 'email' => 'admin@pnl.ac.id'],
            ['jenis_user_id' => $petugas->id,   'name' => 'Petugas Pustakawan', 'nim_nip' => 'P001', 'email' => 'petugas@pnl.ac.id'],
            ['jenis_user_id' => $mahasiswa->id, 'name' => 'Contoh Mahasiswa',   'nim_nip' => '2201001', 'email' => 'mahasiswa@pnl.ac.id'],
            ['jenis_user_id' => $dosen->id,     'name' => 'Contoh Dosen',      'nim_nip' => 'D0012',   'email' => 'dosen@pnl.ac.id'],
        ];

        foreach ($akun as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'jenis_user_id' => $data['jenis_user_id'],
                    'name' => $data['name'],
                    'nim_nip' => $data['nim_nip'],
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
