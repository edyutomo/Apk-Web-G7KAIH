@extends('layouts.app')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Detail Murid: {{ $murid->name }}</h1>
                <p class="text-gray-600 text-sm sm:text-base">{{ $murid->email }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-sm sm:text-base text-center">
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-lg shadow p-3 sm:p-4 text-center">
            <div class="text-lg sm:text-2xl font-bold text-blue-600">{{ $statistik['total_data'] }}</div>
            <div class="text-xs sm:text-sm text-gray-600">Total Data</div>
        </div>
        <div class="bg-white rounded-lg shadow p-3 sm:p-4 text-center">
            <div class="text-lg sm:text-2xl font-bold text-green-600">{{ round($statistik['rata_belajar']) }}m</div>
            <div class="text-xs sm:text-sm text-gray-600">Rata Belajar</div>
        </div>
        <div class="bg-white rounded-lg shadow p-3 sm:p-4 text-center">
            <div class="text-lg sm:text-2xl font-bold text-purple-600">{{ number_format($statistik['rata_bangun'], 1) }}</div>
            <div class="text-xs sm:text-sm text-gray-600">Rata Bangun</div>
        </div>
        <div class="bg-white rounded-lg shadow p-3 sm:p-4 text-center">
            <div class="text-lg sm:text-2xl font-bold text-orange-600">{{ $statistik['konsistensi'] }}%</div>
            <div class="text-xs sm:text-sm text-gray-600">Konsistensi</div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-2 sm:space-y-0">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800">Grafik Progress Belajar</h2>
            <div class="flex space-x-1 sm:space-x-2">
                <button onclick="updateChart('week')" 
                        class="flex-1 sm:flex-none px-2 py-1 sm:px-3 sm:py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 filter-btn text-xs sm:text-sm">
                    Minggu
                </button>
                <button onclick="updateChart('month')" 
                        class="flex-1 sm:flex-none px-2 py-1 sm:px-3 sm:py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 filter-btn text-xs sm:text-sm">
                    Bulan
                </button>
                <button onclick="updateChart('year')" 
                        class="flex-1 sm:flex-none px-2 py-1 sm:px-3 sm:py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 filter-btn text-xs sm:text-sm">
                    Tahun
                </button>
            </div>
        </div>

        <div class="space-y-4 sm:space-y-6">
            <div>
                <h3 class="font-semibold mb-2 text-gray-700 text-sm sm:text-base">Jam Bangun Tidur</h3>
                <div class="chart-container" style="height: 120px; width: 100%;">
                    <canvas id="chartBangun"></canvas>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2 text-gray-700 text-sm sm:text-base">Jam Tidur Malam</h3>
                <div class="chart-container" style="height: 120px; width: 100%;">
                    <canvas id="chartTidur"></canvas>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-2 text-gray-700 text-sm sm:text-base">Durasi Belajar (menit)</h3>
                <div class="chart-container" style="height: 120px; width: 100%;">
                    <canvas id="chartBelajar"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Terbaru -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-gray-800">Data 7 Hari Terakhir</h2>
        
        @if($murid->kebiasaan->count() > 0)
        <div class="table-responsive overflow-x-auto">
            <table class="w-full min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Bangun</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Tidur</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Belajar</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($murid->kebiasaan->take(7) as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm">{{ $data->tanggal->format('d/m/Y') }}</td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm font-mono">{{ $data->jam_bangun }}</td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm font-mono">{{ $data->jam_tidur }}</td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm">
                            <span class="bg-blue-100 text-blue-800 px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                {{ $data->durasi_belajar }}m
                            </span>
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm">
                            @php
                                $durasi = $data->durasi_belajar;
                                if ($durasi >= 120) {
                                    $status = 'Excel';
                                    $color = 'bg-green-100 text-green-800';
                                } elseif ($durasi >= 60) {
                                    $status = 'Good';
                                    $color = 'bg-blue-100 text-blue-800';
                                } else {
                                    $status = 'Perlu';
                                    $color = 'bg-yellow-100 text-yellow-800';
                                }
                            @endphp
                            <span class="{{ $color }} px-1 py-0.5 sm:px-2 sm:py-1 rounded-full text-xs font-medium">
                                {{ $status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-gray-500 text-center py-4 text-sm">Belum ada data kebiasaan.</p>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chartBangun, chartTidur, chartBelajar;
const muridId = {{ $murid->id }};

function updateChart(filter) {
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-blue-500', 'text-white');
        btn.classList.add('bg-blue-100', 'text-blue-700');
    });
    event.target.classList.add('bg-blue-500', 'text-white');
    event.target.classList.remove('bg-blue-100', 'text-blue-700');

    fetch(`/api/murid/${muridId}/chart?filter=${filter}`)
        .then(response => response.json())
        .then(data => {
            updateChartData(data);
        })
        .catch(error => console.error('Error:', error));
}

function updateChartData(data) {
    chartBangun.data.labels = data.labels;
    chartBangun.data.datasets[0].data = data.jam_bangun;
    chartBangun.update();

    chartTidur.data.labels = data.labels;
    chartTidur.data.datasets[0].data = data.jam_tidur;
    chartTidur.update();

    chartBelajar.data.labels = data.labels;
    chartBelajar.data.datasets[0].data = data.durasi_belajar;
    chartBelajar.update();
}

// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    const initialData = @json($chartData);

    const mobileOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { maxRotation: 45, minRotation: 45, font: { size: window.innerWidth < 640 ? 10 : 12 } } },
            y: { ticks: { font: { size: window.innerWidth < 640 ? 10 : 12 } } }
        }
    };

    // Chart Jam Bangun
    const ctxBangun = document.getElementById('chartBangun');
    if (ctxBangun) {
        chartBangun = new Chart(ctxBangun.getContext('2d'), {
            type: 'line',
            data: {
                labels: initialData.labels,
                datasets: [{
                    label: 'Jam Bangun',
                    data: initialData.jam_bangun,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: window.innerWidth < 640 ? 2 : 3,
                }]
            },
            options: mobileOptions
        });
    }

    // Chart Jam Tidur
    const ctxTidur = document.getElementById('chartTidur');
    if (ctxTidur) {
        chartTidur = new Chart(ctxTidur.getContext('2d'), {
            type: 'line',
            data: {
                labels: initialData.labels,
                datasets: [{
                    label: 'Jam Tidur',
                    data: initialData.jam_tidur,
                    borderColor: 'rgb(139, 92, 246)',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: window.innerWidth < 640 ? 2 : 3,
                }]
            },
            options: mobileOptions
        });
    }

    // Chart Durasi Belajar
    const ctxBelajar = document.getElementById('chartBelajar');
    if (ctxBelajar) {
        chartBelajar = new Chart(ctxBelajar.getContext('2d'), {
            type: 'line',
            data: {
                labels: initialData.labels,
                datasets: [{
                    label: 'Durasi Belajar',
                    data: initialData.durasi_belajar,
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: window.innerWidth < 640 ? 2 : 3,
                }]
            },
            options: mobileOptions
        });
    }
});
</script>

<style>
.filter-btn {
    transition: all 0.3s ease;
    min-height: 32px;
}

@media (max-width: 640px) {
    .table-responsive {
        font-size: 11px;
    }
    
    table {
        min-width: 500px;
    }
    
    .chart-container {
        height: 100px !important;
    }
}
</style>
@endsection