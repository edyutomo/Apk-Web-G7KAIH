<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kebiasaan;
use App\Models\Murid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MuridController extends Controller
{
    // Tampilkan form tambah murid
    public function create()
    {
        \Log::info('User role: ' . auth()->user()->role);
        \Log::info('User name: ' . auth()->user()->name);
        // Manual check role
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat mengakses halaman ini.');
        }

        return view('murid.create');
    }

    // Simpan murid baru
    public function store(Request $request)
    {
        // Manual check role
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat mengakses halaman ini.');
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
            'role' => 'murid',
            'guru_id' => Auth::id()
        ]);

        return redirect()->route('dashboard')->with('success', 'Akun murid berhasil dibuat!');
    }

    // Detail murid dengan grafik
    public function show($id)
    {
        // Manual check role
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat mengakses halaman ini.');
        }

        $murid = User::where('id', $id)
            ->where('guru_id', Auth::id())
            ->with(['kebiasaan' => function($query) {
                $query->orderBy('tanggal', 'desc')->take(30);
            }])
            ->firstOrFail();

        // Siapkan data untuk chart
        $chartData = [
            'labels' => $murid->kebiasaan->pluck('tanggal')->map(function($date) {
                return $date->format('d M');
            }),
            'jam_bangun' => $murid->kebiasaan->map(function($item) {
                if (!$item->jam_bangun) return 0;
                try {
                    $time = \Carbon\Carbon::parse($item->jam_bangun);
                    return $time->hour + ($time->minute / 100);
                } catch (\Exception $e) {
                    return 0;
                }
            }),
            'jam_tidur' => $murid->kebiasaan->map(function($item) {
                if (!$item->jam_tidur) return 0;
                try {
                    $time = \Carbon\Carbon::parse($item->jam_tidur);
                    return $time->hour + ($time->minute / 100);
                } catch (\Exception $e) {
                    return 0;
                }
            }),
            'durasi_belajar' => $murid->kebiasaan->pluck('durasi_belajar')
        ];

        // Hitung statistik
        $statistik = [
            'total_data' => $murid->kebiasaan->count(),
            'rata_belajar' => $murid->kebiasaan->avg('durasi_belajar'),
            'rata_bangun' => $murid->kebiasaan->avg(function($item) {
                if (!$item->jam_bangun) return 0;
                $time = \Carbon\Carbon::parse($item->jam_bangun);
                return $time->hour + ($time->minute / 100);
            }),
            'konsistensi' => $murid->kebiasaan->count() > 0 ? 
                round(($murid->kebiasaan->count() / 30) * 100) : 0
        ];

        return view('murid.show', compact('murid', 'chartData', 'statistik'));
    }

    // API untuk data chart murid
    public function getChartData($id, Request $request)
    {
        // Manual check role
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat mengakses halaman ini.');
        }

        $murid = User::where('id', $id)
            ->where('guru_id', Auth::id())
            ->firstOrFail();

        $filter = $request->get('filter', 'month');

        $query = Kebiasaan::where('murid_id', $id);

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
        }

        $kebiasaan = $query->orderBy('tanggal', 'asc')->get();

        $data = [
            'labels' => $kebiasaan->pluck('tanggal')->map(function($date) {
                return $date->format('d M');
            }),
            'jam_bangun' => $kebiasaan->map(function($item) {
                if (!$item->jam_bangun) return 0;
                $time = \Carbon\Carbon::parse($item->jam_bangun);
                return $time->hour + ($time->minute / 100);
            }),
            'jam_tidur' => $kebiasaan->map(function($item) {
                if (!$item->jam_tidur) return 0;
                $time = \Carbon\Carbon::parse($item->jam_tidur);
                return $time->hour + ($time->minute / 100);
            }),
            'durasi_belajar' => $kebiasaan->pluck('durasi_belajar')
        ];

        return response()->json($data);
    }

    // Tampilkan form upload CSV
    public function upload()
    {
        // Manual check role
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat mengakses halaman ini.');
        }
        return view('murid.upload');
    }

    // Preview upload CSV
    public function previewUpload(Request $request)
    {
        // Manual check role
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat mengakses halaman ini.');
        }

        \Log::info('=== PREVIEW UPLOAD STARTED ===');
        \Log::info('File: ' . $request->file('csv_file')->getClientOriginalName());
        \Log::info('Kelas: ' . $request->kelas);
        
        $request->validate([
            'kelas' => 'required|string|max:10',
            'csv_file' => 'required|file|mimes:csv,txt|max:1024'
        ]);

        try {
            $file = $request->file('csv_file');
            $kelas = strtoupper($request->kelas);
            
            // Baca file CSV dengan auto-detect delimiter
            $fileContent = file($file);
            $firstLine = $fileContent[0];
            
            // Deteksi delimiter (koma atau titik koma)
            $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
            \Log::info('Detected delimiter: ' . $delimiter);
            
            $csvData = array_map(function($line) use ($delimiter) {
                return str_getcsv($line, $delimiter);
            }, $fileContent);
            
            $headers = array_shift($csvData);
            
            \Log::info('CSV Headers: ' . json_encode($headers));
            \Log::info('CSV Data count: ' . count($csvData));

            // Validasi header - terima "Nama" atau "Nama,NIS"
            $firstHeader = strtolower(trim($headers[0]));
            if ($firstHeader !== 'nama') {
                \Log::error('Invalid CSV header: ' . $headers[0]);
                return redirect()->back()->with('error', 'Format CSV tidak valid. Header pertama harus "Nama"');
            }
            
            $previewData = [];
            $usedEmails = [];
            
            foreach ($csvData as $index => $row) {
                if (!empty($row[0])) {
                    $namaLengkap = trim($row[0]);
                    
                    // Ambil nama depan (kata pertama)
                    $namaDepan = explode(' ', $namaLengkap)[0];
                    
                    // Gunakan NIS dari CSV jika ada, atau generate random
                    $nis = !empty($row[1]) ? trim($row[1]) : sprintf('%04d', rand(1000, 9999));
                    
                    // Generate email: nama-depan + NIS + @sekolah.com
                    $email = $namaDepan . $nis . '@sekolah.com';
                    
                    // Pastikan email unik
                    if (in_array($email, $usedEmails)) {
                        $counter = 1;
                        do {
                            $email = $namaDepan . $nis . $counter . '@sekolah.com';
                            $counter++;
                        } while (in_array($email, $usedEmails));
                    }
                    $usedEmails[] = $email;
                    
                    // Generate password: nama-depan + NIS + kelas
                    $password = $namaDepan . $nis . $kelas;
                    
                    $previewData[] = [
                        'name' => $namaLengkap,
                        'email' => $email,
                        'password' => $password,
                        'nis' => $nis
                    ];
                    
                    \Log::info("Row {$index}: {$namaLengkap} -> {$email}");
                }
            }
            
            \Log::info('Preview data count: ' . count($previewData));
            
            if (empty($previewData)) {
                return redirect()->back()->with('error', 'Tidak ada data yang valid dalam file CSV');
            }
            
            // Simpan di session
            session([
                'preview_data' => $previewData,
                'kelas' => $kelas
            ]);

            return redirect()->route('murid.upload')->with('success', 'Preview data berhasil! Data ditemukan: ' . count($previewData) . ' murid');
                
        } catch (\Exception $e) {
            \Log::error('Preview upload error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error membaca file: ' . $e->getMessage());
        }
    }

    // Process upload CSV
    public function processUpload(Request $request)
    {
        // Manual check role
        if (Auth::user()->role !== 'guru') {
            abort(403, 'Hanya guru yang dapat mengakses halaman ini.');
        }

        $request->validate([
            'kelas' => 'required|string|max:10'
        ]);

        $previewData = session('preview_data');
        $kelas = $request->kelas;

        if (!$previewData) {
            return redirect()->route('murid.upload')->with('error', 'Data preview tidak ditemukan. Silakan upload ulang.');
        }

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($previewData as $data) {
            try {
                // Cek email duplikat
                if (User::where('email', $data['email'])->exists()) {
                    $errorCount++;
                    $errors[] = "Email sudah terdaftar: {$data['email']}";
                    continue;
                }

                // Buat user murid
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role' => 'murid',
                    'guru_id' => Auth::id()
                ]);

                // Jika model Murid ada, buat juga data di table murid
                if (class_exists('App\Models\Murid')) {
                    Murid::create([
                        'user_id' => $user->id,
                        'nis' => $data['nis'],
                        'kelas' => $kelas
                    ]);
                }

                $successCount++;

            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Error membuat akun {$data['name']}: " . $e->getMessage();
                \Log::error("Error creating user {$data['name']}: " . $e->getMessage());
            }
        }

        // Clear session data
        session()->forget(['preview_data', 'kelas']);

        $message = "Upload selesai! Berhasil: {$successCount}, Gagal: {$errorCount}";

        if ($errorCount > 0) {
            return redirect()->route('murid.upload')->with([
                'error' => $message,
                'errors' => $errors
            ]);
        }

        return redirect()->route('murid.upload')->with('success', $message);
    }

    // ==============================================
    // RESET PASSWORD - HANYA UNTUK PENGAWAS
    // ==============================================

    /**
     * Menampilkan form reset password - HANYA PENGAWAS
     */
    public function showResetPasswordForm()
    {
        // Hanya pengawas yang bisa akses
        if (Auth::user()->role !== 'pengawas') {
            abort(403, 'Hanya pengawas yang dapat mengakses halaman reset password.');
        }

        // ✅ KEMBALIKAN SEARCH PARAMETER JIKA ADA DI SESSION
    $search = session('search', '');

    return view('murid.reset-password')
        ->with('search', $search);
    }

    /**
     * Pencarian murid berdasarkan NIS, nama, atau kelas - HANYA PENGAWAS
     */
    public function searchMurid(Request $request)
    {
        // Hanya pengawas yang bisa akses
        if (Auth::user()->role !== 'pengawas') {
            abort(403, 'Hanya pengawas yang dapat mengakses halaman reset password.');
        }

        $request->validate([
            'search' => 'required|string|min:2'
        ]);

        $search = $request->search;

        // Cari semua murid (tanpa filter guru_id karena pengawas bisa akses semua)
        $murid = User::where('role', 'murid')
            ->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->get();

        return view('murid.reset-password')
        ->with('murid', $murid)
        ->with('search', $search); // ✅ GUNAKAN with() BUKAN compact()
    }

    /**
     * Generate password baru untuk murid - HANYA PENGAWAS
     */
    public function generateNewPassword(Request $request)
    {
        // Hanya pengawas yang bisa akses
        if (Auth::user()->role !== 'pengawas') {
            abort(403, 'Hanya pengawas yang dapat melakukan reset password.');
        }

        $request->validate([
            'murid_id' => 'required|exists:users,id'
        ]);

        // Pengawas bisa reset password semua murid (tanpa filter guru_id)
        $user = User::where('id', $request->murid_id)
            ->where('role', 'murid')
            ->firstOrFail();
        
        // Generate password baru (8 karakter acak)
        $newPassword = Str::random(8);
        
        // Update password user
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

         // ✅ SIMPAN SEARCH PARAMETER
    $search = $request->search;

    return redirect()->route('pengawas.reset-password')
        ->with('success', 'Password berhasil direset!')
        ->with('new_password', $newPassword)
        ->with('murid_name', $user->name)
        ->with('search', $search); // ✅ KEMBALIKAN SEARCH PARAMETER
    }

    /**
     * Reset password dengan password custom - HANYA PENGAWAS
     */
    public function resetPasswordAction(Request $request, $id)
    {
        // Hanya pengawas yang bisa akses
        if (Auth::user()->role !== 'pengawas') {
            abort(403, 'Hanya pengawas yang dapat melakukan reset password.');
        }

        $request->validate([
            'new_password' => 'required|string|min:6'
        ]);

        // Pengawas bisa reset password semua murid (tanpa filter guru_id)
        $user = User::where('id', $id)
            ->where('role', 'murid')
            ->firstOrFail();
        
        // Update password user
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

         // ✅ SIMPAN SEARCH PARAMETER agar tidak hilang
    $search = $request->search;

    return redirect()->route('pengawas.reset-password')
        ->with('success', 'Password berhasil direset!')
        ->with('murid_name', $user->name)
        ->with('search', $search); // ✅ KEMBALIKAN SEARCH PARAMETER
    }
}