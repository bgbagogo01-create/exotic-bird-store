<?php $__env->startSection('title', 'Riwayat Penjualan'); ?>
<?php $__env->startSection('page_title', 'Riwayat Penjualan'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight heading-font">Riwayat Penjualan</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-normal">Pantau catatan invoice transaksi, pendapatan, serta cetak ulang struk kasir.</p>
        </div>
        <a href="<?php echo e(route('transactions.pos')); ?>" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/10 transition-all flex items-center space-x-2 w-fit">
            <i class="fa-solid fa-cash-register"></i>
            <span>Buka Kasir POS</span>
        </a>
    </div>

    <!-- Date Range Audit Filter -->
    <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
        <form action="<?php echo e(route('transactions.index')); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <!-- Search Query -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Cari No Invoice / Pembeli</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?php echo e(request()->get('search')); ?>" placeholder="INV-2026... atau nama..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                </div>
            </div>

            <!-- Start Date -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Dari Tanggal</label>
                <input type="date" name="start_date" value="<?php echo e(request()->get('start_date')); ?>"
                    class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
            </div>

            <!-- End Date -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Sampai Tanggal</label>
                <input type="date" name="end_date" value="<?php echo e(request()->get('end_date')); ?>"
                    class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
            </div>

            <!-- Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Cari
                </button>
                <a href="<?php echo e(route('transactions.index')); ?>" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-xl text-xs transition-all hover:bg-slate-200 dark:hover:bg-slate-700 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- History Table Card -->
    <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 uppercase tracking-wider">
                        <th class="py-3 font-semibold w-12 text-center">#</th>
                        <th class="py-3 font-semibold">Nomor Invoice</th>
                        <th class="py-3 font-semibold">Waktu Penjualan</th>
                        <th class="py-3 font-semibold">Nama Kasir</th>
                        <th class="py-3 font-semibold">Nama Pembeli</th>
                        <th class="py-3 font-semibold text-right">Subtotal</th>
                        <th class="py-3 font-semibold text-right">Pot. Diskon</th>
                        <th class="py-3 font-semibold text-right">Total Bersih</th>
                        <th class="py-3 font-semibold text-right w-24">Cetak Struk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors" x-data="{ expanded: false }">
                        <td class="py-3.5 text-center text-slate-400 font-medium">
                            <?php echo e(($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration); ?>

                        </td>
                        <td class="py-3.5">
                            <button type="button" @click="expanded = !expanded" class="font-extrabold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center space-x-1">
                                <span><?php echo e($transaction->invoice_number); ?></span>
                                <i class="fa-solid text-[9px] transition-transform duration-200" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>
                        </td>
                        <td class="py-3.5 font-medium whitespace-nowrap"><?php echo e($transaction->created_at->isoFormat('D MMM Y, H:i')); ?></td>
                        <td class="py-3.5 text-slate-500 dark:text-slate-400"><?php echo e($transaction->user->name); ?></td>
                        <td class="py-3.5 font-semibold"><?php echo e($transaction->buyer_name); ?></td>
                        <td class="py-3.5 text-right font-medium text-slate-400">Rp <?php echo e(number_format($transaction->subtotal, 0, ',', '.')); ?></td>
                        <td class="py-3.5 text-right text-rose-500 font-medium">-Rp <?php echo e(number_format($transaction->discount, 0, ',', '.')); ?></td>
                        <td class="py-3.5 text-right font-bold text-slate-800 dark:text-slate-100">Rp <?php echo e(number_format($transaction->total_price, 0, ',', '.')); ?></td>
                        <td class="py-3.5 text-right whitespace-nowrap">
                            <!-- Printer Button Trigger (Popup window printable receipt) -->
                            <button type="button" onclick="window.open('<?php echo e(route('transactions.receipt', $transaction->id)); ?>', 'Cetak Struk', 'width=450,height=600,scrollbars=yes')"
                                class="px-2.5 py-1.5 bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 hover:bg-indigo-50 hover:text-white rounded-lg transition-colors inline-block" title="Cetak Struk POS">
                                <i class="fa-solid fa-print"></i> Struk
                            </button>
                        </td>
                    </tr>
                    
                    <!-- Collapsible Expandable row containing itemized lines list -->
                    <template x-if="expanded">
                        <tr class="bg-slate-50/50 dark:bg-slate-900/40">
                            <td colspan="9" class="p-4 border-b border-slate-200 dark:border-slate-800/80">
                                <div class="max-w-3xl pl-12 space-y-3">
                                    <h5 class="font-bold text-[10px] uppercase text-slate-400 tracking-wider">Rincian Belanja Barang</h5>
                                    
                                    <div class="border rounded-2xl overflow-hidden dark:border-slate-800 bg-white dark:bg-slate-950/60 shadow-inner">
                                        <table class="w-full text-left text-xs border-collapse">
                                            <thead>
                                                <tr class="border-b border-slate-100 dark:border-slate-850 text-slate-400">
                                                    <th class="p-3 font-semibold">Nama Barang</th>
                                                    <th class="p-3 font-semibold text-center w-24">Harga Jual</th>
                                                    <th class="p-3 font-semibold text-center w-20">Jumlah</th>
                                                    <th class="p-3 font-semibold text-right w-28">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 dark:divide-slate-850">
                                                <?php $__currentLoopData = $transaction->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="p-3 font-medium flex items-center space-x-2">
                                                        <span class="font-semibold"><?php echo e($item->product ? $item->product->name : 'Barang Dihapus'); ?></span>
                                                        <span class="text-[9px] font-mono text-slate-400">(<?php echo e($item->product ? $item->product->sku : '-'); ?>)</span>
                                                    </td>
                                                    <td class="p-3 text-center text-slate-500 dark:text-slate-400">Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></td>
                                                    <td class="p-3 text-center font-bold text-slate-700 dark:text-slate-300"><?php echo e($item->quantity); ?> pcs</td>
                                                    <td class="p-3 text-right font-semibold text-slate-800 dark:text-slate-100">Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <!-- Payment Info summary inside expander -->
                                    <div class="flex items-center justify-between text-[11px] px-2 text-slate-400">
                                        <span>Jumlah Uang Bayar: <strong>Rp <?php echo e(number_format($transaction->pay_amount, 0, ',', '.')); ?></strong></span>
                                        <span>Uang Kembali: <strong>Rp <?php echo e(number_format($transaction->change_amount, 0, ',', '.')); ?></strong></span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400 dark:text-slate-500">Tidak ada riwayat transaksi penjualan ditemukan.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($transactions->appends(request()->query())->links()); ?>

        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\syarat kp\project kp\resources\views/transactions/index.blade.php ENDPATH**/ ?>