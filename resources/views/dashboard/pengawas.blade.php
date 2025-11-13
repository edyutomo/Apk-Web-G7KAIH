@extends('layouts.app')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header dan Quick Actions -->
<div class="bg-white rounded-lg shadow p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-3 sm:space-y-0">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Dashboard Pengawas</h1>
            <p class="text-gray-600 text-sm sm:text-base">Selamat datang, {{ $user->name }}!</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
            <!-- Reset Password Murid -->
            <a href="{{ route('pengawas.reset-password') }}" 
               class="bg-blue-600 text-white px-4 py-3 sm:py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center text-sm sm:text-base transition-colors">
                <span class="mr-2">🔐</span> Reset Password Murid
            </a>
            
            <!-- Reset Password Guru -->
            <a href="{{ route('pengawas.reset-password-guru') }}" 
               class="bg-green-600 text-white px-4 py-3 sm:py-2 rounded-lg hover:bg-green-700 flex items-center justify-center text-sm sm:text-base transition-colors">
                <span class="mr-2">👨‍🏫</span> Reset Password Guru
            </a>
            
            <!-- Tambah Guru -->
            <a href="{{ route('guru.create') }}" 
               class="bg-purple-600 text-white px-4 py-3 sm:py-2 rounded-lg hover:bg-purple-700 flex items-center justify-center text-sm sm:text-base transition-colors">
                <span class="mr-2">➕</span> Tambah Guru
            </a>
        </div>
    </div>

        <!-- Statistik Keseluruhan Sekolah -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 mt-4">
            <div class="bg-purple-50 rounded-lg p-3 sm:p-4">
                <div class="text-lg sm:text-2xl font-bold text-purple-600">{{ $totalGuru }}</div>
                <div class="text-xs sm:text-sm text-purple-700">Total Guru</div>
            </div>
            <div class="bg-blue-50 rounded-lg p-3 sm:p-4">
                <div class="text-lg sm:text-2xl font-bold text-blue-600">{{ $totalMurid }}</div>
                <div class="text-xs sm:text-sm text-blue-700">Total Murid</div>
            </div>
            <div class="bg-green-50 rounded-lg p-3 sm:p-4">
                <div class="text-lg sm:text-2xl font-bold text-green-600">{{ $totalMuridAktif }}</div>
                <div class="text-xs sm:text-sm text-green-700">Murid Aktif</div>
            </div>
            <div class="bg-orange-50 rounded-lg p-3 sm:p-4">
                <div class="text-lg sm:text-2xl font-bold text-orange-600">{{ $totalDataKebiasaan }}</div>
                <div class="text-xs sm:text-sm text-orange-700">Total Data</div>
            </div>
            <div class="bg-red-50 rounded-lg p-3 sm:p-4">
                <div class="text-lg sm:text-2xl font-bold text-red-600">{{ round($rataBelajarKeseluruhan ?? 0) }}m</div>
                <div class="text-xs sm:text-sm text-red-700">Rata Belajar</div>
            </div>
        </div>
    </div>

    <!-- Analisis Cepat -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Guru dengan Murid Terbanyak -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-gray-800">🏆 Guru Paling Produktif</h2>
            @php
                $guruProduktif = $guruList->sortByDesc('murid_count')->first();
            @endphp
            @if($guruProduktif && $guruProduktif->murid_count > 0)
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-purple-600 font-bold text-sm sm:text-base">👨‍🏫</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 text-sm sm:text-base">{{ $guruProduktif->name }}</h3>
                        <p class="text-gray-600 text-xs sm:text-sm">{{ $guruProduktif->murid_count }} murid bimbingan</p>
                    </div>
                    <div class="text-right">
                        <span class="bg-purple-100 text-purple-800 px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium">
                            {{ round(($guruProduktif->murid_count / $totalMurid) * 100) }}%
                        </span>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-sm sm:text-base">Belum ada data yang cukup</p>
            @endif
        </div>

        <!-- Statistik Aktivitas -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-gray-800">📈 Statistik Aktivitas</h2>
            <div class="space-y-2 sm:space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-xs sm:text-sm">Persentase Murid Aktif</span>
                    <span class="font-semibold text-green-600 text-xs sm:text-sm">
                        {{ $totalMurid > 0 ? round(($totalMuridAktif / $totalMurid) * 100) : 0 }}%
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-xs sm:text-sm">Rata Data per Murid</span>
                    <span class="font-semibold text-blue-600 text-xs sm:text-sm">
                        {{ $totalMurid > 0 ? round($totalDataKebiasaan / $totalMurid) : 0 }} data
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-xs sm:text-sm">Rasio Guru:Murid</span>
                    <span class="font-semibold text-purple-600 text-xs sm:text-sm">
                        1:{{ $totalGuru > 0 ? round($totalMurid / $totalGuru) : 0 }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Pengawas Lainnya -->
    @if($pengawasList->count() > 0)
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-gray-800">👥 Pengawas Lainnya</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($pengawasList as $pengawas)
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <span class="text-purple-600 font-semibold text-sm">👨‍💼</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800 text-sm sm:text-base">{{ $pengawas->name }}</h3>
                    <p class="text-gray-600 text-xs sm:text-sm">{{ $pengawas->email }}</p>
                </div>
                <div class="text-right">
                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs font-medium">
                        Pengawas
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Daftar Guru dan Murid -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 gap-3 sm:gap-0">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800">👨‍🏫 Daftar Guru dan Murid</h2>
            <div class="flex space-x-2">
                <input type="text" id="searchGuru" placeholder="Cari nama guru..." 
                       class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base w-full sm:w-auto">
            </div>
        </div>
        
        @if($guruList->count() > 0)
            <div class="table-responsive overflow-x-auto">
                <table class="w-full min-w-full table-auto" id="guruTable">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Nama Guru</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Jumlah Murid</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Murid Aktif</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Persentase</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($guruList as $guru)
                        <tr class="guru-row hover:bg-gray-50" data-name="{{ strtolower($guru->name) }}">
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                <div class="font-medium text-gray-900 text-xs sm:text-sm">{{ $guru->name }}</div>
                            </td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm text-gray-600">{{ $guru->email }}</td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                <span class="bg-blue-100 text-blue-800 px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                    {{ $guru->murid_count }} murid
                                </span>
                            </td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                @php
                                    $muridAktif = $guru->murid->where('kebiasaan_count', '>', 0)->count();
                                @endphp
                                <span class="bg-green-100 text-green-800 px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                    {{ $muridAktif }} aktif
                                </span>
                            </td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                @php
                                    $persentase = $guru->murid_count > 0 ? round(($muridAktif / $guru->murid_count) * 100) : 0;
                                @endphp
                                <div class="flex items-center space-x-2">
                                    <div class="w-12 sm:w-16 bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $persentase }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-600 w-8">{{ $persentase }}%</span>
                                </div>
                            </td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                <div class="flex space-x-1 sm:space-x-2">
                                    <a href="{{ route('guru.show', $guru->id) }}" 
                                       class="bg-blue-500 text-white px-2 py-1 sm:px-3 sm:py-1 rounded text-xs sm:text-sm hover:bg-blue-600 flex items-center whitespace-nowrap">
                                        <span class="mr-1 hidden sm:inline">📊</span> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary -->
            <div class="mt-3 sm:mt-4 p-3 sm:p-4 bg-gray-50 rounded-lg">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center text-xs sm:text-sm text-gray-600 space-y-1 sm:space-y-0">
                    <span>Total: {{ $guruList->count() }} guru</span>
                    <span>Total murid: {{ $totalMurid }} siswa</span>
                    <span>Rata-rata: {{ $totalGuru > 0 ? round($totalMurid / $totalGuru) : 0 }} murid/guru</span>
                </div>
            </div>
        @else
            <div class="text-center py-6 sm:py-8">
                <div class="text-gray-400 text-4xl sm:text-6xl mb-3 sm:mb-4">👨‍🏫</div>
                <p class="text-gray-500 text-sm sm:text-lg">Belum ada data guru.</p>
                <p class="text-gray-400 text-xs sm:text-sm mt-1 sm:mt-2">Mulai dengan menambahkan guru baru.</p>
                <a href="{{ route('guru.create') }}" class="inline-block mt-3 sm:mt-4 bg-purple-600 text-white px-4 sm:px-6 py-2 rounded-lg hover:bg-purple-700 text-sm sm:text-base">
                    👨‍🏫 Tambah Guru Pertama
                </a>
            </div>
        @endif
    </div>

    <!-- Laporan Keseluruhan -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-gray-800">📋 Laporan Keseluruhan Sekolah</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <!-- Distribusi Murid per Guru -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">📊 Distribusi Murid per Guru</h3>
                <div class="space-y-2">
                    @foreach($guruList as $guru)
                    <div class="flex justify-between items-center">
                        <span class="text-xs sm:text-sm text-gray-600 truncate mr-2" style="max-width: 120px;">{{ $guru->name }}</span>
                        <div class="flex items-center space-x-2 flex-1">
                            <div class="w-16 sm:w-24 bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" 
                                     style="width: {{ $totalMurid > 0 ? round(($guru->murid_count / $totalMurid) * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500 w-6 text-right">{{ $guru->murid_count }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Analisis Kebiasaan Belajar -->
            <div>
                <h3 class="font-semibold text-gray-700 mb-2 text-sm sm:text-base">📚 Analisis Kebiasaan Belajar</h3>
                <div class="space-y-2 sm:space-y-3">
                    <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
                        <span class="text-blue-700 text-xs sm:text-sm">Rata Durasi Belajar</span>
                        <span class="font-semibold text-blue-800 text-xs sm:text-sm">{{ round($rataBelajarKeseluruhan ?? 0) }} menit/hari</span>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-green-50 rounded">
                        <span class="text-green-700 text-xs sm:text-sm">Tingkat Partisipasi</span>
                        <span class="font-semibold text-green-800 text-xs sm:text-sm">
                            {{ $totalMurid > 0 ? round(($totalMuridAktif / $totalMurid) * 100) : 0 }}%
                        </span>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-orange-50 rounded">
                        <span class="text-orange-700 text-xs sm:text-sm">Kepadatan Data</span>
                        <span class="font-semibold text-orange-800 text-xs sm:text-sm">
                            {{ $totalMurid > 0 ? round($totalDataKebiasaan / $totalMurid) : 0 }} data/murid
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Pengawas -->
    <div class="bg-purple-50 rounded-lg shadow p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-purple-800">ℹ️ Info Pengawas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div>
                <h3 class="font-semibold text-purple-700 mb-2 text-sm sm:text-base">Akses dan Kewenangan:</h3>
                <ul class="text-purple-600 text-xs sm:text-sm list-disc list-inside space-y-1">
                    <li>Memantau semua guru dan murid</li>
                    <li>Membuat akun guru baru</li>
                    <li>Melihat laporan keseluruhan sekolah</li>
                    <li>Menganalisis data kebiasaan siswa</li>
                    <li>Monitoring progress belajar</li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold text-purple-700 mb-2 text-sm sm:text-base">Fitur Utama:</h3>
                <ul class="text-purple-600 text-xs sm:text-sm list-disc list-inside space-y-1">
                    <li>Dashboard statistik real-time</li>
                    <li>Analisis performa guru</li>
                    <li>Monitoring kebiasaan belajar</li>
                    <li>Laporan periodik otomatis</li>
                    <li>Manajemen pengguna sistem</li>
                </ul>
            </div>
        </div>
        <div class="mt-3 sm:mt-4 p-2 sm:p-3 bg-purple-100 rounded-lg">
            <p class="text-purple-800 text-xs sm:text-sm font-semibold text-center">
                🎯 Anda memiliki akses penuh untuk memantau seluruh aktivitas di sistem G7KAIH.
            </p>
        </div>
    </div>
</div>

<script>
    // Search functionality
    document.getElementById('searchGuru').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        document.querySelectorAll('.guru-row').forEach(row => {
            const name = row.getAttribute('data-name');
            
            if (name.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

<style>
    .guru-row {
        transition: all 0.3s ease;
    }

    /* Improve table responsiveness */
    @media (max-width: 640px) {
        .table-responsive {
            font-size: 11px;
        }
        
        table {
            min-width: 700px;
        }
        
        .text-truncate {
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }

    /* Touch improvements */
    @media (max-width: 768px) {
        button, .filter-btn {
            min-height: 44px;
        }
        
        input, select {
            font-size: 16px;
        }
    }
</style>
@endsection