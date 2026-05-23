<?php $__env->startSection('title', $product->name); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-12 animate-fade-in">
    
    <!-- Breadcrumb back link -->
    <div class="flex items-center space-x-2 text-xs text-slate-400">
        <a href="<?php echo e(route('catalog.index')); ?>" class="hover:text-indigo-600 font-semibold transition-colors"><i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Katalog</a>
    </div>

    <!-- Product Details Main Layout (2 Columns Grid) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
        
        <!-- Column 1: Big Product Image -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-5 shadow-sm">
            <div class="w-full h-80 md:h-[400px] rounded-2xl overflow-hidden border dark:border-slate-850 bg-slate-100 shadow-inner">
                <img src="<?php echo e($product->image_url); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Column 2: Specs Info & Action WhatsApp -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
            
            <!-- Category, Name & SKU -->
            <div class="space-y-2 border-b border-slate-100 dark:border-slate-800/60 pb-5">
                <span class="px-2.5 py-1 bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 rounded-lg text-[9px] font-extrabold uppercase tracking-wider heading-font"><?php echo e($product->category->name); ?></span>
                <h1 class="text-xl md:text-2xl font-bold tracking-tight text-slate-800 dark:text-slate-100 heading-font mt-2"><?php echo e($product->name); ?></h1>
                <div class="flex items-center space-x-3 text-[10px] text-slate-400 mt-2 font-mono">
                    <span>Kode SKU: <strong><?php echo e($product->sku); ?></strong></span>
                    <span>•</span>
                    <span>Status: 
                        <strong class="text-green-600 dark:text-green-400 font-bold uppercase text-[9px]">Tersedia</strong>
                    </span>
                </div>
            </div>

            <!-- Price -->
            <div class="space-y-1">
                <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Harga Jual Produk</span>
                <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 heading-font">
                    <?php echo e(\App\Models\Setting::get('shop_currency', 'Rp')); ?> <?php echo e(number_format($product->price, 0, ',', '.')); ?>

                </h3>
            </div>

            <!-- Description -->
            <div class="space-y-2">
                <span class="text-[9px] text-slate-400 uppercase font-bold tracking-wider">Deskripsi & Spesifikasi</span>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-normal whitespace-pre-line"><?php echo e($product->description ?: 'Spesifikasi produk ini belum dicantumkan secara lengkap oleh admin toko.'); ?></p>
            </div>

            <!-- WhatsApp Order button trigger -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800/60">
                <a href="<?php echo e($waLink); ?>" target="_blank"
                    class="w-full py-3.5 bg-green-500 hover:bg-green-600 text-white font-extrabold rounded-2xl text-xs uppercase tracking-wider text-center shadow-lg shadow-green-500/20 hover:shadow-xl transition-all flex items-center justify-center space-x-2">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    <span>Pesan Langsung via WhatsApp</span>
                </a>
                <span class="text-[9px] text-slate-400 block text-center mt-2.5"><i class="fa-solid fa-info-circle mr-0.5"></i> Setelah menekan tombol, Anda akan diarahkan langsung ke WhatsApp admin dengan draf pesan pesanan otomatis.</span>
            </div>

        </div>

    </div>

    <!-- Related Products Section (Horizontal Showcase) -->
    <?php if(!$relatedProducts->isEmpty()): ?>
    <div class="space-y-6 pt-6">
        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font">Barang Lain yang Mungkin Anda Suka</h4>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-4 shadow-sm flex flex-col justify-between hover:scale-[1.01] transition-transform duration-250 group">
                
                <a href="<?php echo e(route('catalog.show', $rel->slug)); ?>" class="space-y-4">
                    <div class="relative w-full h-36 rounded-2xl overflow-hidden border dark:border-slate-850 bg-slate-100 shadow-inner">
                        <img src="<?php echo e($rel->image_url); ?>" alt="<?php echo e($rel->name); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="space-y-1">
                        <span class="text-[9px] uppercase font-bold text-indigo-600 dark:text-indigo-400 tracking-wider heading-font"><?php echo e($rel->category->name); ?></span>
                        <h5 class="font-bold text-xs text-slate-800 dark:text-slate-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors"><?php echo e($rel->name); ?></h5>
                    </div>
                </a>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                    <span class="font-bold text-xs text-slate-800 dark:text-slate-100">
                        <?php echo e(\App\Models\Setting::get('shop_currency', 'Rp')); ?> <?php echo e(number_format($rel->price, 0, ',', '.')); ?>

                    </span>
                    <a href="<?php echo e(route('catalog.show', $rel->slug)); ?>" class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                        Detail <i class="fa-solid fa-chevron-right text-[8px] ml-0.5"></i>
                    </a>
                </div>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\syarat kp\project kp\resources\views/catalog/show.blade.php ENDPATH**/ ?>