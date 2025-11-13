@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-0">🔐 Reset Password Murid</h1>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
            <a href="{{ route('pengawas.reset-password-guru') }}" 
               class="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 flex items-center justify-center text-sm btn-mobile">
                <span class="mr-2">👨‍🏫</span> 
                <span class="sm:hidden">Reset Guru</span>
                <span class="hidden sm:inline">Reset Password Guru</span>
            </a>
            <a href="{{ route('dashboard') }}" 
               class="bg-gray-500 text-white px-4 py-3 rounded-lg hover:bg-gray-600 flex items-center justify-center text-sm btn-mobile">
                <span class="mr-2">←</span> Dashboard
            </a>
        </div>
    </div>

    <!-- Notifications -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            <div class="flex items-center">
                <span class="text-lg mr-2">✅</span>
                <div>
                    <strong>Berhasil!</strong> Password untuk <strong>{{ session('murid_name') }}</strong> 
                    @if(session('new_password'))
                        telah direset menjadi: <code class="bg-green-200 px-2 py-1 rounded font-mono text-xs">{{ session('new_password') }}</code>
                    @else
                        telah direset.
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Pencarian -->
<div class="bg-blue-50 rounded-lg p-4 sm:p-6 mb-6">
    <h2 class="text-lg font-semibold text-blue-800 mb-4">🔍 Cari Murid</h2>
    
    <form action="{{ route('pengawas.search-murid') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Cari berdasarkan Nama, Email, atau Kelas
            </label>
            <div class="flex flex-col sm:flex-row gap-2">
                <input type="text" name="search" value="{{ $search ?? old('search') }}" 
                       class="flex-1 px-3 py-3 sm:py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base sm:text-sm btn-mobile"
                       required placeholder="Cari murid..." 
                       style="font-size: 16px;">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-3 sm:py-2 rounded-lg hover:bg-blue-700 font-semibold text-base sm:text-sm btn-mobile">
                    <span class="sm:hidden">🔍 Cari</span>
                    <span class="hidden sm:inline">Cari</span>
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                Masukkan nama murid, email, atau kelas untuk mencari
            </p>
        </div>
    </form>
</div>

    <!-- Hasil Pencarian -->
    @if(isset($murid) && $murid->count() > 0)
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-4 py-3 border-b">
            <h3 class="text-lg font-semibold text-gray-800">
                📋 Hasil Pencarian ({{ $murid->count() }} murid ditemukan)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px] sm:min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Nama Lengkap</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Email</th>
                        <th class="px-2 py-2 sm:px-4 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($murid as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-2 sm:px-4 sm:py-3">
                            <div class="font-medium text-gray-900 text-xs sm:text-sm whitespace-nowrap">{{ $m->name }}</div>
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3">
                            <div class="text-xs sm:text-sm text-gray-600 font-mono truncate max-w-[120px] sm:max-w-none">{{ $m->email }}</div>
                        </td>
                        <td class="px-2 py-2 sm:px-4 sm:py-3">
                            <div class="flex flex-col sm:flex-row gap-1 sm:gap-2">
                                <!-- Generate Password Otomatis -->
                                <!-- <form action="{{ route('pengawas.generate-password') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="murid_id" value="{{ $m->id }}">
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <button type="submit" 
                                            class="bg-green-600 text-white px-2 py-2 sm:px-3 sm:py-1 rounded text-xs hover:bg-green-700 flex items-center justify-center w-full sm:w-auto btn-mobile"
                                            onclick="return confirm('Generate password baru untuk {{ $m->name }}?')">
                                        <span class="mr-1">🔄</span>
                                        <span class="sm:hidden">Generate</span>
                                        <span class="hidden sm:inline">Generate Baru</span>
                                    </button>
                                </form> -->

                                <!-- Reset Password Manual -->
                                <button type="button" 
                                        onclick="showCustomPasswordForm({{ $m->id }}, '{{ $m->name }}')"
                                        class="bg-blue-600 text-white px-2 py-2 sm:px-3 sm:py-1 rounded text-xs hover:bg-blue-700 flex items-center justify-center w-full sm:w-auto btn-mobile">
                                    <span class="mr-1">✏️</span>
                                    <span class="sm:hidden">Custom</span>
                                    <span class="hidden sm:inline">Custom Password</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif(isset($search) && isset($murid) && $murid->count() === 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
        <div class="text-yellow-600 mb-2">
            <span class="text-2xl">🔍</span>
        </div>
        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Tidak Ditemukan</h3>
        <p class="text-yellow-700 text-sm">Tidak ada murid yang ditemukan dengan kata kunci "{{ $search }}"</p>
    </div>
    @endif

    <!-- Panduan -->
    <div class="bg-purple-50 rounded-lg p-4 sm:p-6 mt-6">
        <h3 class="font-semibold text-purple-800 mb-3 text-sm sm:text-base">ℹ️ Panduan Reset Password</h3>
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
<div id="customPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 px-4">
    <div class="bg-white rounded-lg p-4 sm:p-6 w-full max-w-md mx-auto modal-mobile">
        <h3 class="text-lg font-semibold text-gray-800 mb-4" id="modalTitle">Set Custom Password</h3>
        
        <form id="customPasswordForm" method="POST">
            @csrf
            <input type="hidden" name="search" id="modalSearchInput" value="{{ $search ?? '' }}">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                <input type="text" name="new_password" 
                       class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-base btn-mobile"
                       required minlength="6" placeholder="Minimal 6 karakter" id="passwordInput"
                       style="font-size: 16px;">
                <div class="flex items-center mt-2 space-x-4">
                    <button type="button" onclick="generateSuggestion()" 
                            class="text-xs bg-gray-200 text-gray-700 px-2 py-2 rounded hover:bg-gray-300 btn-mobile">
                        💡 Generate Suggestion
                    </button>
                    <span id="passwordStrength" class="text-xs font-medium"></span>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 sm:justify-end">
                <button type="button" onclick="hideCustomPasswordForm()" 
                        class="px-4 py-3 text-gray-600 hover:text-gray-800 bg-gray-200 rounded btn-mobile order-2 sm:order-1">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-3 rounded hover:bg-blue-700 btn-mobile order-1 sm:order-2">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showCustomPasswordForm(muridId, muridName) {
    const modal = document.getElementById('customPasswordModal');
    const form = document.getElementById('customPasswordForm');
    const title = document.getElementById('modalTitle');
    const searchInput = document.getElementById('modalSearchInput');

    title.textContent = `Set Password untuk ${muridName}`;
    form.action = `/pengawas/reset-password/${muridId}`;
    
    // ✅ SET VALUE SEARCH DARI INPUT UTAMA
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

// Prevent zoom on iOS
document.addEventListener('touchstart', function() {
    // Keep this empty to prevent default zoom behavior
}, { passive: true });
</script>

<style>
.btn-mobile {
    min-height: 44px;
    touch-action: manipulation;
}

@media (max-width: 640px) {
    .table-responsive {
        font-size: 11px;
    }
    
    table {
        min-width: 600px;
    }
    
    th, td {
        padding: 8px 4px;
    }
    
    .text-truncate {
        max-width: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    #customPasswordModal .bg-white {
        margin: 20px;
        width: calc(100% - 40px);
    }
    
    .modal-mobile {
        padding: 1rem;
    }
}

/* Touch improvements */
@media (max-width: 768px) {
    button, .btn-mobile {
        min-height: 44px;
    }
    
    input, select {
        font-size: 16px !important; /* Prevent zoom on iOS */
    }
}

/* Better scrolling on mobile */
@media (max-width: 640px) {
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
}
</style>
@endsection