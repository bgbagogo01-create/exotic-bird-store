<?php $__env->startSection('title', 'Katalog Belanja'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 animate-fade-in">
    
    <!-- Hero / Greeting Banner Section -->
    <div class="text-center space-y-3 max-w-xl mx-auto py-4">
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight heading-font bg-gradient-to-r from-indigo-600 to-blue-600 dark:from-indigo-400 dark:to-blue-400 bg-clip-text text-transparent">Katalog Produk Modern</h1>
        <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-normal">Temukan barang berkualitas dengan harga terbaik. Pesan langsung dengan mudah via WhatsApp admin kami.</p>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm max-w-3xl mx-auto space-y-6">
        <!-- Search Form -->
        <form action="<?php echo e(route('catalog.index')); ?>" method="GET" class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
            <input type="text" name="q" value="<?php echo e(request()->get('q')); ?>" placeholder="Cari barang impian Anda di sini..."
                class="w-full pl-11 pr-24 py-3 rounded-2xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all shadow-inner">
            
            <button type="submit" class="absolute top-1.5 right-1.5 py-1.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-all shadow shadow-indigo-600/10">
                Cari
            </button>
        </form>

        <!-- Category Pills Filter (Horizontal Scroll) -->
        <div class="space-y-2">
            <span class="text-[9px] uppercase font-bold text-slate-400 dark:text-slate-500 tracking-widest heading-font">Pilih Kategori</span>
            <div class="flex items-center space-x-2 overflow-x-auto pb-2 scrollbar-none whitespace-nowrap">
                <!-- 'Semua' pill -->
                <a href="<?php echo e(route('catalog.index', request()->only('q'))); ?>" 
                    class="px-4 py-1.5 rounded-full text-xs font-bold transition-all border <?php echo e(!request()->has('category') ? 'bg-indigo-600 text-white border-indigo-600 shadow shadow-indigo-600/10' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700'); ?>">
                    Semua Produk
                </a>

                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!-- Category specific pill -->
                    <a href="<?php echo e(route('catalog.index', array_merge(request()->only('q'), ['category' => $cat->slug]))); ?>" 
                        class="px-4 py-1.5 rounded-full text-xs font-bold transition-all border <?php echo e(request()->get('category') === $cat->slug ? 'bg-indigo-600 text-white border-indigo-600 shadow shadow-indigo-600/10' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700'); ?>">
                        <?php echo e($cat->name); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Product Showcase Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 pt-4">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-4 shadow-sm flex flex-col justify-between hover:scale-[1.01] transition-transform duration-250 group">
            
            <!-- Link to detailed page -->
            <a href="<?php echo e(route('catalog.show', $prod->slug)); ?>" class="space-y-4">
                <!-- Product Image box -->
                <div class="relative w-full h-44 rounded-2xl overflow-hidden border dark:border-slate-850 bg-slate-100 shadow-inner">
                    <img src="<?php echo e($prod->image_url); ?>" alt="<?php echo e($prod->name); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <span class="absolute top-2 left-2 px-2 py-0.5 bg-black/60 backdrop-blur text-white text-[8px] font-mono font-bold rounded">
                        <?php echo e($prod->sku); ?>

                    </span>
                </div>

                <!-- Label & Title -->
                <div class="space-y-1">
                    <span class="text-[9px] uppercase font-bold text-indigo-600 dark:text-indigo-400 tracking-wider heading-font"><?php echo e($prod->category->name); ?></span>
                    <h3 class="font-bold text-xs text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1" title="<?php echo e($prod->name); ?>"><?php echo e($prod->name); ?></h3>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 line-clamp-2 leading-relaxed font-normal"><?php echo e($prod->description ?: 'Spesifikasi detail produk dapat dilihat di halaman selengkapnya.'); ?></p>
                </div>
            </a>

            <!-- Bottom Price & Buy Action row -->
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                <div>
                    <span class="text-[8px] text-slate-400 block uppercase font-semibold">Harga</span>
                    <span class="font-extrabold text-xs text-slate-800 dark:text-slate-100">
                        <?php echo e(\App\Models\Setting::get('shop_currency', 'Rp')); ?> <?php echo e(number_format($prod->price, 0, ',', '.')); ?>

                    </span>
                </div>
                
                <span class="px-2 py-0.5 bg-green-500/10 text-green-600 dark:bg-green-500/20 dark:text-green-400 rounded-full font-bold text-[8px] uppercase tracking-wide">
                    <i class="fa-solid fa-circle-check mr-0.5"></i> Tersedia
                </span>
            </div>

            <!-- Buttons -->
            <div class="grid grid-cols-2 gap-2 mt-4">
                <a href="<?php echo e(route('catalog.show', $prod->slug)); ?>" 
                    class="py-2 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-xl text-[10px] uppercase tracking-wider text-center transition-colors">
                    Detail
                </a>
                
                <?php
                    $adminPhone = \App\Models\Setting::get('shop_phone', '6281234567890');
                    $msgText = "Halo Admin, saya ingin memesan barang:\n\nNama: *{$prod->name}*\nSKU: *{$prod->sku}*\n\nApakah barang ini masih ready?";
                    $quickWaLink = "https://wa.me/{$adminPhone}?text=" . urlencode($msgText);
                ?>
                <a href="<?php echo e($quickWaLink); ?>" target="_blank"
                    class="py-2 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl text-[10px] uppercase tracking-wider text-center shadow-md shadow-green-500/10 transition-colors flex items-center justify-center space-x-1">
                    <i class="fa-brands fa-whatsapp text-xs"></i>
                    <span>Pesan</span>
                </a>
            </div>

        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full bg-white/60 dark:bg-slate-900/60 border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-12 text-center text-slate-400 dark:text-slate-500 max-w-md mx-auto">
            <i class="fa-solid fa-store-slash text-4xl mb-3 text-slate-350 block"></i>
            <h4 class="font-bold text-xs heading-font">Barang Tidak Ditemukan</h4>
            <p class="text-[10px] text-slate-400 mt-1 leading-relaxed">Produk yang Anda cari tidak tersedia, atau telah habis terjual. Silakan bersihkan kata kunci pencarian Anda.</p>
            <a href="<?php echo e(route('catalog.index')); ?>" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-xl text-[10px] uppercase tracking-wider mt-4 inline-block shadow shadow-indigo-600/10">Lihat Semua Produk</a>
        </div>
        <?php endif; ?>
    </div>

    <!-- Catalog Pagination -->
    <div class="pt-6">
        <?php echo e($products->appends(request()->query())->links()); ?>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.store', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\syarat kp\project kp\resources\views/catalog/index.blade.php ENDPATH**/ ?>