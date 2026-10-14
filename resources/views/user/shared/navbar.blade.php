{{-- Reusable User Navbar Component --}}
<header class="w-full bg-[#FCFBF9] border-b border-stone-200/70 sticky top-0 z-50 backdrop-blur-md bg-[#FCFBF9]/95 transition-all">
    {{-- Top Announcement Bar --}}
    <div class="bg-gradient-to-r from-rose-900 via-rose-800 to-rose-900 text-white text-xs py-2 px-4">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 bg-rose-700/80 text-rose-100 px-2.5 py-0.5 rounded-full font-medium text-[11px] tracking-wide">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Aksi Nyata
                </span>
                <span class="font-medium text-stone-200">Kios Mandiri Solo & Surabaya:</span>
                <a href="#transparansi" class="text-white hover:text-rose-200 underline underline-offset-2 transition-colors">
                    Baca update donasi dan penyaluran berkala &rarr;
                </a>
            </div>
            <div class="hidden md:flex items-center gap-4 text-rose-200 text-[11px]">
                <span class="flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Senin - Sabtu: 08.00 - 16.00 WIB
                </span>
                <span class="text-rose-400">|</span>
                <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                    </svg>
                    Hotline WA: 0812-3456-7890
                </a>
            </div>
        </div>
    </div>

    {{-- Main Navbar --}}
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Brand Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-rose-600 via-rose-700 to-teal-700 p-0.5 shadow-md shadow-rose-900/10 group-hover:scale-105 transition-transform">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center">
                        <svg class="w-6 h-6 text-rose-600 group-hover:text-rose-700 transition-colors" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xl font-bold tracking-tight text-stone-900 group-hover:text-rose-700 transition-colors">
                            Kios Mandiri
                        </span>
                        <span class="text-[10px] font-semibold bg-rose-100 text-rose-700 px-1.5 py-0.5 rounded tracking-wide uppercase">
                            YPKABK
                        </span>
                    </div>
                    <span class="text-xs text-stone-500 font-medium">
                        Pemberdayaan Vokasi Anak Istimewa
                    </span>
                </div>
            </a>

            {{-- Desktop Navigation Links --}}
            <div class="hidden lg:flex items-center gap-7 text-sm font-medium">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-rose-700 font-bold' : 'text-stone-700 hover:text-rose-700 font-medium' }} transition-colors flex items-center gap-1.5">
                    <span>Beranda</span>
                </a>
                <a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog') ? 'text-rose-700 font-bold' : 'text-stone-700 hover:text-rose-700 font-medium' }} transition-colors flex items-center gap-1.5">
                    <span>Katalog Pilihan</span>
                </a>
                <a href="{{ url('/#cara-kerja') }}" class="text-stone-700 hover:text-rose-700 font-medium transition-colors">
                    Cara Donasi Barang
                </a>
                <a href="{{ url('/#transparansi') }}" class="text-stone-700 hover:text-rose-700 font-medium transition-colors">
                    Transparansi & Dampak
                </a>
            </div>

            {{-- Right Actions --}}
            <div class="hidden md:flex items-center gap-3">
                {{-- Quick Search Trigger --}}
                <a href="{{ route('katalog') }}" class="p-2.5 text-stone-500 hover:text-stone-800 hover:bg-stone-100 rounded-xl transition-colors" title="Cari produk di katalog">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </a>

                {{-- Secondary Action Button: Jelajahi --}}
                <a href="{{ route('katalog') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-300 text-stone-700 text-sm font-semibold hover:border-rose-300 hover:text-rose-700 hover:bg-rose-50/50 transition-all {{ request()->routeIs('katalog') ? 'bg-rose-50 border-rose-300 text-rose-700' : '' }}">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Katalog</span>
                </a>

                {{-- Primary Action Button: Donasi Barang --}}
                <a href="{{ url('/#donasi-sekarang') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-rose-700 to-rose-600 hover:from-rose-800 hover:to-rose-700 text-white text-sm font-semibold shadow-sm hover:shadow-md hover:shadow-rose-700/20 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>Donasikan Barang</span>
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <div class="flex items-center gap-2 lg:hidden">
                <a href="{{ route('katalog') }}" class="p-2 text-stone-600 hover:text-rose-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </a>
                <button type="button" id="mobile-menu-toggle" aria-label="Toggle navigation menu" class="p-2.5 rounded-xl text-stone-600 hover:text-stone-900 hover:bg-stone-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                </button>
            </div>

        </div>
    </nav>

    {{-- Mobile Drawer Menu --}}
    <div id="mobile-menu" class="hidden lg:hidden border-t border-stone-200/80 bg-white/95 backdrop-blur-md px-4 pt-3 pb-6 space-y-3">
        <div class="space-y-1 text-base font-medium text-stone-700">
            <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-lg {{ request()->routeIs('home') ? 'text-rose-700 font-semibold bg-rose-50/70' : 'hover:bg-stone-50 hover:text-rose-700' }}">
                Beranda
            </a>
            <a href="{{ route('katalog') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg {{ request()->routeIs('katalog') ? 'text-rose-700 font-semibold bg-rose-50/70' : 'hover:bg-stone-50 hover:text-rose-700' }}">
                <span>Katalog Pilihan</span>
            </a>
            <a href="{{ url('/#cara-kerja') }}" class="block px-3 py-2.5 rounded-lg hover:bg-stone-50 hover:text-rose-700">
                Cara Donasi Barang
            </a>
            <a href="{{ url('/#transparansi') }}" class="block px-3 py-2.5 rounded-lg hover:bg-stone-50 hover:text-rose-700">
                Transparansi & Dampak
            </a>
        </div>
        <div class="pt-3 border-t border-stone-200 flex flex-col gap-2.5">
            <a href="{{ url('/#donasi-sekarang') }}" class="w-full text-center py-3 rounded-xl bg-rose-700 text-white font-semibold shadow-sm">
                Donasikan Barang Preloved
            </a>
            <a href="{{ route('katalog') }}" class="w-full text-center py-2.5 rounded-xl border border-stone-300 text-stone-700 font-semibold hover:bg-stone-50">
                Lihat Katalog Produk
            </a>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        if (toggleBtn && mobileMenu) {
            toggleBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
            // Close mobile menu on clicking any link inside it
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                });
            });
        }
    });
</script>
