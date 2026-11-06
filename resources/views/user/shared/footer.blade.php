{{-- Reusable User Footer Component --}}
@php
    // URL Media Sosial Resmi Kios Mandiri YPKABK
    $socialMediaLinks = [
        'instagram' => 'https://www.instagram.com/kiosmandiri.ypkabk',
        'youtube' => '#',   // TODO: ganti dengan URL channel YouTube resmi YPKABK
        'facebook' => '#',  // TODO: ganti dengan URL halaman Facebook resmi YPKABK
    ];
@endphp

<footer id="kontak" class="bg-[#2B2430] text-white pt-16 pb-12 border-t border-white/10 font-sans">
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

            {{-- Kolom 4: Media Sosial (Tiga Tautan Teks Sungguhan) --}}
            <div class="space-y-4">
                <h4 class="font-semibold text-base text-white tracking-wide">Media Sosial</h4>
                <p class="text-xs sm:text-sm text-white/80 leading-relaxed font-normal">
                    Ikuti cerita perkembangan karya dan ruang terapi anak-anak binaan kami:
                </p>
                <div class="flex flex-col space-y-2 text-xs sm:text-sm font-normal">
                    <a href="{{ $socialMediaLinks['instagram'] }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="text-white/80 hover:text-white hover:underline transition-colors">
                        Instagram
                    </a>
                    <a href="{{ $socialMediaLinks['youtube'] }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="text-white/80 hover:text-white hover:underline transition-colors">
                        YouTube
                    </a>
                    <a href="{{ $socialMediaLinks['facebook'] }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="text-white/80 hover:text-white hover:underline transition-colors">
                        Facebook
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
