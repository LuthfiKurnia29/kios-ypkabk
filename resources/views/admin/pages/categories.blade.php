@extends('admin.shared.Layout')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="space-y-5">

    {{-- Alert Flash Message --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Manajemen Kategori</h2>
            <p class="text-sm text-gray-500 mt-0.5">Kelola kategori untuk katalog barang jualan dan form donasi barang.</p>
        </div>
        <button type="button" onclick="openModal('modal-tambah')"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors shadow-sm self-start sm:self-auto min-h-[44px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </button>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-4">
            <p class="text-xs font-semibold text-gray-500">Total Kategori</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalCount ?? $categories->total() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-4">
            <p class="text-xs font-semibold text-gray-500">Kategori Produk</p>
            <p class="text-2xl font-bold text-primary mt-1">{{ $productCategoryCount ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-4">
            <p class="text-xs font-semibold text-gray-500">Kategori Donasi</p>
            <p class="text-2xl font-bold text-secondary mt-1">{{ $donationCategoryCount ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-xs p-4">
            <p class="text-xs font-semibold text-gray-500">Kategori Aktif</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $activeCount ?? 0 }}</p>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <form method="GET" action="{{ route('admin.categories.index') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Cari Kategori</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, slug, atau keterangan..."
                class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
        </div>
        <div class="min-w-[140px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Tipe</label>
            <select name="type" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                <option value="">Semua Tipe</option>
                <option value="product" {{ request('type') === 'product' ? 'selected' : '' }}>Produk Jualan</option>
                <option value="donation" {{ request('type') === 'donation' ? 'selected' : '' }}>Donasi Barang</option>
            </select>
        </div>
        <div class="min-w-[130px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
            <select name="is_active" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-700 transition">Cari</button>
            @if(request()->hasAny(['search', 'type', 'is_active']))
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition">Reset</a>
            @endif
        </div>
    </form>

    {{-- Categories Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <p class="text-xs font-semibold text-gray-500">{{ $categories->total() }} kategori terdaftar</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-100">
                <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5 w-12">#</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Slug URL</th>
                        <th class="px-5 py-3.5">Tipe Kategori</th>
                        <th class="px-5 py-3.5">Produk Terkait</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4 text-gray-400 text-xs">
                                {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-pink-50 text-primary flex items-center justify-center text-base font-bold shrink-0 border border-pink-100">
                                        {{ $category->icon ?: '🏷️' }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 text-sm truncate">{{ $category->name }}</p>
                                        @if($category->description)
                                            <p class="text-[11px] text-gray-400 truncate max-w-xs">{{ $category->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <code class="px-2 py-1 rounded bg-gray-100 text-gray-600 text-[11px] font-mono">{{ $category->slug }}</code>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($category->type === 'donation')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-secondary border border-blue-100">
                                        Donasi Barang
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-pink-50 text-primary border border-pink-100">
                                        Produk Katalog
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-semibold text-gray-800">
                                    {{ $category->products_count ?? 0 }} barang
                                </span>
                                @if(($category->donations_count ?? 0) > 0)
                                    <span class="text-gray-400 text-[10px] block">({{ $category->donations_count }} donasi)</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($category->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-500 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        onclick="openEditCategoryModal({{ $category->id }}, {{ json_encode($category->name) }}, {{ json_encode($category->slug) }}, {{ json_encode($category->type ?? 'product') }}, {{ json_encode($category->icon ?? '') }}, {{ json_encode($category->description ?? '') }}, {{ $category->is_active ? 'true' : 'false' }})"
                                        class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold hover:bg-blue-100 transition">
                                        Edit
                                    </button>
                                    <button type="button"
                                        onclick="openDeleteCategoryModal({{ $category->id }}, {{ json_encode($category->name) }}, {{ $category->products_count ?? 0 }})"
                                        class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 text-xs font-semibold hover:bg-rose-100 transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <p class="text-sm font-medium">Belum ada kategori yang cocok dengan pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $categories->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ======= MODAL TAMBAH KATEGORI ======= --}}
<div id="modal-tambah" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-tambah')"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl z-10">
            <h3 class="text-base font-bold text-gray-900">Tambah Kategori Baru</h3>
            <button type="button" onclick="closeModal('modal-tambah')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="p-6 space-y-4">
            @csrf

            {{-- Nama Kategori --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required maxlength="255"
                    placeholder="Contoh: Kriya Kayu & Dekorasi"
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('name') border-rose-400 @enderror">
                @error('name')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Slug URL --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Slug URL <span class="text-gray-400 font-normal">(opsional, dibuat otomatis jika kosong)</span></label>
                <input type="text" name="slug" value="{{ old('slug') }}" maxlength="255"
                    placeholder="kriya-kayu-dekorasi"
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition font-mono @error('slug') border-rose-400 @enderror">
                @error('slug')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Tipe & Ikon --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe Kategori <span class="text-rose-500">*</span></label>
                    <select name="type" required
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                        <option value="product" {{ old('type', 'product') === 'product' ? 'selected' : '' }}>Produk Jualan</option>
                        <option value="donation" {{ old('type') === 'donation' ? 'selected' : '' }}>Donasi Barang</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Ikon / Emoji</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" maxlength="50"
                        placeholder="Contoh: 🎨 atau 🪴"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Singkat</label>
                <textarea name="description" rows="2" maxlength="1000"
                    placeholder="Penjelasan singkat mengenai kategori ini..."
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-200">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active_tambah" value="1"
                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/30 cursor-pointer">
                <label for="is_active_tambah" class="text-xs font-semibold text-gray-700 cursor-pointer">
                    Kategori Aktif (Dapat dipilih saat menambah/edit barang dan muncul di publik)
                </label>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition shadow-sm">Simpan Kategori</button>
                <button type="button" onclick="closeModal('modal-tambah')" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- ======= MODAL EDIT KATEGORI ======= --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-edit')"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl z-10">
            <h3 class="text-base font-bold text-gray-900">Edit Kategori</h3>
            <button type="button" onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit-category" method="POST" action="" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- Nama Kategori --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="name" id="edit-cat-name" required maxlength="255"
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
            </div>

            {{-- Slug URL --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Slug URL</label>
                <input type="text" name="slug" id="edit-cat-slug" maxlength="255"
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition font-mono">
            </div>

            {{-- Tipe & Ikon --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe Kategori <span class="text-rose-500">*</span></label>
                    <select name="type" id="edit-cat-type" required
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                        <option value="product">Produk Jualan</option>
                        <option value="donation">Donasi Barang</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Ikon / Emoji</label>
                    <input type="text" name="icon" id="edit-cat-icon" maxlength="50"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Singkat</label>
                <textarea name="description" id="edit-cat-desc" rows="2" maxlength="1000"
                    class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none"></textarea>
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-200">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="edit-cat-active" value="1"
                    class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/30 cursor-pointer">
                <label for="edit-cat-active" class="text-xs font-semibold text-gray-700 cursor-pointer">
                    Kategori Aktif
                </label>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition shadow-sm">Simpan Perubahan</button>
                <button type="button" onclick="closeModal('modal-edit')" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- ======= MODAL HAPUS KATEGORI ======= --}}
<div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-hapus')"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <div class="text-center space-y-3">
            <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center mx-auto">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900">Hapus Kategori?</h3>
            <p class="text-sm text-gray-500">
                Kategori "<span id="hapus-nama-kategori" class="font-semibold text-gray-800"></span>" akan dihapus.
            </p>
            <p id="hapus-warning-produk" class="text-xs text-amber-600 bg-amber-50 p-2.5 rounded-xl border border-amber-200 hidden">
                ⚠️ Ada <span id="hapus-jumlah-produk" class="font-bold"></span> produk terkait. Produk tidak akan terhapus, namun status kategorinya menjadi kosong (tanpa kategori).
            </p>
        </div>
        <form id="form-hapus-category" method="POST" action="" class="mt-5 flex gap-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex-1 py-2.5 rounded-xl bg-rose-600 text-white font-semibold text-sm hover:bg-rose-700 transition">Ya, Hapus</button>
            <button type="button" onclick="closeModal('modal-hapus')" class="flex-1 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition">Batal</button>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function openEditCategoryModal(id, name, slug, type, icon, description, isActive) {
        const form = document.getElementById('form-edit-category');
        form.action = '/admin/categories/' + id;

        document.getElementById('edit-cat-name').value = name || '';
        document.getElementById('edit-cat-slug').value = slug || '';
        document.getElementById('edit-cat-type').value = type || 'product';
        document.getElementById('edit-cat-icon').value = icon || '';
        document.getElementById('edit-cat-desc').value = description || '';
        document.getElementById('edit-cat-active').checked = !!isActive;

        openModal('modal-edit');
    }

    function openDeleteCategoryModal(id, name, productsCount) {
        document.getElementById('form-hapus-category').action = '/admin/categories/' + id;
        document.getElementById('hapus-nama-kategori').textContent = name;

        const warningEl = document.getElementById('hapus-warning-produk');
        const jumlahEl = document.getElementById('hapus-jumlah-produk');
        if (productsCount > 0) {
            jumlahEl.textContent = productsCount;
            warningEl.classList.remove('hidden');
        } else {
            warningEl.classList.add('hidden');
        }

        openModal('modal-hapus');
    }

    // Auto-open tambah modal jika ada error validasi saat store
    @if($errors->any() && old('_method') === null)
        openModal('modal-tambah');
    @elseif($errors->any() && old('_method') === 'PUT')
        openModal('modal-edit');
    @endif
</script>
@endsection

