<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ \App\Models\Setting::get('shop_name', 'EXOTIC BIRD STORE') }}</title>
    
    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- ApexCharts (Modern Dashboard Charts) -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark Mode Initializer Script (Preventing FOUC) -->
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
        /* Custom Loading Spinner */
        #loader {
            transition: opacity 0.3s ease-out, visibility 0.3s ease-out;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-[#f8fafc] text-slate-800 dark:bg-[#0f172a] dark:text-slate-100 transition-colors duration-300 antialiased min-h-screen flex flex-col">

    <!-- Global Page Loader -->
    <div id="loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white dark:bg-[#0f172a]">
        <div class="flex flex-col items-center">
            <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent dark:border-indigo-400 dark:border-t-transparent rounded-full animate-spin"></div>
            <p class="mt-4 text-sm font-medium text-slate-500 dark:text-slate-400 heading-font tracking-wider">MEMUAT SISTEM...</p>
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden" x-data="{ sidebarOpen: false }">
        
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white/70 dark:bg-slate-900/60 backdrop-blur-xl border-r border-slate-200/50 dark:border-slate-800/50 transform -translate-x-full md:translate-x-0 md:static transition-transform duration-300 ease-in-out flex flex-col">
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200/50 dark:border-slate-800/50">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/20">
                        <i class="fa-solid fa-boxes-stacked text-sm"></i>
                    </div>
                    <span class="text-lg font-bold bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400 bg-clip-text text-transparent heading-font">EXOTIC BIRD STORE</span>
                </a>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-100">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <!-- Sidebar Menu Content -->
            <div class="flex-1 overflow-y-auto px-4 py-6 space-y-7">
                <!-- Group Menu: Utama -->
                <div class="space-y-2">
                    <span class="px-3 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Menu Utama</span>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('dashboard') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i class="fa-solid fa-chart-line w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('catalog.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('catalog.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i class="fa-solid fa-store w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Katalog Pembeli</span>
                    </a>
                </div>

                <!-- Group Menu: Inventaris -->
                <div class="space-y-2">
                    <span class="px-3 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Gudang & Inventaris</span>
                    
                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('categories.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('categories.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i class="fa-solid fa-tags w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Kategori Barang</span>
                    </a>
                    @endif

                    <a href="{{ route('products.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('products.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i class="fa-solid fa-cubes w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Daftar Barang</span>
                    </a>

                    <a href="{{ route('stock.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('stock.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i class="fa-solid fa-arrows-spin w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Mutasi & Log Stok</span>
                    </a>
                </div>

                <!-- Group Menu: Penjualan -->
                <div class="space-y-2">
                    <span class="px-3 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Transaksi & Kasir</span>
                    
                    <a href="{{ route('transactions.pos') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('transactions.pos') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i class="fa-solid fa-cash-register w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Aplikasi POS (Kasir)</span>
                    </a>

                    <a href="{{ route('transactions.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('transactions.index') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i class="fa-solid fa-receipt w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Riwayat Penjualan</span>
                    </a>
                </div>

                <!-- Group Menu: Laporan -->
                <div class="space-y-2">
                    <span class="px-3 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Laporan & Ekspor</span>
                    
                    <a href="{{ route('reports.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('reports.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i class="fa-solid fa-file-invoice-dollar w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Ekspor Laporan</span>
                    </a>
                </div>

                @if(Auth::user()->isAdmin())
                <!-- Group Menu: Admin Settings -->
                <div class="space-y-2">
                    <span class="px-3 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Konfigurasi Toko</span>
                    
                    <a href="{{ route('users.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('users.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i class="fa-solid fa-users-gear w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Kelola Pengguna</span>
                    </a>

                    <a href="{{ route('settings.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ Route::is('settings.*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-slate-100' }}">
                        <i class="fa-solid fa-sliders w-5 text-center group-hover:scale-110 transition-transform"></i>
                        <span>Pengaturan Sistem</span>
                    </a>
                </div>
                @endif
            </div>

            <!-- Sidebar Footer User Panel -->
            <div class="p-4 border-t border-slate-200/50 dark:border-slate-800/50">
                <div class="flex items-center space-x-3 px-2 py-1.5 bg-slate-50 dark:bg-slate-800/40 rounded-xl">
                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=6366f1&color=fff' }}" alt="Avatar" class="w-9 h-9 rounded-lg object-cover ring-2 ring-indigo-500/20">
                    <div class="flex-1 overflow-hidden">
                        <h4 class="text-xs font-semibold truncate">{{ Auth::user()->name }}</h4>
                        <span class="text-[9px] uppercase tracking-wider font-semibold text-indigo-600 dark:text-indigo-400">{{ Auth::user()->role ? Auth::user()->role->display_name : 'Staff' }}</span>
                    </div>
                    <a href="{{ route('profile') }}" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200" title="Ubah Profil">
                        <i class="fa-solid fa-user-pen text-sm"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Body Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Navbar Header (Frosted Glassmorphism) -->
            <header class="h-16 flex items-center justify-between px-6 bg-white/70 dark:bg-slate-900/60 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/50 z-20">
                <!-- Left Section: Burger button & Breadcrumbs -->
                <div class="flex items-center space-x-4">
                    <button id="sidebarToggle" class="md:hidden text-slate-500 hover:text-slate-800 dark:hover:text-slate-100">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <!-- Dynamic Breadcrumb -->
                    <div class="hidden sm:flex items-center space-x-2 text-xs text-slate-500 dark:text-slate-400">
                        <span class="heading-font hover:text-indigo-600 cursor-pointer">Sistem</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        <span class="font-semibold text-slate-800 dark:text-slate-100 heading-font">@yield('page_title', 'Dashboard')</span>
                    </div>
                </div>

                <!-- Right Section: Theme Toggle & Logout -->
                <div class="flex items-center space-x-4">
                    <!-- Light / Dark Mode Toggle Switch -->
                    <button id="themeToggle" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors shadow-sm" title="Ganti Tema">
                        <i class="fa-solid fa-moon dark:hidden"></i>
                        <i class="fa-solid fa-sun hidden dark:inline"></i>
                    </button>

                    <!-- Active Cashier Shop Name -->
                    <div class="hidden lg:flex items-center px-3 py-1.5 bg-green-500/10 text-green-600 dark:bg-green-500/20 dark:text-green-400 rounded-lg text-xs font-semibold heading-font space-x-1.5">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-ping"></span>
                        <span>Kasir Online</span>
                    </div>

                    <!-- User Actions -->
                    <div class="h-8 w-[1px] bg-slate-200 dark:bg-slate-800"></div>

                    <!-- Logout Button Form -->
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-xl bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white transition-all flex items-center justify-center shadow-sm" title="Keluar">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto p-6 md:p-8 space-y-6">
                @yield('content')
            </main>

            <!-- Sticky Footer -->
            <footer class="h-12 border-t border-slate-200/50 dark:border-slate-800/50 flex items-center justify-between px-6 text-[10px] text-slate-400 dark:text-slate-500 bg-white/40 dark:bg-slate-900/20 backdrop-blur heading-font">
                <span>© {{ date('Y') }} {{ \App\Models\Setting::get('shop_name', 'EXOTIC BIRD STORE') }} - Hak Cipta Dilindungi.</span>
                <span class="hidden sm:inline-flex items-center gap-1">
                    <span>Didesain dengan</span>
                    <i class="fa-solid fa-heart text-red-500 animate-pulse"></i>
                    <span>& Laravel 12</span>
                </span>
            </footer>

        </div>
    </div>

    <!-- Scripts Section -->
    <script>
        // Global page loader fadeout
        window.addEventListener('DOMContentLoaded', () => {
            const loader = document.getElementById('loader');
            if(loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.visibility = 'hidden';
                    }, 300);
                }, 100);
            }
        });

        // Mobile Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        if(sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('-translate-x-full');
            });
            document.addEventListener('click', (e) => {
                if(!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        }

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
                // Dispatch dark-mode-changed event for Charts
                window.dispatchEvent(new Event('dark-mode-changed'));
            });
        }

        // SweetAlert2 Session Toast Handlers
        @if(session('toast_success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('toast_success') }}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if(session('toast_error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('toast_error') }}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        @endif
        
        @if(session('toast_warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: "{{ session('toast_warning') }}",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4500,
                timerProgressBar: true
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Input!',
                html: '<ul class="text-left text-xs list-disc pl-4">{!! implode("", $errors->all("<li>:message</li>")) !!}</ul>',
                confirmButtonColor: '#4f46e5',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
        @endif

        // Global Delete Confirmation SweetAlert Trigger
        function confirmDelete(formId, itemName = 'item') {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus data '${itemName}'. Data yang dihapus tidak dapat dipulihkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            })
        }
    </script>

    @yield('scripts')
</body>
</html>
