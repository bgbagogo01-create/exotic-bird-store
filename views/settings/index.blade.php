@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page_title', 'Pengaturan Website & Toko')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Toko Information Config Form -->
    <div class="lg:col-span-2 bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 md:p-8 shadow-sm">
        <h4 class="text-sm font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font mb-6">
            <i class="fa-solid fa-sliders text-indigo-500 mr-1.5"></i> Identitas & Konfigurasi Toko
        </h4>
        
        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Shop Name -->
                <div class="space-y-2">
                    <label for="shop_name" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Nama Toko / Perusahaan</label>
                    <input type="text" name="shop_name" id="shop_name" value="{{ old('shop_name', $settings['shop_name']) }}" required placeholder="Contoh: EXOTIC BIRD STORE"
                        class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    @error('shop_name')
                        <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Shop Phone / WA number -->
                <div class="space-y-2">
                    <label for="shop_phone" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">No WhatsApp Admin (Pesan Order)</label>
                    <input type="text" name="shop_phone" id="shop_phone" value="{{ old('shop_phone', $settings['shop_phone']) }}" required placeholder="Contoh: 08123456789"
                        class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    <span class="text-[9px] text-slate-400 block mt-1"><i class="fa-solid fa-circle-info"></i> Gunakan format lokal atau internasional. Otomatis diformat untuk chat WhatsApp.</span>
                    @error('shop_phone')
                        <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Shop Email -->
                <div class="space-y-2">
                    <label for="shop_email" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Email Kontak Toko (Opsional)</label>
                    <input type="email" name="shop_email" id="shop_email" value="{{ old('shop_email', $settings['shop_email']) }}" placeholder="toko@email.com"
                        class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    @error('shop_email')
                        <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Shop Currency symbol -->
                <div class="space-y-2">
                    <label for="shop_currency" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Mata Uang Toko</label>
                    <input type="text" name="shop_currency" id="shop_currency" value="{{ old('shop_currency', $settings['shop_currency']) }}" required placeholder="Contoh: Rp"
                        class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    @error('shop_currency')
                        <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Shop Address -->
            <div class="space-y-2">
                <label for="shop_address" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Alamat Lengkap Toko</label>
                <textarea name="shop_address" id="shop_address" rows="3" placeholder="Alamat jalan, nomor, kecamatan, kota..."
                    class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">{{ old('shop_address', $settings['shop_address']) }}</textarea>
                @error('shop_address')
                    <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex justify-end pt-2 border-t border-slate-100 dark:border-slate-800/60">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/10 transition-all">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    <!-- Right Column: Database backup & Database Reset Modules -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Widget: Database Backup Download -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font mb-4">
                <i class="fa-solid fa-database text-indigo-500 mr-1"></i> Cadangan Database
            </h4>
            
            <p class="text-[11px] text-slate-400 mb-5 leading-normal">Unduh salinan cadangan seluruh tabel database MySQL dalam format file SQL SQL. Anda dapat memulihkannya sewaktu-waktu.</p>
            
            <a href="{{ route('settings.backup') }}" 
                class="w-full py-2.5 bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 hover:bg-indigo-500 hover:text-white font-bold rounded-xl text-xs transition-all shadow-sm flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-cloud-arrow-down"></i>
                <span>Unduh File SQL</span>
            </a>
        </div>

        <!-- Widget: System reset utilities -->
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
            <h4 class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 heading-font mb-4">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> Area Reset Sistem
            </h4>
            
            <p class="text-[11px] text-slate-400 mb-4 leading-normal">Bersihkan dan kosongkan tabel transaksi penjualan, catatan barang masuk/keluar gudang, atau kosongkan semua data untuk mulai dari awal.</p>
            
            <div class="bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 p-3.5 rounded-2xl text-[10px] leading-relaxed mb-5">
                <i class="fa-solid fa-triangle-exclamation mr-1 animate-pulse"></i>
                <strong>PERINGATAN!</strong> Seluruh tindakan di area ini bersifat permanen dan tidak dapat dibatalkan.
            </div>

            <form action="{{ route('settings.reset') }}" method="POST" id="resetSystemForm" class="space-y-4">
                @csrf
                
                <div class="space-y-1">
                    <label for="reset_type" class="text-[9px] font-bold text-slate-400 uppercase tracking-widest heading-font">Pilih Kategori Reset</label>
                    <select name="reset_type" id="reset_type" required
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 focus:outline-none transition-all cursor-pointer">
                        <option value="">Pilih Opsi...</option>
                        <option value="transactions">Reset Data Transaksi Penjualan</option>
                        <option value="stock">Reset Riwayat Stok & Kosongkan Produk</option>
                        <option value="all">Reset Seluruh Data Sistem (Pembersihan Total)</option>
                    </select>
                </div>

                <button type="button" onclick="confirmSystemReset()" 
                    class="w-full py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-rose-500/10 transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Eksekusi Reset Data</span>
                </button>
            </form>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    // System Reset Safety Confirmation Popover
    function confirmSystemReset() {
        const select = document.getElementById('reset_type');
        const resetVal = select.value;
        
        if (!resetVal) {
            Swal.fire({
                icon: 'warning',
                title: 'Opsi Belum Dipilih!',
                text: 'Silakan pilih kategori data yang ingin Anda reset terlebih dahulu.',
                confirmButtonColor: '#4f46e5'
            });
            return;
        }

        let descText = "";
        if (resetVal === 'transactions') descText = "Tindakan ini akan MENGAPUS seluruh catatan transaksi penjualan dan detail struk belanja selamanya.";
        if (resetVal === 'stock') descText = "Tindakan ini akan MENGAPUS log barang masuk/keluar, serta MENGOSONGKAN (set 0) seluruh stok produk saat ini.";
        if (resetVal === 'all') descText = "Tindakan ini akan melakukan PEMBERSIHAN TOTAL. Seluruh transaksi, data stok gudang akan dihapus, dan stok produk akan diset 0.";

        Swal.fire({
            title: 'SANGAT PENTING!',
            text: descText + " Apakah Anda sangat yakin? Ketik konfirmasi untuk memproses.",
            icon: 'warning',
            input: 'text',
            inputPlaceholder: "Ketik 'KONFIRMASI RESET' di sini...",
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#475569',
            confirmButtonText: 'Ya, eksekusi sekarang!',
            cancelButtonText: 'Batal',
            inputValidator: (value) => {
                if (value !== 'KONFIRMASI RESET') {
                    return 'Kalimat konfirmasi yang Anda ketik salah!'
                }
            },
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading spinner during reset
                Swal.fire({
                    title: 'Memproses Reset Data...',
                    html: 'Sistem sedang membersihkan tabel database...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                document.getElementById('resetSystemForm').submit();
            }
        })
    }
</script>
@endsection
