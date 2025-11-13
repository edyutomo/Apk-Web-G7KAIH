@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-3 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-lg sm:text-2xl font-bold text-gray-800 mb-3 sm:mb-0">🔐 Reset Password Guru</h1>
        <div class="flex flex-col sm:flex-row gap-2 sm:space-x-2">
            <a href="{{ route('pengawas.reset-password') }}" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 text-xs sm:text-sm text-center">
                🔐 Reset Murid
            </a>
            <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-3 py-2 rounded hover:bg-gray-600 text-xs sm:text-sm text-center">
                ← Dashboard
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-4 text-sm">
            <div class="flex items-start">
                <span class="text-lg mr-2 mt-0.5">✅</span>
                <div>
                    <strong>Berhasil!</strong> Password untuk <strong>{{ session('guru_name') }}</strong> 
                    @if(session('new_password'))
                        telah direset menjadi: <code class="bg-green-200 px-1 py-0.5 rounded font-mono text-xs">{{ session('new_password') }}</code>
                    @else
                        telah direset.
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Pencarian -->
    <div class="bg-green-50 rounded-lg p-4 sm:p-6 mb-6">
        <h2 class="text-base sm:text-lg font-semibold text-green-800 mb-3">🔍 Cari Guru</h2>
        
        <form action="{{ route('pengawas.search-guru') }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Cari berdasarkan Nama atau Email
                </label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="text" name="search" value="{{ $search ?? old('search') }}" 
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                           required placeholder="Contoh: Budi Santoso, budi@guru.sekolah.com">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-semibold text-sm sm:text-base">
                        Cari
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">
                    Masukkan nama guru atau email untuk mencari
                </p>
            </div>
        </form>
    </div>

    <!-- Hasil Pencarian -->
    @if(isset($guru) && $guru->count() > 0)
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-3 py-2 sm:px-4 sm:py-3 border-b">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800">
                📋 Hasil Pencarian ({{ $guru->count() }} guru ditemukan)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Email</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Murid</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($guru as $g)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-sm font-medium">
                            <div class="font-semibold">{{ $g->name }}</div>
                            <div class="text-xs text-gray-500 sm:hidden mt-1">{{ $g->email }}</div>
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-sm font-mono text-gray-600 hidden sm:table-cell">
                            {{ $g->email }}
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-sm">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium whitespace-nowrap">
                                {{ $g->murid_count ?? 0 }} murid
                            </span>
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3 text-sm">
                            <div class="flex flex-col sm:flex-row gap-1 sm:space-x-2">
                                <!-- Generate Password Otomatis -->
                                <!-- <form action="{{ route('pengawas.generate-password-guru') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="guru_id" value="{{ $g->id }}">
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <button type="submit" 
                                            class="bg-green-600 text-white px-2 py-1 rounded text-xs hover:bg-green-700 w-full sm:w-auto text-center"
                                            onclick="return confirm('Generate password baru untuk {{ $g->name }}?')">
                                        🔄 Generate
                                    </button>
                                </form> -->

                                <!-- Reset Password Manual -->
                                <button type="button" 
                                        onclick="showCustomPasswordForm({{ $g->id }}, '{{ $g->name }}')"
                                        class="bg-blue-600 text-white px-2 py-1 rounded text-xs hover:bg-blue-700 w-full sm:w-auto text-center">
                                    ✏️ Custom
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif(isset($search) && !empty($search) && $guru->count() === 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
        <div class="text-yellow-600 mb-2">
            <span class="text-2xl">🔍</span>
        </div>
        <h3 class="text-base sm:text-lg font-semibold text-yellow-800 mb-2">Tidak Ditemukan</h3>
        <p class="text-yellow-700 text-sm">Tidak ada guru yang ditemukan dengan kata kunci "{{ $search }}"</p>
    </div>
    @endif

    <!-- Panduan -->
    <div class="bg-purple-50 rounded-lg p-4 sm:p-6 mt-6">
        <h3 class="font-semibold text-purple-800 mb-3 text-base sm:text-lg">ℹ️ Panduan Reset Password Guru</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-purple-700">
            <div>
                <h4 class="font-semibold mb-2">🔄 Generate Password Baru</h4>
                <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm">
                    <li>Sistem akan generate password 8 karakter acak</li>
                    <li>Password akan ditampilkan sekali saja</li>
                    <li>Cocok untuk reset password darurat</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">✏️ Custom Password</h4>
                <ul class="list-disc list-inside space-y-1 text-xs sm:text-sm">
                    <li>Buat password custom sesuai keinginan</li>
                    <li>Minimal 6 karakter</li>
                    <li>Cocok untuk pattern tertentu</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Custom Password -->
<div id="customPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-3">
    <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-md mx-auto">
        <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4" id="modalTitle">Set Custom Password</h3>
        
        <form id="customPasswordForm" method="POST">
            @csrf
            @method('POST')
            <input type="hidden" name="search" id="modalSearchInput" value="{{ $search ?? '' }}">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <input type="text" name="new_password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"
                       required minlength="6" placeholder="Minimal 6 karakter" id="passwordInput">
                <div class="flex flex-col sm:flex-row sm:items-center mt-2 gap-2 sm:space-x-4">
                    <button type="button" onclick="generateSuggestion()" 
                            class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded hover:bg-gray-300 whitespace-nowrap">
                        💡 Generate Suggestion
                    </button>
                    <span id="passwordStrength" class="text-xs font-medium"></span>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row justify-end gap-2 sm:space-x-3">
                <button type="button" onclick="hideCustomPasswordForm()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 text-sm order-2 sm:order-1">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm order-1 sm:order-2">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showCustomPasswordForm(guruId, guruName) {
    const modal = document.getElementById('customPasswordModal');
    const form = document.getElementById('customPasswordForm');
    const title = document.getElementById('modalTitle');
    const searchInput = document.getElementById('modalSearchInput');

    title.textContent = `Set Password untuk ${guruName}`;
    form.action = `/pengawas/reset-password-guru/${guruId}`;
    
    // Set value search dari input utama
    const mainSearchInput = document.querySelector('input[name="search"]');
    if (mainSearchInput && mainSearchInput.value) {
        searchInput.value = mainSearchInput.value;
    }

    modal.classList.remove('hidden');
    document.getElementById('passwordInput').focus();
}

