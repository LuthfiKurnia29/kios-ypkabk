@extends('admin.shared.Layout')

@section('title', 'Manajemen Produk')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Manajemen Produk Katalog</h2>
            <p class="text-sm text-gray-500 mt-0.5">Tambah, ubah, dan hapus produk yang tampil di kios publik.</p>
        </div>
        <button type="button" onclick="openModal('modal-tambah')"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors shadow-sm self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </button>
    </div>

    {{-- Filter & Search Bar --}}
    <form method="GET" action="{{ route('admin.products.index') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Cari Produk</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, deskripsi, pengrajin..."
                class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
        </div>
        <div class="min-w-[140px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori</label>
            <select name="category_id" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="min-w-[130px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Status Stok</label>
            <select name="status" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                <option value="">Semua Status</option>
                <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="minimal_stock" {{ request('status') === 'minimal_stock' ? 'selected' : '' }}>Stok Minim</option>
                <option value="empty" {{ request('status') === 'empty' ? 'selected' : '' }}>Habis</option>
            </select>
        </div>
        <div class="min-w-[120px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Sumber</label>
            <select name="source" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                <option value="">Semua</option>
                <option value="karya" {{ request('source') === 'karya' ? 'selected' : '' }}>Karya ABK</option>
                <option value="preloved" {{ request('source') === 'preloved' ? 'selected' : '' }}>Preloved</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-700 transition">Cari</button>
            @if(request()->hasAny(['search','category_id','status','source']))
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition">Reset</a>
            @endif
        </div>
    </form>

    {{-- Product Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <p class="text-xs font-semibold text-gray-500">{{ $products->total() }} produk ditemukan</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-100">
                <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5 w-12">#</th>
                        <th class="px-5 py-3.5">Produk</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Harga</th>
                        <th class="px-5 py-3.5">Stok</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Sumber</th>
                        <th class="px-5 py-3.5">Unggulan</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium">
                    @forelse($products as $i => $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4 text-gray-400 text-xs">{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        @php
                                            $imgSrc = (str_starts_with($product->image, 'data:image') || str_starts_with($product->image, 'http'))
                                                ? $product->image
                                                : asset($product->image);
                                        @endphp
                                        <img src="{{ $imgSrc }}" alt="{{ $product->name }}"
                                            class="w-10 h-10 rounded-xl object-cover border border-gray-100 shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 truncate max-w-[200px]">{{ $product->name }}</p>
                                        @if($product->artisan_name)
                                            <p class="text-[11px] text-gray-400 truncate">oleh {{ $product->artisan_name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($product->category)
                                    <span class="px-2 py-0.5 rounded-lg bg-gray-100 text-gray-700 text-[11px] font-semibold">{{ $product->category->name }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-bold text-gray-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="font-bold {{ $product->stock <= 0 ? 'text-rose-600' : ($product->stock <= 3 ? 'text-amber-600' : 'text-gray-900') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold
                                    {{ $product->status === 'ready' ? 'bg-emerald-100 text-emerald-800' :
                                       ($product->status === 'minimal_stock' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $product->status === 'ready' ? 'READY' : ($product->status === 'minimal_stock' ? 'MINIM' : 'HABIS') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($product->source === 'karya')
                                    <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-blue-700 text-[11px] font-semibold">Karya ABK</span>
                                @elseif($product->source === 'preloved')
                                    <span class="px-2 py-0.5 rounded-lg bg-purple-50 text-purple-700 text-[11px] font-semibold">Preloved</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                @if($product->is_featured)
                                    <span class="text-amber-400" title="Produk Unggulan">★</span>
                                @else
                                    <span class="text-gray-200">★</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        onclick="openEditModal({{ $product->id }}, {{ json_encode($product->name) }}, {{ $product->price }}, {{ $product->stock }}, {{ json_encode($product->description ?? '') }}, {{ json_encode($product->image ?? '') }}, {{ json_encode($product->source ?? '') }}, {{ json_encode($product->condition ?? '') }}, {{ json_encode($product->artisan_name ?? '') }}, {{ $product->category_id ?? 'null' }}, {{ $product->is_featured ? 'true' : 'false' }})"
                                        class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-semibold hover:bg-blue-100 transition">
                                        Edit
                                    </button>
                                    <button type="button"
                                        onclick="openDeleteModal({{ $product->id }}, {{ json_encode($product->name) }})"
                                        class="px-3 py-1.5 rounded-lg bg-rose-50 text-rose-700 text-xs font-semibold hover:bg-rose-100 transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    <p class="text-sm font-medium">Belum ada produk ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ======= MODAL TAMBAH PRODUK ======= --}}
<div id="modal-tambah" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-tambah')"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl z-10">
            <h3 class="text-base font-bold text-gray-900">Tambah Produk Baru</h3>
            <button type="button" onclick="closeModal('modal-tambah')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @include('admin.partials._product-form', ['categories' => $categories])
            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition">Simpan Produk</button>
                <button type="button" onclick="closeModal('modal-tambah')" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- ======= MODAL EDIT PRODUK ======= --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-edit')"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-2xl z-10">
            <h3 class="text-base font-bold text-gray-900">Edit Produk</h3>
            <button type="button" onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit" method="POST" action="" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            @include('admin.partials._product-form', ['categories' => $categories, 'isEdit' => true])
            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition">Simpan Perubahan</button>
                <button type="button" onclick="closeModal('modal-edit')" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- ======= MODAL HAPUS PRODUK ======= --}}
<div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-hapus')"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <div class="text-center space-y-3">
            <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center mx-auto">
                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900">Hapus Produk?</h3>
            <p class="text-sm text-gray-500">Produk "<span id="hapus-nama" class="font-semibold text-gray-800"></span>" akan dihapus permanen dan tidak bisa dikembalikan.</p>
        </div>
        <form id="form-hapus" method="POST" action="" class="mt-5 flex gap-3">
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

        if (id === 'modal-tambah') {
            const imgPreview = document.getElementById('img-preview-tambah');
            const iconPlaceholder = document.getElementById('icon-placeholder-tambah');
            const fileInput = document.getElementById('file-input-tambah');
            if (fileInput) fileInput.value = '';
            if (imgPreview) {
                imgPreview.src = '';
                imgPreview.classList.add('hidden');
            }
            if (iconPlaceholder) iconPlaceholder.classList.remove('hidden');
        }
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function handleImagePreview(input, imgId, iconId) {
        const img = document.getElementById(imgId);
        const icon = document.getElementById(iconId);
        if (!img || !icon) return;

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                img.classList.remove('hidden');
                icon.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openEditModal(id, name, price, stock, description, image, source, condition, artisanName, categoryId, isFeatured) {
        const form = document.getElementById('form-edit');
        form.action = '/admin/products/' + id;

        const setValue = (selector, val) => {
            const el = form.querySelector(selector);
            if (el) el.value = val ?? '';
        };
        const setCheck = (selector, val) => {
            const el = form.querySelector(selector);
            if (el) el.checked = !!val;
        };

        setValue('[name="name"]', name);
        setValue('[name="price"]', price);
        setValue('[name="stock"]', stock);
        setValue('[name="description"]', description);
        setValue('[name="source"]', source);
        setValue('[name="condition"]', condition);
        setValue('[name="artisan_name"]', artisanName);
        setValue('[name="category_id"]', categoryId);
        setCheck('[name="is_featured"]', isFeatured);

        // Atur preview gambar saat ini
        const imgPreview = document.getElementById('img-preview-edit');
        const iconPlaceholder = document.getElementById('icon-placeholder-edit');
        const fileInput = document.getElementById('file-input-edit');
        if (fileInput) fileInput.value = '';

        if (image && typeof image === 'string' && image.trim() !== '') {
            const src = (image.startsWith('data:image') || image.startsWith('http')) 
                ? image 
                : ('/' + image.replace(/^\//, ''));
            imgPreview.src = src;
            imgPreview.classList.remove('hidden');
            iconPlaceholder.classList.add('hidden');
        } else {
            imgPreview.src = '';
            imgPreview.classList.add('hidden');
            iconPlaceholder.classList.remove('hidden');
        }

        openModal('modal-edit');
    }

    function openDeleteModal(id, name) {
        document.getElementById('form-hapus').action = '/admin/products/' + id;
        document.getElementById('hapus-nama').textContent = name;
        openModal('modal-hapus');
    }

    // Auto-open tambah modal jika ada error validasi dari store
    @if($errors->any() && old('_method') === null)
        openModal('modal-tambah');
    @elseif($errors->any() && old('_method') === 'PUT')
        openModal('modal-edit');
    @endif
</script>
@endsection

