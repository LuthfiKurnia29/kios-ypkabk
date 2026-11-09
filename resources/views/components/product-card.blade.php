@props([
    'product',
    'buttonLabel' => 'Pilih Produk',
])

@php
    $name = is_array($product) ? ($product['name'] ?? $product['title'] ?? '') : ($product->name ?? '');
    $price = is_array($product) ? ($product['price'] ?? 0) : ($product->price ?? 0);
    $id = is_array($product) ? ($product['id'] ?? null) : ($product->id ?? null);
    $image = is_array($product) ? ($product['image'] ?? null) : ($product->image ?? null);
    $source = is_array($product) ? ($product['source'] ?? null) : ($product->source ?? null);
    $artisan = is_array($product) ? ($product['artisan_name'] ?? null) : ($product->artisan_name ?? null);
    $desc = is_array($product) ? ($product['description'] ?? null) : ($product->description ?? null);
    $stock = is_array($product) ? ($product['stock'] ?? 1) : ($product->stock ?? 1);
    $status = is_array($product) ? ($product['status'] ?? 'ready') : ($product->status ?? 'ready');

    $isOutOfStock = ($stock <= 0) || ($status === 'empty');
    $isDataUri = !empty($image) && (str_starts_with($image, 'data:image') || str_starts_with($image, 'http'));
    $hasValidImage = !empty($image) && ($isDataUri || file_exists(public_path($image)));
    $imageSrc = $isDataUri ? $image : asset($image);
@endphp

<div {{ $attributes->merge(['class' => 'rounded-[12px] p-4 border flex flex-col justify-between space-y-3 transition-shadow ' . ($isOutOfStock ? 'bg-stone-50 border-stone-200 opacity-75' : 'bg-white border-[#2B2430]/10 hover:shadow-sm')]) }}>
    {{-- Foto Produk (Rasio 4:5) --}}
    <div class="w-full aspect-[4/5] rounded-[8px] overflow-hidden bg-[#FDEEF4] relative">
        @if($hasValidImage)
            <img src="{{ $imageSrc }}"
                 alt="{{ $name }}"
                 class="w-full h-full object-cover rounded-[8px] {{ $isOutOfStock ? 'grayscale-[50%]' : '' }}"
                 loading="lazy">
        @else
            <div class="w-full h-full bg-[#FDEEF4]"></div>
        @endif

        {{-- Badge Stok Habis --}}
        @if($isOutOfStock)
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center p-2">
                <span class="px-2.5 py-1 rounded bg-stone-900/80 text-white text-[11px] font-bold tracking-wider uppercase">
                    Stok Habis
                </span>
            </div>
        @endif
    </div>

    {{-- Metadata & Kategori --}}
    <div class="space-y-2 flex-grow flex flex-col">
        <div class="flex items-center justify-between gap-2">
            @if($source === 'karya')
                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-[#FDEEF4] text-primary border border-primary/20">
                    Karya ABK
                </span>
            @elseif($source === 'preloved')
                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-stone-100 text-[#2B2430] border border-stone-200">
                    Preloved
                </span>
            @else
                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-semibold bg-[#FDEEF4] text-primary border border-primary/20">
                    Donasi Terpilih
                </span>
            @endif

            @if($artisan)
                <span class="text-[11px] text-[#2B2430]/60 truncate font-medium">
                    {{ $artisan }}
                </span>
            @endif
        </div>

        {{-- Harga Teks Biasa (Tanpa Label Bundar Kuning) --}}
        <div class="text-base font-bold {{ $isOutOfStock ? 'text-stone-500' : 'text-primary' }}">
            Rp {{ number_format((float) $price, 0, ',', '.') }}
        </div>

        {{-- Nama Produk --}}
        <h3 class="font-semibold text-sm sm:text-base text-[#2B2430] leading-snug line-clamp-1" title="{{ $name }}">
            {{ $name }}
        </h3>

        {{-- Deskripsi Singkat --}}
        @if($desc)
            <p class="text-xs text-[#2B2430]/70 font-normal line-clamp-2 leading-relaxed">
                {{ $desc }}
            </p>
        @endif
    </div>

    {{-- Tombol Aksi: Outline Primary atau Nonaktif bila Stok Habis --}}
    <div class="pt-2">
        @if($isOutOfStock)
            <button type="button"
                    disabled
                    class="w-full h-10 flex items-center justify-center rounded-[8px] border border-stone-300 bg-stone-100 text-stone-400 font-semibold text-sm cursor-not-allowed">
                Stok Habis
            </button>
        @else
            <button type="button"
                    onclick="openOrderModal('{{ addslashes($name) }}', '{{ number_format((float) $price, 0, ',', '.') }}', {{ $id !== null ? $id : 'null' }})"
                    class="w-full h-10 flex items-center justify-center rounded-[8px] border border-primary text-primary hover:bg-primary hover:text-white font-semibold text-sm transition-all active:scale-[0.98]">
                {{ $buttonLabel ?? 'Pilih Produk' }}
            </button>
        @endif
    </div>
</div>
