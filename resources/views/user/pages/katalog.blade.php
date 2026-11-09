@extends('user.shared.Layout')

@section('content')
@php
    $productList = (isset($products) && count($products) > 0) ? $products : [
        [
            'id' => 1,
            'name' => 'Tote Bag Lukis Pelangi',
            'price' => 45000,
            'source' => 'karya',
            'condition' => 'mulus',
            'category_id' => 1,
            'stock' => 5,
            'status' => 'ready',
            'artisan_name' => 'Karya Dimas (16 th), Sentra Sidoarjo',
            'description' => 'Lukisan tangan penuh warna di atas kain kanvas tebal ramah lingkungan.',
            'image' => 'images/pict-2.webp',
        ],
        [
            'id' => 2,
            'name' => 'Boneka Rajut Kelinci',
            'price' => 65000,
            'source' => 'karya',
            'condition' => 'baru',
            'category_id' => 1,
            'stock' => 3,
            'status' => 'ready',
            'artisan_name' => 'Karya Nabila (14 th), Sentra Sidoarjo',
            'description' => 'Rajutan benang wol lembut amigurumi buatan siswa vokasi.',
            'image' => 'images/pict-1.webp',
        ],
        [
            'id' => 3,
            'name' => 'Set Ensiklopedia Anak',
            'price' => 95000,
            'source' => 'preloved',
            'condition' => 'mulus',
            'category_id' => 3,
            'stock' => 2,
            'status' => 'ready',
            'artisan_name' => 'Donasi Keluarga Ibu Rini, Mojokerto',
            'description' => 'Tiga buku dongeng bergambar anak, kondisi rapi dan halaman lengkap.',
            'image' => 'images/pict-4.webp',
        ],
        [
            'id' => 4,
            'name' => 'Jaket Denim Anak',
            'price' => 50000,
            'source' => 'preloved',
            'condition' => 'mulus',
            'category_id' => 2,
            'stock' => 1,
            'status' => 'ready',
            'artisan_name' => 'Kondisi Sangat Baik (Size 6-8 th)',
            'description' => 'Pakaian anak denim bersih, kancing lengkap, siap pakai.',
            'image' => 'images/pict-3.webp',
        ],
        [
            'id' => 5,
            'name' => 'Gelang Manik Pelangi',
            'price' => 25000,
            'source' => 'karya',
            'condition' => 'baru',
            'category_id' => 1,
            'stock' => 8,
            'status' => 'ready',
            'artisan_name' => 'Karya Sarah (15 th), Sentra Mojokerto',
            'description' => 'Dirangkai oleh anak istimewa dalam latihan motorik halus.',
            'image' => 'images/pict-2.webp',
        ],
        [
            'id' => 6,
            'name' => 'Kemeja Linen Santai',
            'price' => 70000,
            'source' => 'preloved',
            'condition' => 'baru',
            'category_id' => 2,
            'stock' => 2,
            'status' => 'ready',
            'artisan_name' => 'Kondisi Istimewa (Size L Dewasa)',
            'description' => 'Kemeja pria linen katun sejuk, donasi pendukung di Surabaya.',
            'image' => 'images/pict-3.webp',
        ],
        [
            'id' => 7,
            'name' => 'Hiasan Dinding Tenun Mini',
            'price' => 80000,
            'source' => 'karya',
            'condition' => 'baru',
            'category_id' => 4,
            'stock' => 0,
            'status' => 'empty',
            'artisan_name' => 'Karya Bayu (15 th), Sentra Sidoarjo',
            'description' => 'Tenun benang warna pastel berbingkai kayu mini.',
            'image' => null,
        ],
    ];

    $totalCount = $totalProductCount ?? count($productList);
@endphp

{{-- 1. Header Halaman (Komponen <x-page-header>) --}}
<x-page-header
    title="Katalog"
    subtitle="Semua hasil penjualan mendanai terapi dan pelatihan anak istimewa."
    :pill="$totalCount . ' produk tersedia'"
    breadcrumb="Beranda / Katalog"
/>

