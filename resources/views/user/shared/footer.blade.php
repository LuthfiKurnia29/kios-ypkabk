{{-- Reusable User Footer Component --}}
<footer class="bg-stone-900 text-stone-300 pt-16 pb-12 border-t border-stone-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-stone-800">

            {{-- Col 1 & 2: Brand Info --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-rose-600 via-rose-700 to-teal-700 p-0.5 shadow-md">
                        <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight text-white">Kios Mandiri YPKABK</span>
                        <p class="text-xs text-stone-400">Yayasan Peduli Kasih Anak Berkebutuhan Khusus</p>
                    </div>
                </div>

                <p class="text-sm text-stone-400 leading-relaxed max-w-md">
                    Wadah ekonomi sirkular dan laboratorium vokasi kreatif bagi anak berkebutuhan khusus. Donasi barang preloved Anda diolah, dipajang, dan hasilnya 100% mendanai ruang terapi dan pendidikan mandiri.
                </p>

                <div class="pt-2 flex items-center gap-3">
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-stone-800 hover:bg-rose-600 text-stone-300 hover:text-white flex items-center justify-center transition-colors" title="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-stone-800 hover:bg-rose-600 text-stone-300 hover:text-white flex items-center justify-center transition-colors" title="YouTube">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                    <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-xl bg-stone-800 hover:bg-rose-600 text-stone-300 hover:text-white flex items-center justify-center transition-colors" title="TikTok">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 2.84 3.46 2.61 1.25-.1 2.33-.94 2.74-2.12.18-.54.24-1.12.24-1.69.01-5.07.01-10.14.01-15.21z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Col 3: Quick Navigation --}}
            <div>
                <h4 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Navigasi</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:text-rose-400 transition-colors">Beranda</a></li>
                    <li><a href="#cara-kerja" class="hover:text-rose-400 transition-colors">Cara Kerja Kios</a></li>
                    <li><a href="#katalog" class="hover:text-rose-400 transition-colors">Katalog Karya & Preloved</a></li>
                    <li><a href="#transparansi" class="hover:text-rose-400 transition-colors">Rekaman Transparansi</a></li>
                    <li><a href="#donasi-sekarang" class="hover:text-rose-400 transition-colors">Panduan Donasi Barang</a></li>
                </ul>
            </div>

            {{-- Col 4: Kategori Produk --}}
            <div>
                <h4 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Kategori Donasi</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="#katalog" class="hover:text-rose-400 transition-colors">Karya Rajut & Kriya ABK</a></li>
                    <li><a href="#katalog" class="hover:text-rose-400 transition-colors">Pakaian Anak & Dewasa</a></li>
                    <li><a href="#katalog" class="hover:text-rose-400 transition-colors">Buku Ensiklopedia & Cerita</a></li>
                    <li><a href="#katalog" class="hover:text-rose-400 transition-colors">Mainan Edukatif & Puzzle</a></li>
                    <li><a href="#katalog" class="hover:text-rose-400 transition-colors">Kerajinan Kayu Vokasi</a></li>
                </ul>
            </div>

            {{-- Col 5: Lokasi & Kontak --}}
            <div>
                <h4 class="text-sm font-semibold text-white tracking-wider uppercase mb-4">Lokasi Sentra</h4>
                <div class="space-y-3 text-sm text-stone-400">
                    <div>
                        <p class="font-medium text-white">Sentra Solo:</p>
                        <p class="text-xs">Jl. Veteran No. 45, Laweyan, Surakarta</p>
                    </div>
                    <div>
                        <p class="font-medium text-white">Sentra Surabaya:</p>
                        <p class="text-xs">Jl. Gayungsari Barat No. 12, Surabaya</p>
                    </div>
                    <div class="pt-1">
                        <p class="font-medium text-white">Email:</p>
                        <p class="text-xs">kios@pedulikasihabk.org</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Bottom Copyright & Disclaimer --}}
        <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-stone-500">
            <p>
                &copy; {{ date('Y') }} Yayasan Peduli Kasih ABK (YPKABK). Hak Cipta Dilindungi Undang-Undang.
            </p>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-stone-400 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-stone-400 transition-colors">Ketentuan Donasi</a>
                <a href="#" class="hover:text-stone-400 transition-colors">Laporan Keuangan Publik</a>
            </div>
        </div>
    </div>
</footer>
