<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G7KAIH - Monitoring Kebiasaan Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation Mobile Friendly -->
    @auth
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-3 sm:px-4">
            <div class="flex justify-between items-center py-3">
                <div class="font-bold text-lg sm:text-xl">G7KAIH</div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <span class="text-xs sm:text-sm hidden sm:inline">{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
                    <span class="text-xs sm:text-sm sm:hidden">{{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 px-2 py-1 sm:px-3 sm:py-1 rounded text-xs sm:text-sm">
                            <span class="hidden sm:inline">Logout</span>
                            <span class="sm:hidden">🚪</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-4 sm:py-6 px-3 sm:px-4">
        <!-- Notifikasi -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 sm:px-4 sm:py-3 rounded mb-4 text-sm sm:text-base">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 sm:px-4 sm:py-3 rounded mb-4 text-sm sm:text-base">
                {{ session('error') }}
            </div>
        @endif

        <!-- Content dari view lain -->
        @yield('content')
    </main>

    <!-- Script untuk toggle password -->
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

        // Auto-initialize untuk mobile
        document.addEventListener('DOMContentLoaded', function() {
            // Add toggle functionality to all password fields
            document.querySelectorAll('input[type="password"]').forEach(function(input) {
                if (!input.id) {
                    input.id = 'password-' + Math.random().toString(36).substr(2, 9);
                }
                
                if (!input.parentElement.classList.contains('password-wrapper')) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'password-wrapper relative';
                    input.parentNode.insertBefore(wrapper, input);
                    wrapper.appendChild(input);
                    
                    // Add eye icon dengan ukuran responsive
                    const eyeIcon = document.createElement('span');
                    eyeIcon.className = 'absolute right-2 sm:right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600 text-sm sm:text-base';
                    eyeIcon.innerHTML = '<i class="fas fa-eye"></i>';
                    eyeIcon.onclick = function() {
                        togglePassword(input.id);
                    };
                    wrapper.appendChild(eyeIcon);
                    
                    // Tambahkan padding untuk mobile
                    input.classList.add('pr-8', 'sm:pr-10');
                }
            });

            // Mobile menu handling jika diperlukan
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>

    <style>
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            transition: color 0.3s ease;
        }
        
        /* Improve touch targets for mobile */
        @media (max-width: 640px) {
            button, a {
                min-height: 44px;
                min-width: 44px;
            }
            
            input, select, textarea {
                font-size: 16px; /* Prevent zoom on iOS */
            }
            
            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</body>
</html>