{{-- 2. Konten Utama: Sidebar Filter + Grid Produk --}}
<section class="w-full bg-[#FFFBF7] py-10 lg:py-16">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- Mobile Filter Trigger Button --}}
            <div class="w-full flex items-center justify-between lg:hidden pb-2">
                <button type="button"
                        id="open-filter-drawer-btn"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-[8px] bg-white border border-[#2B2430]/15 text-sm font-semibold text-[#2B2430] shadow-xs active:scale-95 transition-all">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filter Produk</span>
                </button>
                <span class="text-xs text-[#2B2430]/70 font-medium">
                    {{ $totalCount }} produk
                </span>
            </div>

            {{-- ----------------------------------------------------
                 SIDEBAR FILTER DESKTOP (280px)
                 ---------------------------------------------------- --}}
            <aside class="hidden lg:block w-[280px] shrink-0 bg-white rounded-[12px] p-6 border border-[#2B2430]/10 shadow-xs space-y-6 sticky top-24">
                {{-- Header Filter --}}
                <div class="flex items-center justify-between pb-4 border-b border-[#2B2430]/10">
                    <h2 class="font-bold text-base text-[#2B2430]">Filter Produk</h2>
                    <button type="button" id="sidebar-reset-btn" class="text-xs font-semibold text-primary hover:underline">
                        Reset
                    </button>
                </div>

                {{-- Filter Group 1: Kategori dari $categories (Tanpa N+1, Tanpa Jumlah jika Tidak Tersedia) --}}
                @if(isset($categories) && count($categories) > 0)
                    <div class="space-y-3">
                        <h3 class="text-xs uppercase tracking-wider font-bold text-[#2B2430]/60">Kategori</h3>
                        <div class="space-y-2 text-sm text-[#2B2430]">
                            @foreach($categories as $category)
                                <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                    <input type="checkbox"
                                           value="{{ $category->id }}"
                                           class="filter-checkbox filter-category w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                                    <span class="text-xs sm:text-sm font-medium">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="space-y-3">
                        <h3 class="text-xs uppercase tracking-wider font-bold text-[#2B2430]/60">Kategori</h3>
                        <div class="space-y-2 text-sm text-[#2B2430]">
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" value="1" class="filter-checkbox filter-category w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                                <span class="text-xs sm:text-sm font-medium">Rajut &amp; Kriya ABK</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" value="2" class="filter-checkbox filter-category w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                                <span class="text-xs sm:text-sm font-medium">Pakaian Preloved</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" value="3" class="filter-checkbox filter-category w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                                <span class="text-xs sm:text-sm font-medium">Buku &amp; Mainan Edukatif</span>
                            </label>
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input type="checkbox" value="4" class="filter-checkbox filter-category w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                                <span class="text-xs sm:text-sm font-medium">Dekorasi &amp; Kerajinan</span>
                            </label>
                        </div>
                    </div>
                @endif

                {{-- Filter Group 2: Jenis Barang (source: Karya ABK / Preloved) --}}
                <div class="space-y-3 pt-4 border-t border-[#2B2430]/10">
                    <h3 class="text-xs uppercase tracking-wider font-bold text-[#2B2430]/60">Jenis Barang</h3>
                    <div class="space-y-2 text-sm text-[#2B2430]">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" value="karya" class="filter-checkbox filter-source w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                            <span class="text-xs sm:text-sm font-medium">Karya ABK</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" value="preloved" class="filter-checkbox filter-source w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                            <span class="text-xs sm:text-sm font-medium">Preloved</span>
                        </label>
                    </div>
                </div>

                {{-- Filter Group 3: Kondisi (condition: baru / mulus / layak) --}}
                <div class="space-y-3 pt-4 border-t border-[#2B2430]/10">
                    <h3 class="text-xs uppercase tracking-wider font-bold text-[#2B2430]/60">Kondisi</h3>
                    <div class="space-y-2 text-sm text-[#2B2430]">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" value="baru" class="filter-checkbox filter-condition w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                            <span class="text-xs sm:text-sm font-medium">Baru</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" value="mulus" class="filter-checkbox filter-condition w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                            <span class="text-xs sm:text-sm font-medium">Sangat Baik</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" value="layak" class="filter-checkbox filter-condition w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                            <span class="text-xs sm:text-sm font-medium">Baik</span>
                        </label>
                    </div>
                </div>

                {{-- Filter Group 4: Rentang Harga Min / Maks --}}
                <div class="space-y-3 pt-4 border-t border-[#2B2430]/10">
                    <h3 class="text-xs uppercase tracking-wider font-bold text-[#2B2430]/60">Rentang Harga</h3>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <label class="block text-[11px] text-[#2B2430]/60 mb-1">Min (Rp)</label>
                            <input type="number"
                                   id="price-min"
                                   placeholder="0"
                                   min="0"
                                   step="5000"
                                   class="w-full px-2.5 py-1.5 rounded-[6px] border border-[#2B2430]/20 text-xs text-[#2B2430] focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-[11px] text-[#2B2430]/60 mb-1">Maks (Rp)</label>
                            <input type="number"
                                   id="price-max"
                                   placeholder="150000"
                                   min="0"
                                   step="5000"
                                   class="w-full px-2.5 py-1.5 rounded-[6px] border border-[#2B2430]/20 text-xs text-[#2B2430] focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                    </div>
                </div>

                {{-- Tombol Terapkan & Reset --}}
                <div class="pt-4 space-y-2 border-t border-[#2B2430]/10">
                    <button type="button"
                            id="sidebar-apply-btn"
                            class="w-full h-10 flex items-center justify-center rounded-[8px] bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition-all shadow-xs active:scale-95">
                        Terapkan Filter
                    </button>
                    <button type="button"
                            id="sidebar-clear-btn"
                            class="w-full h-10 flex items-center justify-center rounded-[8px] border border-[#2B2430]/20 text-[#2B2430] hover:bg-stone-50 font-semibold text-sm transition-all">
                        Reset Pilihan
                    </button>
                </div>
            </aside>

            {{-- ----------------------------------------------------
                 MAIN CATALOG AREA
                 ---------------------------------------------------- --}}
            <main class="flex-grow w-full space-y-6">

                {{-- Toolbar: Penghitung Produk + Urutkan + Chip Filter Aktif --}}
                <div class="space-y-3 pb-2 border-b border-[#2B2430]/10">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        {{-- Text Status Menampilkan X dari Y produk --}}
                        <p class="text-sm text-[#2B2430]/80 font-medium">
                            Menampilkan <span id="visible-count" class="font-bold text-[#2B2430]">{{ count($productList) }}</span> dari <span id="total-count" class="font-bold text-[#2B2430]">{{ count($productList) }}</span> produk pilihan
                        </p>

                        {{-- Dropdown Urutkan --}}
                        <div class="flex items-center gap-2 self-start sm:self-auto">
                            <label for="catalog-sort" class="text-xs font-semibold text-[#2B2430]/70 shrink-0">
                                Urutkan:
                            </label>
                            <select id="catalog-sort"
                                    class="px-3 py-1.5 rounded-[8px] bg-white border border-[#2B2430]/20 text-xs font-semibold text-[#2B2430] focus:outline-none focus:ring-1 focus:ring-primary cursor-pointer">
                                <option value="terbaru">Terbaru</option>
                                <option value="termurah">Harga Terendah</option>
                                <option value="termahal">Harga Tertinggi</option>
                            </select>
                        </div>
                    </div>

                    {{-- Chip Filter Aktif yang Bisa Dihapus --}}
                    <div id="active-chips-container" class="hidden flex-wrap items-center gap-2 pt-1 text-xs">
                        <span class="text-[11px] text-[#2B2430]/60 font-medium">Filter aktif:</span>
                        <div id="active-chips-list" class="flex flex-wrap items-center gap-2"></div>
                        <button type="button" id="clear-all-chips-btn" class="text-xs text-primary font-semibold hover:underline ml-1">
                            Hapus Semua
                        </button>
                    </div>
                </div>

                {{-- Grid Produk (3 Kolom Desktop, 2 Tablet, 1-2 Mobile) --}}
                <div id="catalog-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($productList as $item)
                        @php
                            $itemId = is_array($item) ? ($item['id'] ?? '') : $item->id;
                            $itemName = is_array($item) ? ($item['name'] ?? $item['title'] ?? '') : $item->name;
                            $itemCategory = is_array($item) ? ($item['category_id'] ?? '') : ($item->category_id ?? '');
                            $itemSource = is_array($item) ? ($item['source'] ?? '') : ($item->source ?? '');
                            $itemCondition = is_array($item) ? ($item['condition'] ?? '') : ($item->condition ?? '');
                            $itemPrice = (float)(is_array($item) ? ($item['price'] ?? 0) : $item->price);
                            $itemDate = is_array($item) ? ($item['id'] ?? 0) : ($item->id ?? 0);
                        @endphp
                        <x-product-card
                            :product="$item"
                            button-label="Pilih Produk"
                            class="catalog-item"
                            data-id="{{ $itemId }}"
                            data-name="{{ strtolower($itemName) }}"
                            data-category="{{ $itemCategory }}"
                            data-source="{{ $itemSource }}"
                            data-condition="{{ $itemCondition }}"
                            data-price="{{ $itemPrice }}"
                            data-order="{{ $itemDate }}"
                        />
                    @endforeach
                </div>

                {{-- Kondisi Nyata: Produk Tidak Ditemukan Saat Filter Kosong --}}
                <div id="no-products-found" class="hidden py-16 px-6 text-center space-y-4 bg-white rounded-[12px] border border-[#2B2430]/10 shadow-xs">
                    <div class="w-12 h-12 mx-auto rounded-full bg-[#FDEEF4] text-primary flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-[#2B2430]">
                        Produk tidak ditemukan
                    </h3>
                    <p class="text-xs sm:text-sm text-[#2B2430]/70 max-w-md mx-auto leading-relaxed">
                        Tidak ada produk yang cocok dengan kombinasi filter yang dipilih. Silakan sesuaikan kriteria atau atur ulang filter Anda.
                    </p>
                    <div class="pt-2">
                        <button type="button"
                                id="empty-state-reset-btn"
                                class="inline-flex items-center justify-center px-6 h-10 rounded-[8px] bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition-all shadow-xs active:scale-95">
                            Reset Filter
                        </button>
                    </div>
                </div>

            </main>
        </div>
    </div>
</section>

{{-- ----------------------------------------------------
     MOBILE FILTER BOTTOM SHEET (DRAWER)
     ---------------------------------------------------- --}}
<div id="mobile-filter-drawer" class="fixed inset-0 z-50 hidden bg-black/50 flex flex-col justify-end lg:hidden transition-opacity">
    <div class="bg-white rounded-t-[20px] max-h-[85vh] flex flex-col w-full shadow-2xl overflow-hidden">
        {{-- Drawer Header --}}
        <div class="flex items-center justify-between p-5 border-b border-[#2B2430]/10">
            <h3 class="font-bold text-base text-[#2B2430]">Filter &amp; Urutkan</h3>
            <button type="button" id="close-filter-drawer-btn" class="p-1 rounded-lg text-[#2B2430]/60 hover:text-[#2B2430]" aria-label="Tutup Filter">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Drawer Content (Scrollable) --}}
        <div class="p-5 overflow-y-auto space-y-6 flex-grow text-xs sm:text-sm">
            {{-- Kategori Mobile --}}
            <div class="space-y-3">
                <h4 class="text-xs uppercase tracking-wider font-bold text-[#2B2430]/60">Kategori</h4>
                <div class="space-y-2 text-[#2B2430]">
                    @if(isset($categories) && count($categories) > 0)
                        @foreach($categories as $category)
                            <label class="flex items-center gap-2.5 cursor-pointer">
                                <input type="checkbox"
                                       value="{{ $category->id }}"
                                       class="filter-checkbox-mobile mobile-category w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                                <span class="font-medium">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    @else
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" value="1" class="filter-checkbox-mobile mobile-category w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                            <span class="font-medium">Rajut &amp; Kriya ABK</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" value="2" class="filter-checkbox-mobile mobile-category w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                            <span class="font-medium">Pakaian Preloved</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" value="3" class="filter-checkbox-mobile mobile-category w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                            <span class="font-medium">Buku &amp; Mainan Edukatif</span>
                        </label>
                    @endif
                </div>
            </div>

            {{-- Jenis Barang Mobile --}}
            <div class="space-y-3 pt-4 border-t border-[#2B2430]/10">
                <h4 class="text-xs uppercase tracking-wider font-bold text-[#2B2430]/60">Jenis Barang</h4>
                <div class="space-y-2 text-[#2B2430]">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" value="karya" class="filter-checkbox-mobile mobile-source w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                        <span class="font-medium">Karya ABK</span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" value="preloved" class="filter-checkbox-mobile mobile-source w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                        <span class="font-medium">Preloved</span>
                    </label>
                </div>
            </div>

            {{-- Kondisi Mobile --}}
            <div class="space-y-3 pt-4 border-t border-[#2B2430]/10">
                <h4 class="text-xs uppercase tracking-wider font-bold text-[#2B2430]/60">Kondisi</h4>
                <div class="space-y-2 text-[#2B2430]">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" value="baru" class="filter-checkbox-mobile mobile-condition w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                        <span class="font-medium">Baru</span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" value="mulus" class="filter-checkbox-mobile mobile-condition w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                        <span class="font-medium">Sangat Baik</span>
                    </label>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" value="layak" class="filter-checkbox-mobile mobile-condition w-4 h-4 rounded border-[#2B2430]/20 text-primary focus:ring-primary">
                        <span class="font-medium">Baik</span>
                    </label>
                </div>
            </div>

            {{-- Rentang Harga Mobile --}}
            <div class="space-y-3 pt-4 border-t border-[#2B2430]/10">
                <h4 class="text-xs uppercase tracking-wider font-bold text-[#2B2430]/60">Rentang Harga</h4>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[11px] text-[#2B2430]/60 mb-1">Min (Rp)</label>
                        <input type="number" id="mobile-price-min" placeholder="0" class="w-full px-2.5 py-1.5 rounded-[6px] border border-[#2B2430]/20 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] text-[#2B2430]/60 mb-1">Maks (Rp)</label>
                        <input type="number" id="mobile-price-max" placeholder="150000" class="w-full px-2.5 py-1.5 rounded-[6px] border border-[#2B2430]/20 text-xs">
                    </div>
                </div>
            </div>
        </div>

        {{-- Drawer Actions --}}
        <div class="p-4 border-t border-[#2B2430]/10 bg-white grid grid-cols-2 gap-3">
            <button type="button" id="mobile-reset-btn" class="h-11 rounded-[8px] border border-[#2B2430]/20 text-[#2B2430] font-semibold text-sm">
                Reset
            </button>
            <button type="button" id="mobile-apply-btn" class="h-11 rounded-[8px] bg-primary text-white font-semibold text-sm">
                Terapkan
            </button>
        </div>
    </div>
</div>

{{-- ----------------------------------------------------
     CLIENT-SIDE FILTER & SORT VANILLA JAVASCRIPT
     ---------------------------------------------------- --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const grid = document.getElementById('catalog-grid');
        const items = Array.from(document.querySelectorAll('.catalog-item'));
        const visibleCountEl = document.getElementById('visible-count');
        const totalCountEl = document.getElementById('total-count');
        const noProductsEl = document.getElementById('no-products-found');
        const sortSelect = document.getElementById('catalog-sort');

        const activeChipsContainer = document.getElementById('active-chips-container');
        const activeChipsList = document.getElementById('active-chips-list');
        const clearAllChipsBtn = document.getElementById('clear-all-chips-btn');

        // Inputs Desktop
        const desktopCatInputs = document.querySelectorAll('.filter-category');
        const desktopSourceInputs = document.querySelectorAll('.filter-source');
        const desktopConditionInputs = document.querySelectorAll('.filter-condition');
        const desktopPriceMin = document.getElementById('price-min');
        const desktopPriceMax = document.getElementById('price-max');
        const sidebarApplyBtn = document.getElementById('sidebar-apply-btn');
        const sidebarResetBtn = document.getElementById('sidebar-reset-btn');
        const emptyStateResetBtn = document.getElementById('empty-state-reset-btn');

        // Drawer Mobile
        const drawer = document.getElementById('mobile-filter-drawer');
        const openDrawerBtn = document.getElementById('open-filter-drawer-btn');
        const closeDrawerBtn = document.getElementById('close-filter-drawer-btn');
        const mobileApplyBtn = document.getElementById('mobile-apply-btn');
        const mobileResetBtn = document.getElementById('mobile-reset-btn');
        const mobileCatInputs = document.querySelectorAll('.mobile-category');
        const mobileSourceInputs = document.querySelectorAll('.mobile-source');
        const mobileConditionInputs = document.querySelectorAll('.mobile-condition');
        const mobilePriceMin = document.getElementById('mobile-price-min');
        const mobilePriceMax = document.getElementById('mobile-price-max');

        // Total products
        if (totalCountEl) totalCountEl.innerText = items.length;

        // Current filter state
        let state = {
            categories: [],
            sources: [],
            conditions: [],
            minPrice: null,
            maxPrice: null
        };

        function syncInputsFromState() {
            // Sync Desktop
            desktopCatInputs.forEach(i => i.checked = state.categories.includes(i.value));
            desktopSourceInputs.forEach(i => i.checked = state.sources.includes(i.value));
            desktopConditionInputs.forEach(i => i.checked = state.conditions.includes(i.value));
            if (desktopPriceMin) desktopPriceMin.value = state.minPrice ?? '';
            if (desktopPriceMax) desktopPriceMax.value = state.maxPrice ?? '';

            // Sync Mobile
            mobileCatInputs.forEach(i => i.checked = state.categories.includes(i.value));
            mobileSourceInputs.forEach(i => i.checked = state.sources.includes(i.value));
            mobileConditionInputs.forEach(i => i.checked = state.conditions.includes(i.value));
            if (mobilePriceMin) mobilePriceMin.value = state.minPrice ?? '';
            if (mobilePriceMax) mobilePriceMax.value = state.maxPrice ?? '';
        }

        function renderChips() {
            if (!activeChipsList || !activeChipsContainer) return;
            activeChipsList.innerHTML = '';

            const chips = [];

            state.categories.forEach(catId => {
                const labelEl = document.querySelector(`.filter-category[value="${catId}"]`);
                const name = labelEl ? labelEl.nextElementSibling?.textContent?.trim() : `Kategori ${catId}`;
                chips.push({ type: 'category', val: catId, label: name });
            });

            state.sources.forEach(src => {
                const label = src === 'karya' ? 'Karya ABK' : 'Preloved';
                chips.push({ type: 'source', val: src, label });
            });

            state.conditions.forEach(cond => {
                const map = { 'baru': 'Kondisi: Baru', 'mulus': 'Kondisi: Sangat Baik', 'layak': 'Kondisi: Baik' };
                chips.push({ type: 'condition', val: cond, label: map[cond] || cond });
            });

            if (state.minPrice !== null && state.minPrice > 0) {
                chips.push({ type: 'minPrice', val: null, label: `Min: Rp ${parseInt(state.minPrice).toLocaleString('id-ID')}` });
            }

            if (state.maxPrice !== null && state.maxPrice > 0) {
                chips.push({ type: 'maxPrice', val: null, label: `Maks: Rp ${parseInt(state.maxPrice).toLocaleString('id-ID')}` });
            }

            if (chips.length > 0) {
                activeChipsContainer.classList.remove('hidden');
                activeChipsContainer.classList.add('flex');

                chips.forEach(chip => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white border border-[#2B2430]/15 text-[#2B2430] hover:border-primary hover:text-primary transition-colors text-[11px] font-medium shadow-2xs';
                    btn.innerHTML = `<span>${chip.label}</span><svg class="w-3 h-3 text-[#2B2430]/50 hover:text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>`;

                    btn.addEventListener('click', () => {
                        if (chip.type === 'category') {
                            state.categories = state.categories.filter(c => c !== chip.val);
                        } else if (chip.type === 'source') {
                            state.sources = state.sources.filter(s => s !== chip.val);
                        } else if (chip.type === 'condition') {
                            state.conditions = state.conditions.filter(c => c !== chip.val);
                        } else if (chip.type === 'minPrice') {
                            state.minPrice = null;
                        } else if (chip.type === 'maxPrice') {
                            state.maxPrice = null;
                        }
                        syncInputsFromState();
                        applyFilters();
                    });

                    activeChipsList.appendChild(btn);
                });
            } else {
                activeChipsContainer.classList.add('hidden');
                activeChipsContainer.classList.remove('flex');
            }
        }

        function applyFilters() {
            let visibleCount = 0;

            items.forEach(card => {
                const cat = card.getAttribute('data-category');
                const src = card.getAttribute('data-source');
                const cond = card.getAttribute('data-condition');
                const price = parseFloat(card.getAttribute('data-price') || '0');

                // Filter Category
                const matchCategory = (state.categories.length === 0) || state.categories.includes(cat);

                // Filter Source
                const matchSource = (state.sources.length === 0) || state.sources.includes(src);

                // Filter Condition
                const matchCondition = (state.conditions.length === 0) || state.conditions.includes(cond);

                // Filter Price
                let matchPrice = true;
                if (state.minPrice !== null && state.minPrice !== '') {
                    if (price < parseFloat(state.minPrice)) matchPrice = false;
                }
                if (state.maxPrice !== null && state.maxPrice !== '') {
                    if (price > parseFloat(state.maxPrice)) matchPrice = false;
                }

                if (matchCategory && matchSource && matchCondition && matchPrice) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCountEl) visibleCountEl.innerText = visibleCount;

            // Empty state check
            if (noProductsEl) {
                if (visibleCount === 0) {
                    noProductsEl.classList.remove('hidden');
                } else {
                    noProductsEl.classList.add('hidden');
                }
            }

            renderChips();
        }

        function resetAllFilters() {
            state = {
                categories: [],
                sources: [],
                conditions: [],
                minPrice: null,
                maxPrice: null
            };
            syncInputsFromState();
            applyFilters();
        }

        // Apply from desktop
        function readFromDesktop() {
            state.categories = Array.from(desktopCatInputs).filter(i => i.checked).map(i => i.value);
            state.sources = Array.from(desktopSourceInputs).filter(i => i.checked).map(i => i.value);
            state.conditions = Array.from(desktopConditionInputs).filter(i => i.checked).map(i => i.value);
            state.minPrice = desktopPriceMin?.value ? parseFloat(desktopPriceMin.value) : null;
            state.maxPrice = desktopPriceMax?.value ? parseFloat(desktopPriceMax.value) : null;
            applyFilters();
        }

        // Apply from mobile
        function readFromMobile() {
            state.categories = Array.from(mobileCatInputs).filter(i => i.checked).map(i => i.value);
            state.sources = Array.from(mobileSourceInputs).filter(i => i.checked).map(i => i.value);
            state.conditions = Array.from(mobileConditionInputs).filter(i => i.checked).map(i => i.value);
            state.minPrice = mobilePriceMin?.value ? parseFloat(mobilePriceMin.value) : null;
            state.maxPrice = mobilePriceMax?.value ? parseFloat(mobilePriceMax.value) : null;
            syncInputsFromState();
            applyFilters();
            closeDrawer();
        }

        // Listeners Desktop
        sidebarApplyBtn?.addEventListener('click', readFromDesktop);
        sidebarResetBtn?.addEventListener('click', resetAllFilters);
        emptyStateResetBtn?.addEventListener('click', resetAllFilters);
        clearAllChipsBtn?.addEventListener('click', resetAllFilters);

        // Instant filter on checkbox changes
        desktopCatInputs.forEach(i => i.addEventListener('change', readFromDesktop));
        desktopSourceInputs.forEach(i => i.addEventListener('change', readFromDesktop));
        desktopConditionInputs.forEach(i => i.addEventListener('change', readFromDesktop));

        // Sorting
        sortSelect?.addEventListener('change', () => {
            const val = sortSelect.value;
            const currentItems = Array.from(items);

            currentItems.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price') || '0');
                const priceB = parseFloat(b.getAttribute('data-price') || '0');
                const orderA = parseInt(a.getAttribute('data-order') || '0', 10);
                const orderB = parseInt(b.getAttribute('data-order') || '0', 10);

                if (val === 'termurah') return priceA - priceB;
                if (val === 'termahal') return priceB - priceA;
                return orderB - orderA; // Terbaru
            });

            currentItems.forEach(el => grid?.appendChild(el));
        });

        // Mobile Drawer Controls
        function openDrawer() {
            if (drawer) {
                syncInputsFromState();
                drawer.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeDrawer() {
            if (drawer) {
                drawer.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        openDrawerBtn?.addEventListener('click', openDrawer);
        closeDrawerBtn?.addEventListener('click', closeDrawer);
        mobileApplyBtn?.addEventListener('click', readFromMobile);
        mobileResetBtn?.addEventListener('click', () => {
            resetAllFilters();
            closeDrawer();
        });

        drawer?.addEventListener('click', (e) => {
            if (e.target === drawer) closeDrawer();
        });

        // Initial setup
        applyFilters();
    });
</script>

{{-- Modal & Skrip Interaktif (Hanya SATU Kali Di-include) --}}
@include('user.pages.home._modal-order')
@include('user.pages.home._scripts')
@endsection
