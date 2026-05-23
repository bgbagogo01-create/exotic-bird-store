<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Katalog Produk'); ?> - <?php echo e(\App\Models\Setting::get('shop_name', 'EXOTIC BIRD STORE')); ?></title>
    
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS (via Vite) -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

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
        }
        .heading-font {
            font-family: 'Outfit', sans-serif;
        }
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 99px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.15);
            border-radius: 99px;
        }
        #store-loader {
            transition: opacity 0.3s ease-out, visibility 0.3s ease-out;
        }
    </style>
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body class="bg-[#f8fafc] text-slate-800 dark:bg-[#0f172a] dark:text-slate-100 transition-colors duration-300 antialiased min-h-screen flex flex-col justify-between">

    <!-- Store Loader -->
    <div id="store-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-[#0f172a]">
        <div class="flex flex-col items-center">
            <div class="w-10 h-10 border-4 border-indigo-600 border-t-transparent dark:border-indigo-400 dark:border-t-transparent rounded-full animate-spin"></div>
            <p class="mt-4 text-xs font-semibold text-slate-500 dark:text-slate-400 heading-font tracking-wider">MEMBUAT KATALOG STORE...</p>
        </div>
    </div>

    <!-- Store Header Navbar (Frosted Glassmorphism) -->
    <header class="sticky top-0 z-40 bg-white/75 dark:bg-slate-900/60 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Left: Branding -->
            <a href="<?php echo e(route('catalog.index')); ?>" class="flex items-center space-x-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/20">
                    <i class="fa-solid fa-boxes-stacked text-sm"></i>
                </div>
                <span class="text-md font-extrabold bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 bg-clip-text text-transparent heading-font">EXOTIC BIRD STORE</span>
            </a>

            <!-- Right: Menus, Theme Switch, Auth buttons -->
            <div class="flex items-center space-x-4">
                <!-- Theme Toggle Switch -->
                <button id="themeToggle" class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm" title="Ganti Tema">
                    <i class="fa-solid fa-moon dark:hidden"></i>
                    <i class="fa-solid fa-sun hidden dark:inline"></i>
                </button>

                <!-- Auth / Panel Redirection buttons -->
                <?php if(auth()->guard()->check()): ?>
                    <!-- If Admin / Cashier, button to go to dashboard -->
                    <?php if(Auth::user()->isAdmin() || Auth::user()->isKasir()): ?>
                        <a href="<?php echo e(route('dashboard')); ?>" class="hidden sm:flex items-center space-x-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                            <i class="fa-solid fa-gauge"></i>
                            <span>Dashboard</span>
                        </a>
                    <?php endif; ?>

                    <!-- User panel dropdown replacement (Direct link to profile) -->
                    <div class="flex items-center space-x-2">
                        <a href="<?php echo e(route('profile')); ?>" class="flex items-center space-x-2 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl text-xs font-semibold transition-all">
                            <img src="<?php echo e(Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=6366f1&color=fff'); ?>" alt="Avatar" class="w-5 h-5 rounded-md object-cover">
                            <span class="max-w-[80px] truncate hidden md:inline"><?php echo e(Auth::user()->name); ?></span>
                        </a>
                        
                        <!-- Logout Form -->
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-9 h-9 bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition-all flex items-center justify-center shadow-sm" title="Keluar">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <!-- Not Logged In: show login / register buttons -->
                    <a href="<?php echo e(route('login')); ?>" class="text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-indigo-600 transition-colors">Masuk</a>
                    <a href="<?php echo e(route('register')); ?>" class="px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Store Content Container -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Store Footer -->
    <footer class="border-t border-slate-200/50 dark:border-slate-800/50 bg-white/40 dark:bg-slate-900/20 backdrop-blur py-6 text-center text-xs text-slate-400 dark:text-slate-500 heading-font">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>© <?php echo e(date('Y')); ?> <?php echo e(\App\Models\Setting::get('shop_name', 'EXOTIC BIRD STORE')); ?> - Hak Cipta Dilindungi.</span>
            <span class="flex items-center space-x-1">
                <span>Disediakan untuk Pembeli</span>
                <i class="fa-solid fa-heart text-red-500 animate-pulse"></i>
            </span>
        </div>
    </footer>

    <!-- Scripts Section -->
    <script>
        // Global Store Loader
        window.addEventListener('DOMContentLoaded', () => {
            const loader = document.getElementById('store-loader');
            if(loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.visibility = 'hidden';
                    }, 300);
                }, 100);
            }
        });

        // Theme Toggle Logic
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

        // SweetAlert Session Toast Handlers
        <?php if(session('toast_success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: "<?php echo e(session('toast_success')); ?>",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        <?php endif; ?>
    </script>
</body>
</html>
<?php /**PATH D:\syarat kp\project kp\resources\views/layouts/store.blade.php ENDPATH**/ ?>