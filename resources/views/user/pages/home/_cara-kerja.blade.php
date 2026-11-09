{{-- Section: Cara Berbagi (Latar #FFFBF7 Sesuai PDF Halaman 1) --}}
<section id="cara-donasi" class="w-full bg-[#FFFBF7] py-16 lg:py-20 font-sans">
    <div class="max-w-[1200px] mx-auto px-6 space-y-10">

        {{-- Header Section --}}
        <div class="space-y-3 max-w-2xl">
            <span class="text-xs uppercase tracking-wider font-semibold text-primary">
                Langkah Praktis
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#2B2430] tracking-tight">
                Cara Berbagi di Kios Mandiri
            </h2>
            <p class="text-sm sm:text-base text-[#2B2430]/75 font-normal leading-relaxed">
                Donasikan barang layak pakai untuk mendukung kebutuhan ruang terapi anak binaan.
            </p>
        </div>

        {{-- 3 Kartu Langkah Praktis --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Langkah 01 --}}
            <div class="bg-white rounded-[12px] p-6 sm:p-8 border border-[#2B2430]/10 flex flex-col justify-between space-y-4 shadow-xs">
                <div class="space-y-3">
                    <span class="font-bold text-2xl sm:text-3xl text-primary block">
                        01
                    </span>
                    <h3 class="font-semibold text-lg text-[#2B2430] leading-snug">
                        Pilih yang Masih Layak
                    </h3>
                </div>
                <p class="text-xs sm:text-sm text-[#2B2430]/75 font-normal leading-relaxed">
                    Pilih pakaian bersih, buku, tas, atau perlengkapan rumah dengan kondisi baik dan siap pakai.
                </p>
            </div>

            {{-- Langkah 02 --}}
            <div class="bg-white rounded-[12px] p-6 sm:p-8 border border-[#2B2430]/10 flex flex-col justify-between space-y-4 shadow-xs">
                <div class="space-y-3">
                    <span class="font-bold text-2xl sm:text-3xl text-primary block">
                        02
                    </span>
                    <h3 class="font-semibold text-lg text-[#2B2430] leading-snug">
                        Kirim Foto via WhatsApp
                    </h3>
                </div>
                <p class="text-xs sm:text-sm text-[#2B2430]/75 font-normal leading-relaxed">
                    Kirim foto dan keterangan barang ke kontak relawan untuk konfirmasi penerimaan secara langsung.
                </p>
            </div>

            {{-- Langkah 03 --}}
            <div class="bg-white rounded-[12px] p-6 sm:p-8 border border-[#2B2430]/10 flex flex-col justify-between space-y-4 shadow-xs">
                <div class="space-y-3">
                    <span class="font-bold text-2xl sm:text-3xl text-primary block">
                        03
                    </span>
                    <h3 class="font-semibold text-lg text-[#2B2430] leading-snug">
                        Kirim atau Antar Barang
                    </h3>
                </div>
                <p class="text-xs sm:text-sm text-[#2B2430]/75 font-normal leading-relaxed">
                    Antar ke sentra Sidoarjo atau Mojokerto, atau kirim melalui ekspedisi. Tim kami mendata dan menyalurkannya.
                </p>
            </div>

        </div>

        {{-- Tautan Lengkap ke Cara Donasi --}}
        <div class="pt-2">
            <a href="{{ route('cara-donasi') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-primary/80 transition-colors">
                <span>Lihat panduan lengkap barang yang dapat didonasikan</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

    </div>
</section>
