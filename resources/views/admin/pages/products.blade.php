@extends('admin.shared.Layout')

@section('title', 'Produk')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Produk Katalog</h2>
            <p class="text-sm text-gray-500 mt-0.5">Tambah, ubah, dan hapus produk yang tampil di kios publik.</p>
        </div>
        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <a href="{{ route('admin.categories.index') }}"
                class="inline-flex items-center px-3.5 py-2 rounded-md border border-gray-200 bg-white text-gray-700 text-xs font-medium hover:bg-gray-50 transition">
                Kelola Kategori
            </a>
            <button type="button" onclick="openModal('modal-tambah')"
                class="inline-flex items-center px-4 py-2 rounded-md bg-primary text-white text-xs font-medium hover:bg-primary/90 transition-colors">
                Tambah Produk
            </button>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <form method="GET" action="{{ route('admin.products.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Cari Produk</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, deskripsi, pengrajin..."
                class="w-full px-3 py-1.5 rounded-md border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-primary">
        </div>
        <div class="min-w-[140px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori</label>
            <select name="category_id" class="w-full px-3 py-1.5 rounded-md border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-primary bg-white">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="min-w-[130px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Status Stok</label>
            <select name="status" class="w-full px-3 py-1.5 rounded-md border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-primary bg-white">
                <option value="">Semua Status</option>
                <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="minimal_stock" {{ request('status') === 'minimal_stock' ? 'selected' : '' }}>Stok Minim</option>
                <option value="empty" {{ request('status') === 'empty' ? 'selected' : '' }}>Habis</option>
            </select>
        </div>
        <div class="min-w-[120px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Sumber</label>
            <select name="source" class="w-full px-3 py-1.5 rounded-md border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-1 focus:ring-primary bg-white">
                <option value="">Semua</option>
                <option value="karya" {{ request('source') === 'karya' ? 'selected' : '' }}>Karya ABK</option>
                <option value="preloved" {{ request('source') === 'preloved' ? 'selected' : '' }}>Preloved</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-1.5 rounded-md bg-gray-900 text-white text-xs font-medium hover:bg-gray-700 transition">Cari</button>
            @if(request()->hasAny(['search','category_id','status','source']))
                <a href="{{ route('admin.products.index') }}" class="px-4 py-1.5 rounded-md bg-gray-100 text-gray-600 text-xs font-medium hover:bg-gray-200 transition">Reset</a>
            @endif
        </div>
    </form>

    {{-- Product Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
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
                                            class="w-10 h-10 rounded-md object-cover border border-gray-100 shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-md bg-gray-100 flex items-center justify-center shrink-0 text-gray-400 text-[10px]">
                                            Foto
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 truncate max-w-[200px]">{{ $product->name }}</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            @if($product->artisan_name)
                                                <p class="text-[11px] text-gray-400 truncate">oleh {{ $product->artisan_name }}</p>
                                            @endif
                                            @if($product->images->count() > 1)
                                                <span class="inline-flex items-center text-[10px] bg-pink-50 text-primary px-1.5 py-0.5 rounded font-medium">
                                                    {{ $product->images->count() }} foto
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($product->category)
                                    <span class="text-gray-700 text-xs">{{ $product->category->name }}</span>
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
                                <span class="text-xs font-medium {{ $product->status === 'ready' ? 'text-emerald-700' : ($product->status === 'minimal_stock' ? 'text-amber-700' : 'text-rose-700') }}">
                                    {{ $product->status === 'ready' ? 'Ready' : ($product->status === 'minimal_stock' ? 'Minim' : 'Habis') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="text-xs text-gray-600">
                                    {{ $product->source === 'karya' ? 'Karya ABK' : ($product->source === 'preloved' ? 'Preloved' : '—') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center text-xs">
                                {{ $product->is_featured ? 'Ya' : '—' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.show', $product) }}"
                                        class="px-2.5 py-1 rounded-md border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-medium transition">
                                        Detail
                                    </a>
                                    <button type="button"
                                        onclick="openEditModal({{ $product->id }}, {{ json_encode($product->name) }}, {{ $product->price }}, {{ $product->stock }}, {{ json_encode($product->description ?? '') }}, {{ json_encode($product->image ?? '') }}, {{ json_encode($product->source ?? '') }}, {{ json_encode($product->condition ?? '') }}, {{ json_encode($product->artisan_name ?? '') }}, {{ $product->category_id ?? 'null' }}, {{ $product->is_featured ? 'true' : 'false' }}, {{ json_encode($product->images->map(fn($img) => ['id' => $img->id, 'image' => $img->image])) }})"
                                        class="px-2.5 py-1 rounded-md border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-medium transition">
                                        Edit
                                    </button>
                                    <button type="button"
                                        onclick="openDeleteModal({{ $product->id }}, {{ json_encode($product->name) }})"
                                        class="px-2.5 py-1 rounded-md border border-gray-200 bg-white hover:bg-gray-50 text-rose-700 text-xs font-medium transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-10 text-center text-sm text-gray-500">
                                Belum ada produk ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/50">
                {{ $products->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

{{-- ======= MODAL TAMBAH PRODUK ======= --}}
<div id="modal-tambah" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-tambah')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-lg z-10">
            <h3 class="text-base font-bold text-gray-900">Tambah Produk Baru</h3>
            <button type="button" onclick="closeModal('modal-tambah')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @include('admin.partials._product-form', ['categories' => $categories])
            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex-1 py-2 rounded-md bg-primary text-white font-medium text-sm hover:bg-primary/90 transition">Simpan Produk</button>
                <button type="button" onclick="closeModal('modal-tambah')" class="px-5 py-2 rounded-md bg-gray-100 text-gray-700 font-medium text-sm hover:bg-gray-200 transition">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- ======= MODAL EDIT PRODUK ======= --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-edit')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between rounded-t-lg z-10">
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
                <button type="submit" class="flex-1 py-2 rounded-md bg-primary text-white font-medium text-sm hover:bg-primary/90 transition">Simpan Perubahan</button>
                <button type="button" onclick="closeModal('modal-edit')" class="px-5 py-2 rounded-md bg-gray-100 text-gray-700 font-medium text-sm hover:bg-gray-200 transition">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- ======= MODAL HAPUS PRODUK ======= --}}
<div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-hapus')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-sm p-6">
        <div class="text-center space-y-2">
            <h3 class="text-base font-bold text-gray-900">Hapus Produk?</h3>
            <p class="text-sm text-gray-500">Produk "<span id="hapus-nama" class="font-semibold text-gray-800"></span>" akan dihapus permanen dan tidak bisa dikembalikan.</p>
        </div>
        <form id="form-hapus" method="POST" action="" class="mt-4 flex gap-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex-1 py-2 rounded-md bg-rose-600 text-white font-medium text-sm hover:bg-rose-700 transition">Ya, Hapus</button>
            <button type="button" onclick="closeModal('modal-hapus')" class="flex-1 py-2 rounded-md bg-gray-100 text-gray-700 font-medium text-sm hover:bg-gray-200 transition">Batal</button>
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
            const fileInput = document.getElementById('file-input-tambah');
            if (fileInput) fileInput.value = '';
            const multiPreview = document.getElementById('multi-preview-tambah');
            if (multiPreview) {
                multiPreview.innerHTML = '';
                multiPreview.classList.add('hidden');
            }
        }
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function handleMultipleImagePreview(input, containerId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        container.innerHTML = '';
        if (!input.files || input.files.length === 0) {
            container.classList.add('hidden');
            return;
        }
        container.classList.remove('hidden');
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'w-14 h-14 rounded-xl border border-gray-200 overflow-hidden relative shadow-xs shrink-0 bg-white';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <span class="absolute bottom-0 inset-x-0 bg-black/60 text-[9px] text-white text-center py-0.5 font-bold">#${index + 1}</span>
                `;
                container.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function openEditModal(id, name, price, stock, description, image, source, condition, artisanName, categoryId, isFeatured, imagesList = []) {
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

        // Reset input file dan preview baru
        const fileInput = document.getElementById('file-input-edit');
        if (fileInput) fileInput.value = '';
        const multiPreview = document.getElementById('multi-preview-edit');
        if (multiPreview) {
            multiPreview.innerHTML = '';
            multiPreview.classList.add('hidden');
        }

        // Tampilkan foto-foto yang sudah tersimpan
        const existingWrapper = document.getElementById('existing-images-wrapper-edit');
        const existingContainer = document.getElementById('existing-images-edit');
        if (existingContainer && existingWrapper) {
            existingContainer.innerHTML = '';
            const allImages = (imagesList && imagesList.length > 0)
                ? imagesList
                : (image ? [{ id: null, image: image }] : []);

            if (allImages.length > 0) {
                existingWrapper.classList.remove('hidden');
                allImages.forEach((item, idx) => {
                    const src = (item.image.startsWith('data:image') || item.image.startsWith('http')) 
                        ? item.image 
                        : ('/' + item.image.replace(/^\//, ''));
                    const targetImageId = item.id ? item.id : 'cover';

                    const card = document.createElement('div');
                    card.className = 'relative w-20 h-20 rounded-xl border border-gray-200 overflow-hidden bg-white shrink-0 shadow-xs flex flex-col justify-end group';
                    card.innerHTML = `
                        <img src="${src}" class="absolute inset-0 w-full h-full object-cover">
                        <div class="relative z-10 bg-gradient-to-t from-black/85 via-black/50 to-transparent p-1 flex items-center justify-between">
                            <span class="text-[9px] text-white font-bold px-0.5">#${idx + 1}</span>
                            <button type="button" onclick="deleteExistingImage(${id}, '${targetImageId}', this)"
                                class="px-1.5 py-0.5 rounded bg-rose-600 hover:bg-rose-700 text-white text-[9px] font-bold shadow-sm transition flex items-center gap-0.5 cursor-pointer"
                                title="Hapus foto ini">
                                <span>Hapus</span>
                            </button>
                        </div>
                    `;
                    existingContainer.appendChild(card);
                });
            } else {
                existingWrapper.classList.add('hidden');
            }
        }

        openModal('modal-edit');
    }

    async function deleteExistingImage(productId, imageId, btn) {
        if (!confirm('Hapus foto ini dari produk?')) return;
        const card = btn.closest('.relative');
        const url = (imageId === 'cover')
            ? `/admin/products/${productId}/cover-image`
            : `/admin/products/${productId}/images/${imageId}`;
        try {
            btn.disabled = true;
            const res = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            });
            if (res.ok) {
                card.remove();
                const container = document.getElementById('existing-images-edit');
                if (container && container.children.length === 0) {
                    document.getElementById('existing-images-wrapper-edit')?.classList.add('hidden');
                }
            } else {
                alert('Gagal menghapus foto.');
                btn.disabled = false;
            }
        } catch (e) {
            alert('Terjadi kesalahan jaringan.');
            btn.disabled = false;
        }
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

