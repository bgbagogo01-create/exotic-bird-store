@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page_title', 'Manajemen Akun Pengguna')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Tambah User Baru Form -->
    <div class="lg:col-span-1">
        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm sticky top-6">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font mb-6">
                <i class="fa-solid fa-user-plus text-indigo-500 mr-1.5"></i> Buat Akun Baru
            </h4>
            
            <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Name -->
                <div class="space-y-1">
                    <label for="name" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Nama Pengguna</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Nama lengkap..."
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    @error('name')
                        <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Email -->
                <div class="space-y-1">
                    <label for="email" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Alamat Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    @error('email')
                        <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <label for="password" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Kata Sandi</label>
                    <input type="password" name="password" id="password" required placeholder="Minimal 6 karakter"
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                    @error('password')
                        <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="space-y-1">
                    <label for="role_id" class="text-[10px] font-bold text-slate-400 uppercase tracking-widest heading-font">Pilih Peran / Level</label>
                    <select name="role_id" id="role_id" required
                        class="w-full px-3 py-2 rounded-xl bg-white/50 dark:bg-slate-950/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all cursor-pointer">
                        <option value="">Pilih Level Peran...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="w-full py-2.5 mt-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/10 transition-all">
                    Simpan Akun User
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: User Accounts Listing Table -->
    <div class="lg:col-span-2 bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
        <h4 class="font-bold text-sm uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font mb-6">Daftar Akun Pengguna</h4>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 uppercase tracking-wider">
                        <th class="py-3 font-semibold w-12 text-center">#</th>
                        <th class="py-3 font-semibold">Nama Lengkap</th>
                        <th class="py-3 font-semibold">Alamat Email</th>
                        <th class="py-3 font-semibold text-center w-24">Level Akses</th>
                        <th class="py-3 font-semibold text-center">Tgl Daftar</th>
                        <th class="py-3 font-semibold text-right w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($users as $usr)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                        <td class="py-3.5 text-center text-slate-400 font-medium">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                        </td>
                        <td class="py-3.5 flex items-center space-x-2.5">
                            <img src="{{ $usr->avatar ? asset('storage/' . $usr->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($usr->name) . '&background=6366f1&color=fff' }}" alt="Avatar" class="w-7 h-7 rounded-lg object-cover">
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ $usr->name }}</span>
                        </td>
                        <td class="py-3.5 font-mono text-[10px] text-slate-500 dark:text-slate-400">{{ $usr->email }}</td>
                        <td class="py-3.5 text-center">
                            @if($usr->role->name === 'admin')
                                <span class="px-2 py-0.5 bg-indigo-500/10 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400 rounded-full font-bold uppercase text-[8px] tracking-wider">Admin</span>
                            @elseif($usr->role->name === 'kasir')
                                <span class="px-2 py-0.5 bg-blue-500/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 rounded-full font-bold uppercase text-[8px] tracking-wider">Kasir</span>
                            @else
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 rounded-full font-bold uppercase text-[8px] tracking-wider">Pembeli</span>
                            @endif
                        </td>
                        <td class="py-3.5 text-center text-slate-400 font-medium">{{ $usr->created_at->format('d/m/Y') }}</td>
                        <td class="py-3.5 text-right whitespace-nowrap">
                            <!-- Delete Button Form -->
                            <form action="{{ route('users.destroy', $usr->id) }}" method="POST" id="delete-form-{{ $usr->id }}" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-form-{{ $usr->id }}', '{{ $usr->name }}')" 
                                    class="px-2.5 py-1.5 bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition-colors" title="Hapus Akun">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500">Tidak ada data pengguna lain ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->appends(request()->query())->links() }}
        </div>

    </div>

</div>
@endsection
