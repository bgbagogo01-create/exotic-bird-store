@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('page_title', 'Dashboard Utama')

@section('content')
<div class="space-y-6">
    
    <!-- Top Greeting Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight heading-font">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Berikut ringkasan operasional dan inventaris toko Anda per hari ini.</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-xs font-semibold px-3 py-1.5 bg-slate-100 dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200/50 dark:border-slate-800">
                <i class="fa-solid fa-calendar-day text-indigo-500 mr-1.5"></i>
                {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
            </span>
        </div>
    </div>

    <!-- Low Stock Alert Banner (Only shown if lowStockCount > 0) -->
    @if($lowStockCount > 0)
    <div class="bg-amber-500/10 border border-amber-500/30 rounded-2xl p-4 flex items-start space-x-3 text-amber-600 dark:text-amber-400 shadow-sm animate-pulse">
        <i class="fa-solid fa-triangle-exclamation text-lg mt-0.5"></i>
        <div class="flex-1">
            <h4 class="font-bold text-sm heading-font">Peringatan: Stok Barang Menipis!</h4>
            <p class="text-xs mt-0.5">Ada <strong>{{ $lowStockCount }}</strong> barang yang telah mencapai atau berada di bawah batas minimum stok. Segera lakukan restok barang.</p>
        </div>
        <a href="#low-stock-panel" class="text-xs font-bold underline hover:no-underline px-2.5 py-1 bg-amber-500/20 hover:bg-amber-500/30 rounded-lg transition-colors">Lihat Detail</a>
    </div>
    @endif

    <!-- KPI Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Metric Card: Total Pendapatan -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex items-center space-x-4 transition-transform hover:scale-[1.01]">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-green-500 to-emerald-500 flex items-center justify-center text-white shadow-lg shadow-green-500/10">
                <i class="fa-solid fa-money-bill-trend-up text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400 dark:text-slate-500 heading-font">Total Pendapatan</span>
                <h3 class="text-xl font-extrabold tracking-tight mt-0.5 heading-font">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Metric Card: Total Transaksi -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex items-center space-x-4 transition-transform hover:scale-[1.01]">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-500 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/10">
                <i class="fa-solid fa-cart-shopping text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400 dark:text-slate-500 heading-font">Total Penjualan</span>
                <h3 class="text-xl font-extrabold tracking-tight mt-0.5 heading-font">{{ $totalTransactions }} Transaksi</h3>
            </div>
        </div>

        <!-- Metric Card: Total Barang -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex items-center space-x-4 transition-transform hover:scale-[1.01]">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/10">
                <i class="fa-solid fa-box text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400 dark:text-slate-500 heading-font">Varian Barang</span>
                <h3 class="text-xl font-extrabold tracking-tight mt-0.5 heading-font">{{ $totalProducts }} Produk</h3>
            </div>
        </div>

        <!-- Metric Card: Barang Menipis -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex items-center space-x-4 transition-transform hover:scale-[1.01]">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-500 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-rose-500/10">
                <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            </div>
            <div>
                <span class="text-[10px] uppercase font-bold tracking-widest text-slate-400 dark:text-slate-500 heading-font">Stok Menipis</span>
                <h3 class="text-xl font-extrabold tracking-tight mt-0.5 heading-font {{ $lowStockCount > 0 ? 'text-rose-500' : '' }}">{{ $lowStockCount }} Item</h3>
            </div>
        </div>

    </div>

    <!-- Charts Grid (ApexCharts) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Sales Trend Line Chart (7 Days) -->
        <div class="lg:col-span-2 bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-sm uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font">Tren Penjualan Mingguan</h4>
                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold">7 Hari Terakhir</span>
            </div>
            <div id="salesChart" class="w-full h-80"></div>
        </div>

        <!-- Stock Distribution Donut Chart -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex flex-col">
            <h4 class="font-bold text-sm uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font mb-4">Sebaran Varian Kategori</h4>
            <div class="flex-1 flex items-center justify-center">
                <div id="categoryChart" class="w-full"></div>
            </div>
        </div>

    </div>

    <!-- Lower Operational Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Transactions Log Panel -->
        <div class="lg:col-span-2 bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h4 class="font-bold text-sm uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font">Aktivitas Transaksi Terbaru</h4>
                <a href="{{ route('transactions.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 underline heading-font">Lihat Semua</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                            <th class="py-3 font-semibold">No Invoice</th>
                            <th class="py-3 font-semibold">Kasir</th>
                            <th class="py-3 font-semibold">Nama Pembeli</th>
                            <th class="py-3 font-semibold">Total Belanja</th>
                            <th class="py-3 font-semibold">Tanggal</th>
                            <th class="py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($recentTransactions as $transaction)
                        <tr>
                            <td class="py-3.5 font-bold text-indigo-600 dark:text-indigo-400">{{ $transaction->invoice_number }}</td>
                            <td class="py-3.5 font-medium">{{ $transaction->user->name }}</td>
                            <td class="py-3.5 text-slate-500 dark:text-slate-400">{{ $transaction->buyer_name }}</td>
                            <td class="py-3.5 font-bold">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                            <td class="py-3.5 text-slate-500 dark:text-slate-400">{{ $transaction->created_at->format('d/m H:i') }}</td>
                            <td class="py-3.5 text-right">
                                <a href="{{ route('transactions.receipt', $transaction->id) }}" target="_blank" class="px-2.5 py-1 bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 hover:bg-indigo-500 hover:text-white rounded-lg transition-colors inline-block">
                                    <i class="fa-solid fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 dark:text-slate-500">Belum ada transaksi tercatat hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Live Stock Audit Activity Stream -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex flex-col">
            <h4 class="font-bold text-sm uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font mb-6">Log Aktivitas Stok</h4>
            
            <div class="flex-1 space-y-4">
                @forelse($activities as $act)
                <div class="flex items-start space-x-3 text-xs">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 {{ $act['type'] === 'in' ? 'bg-green-500/10 text-green-600 dark:bg-green-500/20 dark:text-green-400' : 'bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400' }}">
                        <i class="fa-solid {{ $act['type'] === 'in' ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline">
                            <h5 class="font-bold truncate">{{ $act['title'] }}</h5>
                            <span class="text-[9px] text-slate-400 shrink-0">{{ $act['time'] }}</span>
                        </div>
                        <p class="text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ $act['desc'] }}</p>
                        <span class="text-[9px] font-semibold text-slate-400 dark:text-slate-500">Pencatat: {{ $act['user'] }}</span>
                    </div>
                </div>
                @empty
                <div class="h-full flex items-center justify-center text-slate-400 dark:text-slate-500 py-10">
                    <div class="text-center">
                        <i class="fa-solid fa-clock-rotate-left text-2xl mb-2 block"></i>
                        <span>Belum ada mutasi stok dicatat.</span>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Low Stock Table (Only anchors if needed) -->
    <div id="low-stock-panel" class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
        <h4 class="font-bold text-sm uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font mb-4">Daftar Barang Stok Menipis</h4>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                        <th class="py-3 font-semibold">Barang</th>
                        <th class="py-3 font-semibold">Kategori</th>
                        <th class="py-3 font-semibold">Kode SKU</th>
                        <th class="py-3 font-semibold text-center">Batas Minimum</th>
                        <th class="py-3 font-semibold text-center">Stok Saat Ini</th>
                        <th class="py-3 font-semibold text-right">Aksi Restok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($lowStockProducts as $prod)
                    <tr>
                        <td class="py-3.5 flex items-center space-x-2.5">
                            <img src="{{ $prod->image_url }}" alt="Barang" class="w-8 h-8 rounded-lg object-cover bg-slate-100 border dark:border-slate-800">
                            <span class="font-semibold">{{ $prod->name }}</span>
                        </td>
                        <td class="py-3.5 text-slate-500 dark:text-slate-400">{{ $prod->category->name }}</td>
                        <td class="py-3.5 font-mono">{{ $prod->sku }}</td>
                        <td class="py-3.5 text-center font-medium">{{ $prod->min_stock }} pcs</td>
                        <td class="py-3.5 text-center">
                            <span class="px-2 py-0.5 bg-rose-500/10 text-rose-500 rounded-full font-bold">
                                {{ $prod->stock }} pcs
                            </span>
                        </td>
                        <td class="py-3.5 text-right">
                            <a href="{{ route('stock.index') }}" class="px-3 py-1.5 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors shadow-sm shadow-green-500/10">
                                <i class="fa-solid fa-plus mr-1"></i> Input Masuk
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-green-500 dark:text-green-400 font-bold">
                            <i class="fa-solid fa-circle-check mr-1"></i> Semua stok aman! Tidak ada barang menipis saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // ApexCharts Configurations
    document.addEventListener("DOMContentLoaded", function() {
        const isDark = document.documentElement.classList.contains('dark');
        
        // 1. Weekly Sales Spline Area Chart
        const salesOptions = {
            chart: {
                type: 'area',
                height: 320,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                background: 'transparent'
            },
            theme: {
                mode: isDark ? 'dark' : 'light'
            },
            stroke: {
                curve: 'smooth',
                width: 3,
                colors: ['#6366f1']
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 90, 100],
                    colorStops: [
                        { offset: 0, color: '#6366f1', opacity: 0.3 },
                        { offset: 100, color: '#6366f1', opacity: 0 }
                    ]
                }
            },
            series: [{
                name: 'Total Penjualan (Rp)',
                data: {!! json_encode($salesTrend) !!}
            }],
            xaxis: {
                categories: {!! json_encode($dates) !!},
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return "Rp " + val.toLocaleString('id-ID');
                    }
                }
            },
            grid: {
                borderColor: isDark ? '#1e293b' : '#f1f5f9',
                strokeDashArray: 4
            },
            dataLabels: { enabled: false },
            colors: ['#6366f1']
        };

        const salesChart = new ApexCharts(document.querySelector("#salesChart"), salesOptions);
        salesChart.render();

        // 2. Category Donut Chart
        const catOptions = {
            chart: {
                type: 'donut',
                height: 280,
                fontFamily: 'Inter, sans-serif',
                background: 'transparent'
            },
            theme: {
                mode: isDark ? 'dark' : 'light'
            },
            series: {!! json_encode($categoryProductCounts) !!},
            labels: {!! json_encode($categoryNames) !!},
            colors: ['#6366f1', '#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6'],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                labels: {
                    colors: isDark ? '#94a3b8' : '#64748b'
                }
            },
            stroke: { show: false },
            dataLabels: { enabled: false },
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '12px',
                                fontWeight: 600
                            },
                            value: {
                                show: true,
                                fontSize: '18px',
                                fontWeight: 700,
                                formatter: function(val) {
                                    return val + " Pcs";
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total Barang',
                                fontSize: '10px',
                                fontWeight: 600,
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0) + " Pcs";
                                }
                            }
                        }
                    }
                }
            }
        };

        const categoryChart = new ApexCharts(document.querySelector("#categoryChart"), catOptions);
        categoryChart.render();

        // Dynamic Chart Theme Switcher
        window.addEventListener('dark-mode-changed', () => {
            const dark = document.documentElement.classList.contains('dark');
            salesChart.updateOptions({
                theme: { mode: dark ? 'dark' : 'light' },
                grid: { borderColor: dark ? '#1e293b' : '#f1f5f9' }
            });
            categoryChart.updateOptions({
                theme: { mode: dark ? 'dark' : 'light' },
                legend: { labels: { colors: dark ? '#94a3b8' : '#64748b' } }
            });
        });
    });
</script>
@endsection
