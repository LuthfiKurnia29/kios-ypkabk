@extends('user.shared.Layout')

@section('content')
<div class="space-y-16 lg:space-y-24 py-8 lg:py-12">

    {{-- ========================================================
         HERO SECTION
         ======================================================== --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            
            {{-- Left Column: Copy & Actions --}}
            <div class="lg:col-span-7 space-y-6">
                
                {{-- Top Badge / Announcement Pill --}}
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-rose-50 border border-rose-200/80 shadow-xs">
                    <span class="w-6 h-6 rounded-full bg-rose-100 flex items-center justify-center text-rose-600">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    </span>
                    <span class="text-xs font-semibold text-rose-950">
                        Aksi Nyata di Kios Solo &amp; Surabaya
                    </span>
                    <span class="text-rose-300">•</span>
                    <a href="#transparansi" class="text-xs font-medium text-rose-700 hover:text-rose-900 transition-colors flex items-center gap-1">
                        Baca update donasi dan penyaluran
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                {{-- Main Headline --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-stone-900 tracking-tight leading-[1.18]">
                    Beri Kehidupan Kedua untuk Barangmu, 
                    <span class="text-rose-700 underline decoration-rose-300 decoration-wavy decoration-2">Ciptakan Peluang</span> 
                    untuk Anak Istimewa.
                </h1>

                {{-- Subtitle / Description --}}
                <p class="text-base sm:text-lg text-stone-600 leading-relaxed max-w-2xl">
                    Kios Mandiri mengelola donasi <span class="font-semibold text-stone-800">preloved layak pakai</span> dan karya kreatif anak berkebutuhan khusus binaan Yayasan Peduli Kasih ABK (YPKABK) untuk membiayai ruang terapi gratis dan pelatihan vokasi mandiri.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <a href="#katalog" class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-rose-700 hover:bg-rose-800 text-white font-semibold text-sm shadow-md shadow-rose-700/25 active:scale-95 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span>Jelajahi Katalog Belanja</span>
                    </a>

                    <a href="#donasi-sekarang" class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-teal-800 hover:bg-teal-900 text-white font-semibold text-sm shadow-md shadow-teal-900/25 active:scale-95 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Donasikan Barang Preloved</span>
                    </a>
                </div>

                {{-- Trust Badges Row --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3">
                    <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-white border border-stone-200/80 shadow-xs">
                        <span class="w-7 h-7 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="text-xs font-medium text-stone-700 leading-snug">
                            100% Hasil Donasi &amp; Penjualan Tersalurkan
                        </span>
                    </div>

                    <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-white border border-stone-200/80 shadow-xs">
                        <span class="w-7 h-7 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                        <span class="text-xs font-medium text-stone-700 leading-snug">
                            Tanpa Biaya Masuk / Pelatihan Terapi
                        </span>
                    </div>

                    <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-white border border-stone-200/80 shadow-xs">
                        <span class="w-7 h-7 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                        </span>
                        <span class="text-xs font-medium text-stone-700 leading-snug">
                            Bisa Drop-off / Pick-up Solo &amp; Surabaya
                        </span>
                    </div>
                </div>

            </div>

            {{-- Right Column: Showcase Card & Photo Preview --}}
            <div class="lg:col-span-5 relative">
                
                {{-- Main Hero Display Card --}}
                <div class="bg-white rounded-3xl p-5 border border-stone-200/90 shadow-xl shadow-stone-200/50 space-y-4">
                    
                    {{-- Showcase Card Header --}}
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-sm">
                                📦
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-stone-900 leading-tight">Etalase Kasih ABK</h3>
                                <p class="text-[11px] text-stone-500">Update harian langsung dari studio vokasi</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            Live Stats
                        </span>
                    </div>

                    {{-- Large Workshop Photo --}}
                    <div class="relative rounded-2xl overflow-hidden aspect-[4/3] bg-stone-100 shadow-inner group">
                        <img src="{{ asset('images/hero-workshop.jpg') }}" 
                             alt="Workshop Vokasi Anak Istimewa YPKABK" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="eager">

                        {{-- Floating pill tags on photo --}}
                        <div class="absolute bottom-3 left-3 right-3 flex flex-col gap-1.5 p-3 rounded-xl bg-black/60 backdrop-blur-md text-white">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] uppercase font-bold tracking-wider bg-rose-600 px-2 py-0.5 rounded text-white">
                                    Studio Kreatif Vokasi
                                </span>
                                <span class="text-[11px] text-stone-200">
                                    Bina Mandiri ABK
                                </span>
                            </div>
                            <h4 class="text-sm font-semibold leading-tight text-white">
                                Kemewahan Upcycle Lewat Tangan Penuh Kasih
                            </h4>
                        </div>
                    </div>

                    {{-- Bottom Item Preview in Card --}}
                    <div class="flex items-center justify-between pt-1 text-xs">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-700 flex items-center justify-center font-bold text-xs">
                                🎨
                            </div>
                            <div>
                                <p class="font-semibold text-stone-800 leading-tight">Pohon Daur Ulang Melati</p>
                                <p class="text-[11px] text-stone-500">Karya Sentra Kreatif Solo</p>
                            </div>
                        </div>
                        <a href="#katalog" class="text-rose-700 font-semibold hover:text-rose-900 transition-colors flex items-center gap-1">
                            Lihat detail &rarr;
                        </a>
                    </div>

                </div>

                {{-- Floating Tooltip / Quote on Bottom Left of Hero Card --}}
                <div class="mt-4 sm:mt-0 sm:absolute sm:-bottom-6 sm:-left-8 sm:max-w-xs bg-amber-50/95 backdrop-blur-sm border border-amber-200/90 rounded-2xl p-3.5 shadow-lg shadow-amber-900/10 flex items-start gap-3">
                    <span class="text-xl">🌱</span>
                    <p class="text-xs text-amber-950 font-medium leading-relaxed">
                        <strong class="font-bold text-amber-900">Mini Donasi Nyata:</strong> Setiap transaksi senilai Rp 25.000 setara dengan membiayai 1 sesi modul edukasi anak disabilitas binaan kami.
                    </p>
                </div>

            </div>

        </div>
    </section>


    {{-- ========================================================
         METRICS / IMPACT STATS BAR (4 CARDS)
         ======================================================== --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            {{-- Stat 1 --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-stone-200/80 shadow-xs hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">
                    1.420+
                </div>
                <div class="text-sm font-bold text-stone-800 mt-1">
                    Barang Preloved Tersalurkan
                </div>
                <div class="text-xs text-stone-500 mt-0.5">
                    Dari 1.120+ donatur seluruh Indonesia
                </div>
            </div>

            {{-- Stat 2 --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-stone-200/80 shadow-xs hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">
                    38 Anak
                </div>
                <div class="text-sm font-bold text-stone-800 mt-1">
                    ABK Terlatih Mandiri
                </div>
                <div class="text-xs text-stone-500 mt-0.5">
                    Kelas keterampilan &amp; pelayanan kios
                </div>
            </div>

            {{-- Stat 3 --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-stone-200/80 shadow-xs hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">
                    Rp 84,5 Juta
                </div>
                <div class="text-sm font-bold text-stone-800 mt-1">
                    Dana Terapi Terkumpul
                </div>
                <div class="text-xs text-stone-500 mt-0.5">
                    100% dialokasikan secara transparan
                </div>
            </div>

            {{-- Stat 4 --}}
            <div class="bg-white rounded-2xl p-5 sm:p-6 border border-stone-200/80 shadow-xs hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-stone-900 tracking-tight">
                    100%
                </div>
                <div class="text-sm font-bold text-stone-800 mt-1">
                    Laporan Akuntabel
                </div>
                <div class="text-xs text-stone-500 mt-0.5">
                    Audit berkala oleh YPKABK
                </div>
            </div>

        </div>
    </section>


    {{-- ========================================================
         SECTION: BAGAIMANA KIOS MANDIRI BEKERJA? (PROCESS)
         ======================================================== --}}
    <section id="cara-kerja" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-24">
        
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto space-y-3 mb-12">
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold tracking-wider uppercase bg-amber-100 text-amber-800">
                TRANSPARANSI &amp; PEMBERDAYAAN
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight">
                Bagaimana Kios Mandiri Bekerja?
            </h2>
            <p class="text-stone-600 text-sm sm:text-base leading-relaxed">
                Setiap donasi pakaian, buku, atau kerajinan tangan melalui siklus kasih yang ramah, memberdayakan, dan membina masa depan.
            </p>
        </div>

        {{-- 4 Process Steps Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            {{-- Step 1 --}}
            <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-xs flex flex-col justify-between hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="w-9 h-9 rounded-full bg-rose-100 text-rose-700 font-extrabold flex items-center justify-center text-sm shadow-xs">
                            1
                        </span>
                        <span class="text-xl">📦</span>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900">
                        Donasi Diterima
                    </h3>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        Donatur mengirimkan pakaian layak pakai, buku bacaan, mainan edukatif, atau alat lukis/alat musik melalui pos maupun drop-off langsung di kantor yayasan.
                    </p>
                </div>
                <div class="pt-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                        <span>✅</span> Bebas Titip Ekspedisi
                    </span>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-xs flex flex-col justify-between hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="w-9 h-9 rounded-full bg-amber-100 text-amber-700 font-extrabold flex items-center justify-center text-sm shadow-xs">
                            2
                        </span>
                        <span class="text-xl">📹</span>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900">
                        Unboxing &amp; Sortir
                    </h3>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        Tim relawan bersama anak ABK membuka paket dengan dokumentasi video transparan, memvalidasi kualitas, mencuci higienis, dan mengelompokkan kategori.
                    </p>
                </div>
                <div class="pt-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                        <span>🎥</span> Higienis &amp; Tercatat CRM
                    </span>
                </div>
            </div>

            {{-- Step 3 --}}
            <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-xs flex flex-col justify-between hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="w-9 h-9 rounded-full bg-teal-100 text-teal-700 font-extrabold flex items-center justify-center text-sm shadow-xs">
                            3
                        </span>
                        <span class="text-xl">🏬</span>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900">
                        Etalase &amp; Pelatihan
                    </h3>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        Anak-anak ABK berlatih memasang label harga, menata display rak, memotret etalase online, serta melatih interaksi sosial sebagai kasir cilik yang percaya diri.
                    </p>
                </div>
                <div class="pt-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-teal-50 text-teal-700 border border-teal-100">
                        <span>🎓</span> Laboratorium Vokasional
                    </span>
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="bg-white rounded-3xl p-6 border border-stone-200/80 shadow-xs flex flex-col justify-between hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="w-9 h-9 rounded-full bg-rose-100 text-rose-700 font-extrabold flex items-center justify-center text-sm shadow-xs">
                            4
                        </span>
                        <span class="text-xl">💖</span>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900">
                        Dana Terapi &amp; Mandiri
                    </h3>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        100% sumbangsih dan laba digunakan untuk membiayai fisioterapi, terapi wicara, beasiswa anak kurang mampu, dan honor apresiasi bagi para karya mandiri.
                    </p>
                </div>
                <div class="pt-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                        <span>📈</span> Dompet Tembus 4 Posko
                    </span>
                </div>
            </div>

        </div>
    </section>


    {{-- ========================================================
         SECTION: PRODUK PILIHAN / KATALOG (6 CARDS GRID)
         ======================================================== --}}
    <section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-24">
        
        {{-- Section Header & Filter Tabs --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
            <div>
                <a href="#katalog" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-rose-600 mb-2">
                    <span>&larr; Pilihan Pilihan Berkah &amp; Donasi</span>
                </a>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-stone-900 tracking-tight">
                    Karya Anak Istimewa &amp; Donasi Preloved Terpilih
                </h2>
                <p class="text-sm text-stone-500 mt-1">
                    Setiap pembelian langsung mendanai sesi rehabilitasi dan membuka masa depan mandiri.
                </p>
            </div>

            {{-- Interactive Filter Tabs --}}
            <div class="flex flex-wrap gap-2 text-xs font-semibold" id="category-filter-container">
                <button type="button" data-filter="all" class="category-btn px-4 py-2 rounded-xl bg-rose-700 text-white shadow-xs transition-colors">
                    Semua Produk
                </button>
                <button type="button" data-filter="handmade" class="category-btn px-4 py-2 rounded-xl bg-white border border-stone-200 text-stone-600 hover:text-stone-900 hover:border-stone-400 transition-colors">
                    Karya Anak ABK (Handmade)
                </button>
                <button type="button" data-filter="preloved" class="category-btn px-4 py-2 rounded-xl bg-white border border-stone-200 text-stone-600 hover:text-stone-900 hover:border-stone-400 transition-colors">
                    Preloved Pilihan
                </button>
                <button type="button" data-filter="kids" class="category-btn px-4 py-2 rounded-xl bg-white border border-stone-200 text-stone-600 hover:text-stone-900 hover:border-stone-400 transition-colors">
                    Buku &amp; Mainan Anak
                </button>
            </div>
        </div>

        {{-- 6 Product Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8" id="product-grid">

            {{-- Product 1 --}}
            <div class="product-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group" data-category="handmade">
                <div class="relative aspect-[4/3] bg-stone-100 overflow-hidden">
                    <img src="{{ asset('images/tote-bag.jpg') }}" 
                         alt="Tote Bag Lukis Pelangi Bahagia" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-700 text-white shadow-xs">
                            Karya ABK
                        </span>
                    </div>
                    <div class="absolute bottom-3 right-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-stone-700 border border-stone-200/70">
                            Stok 1 pcs
                        </span>
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-1.5">
                        <p class="text-[11px] font-semibold text-rose-700 flex items-center gap-1">
                            <span>🎨</span> Kriya ABK Binaan Kios Mandiri
                        </p>
                        <h3 class="text-base font-bold text-stone-900 leading-snug">
                            Tote Bag Lukis Pelangi Bahagia
                        </h3>
                        <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                            Kanvas tebal 100% katun dikerjakan 2 hari penuh oleh Kevin (autisme). Dilapisi antiair membantu ketahanan warna.
                        </p>
                    </div>

                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                            <span class="text-base font-extrabold text-stone-900">Rp 45.000</span>
                        </div>
                        <button type="button" onclick="openOrderModal('Tote Bag Lukis Pelangi Bahagia', '45.000')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-700 hover:text-white font-semibold text-xs border border-rose-200 transition-all active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Beli &amp; Bantu</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Product 2 --}}
            <div class="product-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group" data-category="handmade">
                <div class="relative aspect-[4/3] bg-stone-100 overflow-hidden">
                    <img src="{{ asset('images/crochet-bunny.jpg') }}" 
                         alt="Boneka Rajut Amigurumi Kelinci" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-700 text-white shadow-xs">
                            Karya ABK
                        </span>
                    </div>
                    <div class="absolute bottom-3 right-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-stone-700 border border-stone-200/70">
                            Sisa 3 pcs
                        </span>
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-1.5">
                        <p class="text-[11px] font-semibold text-rose-700 flex items-center gap-1">
                            <span>🧶</span> Karya ABK Mandiri Kelas Rajut Vokasi
                        </p>
                        <h3 class="text-base font-bold text-stone-900 leading-snug">
                            Boneka Rajut Amigurumi Kelinci
                        </h3>
                        <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                            Benang katun susu lembut anti-alergi dengan isian dacron bersih. Dibuat dengan ketelitian dan ketelatenan tinggi.
                        </p>
                    </div>

                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                            <span class="text-base font-extrabold text-stone-900">Rp 65.000</span>
                        </div>
                        <button type="button" onclick="openOrderModal('Boneka Rajut Amigurumi Kelinci', '65.000')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-700 hover:text-white font-semibold text-xs border border-rose-200 transition-all active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Beli &amp; Bantu</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Product 3 --}}
            <div class="product-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group" data-category="kids">
                <div class="relative aspect-[4/3] bg-stone-100 overflow-hidden">
                    <img src="{{ asset('images/books.jpg') }}" 
                         alt="Set Ensiklopedia Anak Bergambar" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-700 text-white shadow-xs">
                            Donasi Terpilih
                        </span>
                    </div>
                    <div class="absolute bottom-3 right-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-stone-700 border border-stone-200/70">
                            1 Set Saja
                        </span>
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-1.5">
                        <p class="text-[11px] font-semibold text-teal-700 flex items-center gap-1">
                            <span>📚</span> Donasi dari Keluarga Bpk. Adrian (JKT)
                        </p>
                        <h3 class="text-base font-bold text-stone-900 leading-snug">
                            Set Ensiklopedia Anak Bergambar (5 Jilid)
                        </h3>
                        <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                            Kondisi 95% mulus seperti baru! Seri edukasi berwarna: Seri Binatang, Semesta Alam, dan Antariksa. Layak baca.
                        </p>
                    </div>

                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                            <span class="text-base font-extrabold text-stone-900">Rp 95.000</span>
                        </div>
                        <button type="button" onclick="openOrderModal('Set Ensiklopedia Anak Bergambar (5 Jilid)', '95.000')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-700 hover:text-white font-semibold text-xs border border-rose-200 transition-all active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Beli &amp; Bantu</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Product 4 --}}
            <div class="product-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group" data-category="preloved">
                <div class="relative aspect-[4/3] bg-stone-100 overflow-hidden">
                    <img src="{{ asset('images/denim-jacket.jpg') }}" 
                         alt="Jaket Denim Anak Motif Dinosaurus" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-600 text-white shadow-xs">
                            Preloved Istimewa
                        </span>
                    </div>
                    <div class="absolute bottom-3 right-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-stone-700 border border-stone-200/70">
                            Size 4-5 Thn
                        </span>
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-1.5">
                        <p class="text-[11px] font-semibold text-amber-700 flex items-center gap-1">
                            <span>👕</span> Sumbangan Donatur dr. Shanti
                        </p>
                        <h3 class="text-base font-bold text-stone-900 leading-snug">
                            Jaket Denim Anak Motif Dinosaurus
                        </h3>
                        <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                            Bahan denim lembut tidak kaku, warna biru cerah tanpa noda atau sobek. Donasi dari Ibu Maya di Surabaya.
                        </p>
                    </div>

                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                            <span class="text-base font-extrabold text-stone-900">Rp 50.000</span>
                        </div>
                        <button type="button" onclick="openOrderModal('Jaket Denim Anak Motif Dinosaurus', '50.000')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-700 hover:text-white font-semibold text-xs border border-rose-200 transition-all active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Beli &amp; Bantu</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Product 5 --}}
            <div class="product-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group" data-category="handmade">
                <div class="relative aspect-[4/3] bg-stone-100 overflow-hidden">
                    <img src="{{ asset('images/balance-board.jpg') }}" 
                         alt="Papan Titian Keseimbangan Kayu" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                         loading="lazy">
                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-700 text-white shadow-xs">
                            Karya Kayu ABK
                        </span>
                    </div>
                    <div class="absolute bottom-3 right-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-stone-700 border border-stone-200/70">
                            Stok 4 unit
                        </span>
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-1.5">
                        <p class="text-[11px] font-semibold text-rose-700 flex items-center gap-1">
                            <span>🪵</span> Bengkel Kayu Vokasi YPKABK
                        </p>
                        <h3 class="text-base font-bold text-stone-900 leading-snug">
                            Papan Titian Keseimbangan Kayu (Curved)
                        </h3>
                        <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                            Kayu solid dihaluskan tanpa sudut tajam, finishing waterbased ramah anak untuk stimulasi sensorik vestibular motorik.
                        </p>
                    </div>

                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                            <span class="text-base font-extrabold text-stone-900">Rp 140.000</span>
                        </div>
                        <button type="button" onclick="openOrderModal('Papan Titian Keseimbangan Kayu (Curved)', '140.000')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-700 hover:text-white font-semibold text-xs border border-rose-200 transition-all active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Beli &amp; Bantu</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Product 6 --}}
            <div class="product-card bg-white rounded-3xl border border-stone-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group" data-category="kids">
                <div class="relative aspect-[4/3] bg-gradient-to-br from-amber-50 to-orange-100 overflow-hidden flex items-center justify-center p-6">
                    <div class="w-full h-full rounded-2xl border-2 border-dashed border-amber-300/80 bg-white/80 p-4 flex flex-col items-center justify-center text-center shadow-inner">
                        <div class="grid grid-cols-4 gap-2 mb-2 text-xl font-bold">
                            <span class="text-rose-500">أ</span>
                            <span class="text-teal-600">ب</span>
                            <span class="text-amber-500">ت</span>
                            <span class="text-indigo-500">ث</span>
                            <span class="text-blue-500">1</span>
                            <span class="text-emerald-500">2</span>
                            <span class="text-purple-500">3</span>
                            <span class="text-rose-600">4</span>
                        </div>
                        <span class="text-xs font-bold text-amber-900">Puzzle Kayu Edukatif</span>
                    </div>

                    <div class="absolute top-3 left-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-700 text-white shadow-xs">
                            Donasi Terpilih
                        </span>
                    </div>
                    <div class="absolute bottom-3 right-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-white/90 backdrop-blur-xs text-stone-700 border border-stone-200/70">
                            Kondisi 98%
                        </span>
                    </div>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div class="space-y-1.5">
                        <p class="text-[11px] font-semibold text-teal-700 flex items-center gap-1">
                            <span>🧩</span> Diserahkan Ny. Rini dari Sidoarjo
                        </p>
                        <h3 class="text-base font-bold text-stone-900 leading-snug">
                            Set Puzzle Kayu Hijaiyah &amp; Angka
                        </h3>
                        <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">
                            Lengkap tanpa ada potongan yang hilang. Cat warna menarik untuk melatih motorik halus dan pengenalan huruf/angka.
                        </p>
                    </div>

                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase text-stone-400 font-semibold block">Harga Donasi</span>
                            <span class="text-base font-extrabold text-stone-900">Rp 35.000</span>
                        </div>
                        <button type="button" onclick="openOrderModal('Set Puzzle Kayu Hijaiyah & Angka', '35.000')" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-700 hover:text-white font-semibold text-xs border border-rose-200 transition-all active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Beli &amp; Bantu</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        {{-- Catalog Footer CTA Button --}}
        <div class="text-center pt-10">
            <a href="#katalog" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-2xl bg-white border border-stone-300 text-stone-800 font-semibold text-sm hover:border-rose-400 hover:text-rose-700 shadow-xs hover:shadow-md transition-all group">
                <span>Lihat Semua 120+ Produk di Katalog Lengkap</span>
                <svg class="w-4 h-4 text-stone-400 group-hover:text-rose-600 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

    </section>


    {{-- ========================================================
         SECTION: CERITA SENYUM & REKAMAN TRANSPARANSI
         ======================================================== --}}
    <section id="transparansi" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-24">
        
        {{-- Section Header --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
            <div>
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-100 text-rose-800 mb-2">
                    Bukti Dampak &amp; Kasih
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-stone-900 tracking-tight">
                    Cerita Senyum Donatur &amp; Rekaman Transparansi
                </h2>
                <p class="text-sm text-stone-500 mt-1">
                    Bukan sekadar transaksi barang, ini adalah rantai kebaikan yang nyata antara donatur, pembeli, dan anak-anak binaan YPKABK.
                </p>
            </div>

            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-xs font-semibold text-rose-700 hover:text-rose-900 transition-colors">
                <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                <span>Video Unboxing Instagram &rarr;</span>
            </a>
        </div>

        {{-- Split Showcase: Video Left & Reviews Right --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Left: Video Feature & Stats --}}
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-stone-200/90 shadow-sm space-y-5">
                
                {{-- Video Player Mockup --}}
                <div class="relative rounded-2xl overflow-hidden aspect-video bg-stone-900 group">
                    <img src="{{ asset('images/hero-workshop.jpg') }}" 
                         alt="Unboxing Video YPKABK" 
                         class="w-full h-full object-cover opacity-80 group-hover:opacity-90 transition-opacity">

                    {{-- Dark Overlay Gradient --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-900/40 to-transparent"></div>

                    {{-- Center Play Button --}}
                    <div class="absolute inset-0 flex items-center justify-center">
                        <button type="button" onclick="alert('Memutar rekaman video unboxing dan penimbangan donasi #1207.')" class="w-16 h-16 rounded-full bg-rose-600/90 hover:bg-rose-600 text-white flex items-center justify-center shadow-xl shadow-rose-950/40 hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 translate-x-0.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Video Top Tags --}}
                    <div class="absolute top-4 left-4 flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-500 text-stone-950 uppercase tracking-wide">
                            Unboxing Paket #1207
                        </span>
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-black/60 backdrop-blur-xs text-white">
                            Donasi dari Amanda di Bandung
                        </span>
                    </div>

                    {{-- Video Bottom Quote --}}
                    <div class="absolute bottom-4 left-4 right-4">
                        <p class="text-sm font-semibold text-white leading-snug drop-shadow-sm">
                            &ldquo;Terima kasih banyak, paket mainan sensory play dan bajunya sudah sampai di Surabaya!&rdquo;
                        </p>
                    </div>
                </div>

                {{-- 3 Micro Stats in Box --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-stone-50 rounded-2xl p-3.5 text-center border border-stone-200/60">
                        <div class="text-lg font-extrabold text-stone-900">3 Kardus</div>
                        <div class="text-[11px] text-stone-500 font-medium">Pakaian &amp; Edukasi</div>
                    </div>
                    <div class="bg-stone-50 rounded-2xl p-3.5 text-center border border-stone-200/60">
                        <div class="text-lg font-extrabold text-stone-900">24 Jam</div>
                        <div class="text-[11px] text-stone-500 font-medium">Konfirmasi ke Donatur</div>
                    </div>
                    <div class="bg-stone-50 rounded-2xl p-3.5 text-center border border-stone-200/60">
                        <div class="text-lg font-extrabold text-stone-900">100%</div>
                        <div class="text-[11px] text-stone-500 font-medium">Lolos Kurasi Kios</div>
                    </div>
                </div>

                {{-- Card Meta Footer --}}
                <div class="flex items-center justify-between text-xs text-stone-500 pt-1">
                    <span>Diunggah pada: 24 Oktober 2026</span>
                    <a href="#transparansi" class="font-semibold text-rose-700 hover:text-rose-900 transition-colors">
                        Buka Laporan Lengkap Publik &rarr;
                    </a>
                </div>

            </div>

            {{-- Right: Testimonials Column --}}
            <div class="lg:col-span-5 space-y-4">
                
                {{-- Review 1 --}}
                <div class="bg-white rounded-3xl p-5 border border-stone-200/90 shadow-xs space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-xs shrink-0">
                                NN
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-stone-900 leading-tight">Nanie Kurniawati</h4>
                                <p class="text-[11px] text-stone-500">Donatur Preloved • Jakarta Timur</p>
                            </div>
                        </div>
                        <div class="text-amber-400 text-xs tracking-wider">
                            ★★★★★
                        </div>
                    </div>
                    <p class="text-xs text-stone-600 leading-relaxed italic">
                        &ldquo;Sempat bingung pakaian anak menumpuk mau dikemanakan, di Kios Mandiri YPKABK prosesnya gampang banget, laporannya cepat, dan senang sekali melihat video unboxing-nya anak-anak tersenyum bahagia!&rdquo;
                    </p>
                    <div class="pt-1">
                        <span class="text-[11px] font-semibold text-rose-700 bg-rose-50 px-2.5 py-1 rounded-lg">
                            Mendonasikan 18 potong pakaian &amp; buku
                        </span>
                    </div>
                </div>

                {{-- Review 2 --}}
                <div class="bg-white rounded-3xl p-5 border border-stone-200/90 shadow-xs space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-sky-100 text-sky-800 font-bold flex items-center justify-center text-xs shrink-0">
                                DP
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-stone-900 leading-tight">Dr. Dimas Prasetyo</h4>
                                <p class="text-[11px] text-stone-500">Pembeli &amp; Orang Tua Asuh • Surabaya</p>
                            </div>
                        </div>
                        <div class="text-amber-400 text-xs tracking-wider">
                            ★★★★★
                        </div>
                    </div>
                    <p class="text-xs text-stone-600 leading-relaxed italic">
                        &ldquo;Saya beli papan titian dan tote bag rajut DB Bina. Kualitasnya sangat rapi, packing ramah lingkungan, dan yang terpenting menyokong anak-anak istimewa jadi lebih mandiri.&rdquo;
                    </p>
                    <div class="pt-1">
                        <span class="text-[11px] font-semibold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-lg">
                            Membeli produk karya anak istimewa
                        </span>
                    </div>
                </div>

                {{-- Guarantee Card --}}
                <div class="rounded-3xl p-4 bg-teal-50/80 border border-teal-200 flex items-start gap-3">
                    <span class="w-7 h-7 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center shrink-0 text-sm">
                        🛡️
                    </span>
                    <p class="text-xs text-teal-950 font-medium leading-relaxed">
                        Semua donatur berhak meminta rekaman penimbangan, pencatatan etalase serta transparansi alokasi laba ke kas rehabilitasi anak binaan kami.
                    </p>
                </div>

            </div>

        </div>

    </section>


    {{-- ========================================================
         SECTION: CTA DONASI (BOTTOM GRADIENT BANNER)
         ======================================================== --}}
    <section id="donasi-sekarang" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-24">
        <div class="rounded-3xl bg-gradient-to-r from-rose-800 via-rose-700 to-pink-700 text-white p-8 sm:p-12 lg:p-14 shadow-2xl shadow-rose-950/20 overflow-hidden relative">
            
            {{-- Background Decorative Circles --}}
            <div class="absolute -right-20 -top-20 w-80 h-80 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-rose-900/40 blur-2xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center relative z-10">
                
                {{-- Left Side: Copy & Direct Action --}}
                <div class="lg:col-span-7 space-y-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-xs text-white">
                        <span>❤️</span> Mari Bersama Menebar Berkah Bersama
                    </span>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-[1.15]">
                        Punya Barang Bagus yang Jarang Terpakai? Donasikan Hari Ini!
                    </h2>

                    <p class="text-rose-100 text-sm sm:text-base leading-relaxed max-w-xl">
                        Pakaian anak, baju santai, buku pelajaran, mainan edukasi, hingga alat musik layak pakai. Kami sambut dengan senyuman dan jadikan jembatan kemandirian anak-anak istimewa binaan YPKABK.
                    </p>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <button type="button" onclick="openDonationModal()" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-white hover:bg-stone-50 text-rose-700 font-bold text-sm shadow-md active:scale-95 transition-all">
                            <span>🎁</span> Mulai Donasi Sekarang
                        </button>

                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20YPKABK,%20saya%20mau%20konsultasi%20donasi%20barang" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-white/15 hover:bg-white/25 text-white font-semibold text-sm border border-white/30 backdrop-blur-xs active:scale-95 transition-all">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                            </svg>
                            <span>Konsultasi Barang via WhatsApp</span>
                        </a>
                    </div>
                </div>

                {{-- Right Side: Glass Checklist Box --}}
                <div class="lg:col-span-5 bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20 text-white space-y-4">
                    <h3 class="text-sm font-bold tracking-wide flex items-center gap-2">
                        <span>📦</span> Barang yang Sangat Dibutuhkan:
                    </h3>

                    <ul class="space-y-3 text-xs leading-relaxed text-rose-50">
                        <li class="flex items-start gap-2.5">
                            <span class="w-4 h-4 rounded-full bg-emerald-400 text-stone-900 font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">✓</span>
                            <span>Pakaian bayi, anak, dan remaja layak pakai tanpa noda atau sobek</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-4 h-4 rounded-full bg-emerald-400 text-stone-900 font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">✓</span>
                            <span>Buku cerita anak, ensiklopedia bergambar, dan komik edukasi</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-4 h-4 rounded-full bg-emerald-400 text-stone-900 font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">✓</span>
                            <span>Mainan edukasi, puzzle kayu, dan balok susun kondisi lengkap</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="w-4 h-4 rounded-full bg-emerald-400 text-stone-900 font-bold flex items-center justify-center text-[10px] shrink-0 mt-0.5">✓</span>
                            <span>Bahan prakarya: kain perca, benang rajut, dan sisa kanvas kosong</span>
                        </li>
                    </ul>

                    <div class="pt-2 border-t border-white/15">
                        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="text-xs font-semibold text-white hover:text-rose-200 transition-colors flex items-center gap-1">
                            Lihat Syarat &amp; Ketentuan Barang Lengkap &rarr;
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>

{{-- Interactive Modal: Form Donasi Barang --}}
<div id="donation-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
        <button type="button" onclick="closeDonationModal()" class="absolute top-5 right-5 text-stone-400 hover:text-stone-700 p-2 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="space-y-1">
            <span class="text-xs font-bold text-rose-700 uppercase tracking-wider bg-rose-50 px-2.5 py-1 rounded-full">
                Form Donasi Preloved
            </span>
            <h3 class="text-xl font-bold text-stone-900">Donasikan Barang untuk Kios Mandiri</h3>
            <p class="text-xs text-stone-500">Tim kami akan menghubungi Anda untuk konfirmasi jadwal pickup / pengiriman pos.</p>
        </div>

        <form onsubmit="handleDonationSubmit(event)" class="space-y-4 text-xs font-medium text-stone-700">
            <div>
                <label class="block mb-1 text-stone-800">Nama Lengkap Donatur *</label>
                <input type="text" required placeholder="Contoh: Siti Rahmawati" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-rose-500">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block mb-1 text-stone-800">No. WhatsApp *</label>
                    <input type="tel" required placeholder="0812xxxxxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
                <div>
                    <label class="block mb-1 text-stone-800">Kota Asal *</label>
                    <input type="text" required placeholder="Solo / Surabaya / Jkt" class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-rose-500">
                </div>
            </div>
            <div>
                <label class="block mb-1 text-stone-800">Kategori Barang Donasi *</label>
                <select class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-rose-500 bg-white">
                    <option>Pakaian Anak / Dewasa Layak Pakai</option>
                    <option>Buku Bacaan / Ensiklopedia</option>
                    <option>Mainan Edukatif / Puzzle</option>
                    <option>Bahan Prakarya (Kain / Benang / Kanvas)</option>
                    <option>Peralatan Terapi / Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block mb-1 text-stone-800">Perkiraan Jumlah / Deskripsi Singkat</label>
                <textarea rows="2" placeholder="Contoh: 2 kardus pakaian anak usia 3-6 tahun, kondisi bersih dan layak." class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
            </div>
            <button type="submit" class="w-full py-3.5 rounded-xl bg-rose-700 hover:bg-rose-800 text-white font-bold text-sm shadow-md transition-all">
                Kirim Formulir Donasi
            </button>
        </form>
    </div>
</div>

{{-- Interactive Modal: Pembelian / Beli & Bantu --}}
<div id="order-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl relative animate-in fade-in zoom-in-95 duration-200">
        <button type="button" onclick="closeOrderModal()" class="absolute top-5 right-5 text-stone-400 hover:text-stone-700 p-2 rounded-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="space-y-1">
            <span class="text-xs font-bold text-rose-700 uppercase tracking-wider bg-rose-50 px-2.5 py-1 rounded-full">
                Beli &amp; Bantu
            </span>
            <h3 class="text-lg font-bold text-stone-900" id="modal-product-title">Produk Pilihan</h3>
            <p class="text-xs text-stone-500">Harga Donasi: <span id="modal-product-price" class="font-bold text-rose-700">Rp 0</span></p>
        </div>

        <p class="text-xs text-stone-600 bg-amber-50 p-3 rounded-xl border border-amber-200">
            🌱 100% dana dari pembelian ini langsung dialokasikan ke pos kas pelatihan &amp; rehabilitasi anak istimewa YPKABK.
        </p>

        <div class="space-y-3 pt-2">
            <a id="modal-wa-btn" href="#" target="_blank" rel="noopener noreferrer" class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm transition-all">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                </svg>
                <span>Pesan Sekarang via WhatsApp Kios</span>
            </a>
            <button type="button" onclick="closeOrderModal()" class="w-full py-2.5 text-center text-xs font-semibold text-stone-500 hover:text-stone-800">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- Inline Vanilla JS for Tabs & Modals --}}
<script>
    // Tab filtering
    document.addEventListener('DOMContentLoaded', () => {
        const filterBtns = document.querySelectorAll('.category-btn');
        const productCards = document.querySelectorAll('.product-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active button styles
                filterBtns.forEach(b => {
                    b.classList.remove('bg-rose-700', 'text-white', 'shadow-xs');
                    b.classList.add('bg-white', 'border', 'border-stone-200', 'text-stone-600');
                });
                btn.classList.remove('bg-white', 'border', 'border-stone-200', 'text-stone-600');
                btn.classList.add('bg-rose-700', 'text-white', 'shadow-xs');

                const filter = btn.getAttribute('data-filter');
                productCards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-category') === filter) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });

    // Donation Modal
    function openDonationModal() {
        document.getElementById('donation-modal').classList.remove('hidden');
    }
    function closeDonationModal() {
        document.getElementById('donation-modal').classList.add('hidden');
    }
    function handleDonationSubmit(e) {
        e.preventDefault();
        alert('Terima kasih banyak! Tim Kios Mandiri YPKABK akan segera menghubungi nomor WhatsApp Anda untuk koordinasi donasi.');
        closeDonationModal();
    }

    // Order Modal
    function openOrderModal(title, price) {
        document.getElementById('modal-product-title').innerText = title;
        document.getElementById('modal-product-price').innerText = 'Rp ' + price;
        const waText = encodeURIComponent(`Halo Admin Kios Mandiri YPKABK, saya ingin memesan "${title}" seharga Rp ${price}. Apakah barang masih tersedia?`);
        document.getElementById('modal-wa-btn').href = `https://wa.me/6281234567890?text=${waText}`;
        document.getElementById('order-modal').classList.remove('hidden');
    }
    function closeOrderModal() {
        document.getElementById('order-modal').classList.add('hidden');
    }
</script>
@endsection
