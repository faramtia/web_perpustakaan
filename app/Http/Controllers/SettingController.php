<?php

namespace App\Http\Controllers;

use App\Models\JenisUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Aturan peminjaman per role disimpan di tabel jenis_user
 * (lama_pinjam_hari dan denda_per_hari), jadi pengaturannya mengedit tabel itu.
 */
class SettingController extends Controller
{
    public function index(): View
    {
        return view('setting.index', [
            'roles' => JenisUser::withCount('users')->orderBy('id')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'aturan' => ['required', 'array'],
            'aturan.*.lama_pinjam_hari' => ['required', 'integer', 'min:1', 'max:365'],
            'aturan.*.denda_per_hari' => ['required', 'integer', 'min:0', 'max:100000'],
        ], [
            'aturan.*.lama_pinjam_hari.required' => 'Lama pinjam wajib diisi untuk setiap role.',
            'aturan.*.lama_pinjam_hari.min' => 'Lama pinjam minimal 1 hari.',
            'aturan.*.lama_pinjam_hari.max' => 'Lama pinjam maksimal 365 hari.',
            'aturan.*.denda_per_hari.required' => 'Denda per hari wajib diisi untuk setiap role.',
        ]);

        foreach ($request->aturan as $id => $nilai) {
            JenisUser::whereKey($id)->update([
                'lama_pinjam_hari' => (int) $nilai['lama_pinjam_hari'],
                'denda_per_hari' => (int) $nilai['denda_per_hari'],
            ]);
        }

        return back()->with('success', 'Aturan peminjaman disimpan. Berlaku untuk peminjaman baru yang disetujui setelah ini.');
    }
}
