<?php

namespace App\Http\Controllers;

use App\Models\JenisUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Admin mengelola akun pengguna. Pendaftaran mandiri hanya untuk mahasiswa & dosen,
 * jadi akun petugas dan admin dibuat lewat sini.
 */
class PenggunaController extends Controller
{
    public function index(Request $request): View
    {
        $pengguna = User::with('jenisUser')
            ->when($request->filled('role'), fn ($q) => $q->where('jenis_user_id', $request->role))
            ->when($request->filled('q'), function ($q) use ($request) {
                $kata = trim($request->q);
                $q->where(fn ($w) => $w->where('nama', 'like', "%{$kata}%")
                    ->orWhere('email', 'like', "%{$kata}%")
                    ->orWhere('nim_nip', 'like', "%{$kata}%"));
            })
            ->orderBy('jenis_user_id')
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('pengguna.index', [
            'pengguna' => $pengguna,
            'roles' => JenisUser::orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('pengguna.form', ['pengguna' => null, 'roles' => JenisUser::orderBy('id')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->aturan(), $this->pesan() + [
            'password.required' => 'Password wajib diisi.',
        ]);

        User::create($data);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna ditambahkan.');
    }

    public function edit(User $pengguna): View
    {
        return view('pengguna.form', ['pengguna' => $pengguna, 'roles' => JenisUser::orderBy('id')->get()]);
    }

    public function update(Request $request, User $pengguna): RedirectResponse
    {
        $data = $request->validate($this->aturan($pengguna), $this->pesan());

        // Password dikosongkan = tidak diganti.
        if (empty($data['password'])) {
            unset($data['password']);
        }

        // Admin tidak boleh menurunkan role dirinya sendiri (supaya tidak terkunci dari menu admin).
        if ($pengguna->id === Auth::id()) {
            $data['jenis_user_id'] = $pengguna->jenis_user_id;
        }

        $pengguna->update($data);

        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna diperbarui.');
    }

    public function destroy(User $pengguna): RedirectResponse
    {
        if ($pengguna->id === Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        // ON DELETE CASCADE akan menghapus seluruh riwayat pengguna, jadi cegah kalau sudah punya riwayat.
        $punyaRiwayat = $pengguna->peminjaman()->exists() || $pengguna->reservasi()->exists()
            || $pengguna->absensi()->exists() || $pengguna->feedback()->exists()
            || $pengguna->tugasAkhir()->exists() || $pengguna->artikel()->exists();

        if ($punyaRiwayat) {
            return back()->with('error', 'Pengguna ini sudah punya riwayat layanan sehingga tidak bisa dihapus.');
        }

        $pengguna->delete();

        return back()->with('success', 'Pengguna dihapus.');
    }

    private function aturan(?User $pengguna = null): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'nim_nip' => ['required', 'string', 'max:50', 'unique:user,nim_nip'.($pengguna ? ','.$pengguna->id : '')],
            'email' => ['required', 'email', 'max:100', 'unique:user,email'.($pengguna ? ','.$pengguna->id : '')],
            'jenis_user_id' => ['required', 'exists:jenis_user,id'],
            'password' => [$pengguna ? 'nullable' : 'required', Password::min(8)->letters()->numbers()],
        ];
    }

    private function pesan(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'nim_nip.required' => 'NIM / NIP wajib diisi.',
            'nim_nip.unique' => 'NIM / NIP ini sudah dipakai.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah dipakai.',
            'jenis_user_id.required' => 'Pilih role pengguna.',
        ];
    }
}
