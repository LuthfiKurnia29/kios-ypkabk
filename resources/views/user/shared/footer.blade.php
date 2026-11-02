{{-- Reusable User Footer Component --}}
<footer id="kontak" class="bg-olive text-white pt-16 pb-12 border-t border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 pb-12 border-b border-white/10">

            {{-- Kolom 1: Logo, Tagline Singkat & Tautan Sosmed --}}
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="inline-flex items-center bg-white/95 rounded-2xl p-2.5 shadow-sm transition-transform hover:scale-[1.02]">
                    <img src="{{ asset('images/logo/logo.webp') }}" alt="Logo Kios Mandiri YPKABK" width="2437" height="1186" class="h-10 sm:h-12 w-auto object-contain">
                </a>

                <p class="text-sm text-white/80 leading-relaxed">
                    Wadah ekonomi sirkular dan laboratorium vokasi mandiri bagi anak berkebutuhan khusus binaan Yayasan Peduli Kasih ABK (YPKABK).
                </p>

                {{-- Tautan Sosmed dengan Label Teks Sesuai DESIGN.md --}}
                <div class="pt-2">
                    <p class="text-xs font-semibold text-white/70 uppercase tracking-wider mb-2.5">Ikuti Kegiatan Kami</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 hover:bg-sun hover:text-ink text-white text-xs font-semibold transition-all">
                            <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            <span>Instagram</span>
                        </a>
                        <a href="https://youtube.com" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 hover:bg-sun hover:text-ink text-white text-xs font-semibold transition-all">
                            <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                            <span>YouTube</span>
                        </a>
                        <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/10 hover:bg-sun hover:text-ink text-white text-xs font-semibold transition-all">
                            <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 2.84 3.46 2.61 1.25-.1 2.33-.94 2.74-2.12.18-.54.24-1.12.24-1.69.01-5.07.01-10.14.01-15.21z"/>
                            </svg>
                            <span>TikTok</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Kolom 2: Navigasi Cepat (Maksimal 5 Item Sesuai DESIGN.md) --}}
            <div>
                <h4 class="font-heading font-semibold text-base text-white tracking-wide mb-4">Menu Utama</h4>
                <ul class="space-y-3 text-sm">
                    <li>
                        <a href="{{ route('home') }}#beranda" class="text-white/80 hover:text-sun transition-colors inline-flex items-center gap-2">
                            <span class="text-sun">&bull;</span>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('katalog') }}" class="text-white/80 hover:text-sun transition-colors inline-flex items-center gap-2">
                            <span class="text-sun">&bull;</span>
                            <span>Katalog Produk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#dampak" class="text-white/80 hover:text-sun transition-colors inline-flex items-center gap-2">
                            <span class="text-sun">&bull;</span>
                            <span>Dampak Sosial</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#cara-donasi" class="text-white/80 hover:text-sun transition-colors inline-flex items-center gap-2">
                            <span class="text-sun">&bull;</span>
                            <span>Cara Donasi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('home') }}#kontak" class="text-white/80 hover:text-sun transition-colors inline-flex items-center gap-2">
                            <span class="text-sun">&bull;</span>
                            <span>Kontak &amp; Alamat</span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kolom 3: Alamat Sentra Sidoarjo dan Sentra Mojokerto --}}
            <div>
                <h4 class="font-heading font-semibold text-base text-white tracking-wide mb-4">Lokasi Sentra</h4>
                <div class="space-y-3.5 text-sm">
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-3.5 space-y-1">
                        <div class="flex items-center gap-2 text-sun font-bold text-sm">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Sentra Sidoarjo</span>
                        </div>
                        <p class="text-xs text-white/80 leading-relaxed pl-6">
                            Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo, Jawa Timur
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-2xl p-3.5 space-y-1">
                        <div class="flex items-center gap-2 text-sun font-bold text-sm">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Sentra Mojokerto</span>
                        </div>
                        <p class="text-xs text-white/80 leading-relaxed pl-6">
                            Jl. Raya Brangkal No. 12, Sooko, Mojokerto, Jawa Timur
                        </p>
                    </div>
                </div>
            </div>

            {{-- Kolom 4: Kontak, WA & Jam Layanan --}}
            <div>
                <h4 class="font-heading font-semibold text-base text-white tracking-wide mb-4">Kontak &amp; Layanan</h4>
                <div class="space-y-3 text-sm">
                    {{-- WhatsApp --}}
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                       class="flex items-start gap-3 p-3 rounded-2xl bg-white/5 border border-white/10 hover:border-sun/50 transition-colors group">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-white/70">Nomor WhatsApp:</p>
                            <p class="font-bold text-white group-hover:text-sun transition-colors">0812-3456-7890</p>
                        </div>
                    </a>

                    {{-- Jam Operasional --}}
                    <div class="flex items-start gap-3 p-3 rounded-2xl bg-white/5 border border-white/10">
                        <div class="w-8 h-8 rounded-full bg-sun/20 text-sun flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-white/70">Jam Layanan:</p>
                            <p class="font-semibold text-white text-xs mt-0.5">Senin &ndash; Sabtu: 08.00 &ndash; 16.00 WIB</p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-start gap-3 p-3 rounded-2xl bg-white/5 border border-white/10">
                        <div class="w-8 h-8 rounded-full bg-sky-500/20 text-sky-300 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-white/70">Email Resmi:</p>
                            <a href="mailto:kios@pedulikasihabk.org" class="font-semibold text-white text-xs hover:text-sun transition-colors">
                                kios@pedulikasihabk.org
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Baris Bawah: Hak Cipta & Kembali ke Atas --}}
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/70">
            <p class="text-center sm:text-left">
                &copy; {{ date('Y') }} Kios Mandiri &mdash; Yayasan Peduli Kasih ABK (YPKABK). Hak Cipta Dilindungi Undang-Undang.
            </p>
            <div class="flex items-center gap-6">
                <a href="#beranda" class="inline-flex items-center gap-1.5 text-white/80 hover:text-sun font-semibold transition-colors">
                    <span>Kembali ke Atas</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>
