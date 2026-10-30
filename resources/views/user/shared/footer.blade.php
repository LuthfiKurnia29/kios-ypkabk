{{-- Reusable User Footer Component --}}
<footer id="kontak" class="bg-olive text-white pt-16 pb-12 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-white/10">

            {{-- Kolom 1: Logo, Tagline Singkat & Tautan Sosmed --}}
            <div class="space-y-4">
                {{-- TODO: ganti dengan file logo asli dari yayasan (SVG) --}}
                <a href="{{ route('home') }}" class="inline-block bg-white rounded-xl p-2">
                    <img src="{{ asset('images/logo/logo.webp') }}" alt="Logo Kios Mandiri YPKABK" width="2437" height="1186" class="h-12 lg:h-14 w-auto">
                </a>

                <p class="text-sm text-white/80 leading-relaxed">
                    Wadah ekonomi sirkular dan laboratorium vokasi mandiri bagi anak berkebutuhan khusus binaan Yayasan Peduli Kasih ABK.
                </p>

                {{-- Tautan Sosmed --}}
                <div class="pt-2 flex items-center gap-3">
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-white/10 hover:bg-sun hover:text-ink text-white flex items-center justify-center transition-colors" title="Instagram">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-white/10 hover:bg-sun hover:text-ink text-white flex items-center justify-center transition-colors" title="YouTube">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-full bg-white/10 hover:bg-sun hover:text-ink text-white flex items-center justify-center transition-colors" title="TikTok">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 2.84 3.46 2.61 1.25-.1 2.33-.94 2.74-2.12.18-.54.24-1.12.24-1.69.01-5.07.01-10.14.01-15.21z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Kolom 2: Menu --}}
            <div>
                <h4 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Menu</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('home') }}#beranda" class="text-white hover:text-sun transition-colors">Beranda</a></li>
                    <li><a href="{{ route('katalog') }}" class="text-white hover:text-sun transition-colors">Katalog Produk</a></li>
                    <li><a href="{{ route('home') }}#cara-donasi" class="text-white hover:text-sun transition-colors">Cara Donasi</a></li>
                    <li><a href="{{ route('home') }}#dampak" class="text-white hover:text-sun transition-colors">Dampak</a></li>
                    <li><a href="{{ route('home') }}#cerita" class="text-white hover:text-sun transition-colors">Cerita</a></li>
                    <li><a href="{{ route('home') }}#donasi" class="text-white hover:text-sun transition-colors">Donasi Barang</a></li>
                </ul>
            </div>

            {{-- Kolom 3: Alamat Sentra Sidoarjo dan Sentra Mojokerto --}}
            <div>
                <h4 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Lokasi Sentra</h4>
                <div class="space-y-4 text-sm text-white/80">
                    <div>
                        <p class="font-medium text-white">Sentra Sidoarjo:</p>
                        {{-- TODO: ganti dengan alamat asli --}}
                        <p class="text-xs text-white/75 mt-0.5">Sidoarjo, Jawa Timur</p>
                    </div>
                    <div>
                        <p class="font-medium text-white">Sentra Mojokerto:</p>
                        {{-- TODO: ganti dengan alamat asli --}}
                        <p class="text-xs text-white/75 mt-0.5">Mojokerto, Jawa Timur</p>
                    </div>
                </div>
            </div>

            {{-- Kolom 4: Kontak, WA & Jam Layanan --}}
            <div>
                <h4 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Kontak &amp; Layanan</h4>
                <div class="space-y-4 text-sm text-white/80">
                    <div>
                        <p class="font-medium text-white">Nomor WhatsApp:</p>
                        {{-- TODO: ganti dengan data asli --}}
                        <p class="text-xs mt-0.5">
                            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="text-white hover:text-sun transition-colors font-bold">
                                0812-3456-7890
                            </a>
                        </p>
                    </div>
                    <div>
                        <p class="font-medium text-white">Jam Layanan:</p>
                        {{-- TODO: ganti dengan data asli --}}
                        <p class="text-xs text-white/75 mt-0.5">Senin &ndash; Sabtu: 08.00 &ndash; 16.00 WIB</p>
                    </div>
                    <div>
                        <p class="font-medium text-white">Email:</p>
                        {{-- TODO: ganti dengan data asli --}}
                        <p class="text-xs text-white/75 mt-0.5">kios@pedulikasihabk.org</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Baris Bawah: Hak Cipta --}}
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/70">
            <p>
                &copy; {{ date('Y') }} Kios Mandiri YPKABK. Hak Cipta Dilindungi Undang-Undang.
            </p>
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}#beranda" class="text-white hover:text-sun transition-colors">Kembali ke Atas &uarr;</a>
            </div>
        </div>
    </div>
</footer>
