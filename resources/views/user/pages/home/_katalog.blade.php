{{-- ========================================================
     SECTION: PRODUK PILIHAN (4 PRODUK) - FR-01
     id="produk-pilihan", latar putih, py-16 lg:py-24.
     Judul dan satu kalimat, grid 4 produk (4 kolom desktop, 2 kolom HP).
     ======================================================== --}}
<section id="produk-pilihan" class="bg-white py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        {{-- Judul dan Satu Kalimat --}}
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-ink tracking-tight leading-tight">
                Karya Anak Istimewa &amp; Donasi Preloved Terpilih
            </h2>
            <p class="text-ink/75 text-base sm:text-lg leading-relaxed">
                Setiap pembelian langsung mendanai sesi rehabilitasi dan membuka masa depan mandiri bagi anak-anak berkebutuhan khusus.
            </p>
        </div>

        {{-- Grid 4 Produk (4 Kolom Desktop, 2 Kolom HP) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                @foreach($featuredProducts as $product)
                    <div class="bg-cream rounded-2xl p-4 flex flex-col justify-between shadow-sm space-y-4">
                        <div class="space-y-3">
                            <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-butter">
                                <img src="{{ asset($product->image ?: 'images/tote-bag.jpg') }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover rounded-2xl"
                                     loading="lazy">
                            </div>
                            <div class="space-y-1">
                                <h3 class="font-heading text-base sm:text-lg font-bold text-ink leading-snug line-clamp-1" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h3>
                                <p class="text-xs sm:text-sm text-ink/70 line-clamp-1">
                                    {{ $product->artisan_name ?: ($product->source === 'karya' ? 'Kriya Siswa ABK' : 'Barang Donasi Terpilih') }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-3 pt-2 border-t border-ink/10">
                            <div>
                                <span class="text-[10px] uppercase text-ink/60 font-semibold block">Harga Donasi</span>
                                <span class="text-base sm:text-lg font-extrabold text-ink">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button"
                                        onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ (int)$product->price }}, '{{ asset($product->image ?: 'images/tote-bag.jpg') }}', '{{ addslashes($product->category?->name ?? 'Karya ABK') }}')"
                                        class="min-h-[48px] rounded-full border border-ink/15 hover:bg-butter text-ink font-bold text-xs flex items-center justify-center gap-1 transition-all active:scale-95"
                                        title="Tambah ke keranjang">
                                    <svg class="w-4 h-4 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span>+ Keranjang</span>
                                </button>
                                <button type="button"
                                        onclick="openOrderModal('{{ addslashes($product->name) }}', '{{ number_format($product->price, 0, ',', '.') }}', {{ $product->id }})"
                                        class="min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink font-bold text-xs flex items-center justify-center gap-1 transition-all active:scale-95 shadow-sm">
                                    <span>⚡ Beli</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Fallback jika database belum di-seed --}}
                <div class="bg-cream rounded-2xl p-4 flex flex-col justify-between shadow-sm space-y-4">
                    <div class="space-y-3">
                        <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-butter">
                            <img src="{{ asset('images/tote-bag.jpg') }}"
                                 alt="Tote Bag Lukis Pelangi Bahagia"
                                 class="w-full h-full object-cover rounded-2xl"
                                 loading="lazy">
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-heading text-base sm:text-lg font-bold text-ink leading-snug">
                                Tote Bag Lukis Pelangi Bahagia
                            </h3>
                            <p class="text-xs sm:text-sm text-ink/70">
                                Kriya Kanvas ABK
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2 border-t border-ink/10">
                        <div>
                            <span class="text-[10px] uppercase text-ink/60 font-semibold block">Harga Donasi</span>
                            <span class="text-base sm:text-lg font-extrabold text-ink">Rp 45.000</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button"
                                    onclick="addToCart(1, 'Tote Bag Lukis Pelangi Bahagia', 45000, '{{ asset('images/tote-bag.jpg') }}', 'Kriya Kanvas ABK')"
                                    class="min-h-[48px] rounded-full border border-ink/15 hover:bg-butter text-ink font-bold text-xs flex items-center justify-center gap-1 transition-all active:scale-95"
                                    title="Tambah ke keranjang">
                                <svg class="w-4 h-4 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>+ Keranjang</span>
                            </button>
                            <button type="button"
                                    onclick="openOrderModal('Tote Bag Lukis Pelangi Bahagia', '45.000', 1)"
                                    class="min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink font-bold text-xs flex items-center justify-center gap-1 transition-all active:scale-95 shadow-sm">
                                <span>⚡ Beli</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-cream rounded-2xl p-4 flex flex-col justify-between shadow-sm space-y-4">
                    <div class="space-y-3">
                        <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-butter">
                            <img src="{{ asset('images/crochet-bunny.jpg') }}"
                                 alt="Boneka Rajut Amigurumi Kelinci"
                                 class="w-full h-full object-cover rounded-2xl"
                                 loading="lazy">
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-heading text-base sm:text-lg font-bold text-ink leading-snug">
                                Boneka Rajut Amigurumi Kelinci
                            </h3>
                            <p class="text-xs sm:text-sm text-ink/70">
                                Kelas Rajut Vokasi
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2 border-t border-ink/10">
                        <div>
                            <span class="text-[10px] uppercase text-ink/60 font-semibold block">Harga Donasi</span>
                            <span class="text-base sm:text-lg font-extrabold text-ink">Rp 65.000</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button"
                                    onclick="addToCart(2, 'Boneka Rajut Amigurumi Kelinci', 65000, '{{ asset('images/crochet-bunny.jpg') }}', 'Kelas Rajut Vokasi')"
                                    class="min-h-[48px] rounded-full border border-ink/15 hover:bg-butter text-ink font-bold text-xs flex items-center justify-center gap-1 transition-all active:scale-95"
                                    title="Tambah ke keranjang">
                                <svg class="w-4 h-4 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>+ Keranjang</span>
                            </button>
                            <button type="button"
                                    onclick="openOrderModal('Boneka Rajut Amigurumi Kelinci', '65.000', 2)"
                                    class="min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink font-bold text-xs flex items-center justify-center gap-1 transition-all active:scale-95 shadow-sm">
                                <span>⚡ Beli</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-cream rounded-2xl p-4 flex flex-col justify-between shadow-sm space-y-4">
                    <div class="space-y-3">
                        <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-butter">
                            <img src="{{ asset('images/books.jpg') }}"
                                 alt="Set Ensiklopedia Anak (5 Jilid)"
                                 class="w-full h-full object-cover rounded-2xl"
                                 loading="lazy">
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-heading text-base sm:text-lg font-bold text-ink leading-snug">
                                Set Ensiklopedia Anak (5 Jilid)
                            </h3>
                            <p class="text-xs sm:text-sm text-ink/70">
                                Buku Preloved Istimewa
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2 border-t border-ink/10">
                        <div>
                            <span class="text-[10px] uppercase text-ink/60 font-semibold block">Harga Donasi</span>
                            <span class="text-base sm:text-lg font-extrabold text-ink">Rp 95.000</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button"
                                    onclick="addToCart(3, 'Set Ensiklopedia Anak (5 Jilid)', 95000, '{{ asset('images/books.jpg') }}', 'Buku Preloved Istimewa')"
                                    class="min-h-[48px] rounded-full border border-ink/15 hover:bg-butter text-ink font-bold text-xs flex items-center justify-center gap-1 transition-all active:scale-95"
                                    title="Tambah ke keranjang">
                                <svg class="w-4 h-4 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>+ Keranjang</span>
                            </button>
                            <button type="button"
                                    onclick="openOrderModal('Set Ensiklopedia Anak (5 Jilid)', '95.000', 3)"
                                    class="min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink font-bold text-xs flex items-center justify-center gap-1 transition-all active:scale-95 shadow-sm">
                                <span>⚡ Beli</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-cream rounded-2xl p-4 flex flex-col justify-between shadow-sm space-y-4">
                    <div class="space-y-3">
                        <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-butter">
                            <img src="{{ asset('images/denim-jacket.jpg') }}"
                                 alt="Jaket Denim Anak Dinosaurus"
                                 class="w-full h-full object-cover rounded-2xl"
                                 loading="lazy">
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-heading text-base sm:text-lg font-bold text-ink leading-snug">
                                Jaket Denim Anak Dinosaurus
                            </h3>
                            <p class="text-xs sm:text-sm text-ink/70">
                                Pakaian Preloved Terpilih
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2 border-t border-ink/10">
                        <div>
                            <span class="text-[10px] uppercase text-ink/60 font-semibold block">Harga Donasi</span>
                            <span class="text-base sm:text-lg font-extrabold text-ink">Rp 50.000</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button"
                                    onclick="addToCart(4, 'Jaket Denim Anak Dinosaurus', 50000, '{{ asset('images/denim-jacket.jpg') }}', 'Pakaian Preloved Terpilih')"
                                    class="min-h-[48px] rounded-full border border-ink/15 hover:bg-butter text-ink font-bold text-xs flex items-center justify-center gap-1 transition-all active:scale-95"
                                    title="Tambah ke keranjang">
                                <svg class="w-4 h-4 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>+ Keranjang</span>
                            </button>
                            <button type="button"
                                    onclick="openOrderModal('Jaket Denim Anak Dinosaurus', '50.000', 4)"
                                    class="min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink font-bold text-xs flex items-center justify-center gap-1 transition-all active:scale-95 shadow-sm">
                                <span>⚡ Beli</span>
                            </button>
                        </div>
                    </div>
                            <svg class="w-4 h-4 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            <span>Beli &amp; Bantu</span>
                        </button>
                    </div>
                </div>
            @endif
        </div>

        {{-- Tombol bg-sun "Lihat semua produk" ke route('katalog') --}}
        <div class="text-center pt-2">
            <a href="{{ route('katalog') }}"
               class="inline-flex items-center justify-center gap-2 px-8 min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink font-bold text-base transition-all active:scale-95 shadow-sm">
                <span>Lihat semua produk</span>
                <svg class="w-4 h-4 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

    </div>
</section>
