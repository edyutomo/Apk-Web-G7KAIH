@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 space-y-3 sm:space-y-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Upload CSV Murid</h1>
        <div class="flex space-x-2">
            <a href="{{ route('murid.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm sm:text-base text-center flex items-center">
                <span class="mr-2">➕</span> Tambah Manual
            </a>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-sm sm:text-base text-center">
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 sm:px-4 sm:py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 sm:px-4 sm:py-3 rounded mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if(session('preview_data'))
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 sm:p-6 mb-6">
        <h2 class="text-lg sm:text-xl font-semibold text-yellow-800 mb-4">👥 Preview Data Murid</h2>
        
        <div class="mb-4">
            <div class="flex items-center space-x-4 mb-3">
                <span class="text-sm font-medium text-yellow-700">Kelas: {{ session('kelas') }}</span>
                <span class="text-sm font-medium text-yellow-700">Jumlah Murid: {{ count(session('preview_data')) }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
    <table class="w-full min-w-full text-sm">
        <thead>
            <tr class="bg-yellow-100">
                <th class="px-3 py-2 text-left text-yellow-800 font-semibold">No</th>
                <th class="px-3 py-2 text-left text-yellow-800 font-semibold">Nama Lengkap</th>
                <th class="px-3 py-2 text-left text-yellow-800 font-semibold">NIS</th>
                <th class="px-3 py-2 text-left text-yellow-800 font-semibold">Email</th>
                <th class="px-3 py-2 text-left text-yellow-800 font-semibold">Password</th>
            </tr>
        </thead>
        <tbody>
            @foreach(session('preview_data') as $index => $data)
            <tr class="border-b border-yellow-200">
                <td class="px-3 py-2 text-yellow-700">{{ $index + 1 }}</td>
                <td class="px-3 py-2 text-yellow-700">{{ $data['name'] }}</td>
                <td class="px-3 py-2 text-yellow-700 font-mono">{{ $data['nis'] }}</td>
                <td class="px-3 py-2 text-yellow-700 font-mono text-xs">{{ $data['email'] }}</td>
                <td class="px-3 py-2 text-yellow-700 font-mono text-xs">{{ $data['password'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
        <div class="mt-4 flex space-x-3">
            <form action="{{ route('murid.process-upload') }}" method="POST">
                @csrf
                <input type="hidden" name="kelas" value="{{ session('kelas') }}">
                <button type="submit" 
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 text-sm sm:text-base font-semibold">
                    ✅ Konfirmasi & Buat Akun
                </button>
            </form>
            <a href="{{ route('murid.upload') }}" 
               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 text-sm sm:text-base font-semibold">
                🔄 Upload Ulang
            </a>
        </div>
    </div>
    @endif

    <!-- Form Upload -->
    <div class="bg-green-50 rounded-lg p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-semibold text-green-800 mb-4">📤 Upload File CSV</h2>
        
        <form action="{{ route('murid.preview-upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <input type="text" name="kelas" value="{{ old('kelas') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm sm:text-base"
                           required placeholder="Contoh: XA, XB, XI IPA 1" maxlength="10">
                    <p class="text-xs text-gray-500 mt-1">Masukkan kode kelas (maksimal 10 karakter)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">File CSV</label>
                    <input type="file" name="csv_file" accept=".csv,.txt" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm sm:text-base"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Format: CSV dengan header "Nama", maksimal 1MB</p>
                </div>

                <button type="submit" 
                        class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 font-semibold text-sm sm:text-base">
                    🔍 Preview Data
                </button>
            </div>
        </form>
    </div>

    <<!-- Panduan -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-6">
    <div class="bg-blue-50 rounded-lg p-4 sm:p-6">
        <h3 class="font-semibold text-blue-800 mb-3 text-sm sm:text-base">📋 Format CSV</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-sm bg-white rounded border">
                <thead>
                    <tr class="bg-blue-100">
                        <th class="px-2 py-2 text-left text-blue-800 font-semibold">Nama</th>
                        <th class="px-2 py-2 text-left text-blue-800 font-semibold">NIS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-2 py-2 border">Anya Leandra Prabowo</td>
                        <td class="px-2 py-2 border">3017</td>
                    </tr>
                    <tr>
                        <td class="px-2 py-2 border">Axleeno Christoffer Hovan Kurniawan</td>
                        <td class="px-2 py-2 border">3018</td>
                    </tr>
                    <tr>
                        <td class="px-2 py-2 border">Dickenson Maxine Sugiyanto</td>
                        <td class="px-2 py-2 border">3019</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text-blue-700 text-xs mt-2">• Gunakan pemisah <strong>koma (,)</strong> atau <strong>titik koma (;)</strong></p>
        <p class="text-blue-700 text-xs">• NIS akan digunakan untuk email dan password</p>
    </div>

    <div class="bg-purple-50 rounded-lg p-4 sm:p-6">
        <h3 class="font-semibold text-purple-800 mb-3 text-sm sm:text-base">ℹ️ Sistem Generate</h3>
        <ul class="text-purple-700 text-xs sm:text-sm list-disc list-inside space-y-2">
            <li><strong>Email:</strong> nama-depan + NIS + @sekolah.com</li>
            <li><strong>Contoh:</strong> Anya3017@sekolah.com</li>
            <li><strong>Password:</strong> nama-depan + NIS + kelas</li>
            <li><strong>Contoh:</strong> Anya3017XA</li>
            <li>NIS diambil dari file CSV</li>
            <li>Kelas diambil dari input form</li>
        </ul>
    </div>
</div>

<!-- Template Download -->
<div class="bg-orange-50 rounded-lg p-4 sm:p-6 mt-4">
    <h3 class="font-semibold text-orange-800 mb-3 text-sm sm:text-base">📥 Download Template</h3>
    <p class="text-orange-700 text-xs sm:text-sm mb-3">Download template CSV dengan format Nama dan NIS:</p>
    <button onclick="downloadTemplate()" 
            class="inline-flex items-center bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 text-sm sm:text-base">
        <span class="mr-2">📄</span> Download Template CSV
    </button>
</div>

<script>
function downloadTemplate() {
    const csvContent = "data:text/csv;charset=utf-8," 
        + "Nama,NIS\n"
        + "Anya Leandra Prabowo,3017\n"
        + "Axleeno Christoffer Hovan Kurniawan,3018\n"
        + "Dickenson Maxine Sugiyanto,3019\n"
        + "Elia Benny Wahyu Augustha,3020\n"
        + "Evelin Fernanda,3021\n"
        + "Felicia Hasiholan Anindika,3022\n"
        + "Florence Alberta Susilo,3023\n"
        + "Geovanni Surya Senatra,3024\n"
        + "Hana Elysia,3025\n"
        + "Ivan Anthony Rusli,3026\n"
        + "Jazlyn Jefferson Setiawan,3027\n"
        + "Jolyn Karenza Hardiyanto,3028\n"
        + "Jordan Felizton Sudarmo,3029\n"
        + "Joshua Divano Haridandi,3030\n"
        + "Kinara Avril Sherafina,3031\n"
        + "Lowrens Jeremia Kawengian,3032\n"
        + "Magda Chanelysia Evelyn,3033\n"
        + "Marcell Fabiano Wibowo,3034\n"
        + "Marchellia Yeung Daoena,3035\n"
        + "Michael Kurniawan Sanjaya,3036\n"
        + "Resilia Gabriel Florensia,3037\n"
        + "Richard Ignazio Harminto,3038\n"
        + "Rishon Iniko Caesar Hermawan,3039\n"
        + "Samuel Wilson Kurniawan,3040\n"
        + "Tesalonika Margaretha Stefany Putri,3041\n"
        + "Violla Joanna Lausanda,3042\n"
        + "Yemima Gracia Susilo,3043";
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "template_murid_nis.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
// Auto-format kelas input
document.querySelector('input[name="kelas"]').addEventListener('input', function(e) {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
});
</script>

<style>
@media (max-width: 640px) {
    table {
        font-size: 10px;
    }
}
</style>
@endsection