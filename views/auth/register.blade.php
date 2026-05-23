<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pembeli - EXOTIC BIRD STORE</title>
    
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark Mode Initializer -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(-45deg, #23a6d5, #23d5ab, #ee7752, #e73c7e);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        .heading-font {
            font-family: 'Outfit', sans-serif;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.25);
        }
        .dark .glass-card {
            background: rgba(15, 23, 42, 0.35);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 antialiased transition-colors duration-300">

    <!-- Theme Toggle Switch -->
    <button id="themeToggle" class="absolute top-6 right-6 w-10 h-10 rounded-xl bg-white/10 dark:bg-black/10 text-white hover:bg-white/20 dark:hover:bg-black/20 flex items-center justify-center backdrop-blur-md border border-white/20 dark:border-white/10 transition-all shadow-lg" title="Ganti Tema">
        <i class="fa-solid fa-moon dark:hidden"></i>
        <i class="fa-solid fa-sun hidden dark:inline"></i>
    </button>

    <!-- Glassmorphism Container -->
    <div class="glass-card w-full max-w-md rounded-3xl shadow-2xl p-8 md:p-10 transition-all duration-300 transform hover:scale-[1.01] flex flex-col items-center">
        
        <!-- App Logo -->
        <div class="w-14 h-14 rounded-2xl bg-white/20 dark:bg-slate-800/30 flex items-center justify-center border border-white/30 dark:border-white/10 shadow-lg text-white mb-6">
            <i class="fa-solid fa-user-plus text-2xl"></i>
        </div>
        
        <h2 class="text-2xl font-extrabold text-white heading-font text-center tracking-tight">Daftar Akun Baru</h2>
        <p class="text-xs text-slate-200/80 dark:text-slate-300/70 text-center mt-2 mb-8">Buat akun Pembeli Anda untuk melihat katalog & melakukan pemesanan</p>

        <!-- Registration Form -->
        <form action="{{ route('register') }}" method="POST" class="w-full space-y-4">
            @csrf

            <!-- Name Input Group -->
            <div class="space-y-1">
                <label for="name" class="text-xs font-semibold text-white/90 uppercase tracking-wider heading-font">Nama Lengkap</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-white/60 dark:text-slate-400">
                        <i class="fa-solid fa-user"></i>
                    </span>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Nama lengkap Anda" 
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-white/10 dark:bg-slate-900/40 text-white placeholder-white/50 border border-white/20 dark:border-slate-800/60 focus:border-white focus:ring-2 focus:ring-white/20 focus:outline-none transition-all duration-200">
                </div>
                @error('name')
                    <span class="text-xs font-semibold text-red-300 dark:text-red-400 flex items-center space-x-1 mt-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>

            <!-- Email Input Group -->
            <div class="space-y-1">
                <label for="email" class="text-xs font-semibold text-white/90 uppercase tracking-wider heading-font">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-white/60 dark:text-slate-400">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="nama@email.com" 
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-white/10 dark:bg-slate-900/40 text-white placeholder-white/50 border border-white/20 dark:border-slate-800/60 focus:border-white focus:ring-2 focus:ring-white/20 focus:outline-none transition-all duration-200">
                </div>
                @error('email')
                    <span class="text-xs font-semibold text-red-300 dark:text-red-400 flex items-center space-x-1 mt-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>

            <!-- Password Input Group -->
            <div class="space-y-1">
                <label for="password" class="text-xs font-semibold text-white/90 uppercase tracking-wider heading-font">Kata Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-white/60 dark:text-slate-400">
                        <i class="fa-solid fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter" 
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-white/10 dark:bg-slate-900/40 text-white placeholder-white/50 border border-white/20 dark:border-slate-800/60 focus:border-white focus:ring-2 focus:ring-white/20 focus:outline-none transition-all duration-200">
                </div>
                @error('password')
                    <span class="text-xs font-semibold text-red-300 dark:text-red-400 flex items-center space-x-1 mt-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $message }}</span>
                    </span>
                @enderror
            </div>

            <!-- Confirm Password Input Group -->
            <div class="space-y-1">
                <label for="password_confirmation" class="text-xs font-semibold text-white/90 uppercase tracking-wider heading-font">Konfirmasi Sandi</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-white/60 dark:text-slate-400">
                        <i class="fa-solid fa-shield-halved"></i>
                    </span>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi kata sandi" 
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-white/10 dark:bg-slate-900/40 text-white placeholder-white/50 border border-white/20 dark:border-slate-800/60 focus:border-white focus:ring-2 focus:ring-white/20 focus:outline-none transition-all duration-200">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full py-3 px-4 mt-2 rounded-xl bg-white text-blue-900 hover:bg-slate-100 dark:bg-blue-600 dark:text-white dark:hover:bg-blue-500 font-bold heading-font uppercase tracking-wider text-sm shadow-lg hover:shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all duration-200">
                Daftar Akun
            </button>
        </form>

        <!-- Back to login link -->
        <div class="w-full flex items-center my-5">
            <div class="flex-1 h-[1px] bg-white/20"></div>
            <span class="mx-3 text-[10px] uppercase font-bold text-white/50 tracking-wider">Atau</span>
            <div class="flex-1 h-[1px] bg-white/20"></div>
        </div>

        <div class="text-center">
            <p class="text-xs text-white/80">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-white hover:underline focus:outline-none">Masuk sekarang</a>
            </p>
        </div>

    </div>

    <!-- Scripts Section -->
    <script>
        const themeToggle = document.getElementById('themeToggle');
        if(themeToggle) {
            themeToggle.addEventListener('click', () => {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            });
        }
    </script>
</body>
</html>
