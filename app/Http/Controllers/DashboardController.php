<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kebiasaan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect berdasarkan role
        if ($user->role == 'murid') {
            return $this->dashboardMurid($user);
        } elseif ($user->role == 'guru') {
            return $this->dashboardGuru($user);
        } elseif ($user->role == 'pengawas') {
            return $this->dashboardPengawas($user);
        }
        
        // Fallback
        return view('dashboard.murid');
    }

private function dashboardMurid($user)
{
    try {
        // Ambil data kebiasaan murid dengan filter
        $kebiasaan = $user->kebiasaan()
            ->orderBy('tanggal', 'desc')
            ->take(30)
            ->get();

        // Siapkan data untuk chart - PASTIKAN INI ADA
        $chartData = [
            'labels' => $kebiasaan->pluck('tanggal')->map(function($date) {
                return $date->format('d M');
            }),
            'jam_bangun' => $kebiasaan->map(function($item) {
                if (!$item->jam_bangun) return 0;
                try {
                    $time = \Carbon\Carbon::parse($item->jam_bangun);
                    return $time->hour + ($time->minute / 100);
                } catch (\Exception $e) {
                    return 0;
                }
            }),
            'jam_tidur' => $kebiasaan->map(function($item) {
                if (!$item->jam_tidur) return 0;
                try {
                    $time = \Carbon\Carbon::parse($item->jam_tidur);
                    return $time->hour + ($time->minute / 100);
                } catch (\Exception $e) {
                    return 0;
                }
            }),
            'durasi_belajar' => $kebiasaan->pluck('durasi_belajar')
        ];

        // DEBUG: Cek apakah chartData ada
        logger('ChartData sent to view:', ['chartData' => $chartData]);
        logger('Kebiasaan count:', ['count' => $kebiasaan->count()]);

        // Hitung statistik
        $totalData = $kebiasaan->count();
        $rataBelajar = $kebiasaan->avg('durasi_belajar');

        return view('dashboard.murid', [
            'user' => $user,
            'kebiasaan' => $kebiasaan,
            'chartData' => $chartData, // ✅ PASTIKAN INI ADA
            'totalData' => $totalData,
            'rataBelajar' => $rataBelajar
        ]);

    } catch (\Exception $e) {
        logger('Error in dashboardMurid: ' . $e->getMessage());
        
        // Fallback jika ada error
        return view('dashboard.murid', [
            'user' => $user,
            'kebiasaan' => collect(),
            'chartData' => [ // ✅ INI JUGA HARUS ADA
                'labels' => [],
                'jam_bangun' => [],
                'jam_tidur' => [],
                'durasi_belajar' => []
            ],
            'totalData' => 0,
            'rataBelajar' => 0
        ]);
    }
}

    private function dashboardGuru($user)
    {
        // Ambil data murid yang dibimbing oleh guru ini
        $muridList = User::where('guru_id', $user->id)
            ->with(['kebiasaan' => function($query) {
                $query->orderBy('tanggal', 'desc')->take(7); // 7 data terakhir
            }])
            ->get();

        // Hitung statistik untuk setiap murid
        $muridList->each(function($murid) {
            $murid->total_data = $murid->kebiasaan->count();
            $murid->rata_belajar = $murid->kebiasaan->avg('durasi_belajar');
            $murid->data_terbaru = $murid->kebiasaan->first();
        });

        return view('dashboard.guru', [
            'user' => $user,
            'muridList' => $muridList
        ]);
    }

    private function dashboardPengawas($user)
    {
    // Ambil data semua guru dengan statistik murid
    $guruList = User::where('role', 'guru')
        ->withCount('murid')
        ->with(['murid' => function($query) {
            $query->withCount('kebiasaan');
        }])
        ->get();

    // Hitung statistik keseluruhan
    $totalGuru = $guruList->count();
    $totalMurid = User::where('role', 'murid')->count();
    $totalDataKebiasaan = \App\Models\Kebiasaan::count();
    
    // Statistik kebiasaan
    $rataBelajarKeseluruhan = \App\Models\Kebiasaan::avg('durasi_belajar');
    $totalMuridAktif = User::where('role', 'murid')
        ->whereHas('kebiasaan')
        ->count();

    // Ambil data pengawas lainnya
    $pengawasList = User::where('role', 'pengawas')
        ->where('id', '!=', $user->id) // Exclude current user
        ->get();

    return view('dashboard.pengawas', [
        'user' => $user,
        'guruList' => $guruList,
        'pengawasList' => $pengawasList,
        'totalGuru' => $totalGuru,
        'totalMurid' => $totalMurid,
        'totalDataKebiasaan' => $totalDataKebiasaan,
        'rataBelajarKeseluruhan' => $rataBelajarKeseluruhan,
        'totalMuridAktif' => $totalMuridAktif
    ]);
}
}