@extends('layouts.app')

@section('title', 'Kelola Barang')
@section('page_title', 'Kelola Barang (Inventaris)')

@section('content')
<div class="space-y-6" x-data="{ 
    modalOpen: {{ $errors->any() ? 'true' : 'false' }}, 
    modalTitle: '{{ old('_method') === 'PUT' ? 'Edit Data Barang' : 'Tambah Barang Baru' }}', 
    isEdit: {{ old('_method') === 'PUT' ? 'true' : 'false' }}, 
    actionUrl: '{{ old('_method') === 'PUT' ? route('products.update', old('id', 0)) : route('products.store') }}', 
    methodType: 'POST', 
    prodId: '{{ old('id') }}', 
    prodName: '{{ old('name') }}', 
    prodSku: '{{ old('sku') }}', 
    prodCat: '{{ old('category_id') }}', 
    prodDesc: '{{ old('description') }}', 
    prodPrice: '{{ old('price') }}', 
    prodStock: '{{ old('stock') }}', 
    prodMinStock: '{{ old('min_stock') }}', 
    oldImage: '{{ old('old_image') }}' 
}">
    
    <!-- Top Action Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold tracking-tight heading-font">Daftar Barang</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola data produk, harga jual, tingkat ketersediaan stok, dan status gudang.</p>
        </div>
        
        <!-- Add Button (Only Admin can manage products, Cashier can view) -->
        @if(Auth::user()->isAdmin())
        <button @click="modalOpen = true; isEdit = false; modalTitle = 'Tambah Barang Baru'; actionUrl = '{{ route('products.store') }}'; methodType = 'POST'; prodId=''; prodName=''; prodSku=''; prodCat=''; prodDesc=''; prodPrice=''; prodStock=''; prodMinStock=''; oldImage=''" 
            class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/10 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Barang Baru</span>
        </button>
        @endif
    </div>

    <!-- Filter Dashboard Card -->
    <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
        <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <!-- Search Query -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Pencarian Kata Kunci</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request()->get('search') }}" placeholder="Nama, SKU, atau deskripsi..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                </div>
            </div>

            <!-- Category Filter -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Saring Kategori</label>
                <select name="category" class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request()->get('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="space-y-1">
                <label class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest heading-font">Status Ketersediaan</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="tersedia" {{ request()->get('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="habis" {{ request()->get('status') == 'habis' ? 'selected' : '' }}>Habis / Kosong</option>
                </select>
            </div>

            <!-- Action buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                    <i class="fa-solid fa-filter mr-1"></i> Saring
                </button>
                <a href="{{ route('products.index') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-xl text-xs transition-all hover:bg-slate-200 dark:hover:bg-slate-700 text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Main Products Table Card -->
    <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 uppercase tracking-wider">
                        <th class="py-3 font-semibold w-12 text-center">#</th>
                        <th class="py-3 font-semibold">Nama Barang</th>
                        <th class="py-3 font-semibold">Kode SKU</th>
                        <th class="py-3 font-semibold">Kategori</th>
                        <th class="py-3 font-semibold">Harga Jual</th>
                        <th class="py-3 font-semibold text-center">Batas Min</th>
                        <th class="py-3 font-semibold text-center">Stok</th>
                        <th class="py-3 font-semibold text-center w-24">Status</th>
                        @if(Auth::user()->isAdmin())
                        <th class="py-3 font-semibold text-right w-28">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60" id="productTableBody">
                    @forelse($products as $product)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                        <td class="py-3.5 text-center text-slate-400 font-medium">
                            {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                        </td>
                        <td class="py-3.5 flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-xl overflow-hidden border dark:border-slate-850 shrink-0 bg-slate-100 shadow-sm relative group">
                                <img src="{{ $product->image_url }}" alt="Barang" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <span class="font-bold text-slate-800 dark:text-slate-100 truncate block">{{ $product->name }}</span>
                                <span class="text-[10px] text-slate-400 truncate block">{{ $product->slug }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 font-mono text-[10px] font-semibold tracking-wider">{{ $product->sku }}</td>
                        <td class="py-3.5 text-slate-500 dark:text-slate-400">{{ $product->category->name }}</td>
                        <td class="py-3.5 font-bold">
                            {{ \App\Models\Setting::get('shop_currency', 'Rp') }} {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 text-center font-medium text-slate-400">{{ $product->min_stock }} pcs</td>
                        <td class="py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded-full font-bold {{ $product->stock <= $product->min_stock ? 'bg-rose-500/10 text-rose-500' : 'bg-indigo-500/10 text-indigo-500' }}">
                                {{ $product->stock }} pcs
                            </span>
                        </td>
                        <td class="py-3.5 text-center">
                            @if($product->status === 'tersedia')
                                <span class="px-2.5 py-0.5 bg-green-500/10 text-green-600 dark:bg-green-500/20 dark:text-green-400 rounded-full font-semibold uppercase text-[9px] tracking-wide">
                                    <i class="fa-solid fa-circle-check mr-0.5"></i> Tersedia
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400 rounded-full font-semibold uppercase text-[9px] tracking-wide">
                                    <i class="fa-solid fa-circle-xmark mr-0.5"></i> Habis
                                </span>
                            @endif
                        </td>
                        
                        @if(Auth::user()->isAdmin())
                        <td class="py-3.5 text-right space-x-1.5 whitespace-nowrap">
                            <!-- Edit Button -->
                            <button type="button" @click="modalOpen = true; isEdit = true; modalTitle = 'Edit Data Barang'; actionUrl = '{{ route('products.update', $product->id) }}'; methodType = 'POST'; prodId='{{ $product->id }}'; prodName='{{ $product->name }}'; prodSku='{{ $product->sku }}'; prodCat='{{ $product->category_id }}'; prodDesc='{{ $product->description }}'; prodPrice='{{ $product->price }}'; prodStock='{{ $product->stock }}'; prodMinStock='{{ $product->min_stock }}'; oldImage='{{ $product->image_url }}'" 
                                class="px-2.5 py-1.5 bg-blue-500/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 hover:bg-blue-500 hover:text-white rounded-lg transition-colors inline-block" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <!-- Delete Form -->
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" id="delete-form-{{ $product->id }}" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-form-{{ $product->id }}', '{{ $product->name }}')" 
                                    class="px-2.5 py-1.5 bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->isAdmin() ? 9 : 8 }}" class="py-8 text-center text-slate-400 dark:text-slate-500">Tidak ada data produk ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->appends(request()->query())->links() }}
        </div>

    </div>

    <!-- Dynamic Glassmorphic Modal Uploader Overlay -->
    <div class="fixed inset-0 z-40 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-sm transition-opacity duration-300" 
        x-show="modalOpen" x-transition.opacity style="display: none;">
        
        <!-- Modal Panel -->
        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-2xl border border-white/20 dark:border-slate-800/60 rounded-3xl w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl p-6 md:p-8 transform transition-all"
            x-show="modalOpen" x-transition.scale.95>
            
            <div class="flex items-center justify-between border-b border-slate-200/50 dark:border-slate-800/50 pb-4 mb-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font" x-text="modalTitle">Tambah Barang Baru</h3>
                <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form :action="actionUrl" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="hidden" name="id" x-model="prodId">
                <input type="hidden" name="old_image" x-model="oldImage">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Product Name -->
                    <div class="space-y-1">
                        <label for="modal_name" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Nama Barang</label>
                        <input type="text" name="name" id="modal_name" x-model="prodName" required placeholder="Contoh: Asus Vivobook"
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                        @error('name')
                            <span class="text-[10px] text-rose-500 font-semibold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- SKU Code -->
                    <div class="space-y-1">
                        <label for="modal_sku" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Kode SKU</label>
                        <input type="text" name="sku" id="modal_sku" x-model="prodSku" required placeholder="Contoh: LAP-ASUS-001"
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                        @error('sku')
                            <span class="text-[10px] text-rose-500 font-semibold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Category Select -->
                    <div class="space-y-1">
                        <label for="modal_cat" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Kategori</label>
                        <select name="category_id" id="modal_cat" x-model="prodCat" required
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-[10px] text-rose-500 font-semibold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Price Input -->
                    <div class="space-y-1">
                        <label for="modal_price" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Harga Jual (Rp)</label>
                        <input type="number" min="0" name="price" id="modal_price" x-model="prodPrice" required placeholder="Contoh: 7500000"
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                        @error('price')
                            <span class="text-[10px] text-rose-500 font-semibold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Initial Stock (Admin can set only) -->
                    <div class="space-y-1">
                        <label for="modal_stock" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Stok Barang</label>
                        <input type="number" min="0" name="stock" id="modal_stock" x-model="prodStock" required placeholder="Contoh: 15"
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                        @error('stock')
                            <span class="text-[10px] text-rose-500 font-semibold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Minimum Stock Threshold -->
                    <div class="space-y-1">
                        <label for="modal_min_stock" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Batas Minim Stok Alert</label>
                        <input type="number" min="0" name="min_stock" id="modal_min_stock" x-model="prodMinStock" required placeholder="Contoh: 5"
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                        @error('min_stock')
                            <span class="text-[10px] text-rose-500 font-semibold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-1">
                    <label for="modal_desc" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Deskripsi Produk (Opsional)</label>
                    <textarea name="description" id="modal_desc" x-model="prodDesc" rows="3" placeholder="Keterangan spesifikasi barang..."
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all"></textarea>
                    @error('description')
                        <span class="text-[10px] text-rose-500 font-semibold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Image Uploader with Preview -->
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Foto Barang</label>
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-xl overflow-hidden border dark:border-slate-800 shrink-0 bg-slate-100">
                            <!-- Preview local uploaded image or old image fallback -->
                            <img :src="oldImage ? oldImage : 'https://ui-avatars.com/api/?name=Barang&background=6366f1&color=fff'" 
                                id="modalImagePreview" class="w-full h-full object-cover">
                        </div>
                        <input type="file" name="image" id="modalImageInput" accept="image/*"
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-indigo-500/10 file:text-indigo-600 dark:file:bg-indigo-500/20 dark:file:text-indigo-400 hover:file:bg-indigo-500 hover:file:text-white file:cursor-pointer transition-colors duration-200">
                    </div>
                    @error('image')
                        <span class="text-[10px] text-rose-500 font-semibold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Action Button Footer -->
                <div class="flex justify-end space-x-2 pt-4 border-t border-slate-200/50 dark:border-slate-800/50">
                    <button type="button" @click="modalOpen = false" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold rounded-xl text-xs transition-all">
                        Kembali
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/10 transition-all">
                        Simpan Data
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Live Modal Image Preview FileReader
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById('modalImageInput');
        const previewImg = document.getElementById('modalImagePreview');
        
        if(fileInput && previewImg) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.addEventListener('load', function() {
                        previewImg.setAttribute('src', this.result);
                    });
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endsection
