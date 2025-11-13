@extends('layouts.app')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header dan Quick Actions -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-3 sm:space-y-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Dashboard Guru</h1>
                <p class="text-gray-600 text-sm sm:text-base">Selamat datang, {{ $user->name }}!</p>
            </div>
            <a href="{{ route('murid.create') }}" 
               class="bg-green-600 text-white px-4 py-3 sm:py-2 rounded-lg hover:bg-green-700 flex items-center justify-center text-sm sm:text-base">
                <span class="mr-2">+</span> Tambah Murid
            </a>
        </div>

        <!-- Statistik Cepat -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mt-4">
            <div class="bg-blue-50 rounded-lg p-3 sm:p-4">
                <div class="text-lg sm:text-2xl font-bold text-blue-600">{{ $muridList->count() }}</div>
                <div class="text-xs sm:text-sm text-blue-700">Total Murid</div>
            </div>
            <div class="bg-green-50 rounded-lg p-3 sm:p-4">
                <div class="text-lg sm:text-2xl font-bold text-green-600">
                    {{ $muridList->where('total_data', '>', 0)->count() }}
                </div>
                <div class="text-xs sm:text-sm text-green-700">Murid Aktif</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-3 sm:p-4">
                <div class="text-lg sm:text-2xl font-bold text-purple-600">
                    {{ round($muridList->avg('rata_belajar') ?? 0) }}m
                </div>
                <div class="text-xs sm:text-sm text-purple-700">Rata Belajar</div>
            </div>
            <div class="bg-orange-50 rounded-lg p-3 sm:p-4">
                <div class="text-lg sm:text-2xl font-bold text-orange-600">
                    {{ $muridList->sum('total_data') }}
                </div>
                <div class="text-xs sm:text-sm text-orange-700">Total Data</div>
            </div>
        </div>
    </div>

    <!-- Search dan Filter -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Cari nama murid..." 
                       class="w-full px-3 py-2 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base">
            </div>
            <div class="flex space-x-2">
                <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base w-full sm:w-auto">
                    <option value="all">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Belum Aktif</option>
                </select>
                <button onclick="resetFilters()" class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm sm:text-base whitespace-nowrap">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Daftar Murid -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-gray-800">Daftar Murid Bimbingan</h2>
        
        @if($muridList->count() > 0)
            <div class="table-responsive overflow-x-auto">
                <table class="w-full min-w-full table-auto" id="muridTable">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Nama Murid</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Total Data</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Rata Belajar</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($muridList as $murid)
                        <tr class="murid-row hover:bg-gray-50" 
                            data-name="{{ strtolower($murid->name) }}"
                            data-status="{{ $murid->total_data > 0 ? 'active' : 'inactive' }}">
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                <div class="font-medium text-gray-900 text-xs sm:text-sm">{{ $murid->name }}</div>
                            </td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm text-gray-600">{{ $murid->email }}</td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                <span class="bg-blue-100 text-blue-800 px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                    {{ $murid->total_data }} data
                                </span>
                            </td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                @if($murid->rata_belajar)
                                    <span class="bg-green-100 text-green-800 px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                        {{ round($murid->rata_belajar) }}m
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                @if($murid->total_data > 0)
                                    <span class="bg-green-100 text-green-800 px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                        Aktif
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                        Belum Input
                                    </span>
                                @endif
                            </td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                <div class="flex space-x-1 sm:space-x-2">
                                    <a href="{{ route('murid.show', $murid->id) }}" 
                                       class="bg-blue-500 text-white px-2 py-1 sm:px-3 sm:py-1 rounded text-xs sm:text-sm hover:bg-blue-600 flex items-center whitespace-nowrap">
                                        <span class="mr-1 hidden sm:inline">📊</span> Monitor
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
                    <span>Total: {{ $muridList->count() }} murid</span>
                    <span>Aktif: {{ $muridList->where('total_data', '>', 0)->count() }} murid</span>
                    <span>Rata belajar: {{ round($muridList->avg('rata_belajar') ?? 0) }} menit</span>
                </div>
            </div>
        @else
            <div class="text-center py-6 sm:py-8">
                <div class="text-gray-400 text-4xl sm:text-6xl mb-3 sm:mb-4">👨‍🎓</div>
                <p class="text-gray-500 text-sm sm:text-lg">Belum ada murid yang dibimbing.</p>
                <p class="text-gray-400 text-xs sm:text-sm mt-1 sm:mt-2">Mulai dengan menambahkan murid baru.</p>
                <a href="{{ route('murid.create') }}" class="inline-block mt-3 sm:mt-4 bg-green-600 text-white px-4 sm:px-6 py-2 rounded-lg hover:bg-green-700 text-sm sm:text-base">
                    + Tambah Murid Pertama
                </a>
            </div>
        @endif
    </div>

    <!-- Analisis Cepat -->
    @if($muridList->where('total_data', '>', 0)->count() > 0)
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-gray-800">Analisis Kebiasaan Belajar</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <!-- Murid dengan Belajar Terbanyak -->
            <div class="bg-blue-50 rounded-lg p-3 sm:p-4">
                <h3 class="font-semibold text-blue-800 mb-2 text-sm sm:text-base">🏆 Murid Paling Rajin</h3>
                @php
                    $muridRajin = $muridList->where('rata_belajar', '>', 0)->sortByDesc('rata_belajar')->first();
                @endphp
                @if($muridRajin)
                    <p class="text-blue-700 text-sm sm:text-base">{{ $muridRajin->name }}</p>
                    <p class="text-blue-600 text-xs sm:text-sm">Rata-rata: {{ round($muridRajin->rata_belajar) }} menit/hari</p>
                @else
                    <p class="text-blue-600 text-xs sm:text-sm">Belum ada data cukup</p>
                @endif
            </div>

            <!-- Murid yang Perlu Perhatian -->
            <div class="bg-orange-50 rounded-lg p-3 sm:p-4">
                <h3 class="font-semibold text-orange-800 mb-2 text-sm sm:text-base">💡 Perlu Perhatian</h3>
                @php
                    $muridPerhatian = $muridList->where('rata_belajar', '>', 0)->sortBy('rata_belajar')->first();
                @endphp
                @if($muridPerhatian && $muridPerhatian->rata_belajar < 60)
                    <p class="text-orange-700 text-sm sm:text-base">{{ $muridPerhatian->name }}</p>
                    <p class="text-orange-600 text-xs sm:text-sm">Rata-rata: {{ round($muridPerhatian->rata_belajar) }} menit/hari</p>
                @else
                    <p class="text-orange-600 text-xs sm:text-sm">Semua murid baik</p>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Filter dan Search functionality
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = 'all';
    filterMurid();
}

function filterMurid() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    
    document.querySelectorAll('.murid-row').forEach(row => {
        const name = row.getAttribute('data-name');
        const status = row.getAttribute('data-status');
        
        const nameMatch = name.includes(searchTerm);
        const statusMatch = statusFilter === 'all' || status === statusFilter;
        
        if (nameMatch && statusMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Event listeners
document.getElementById('searchInput').addEventListener('input', filterMurid);
document.getElementById('statusFilter').addEventListener('change', filterMurid);

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    filterMurid();
});
</script>

<style>
.murid-row {
    transition: all 0.3s ease;
}

/* Improve table responsiveness */
@media (max-width: 640px) {
    .table-responsive {
        font-size: 11px;
    }
    
    table {
        min-width: 600px;
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