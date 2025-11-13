<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuruController extends Controller
{
    // Tampilkan form tambah guru
    public function create()
    {
        // Manual check role
        if (Auth::user()->role !== 'pengawas') {
            abort(403, 'Hanya pengawas yang dapat mengakses halaman ini.');
        }
        
        return view('guru.create');
    }

    // Simpan guru baru
    public function store(Request $request)
    {
        // Manual check role
        if (Auth::user()->role !== 'pengawas') {
            abort(403, 'Hanya pengawas yang dapat mengakses halaman ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'guru'
        ]);

        return redirect()->route('dashboard')->with('success', 'Akun guru berhasil dibuat!');
    }

    // Detail guru dengan statistik
    public function show($id)
    {
        // Manual check role
        if (Auth::user()->role !== 'pengawas') {
            abort(403, 'Hanya pengawas yang dapat mengakses halaman ini.');
        }

        $guru = User::where('id', $id)
            ->where('role', 'guru')
            ->with(['murid' => function($query) {
                $query->withCount('kebiasaan')
                      ->with(['kebiasaan' => function($q) {
                          $q->orderBy('tanggal', 'desc')->take(7);
                      }]);
            }])
            ->firstOrFail();

        // Hitung statistik untuk guru
        $statistik = [
            'total_murid' => $guru->murid->count(),
            'murid_aktif' => $guru->murid->where('kebiasaan_count', '>', 0)->count(),
            'total_data_kebiasaan' => $guru->murid->sum('kebiasaan_count'),
            'rata_belajar' => $guru->murid->flatMap->kebiasaan->avg('durasi_belajar')
        ];

        return view('guru.show', compact('guru', 'statistik'));
    }

    /**
     * Menampilkan form reset password guru - HANYA PENGAWAS
     */
    public function showResetPasswordGuruForm()
    {
        // Hanya pengawas yang bisa akses
        if (Auth::user()->role !== 'pengawas') {
            abort(403, 'Hanya pengawas yang dapat mengakses halaman reset password guru.');
        }

        // Kembalikan search parameter jika ada di session
        $search = session('search', '');

        // ✅ INISIASI VARIABLE $guru DENGAN COLLECTION KOSONG
    $guru = collect(); // atau new \Illuminate\Database\Eloquent\Collection();

    return view('guru.reset-password', compact('guru', 'search'));
    }

    /**
     * Pencarian guru berdasarkan nama atau email - HANYA PENGAWAS
     */

    public function resetPasswordGuruCustom(Request $request, $id)
{
    try {
        $request->validate([
            'new_password' => 'required|min:6',
        ]);

        // Cari guru berdasarkan ID dan role
        $guru = User::where('role', 'guru')->findOrFail($id);

        // Update password
        $guru->password = Hash::make($request->new_password);
        $guru->save();

        return redirect()->route('pengawas.reset-password-guru')
            ->with([
                'success' => 'Password berhasil direset dengan password custom.',
                'guru_name' => $guru->name,
                'new_password' => $request->new_password // Tampilkan password custom
            ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->route('pengawas.reset-password-guru')
            ->with('error', 'Guru tidak ditemukan.');
    } catch (\Exception $e) {
        return redirect()->route('pengawas.reset-password-guru')
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
    public function searchGuru(Request $request)
    {
        // Hanya pengawas yang bisa akses
        if (Auth::user()->role !== 'pengawas') {
            abort(403, 'Hanya pengawas yang dapat melakukan reset password guru.');
        }

        $request->validate([
            'search' => 'required|string|min:2'
        ]);

        $search = $request->search;

        // Cari semua guru
        $guru = User::where('role', 'guru')
            ->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->withCount('murid') // Hitung jumlah murid
            ->get();

        return view('guru.reset-password', compact('guru', 'search'));
    }

    /**
     * Generate password baru untuk guru - HANYA PENGAWAS
     */
    public function generateNewPasswordGuru(Request $request)
    {
        // Hanya pengawas yang bisa akses
        if (Auth::user()->role !== 'pengawas') {
            abort(403, 'Hanya pengawas yang dapat melakukan reset password guru.');
        }

        $request->validate([
            'guru_id' => 'required|exists:users,id'
        ]);

        $guru = User::where('id', $request->guru_id)
            ->where('role', 'guru')
            ->firstOrFail();
        
        // Generate password baru (8 karakter acak)
        $newPassword = Str::random(8);
        
        // Update password guru
        $guru->update([
            'password' => Hash::make($newPassword)
        ]);

         // Simpan search parameter
        $search = $request->search;

         return redirect()->route('pengawas.reset-password-guru')
            ->with('success', 'Password guru berhasil direset!')
            ->with('new_password', $newPassword)
            ->with('guru_name', $guru->name)
            ->with('search', $search);
    }

    /**
     * Reset password guru dengan password custom - HANYA PENGAWAS
     */
    public function resetPasswordGuruAction(Request $request, $id)
    {
        // Hanya pengawas yang bisa akses
        if (Auth::user()->role !== 'pengawas') {
            abort(403, 'Hanya pengawas yang dapat melakukan reset password guru.');
        }

        $request->validate([
            'new_password' => 'required|string|min:6'
        ]);

        $guru = User::where('id', $id)
            ->where('role', 'guru')
            ->firstOrFail();
        
        // Update password guru
        $guru->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Simpan search parameter
        $search = $request->search;

        return redirect()->route('pengawas.reset-password-guru')
            ->with('success', 'Password guru berhasil direset!')
            ->with('guru_name', $guru->name)
            ->with('search', $search);
    }

}
