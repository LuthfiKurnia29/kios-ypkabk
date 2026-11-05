{{-- Reusable User Navbar Component --}}
<header id="main-header" class="fixed top-0 inset-x-0 z-50 bg-white border-b border-ink/10 shadow-sm transition-all">
    {{-- Main Navbar --}}
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Brand Logo (Kiri) --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 group py-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-sun rounded-xl">
                <img src="{{ asset('images/logo/logo.webp') }}" alt="Logo Kios Mandiri YPKABK" width="2437" height="1186" class="h-11 sm:h-12 lg:h-14 w-auto object-contain transition-transform group-hover:scale-[1.02]">
            </a>

            {{-- Desktop Navigation Links (Tengah - Maksimal 5 Item Sesuai DESIGN.md & SRS) --}}
            <div class="hidden lg:flex items-center gap-1 xl:gap-2 text-base font-medium">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center h-20 px-3.5 border-b-2 transition-all {{ request()->routeIs('home') ? 'border-sun font-bold text-ink' : 'border-transparent text-ink/80 hover:text-ink hover:border-sun' }}">
                    Beranda
                </a>
                <a href="{{ route('katalog') }}"
                   class="inline-flex items-center h-20 px-3.5 border-b-2 transition-all {{ request()->routeIs('katalog') ? 'border-sun font-bold text-ink' : 'border-transparent text-ink/80 hover:text-ink hover:border-sun' }}">
                    Katalog
                </a>
                <a href="{{ route('tentang') }}"
                   class="inline-flex items-center h-20 px-3.5 border-b-2 transition-all {{ request()->routeIs('tentang') ? 'border-sun font-bold text-ink' : 'border-transparent text-ink/80 hover:text-ink hover:border-sun' }}">
                    Tentang Kami
                </a>
                <a href="{{ route('transparansi') }}"
                   class="inline-flex items-center h-20 px-3.5 border-b-2 transition-all {{ request()->routeIs('transparansi') ? 'border-sun font-bold text-ink' : 'border-transparent text-ink/80 hover:text-ink hover:border-sun' }}">
                    Transparansi
                </a>
                <a href="{{ route('home') }}#kontak"
                   class="inline-flex items-center h-20 px-3.5 border-b-2 border-transparent text-ink/80 hover:text-ink hover:border-sun transition-all">
                    Kontak
                </a>
            </div>

            {{-- Right Action: Keranjang & Tombol Utama (Kanan) --}}
            <div class="hidden lg:flex items-center gap-3">
                <a href="{{ route('keranjang') }}"
                   class="relative inline-flex items-center justify-center gap-2 min-h-[48px] px-4 py-2.5 rounded-full border border-ink/15 hover:bg-cream text-ink text-sm font-bold transition-all active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-sun"
                   aria-label="Keranjang Belanja">
                    <svg class="w-5 h-5 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Keranjang</span>
                    <span id="nav-cart-badge-desktop" class="hidden ml-0.5 px-2 py-0.5 rounded-full text-xs font-extrabold bg-[#D81B60] text-white">0</span>
                </a>

                <button type="button"
                        onclick="openDonationModal()"
                        class="inline-flex items-center justify-center gap-2 px-6 min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink text-base font-bold transition-all duration-200 active:scale-95 shadow-sm hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-sun">
                    <svg class="w-5 h-5 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>Donasikan Barang</span>
                </button>
            </div>

            {{-- Mobile Actions: Cart & Menu Toggle --}}
            <div class="flex items-center gap-2 lg:hidden">
                <a href="{{ route('keranjang') }}"
                   class="relative p-2.5 rounded-xl text-ink hover:bg-cream transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-sun"
                   aria-label="Keranjang Belanja">
                    <svg class="w-6 h-6 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span id="nav-cart-badge-mobile-icon" class="hidden absolute top-1.5 right-1.5 bg-[#D81B60] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">0</span>
                </a>

                <button type="button"
                        id="mobile-menu-toggle"
                        aria-label="Toggle navigation menu"
                        aria-expanded="false"
                        class="p-2.5 rounded-xl text-ink hover:bg-cream transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-sun">
                    {{-- Hamburger Icon --}}
                    <svg id="hamburger-icon" class="w-6 h-6 block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    {{-- Close Icon --}}
                    <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </nav>

    {{-- Mobile Drawer Menu (Solid White, Rapi, Aksesibel) --}}
    <div id="mobile-menu" class="hidden lg:hidden border-t border-ink/10 bg-white shadow-xl transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 space-y-3">
            <nav class="flex flex-col space-y-1">
                <a href="{{ route('home') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('home') ? 'bg-cream text-ink font-bold' : 'text-ink/80 hover:bg-cream hover:text-ink' }}">
                    <span>Beranda</span>
                    <svg class="w-4 h-4 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('katalog') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('katalog') ? 'bg-cream text-ink font-bold' : 'text-ink/80 hover:bg-cream hover:text-ink' }}">
                    <span>Katalog</span>
                    <svg class="w-4 h-4 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('tentang') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('tentang') ? 'bg-cream text-ink font-bold' : 'text-ink/80 hover:bg-cream hover:text-ink' }}">
                    <span>Tentang Kami</span>
                    <svg class="w-4 h-4 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('transparansi') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('transparansi') ? 'bg-cream text-ink font-bold' : 'text-ink/80 hover:bg-cream hover:text-ink' }}">
                    <span>Transparansi</span>
                    <svg class="w-4 h-4 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('home') }}#kontak"
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-base font-medium text-ink/80 hover:bg-cream hover:text-ink transition-colors">
                    <span>Kontak</span>
                    <svg class="w-4 h-4 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('keranjang') }}"
                   class="flex items-center justify-between px-4 py-3 rounded-xl text-base font-medium transition-colors {{ request()->routeIs('keranjang') ? 'bg-cream text-ink font-bold' : 'text-ink/80 hover:bg-cream hover:text-ink' }}">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span>Keranjang Belanja</span>
                    </span>
                    <span id="nav-cart-badge-mobile-drawer" class="hidden px-2 py-0.5 rounded-full text-xs font-bold bg-[#D81B60] text-white">0</span>
                </a>
            </nav>

            <div class="pt-3 border-t border-ink/10">
                <button type="button"
                        onclick="openDonationModal()"
                        class="w-full py-3.5 min-h-[48px] flex items-center justify-center gap-2 rounded-full bg-sun hover:bg-sun/90 text-ink font-bold text-base transition-all active:scale-95 shadow-sm">
                    <svg class="w-5 h-5 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>Donasikan Barang</span>
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
