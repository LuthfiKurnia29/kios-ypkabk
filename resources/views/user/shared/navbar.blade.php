{{-- Reusable User Navbar Component --}}
<header id="main-header" class="fixed top-0 inset-x-0 z-50 bg-white border-b border-ink/10 shadow-sm">
    {{-- Main Navbar --}}
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Brand Logo (Kiri) --}}
            {{-- TODO: ganti dengan file logo asli dari yayasan (SVG) --}}
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('images/logo/logo.webp') }}" alt="Logo Kios Mandiri YPKABK" width="2437" height="1186" class="h-12 lg:h-14 w-auto">
            </a>

            {{-- Desktop Navigation Links (Tengah) --}}
            <div class="hidden lg:flex items-center gap-8 text-base font-medium">
                <a href="{{ route('home') }}#beranda"
                   class="{{ request()->routeIs('home') ? 'border-b-[3px] border-sun font-semibold' : 'hover:border-b-[3px] hover:border-sun' }} text-ink pb-1 transition-all">
                    Beranda
                </a>
                <a href="{{ route('katalog') }}"
                   class="{{ request()->routeIs('katalog') ? 'border-b-[3px] border-sun font-semibold' : 'hover:border-b-[3px] hover:border-sun' }} text-ink pb-1 transition-all">
                    Katalog
                </a>
                <a href="{{ route('home') }}#dampak"
                   class="text-ink hover:border-b-[3px] hover:border-sun pb-1 transition-all">
                    Dampak
                </a>
                <a href="{{ route('home') }}#cara-donasi"
                   class="text-ink hover:border-b-[3px] hover:border-sun pb-1 transition-all">
                    Cara Donasi
                </a>
                <a href="{{ route('home') }}#kontak"
                   class="text-ink hover:border-b-[3px] hover:border-sun pb-1 transition-all">
                    Kontak
                </a>
            </div>

            {{-- Right Action: Tombol bg-sun text-ink rounded-full min-h-[48px] (Kanan) --}}
            <div class="hidden lg:flex items-center">
                <button type="button"
                        onclick="openDonationModal()"
                        class="inline-flex items-center justify-center gap-2 px-6 min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink text-base font-bold transition-all active:scale-95 shadow-sm">
                    <svg class="w-4 h-4 text-ink" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span>Donasikan Barang</span>
                </button>
            </div>

            {{-- Mobile Menu Button --}}
            <div class="flex items-center gap-2 lg:hidden">
                <button type="button" id="mobile-menu-toggle" aria-label="Toggle navigation menu" class="p-2.5 rounded-full text-ink hover:bg-sun/30 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                </button>
            </div>

        </div>
    </nav>

    {{-- Mobile Drawer Menu (Solid bg-[#FBEDA8]) --}}
    <div id="mobile-menu" class="hidden lg:hidden border-t border-ink/10 bg-[#FBEDA8] px-4 pt-3 pb-6 space-y-3">
        <div class="space-y-1 text-base font-medium">
            <a href="{{ route('home') }}#beranda"
               class="block px-3 py-2.5 rounded-xl {{ request()->routeIs('home') ? 'bg-sun/40 font-bold' : '' }} text-ink">
                Beranda
            </a>
            <a href="{{ route('katalog') }}"
               class="block px-3 py-2.5 rounded-xl {{ request()->routeIs('katalog') ? 'bg-sun/40 font-bold' : '' }} text-ink">
                Katalog
            </a>
            <a href="{{ route('home') }}#dampak"
               class="block px-3 py-2.5 rounded-xl text-ink">
                Dampak
            </a>
            <a href="{{ route('home') }}#cara-donasi"
               class="block px-3 py-2.5 rounded-xl text-ink">
                Cara Donasi
            </a>
            <a href="{{ route('home') }}#kontak"
               class="block px-3 py-2.5 rounded-xl text-ink">
                Kontak
            </a>
        </div>
        <div class="pt-3 border-t border-ink/10">
            <button type="button"
                    onclick="openDonationModal()"
                    class="w-full text-center py-3 min-h-[48px] flex items-center justify-center gap-2 rounded-full bg-sun hover:bg-sun/90 text-ink font-bold transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Donasikan Barang</span>
            </button>
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
            mobileMenu.querySelectorAll('a, button').forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                });
            });
        }
    });
</script>
