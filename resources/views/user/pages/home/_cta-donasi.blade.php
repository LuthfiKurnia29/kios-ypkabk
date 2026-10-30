{{-- ========================================================
     SECTION: AJAKAN DONASI
     id="donasi", layout: foto kiri, kartu putih mengambang.
     Latar: bg-[#FDF5CC] py-16 lg:py-24, container max-w-7xl mx-auto px-6.
     Teks: text-ink (bukan putih).
     Wrapper: relative.
     Foto di kiri: rounded-3xl object-cover w-full h-[360px] lg:h-[520px] lg:w-[62%].
     Kartu putih di kanan: lg:absolute lg:right-0 lg:top-1/2 lg:-translate-y-1/2 lg:w-[46%] bg-white rounded-3xl p-8 lg:p-10.
     Mobile: relative -mt-12 mx-4.
     Di bawah wrapper: satu baris teks tengah dipisah titik tengah.
     ======================================================== --}}
<section id="donasi" class="bg-[#FDF5CC] py-16 lg:py-24 text-ink">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Wrapper Relative untuk Foto & Kartu Mengambang --}}
        <div class="relative">

            {{-- Foto di Kiri (Foto Lebar Kegiatan) --}}
            <div class="rounded-3xl overflow-hidden w-full h-[360px] lg:h-[520px] lg:w-[62%] bg-cream shadow-sm">
                <img src="{{ asset('images/kegiatan/kegiatan-grup.webp') }}"
                     alt="Kegiatan kelompok anak-anak berkebutuhan khusus dan pendamping di YPKABK"
                     width="1600"
                     height="1066"
                     class="w-full h-full object-cover rounded-3xl"
                     loading="lazy">
            </div>

            {{-- Kartu Putih di Kanan (Menimpa tepi foto di desktop, menimpa bawah foto di mobile) --}}
            <div class="relative -mt-12 mx-4 lg:mx-0 lg:absolute lg:right-0 lg:top-1/2 lg:-translate-y-1/2 lg:w-[46%] bg-white rounded-3xl p-8 lg:p-10 shadow-sm border border-ink/5">
                <h2 class="font-heading text-3xl lg:text-4xl text-ink font-bold leading-tight">
                    Punya Barang Layak Pakai? Donasikan Hari Ini.
                </h2>

                <p class="text-base text-ink/80 mt-4 leading-relaxed">
                    Setiap pakaian, buku, atau mainan yang Anda donasikan diolah dan dijual untuk membiayai ruang terapi gratis serta pelatihan vokasi anak-anak istimewa binaan YPKABK di Sidoarjo &amp; Mojokerto.
                </p>

                {{-- Baris Tombol Donasi & Link WA --}}
                <div class="flex flex-wrap items-center gap-4 mt-6">
                    <button type="button"
                            onclick="openDonationModal()"
                            class="inline-flex items-center justify-center gap-2 px-6 min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink font-bold text-base transition-all active:scale-95 shadow-sm">
                        <svg class="w-5 h-5 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <span>Donasikan Barang</span>
                    </button>

                    <a href="https://wa.me/6281234567890"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 font-bold text-ink underline hover:text-ink/80 text-base py-2">
                        <span>Chat WhatsApp</span>
                        <svg class="w-4 h-4 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>

                {{-- Nomor WA dan Jam Layanan --}}
                <div class="text-sm text-ink/70 mt-5 space-y-1">
                    {{-- TODO: ganti dengan data asli --}}
                    <p>
                        <span class="font-medium text-ink">Hotline WhatsApp:</span> 0812-3456-7890
                    </p>
                    {{-- TODO: ganti dengan data asli --}}
                    <p>
                        <span class="font-medium text-ink">Jam Layanan:</span> Senin &ndash; Sabtu, 08.00 &ndash; 16.00 WIB
                    </p>
                </div>
            </div>

        </div>

        {{-- Satu Baris Teks Tengah di Bawah Wrapper --}}
        <div class="mt-10 lg:mt-14 text-center">
            <p class="text-base text-ink/80 leading-relaxed">
                Kami menerima: pakaian anak &amp; dewasa &middot; buku bacaan &middot; mainan edukatif &middot; perlengkapan kerajinan
            </p>
        </div>

    </div>
</section>
