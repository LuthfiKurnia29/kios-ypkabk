{{-- Section: Produk Pilihan (Latar #FDEEF4 Sesuai PDF Halaman 1) --}}
<section id="produk-pilihan" class="w-full bg-[#FDEEF4] py-16 lg:py-20 font-sans">
    <div class="max-w-[1200px] mx-auto px-6 space-y-10">

        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-3 max-w-2xl">
                <span class="text-xs uppercase tracking-wider font-semibold text-primary">
                    Koleksi Butik Amal
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#2B2430] tracking-tight">
                    Temukan baik, untuk hari baik.
                </h2>
                <p class="text-sm sm:text-base text-[#2B2430]/75 font-normal leading-relaxed">
                    Produk buatan tangan anak istimewa dan donasi preloved pilihan berkualitas butik.
                </p>
            </div>
            <div class="text-xs font-semibold text-[#2B2430]/70 flex items-center gap-1.5 self-start sm:self-end pb-1">
                <span class="w-2 h-2 rounded-full bg-primary"></span>
                <span>Stok diperbarui setiap pekan</span>
            </div>
        </div>

        {{-- Grid 4 Produk Menggunakan Komponen <x-product-card> --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @if(isset($featuredProducts) && $featuredProducts->count() > 0)
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" button-label="Pilih Produk" />
                @endforeach
            @else
                {{-- Fallback jika database belum di-seed --}}
                @php
                    $mockProducts = [
                        [
                            'id' => 1,
                            'name' => 'Tote Bag Bunga Ceria',
                            'price' => 65000,
                            'source' => 'karya',
                            'artisan_name' => 'Sentra Sidoarjo',
                            'description' => 'Lukisan tangan oleh Dimas (16 th, Autisme) di Sentra Sidoarjo.',
                            'image' => 'images/pict-2.webp',
                        ],
                        [
                            'id' => 2,
                            'name' => 'Kemeja Linen Santai',
                            'price' => 85000,
                            'source' => 'preloved',
                            'artisan_name' => 'Kondisi 95%',
                            'description' => 'Kondisi baik dan bersih, donasi pendukung di Surabaya.',
                            'image' => 'images/pict-3.webp',
                        ],
                        [
                            'id' => 3,
                            'name' => 'Paket Buku Cerita',
                            'price' => 50000,
                            'source' => 'preloved',
                            'artisan_name' => 'Set 3 Buku',
                            'description' => 'Tiga buku dongeng bergambar anak, kondisi rapi dan bersih.',
                            'image' => 'images/pict-4.webp',
                        ],
                        [
                            'id' => 4,
                            'name' => 'Gelang Manik Pelangi',
                            'price' => 25000,
                            'source' => 'karya',
                            'artisan_name' => 'Sentra Mojokerto',
                            'description' => 'Dirangkai oleh Nabila (14 th, Down Syndrome) dalam latihan kemandirian.',
                            'image' => 'images/pict-1.webp',
                        ],
                    ];
                @endphp
                @foreach($mockProducts as $product)
                    <x-product-card :product="$product" button-label="Pilih Produk" />
                @endforeach
            @endif
        </div>

        {{-- Tombol Lihat Semua Produk --}}
        <div class="text-center pt-4">
            <a href="{{ route('katalog') }}"
               class="inline-flex items-center justify-center px-8 h-[44px] rounded-[8px] bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition-all active:scale-[0.98] shadow-xs">
                Lihat Semua Produk
            </a>
        </div>

    </div>
</section>
