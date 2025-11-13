@extends('layouts.app')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Detail Guru: {{ $guru->name }}</h1>
                <p class="text-gray-600 text-sm sm:text-base">{{ $guru->email }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-sm sm:text-base text-center">
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Statistik Guru -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-lg shadow p-3 sm:p-4 text-center">
            <div class="text-lg sm:text-2xl font-bold text-blue-600">{{ $statistik['total_murid'] }}</div>
            <div class="text-xs sm:text-sm text-gray-600">Total Murid</div>
        </div>
        <div class="bg-white rounded-lg shadow p-3 sm:p-4 text-center">
            <div class="text-lg sm:text-2xl font-bold text-green-600">{{ $statistik['murid_aktif'] }}</div>
            <div class="text-xs sm:text-sm text-gray-600">Murid Aktif</div>
        </div>
        <div class="bg-white rounded-lg shadow p-3 sm:p-4 text-center">
            <div class="text-lg sm:text-2xl font-bold text-purple-600">{{ $statistik['total_data_kebiasaan'] }}</div>
            <div class="text-xs sm:text-sm text-gray-600">Total Data</div>
        </div>
        <div class="bg-white rounded-lg shadow p-3 sm:p-4 text-center">
            <div class="text-lg sm:text-2xl font-bold text-orange-600">{{ round($statistik['rata_belajar'] ?? 0) }}m</div>
            <div class="text-xs sm:text-sm text-gray-600">Rata Belajar</div>
        </div>
    </div>

    <!-- Daftar Murid -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-gray-800">Daftar Murid Bimbingan</h2>
        
        @if($guru->murid->count() > 0)
            <div class="table-responsive overflow-x-auto">
                <table class="w-full min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Nama Murid</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Data Kebiasaan</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Data Terbaru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($guru->murid as $murid)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                <div class="font-medium text-gray-900 text-xs sm:text-sm">{{ $murid->name }}</div>
                            </td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm text-gray-600">{{ $murid->email }}</td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                <span class="bg-blue-100 text-blue-800 px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                    {{ $murid->kebiasaan_count }} data
                                </span>
                            </td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3">
                                @if($murid->kebiasaan_count > 0)
                                    <span class="bg-green-100 text-green-800 px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                        Aktif
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                        Belum Input
                                    </span>
                                @endif
                            </td>
                            <td class="px-2 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm text-gray-600">
                                @if($murid->kebiasaan->count() > 0)
                                    {{ $murid->kebiasaan->first()->tanggal->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-4 text-sm">Belum ada murid yang dibimbing.</p>
        @endif
    </div>
</div>

<style>
@media (max-width: 640px) {
    .table-responsive {
        font-size: 11px;
    }
    
    table {
        min-width: 600px;
    }
}
</style>
@endsection