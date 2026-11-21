@extends('admin.shared.Layout')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
@php
    $imagesList = $product->images;
    $allImages = [];

    foreach ($imagesList as $img) {
        if (!empty($img->image)) {
            $src = (str_starts_with($img->image, 'data:image') || str_starts_with($img->image, 'http'))
                ? $img->image
                : asset($img->image);
            $allImages[] = [
                'id' => $img->id,
                'src' => $src,
            ];
        }
    }

    if (empty($allImages) && !empty($product->image)) {
        $src = (str_starts_with($product->image, 'data:image') || str_starts_with($product->image, 'http'))
            ? $product->image
            : asset($product->image);
        $allImages[] = [
            'id' => null,
            'src' => $src,
        ];
    }

    $mainImage = !empty($allImages) ? $allImages[0]['src'] : null;
@endphp

<div class="space-y-6">

    {{-- Header & Breadcrumb --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.products.index') }}" class="hover:text-primary transition-colors">Produk</a>
                <span>/</span>
                <span class="text-gray-800 font-semibold truncate max-w-[200px]">{{ $product->name }}</span>
            </div>
            <h2 class="text-xl font-bold text-gray-900">{{ $product->name }}</h2>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto">
            <a href="{{ route('admin.products.index') }}"
                class="inline-flex items-center px-3.5 py-2 rounded-lg border border-gray-200 bg-white text-gray-700 text-xs font-semibold hover:bg-gray-50 transition">
                &larr; Kembali
            </a>
            <a href="{{ route('products.show', $product) }}" target="_blank"
                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-gray-200 bg-white text-primary text-xs font-semibold hover:bg-pink-50 transition">
                <span>Lihat di Publik</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>
    </div>

    {{-- Grid 2 Kolom: Kiri (Galeri Foto) & Kanan (Informasi & Spesifikasi) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- KOLOM KIRI: GALERI MULTI-FOTO PRODUK (5 Kolom) --}}
        <div class="lg:col-span-5 space-y-5">
            <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900">Galeri Foto Produk</h3>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-pink-50 text-primary border border-primary/20">
                        {{ count($allImages) }} Foto Tersimpan
                    </span>
                </div>

                {{-- Foto Utama Besar --}}
                <div class="w-full aspect-square rounded-xl bg-gray-50 border border-gray-100 overflow-hidden relative shadow-xs flex items-center justify-center">
                    @if($mainImage)
                        <img id="admin-main-img" src="{{ $mainImage }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center text-gray-400 text-xs gap-1">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Belum ada foto</span>
                        </div>
                    @endif
                </div>

                {{-- Grid Semua Foto dengan Aksi Hapus Jelas --}}
                @if(count($allImages) > 0)
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-700">Daftar Foto Produk (Klik untuk pratinjau besar):</p>
                            <span class="text-[11px] text-gray-400">Total: {{ count($allImages) }} foto</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($allImages as $idx => $item)
                                <div class="relative w-full aspect-square rounded-xl border border-gray-200 overflow-hidden bg-white shadow-xs flex flex-col justify-end group">
                                    <img src="{{ $item['src'] }}" alt="Foto {{ $idx + 1 }}"
                                         onclick="document.getElementById('admin-main-img').src = '{{ $item['src'] }}'"
                                         class="absolute inset-0 w-full h-full object-cover cursor-pointer hover:opacity-95 transition">
                                    
                                    {{-- Bar Aksi Hapus Selalu Terlihat --}}
                                    <div class="relative z-10 bg-gradient-to-t from-black/85 via-black/50 to-transparent p-2 flex items-center justify-between">
                                        <span class="text-[11px] text-white font-bold drop-shadow">
                                            #{{ $idx + 1 }} {{ $idx === 0 ? '(Sampul)' : '' }}
                                        </span>
                                        <form method="POST" action="{{ $item['id'] ? route('admin.products.images.destroy', [$product, $item['id']]) : route('admin.products.cover.destroy', $product) }}"
                                              onsubmit="return confirm('Hapus foto ke-{{ $idx + 1 }} dari produk ini?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-2 py-1 rounded bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold shadow-sm transition cursor-pointer"
                                                    title="Hapus foto ini">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Form Unggah Foto Tambahan --}}
                <div class="pt-3 border-t border-gray-100">
                    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        @method('PUT')
                        <label class="block text-xs font-semibold text-gray-700">Tambah Foto ke Galeri</label>
                        <input type="file" name="images[]" multiple accept="image/*" required
                               class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-primary hover:file:bg-pink-100 cursor-pointer">
                        <button type="submit" class="w-full py-2 rounded-lg bg-gray-900 text-white text-xs font-semibold hover:bg-gray-800 transition">
                            + Unggah Foto Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: SPESIFIKASI, METRIK & DESKRIPSI (7 Kolom) --}}
        <div class="lg:col-span-7 space-y-5">

            {{-- Kartu Metrik Penjualan & Stok --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-white p-4 rounded-xl border border-gray-200 shadow-xs">
                <div>
                    <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Harga</span>
                    <span class="text-base sm:text-lg font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Stok Sisa</span>
                    <span class="text-base sm:text-lg font-bold {{ $product->stock <= 0 ? 'text-rose-600' : ($product->stock <= 3 ? 'text-amber-600' : 'text-emerald-700') }}">
                        {{ $product->stock }} pcs
                    </span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Total Terjual</span>
                    <span class="text-base sm:text-lg font-bold text-gray-900">{{ $totalSold ?? 0 }} pcs</span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider block">Total Omset</span>
                    <span class="text-base sm:text-lg font-bold text-primary">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Kartu Detail & Spesifikasi --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4 shadow-xs">
                <h3 class="text-sm font-bold text-gray-900 pb-2 border-b border-gray-100">Informasi &amp; Atribut Produk</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-500 block mb-0.5">Kategori</span>
                        <span class="font-semibold text-gray-800">{{ $product->category?->name ?? 'Tanpa Kategori' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-0.5">Sumber Produk</span>
                        <span class="font-semibold text-gray-800">
                            {{ $product->source === 'karya' ? 'Karya Siswa ABK' : ($product->source === 'preloved' ? 'Preloved Berdaya' : 'Donasi Terpilih') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-0.5">Kondisi Barang</span>
                        <span class="font-semibold text-gray-800">{{ ucfirst($product->condition ?? '—') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-0.5">Nama Siswa / Pengrajin</span>
                        <span class="font-semibold text-gray-800">{{ $product->artisan_name ?: '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-0.5">Status Tampil</span>
                        <span class="font-semibold {{ $product->is_featured ? 'text-amber-700' : 'text-gray-700' }}">
                            {{ $product->is_featured ? '★ Produk Unggulan Beranda' : 'Katalog Reguler' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-500 block mb-0.5">Status Ketersediaan</span>
                        <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-semibold {{ $product->status === 'ready' ? 'bg-emerald-50 text-emerald-700' : ($product->status === 'minimal_stock' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                            {{ $product->status === 'ready' ? 'Ready' : ($product->status === 'minimal_stock' ? 'Stok Minim' : 'Habis') }}
                        </span>
                    </div>
                </div>

                {{-- Deskripsi Lengkap --}}
                <div class="pt-3 border-t border-gray-100 space-y-1.5">
                    <span class="text-xs font-semibold text-gray-700 block">Deskripsi Lengkap</span>
                    <div class="p-3.5 rounded-lg bg-gray-50 text-xs text-gray-700 leading-relaxed whitespace-pre-line border border-gray-100">
                        {{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}
                    </div>
                </div>
            </div>

            {{-- Riwayat Pembelian Produk Ini --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
                <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900">Riwayat Pesanan Produk Ini</h3>
                    <span class="text-xs text-gray-500">{{ $product->detailPurchases->count() }} transaksi</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-100">
                        <thead class="bg-gray-50 text-gray-500 uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-4 py-3">Order Code</th>
                                <th class="px-4 py-3">Pembeli</th>
                                <th class="px-4 py-3 text-center">Jumlah</th>
                                <th class="px-4 py-3">Total Subtotal</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 font-medium">
                            @forelse($product->detailPurchases as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-mono font-semibold text-primary">
                                        {{ $item->purchase?->order_code ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-gray-900">{{ $item->purchase?->customer_name ?? '-' }}</div>
                                        <div class="text-[11px] text-gray-400">{{ $item->purchase?->customer_phone ?? '' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center font-bold">
                                        {{ $item->quantity }} pcs
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900">
                                        Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $item->purchase?->status === 'verified' ? 'bg-emerald-50 text-emerald-700' : ($item->purchase?->status === 'cancelled' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">
                                            {{ ucfirst($item->purchase?->status ?? 'pending') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-400 text-xs">
                                        Belum ada riwayat pesanan untuk produk ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
