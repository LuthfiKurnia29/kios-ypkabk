{{-- Reusable User Footer Component --}}
@php
    // URL Media Sosial Resmi Kios Mandiri YPKABK
    $socialMediaLinks = [
        'instagram' => 'https://www.instagram.com/kiosmandiri.ypkabk',
        'youtube' => '#',   // TODO: ganti dengan URL channel YouTube resmi YPKABK
        'tiktok' => '#',    // TODO: ganti dengan URL akun TikTok resmi YPKABK
    ];
@endphp

<footer id="kontak" class="bg-teks text-white pt-16 pb-12 border-t border-white/10 font-sans">
    <div class="max-w-[1200px] mx-auto px-6">
        {{-- 4 Kolom: Kios Mandiri + Jam Layanan; Sentra Fisik; Hotline Kebaikan; Media Sosial --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 pb-12 border-b border-white/10">

            {{-- Kolom 1: Kios Mandiri + Jam Layanan --}}
            <div class="space-y-4">
                <h4 class="font-semibold text-base text-white tracking-wide">Kios Mandiri YPKABK</h4>
                <p class="text-xs sm:text-sm text-white/80 leading-relaxed font-normal">
                    Toko amal dan galeri karya anak berkebutuhan khusus. 100% donasi dan hasil transaksi menopang ruang terapi dan vokasi berdaya di Jawa Timur.
                </p>
                <div class="pt-2">
                    <p class="font-semibold text-white text-xs uppercase tracking-wider">Jam Layanan</p>
                    <p class="text-xs sm:text-sm text-white/80 mt-1 font-normal">Senin &ndash; Sabtu, 08.00 &ndash; 16.00 WIB</p>
                </div>
            </div>

            {{-- Kolom 2: Sentra Fisik --}}
            <div class="space-y-4">
                <h4 class="font-semibold text-base text-white tracking-wide">Sentra Fisik</h4>
                <div class="space-y-3 text-xs sm:text-sm text-white/80 font-normal">
                    <div>
                        <p class="font-semibold text-white">Sentra Sidoarjo</p>
                        <p class="mt-0.5 leading-relaxed">Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo, Jawa Timur</p>
                    </div>
                    <div>
                        <p class="font-semibold text-white">Sentra Mojokerto</p>
                        <p class="mt-0.5 leading-relaxed">Jl. Raya Brangkal No. 12, Sooko, Mojokerto, Jawa Timur</p>
                    </div>
                </div>
            </div>

            {{-- Kolom 3: Hotline Kebaikan --}}
            <div class="space-y-4">
                <h4 class="font-semibold text-base text-white tracking-wide">Hotline Kebaikan</h4>
                <div class="space-y-3 text-xs sm:text-sm text-white/80 font-normal">
                    <div>
                        <p class="font-semibold text-white text-xs uppercase tracking-wider">WhatsApp Bantuan &amp; Tanya Produk</p>
                        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                           class="hover:text-pink-pucat transition-colors font-bold text-sm block mt-0.5">
                            +62 812-3456-7890
                        </a>
                    </div>
                    <div>
                        <p class="font-semibold text-white text-xs uppercase tracking-wider">Surat Elektronik</p>
                        <a href="mailto:kios@pedulikasihabk.org" class="hover:text-pink-pucat transition-colors block mt-0.5 font-normal">
                            kios@pedulikasihabk.org
                        </a>
                    </div>
                </div>
            </div>

            {{-- Kolom 4: Media Sosial (Ikon Instagram, YouTube, TikTok) --}}
            <div class="space-y-4">
                <h4 class="font-semibold text-base text-white tracking-wide">Media Sosial</h4>
                <p class="text-xs sm:text-sm text-white/80 leading-relaxed font-normal">
                    Ikuti cerita perkembangan karya dan ruang terapi anak-anak binaan kami:
                </p>
                <div class="flex items-center gap-2 pt-1">
                    {{-- Instagram --}}
                    <a href="{{ $socialMediaLinks['instagram'] }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Instagram Kios Mandiri YPKABK"
                       class="inline-flex items-center justify-center min-w-[40px] min-h-[40px] w-10 h-10 rounded-full text-white/80 hover:text-pink-pucat hover:bg-white/10 transition-colors focus:outline-hidden focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-teks focus-visible:ring-pink-pucat">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>

                    {{-- YouTube --}}
                    <a href="{{ $socialMediaLinks['youtube'] }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="YouTube Kios Mandiri YPKABK"
                       class="inline-flex items-center justify-center min-w-[40px] min-h-[40px] w-10 h-10 rounded-full text-white/80 hover:text-pink-pucat hover:bg-white/10 transition-colors focus:outline-hidden focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-teks focus-visible:ring-pink-pucat">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>

                    {{-- TikTok --}}
                    <a href="{{ $socialMediaLinks['tiktok'] }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="TikTok Kios Mandiri YPKABK"
                       class="inline-flex items-center justify-center min-w-[40px] min-h-[40px] w-10 h-10 rounded-full text-white/80 hover:text-pink-pucat hover:bg-white/10 transition-colors focus:outline-hidden focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-offset-teks focus-visible:ring-pink-pucat">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64c.298 0 .594.045.87.13V9.4a6.33 6.33 0 0 0-.87-.06A6.34 6.34 0 0 0 3.14 15.7a6.34 6.34 0 0 0 10.82 4.47c.05-.05.1-.1.15-.16a6.3 6.3 0 0 0 1.93-4.57V8.69a8.18 8.18 0 0 0 4.78 1.52v-3.4a4.85 4.85 0 0 1-1.23-.12z"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>

        {{-- Baris Bawah: Pernyataan 100% Hasil Penjualan & Hak Cipta (Tanpa Menu Navigasi) --}}
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/70 font-normal">
            <p class="text-center sm:text-left max-w-xl leading-relaxed">
                100% hasil penjualan dialokasikan untuk operasional ruang terapi gratis &amp; pembinaan vokasi anak disabilitas di Jawa Timur.
            </p>
            <div class="text-center sm:text-right shrink-0 text-white/50 text-[11px]">
                &copy; {{ date('Y') }} Yayasan Peduli Kasih Anak Berkebutuhan Khusus (YPKABK). Hak Cipta Dilindungi.
            </div>
        </div>
    </div>
</footer>
