@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
    <!-- Kolom 1: Form Input dan Statistik -->
    <div class="lg:col-span-1 space-y-4 sm:space-y-6">
        <!-- Form Input Data -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-gray-800">📝 Input Kebiasaan</h2>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('kebiasaan.store') }}" method="POST">
                @csrf
                <div class="space-y-3 sm:space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">⏰ Bangun Tidur</label>
                        <input type="time" name="jam_bangun" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">🌙 Tidur Malam</label>
                        <input type="time" name="jam_tidur" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">📚 Durasi Belajar (menit)</label>
                        <input type="number" name="durasi_belajar" min="0" max="1440" placeholder="Contoh: 120"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Durasi dalam menit</p>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold text-sm sm:text-base">
                        💾 Simpan Data
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistik Ringkas -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-gray-800">📊 Statistik</h2>
            
            <div class="space-y-2 sm:space-y-3">
                <div class="flex justify-between items-center p-2 sm:p-3 bg-blue-50 rounded-lg">
                    <span class="text-blue-700 font-medium text-sm">Total Data</span>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full font-bold text-sm">
                        {{ $totalData }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center p-2 sm:p-3 bg-green-50 rounded-lg">
                    <span class="text-green-700 font-medium text-sm">Rata Belajar</span>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full font-bold text-sm">
                        {{ $rataBelajar ? round($rataBelajar) : 0 }}m
                    </span>
                </div>
                
                <div class="flex justify-between items-center p-2 sm:p-3 bg-purple-50 rounded-lg">
                    <span class="text-purple-700 font-medium text-sm">Konsistensi</span>
                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full font-bold text-sm">
                        {{ $totalData > 0 ? round(($totalData / 30) * 100) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom 2: Grafik dan Data -->
    <div class="lg:col-span-2 space-y-4 sm:space-y-6">
        <!-- Filter dan Grafik -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 sm:mb-6 space-y-2 sm:space-y-0">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">📈 Grafik Kebiasaan</h2>
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

            <!-- Chart Container -->
            <div class="space-y-4 sm:space-y-6">
                <div>
                    <h3 class="font-semibold mb-2 text-gray-700 text-sm sm:text-base">🕒 Jam Bangun</h3>
                    <div class="chart-container" style="height: 120px; width: 100%;">
                        <canvas id="chartBangun"></canvas>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-2 text-gray-700 text-sm sm:text-base">🌙 Jam Tidur</h3>
                    <div class="chart-container" style="height: 120px; width: 100%;">
                        <canvas id="chartTidur"></canvas>
                    </div>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-2 text-gray-700 text-sm sm:text-base">📚 Durasi Belajar</h3>
                    <div class="chart-container" style="height: 120px; width: 100%;">
                        <canvas id="chartBelajar"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data Terbaru -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-gray-800">📋 Data Terbaru</h2>
            
            @if($kebiasaan->count() > 0)
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
                        @foreach($kebiasaan as $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm">{{ $data->tanggal->format('d/m') }}</td>
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
            <div class="text-center py-6">
                <div class="text-gray-400 text-4xl mb-3">📊</div>
                <p class="text-gray-500 text-sm">Belum ada data kebiasaan.</p>
                <p class="text-gray-400 text-xs mt-1">Mulai isi data harian Anda.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Load Chart.js dari CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Variabel global untuk chart
let chartBangun, chartTidur, chartBelajar;

// Fungsi untuk update chart berdasarkan filter
function updateChart(filter) {
    // Update active button
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-blue-500', 'text-white');
        btn.classList.add('bg-blue-100', 'text-blue-700');
    });
    event.target.classList.add('bg-blue-500', 'text-white');
    event.target.classList.remove('bg-blue-100', 'text-blue-700');

    // Fetch data dari API
    fetch(`/api/kebiasaan/chart?filter=${filter}`)
        .then(response => response.json())
        .then(data => {
            updateChartData(data);
        })
        .catch(error => console.error('Error:', error));
}

// Fungsi untuk update data chart
function updateChartData(data) {
    // Update Chart Jam Bangun
    chartBangun.data.labels = data.labels;
    chartBangun.data.datasets[0].data = data.jam_bangun;
    chartBangun.update();

    // Update Chart Jam Tidur
    chartTidur.data.labels = data.labels;
    chartTidur.data.datasets[0].data = data.jam_tidur;
    chartTidur.update();

    // Update Chart Durasi Belajar
    chartBelajar.data.labels = data.labels;
    chartBelajar.data.datasets[0].data = data.durasi_belajar;
    chartBelajar.update();
}

// Initialize charts ketika halaman load
document.addEventListener('DOMContentLoaded', function() {
    // Data awal dari PHP - dengan fallback yang aman
    const initialData = {
        labels: @json($chartData['labels'] ?? []),
        jam_bangun: @json($chartData['jam_bangun'] ?? []),
        jam_tidur: @json($chartData['jam_tidur'] ?? []),
        durasi_belajar: @json($chartData['durasi_belajar'] ?? [])
    };

    // Jika tidak ada data, tampilkan pesan dan stop execution
    if (initialData.labels.length === 0) {
        document.querySelectorAll('.chart-container').forEach(container => {
            const canvas = container.querySelector('canvas');
            if (canvas) {
                canvas.style.display = 'none';
            }
            container.innerHTML += '<div class="text-center text-gray-500 py-8 text-sm">Belum ada data untuk ditampilkan. Mulai dengan mengisi data kebiasaan harian.</div>';
        });
        return;
    }

    // Chart configuration untuk mobile
    const mobileOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                mode: 'index',
                intersect: false,
            }
        },
        scales: {
            x: {
                ticks: {
                    maxRotation: 45,
                    minRotation: 45,
                    font: {
                        size: window.innerWidth < 640 ? 10 : 12
                    }
                }
            },
            y: {
                ticks: {
                    font: {
                        size: window.innerWidth < 640 ? 10 : 12
                    }
                }
            }
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

    // Set filter default ke bulan ini
    updateChart('month');
});

// Handle window resize untuk chart
window.addEventListener('resize', function() {
    if (chartBangun) chartBangun.resize();
    if (chartTidur) chartTidur.resize();
    if (chartBelajar) chartBelajar.resize();
});
</script>

<style>
.filter-btn {
    transition: all 0.3s ease;
    min-height: 32px;
}

.chart-container {
    position: relative;
    margin: auto;
}

/* Improve table responsiveness */
@media (max-width: 640px) {
    .table-responsive {
        font-size: 12px;
    }
    
    table {
        min-width: 500px;
    }
    
    .chart-container {
        height: 100px !important;
    }
}

/* Touch improvements */
@media (max-width: 768px) {
    button, .filter-btn {
        min-height: 44px;
    }
    
    input, select {
        font-size: 16px; /* Prevent zoom on iOS */
    }
}
</style>
@endsection