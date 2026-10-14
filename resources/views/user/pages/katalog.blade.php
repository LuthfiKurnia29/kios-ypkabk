@extends('user.shared.Layout')

@section('content')
<div class="space-y-10 lg:space-y-14 pb-16">

    {{-- ========================================================
         TOP TRUST NOTICE BAR (NFR-02)
         ======================================================== --}}
    <div class="bg-amber-100/90 border-b border-amber-200 text-amber-950 py-2.5 px-4 text-xs font-medium">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-2 text-center md:text-left">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-amber-200 text-amber-800 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
                <span>
                    <strong>NFR-02 Transparan:</strong> 100% margin penjualan dialokasikan untuk beasiswa terapi &amp; kemandirian vokasi anak istimewa YPKABK.
                </span>
            </div>
            <div class="flex items-center gap-4 text-amber-900 text-[11px] font-semibold">
                <span class="flex items-center gap-1">
                    <span>📦</span> Kemasan Kardus Daur Ulang
                </span>
                <span class="text-amber-300">•</span>
                <span class="flex items-center gap-1 text-rose-700">
                    <span>⚡</span> Belanja Cepat Tanpa Perlu Bikin Akun
                </span>
            </div>
        </div>
    </div>


    {{-- ========================================================
         CATALOG HEADER & SEARCH
         ======================================================== --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="text-center max-w-3xl mx-auto space-y-4">
            
            {{-- Category Badge --}}
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                <span>🏷️</span> Etalase Belanja Berdampak Sosial
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-stone-900 tracking-tight leading-tight">
                Etalase Digital Kios Mandiri YPKABK
            </h1>

            <p class="text-sm sm:text-base text-stone-600 leading-relaxed max-w-2xl mx-auto">
                Setiap pembelian Anda 100% mendukung pelatihan kemandirian, fasilitas sensory room, dan beasiswa terapi anak berkebutuhan khusus.
            </p>

            {{-- Search Bar --}}
            <div class="max-w-2xl mx-auto pt-2">
                <div class="relative flex items-center bg-white rounded-2xl border border-stone-300 p-1.5 shadow-sm focus-within:ring-2 focus-within:ring-rose-500 focus-within:border-transparent transition-all">
                    <span class="pl-3.5 text-stone-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" 
                           id="catalog-search-input"
                           placeholder="Cari totebag lukis, kemeja anak preloved, buku dongeng..." 
                           class="w-full pl-3 pr-4 py-2.5 text-sm text-stone-800 placeholder-stone-400 focus:outline-none bg-transparent">
                    <button type="button" 
                            id="catalog-search-btn"
                            class="px-5 py-2.5 rounded-xl bg-rose-700 hover:bg-rose-800 text-white font-semibold text-xs tracking-wide shadow-xs active:scale-95 transition-all shrink-0">
                        Cari Produk
                    </button>
                </div>
            </div>

            {{-- Horizontal Category Pills --}}
            <div class="flex flex-wrap items-center justify-center gap-2 pt-3 text-xs font-semibold" id="catalog-category-tabs">
                <button type="button" data-category="all" class="cat-pill px-4 py-2 rounded-xl bg-rose-700 text-white shadow-xs transition-colors">
                    Semua Produk (128)
                </button>
                <button type="button" data-category="karya-abk" class="cat-pill px-4 py-2 rounded-xl bg-white border border-stone-200 text-stone-700 hover:border-stone-400 transition-colors">
                    <span>🎨</span> Karya Seni &amp; Prakarya ABK (32)
                </button>
                <button type="button" data-category="pakaian" class="cat-pill px-4 py-2 rounded-xl bg-white border border-stone-200 text-stone-700 hover:border-stone-400 transition-colors">
                    <span>👕</span> Pakaian &amp; Aksesori Preloved (45)
                </button>
                <button type="button" data-category="buku-mainan" class="cat-pill px-4 py-2 rounded-xl bg-white border border-stone-200 text-stone-700 hover:border-stone-400 transition-colors">
                    <span>🧸</span> Buku &amp; Mainan Edukatif (27)
                </button>
                <button type="button" data-category="dekorasi" class="cat-pill px-4 py-2 rounded-xl bg-white border border-stone-200 text-stone-700 hover:border-stone-400 transition-colors">
                    <span>🪴</span> Perlengkapan Rumah &amp; Dekorasi (24)
                </button>
            </div>

        </div>
    </section>


    {{-- ========================================================
         MAIN CONTENT: SIDEBAR FILTERS + PRODUCT GRID
         ======================================================== --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- ----------------------------------------------------
                 LEFT COLUMN: FILTER ETALASE (SIDEBAR)
                 ---------------------------------------------------- --}}
            <aside class="lg:col-span-3 space-y-6 bg-white rounded-3xl p-6 border border-stone-200/90 shadow-xs lg:sticky lg:top-28">
                
                {{-- Header Filter --}}
                <div class="flex items-center justify-between pb-4 border-b border-stone-100">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        <h3 class="font-bold text-stone-900 text-sm">Filter Etalase</h3>
                    </div>
                    <button type="button" id="reset-filter-btn" class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition-colors">
                        Reset Semua
                    </button>
                </div>

                {{-- Filter Group 1: Sumber Barang --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-stone-400 uppercase tracking-wider">Sumber Barang</h4>
                    <div class="space-y-2 text-xs text-stone-700">
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div class="flex items-center gap-2.5">
                                <input type="checkbox" value="karya" class="filter-source rounded text-rose-600 focus:ring-rose-500 border-stone-300">
                                <span class="group-hover:text-stone-900 font-medium">Karya Original ABK</span>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800">
                                Spesial
                            </span>
                        </label>
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div class="flex items-center gap-2.5">
                                <input type="checkbox" value="preloved" class="filter-source rounded text-rose-600 focus:ring-rose-500 border-stone-300">
                                <span class="group-hover:text-stone-900 font-medium">Donasi Preloved Lolos QC</span>
                            </div>
                            <span class="text-stone-400 text-[11px]">96</span>
                        </label>
                    </div>
                </div>

                {{-- Filter Group 2: Harga Donasi --}}
                <div class="space-y-3 pt-4 border-t border-stone-100">
                    <h4 class="text-xs font-bold text-stone-400 uppercase tracking-wider">Harga Donasi</h4>
                    <div class="space-y-2 text-xs text-stone-700">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="radio" name="price-filter" value="all" checked class="filter-price text-rose-600 focus:ring-rose-500 border-stone-300">
                            <span>Semua Harga</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="radio" name="price-filter" value="under-30" class="filter-price text-rose-600 focus:ring-rose-500 border-stone-300">
                            <span>&lt; Rp 30.000 <span class="text-[11px] text-stone-400">(Ramah Kantong)</span></span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="radio" name="price-filter" value="30-75" class="filter-price text-rose-600 focus:ring-rose-500 border-stone-300">
                            <span>Rp 30.000 – Rp 75.000</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="radio" name="price-filter" value="over-75" class="filter-price text-rose-600 focus:ring-rose-500 border-stone-300">
                            <span>&gt; Rp 75.000</span>
                        </label>
                    </div>
                </div>

                {{-- Filter Group 3: Tingkat Kondisi --}}
                <div class="space-y-3 pt-4 border-t border-stone-100">
                    <h4 class="text-xs font-bold text-stone-400 uppercase tracking-wider">Tingkat Kondisi</h4>
                    <div class="space-y-2 text-xs text-stone-700">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" value="baru" class="filter-condition rounded text-rose-600 focus:ring-rose-500 border-stone-300">
                            <span>Baru (Prakarya &amp; Vokasi)</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" value="mulus" class="filter-condition rounded text-rose-600 focus:ring-rose-500 border-stone-300">
                            <span>Preloved Sangat Mulus (95–99%)</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" value="layak" class="filter-condition rounded text-rose-600 focus:ring-rose-500 border-stone-300">
                            <span>Preloved Baik &amp; Layak (90%)</span>
                        </label>
                    </div>
                </div>

                {{-- Filter Group 4: Ketersediaan --}}
                <div class="space-y-3 pt-4 border-t border-stone-100">
                    <h4 class="text-xs font-bold text-stone-400 uppercase tracking-wider">Ketersediaan</h4>
                    <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                        <button type="button" class="py-2 px-3 rounded-xl bg-rose-50 text-rose-700 border border-rose-200">
                            Tersedia Saja
                        </button>
                        <button type="button" class="py-2 px-3 rounded-xl bg-stone-50 text-stone-600 hover:bg-stone-100 border border-stone-200">
                            Termasuk Terjual
                        </button>
                    </div>
                </div>

                {{-- Callout Box: Punya Barang Bagus Tak Terpakai? --}}
                <div class="rounded-2xl p-4 bg-sky-50 border border-sky-200 space-y-2.5 text-xs">
                    <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center font-bold">
                        📦
                    </div>
                    <h5 class="font-bold text-stone-900 leading-tight">Punya barang bagus tak terpakai?</h5>
                    <p class="text-stone-600 leading-relaxed text-[11px]">
                        Donasikan pakaian, buku anak, atau mainan edukatif Anda ke Kios Mandiri. Kami cuci, kurasi, dan jadikan dana pemberdayaan anak istimewa.
                    </p>
                    <a href="{{ url('/#donasi-sekarang') }}" class="font-semibold text-rose-700 hover:text-rose-900 transition-colors inline-flex items-center gap-1 text-[11px]">
                        <span>Kirim Donasi Barang Sekarang</span>
                        <span>&rarr;</span>
                    </a>
                </div>

            </aside>


            {{-- ----------------------------------------------------
                 RIGHT COLUMN: PRODUCT CATALOG GRID
                 ---------------------------------------------------- --}}
            <main class="lg:col-span-9 space-y-6">

                {{-- Top Counter & Sorting Bar --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
                    <div class="text-xs text-stone-600">
                        <span class="font-bold text-stone-900">Menampilkan 6 dari 128 barang</span>
                        <span class="text-stone-300 mx-2">•</span>
                        <span>Kurasi Terkini</span>
                    </div>

                    <div class="flex items-center gap-2 text-xs">
                        <label for="catalog-sort" class="text-stone-500 font-medium">Urutkan:</label>
                        <select id="catalog-sort" class="px-3 py-1.5 rounded-xl border border-stone-300 bg-white text-stone-800 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-rose-500">
                            <option value="unggulan">Karya Unggulan ABK</option>
                            <option value="termurah">Harga Terendah</option>
                            <option value="termahal">Harga Tertinggi</option>
                            <option value="terbaru">Terbaru Ditambahkan</option>
                        </select>
                    </div>
                </div>

                {{-- 6 Product Cards Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6" id="catalog-items-grid">

                    {{-- Item 1: Tote Bag --}}
                    <div class="catalog-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
                         data-category="karya-abk"
                         data-source="karya"
                         data-price="45000"
                         data-condition="baru">
                        
                        <div>
                            {{-- Image Container --}}
                            <div class="relative aspect-[4/3] bg-stone-100 overflow-hidden">
                                <img src="{{ asset('images/tote-bag.jpg') }}" 
                                     alt="Tote Bag Kanvas Lukis Pelangi" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-400 text-stone-950 shadow-xs flex items-center gap-1">
                                        <span>🎨</span> Karya Vokasi ABK
                                    </span>
                                </div>
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-stone-700 border border-stone-200/70">
                                        Stok: 3 pcs
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5 space-y-3">
                                <p class="text-[11px] font-semibold text-stone-500 flex items-center gap-1">
                                    <span>✍️</span> Dibuat oleh Tim Vokasional Siswa YPKABK
                                </p>
                                <h3 class="text-base font-bold text-stone-900 leading-snug line-clamp-1">
                                    Tote Bag Kanvas Lukis Pelangi Harapan
                                </h3>
                                <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                                    Totebag katun tebal ukuran 36x40cm, dilukis manual dengan cat akrilik ramah lingkungan. Dilengkapi furing blacu lembut.
                                </p>

                                {{-- Feature Badges --}}
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        100% Baru
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        Bahan Tebal
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer & Actions --}}
                        <div class="px-5 pb-5 pt-2 border-t border-stone-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                                    <span class="text-base font-extrabold text-stone-900">Rp 45.000</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800">
                                    +100% Dana Terapi
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                                <button type="button" onclick="addToCart('Tote Bag Kanvas Lukis Pelangi Harapan')" class="py-2.5 px-3 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors flex items-center justify-center gap-1.5">
                                    <span>🛒</span> Keranjang
                                </button>
                                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Kios%20Mandiri,%20saya%20mau%20pesan%20Tote%20Bag%20Kanvas%20Lukis%20Pelangi%20(Rp%2045.000)" target="_blank" rel="noopener noreferrer" class="py-2.5 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-xs transition-colors flex items-center justify-center gap-1.5">
                                    <span>⚡</span> Beli via WA
                                </a>
                            </div>
                        </div>

                    </div>


                    {{-- Item 2: Buku Cerita --}}
                    <div class="catalog-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
                         data-category="buku-mainan"
                         data-source="preloved"
                         data-price="35000"
                         data-condition="mulus">
                        
                        <div>
                            {{-- Image Container --}}
                            <div class="relative aspect-[4/3] bg-stone-100 overflow-hidden">
                                <img src="{{ asset('images/books.jpg') }}" 
                                     alt="Buku Cerita Bergambar Sahabat Rimba" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-500 text-white shadow-xs flex items-center gap-1">
                                        <span>📖</span> Preloved 98%
                                    </span>
                                </div>
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-rose-700 border border-stone-200/70">
                                        1 pcs (Rare)
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5 space-y-3">
                                <p class="text-[11px] font-semibold text-stone-500 flex items-center gap-1">
                                    <span>💙</span> Donasi dari Kak Arini – Surabaya
                                </p>
                                <h3 class="text-base font-bold text-stone-900 leading-snug line-clamp-1">
                                    Buku Cerita Bergambar: Petualangan Rimba
                                </h3>
                                <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                                    Bilingual Indonesia-Inggris. Kertas tebal glossy, kondisi prima tanpa coretan. Sangat mendidik untuk batita &amp; balita.
                                </p>

                                {{-- Feature Badges --}}
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        QC Lolos
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        Hardcover
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer & Actions --}}
                        <div class="px-5 pb-5 pt-2 border-t border-stone-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-base font-extrabold text-stone-900">Rp 35.000</span>
                                        <span class="text-xs text-stone-400 line-through">Rp 85.000</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                                <button type="button" onclick="addToCart('Buku Cerita Bergambar Rimba')" class="py-2.5 px-3 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors flex items-center justify-center gap-1.5">
                                    <span>🛒</span> Keranjang
                                </button>
                                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Kios%20Mandiri,%20saya%20mau%20pesan%20Buku%20Cerita%20Bergambar%20(Rp%2035.000)" target="_blank" rel="noopener noreferrer" class="py-2.5 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-xs transition-colors flex items-center justify-center gap-1.5">
                                    <span>⚡</span> Beli via WA
                                </a>
                            </div>
                        </div>

                    </div>


                    {{-- Item 3: Jaket Mustard --}}
                    <div class="catalog-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
                         data-category="pakaian"
                         data-source="preloved"
                         data-price="55000"
                         data-condition="mulus">
                        
                        <div>
                            {{-- Image Container --}}
                            <div class="relative aspect-[4/3] bg-amber-50 overflow-hidden flex items-center justify-center">
                                <img src="{{ asset('images/denim-jacket.jpg') }}" 
                                     alt="Jaket Parka Anak Mustard Warm" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-500 text-white shadow-xs flex items-center gap-1">
                                        <span>👕</span> Preloved 95%
                                    </span>
                                </div>
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-stone-700 border border-stone-200/70">
                                        1 pcs (Size 6–8 thn)
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5 space-y-3">
                                <p class="text-[11px] font-semibold text-stone-500 flex items-center gap-1">
                                    <span>💙</span> Donasi dari Ibu Maya – Sidoarjo
                                </p>
                                <h3 class="text-base font-bold text-stone-900 leading-snug line-clamp-1">
                                    Jaket Parka Anak Mustard Warm
                                </h3>
                                <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                                    Bahan katun twill tebal &amp; berfuring halus. Bersih harum, resleting lancar, tidak ada noda atau robek.
                                </p>

                                {{-- Feature Badges --}}
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        Laundry Higienis
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        Unisex
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer & Actions --}}
                        <div class="px-5 pb-5 pt-2 border-t border-stone-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-base font-extrabold text-stone-900">Rp 55.000</span>
                                        <span class="text-xs text-stone-400 line-through">Rp 179.000</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                                <button type="button" onclick="addToCart('Jaket Parka Anak Mustard')" class="py-2.5 px-3 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors flex items-center justify-center gap-1.5">
                                    <span>🛒</span> Keranjang
                                </button>
                                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Kios%20Mandiri,%20saya%20mau%20pesan%20Jaket%20Parka%20Mustard%20(Rp%2055.000)" target="_blank" rel="noopener noreferrer" class="py-2.5 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-xs transition-colors flex items-center justify-center gap-1.5">
                                    <span>⚡</span> Beli via WA
                                </a>
                            </div>
                        </div>

                    </div>


                    {{-- Item 4: Set Tatakan Cangkir --}}
                    <div class="catalog-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
                         data-category="karya-abk"
                         data-source="karya"
                         data-price="28000"
                         data-condition="baru">
                        
                        <div>
                            {{-- Image Container --}}
                            <div class="relative aspect-[4/3] bg-gradient-to-br from-amber-50 to-orange-100 overflow-hidden flex items-center justify-center p-4">
                                <div class="flex items-center justify-center gap-3">
                                    <div class="w-20 h-20 rounded-full bg-orange-200 border-4 border-amber-300 flex flex-col items-center justify-center shadow-md">
                                        <span class="text-2xl">☀️</span>
                                        <span class="text-[9px] font-bold text-amber-900">Gerabah</span>
                                    </div>
                                    <div class="w-20 h-20 rounded-full bg-emerald-100 border-4 border-emerald-300 flex flex-col items-center justify-center shadow-md">
                                        <span class="text-2xl">🌿</span>
                                        <span class="text-[9px] font-bold text-emerald-900">Lukis</span>
                                    </div>
                                </div>
                                
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-400 text-stone-950 shadow-xs flex items-center gap-1">
                                        <span>🎨</span> Karya Siswa ABK
                                    </span>
                                </div>
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-stone-700 border border-stone-200/70">
                                        Stok: 5 set
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5 space-y-3">
                                <p class="text-[11px] font-semibold text-stone-500 flex items-center gap-1">
                                    <span>✍️</span> Kelas Kerajinan Tangan YPKABK
                                </p>
                                <h3 class="text-base font-bold text-stone-900 leading-snug line-clamp-1">
                                    Set Tatakan Cangkir Lukis Gerabah
                                </h3>
                                <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                                    Tatakan gerabah terra cotta dilapisi pernis anti-air. Mempercantik meja kerja sekaligus menyerap embun gelas.
                                </p>

                                {{-- Feature Badges --}}
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        Anti Air
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        Handmade
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer & Actions --}}
                        <div class="px-5 pb-5 pt-2 border-t border-stone-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                                    <span class="text-base font-extrabold text-stone-900">Rp 28.000</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-100 text-rose-800">
                                    Best Seller
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                                <button type="button" onclick="addToCart('Set Tatakan Cangkir Lukis Gerabah')" class="py-2.5 px-3 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors flex items-center justify-center gap-1.5">
                                    <span>🛒</span> Keranjang
                                </button>
                                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Kios%20Mandiri,%20saya%20mau%20pesan%20Set%20Tatakan%20Cangkir%20(Rp%2028.000)" target="_blank" rel="noopener noreferrer" class="py-2.5 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-xs transition-colors flex items-center justify-center gap-1.5">
                                    <span>⚡</span> Beli via WA
                                </a>
                            </div>
                        </div>

                    </div>


                    {{-- Item 5: Wooden Montessori --}}
                    <div class="catalog-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
                         data-category="buku-mainan"
                         data-source="preloved"
                         data-price="42000"
                         data-condition="mulus">
                        
                        <div>
                            {{-- Image Container --}}
                            <div class="relative aspect-[4/3] bg-stone-100 overflow-hidden">
                                <img src="{{ asset('images/balance-board.jpg') }}" 
                                     alt="Wooden Montessori Stacking Tower" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-500 text-white shadow-xs flex items-center gap-1">
                                        <span>🪵</span> Preloved 95%
                                    </span>
                                </div>
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-stone-700 border border-stone-200/70">
                                        1 unit (Lengkap)
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5 space-y-3">
                                <p class="text-[11px] font-semibold text-stone-500 flex items-center gap-1">
                                    <span>💙</span> Donasi dari Keluarga Bpk. Doni – Malang
                                </p>
                                <h3 class="text-base font-bold text-stone-900 leading-snug line-clamp-1">
                                    Wooden Montessori Stacking Tower
                                </h3>
                                <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                                    Kayu halus tanpa serpihan, cat non-toxic waterbased aman untuk batita. Melatih koordinasi motorik &amp; kognitif.
                                </p>

                                {{-- Feature Badges --}}
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        Komplit 16 Balok
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        Desinfeksi UV
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer & Actions --}}
                        <div class="px-5 pb-5 pt-2 border-t border-stone-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-base font-extrabold text-stone-900">Rp 42.000</span>
                                        <span class="text-xs text-stone-400 line-through">Rp 120.000</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                                <button type="button" onclick="addToCart('Wooden Montessori Stacking Tower')" class="py-2.5 px-3 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors flex items-center justify-center gap-1.5">
                                    <span>🛒</span> Keranjang
                                </button>
                                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Kios%20Mandiri,%20saya%20mau%20pesan%20Wooden%20Montessori%20(Rp%2042.000)" target="_blank" rel="noopener noreferrer" class="py-2.5 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-xs transition-colors flex items-center justify-center gap-1.5">
                                    <span>⚡</span> Beli via WA
                                </a>
                            </div>
                        </div>

                    </div>


                    {{-- Item 6: Hiasan Makrame --}}
                    <div class="catalog-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group"
                         data-category="dekorasi"
                         data-source="preloved"
                         data-price="65000"
                         data-condition="mulus">
                        
                        <div>
                            {{-- Image Container --}}
                            <div class="relative aspect-[4/3] bg-stone-100 overflow-hidden flex items-center justify-center">
                                <img src="{{ asset('images/crochet-bunny.jpg') }}" 
                                     alt="Hiasan Dinding Makrame Bohemian" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-500 text-white shadow-xs flex items-center gap-1">
                                        <span>🧵</span> Preloved 97%
                                    </span>
                                </div>
                                <div class="absolute top-3 right-3">
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-rose-700 border border-stone-200/70">
                                        1 pcs Tersedia
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5 space-y-3">
                                <p class="text-[11px] font-semibold text-stone-500 flex items-center gap-1">
                                    <span>💙</span> Donasi dari Ibu Laras – Bandung
                                </p>
                                <h3 class="text-base font-bold text-stone-900 leading-snug line-clamp-1">
                                    Hiasan Dinding Makrame Bohemian
                                </h3>
                                <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                                    Tali katun premium warna broken white, kayu gantungan jati belanda halus. Estetik untuk ruang tamu atau kamar anak.
                                </p>

                                {{-- Feature Badges --}}
                                <div class="flex flex-wrap gap-1.5 pt-1">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        Dekorasi Unik
                                    </span>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-stone-100 text-stone-600">
                                        Sudah Dicuci
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Footer & Actions --}}
                        <div class="px-5 pb-5 pt-2 border-t border-stone-100 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-base font-extrabold text-stone-900">Rp 65.000</span>
                                        <span class="text-xs text-stone-400 line-through">Rp 160.000</span>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 text-xs font-semibold">
                                <button type="button" onclick="addToCart('Hiasan Dinding Makrame Bohemian')" class="py-2.5 px-3 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors flex items-center justify-center gap-1.5">
                                    <span>🛒</span> Keranjang
                                </button>
                                <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Kios%20Mandiri,%20saya%20mau%20pesan%20Makrame%20Bohemian%20(Rp%2065.000)" target="_blank" rel="noopener noreferrer" class="py-2.5 px-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-xs transition-colors flex items-center justify-center gap-1.5">
                                    <span>⚡</span> Beli via WA
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

                {{-- Pagination Row --}}
                <div class="pt-8 border-t border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-500">
                    <div>
                        Menampilkan 6 barang aktif dari total 128 barang terkurasi minggu ini.
                    </div>
                    <div class="flex items-center gap-1 font-semibold">
                        <button type="button" class="px-3 py-1.5 rounded-lg border border-stone-200 hover:bg-stone-50 text-stone-400 cursor-not-allowed" disabled>
                            Sebelumnya
                        </button>
                        <button type="button" class="w-8 h-8 rounded-lg bg-rose-700 text-white font-bold shadow-xs">
                            1
                        </button>
                        <button type="button" class="w-8 h-8 rounded-lg border border-stone-200 hover:bg-stone-50 text-stone-700">
                            2
                        </button>
                        <button type="button" class="w-8 h-8 rounded-lg border border-stone-200 hover:bg-stone-50 text-stone-700">
                            3
                        </button>
                        <button type="button" class="px-3 py-1.5 rounded-lg border border-stone-200 hover:bg-stone-50 text-rose-700 font-bold">
                            Berikutnya
                        </button>
                    </div>
                </div>

            </main>

        </div>
    </section>


    {{-- ========================================================
         BOTTOM SECTION: STANDAR HIGIENITAS & PENGEMASAN
         ======================================================== --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
        
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-10">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-rose-100 text-rose-800">
                STANDAR HIGIENITAS &amp; PENGEMASAN
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-stone-900 tracking-tight">
                Belanja Nyaman, Hati Tenang, Berdampak Nyata
            </h2>
            <p class="text-stone-600 text-sm leading-relaxed">
                Semua produk preloved maupun karya kerajinan melalui proses seleksi ketat sebelum sampai di tangan Anda.
            </p>
        </div>

        {{-- 3 Feature Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Feature 1 --}}
            <div class="bg-white rounded-3xl p-7 border border-stone-200/90 shadow-xs space-y-4 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                    🧴
                </div>
                <h3 class="text-base font-bold text-stone-900">
                    Pembersihan &amp; Sanitasi Higienis
                </h3>
                <p class="text-xs text-stone-500 leading-relaxed">
                    Pakaian donasi dicuci laundry berpewangi lembut, mainan dan buku dibersihkan dengan cairan disinfektan ramah anak sebelum difoto &amp; diarsipkan.
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="bg-white rounded-3xl p-7 border border-stone-200/90 shadow-xs space-y-4 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl">
                    📦
                </div>
                <h3 class="text-base font-bold text-stone-900">
                    Kemasan Daur Ulang Ramah Lingkungan
                </h3>
                <p class="text-xs text-stone-500 leading-relaxed">
                    Kami meminimalisir bubble wrap plastik dan menggunakan kotak kardus daur ulang bersih, disematkan kartu ucapan terima kasih buatan tangan anak binaan.
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="bg-white rounded-3xl p-7 border border-stone-200/90 shadow-xs space-y-4 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                    🚚
                </div>
                <h3 class="text-base font-bold text-stone-900">
                    Ongkir Hemat ke Seluruh Indonesia
                </h3>
                <p class="text-xs text-stone-500 leading-relaxed">
                    Bekerjasama dengan kurir reguler &amp; kargo terpercaya. Pengiriman setiap hari kerja dari Kios Mandiri Surabaya disertai pelacakan nomor resi otomatis.
                </p>
            </div>

        </div>

    </section>

</div>

{{-- Interactive Client-side Logic for Catalog Search, Filter, and Cart Toast --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('catalog-search-input');
        const searchBtn = document.getElementById('catalog-search-btn');
        const categoryPills = document.querySelectorAll('.cat-pill');
        const sourceCheckboxes = document.querySelectorAll('.filter-source');
        const priceRadios = document.querySelectorAll('.filter-price');
        const conditionCheckboxes = document.querySelectorAll('.filter-condition');
        const resetBtn = document.getElementById('reset-filter-btn');
        const cards = document.querySelectorAll('.catalog-card');
        const sortSelect = document.getElementById('catalog-sort');

        let activeCategory = 'all';

        function applyFilters() {
            const query = (searchInput?.value || '').toLowerCase().trim();
            const checkedSources = Array.from(sourceCheckboxes).filter(c => c.checked).map(c => c.value);
            const selectedPrice = Array.from(priceRadios).find(r => r.checked)?.value || 'all';
            const checkedConditions = Array.from(conditionCheckboxes).filter(c => c.checked).map(c => c.value);

            cards.forEach(card => {
                const title = card.querySelector('h3')?.innerText.toLowerCase() || '';
                const desc = card.querySelector('p.line-clamp-2')?.innerText.toLowerCase() || '';
                const cat = card.getAttribute('data-category');
                const source = card.getAttribute('data-source');
                const price = parseInt(card.getAttribute('data-price') || '0', 10);
                const condition = card.getAttribute('data-condition');

                // Search query match
                const matchesQuery = !query || title.includes(query) || desc.includes(query);

                // Category pill match
                const matchesCat = (activeCategory === 'all') || (cat === activeCategory);

                // Source checkbox match
                const matchesSource = (checkedSources.length === 0) || checkedSources.includes(source);

                // Price radio match
                let matchesPrice = true;
                if (selectedPrice === 'under-30') matchesPrice = price < 30000;
                else if (selectedPrice === '30-75') matchesPrice = price >= 30000 && price <= 75000;
                else if (selectedPrice === 'over-75') matchesPrice = price > 75000;

                // Condition checkbox match
                const matchesCondition = (checkedConditions.length === 0) || checkedConditions.includes(condition);

                if (matchesQuery && matchesCat && matchesSource && matchesPrice && matchesCondition) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Category tabs click
        categoryPills.forEach(pill => {
            pill.addEventListener('click', () => {
                categoryPills.forEach(p => {
                    p.classList.remove('bg-rose-700', 'text-white', 'shadow-xs');
                    p.classList.add('bg-white', 'border', 'border-stone-200', 'text-stone-700');
                });
                pill.classList.remove('bg-white', 'border', 'border-stone-200', 'text-stone-700');
                pill.classList.add('bg-rose-700', 'text-white', 'shadow-xs');

                activeCategory = pill.getAttribute('data-category');
                applyFilters();
            });
        });

        // Search trigger
        searchBtn?.addEventListener('click', applyFilters);
        searchInput?.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') applyFilters();
        });

        // Filter events
        sourceCheckboxes.forEach(c => c.addEventListener('change', applyFilters));
        priceRadios.forEach(r => r.addEventListener('change', applyFilters));
        conditionCheckboxes.forEach(c => c.addEventListener('change', applyFilters));

        // Reset filter
        resetBtn?.addEventListener('click', () => {
            if (searchInput) searchInput.value = '';
            sourceCheckboxes.forEach(c => c.checked = false);
            conditionCheckboxes.forEach(c => c.checked = false);
            const allPrice = document.querySelector('.filter-price[value="all"]');
            if (allPrice) allPrice.checked = true;

            categoryPills[0]?.click();
            applyFilters();
        });

        // Sorting
        sortSelect?.addEventListener('change', () => {
            const container = document.getElementById('catalog-items-grid');
            if (!container) return;
            const items = Array.from(cards);

            const val = sortSelect.value;
            items.sort((a, b) => {
                const priceA = parseInt(a.getAttribute('data-price') || '0', 10);
                const priceB = parseInt(b.getAttribute('data-price') || '0', 10);

                if (val === 'termurah') return priceA - priceB;
                if (val === 'termahal') return priceB - priceA;
                return 0;
            });

            items.forEach(item => container.appendChild(item));
        });
    });

    // Simple Cart notification
    function addToCart(title) {
        alert(`Berhasil menambahkan "${title}" ke keranjang donasi belanja Anda!`);
    }
</script>
@endsection
