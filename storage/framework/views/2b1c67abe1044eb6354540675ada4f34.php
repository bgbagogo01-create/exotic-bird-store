<?php $__env->startSection('title', 'Mutasi & Log Stok'); ?>
<?php $__env->startSection('page_title', 'Mutasi & Log Stok (Gudang)'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ activeTab: 'in' }">
    
    <!-- Left Column: Input Forms (Barang Masuk & Keluar) -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Card: Input Barang Masuk -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
            <h4 class="text-sm font-bold uppercase tracking-wider text-green-600 dark:text-green-400 heading-font mb-4">
                <i class="fa-solid fa-square-plus mr-1.5"></i> Input Barang Masuk
            </h4>
            
            <form action="<?php echo e(route('stock.in')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                
                <!-- Product -->
                <div class="space-y-1">
                    <label for="in_product_id" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Pilih Barang</label>
                    <select name="product_id" id="in_product_id" required
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                        <option value="">Pilih Produk...</option>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($prod->id); ?>" <?php echo e(old('product_id') == $prod->id ? 'selected' : ''); ?>><?php echo e($prod->name); ?> (Stok: <?php echo e($prod->stock); ?> pcs)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Quantity -->
                    <div class="space-y-1">
                        <label for="in_quantity" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Jumlah (Pcs)</label>
                        <input type="number" min="1" name="quantity" id="in_quantity" value="<?php echo e(old('quantity')); ?>" required placeholder="Kuantitas"
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    </div>

                    <!-- Date -->
                    <div class="space-y-1">
                        <label for="in_date" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Tanggal</label>
                        <input type="date" name="date" id="in_date" value="<?php echo e(old('date', date('Y-m-d'))); ?>" required
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                    </div>
                </div>

                <!-- Supplier -->
                <div class="space-y-1">
                    <label for="in_supplier" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Pemasok / Supplier (Opsional)</label>
                    <input type="text" name="supplier" id="in_supplier" value="<?php echo e(old('supplier')); ?>" placeholder="Nama PT / Pemasok..."
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                </div>

                <!-- Notes -->
                <div class="space-y-1">
                    <label for="in_notes" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Catatan Tambahan</label>
                    <textarea name="notes" id="in_notes" rows="2" placeholder="Keterangan mutasi..."
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all"><?php echo e(old('notes')); ?></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-green-500/10 transition-all">
                    Simpan Barang Masuk
                </button>
            </form>
        </div>

        <!-- Card: Input Barang Keluar -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
            <h4 class="text-sm font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 heading-font mb-4">
                <i class="fa-solid fa-square-minus mr-1.5"></i> Input Barang Keluar
            </h4>
            
            <form action="<?php echo e(route('stock.out')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                
                <!-- Product -->
                <div class="space-y-1">
                    <label for="out_product_id" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Pilih Barang</label>
                    <select name="product_id" id="out_product_id" required
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                        <option value="">Pilih Produk...</option>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($prod->id); ?>" <?php echo e(old('product_id') == $prod->id ? 'selected' : ''); ?>><?php echo e($prod->name); ?> (Stok: <?php echo e($prod->stock); ?> pcs)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Quantity -->
                    <div class="space-y-1">
                        <label for="out_quantity" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Jumlah (Pcs)</label>
                        <input type="number" min="1" name="quantity" id="out_quantity" value="<?php echo e(old('quantity')); ?>" required placeholder="Kuantitas"
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                        <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-[10px] text-rose-500 font-semibold"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Date -->
                    <div class="space-y-1">
                        <label for="out_date" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Tanggal</label>
                        <input type="date" name="date" id="out_date" value="<?php echo e(old('date', date('Y-m-d'))); ?>" required
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                    </div>
                </div>

                <!-- Reason selection -->
                <div class="space-y-1">
                    <label for="out_reason" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Alasan Keluar</label>
                    <select name="reason" id="out_reason" required
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                        <option value="">Pilih Alasan...</option>
                        <option value="rusak" <?php echo e(old('reason') == 'rusak' ? 'selected' : ''); ?>>Barang Rusak</option>
                        <option value="hilang" <?php echo e(old('reason') == 'hilang' ? 'selected' : ''); ?>>Barang Hilang</option>
                        <option value="expired" <?php echo e(old('reason') == 'expired' ? 'selected' : ''); ?>>Kedaluwarsa (Expired)</option>
                        <option value="penyesuaian" <?php echo e(old('reason') == 'penyesuaian' ? 'selected' : ''); ?>>Penyesuaian Stok Gudang</option>
                    </select>
                </div>

                <!-- Notes -->
                <div class="space-y-1">
                    <label for="out_notes" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Catatan Tambahan</label>
                    <textarea name="notes" id="out_notes" rows="2" placeholder="Keterangan kerusakan/kehilangan..."
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all"><?php echo e(old('notes')); ?></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-rose-500/10 transition-all">
                    Simpan Barang Keluar
                </button>
            </form>
        </div>

    </div>

    <!-- Right Column: Tabs Panel History List -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Dynamic Logs Filter toolbar -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
            <h4 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font mb-4">Saring Riwayat Mutasi</h4>
            
            <form action="<?php echo e(route('stock.index')); ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <!-- Product -->
                <div>
                    <select name="product_id" class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                        <option value="">Semua Barang</option>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($prod->id); ?>" <?php echo e(request()->get('product_id') == $prod->id ? 'selected' : ''); ?>><?php echo e($prod->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Start date -->
                <div>
                    <input type="date" name="start_date" value="<?php echo e(request()->get('start_date')); ?>" placeholder="Dari Tanggal"
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                </div>

                <!-- End date -->
                <div>
                    <input type="date" name="end_date" value="<?php echo e(request()->get('end_date')); ?>" placeholder="Sampai Tanggal"
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                </div>

                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                        Cari
                    </button>
                    <a href="<?php echo e(route('stock.index')); ?>" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-xl text-xs transition-all hover:bg-slate-200 dark:hover:bg-slate-700 text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>

        <!-- Main Tabbed Panel -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
            
            <!-- Tab Headers -->
            <div class="flex items-center space-x-2 border-b border-slate-200/50 dark:border-slate-800/50 pb-4 mb-6">
                <button type="button" @click="activeTab = 'in'" :class="activeTab === 'in' ? 'bg-green-500/10 text-green-600 dark:bg-green-500/20 dark:text-green-400 font-bold' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-100'"
                    class="px-4 py-2 rounded-xl text-xs transition-all duration-200 heading-font uppercase tracking-wider">
                    <i class="fa-solid fa-circle-arrow-down mr-1"></i> Barang Masuk
                </button>
                <button type="button" @click="activeTab = 'out'" :class="activeTab === 'out' ? 'bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400 font-bold' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-100'"
                    class="px-4 py-2 rounded-xl text-xs transition-all duration-200 heading-font uppercase tracking-wider">
                    <i class="fa-solid fa-circle-arrow-up mr-1"></i> Barang Keluar
                </button>
            </div>

            <!-- Tab 1 Panel: Riwayat Barang Masuk -->
            <div x-show="activeTab === 'in'" class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                <th class="py-3 font-semibold w-10 text-center">#</th>
                                <th class="py-3 font-semibold">Tanggal</th>
                                <th class="py-3 font-semibold">Barang</th>
                                <th class="py-3 font-semibold text-center">Jumlah</th>
                                <th class="py-3 font-semibold">Pemasok</th>
                                <th class="py-3 font-semibold">Pencatat</th>
                                <th class="py-3 font-semibold">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            <?php $__empty_1 = true; $__currentLoopData = $stockIns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="py-3.5 text-center text-slate-400"><?php echo e(($stockIns->currentPage() - 1) * $stockIns->perPage() + $loop->iteration); ?></td>
                                <td class="py-3.5 font-medium whitespace-nowrap"><?php echo e($in->date); ?></td>
                                <td class="py-3.5 flex items-center space-x-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100"><?php echo e($in->product->name); ?></span>
                                    <span class="text-[9px] text-slate-400 font-mono"><?php echo e($in->product->sku); ?></span>
                                </td>
                                <td class="py-3.5 text-center font-bold text-green-600 dark:text-green-400">+<?php echo e($in->quantity); ?> pcs</td>
                                <td class="py-3.5 text-slate-500 dark:text-slate-400"><?php echo e($in->supplier ?: '-'); ?></td>
                                <td class="py-3.5 font-medium"><?php echo e($in->user->name); ?></td>
                                <td class="py-3.5 text-slate-400 truncate max-w-[150px]" title="<?php echo e($in->notes); ?>"><?php echo e($in->notes ?: '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">Tidak ada log barang masuk tercatat.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <?php echo e($stockIns->appends(request()->query())->links()); ?>

                </div>
            </div>

            <!-- Tab 2 Panel: Riwayat Barang Keluar -->
            <div x-show="activeTab === 'out'" class="space-y-4" style="display: none;">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400">
                                <th class="py-3 font-semibold w-10 text-center">#</th>
                                <th class="py-3 font-semibold">Tanggal</th>
                                <th class="py-3 font-semibold">Barang</th>
                                <th class="py-3 font-semibold text-center">Jumlah</th>
                                <th class="py-3 font-semibold">Alasan Keluar</th>
                                <th class="py-3 font-semibold">Pencatat</th>
                                <th class="py-3 font-semibold">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                            <?php $__empty_1 = true; $__currentLoopData = $stockOuts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $out): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="py-3.5 text-center text-slate-400"><?php echo e(($stockOuts->currentPage() - 1) * $stockOuts->perPage() + $loop->iteration); ?></td>
                                <td class="py-3.5 font-medium whitespace-nowrap"><?php echo e($out->date); ?></td>
                                <td class="py-3.5 flex items-center space-x-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100"><?php echo e($out->product->name); ?></span>
                                    <span class="text-[9px] text-slate-400 font-mono"><?php echo e($out->product->sku); ?></span>
                                </td>
                                <td class="py-3.5 text-center font-bold text-rose-600 dark:text-rose-400">-<?php echo e($out->quantity); ?> pcs</td>
                                <td class="py-3.5 font-semibold">
                                    <?php if($out->reason === 'rusak'): ?>
                                        <span class="text-rose-500 uppercase text-[9px] px-2 py-0.5 bg-rose-500/10 rounded">Rusak</span>
                                    <?php elseif($out->reason === 'hilang'): ?>
                                        <span class="text-amber-500 uppercase text-[9px] px-2 py-0.5 bg-amber-500/10 rounded">Hilang</span>
                                    <?php elseif($out->reason === 'expired'): ?>
                                        <span class="text-orange-500 uppercase text-[9px] px-2 py-0.5 bg-orange-500/10 rounded">Expired</span>
                                    <?php else: ?>
                                        <span class="text-indigo-500 uppercase text-[9px] px-2 py-0.5 bg-indigo-500/10 rounded">Penyesuaian</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 font-medium"><?php echo e($out->user->name); ?></td>
                                <td class="py-3.5 text-slate-400 truncate max-w-[150px]" title="<?php echo e($out->notes); ?>"><?php echo e($out->notes ?: '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">Tidak ada log barang keluar tercatat.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <?php echo e($stockOuts->appends(request()->query())->links()); ?>

                </div>
            </div>

        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\syarat kp\project kp\resources\views/stock/index.blade.php ENDPATH**/ ?>