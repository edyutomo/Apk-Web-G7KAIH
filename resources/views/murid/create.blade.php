@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 space-y-3 sm:space-y-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Tambah Murid Baru</h1>
        <div class="flex space-x-2">
            <a href="{{ route('murid.upload') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm sm:text-base text-center flex items-center">
                <span class="mr-2">📁</span> Upload CSV
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form Manual -->
        <div class="bg-blue-50 rounded-lg p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-semibold text-blue-800 mb-4">➕ Tambah Manual</h2>
            <form action="{{ route('murid.store') }}" method="POST">
                @csrf
                <div class="space-y-3 sm:space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                               required placeholder="Masukkan nama lengkap">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                               required placeholder="email@sekolah.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="password-wrapper relative">
                            <input type="password" name="password" id="password-create"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10 text-sm sm:text-base"
                                   required placeholder="Minimal 6 karakter">
                            <span class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600"
                                  onclick="togglePassword('password-create')">
                                <i class="fas fa-eye" id="eye-password-create"></i>
                            </span>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <div class="password-wrapper relative">
                            <input type="password" name="password_confirmation" id="password-confirm"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10 text-sm sm:text-base"
                                   required placeholder="Ulangi password">
                            <span class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600"
                                  onclick="togglePassword('password-confirm')">
                                <i class="fas fa-eye" id="eye-password-confirm"></i>
                            </span>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold text-sm sm:text-base">
                        👨‍🎓 Buat Akun Murid
                    </button>
                </div>
            </form>
        </div>

        <!-- Upload CSV -->
        <div class="bg-green-50 rounded-lg p-4 sm:p-6">
            <h2 class="text-lg sm:text-xl font-semibold text-green-800 mb-4">📁 Upload CSV</h2>
            <div class="space-y-4">
                <div class="text-center">
                    <div class="text-4xl mb-3">📊</div>
                    <h3 class="font-semibold text-green-700 mb-2">Tambah Banyak Murid Sekaligus</h3>
                    <p class="text-green-600 text-sm mb-4">Upload file CSV berisi daftar nama murid untuk dibuatkan akun otomatis.</p>
                    
                    <a href="{{ route('murid.upload') }}" 
                       class="inline-flex items-center bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 text-sm sm:text-base font-semibold">
                        <span class="mr-2">🚀</span> Mulai Upload CSV
                    </a>
                </div>

                <div class="bg-white rounded-lg p-3 border border-green-200">
                    <h4 class="font-semibold text-green-800 mb-2 text-sm">Keuntungan Upload CSV:</h4>
                    <ul class="text-green-700 text-xs list-disc list-inside space-y-1">
                        <li>Generate email & password otomatis</li>
                        <li>Tambah banyak murid sekaligus</li>
                        <li>Format sederhana hanya kolom "Nama"</li>
                        <li>System generate NIS otomatis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <h3 class="font-semibold text-blue-800 mb-2 text-sm sm:text-base">Informasi:</h3>
        <p class="text-blue-700 text-xs sm:text-sm">Murid yang dibuat akan dapat menginput data kebiasaan harian dan melihat grafik progress belajar.</p>
    </div>
</div>

<script>
    function togglePassword(inputId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(`eye-${inputId}`);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection