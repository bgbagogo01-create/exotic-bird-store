@extends('layouts.app')

@section('title', 'Mutasi & Log Stok')
@section('page_title', 'Mutasi & Log Stok (Gudang)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ activeTab: 'in' }">
    
    <!-- Left Column: Input Forms (Barang Masuk & Keluar) -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Card: Input Barang Masuk -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
            <h4 class="text-sm font-bold uppercase tracking-wider text-green-600 dark:text-green-400 heading-font mb-4">
                <i class="fa-solid fa-square-plus mr-1.5"></i> Input Barang Masuk
            </h4>
            
            <form action="{{ route('stock.in') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Product -->
                <div class="space-y-1">
                    <label for="in_product_id" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Pilih Barang</label>
                    <select name="product_id" id="in_product_id" required
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                        <option value="">Pilih Produk...</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ old('product_id') == $prod->id ? 'selected' : '' }}>{{ $prod->name }} (Stok: {{ $prod->stock }} pcs)</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Quantity -->
                    <div class="space-y-1">
                        <label for="in_quantity" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Jumlah (Pcs)</label>
                        <input type="number" min="1" name="quantity" id="in_quantity" value="{{ old('quantity') }}" required placeholder="Kuantitas"
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    </div>

                    <!-- Date -->
                    <div class="space-y-1">
                        <label for="in_date" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Tanggal</label>
                        <input type="date" name="date" id="in_date" value="{{ old('date', date('Y-m-d')) }}" required
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                    </div>
                </div>

                <!-- Supplier -->
                <div class="space-y-1">
                    <label for="in_supplier" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Pemasok / Supplier (Opsional)</label>
                    <input type="text" name="supplier" id="in_supplier" value="{{ old('supplier') }}" placeholder="Nama PT / Pemasok..."
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                </div>

                <!-- Notes -->
                <div class="space-y-1">
                    <label for="in_notes" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Catatan Tambahan</label>
                    <textarea name="notes" id="in_notes" rows="2" placeholder="Keterangan mutasi..."
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">{{ old('notes') }}</textarea>
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
            
            <form action="{{ route('stock.out') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Product -->
                <div class="space-y-1">
                    <label for="out_product_id" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Pilih Barang</label>
                    <select name="product_id" id="out_product_id" required
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                        <option value="">Pilih Produk...</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ old('product_id') == $prod->id ? 'selected' : '' }}>{{ $prod->name }} (Stok: {{ $prod->stock }} pcs)</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Quantity -->
                    <div class="space-y-1">
                        <label for="out_quantity" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Jumlah (Pcs)</label>
                        <input type="number" min="1" name="quantity" id="out_quantity" value="{{ old('quantity') }}" required placeholder="Kuantitas"
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                        @error('quantity')
                            <span class="text-[10px] text-rose-500 font-semibold">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div class="space-y-1">
                        <label for="out_date" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Tanggal</label>
                        <input type="date" name="date" id="out_date" value="{{ old('date', date('Y-m-d')) }}" required
                            class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                    </div>
                </div>

                <!-- Reason selection -->
                <div class="space-y-1">
                    <label for="out_reason" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Alasan Keluar</label>
                    <select name="reason" id="out_reason" required
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                        <option value="">Pilih Alasan...</option>
                        <option value="rusak" {{ old('reason') == 'rusak' ? 'selected' : '' }}>Barang Rusak</option>
                        <option value="hilang" {{ old('reason') == 'hilang' ? 'selected' : '' }}>Barang Hilang</option>
                        <option value="expired" {{ old('reason') == 'expired' ? 'selected' : '' }}>Kedaluwarsa (Expired)</option>
                        <option value="penyesuaian" {{ old('reason') == 'penyesuaian' ? 'selected' : '' }}>Penyesuaian Stok Gudang</option>
                    </select>
                </div>

                <!-- Notes -->
                <div class="space-y-1">
                    <label for="out_notes" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Catatan Tambahan</label>
                    <textarea name="notes" id="out_notes" rows="2" placeholder="Keterangan kerusakan/kehilangan..."
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">{{ old('notes') }}</textarea>
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
            
            <form action="{{ route('stock.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <!-- Product -->
                <div>
                    <select name="product_id" class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                        <option value="">Semua Barang</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ request()->get('product_id') == $prod->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Start date -->
                <div>
                    <input type="date" name="start_date" value="{{ request()->get('start_date') }}" placeholder="Dari Tanggal"
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                </div>

                <!-- End date -->
                <div>
                    <input type="date" name="end_date" value="{{ request()->get('end_date') }}" placeholder="Sampai Tanggal"
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                </div>

                <div class="flex space-x-2">
                    <button type="submit" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                        Cari
                    </button>
                    <a href="{{ route('stock.index') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold rounded-xl text-xs transition-all hover:bg-slate-200 dark:hover:bg-slate-700 text-center">
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
                            @forelse($stockIns as $in)
                            <tr>
                                <td class="py-3.5 text-center text-slate-400">{{ ($stockIns->currentPage() - 1) * $stockIns->perPage() + $loop->iteration }}</td>
                                <td class="py-3.5 font-medium whitespace-nowrap">{{ $in->date }}</td>
                                <td class="py-3.5 flex items-center space-x-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $in->product->name }}</span>
                                    <span class="text-[9px] text-slate-400 font-mono">{{ $in->product->sku }}</span>
                                </td>
                                <td class="py-3.5 text-center font-bold text-green-600 dark:text-green-400">+{{ $in->quantity }} pcs</td>
                                <td class="py-3.5 text-slate-500 dark:text-slate-400">{{ $in->supplier ?: '-' }}</td>
                                <td class="py-3.5 font-medium">{{ $in->user->name }}</td>
                                <td class="py-3.5 text-slate-400 truncate max-w-[150px]" title="{{ $in->notes }}">{{ $in->notes ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">Tidak ada log barang masuk tercatat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $stockIns->appends(request()->query())->links() }}
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
                            @forelse($stockOuts as $out)
                            <tr>
                                <td class="py-3.5 text-center text-slate-400">{{ ($stockOuts->currentPage() - 1) * $stockOuts->perPage() + $loop->iteration }}</td>
                                <td class="py-3.5 font-medium whitespace-nowrap">{{ $out->date }}</td>
                                <td class="py-3.5 flex items-center space-x-2">
                                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ $out->product->name }}</span>
                                    <span class="text-[9px] text-slate-400 font-mono">{{ $out->product->sku }}</span>
                                </td>
                                <td class="py-3.5 text-center font-bold text-rose-600 dark:text-rose-400">-{{ $out->quantity }} pcs</td>
                                <td class="py-3.5 font-semibold">
                                    @if($out->reason === 'rusak')
                                        <span class="text-rose-500 uppercase text-[9px] px-2 py-0.5 bg-rose-500/10 rounded">Rusak</span>
                                    @elseif($out->reason === 'hilang')
                                        <span class="text-amber-500 uppercase text-[9px] px-2 py-0.5 bg-amber-500/10 rounded">Hilang</span>
                                    @elseif($out->reason === 'expired')
                                        <span class="text-orange-500 uppercase text-[9px] px-2 py-0.5 bg-orange-500/10 rounded">Expired</span>
                                    @else
                                        <span class="text-indigo-500 uppercase text-[9px] px-2 py-0.5 bg-indigo-500/10 rounded">Penyesuaian</span>
                                    @endif
                                </td>
                                <td class="py-3.5 font-medium">{{ $out->user->name }}</td>
                                <td class="py-3.5 text-slate-400 truncate max-w-[150px]" title="{{ $out->notes }}">{{ $out->notes ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">Tidak ada log barang keluar tercatat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $stockOuts->appends(request()->query())->links() }}
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
