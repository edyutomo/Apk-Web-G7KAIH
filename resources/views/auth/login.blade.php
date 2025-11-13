<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - G7KAIH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            transition: color 0.3s ease;
        }
        /* Prevent zoom on iOS */
        @media (max-width: 640px) {
            input, select, textarea {
                font-size: 16px;
            }
        }
        
        /* Custom gradient background */
        .gradient-bg {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        
        /* Animation for logo */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Logo image styling */
        .logo-image {
            max-width: 200px;
            height: auto;
        }
        
        @media (min-width: 768px) {
            .logo-image {
                max-width: 250px;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-3 sm:p-6">
    <div class="w-full max-w-6xl bg-white rounded-xl shadow-lg overflow-hidden flex flex-col md:flex-row">
        <!-- Bagian Gambar/Logo -->
        <div class="gradient-bg w-full md:w-1/2 p-8 sm:p-12 flex flex-col items-center justify-center text-white">
            <div class="floating mb-6">
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 sm:p-8 shadow-xl border border-white/30 flex flex-col items-center">
                    <!-- Logo Image -->
                    <img src="/background.png" alt="G7KATH Logo" class="logo-image mb-4">
                    <!-- Teks di bawah gambar -->
                    <div class="text-center">
                        <div class="text-lg sm:text-xl font-semibold mb-2">GERAKAN 7 KEBIASAAN</div>
                        <div class="text-xl sm:text-2xl font-bold text-yellow-300">ANAK INDONESIA HEBAT</div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-6">
                <p class="text-lg opacity-90">Sistem Manajemen Pembelajaran</p>
                <p class="text-sm opacity-80 mt-2">Membentuk kebiasaan positif untuk generasi hebat Indonesia</p>
            </div>
            
            <!-- Quotes atau testimoni -->
            <div class="mt-8 text-center italic opacity-90">
                <p>"Pendidikan adalah senjata paling ampuh untuk mengubah dunia."</p>
                <p class="text-sm mt-2">- Nelson Mandela</p>
            </div>
        </div>
        
        <!-- Bagian Form Login -->
        <div class="w-full md:w-1/2 p-6 sm:p-8 md:p-12">
            <h2 class="text-2xl sm:text-3xl font-bold mb-2 text-gray-800 text-center md:text-left">Login G7KAIH</h2>
            <p class="text-gray-600 mb-6 text-center md:text-left">Masuk ke akun Anda untuk melanjutkan</p>
            
            <!-- Tampilkan error jika ada -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                    {{ session('error') }}
                </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base" 
                           placeholder="Masukkan email Anda"
                           required autofocus>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <div class="password-wrapper relative">
                        <input type="password" name="password" id="password-login"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-10 text-base" 
                               placeholder="Masukkan password Anda"
                               required>
                        <span class="password-toggle absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600"
                              onclick="togglePassword('password-login')">
                            <i class="fas fa-eye" id="eye-password-login"></i>
                        </span>
                    </div>
                </div>

                <!-- Remember me checkbox -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                    </div>
                    
                    <div>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lupa password?</a>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-semibold text-base transition duration-200">
                    Login
                </button>
            </form>

            <!-- Informasi akun demo -->
            <!-- Informasi akun demo
<div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-gray-50 rounded-lg">
    <h3 class="font-bold mb-2 text-sm sm:text-base">Akun Demo:</h3>
    <div class="space-y-1 text-xs sm:text-sm">
        <p><strong>Pengawas 1:</strong> pengawas@sekolah.com / password</p>
        <p><strong>Pengawas 2:</strong> novian@sekolah.com / password</p>
        <p><strong>Guru:</strong> guru@sekolah.com / password</p>
        <p><strong>Murid:</strong> murid@sekolah.com / password</p>
    </div>
</div>-->
            
            <!-- Footer -->
            <div class="mt-6 text-center text-xs text-gray-500">
                <p>&copy; 2025 G7KAIH - Gerakan 7 Kebiasaan Anak Indonesia Hebat</p>
            </div>
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
        
        // Tambahkan efek interaktif pada tombol login
        document.addEventListener('DOMContentLoaded', function() {
            const loginButton = document.querySelector('button[type="submit"]');
            
            loginButton.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.1)';
            });
            
            loginButton.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    </script>
</body>
</html>