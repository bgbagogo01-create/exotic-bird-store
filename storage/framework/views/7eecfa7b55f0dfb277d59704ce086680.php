<?php $__env->startSection('title', 'Aplikasi POS (Kasir)'); ?>
<?php $__env->startSection('page_title', 'Aplikasi POS (Kasir)'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    /* Custom styling for compact checkout items */
    .cart-scroll {
        max-height: calc(100vh - 430px);
        min-height: 250px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" id="posContainer">
    
    <!-- Left Section: Cashier Product Catalog Grid (2 Columns Widescreen) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Search & Category Filter Bar -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="w-full sm:w-72 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" id="posSearch" placeholder="Cari barang atau SKU..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
            </div>
            
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <i class="fa-solid fa-tags text-xs text-slate-400"></i>
                <select id="posCategoryFilter" class="w-full sm:w-48 px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                    <option value="">Semua Kategori</option>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <!-- Dynamic categories extract from collection -->
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $extractedCategories = $products->pluck('category')->unique('id');
                    ?>
                    <?php $__currentLoopData = $extractedCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <!-- Interactive Product Catalog Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6" id="posProductGrid">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="pos-product-card bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-5 shadow-sm hover:scale-[1.01] transition-transform duration-200 flex flex-col justify-between" 
                data-id="<?php echo e($prod->id); ?>" 
                data-name="<?php echo e($prod->name); ?>" 
                data-sku="<?php echo e($prod->sku); ?>" 
                data-price="<?php echo e($prod->price); ?>" 
                data-stock="<?php echo e($prod->stock); ?>" 
                data-category="<?php echo e($prod->category_id); ?>">
                
                <div>
                    <!-- Product Image & SKU Frame -->
                    <div class="relative w-full h-36 rounded-2xl overflow-hidden border dark:border-slate-850 bg-slate-100 mb-4">
                        <img src="<?php echo e($prod->image_url); ?>" alt="Product Image" class="w-full h-full object-cover">
                        <span class="absolute top-2 left-2 px-2 py-0.5 bg-black/60 backdrop-blur text-white text-[8px] font-mono font-bold uppercase tracking-wider rounded">
                            <?php echo e($prod->sku); ?>

                        </span>
                    </div>

                    <!-- Category & Title -->
                    <div class="space-y-1">
                        <span class="text-[9px] uppercase font-bold text-indigo-600 dark:text-indigo-400 tracking-wider heading-font"><?php echo e($prod->category->name); ?></span>
                        <h3 class="font-bold text-xs text-slate-800 dark:text-slate-100 truncate" title="<?php echo e($prod->name); ?>"><?php echo e($prod->name); ?></h3>
                    </div>
                </div>

                <!-- Price, Stock & Add Button -->
                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                    <div>
                        <span class="text-[9px] text-slate-400 block uppercase font-semibold">Harga Jual</span>
                        <span class="font-extrabold text-xs text-indigo-600 dark:text-indigo-400"><?php echo e($shopCurrency); ?> <?php echo e(number_format($prod->price, 0, ',', '.')); ?></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] text-slate-400 block uppercase font-semibold">Tersedia</span>
                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100"><?php echo e($prod->stock); ?> pcs</span>
                    </div>
                </div>

                <button type="button" onclick="addToCart(<?php echo e($prod->id); ?>, '<?php echo e(addslashes($prod->name)); ?>', '<?php echo e($prod->sku); ?>', <?php echo e($prod->price); ?>, <?php echo e($prod->stock); ?>)"
                    class="w-full mt-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-[10px] uppercase tracking-wider shadow-md shadow-indigo-600/10 transition-colors flex items-center justify-center space-x-1">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Tambah</span>
                </button>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full bg-white/60 dark:bg-slate-900/60 border rounded-3xl p-10 text-center text-slate-400 dark:text-slate-500">
                <i class="fa-solid fa-store-slash text-3xl mb-3 block text-slate-300"></i>
                <span class="text-xs">Tidak ada produk tersedia untuk transaksi POS saat ini.</span>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <!-- Right Section: Point-of-Sale POS Shopping Cart (1 Column Layout) -->
    <div class="lg:col-span-1">
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm flex flex-col justify-between sticky top-6">
            
            <form action="<?php echo e(route('transactions.store')); ?>" method="POST" id="posCheckoutForm" class="space-y-5">
                <?php echo csrf_field(); ?>
                <!-- Cart JSON payload -->
                <input type="hidden" name="cart" id="hiddenCartInput">
                
                <div class="border-b border-slate-200/50 dark:border-slate-800/50 pb-3 flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font">Keranjang POS</h4>
                    <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 rounded text-[9px] font-bold" id="cartBadgeCount">0 Item</span>
                </div>

                <!-- Pelanggan Name -->
                <div class="space-y-1.5">
                    <label for="buyer_name" class="text-[9px] font-bold text-slate-400 uppercase tracking-widest heading-font">Nama Pembeli / Pelanggan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fa-solid fa-user-tag text-xs"></i>
                        </span>
                        <input type="text" name="buyer_name" id="buyer_name" placeholder="Pelanggan Umum"
                            class="w-full pl-9 pr-4 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Shopping Cart Scrolling List -->
                <div class="cart-scroll overflow-y-auto pr-1 space-y-3" id="cartItemList">
                    <!-- Empty Cart Message Placeholder -->
                    <div class="h-full flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 py-10" id="emptyCartPlaceholder">
                        <i class="fa-solid fa-cart-arrow-down text-3xl mb-2 text-slate-300"></i>
                        <span class="text-center text-[10px]">Keranjang belanja kosong.<br>Klik tombol "Tambah" di katalog produk.</span>
                    </div>
                </div>

                <!-- Financial Calculation Totals -->
                <div class="border-t border-slate-200/50 dark:border-slate-800/50 pt-4 space-y-2.5 text-xs">
                    
                    <!-- Subtotal row -->
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-medium">Subtotal</span>
                        <span class="font-bold" id="billSubtotal"><?php echo e($shopCurrency); ?> 0</span>
                    </div>

                    <!-- Discount Input row -->
                    <div class="flex justify-between items-center">
                        <span class="text-slate-400 font-medium">Potongan Diskon (<?php echo e($shopCurrency); ?>)</span>
                        <input type="number" min="0" name="discount" id="posDiscountInput" value="0"
                            class="w-24 px-2 py-1 text-right rounded-lg bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    </div>

                    <div class="h-[1px] bg-slate-200/50 dark:bg-slate-850/50 my-2"></div>

                    <!-- Grand Total row -->
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-bold heading-font uppercase text-[10px] text-slate-400 tracking-wider">Total Pembayaran</span>
                        <span class="font-extrabold text-indigo-600 dark:text-indigo-400" id="billGrandTotal"><?php echo e($shopCurrency); ?> 0</span>
                    </div>

                    <!-- Cash Pay Input row -->
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider heading-font">Jumlah Uang Bayar</span>
                        <input type="number" min="0" name="pay_amount" id="posPayInput" required value="0"
                            class="w-32 px-3 py-1.5 text-right font-extrabold text-sm text-green-600 dark:text-green-400 rounded-xl bg-white dark:bg-slate-950/80 border-2 border-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    </div>

                    <!-- Change returned row -->
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-slate-400 font-medium">Kembalian</span>
                        <span class="font-bold text-slate-700 dark:text-slate-300" id="billChangeAmount"><?php echo e($shopCurrency); ?> 0</span>
                    </div>

                </div>

                <!-- Submit POS Checkout -->
                <button type="button" id="posCheckoutBtn" onclick="processCheckoutSubmit()"
                    class="w-full mt-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/10 hover:shadow-xl transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-cash-register"></i>
                    <span>Proses Checkout Transaksi</span>
                </button>

            </form>
        </div>
    </div>

</div>

<!-- Thermal Receipt Auto-Print Modal Trigger -->
<?php if(session('print_receipt_id')): ?>
<div class="hidden">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Auto open receipt in printable new window popup!
            window.open("<?php echo e(route('transactions.receipt', session('print_receipt_id'))); ?>", "Cetak Struk", "width=400,height=600,scrollbars=yes");
        });
    </script>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // In-memory Cart model state
    let cart = [];
    const currency = "<?php echo e($shopCurrency); ?>";
    let emptyCartHtml = '';

    document.addEventListener("DOMContentLoaded", function() {
        const placeholder = document.getElementById('emptyCartPlaceholder');
        if (placeholder) {
            emptyCartHtml = placeholder.outerHTML;
        }
    });

    // 1. Add item to cart
    function addToCart(productId, name, sku, price, stock) {
        if (stock <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Stok Habis!',
                text: `Barang '${name}' tidak dapat ditambahkan karena kehabisan stok gudang.`,
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        const existingItem = cart.find(item => item.product_id === productId);

        if (existingItem) {
            if (existingItem.quantity >= stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Batas Stok Terlampaui!',
                    text: `Jumlah belanja untuk '${name}' sudah mencapai stok maksimum yang tersedia (${stock} pcs).`,
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }
            existingItem.quantity += 1;
        } else {
            cart.push({
                product_id: productId,
                name: name,
                sku: sku,
                price: parseFloat(price),
                stock: parseInt(stock),
                quantity: 1
            });
        }

        updateCartUI();
        
        // Soft Toast notify
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: false
        });
        Toast.fire({
            icon: 'success',
            title: `+ '${name}' ditambahkan`
        });
    }

    // 2. Modify cart quantity
    function updateQty(productId, delta) {
        const item = cart.find(i => i.product_id === productId);
        if (!item) return;

        const newQty = item.quantity + delta;

        if (newQty <= 0) {
            removeFromCart(productId);
            return;
        }

        if (newQty > item.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Batas Stok Terlampaui!',
                text: `Jumlah belanja untuk '${item.name}' sudah mencapai stok maksimum yang tersedia (${item.stock} pcs).`,
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        item.quantity = newQty;
        updateCartUI();
    }

    // 3. Remove item from cart
    function removeFromCart(productId) {
        cart = cart.filter(item => item.product_id !== productId);
        updateCartUI();
    }

    // 4. Update POS Cart UI panel and compute financials
    function updateCartUI() {
        const cartList = document.getElementById('cartItemList');
        const emptyPlaceholder = document.getElementById('emptyCartPlaceholder');
        const badgeCount = document.getElementById('cartBadgeCount');
        const subtotalSpan = document.getElementById('billSubtotal');
        const grandTotalSpan = document.getElementById('billGrandTotal');
        const changeSpan = document.getElementById('billChangeAmount');
        const discountInput = document.getElementById('posDiscountInput');
        const payInput = document.getElementById('posPayInput');

        // Toggle empty placeholder
        if (cart.length === 0) {
            cartList.innerHTML = emptyCartHtml;
            badgeCount.innerText = '0 Item';
            subtotalSpan.innerText = `${currency} 0`;
            grandTotalSpan.innerText = `${currency} 0`;
            changeSpan.innerText = `${currency} 0`;
            return;
        }

        // Clean table
        cartList.innerHTML = '';

        let subtotal = 0;
        let totalItems = 0;

        cart.forEach(item => {
            const itemSubtotal = item.price * item.quantity;
            subtotal += itemSubtotal;
            totalItems += item.quantity;

            // Generate HTML item row
            const div = document.createElement('div');
            div.className = "flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800/40 rounded-2xl border dark:border-slate-850 animate-fade-in";
            div.innerHTML = `
                <div class="flex-1 min-w-0 pr-2">
                    <h5 class="font-bold truncate text-[11px]">${item.name}</h5>
                    <span class="text-[9px] text-slate-400 font-mono block">${item.sku} | ${currency} ${item.price.toLocaleString('id-ID')}</span>
                </div>
                <div class="flex items-center space-x-2 shrink-0">
                    <button type="button" onclick="updateQty(${item.product_id}, -1)" class="w-6 h-6 bg-slate-200 dark:bg-slate-700 hover:bg-slate-350 dark:hover:bg-slate-650 rounded-lg flex items-center justify-center font-bold text-xs">-</button>
                    <span class="text-xs font-bold w-6 text-center">${item.quantity}</span>
                    <button type="button" onclick="updateQty(${item.product_id}, 1)" class="w-6 h-6 bg-slate-200 dark:bg-slate-700 hover:bg-slate-350 dark:hover:bg-slate-650 rounded-lg flex items-center justify-center font-bold text-xs">+</button>
                    <button type="button" onclick="removeFromCart(${item.product_id})" class="text-rose-500 hover:text-rose-600 pl-1"><i class="fa-solid fa-trash-can text-sm"></i></button>
                </div>
            `;
            cartList.appendChild(div);
        });

        // Compute Financial math
        badgeCount.innerText = `${totalItems} Pcs`;
        subtotalSpan.innerText = `${currency} ${subtotal.toLocaleString('id-ID')}`;

        const discount = parseFloat(discountInput.value) || 0;
        let grandTotal = subtotal - discount;
        if (grandTotal < 0) grandTotal = 0;

        grandTotalSpan.innerText = `${currency} ${grandTotal.toLocaleString('id-ID')}`;

        // Compute change
        const payAmount = parseFloat(payInput.value) || 0;
        let change = payAmount - grandTotal;
        if (change < 0) change = 0;

        changeSpan.innerText = `${currency} ${change.toLocaleString('id-ID')}`;
    }

    // 5. Watch financial inputs (Live calculations)
    document.getElementById('posDiscountInput').addEventListener('input', updateCartUI);
    document.getElementById('posPayInput').addEventListener('input', updateCartUI);

    // 6. POS Search Filter engine
    document.getElementById('posSearch').addEventListener('input', function() {
        const searchVal = this.value.toLowerCase().trim();
        filterGrid(searchVal, document.getElementById('posCategoryFilter').value);
    });

    document.getElementById('posCategoryFilter').addEventListener('change', function() {
        const catVal = this.value;
        filterGrid(document.getElementById('posSearch').value.toLowerCase().trim(), catVal);
    });

    function filterGrid(search, category) {
        const cards = document.querySelectorAll('.pos-product-card');
        
        cards.forEach(card => {
            const name = card.getAttribute('data-name').toLowerCase();
            const sku = card.getAttribute('data-sku').toLowerCase();
            const catId = card.getAttribute('data-category');

            const matchesSearch = name.includes(search) || sku.includes(search);
            const matchesCategory = category === "" || catId === category;

            if (matchesSearch && matchesCategory) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // 7. Validate and Submit POS checkout
    function processCheckoutSubmit() {
        if (cart.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Keranjang Kosong!',
                text: 'Silakan tambahkan barang ke dalam keranjang POS terlebih dahulu.',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        const discount = parseFloat(document.getElementById('posDiscountInput').value) || 0;
        const payInput = document.getElementById('posPayInput');
        const payAmount = parseFloat(payInput.value) || 0;

        // Calculate grand total again
        let subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        let grandTotal = subtotal - discount;
        if (grandTotal < 0) grandTotal = 0;

        if (payAmount < grandTotal) {
            Swal.fire({
                icon: 'error',
                title: 'Uang Kurang!',
                text: `Jumlah pembayaran (Rp ${payAmount.toLocaleString('id-ID')}) kurang dari total tagihan (Rp ${grandTotal.toLocaleString('id-ID')}).`,
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        // Set serialized json string in hidden input
        const serializedCart = cart.map(item => ({
            product_id: item.product_id,
            quantity: item.quantity
        }));
        
        document.getElementById('hiddenCartInput').value = JSON.stringify(serializedCart);
        
        // Submit
        document.getElementById('posCheckoutForm').submit();
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\syarat kp\project kp\resources\views/transactions/pos.blade.php ENDPATH**/ ?>