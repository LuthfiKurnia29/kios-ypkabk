@extends('user.shared.Layout')

@section('title', $product->name . ' - Detail Produk')

@section('content')
@php
    $imagesList = $product->images;
    $allImages = [];

    foreach ($imagesList as $img) {
        if (!empty($img->image)) {
            $src = (str_starts_with($img->image, 'data:image') || str_starts_with($img->image, 'http'))
                ? $img->image
                : asset($img->image);
            $allImages[] = $src;
        }
    }

    // Fallback bila product_images masih kosong namun $product->image ada
    if (empty($allImages) && !empty($product->image)) {
        $src = (str_starts_with($product->image, 'data:image') || str_starts_with($product->image, 'http'))
            ? $product->image
            : asset($product->image);
        $allImages[] = $src;
    }

    $mainImage = !empty($allImages) ? $allImages[0] : null;
    $isOutOfStock = ($product->stock <= 0) || ($product->status === 'empty');
@endphp

<div class="w-full bg-[#FFFBF7] min-h-screen py-8 lg:py-12">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6">

        {{-- 1. BREADCRUMB --}}
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-[#2B2430]/60 mb-6 font-medium">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span>/</span>
            <a href="{{ route('katalog') }}" class="hover:text-primary transition-colors">Katalog</a>
            <span>/</span>
            <span class="text-[#2B2430] font-semibold truncate max-w-[240px] sm:max-w-md">{{ $product->name }}</span>
        </nav>

        {{-- 2. KONTEN UTAMA: GALERI FOTO & INFO PRODUK --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start bg-white p-6 sm:p-8 rounded-[16px] border border-[#2B2430]/10 shadow-xs">

            {{-- KOLOM KIRI: GALERI MULTI-FOTO (5 Kolom) --}}
            <div class="lg:col-span-5 space-y-4">
                {{-- Foto Utama Besar --}}
                <div class="w-full aspect-[4/5] sm:aspect-square rounded-[14px] bg-[#FDEEF4] border border-[#2B2430]/10 overflow-hidden relative shadow-xs flex items-center justify-center">
                    @if($mainImage)
                        <img id="main-product-image"
                             src="{{ $mainImage }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-opacity duration-200 {{ $isOutOfStock ? 'grayscale-[40%]' : '' }}">
                    @else
                        <div class="flex flex-col items-center justify-center p-6 text-center text-[#2B2430]/40 space-y-2">
                            <svg class="w-12 h-12 text-[#2B2430]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs font-medium">Foto belum tersedia</span>
                        </div>
                    @endif

                    {{-- Badge Status Stok Habis pada Foto --}}
                    @if($isOutOfStock)
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center p-3 pointer-events-none">
                            <span class="px-3 py-1.5 rounded-lg bg-stone-900/85 text-white text-xs font-bold tracking-wider uppercase">
                                Stok Habis
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Thumbnail Strip (Jika Foto Lebih dari 1) --}}
                @if(count($allImages) > 1)
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-[#2B2430]/70">Semua Foto ({{ count($allImages) }})</p>
                            <span class="text-[11px] text-[#2B2430]/50">Klik foto untuk memperbesar</span>
                        </div>
                        <div class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-thin">
                            @foreach($allImages as $idx => $imgUrl)
                                <button type="button"
                                        onclick="switchMainImage(this, '{{ $imgUrl }}')"
                                        class="product-thumb-btn w-16 h-16 sm:w-20 sm:h-20 rounded-[10px] border-2 {{ $idx === 0 ? 'border-primary ring-2 ring-primary/30' : 'border-[#2B2430]/10 hover:border-primary/50' }} overflow-hidden shrink-0 transition-all focus:outline-none relative bg-white"
                                        aria-label="Tampilkan foto ke-{{ $idx + 1 }}">
                                    <img src="{{ $imgUrl }}" alt="{{ $product->name }} {{ $idx + 1 }}" class="w-full h-full object-cover">
                                    <span class="absolute bottom-0 inset-x-0 bg-black/50 text-[9px] text-white text-center font-bold py-0.5">
                                        #{{ $idx + 1 }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- KOLOM KANAN: DETAIL INFORMASI & TOMBOL PESAN (7 Kolom) --}}
            <div class="lg:col-span-7 flex flex-col space-y-6">

                {{-- Badges Kategori, Sumber, & Kondisi --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if($product->source === 'karya')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#FDEEF4] text-primary border border-primary/20">
                            <span>🎨</span> Karya Siswa ABK
                        </span>
                    @elseif($product->source === 'preloved')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-stone-100 text-[#2B2430] border border-stone-200">
                            <span>📦</span> Preloved Berdaya
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#FDEEF4] text-primary border border-primary/20">
                            <span>✨</span> Donasi Terpilih
                        </span>
                    @endif

                    @if($product->category)
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-stone-50 text-[#2B2430]/80 border border-stone-200">
                            {{ $product->category->name }}
                        </span>
                    @endif

                    @if($product->condition)
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $product->condition === 'baru' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                            Kondisi: {{ ucfirst($product->condition) }}
                        </span>
                    @endif
                </div>

                {{-- Judul Produk & Pengrajin --}}
                <div class="space-y-2">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#2B2430] leading-tight">
                        {{ $product->name }}
                    </h1>

                    @if($product->artisan_name)
                        <div class="flex items-center gap-2 text-sm text-[#2B2430]/75">
                            <span class="font-medium">Dibuat dengan cinta oleh:</span>
                            <span class="font-semibold text-[#2B2430] bg-[#FDEEF4] px-2.5 py-0.5 rounded-md border border-primary/10">
                                {{ $product->artisan_name }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Harga & Ketersediaan Stok --}}
                <div class="p-4 rounded-[12px] bg-stone-50 border border-[#2B2430]/10 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <span class="text-xs text-[#2B2430]/60 block font-medium">Harga Penjualan</span>
                        <div class="text-2xl sm:text-3xl font-extrabold text-primary">
                            Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- Status Stok --}}
                    <div class="text-right">
                        <span class="text-xs text-[#2B2430]/60 block font-medium">Ketersediaan</span>
                        @if($isOutOfStock)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                Stok Habis
                            </span>
                        @elseif($product->stock <= 3)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                Tersisa {{ $product->stock }} buah
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Tersedia ({{ $product->stock }} unit)
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Deskripsi Produk --}}
                <div class="space-y-2 pt-2 border-t border-[#2B2430]/10">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#2B2430]/70">Deskripsi Produk</h2>
                    <div class="text-sm text-[#2B2430]/85 leading-relaxed font-normal whitespace-pre-line">
                        {{ $product->description ?: 'Belum ada deskripsi mendetail untuk produk ini. Setiap pembelian Anda secara langsung mendukung kemandirian anak-anak istimewa binaan Yayasan Pendidikan Anak Buta & Tuli (YPKABK).' }}
                    </div>
                </div>

                {{-- Dampak Sosial Box --}}
                <div class="p-3.5 rounded-[12px] bg-[#FDEEF4]/60 border border-primary/15 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center shrink-0 font-bold text-sm">
                        ♥
                    </div>
                    <div class="text-xs text-[#2B2430]/80 leading-relaxed">
                        <strong class="text-primary font-bold">100% Manfaat Nyata:</strong> Setiap rupiah dari pembelian ini dialokasikan langsung untuk pembiayaan alat kerja, pelatihan vokasi terarah, dan program kemandirian anak-anak istimewa di YPKABK.
                    </div>
                </div>

                {{-- Tombol Aksi Utama --}}
                <div class="pt-3 space-y-2.5">
                    <div class="flex flex-col sm:flex-row gap-3">
                        @if($isOutOfStock)
                            <button type="button" disabled
                                    class="flex-1 h-12 rounded-[10px] bg-stone-200 text-stone-500 font-bold text-sm flex items-center justify-center cursor-not-allowed border border-stone-300">
                                Stok Ready Habis
                            </button>
                        @else
                            <button type="button"
                                    onclick="openOrderModal('{{ addslashes($product->name) }}', '{{ number_format((float) $product->price, 0, ',', '.') }}', {{ $product->id }})"
                                    class="flex-1 h-12 rounded-[10px] bg-primary hover:bg-primary/90 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98]">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>Beli Ready Stock</span>
                            </button>
                        @endif

                        <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin Kios YPKABK, saya ingin bertanya tentang produk: ' . $product->name . ' (' . route('products.show', $product->id) . ')') }}"
                           target="_blank"
                           class="px-5 h-12 rounded-[10px] border border-[#2B2430]/20 bg-white hover:bg-stone-50 text-[#2B2430] font-semibold text-sm flex items-center justify-center gap-2 transition-colors">
                            <span>💬 Tanya via WA</span>
                        </a>
                    </div>

                    {{-- Opsi Print on Demand Kustom --}}
                    <button type="button"
                            onclick="openOnDemandModal('{{ addslashes($product->name) }}', '{{ number_format((float) $product->price, 0, ',', '.') }}', {{ $product->id }})"
                            class="w-full h-11 rounded-[10px] bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all active:scale-[0.98] cursor-pointer">
                        <span>🎨 Pesan Cetak Kustom (Print on Demand) &rarr;</span>
                    </button>
                </div>

            </div>
        </div>

        {{-- 3. PRODUK TERKAIT LAINNYA --}}
        @if(isset($relatedProducts) && count($relatedProducts) > 0)
            <div class="mt-12 lg:mt-16 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-[#2B2430]">Produk Pilihan Lainnya</h2>
                        <p class="text-xs sm:text-sm text-[#2B2430]/65 mt-0.5">Dukung karya dan donasi lainnya dari sentra kemandirian kami.</p>
                    </div>
                    <a href="{{ route('katalog') }}" class="text-xs sm:text-sm font-semibold text-primary hover:underline flex items-center gap-1">
                        <span>Lihat Semua Katalog</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($relatedProducts as $related)
                        <x-product-card :product="$related" button-label="Pilih Produk" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    function switchMainImage(button, src) {
        const mainImg = document.getElementById('main-product-image');
        if (!mainImg) return;
        
        mainImg.style.opacity = '0.3';
        setTimeout(() => {
            mainImg.src = src;
            mainImg.style.opacity = '1';
        }, 120);

        document.querySelectorAll('.product-thumb-btn').forEach(btn => {
            btn.classList.remove('border-primary', 'ring-2', 'ring-primary/30');
            btn.classList.add('border-[#2B2430]/10');
        });

        button.classList.add('border-primary', 'ring-2', 'ring-primary/30');
        button.classList.remove('border-[#2B2430]/10');
    }
</script>

{{-- Modal & Skrip Order --}}
@include('user.pages.home._modal-order')
@include('user.pages.home._scripts')
@endsection
