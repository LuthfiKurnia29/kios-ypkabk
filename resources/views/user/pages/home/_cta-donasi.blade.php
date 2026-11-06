{{-- Section: Banner Mulai Donasi (Full-Width #C2185B Tanpa Rounded, Menempel ke Footer) --}}
<section id="donasi" class="w-full bg-primary text-white py-16 lg:py-20 rounded-none font-sans">
    <div class="max-w-[1200px] mx-auto px-6 text-center space-y-4">

        {{-- Label Atas --}}
        <span class="text-xs uppercase tracking-wider font-semibold text-white/80 block">
            Mulai Donasi
        </span>

        {{-- Judul Banner --}}
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white tracking-tight max-w-2xl mx-auto leading-tight">
            Salurkan barang layak pakai untuk ruang terapi anak disabilitas
        </h2>

        {{-- Deskripsi --}}
        <p class="text-sm sm:text-base text-white/90 font-normal leading-relaxed max-w-xl mx-auto pt-1">
            Donasi pakaian, buku, dan perlengkapan rumah Anda membantu mendanai sesi terapi gratis dan kemandirian vokasi di Jawa Timur.
        </p>

        {{-- Tombol Aksi --}}
        <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
            {{-- Tombol Donasikan Barang --}}
            <button type="button"
                    onclick="openDonationModal()"
                    class="inline-flex items-center justify-center px-6 h-[44px] rounded-[8px] bg-white text-primary font-semibold text-sm hover:bg-white/95 transition-all active:scale-[0.98] shadow-xs">
                Donasikan Barang
            </button>

            {{-- Tombol Chat WhatsApp Hotline --}}
            <a href="https://wa.me/6281234567890"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center justify-center px-6 h-[44px] rounded-[8px] border border-white/60 text-white font-semibold text-sm hover:bg-white/10 transition-all active:scale-[0.98]">
                Chat WhatsApp Hotline (+62 812-3456-7890)
            </a>
        </div>

        {{-- Keterangan Legalitas Yayasan --}}
        <p class="text-xs text-white/70 font-normal pt-6">
            Yayasan Peduli Kasih ABK (YPKABK) berizin resmi Kemensos &amp; Dinsos Prov. Jawa Timur.
        </p>

    </div>
</section>
