{{-- Reusable User Navbar Component --}}
@php
    $isBeranda = request()->routeIs('home', 'user.home');
    $isKatalog = request()->routeIs('katalog', 'products.*');
    $isDampak = request()->routeIs('dampak');
    $isUnboxing = request()->routeIs('unboxing');
    $isCaraDonasi = request()->routeIs('cara-donasi');
    $isKontak = request()->routeIs('kontak');
@endphp

<header id="main-header" class="fixed top-0 inset-x-0 z-50 bg-krem border-b border-teks/10 transition-all">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="flex items-center justify-between h-[72px]">

            {{-- Brand Logo (Kiri) - Tinggi 40px tanpa teks tambahan --}}
            <a href="{{ route('home') }}" class="flex items-center py-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded-lg">
                <img src="{{ file_exists(public_path('images/logo/logo.webp')) ? asset('images/logo/logo.webp') : asset('images/logo.webp') }}" alt="Logo Kios Mandiri YPKABK" class="h-10 w-auto object-contain">
            </a>

            {{-- Desktop Navigation Links (Tengah) --}}
            <nav class="hidden lg:flex items-center gap-6 xl:gap-8 text-sm h-[72px]">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center h-[72px] border-b-2 transition-colors {{ $isBeranda ? 'border-primary text-primary font-semibold' : 'border-transparent text-teks/80 hover:text-primary font-medium' }}">
                    Beranda
                </a>
                <a href="{{ route('katalog') }}"
                   class="inline-flex items-center h-[72px] border-b-2 transition-colors {{ $isKatalog ? 'border-primary text-primary font-semibold' : 'border-transparent text-teks/80 hover:text-primary font-medium' }}">
                    Katalog
                </a>
                <a href="{{ route('dampak') }}"
                   class="inline-flex items-center h-[72px] border-b-2 transition-colors {{ $isDampak ? 'border-primary text-primary font-semibold' : 'border-transparent text-teks/80 hover:text-primary font-medium' }}">
                    Dampak
                </a>
                <a href="{{ route('unboxing') }}"
                   class="inline-flex items-center h-[72px] border-b-2 transition-colors {{ $isUnboxing ? 'border-primary text-primary font-semibold' : 'border-transparent text-teks/80 hover:text-primary font-medium' }}">
                    Unboxing
                </a>
                <a href="{{ route('cara-donasi') }}"
                   class="inline-flex items-center h-[72px] border-b-2 transition-colors {{ $isCaraDonasi ? 'border-primary text-primary font-semibold' : 'border-transparent text-teks/80 hover:text-primary font-medium' }}">
                    Cara Donasi
                </a>
                <a href="{{ route('kontak') }}"
                   class="inline-flex items-center h-[72px] border-b-2 transition-colors {{ $isKontak ? 'border-primary text-primary font-semibold' : 'border-transparent text-teks/80 hover:text-primary font-medium' }}">
                    Kontak
                </a>
            </nav>

            {{-- Right Action: Tombol Donasikan Barang (Kanan) --}}
            <div class="hidden lg:flex items-center">
                <button type="button"
                        onclick="openDonationModal()"
                        class="inline-flex items-center justify-center px-5 h-[44px] rounded-[8px] bg-primary hover:bg-primary/90 text-white text-sm font-semibold transition-all active:scale-95 shadow-xs focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-primary">
                    Donasikan Barang
                </button>
            </div>

            {{-- Mobile Menu Button --}}
            <div class="flex items-center lg:hidden">
                <button type="button"
                        id="mobile-menu-toggle"
                        aria-label="Menu Navigasi"
                        aria-expanded="false"
                        class="p-2 rounded-lg text-teks hover:bg-pink-pucat transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                    <svg id="hamburger-icon" class="w-6 h-6 block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Mobile Drawer Menu --}}
    <div id="mobile-menu" class="hidden lg:hidden border-t border-teks/10 bg-krem shadow-lg transition-all">
        <div class="px-6 py-4 space-y-3">
            <nav class="flex flex-col space-y-1">
                <a href="{{ route('home') }}"
                   class="px-3 py-2.5 rounded-lg text-sm transition-colors {{ $isBeranda ? 'text-primary font-semibold bg-pink-pucat' : 'text-teks/80 hover:text-primary hover:bg-pink-pucat/50 font-medium' }}">
                    Beranda
                </a>
                <a href="{{ route('katalog') }}"
                   class="px-3 py-2.5 rounded-lg text-sm transition-colors {{ $isKatalog ? 'text-primary font-semibold bg-pink-pucat' : 'text-teks/80 hover:text-primary hover:bg-pink-pucat/50 font-medium' }}">
                    Katalog
                </a>
                <a href="{{ route('dampak') }}"
                   class="px-3 py-2.5 rounded-lg text-sm transition-colors {{ $isDampak ? 'text-primary font-semibold bg-pink-pucat' : 'text-teks/80 hover:text-primary hover:bg-pink-pucat/50 font-medium' }}">
                    Dampak
                </a>
                <a href="{{ route('unboxing') }}"
                   class="px-3 py-2.5 rounded-lg text-sm transition-colors {{ $isUnboxing ? 'text-primary font-semibold bg-pink-pucat' : 'text-teks/80 hover:text-primary hover:bg-pink-pucat/50 font-medium' }}">
                    Unboxing
                </a>
                <a href="{{ route('cara-donasi') }}"
                   class="px-3 py-2.5 rounded-lg text-sm transition-colors {{ $isCaraDonasi ? 'text-primary font-semibold bg-pink-pucat' : 'text-teks/80 hover:text-primary hover:bg-pink-pucat/50 font-medium' }}">
                    Cara Donasi
                </a>
                <a href="{{ route('kontak') }}"
                   class="px-3 py-2.5 rounded-lg text-sm transition-colors {{ $isKontak ? 'text-primary font-semibold bg-pink-pucat' : 'text-teks/80 hover:text-primary hover:bg-pink-pucat/50 font-medium' }}">
                    Kontak
                </a>
            </nav>

            <div class="pt-2 border-t border-teks/10">
                <button type="button"
                        onclick="openDonationModal()"
                        class="w-full h-[44px] flex items-center justify-center rounded-[8px] bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition-all active:scale-95 shadow-xs">
                    Donasikan Barang
                </button>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const closeIcon = document.getElementById('close-icon');

        if (toggleBtn && mobileMenu) {
            toggleBtn.addEventListener('click', () => {
                const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
                toggleBtn.setAttribute('aria-expanded', !isExpanded);
                mobileMenu.classList.toggle('hidden');
                if (hamburgerIcon && closeIcon) {
                    hamburgerIcon.classList.toggle('hidden');
                    closeIcon.classList.toggle('hidden');
                }
            });

            mobileMenu.querySelectorAll('a, button').forEach(item => {
                item.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    toggleBtn.setAttribute('aria-expanded', 'false');
                    if (hamburgerIcon && closeIcon) {
                        hamburgerIcon.classList.remove('hidden');
                        closeIcon.classList.add('hidden');
                    }
                });
            });
        }
    });
</script>
