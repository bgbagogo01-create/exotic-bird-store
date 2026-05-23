@extends('layouts.app')

@section('title', 'Kategori Barang')
@section('page_title', 'Kategori Barang')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column: Form Panel (Add / Edit) -->
    <div class="lg:col-span-1">
        @php
            $isEdit = request()->has('edit');
            $editId = request()->get('edit');
            $editCategory = $isEdit ? \App\Models\Category::find($editId) : null;
            
            // Fallback to creation if edit target not found
            if ($isEdit && !$editCategory) {
                $isEdit = false;
            }
        @endphp

        <div class="bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm sticky top-6">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font mb-6">
                @if($isEdit)
                    <i class="fa-solid fa-pen-to-square text-indigo-500 mr-1.5"></i> Edit Kategori
                @else
                    <i class="fa-solid fa-folder-plus text-indigo-500 mr-1.5"></i> Tambah Kategori
                @endif
            </h4>
            
            <form action="{{ $isEdit ? route('categories.update', $editCategory->id) : route('categories.store') }}" method="POST" class="space-y-5">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif
                
                <!-- Category Name -->
                <div class="space-y-2">
                    <label for="name" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Nama Kategori</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $isEdit ? $editCategory->name : '') }}" required placeholder="Contoh: Elektronik"
                        class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">
                    @error('name')
                        <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>
                
                <!-- Description -->
                <div class="space-y-2">
                    <label for="description" class="text-xs font-bold text-slate-400 uppercase tracking-wider heading-font">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" rows="4" placeholder="Keterangan kategori..."
                        class="w-full px-4 py-2.5 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all duration-200">{{ old('description', $isEdit ? $editCategory->description : '') }}</textarea>
                    @error('description')
                        <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Action buttons -->
                <div class="flex items-center space-x-2 pt-2">
                    <button type="submit" class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/10 transition-all">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                    </button>
                    @if($isEdit)
                        <a href="{{ route('categories.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-250 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-semibold rounded-xl text-xs transition-all">
                            Batal
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </div>

    <!-- Right Column: Listing Table Panel -->
    <div class="lg:col-span-2 bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-800/50 rounded-3xl p-6 shadow-sm">
        
        <!-- Table Search Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h4 class="font-bold text-sm uppercase tracking-wider text-slate-400 dark:text-slate-500 heading-font">Daftar Kategori</h4>
            
            <form action="{{ route('categories.index') }}" method="GET" class="w-full sm:w-64">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" name="search" value="{{ request()->get('search') }}" placeholder="Cari kategori..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-850 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:outline-none transition-all">
                </div>
            </form>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-slate-400 uppercase tracking-wider">
                        <th class="py-3 font-semibold w-12 text-center">#</th>
                        <th class="py-3 font-semibold">Nama Kategori</th>
                        <th class="py-3 font-semibold">Slug URL</th>
                        <th class="py-3 font-semibold">Deskripsi</th>
                        <th class="py-3 font-semibold text-center w-24">Jumlah Produk</th>
                        <th class="py-3 font-semibold text-right w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($categories as $category)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                        <td class="py-3.5 text-center text-slate-400 font-medium">
                            {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                        </td>
                        <td class="py-3.5 font-bold text-slate-800 dark:text-slate-100">{{ $category->name }}</td>
                        <td class="py-3.5 text-slate-400 font-mono text-[10px]">{{ $category->slug }}</td>
                        <td class="py-3.5 text-slate-500 dark:text-slate-400 max-w-xs truncate" title="{{ $category->description }}">
                            {{ $category->description ?: '-' }}
                        </td>
                        <td class="py-3.5 text-center font-bold text-indigo-600 dark:text-indigo-400">
                            {{ $category->products()->count() }}
                        </td>
                        <td class="py-3.5 text-right space-x-1.5 whitespace-nowrap">
                            <!-- Edit Button -->
                            <a href="{{ route('categories.index', ['edit' => $category->id]) }}" class="px-2.5 py-1.5 bg-blue-500/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400 hover:bg-blue-500 hover:text-white rounded-lg transition-colors inline-block" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>

                            <!-- Delete Button Form -->
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" id="delete-form-{{ $category->id }}" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('delete-form-{{ $category->id }}', '{{ $category->name }}')" 
                                    class="px-2.5 py-1.5 bg-rose-500/10 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500">Tidak ada data kategori ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-6">
            {{ $categories->appends(request()->query())->links() }}
        </div>

    </div>

</div>
@endsection
