{{-- Section: Hero --}}
<section id="beranda" class="w-full bg-[#FFFBF7] pt-[96px] pb-12 lg:pt-[104px] lg:pb-16">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">

            {{-- Kolom Kiri: Teks & Aksi --}}
            <div class="flex flex-col justify-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[#2B2430] leading-tight tracking-tight">
                    Barang preloved, <span class="text-primary">peluang baru</span> untuk anak berkebutuhan khusus.
                </h1>

                <p class="text-base sm:text-lg text-[#2B2430]/80 font-normal leading-relaxed mt-5 max-w-xl">
                    Donasi barang layak pakai dan hasil karya adik-adik disabilitas kami kelola untuk operasional ruang terapi gratis dan pelatihan vokasi mandiri di Sidoarjo dan Mojokerto.
                </p>

                <div class="flex flex-wrap items-center gap-4 mt-8">
                    {{-- Tombol 1: Donasikan Barang --}}
                    <button type="button"
                            onclick="openDonationModal()"
                            class="inline-flex items-center justify-center px-6 h-[44px] rounded-[8px] bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition-all active:scale-[0.98] shadow-xs">
                        Donasikan Barang
                    </button>

                    {{-- Tombol 2: Lihat Katalog --}}
                    <a href="{{ route('katalog') }}"
                       class="inline-flex items-center justify-center px-6 h-[44px] rounded-[8px] border border-primary text-primary hover:bg-primary hover:text-white font-semibold text-sm transition-all active:scale-[0.98]">
                        Lihat Katalog
                    </a>
                </div>
            </div>

            {{-- Kolom Kanan: Foto pict-1.webp (Max 440px di Desktop, Rata Kanan, Max Height 480px, Rasio Asli) --}}
            <div class="w-full flex justify-center lg:justify-end">
                <div class="w-full lg:max-w-[440px] flex justify-center lg:justify-end">
                    <img src="{{ asset('images/pict-1.webp') }}"
                         alt="Karya dan kegiatan anak binaan Kios Mandiri YPKABK"
                         class="max-h-[360px] lg:max-h-[480px] w-auto h-auto object-contain rounded-[12px] shadow-xs border border-[#2B2430]/10"
                         loading="eager"
                         fetchpriority="high">
                </div>
            </div>

        </div>
    </div>
</section>
