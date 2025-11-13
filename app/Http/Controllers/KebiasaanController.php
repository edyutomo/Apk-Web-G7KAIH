<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kebiasaan;
use Illuminate\Support\Facades\Auth;

class KebiasaanController extends Controller
{
    // Simpan data kebiasaan baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required|date',
            'jam_bangun' => 'required|date_format:H:i',
            'jam_tidur' => 'required|date_format:H:i',
            'durasi_belajar' => 'required|integer|min:0|max:1440' // maksimal 24 jam dalam menit
        ]);

        // Cek apakah sudah ada data untuk tanggal tersebut
        $existingData = Kebiasaan::where('murid_id', Auth::id())
            ->where('tanggal', $request->tanggal)
            ->first();

        if ($existingData) {
            return redirect()->back()->with('error', 'Data untuk tanggal ini sudah ada!');
        }

        // Simpan data
        Kebiasaan::create([
            'murid_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'jam_bangun' => $request->jam_bangun,
            'jam_tidur' => $request->jam_tidur,
            'durasi_belajar' => $request->durasi_belajar
        ]);

        return redirect()->route('dashboard')->with('success', 'Data kebiasaan berhasil disimpan!');
    }

    // API untuk data chart
    public function getChartData(Request $request)
    {
        $muridId = Auth::id();
        $filter = $request->get('filter', 'month'); // week, month, year

        $query = Kebiasaan::where('murid_id', $muridId);

        // Apply filter berdasarkan periode
        switch ($filter) {
            case 'week':
                $query->where('tanggal', '>=', now()->subWeek());
                break;
            case 'month':
                $query->where('tanggal', '>=', now()->subMonth());
                break;
            case 'year':
                $query->where('tanggal', '>=', now()->subYear());
                break;
            default:
                $query->where('tanggal', '>=', now()->subMonth());
        }

        $kebiasaan = $query->orderBy('tanggal', 'asc')->get();

        $data = [
            'labels' => $kebiasaan->pluck('tanggal')->map(function($date) {
                return $date->format('d M');
            }),
            'jam_bangun' => $kebiasaan->pluck('jam_bangun')->map(function($time) {
                $carbon = \Carbon\Carbon::parse($time);
                return $carbon->format('H.i');
            }),
            'jam_tidur' => $kebiasaan->pluck('jam_tidur')->map(function($time) {
                $carbon = \Carbon\Carbon::parse($time);
                return $carbon->format('H.i');
            }),
            'durasi_belajar' => $kebiasaan->pluck('durasi_belajar')
        ];

        return response()->json($data);
    }
}
