{{-- ========================================================
     SECTION: HERO (Gaya Humankind)
     Latar: bg-hero-gradient, id="beranda"
     Padding atas: pt-28 lg:pt-32 (di belakang fixed navbar), pb-12 lg:pb-16.
     Tanpa margin atas pada section.
     Container: max-w-7xl mx-auto px-6.
     Grid: lg:grid-cols-2 gap-10 items-stretch.
     - Kiri: headline font-heading font-semibold text-4xl lg:text-6xl leading-[1.1] max-w-xl (tanpa tracking negatif).
             Paragraf text-base lg:text-lg max-w-md text-ink/80 mt-6.
             Dua tombol sejajar mt-8 gap-3:
             (1) bg-sun text-ink "Donasikan Barang" (openDonationModal)
             (2) bg-white text-ink "Lihat Katalog" (route('katalog'))
     - Kanan: relative rounded-3xl overflow-hidden min-h-[420px] lg:min-h-full (mobile: h-[360px]),
              gambar absolute inset-0 w-full h-full object-cover object-top (tanpa pop up kartu).
     ======================================================== --}}
<section id="beranda" class="bg-hero-gradient pt-28 lg:pt-32 pb-12 lg:pb-16 lg:min-h-[calc(100vh)] flex items-center">
    <div class="max-w-7xl mx-auto px-6 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-stretch">

            {{-- Kolom Kiri: Teks & Aksi --}}
            <div class="flex flex-col justify-center py-4 lg:py-8">
                <h1 class="font-heading font-semibold text-4xl lg:text-6xl leading-[1.1] max-w-xl text-ink">
                    Barang preloved, peluang baru untuk anak istimewa.
                </h1>

                <p class="text-base lg:text-lg max-w-md text-ink/80 leading-relaxed mt-6">
                    Kios Mandiri mengelola donasi preloved layak pakai dan karya kreatif anak berkebutuhan khusus binaan YPKABK di Sidoarjo &amp; Mojokerto untuk membiayai ruang terapi gratis dan pelatihan vokasi mandiri.
                </p>

                <div class="flex flex-wrap items-center gap-3 mt-8">
                    {{-- Tombol 1: bg-sun "Donasikan Barang" --}}
                    <button type="button"
                            onclick="openDonationModal()"
                            class="inline-flex items-center justify-center gap-2 px-6 min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink font-bold text-base transition-all active:scale-95 shadow-sm">
                        <svg class="w-5 h-5 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Donasikan Barang</span>
                    </button>

                    {{-- Tombol 2: bg-white "Lihat Katalog" --}}
                    <a href="{{ route('katalog') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 min-h-[48px] rounded-full bg-white hover:bg-white/90 text-ink font-bold text-base transition-all active:scale-95 shadow-sm">
                        <span>Lihat Katalog</span>
                        <svg class="w-4 h-4 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Kolom Kanan: Foto Hero --}}
            <div class="relative rounded-3xl overflow-hidden h-[360px] min-h-[360px] lg:h-auto lg:min-h-full bg-cream shadow-sm">
                <img src="{{ asset('images/hero/hero.webp') }}"
                     alt="Ibu dan anak berkebutuhan khusus bercerita dalam kegiatan YPKABK"
                     width="1600"
                     height="1121"
                     fetchpriority="high"
                     class="absolute inset-0 w-full h-full object-cover object-center rounded-3xl">
            </div>

        </div>
    </div>
</section>
