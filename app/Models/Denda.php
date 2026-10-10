<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DendaController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        // Ambil peminjaman tanpa klausa WHERE denda agar tidak terjadi SQL Column Not Found
        $query = Peminjaman::with(['user']);

        if (in_array($user->role, ['mahasiswa', 'dosen'], true)) {
            $query->where('user_id', $user->id);
        }

        $dendaList = $query->orderBy('id', 'desc')->paginate(10);

        return view('denda.index', compact('dendaList'));
    }

    public function bayar(Request $request, $id): RedirectResponse
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Jika ada kolom status_denda atau status, update nilainya
        $peminjaman->update([
            'status_denda' => 'lunas',
        ]);

        return back()->with('success', 'Pembayaran denda berhasil dicatat.');
    }
}