<?php $__env->startSection('title', 'Ekspor Laporan'); ?>
<?php $__env->startSection('page_title', 'Ekspor Laporan & Statistik'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <!-- Top Header Title -->
    <div>
        <h1 class="text-xl font-bold tracking-tight heading-font">Laporan & Pembukuan</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Saring data mutasi gudang & transaksi keuangan, cetak dalam format PDF, atau ekspor ke file Excel.</p>
    </div>

    <!-- Date Range Selection Filter -->
    <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
        <h4 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font mb-4">Saring Tanggal Pembukuan</h4>
        
        <form action="<?php echo e(route('reports.index')); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Start Date -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Dari Tanggal</label>
                <input type="date" name="start_date" value="<?php echo e($startDate); ?>" required
                    class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
            </div>

            <!-- End Date -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Sampai Tanggal</label>
                <input type="date" name="end_date" value="<?php echo e($endDate); ?>" required
                    class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
            </div>

            <!-- Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                    <i class="fa-solid fa-arrows-rotate mr-1"></i> Perbarui Statistik
                </button>
                <a href="<?php echo e(route('reports.index')); ?>" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-xl text-xs transition-all hover:bg-slate-200 dark:hover:bg-slate-700 text-center">
                    Reset Bln Ini
                </a>
            </div>
        </form>
    </div>

    <!-- Filtered Statistics Metrices Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Metric 1: Omset Penjualan -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 flex items-center justify-center font-bold text-lg">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Omset Terfilter</span>
                <h3 class="text-md font-extrabold tracking-tight mt-0.5 heading-font"><?php echo e($shopCurrency); ?> <?php echo e(number_format($salesRevenue, 0, ',', '.')); ?></h3>
            </div>
        </div>

        <!-- Metric 2: Volume Transaksi -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 flex items-center justify-center font-bold text-lg">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Volume Transaksi</span>
                <h3 class="text-md font-extrabold tracking-tight mt-0.5 heading-font"><?php echo e($salesCount); ?> Transaksi</h3>
            </div>
        </div>

        <!-- Metric 3: Barang Masuk -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-green-500/10 text-green-600 dark:bg-green-500/20 dark:text-green-400 flex items-center justify-center font-bold text-lg">
                <i class="fa-solid fa-arrow-trend-up"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Barang Masuk</span>
                <h3 class="text-md font-extrabold tracking-tight mt-0.5 heading-font">+<?php echo e($stockInCount ?: 0); ?> Pcs</h3>
            </div>
        </div>

        <!-- Metric 4: Barang Keluar -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400 flex items-center justify-center font-bold text-lg">
                <i class="fa-solid fa-arrow-trend-down"></i>
            </div>
            <div>
                <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Barang Keluar</span>
                <h3 class="text-md font-extrabold tracking-tight mt-0.5 heading-font">-<?php echo e($stockOutCount ?: 0); ?> Pcs</h3>
            </div>
        </div>

    </div>

    <!-- Main Export Command Center Dashboard (Admin only can export, Cashier only view) -->
    <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font mb-6"><i class="fa-solid fa-download text-indigo-500 mr-1.5"></i> Pusat Unduhan Berkas</h4>
        
        <?php if(Auth::user()->isAdmin()): ?>
        <div class="divide-y divide-slate-100 dark:divide-slate-800/60 space-y-4">
            
            <!-- Row 1: Laporan Penjualan -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 first:pt-0">
                <div class="space-y-1">
                    <h5 class="font-bold text-xs heading-font text-slate-800 dark:text-slate-100">1. Laporan Transaksi Penjualan</h5>
                    <p class="text-[10px] text-slate-400">Berisi daftar omset transaksi kasir, subtotal, diskon, nama kasir, serta info pembeli.</p>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <a href="<?php echo e(route('reports.pdf', ['report_type' => 'sales', 'start_date' => $startDate, 'end_date' => $endDate])); ?>" target="_blank" 
                        class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl text-[10px] uppercase tracking-wider shadow-md shadow-rose-500/10 transition-all flex items-center space-x-1.5">
                        <i class="fa-solid fa-file-pdf"></i> <span>Cetak PDF</span>
                    </a>
                    <a href="<?php echo e(route('reports.excel', ['report_type' => 'sales', 'start_date' => $startDate, 'end_date' => $endDate])); ?>" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-[10px] uppercase tracking-wider shadow-md shadow-green-600/10 transition-all flex items-center space-x-1.5">
                        <i class="fa-solid fa-file-excel"></i> <span>Ekspor Excel</span>
                    </a>
                </div>
            </div>

            <!-- Row 2: Laporan Barang Masuk -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4">
                <div class="space-y-1">
                    <h5 class="font-bold text-xs heading-font text-slate-800 dark:text-slate-100">2. Laporan Mutasi Barang Masuk</h5>
                    <p class="text-[10px] text-slate-400">Mencatat barang yang masuk ke gudang, penambahan kuantitas, nama supplier, dan catatan log.</p>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <a href="<?php echo e(route('reports.pdf', ['report_type' => 'stock_in', 'start_date' => $startDate, 'end_date' => $endDate])); ?>" target="_blank" 
                        class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl text-[10px] uppercase tracking-wider shadow-md shadow-rose-500/10 transition-all flex items-center space-x-1.5">
                        <i class="fa-solid fa-file-pdf"></i> <span>Cetak PDF</span>
                    </a>
                    <a href="<?php echo e(route('reports.excel', ['report_type' => 'stock_in', 'start_date' => $startDate, 'end_date' => $endDate])); ?>" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-[10px] uppercase tracking-wider shadow-md shadow-green-600/10 transition-all flex items-center space-x-1.5">
                        <i class="fa-solid fa-file-excel"></i> <span>Ekspor Excel</span>
                    </a>
                </div>
            </div>

            <!-- Row 3: Laporan Barang Keluar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4">
                <div class="space-y-1">
                    <h5 class="font-bold text-xs heading-font text-slate-800 dark:text-slate-100">3. Laporan Mutasi Barang Keluar</h5>
                    <p class="text-[10px] text-slate-400">Mendokumentasikan kuantitas stok yang keluar dikarenakan barang rusak, hilang, kedaluwarsa, atau disesuaikan.</p>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <a href="<?php echo e(route('reports.pdf', ['report_type' => 'stock_out', 'start_date' => $startDate, 'end_date' => $endDate])); ?>" target="_blank" 
                        class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl text-[10px] uppercase tracking-wider shadow-md shadow-rose-500/10 transition-all flex items-center space-x-1.5">
                        <i class="fa-solid fa-file-pdf"></i> <span>Cetak PDF</span>
                    </a>
                    <a href="<?php echo e(route('reports.excel', ['report_type' => 'stock_out', 'start_date' => $startDate, 'end_date' => $endDate])); ?>" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-[10px] uppercase tracking-wider shadow-md shadow-green-600/10 transition-all flex items-center space-x-1.5">
                        <i class="fa-solid fa-file-excel"></i> <span>Ekspor Excel</span>
                    </a>
                </div>
            </div>

        </div>
        <?php else: ?>
        <div class="bg-indigo-500/5 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400 p-4 rounded-2xl text-xs flex items-center space-x-2">
            <i class="fa-solid fa-circle-info"></i>
            <span>Akun Anda terdaftar sebagai <strong>Kasir</strong>. Cetak PDF / Ekspor Excel eksklusif untuk level <strong>Admin</strong>. Anda hanya dapat melihat rangkuman di halaman ini.</span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Active Filtered Preview Listings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Table 1: Filtered Sales Transactions Preview -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
            <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font mb-4">Pratinjau Transaksi Terfilter</h4>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-[11px]">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                            <th class="py-2 font-semibold">No Invoice</th>
                            <th class="py-2 font-semibold">Nama Pembeli</th>
                            <th class="py-2 font-semibold text-right">Total Bersih</th>
                            <th class="py-2 font-semibold text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="py-2.5 font-bold text-indigo-600 dark:text-indigo-400"><?php echo e($tr->invoice_number); ?></td>
                            <td class="py-2.5 text-slate-500 dark:text-slate-400"><?php echo e($tr->buyer_name); ?></td>
                            <td class="py-2.5 text-right font-bold">Rp <?php echo e(number_format($tr->total_price, 0, ',', '.')); ?></td>
                            <td class="py-2.5 text-right text-slate-400"><?php echo e($tr->created_at->format('d/m/Y')); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400">Tidak ada transaksi ditemukan pada rentang tanggal ini.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table 2: Filtered Stock In & Out Combined Activity Preview -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font mb-4">Pratinjau Mutasi Stok Terfilter</h4>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-[11px]">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                <th class="py-2 font-semibold">Barang</th>
                                <th class="py-2 font-semibold text-center">Tipe</th>
                                <th class="py-2 font-semibold text-center">Jumlah</th>
                                <th class="py-2 font-semibold text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            <!-- Show combined inward logs -->
                            <?php $inCount = 0; ?>
                            <?php $__currentLoopData = $stockIns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($inCount < 5): ?>
                            <tr>
                                <td class="py-2.5 font-bold text-slate-800 dark:text-slate-100">
                                    <?php echo e($in->product->name); ?> <span class="text-[9px] font-mono text-slate-400">(<?php echo e($in->product->sku); ?>)</span>
                                </td>
                                <td class="py-2.5 text-center"><span class="px-2 py-0.5 bg-green-500/10 text-green-600 dark:bg-green-500/20 rounded-full font-bold text-[8px] uppercase">Masuk</span></td>
                                <td class="py-2.5 text-center font-bold text-green-600 dark:text-green-400">+<?php echo e($in->quantity); ?> pcs</td>
                                <td class="py-2.5 text-right text-slate-400"><?php echo e($in->date); ?></td>
                            </tr>
                            <?php $inCount++; ?>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <!-- Show combined outward logs -->
                            <?php $outCount = 0; ?>
                            <?php $__currentLoopData = $stockOuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $out): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($outCount < 5): ?>
                            <tr>
                                <td class="py-2.5 font-bold text-slate-800 dark:text-slate-100">
                                    <?php echo e($out->product->name); ?> <span class="text-[9px] font-mono text-slate-400">(<?php echo e($out->product->sku); ?>)</span>
                                </td>
                                <td class="py-2.5 text-center"><span class="px-2 py-0.5 bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 rounded-full font-bold text-[8px] uppercase">Keluar</span></td>
                                <td class="py-2.5 text-center font-bold text-rose-600 dark:text-rose-400">-<?php echo e($out->quantity); ?> pcs</td>
                                <td class="py-2.5 text-right text-slate-400"><?php echo e($out->date); ?></td>
                            </tr>
                            <?php $outCount++; ?>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php if($stockIns->isEmpty() && $stockOuts->isEmpty()): ?>
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Tidak ada penyesuaian stok terfilter.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\syarat kp\project kp\resources\views/reports/index.blade.php ENDPATH**/ ?>