function hideCustomPasswordForm() {
    document.getElementById('customPasswordModal').classList.add('hidden');
}

function generateSuggestion() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let password = '';
    
    for (let i = 0; i < 8; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    
    document.getElementById('passwordInput').value = password;
    checkPasswordStrength(password);
}

function checkPasswordStrength(password) {
    const strengthElem = document.getElementById('passwordStrength');
    let strength = 'Weak';
    let color = 'text-red-600';
    
    if (password.length >= 8) {
        strength = 'Medium';
        color = 'text-yellow-600';
    }
    if (password.length >= 10 && /[0-9]/.test(password) && /[A-Z]/.test(password)) {
        strength = 'Strong';
        color = 'text-green-600';
    }
    
    strengthElem.textContent = strength;
    strengthElem.className = `text-xs font-medium ${color}`;
}

// Event listener untuk real-time password strength check
document.getElementById('passwordInput').addEventListener('input', function(e) {
    checkPasswordStrength(e.target.value);
});

// Close modal when clicking outside
document.getElementById('customPasswordModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideCustomPasswordForm();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideCustomPasswordForm();
    }
});
</script>

<style>
/* Custom styles for better mobile experience */
@media (max-width: 640px) {
    table {
        font-size: 12px;
    }
    
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
    
    #customPasswordModal {
        padding: 1rem;
    }
}

/* Improve touch targets for mobile */
@media (max-width: 768px) {
    button, a {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    input, select, textarea {
        font-size: 16px; /* Prevent zoom on iOS */
    }
}
</style>
@endsection