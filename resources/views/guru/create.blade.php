@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-4 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 space-y-3 sm:space-y-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Tambah Guru Baru</h1>
        <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 text-sm sm:text-base text-center">
            Kembali ke Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 sm:px-4 sm:py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('guru.store') }}" method="POST">
        @csrf
        <div class="space-y-3 sm:space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap Guru</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                       required placeholder="Masukkan nama lengkap guru">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                       required placeholder="email@guru.sekolah.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="password-wrapper relative">
                    <input type="password" name="password" id="password-guru"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10 text-sm sm:text-base"
                           required placeholder="Minimal 6 karakter">
                    <span class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600"
                          onclick="togglePassword('password-guru')">
                        <i class="fas fa-eye" id="eye-password-guru"></i>
                    </span>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <div class="password-wrapper relative">
                    <input type="password" name="password_confirmation" id="password-guru-confirm"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10 text-sm sm:text-base"
                           required placeholder="Ulangi password">
                    <span class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600"
                          onclick="togglePassword('password-guru-confirm')">
                        <i class="fas fa-eye" id="eye-password-guru-confirm"></i>
                    </span>
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 font-semibold text-sm sm:text-base">
                👨‍🏫 Buat Akun Guru
            </button>
        </div>
    </form>

    <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-purple-50 rounded-lg">
        <h3 class="font-semibold text-purple-800 mb-2 text-sm sm:text-base">Informasi:</h3>
        <p class="text-purple-700 text-xs sm:text-sm">Guru yang dibuat akan dapat:</p>
        <ul class="text-purple-600 text-xs sm:text-sm list-disc list-inside mt-1 space-y-1">
            <li>Memantau murid bimbingan</li>
            <li>Membuat akun murid baru</li>
            <li>Melihat grafik progress murid</li>
            <li>Menganalisis kebiasaan belajar murid</li>
        </ul>
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