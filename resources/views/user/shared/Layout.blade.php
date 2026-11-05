<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Kios Mandiri YPKABK' }}</title>

    <meta name="description" content="Kios Mandiri mengelola donasi preloved layak pakai dan karya kreatif anak berkebutuhan khusus binaan Yayasan Peduli Kasih ABK (YPKABK) untuk membiayai ruang terapi gratis dan pelatihan vokasi mandiri.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=baloo-2:400,600,700,800&family=nunito:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-cream text-ink font-sans antialiased flex flex-col selection:bg-sun selection:text-ink">

    {{-- Reusable Navigation Bar --}}
    @include('user.shared.navbar')

    {{-- Flash Notifications (If Any) --}}
    @if (session('success') || session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 w-full">
            @if (session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm text-sm">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center gap-3 p-4 bg-butter border border-ink/20 text-ink rounded-2xl shadow-sm text-sm">
                    <svg class="w-5 h-5 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>
    @endif

    {{-- Main Content --}}
    <main class="flex-grow w-full">
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>

    {{-- Reusable Footer --}}
    @include('user.shared.footer')

    {{-- Floating Quick WhatsApp Help --}}
    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Kios%20Mandiri%20YPKABK,%20saya%20ingin%20bertanya%20tentang%20donasi/katalog" 
       target="_blank" 
       rel="noopener noreferrer"
       class="fixed bottom-6 right-6 z-40 bg-emerald-600 hover:bg-emerald-700 text-white min-h-[48px] px-4 py-3 rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center gap-2 group focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-emerald-500"
       title="Hubungi Admin Kios via WhatsApp">
        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
        </svg>
        <span class="max-w-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs transition-all duration-300 ease-in-out text-sm font-semibold pr-1">
            Tanya Kios ABK
        </span>
    </a>

    {{-- Kios Floating Cart Toast Notification --}}
    <div id="kios-toast"
         class="fixed bottom-24 right-4 sm:right-6 z-50 hidden transition-all duration-300 transform translate-y-2 opacity-0"
         role="alert"
         aria-live="polite">
        <div class="bg-white border-2 border-sun rounded-2xl shadow-xl p-4 flex items-center gap-3 max-w-sm sm:max-w-md text-ink">
            <span class="text-2xl shrink-0" id="kios-toast-icon">🛍️</span>
            <div class="flex-1 min-w-0">
                <p id="kios-toast-title" class="font-bold text-xs uppercase tracking-wider text-ink/70">Keranjang Belanja</p>
                <p id="kios-toast-message" class="text-xs sm:text-sm font-semibold text-ink truncate"></p>
            </div>
            <a href="{{ route('keranjang') }}"
               class="px-3.5 py-2 bg-sun hover:bg-sun/90 text-ink text-xs font-bold rounded-xl shrink-0 transition-colors shadow-xs">
                Lihat
            </a>
        </div>
    </div>

    {{-- Global Cart Management Script (Guest Cart via localStorage) --}}
    <script>
        const KIOS_CART_KEY = 'kios_cart_items_v1';

        function getKiosCart() {
            try {
                return JSON.parse(localStorage.getItem(KIOS_CART_KEY)) || [];
            } catch (e) {
                return [];
            }
        }

        function saveKiosCart(cart) {
            localStorage.setItem(KIOS_CART_KEY, JSON.stringify(cart));
            updateAllCartBadges();
            window.dispatchEvent(new CustomEvent('kios-cart-updated', { detail: { cart } }));
        }

        function updateAllCartBadges() {
            const cart = getKiosCart();
            const totalCount = cart.reduce((sum, item) => sum + (parseInt(item.quantity, 10) || 1), 0);

            const desktopBadge = document.getElementById('nav-cart-badge-desktop');
            const mobileIconBadge = document.getElementById('nav-cart-badge-mobile-icon');
            const mobileDrawerBadge = document.getElementById('nav-cart-badge-mobile-drawer');

            if (desktopBadge) {
                if (totalCount > 0) {
                    desktopBadge.innerText = totalCount;
                    desktopBadge.classList.remove('hidden');
                } else {
                    desktopBadge.classList.add('hidden');
                }
            }

            if (mobileIconBadge) {
                if (totalCount > 0) {
                    mobileIconBadge.innerText = totalCount > 99 ? '99+' : totalCount;
                    mobileIconBadge.classList.remove('hidden');
                } else {
                    mobileIconBadge.classList.add('hidden');
                }
            }

            if (mobileDrawerBadge) {
                if (totalCount > 0) {
                    mobileDrawerBadge.innerText = totalCount;
                    mobileDrawerBadge.classList.remove('hidden');
                } else {
                    mobileDrawerBadge.classList.add('hidden');
                }
            }
        }

        let _toastTimer = null;
        function showKiosToast(title, message, icon = '🛍️') {
            const toast = document.getElementById('kios-toast');
            const titleEl = document.getElementById('kios-toast-title');
            const msgEl = document.getElementById('kios-toast-message');
            const iconEl = document.getElementById('kios-toast-icon');

            if (!toast || !msgEl) return;

            if (titleEl) titleEl.innerText = title;
            msgEl.innerText = message;
            if (iconEl) iconEl.innerText = icon;

            toast.classList.remove('hidden');
            // Trigger animation
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
                toast.classList.add('translate-y-0', 'opacity-100');
            });

            clearTimeout(_toastTimer);
            _toastTimer = setTimeout(() => {
                toast.classList.remove('translate-y-0', 'opacity-100');
                toast.classList.add('translate-y-2', 'opacity-0');
                setTimeout(() => toast.classList.add('hidden'), 300);
            }, 3500);
        }

        function addToCart(id, name, price, image, category = 'Produk Kios') {
            const cart = getKiosCart();
            const numericId = id ? parseInt(id, 10) : null;
            const numericPrice = typeof price === 'number' ? price : parseInt(String(price).replace(/[^0-9]/g, ''), 10) || 0;

            const existingIndex = cart.findIndex(item => (numericId && item.id === numericId) || item.name === name);

            if (existingIndex > -1) {
                cart[existingIndex].quantity = (parseInt(cart[existingIndex].quantity, 10) || 1) + 1;
            } else {
                cart.push({
                    id: numericId,
                    name: name,
                    price: numericPrice,
                    image: image || '/images/tote-bag.jpg',
                    category: category,
                    quantity: 1
                });
            }

            saveKiosCart(cart);
            showKiosToast('Ditambahkan ke Keranjang', name, '✅');
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateAllCartBadges();
        });
    </script>

</body>
</html>
