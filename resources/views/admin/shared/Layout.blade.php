<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title')@else{{ $title ?? $computedHeaderTitle }}@endif</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/Logo Kios.jpg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/Logo Kios.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-admin-bg font-sans antialiased text-teks">

    @php
        $computedHeaderTitle = $title ?? match(true) {
            request()->routeIs('admin.products.*') => 'Produk',
            request()->routeIs('admin.categories.*') => 'Kategori Produk',
            request()->routeIs('admin.purchases.*') => 'Pesanan',
            request()->routeIs('admin.donations.*') => 'Donasi Masuk',
            request()->routeIs('admin.reports.income') => 'Laporan Pemasukan',
            request()->routeIs('admin.reports.*') => 'Laporan Pengeluaran',
            request()->routeIs('admin.messages.*') => 'Pesan Masuk',
            request()->routeIs('admin.site-content.*') => 'Konten Situs',
            request()->routeIs('admin.reviews.*') => 'Ulasan & Testimoni',
            request()->routeIs('admin.unboxing.*') => 'Galeri Unboxing',
            request()->routeIs('admin.dashboard') => 'Dashboard',
            default => 'Dashboard'
        };

        $userName = auth()->user()->name ?? 'Admin';
        $nameParts = preg_split('/\s+/', trim($userName)) ?: [];
        if (count($nameParts) >= 2) {
            $userInitials = strtoupper(mb_substr($nameParts[0], 0, 1) . mb_substr(end($nameParts), 0, 1));
        } else {
            $userInitials = strtoupper(mb_substr($userName, 0, min(2, mb_strlen($userName))));
        }

        $isDashboard = request()->routeIs('admin.dashboard');
        $isProducts = request()->routeIs('admin.products.*');
        $isPurchases = request()->routeIs('admin.purchases.*');
        $isDonations = request()->routeIs('admin.donations.*');

        $unboxingHasRoute = Route::has('admin.unboxing.index');
        $unboxingUrl = $unboxingHasRoute ? route('admin.unboxing.index') : '#';
        $isUnboxing = $unboxingHasRoute && request()->routeIs('admin.unboxing.*');

        $reportsHasRoute = Route::has('admin.reports.index');
        $reportsUrl = $reportsHasRoute ? route('admin.reports.index') : '#';
        $isReports = $reportsHasRoute && request()->routeIs('admin.reports.*');

        $messagesHasRoute = Route::has('admin.messages.index');
        $messagesUrl = $messagesHasRoute ? route('admin.messages.index') : '#';
        $isMessages = $messagesHasRoute && request()->routeIs('admin.messages.*');
        $newMessagesCount = $messagesHasRoute && \Illuminate\Support\Facades\Schema::hasTable('contact_messages')
            ? \App\Models\ContactMessage::where('status', 'new')->count()
            : 0;

        $isSiteContent = request()->routeIs('admin.site-content.*');
        $isReviews = request()->routeIs('admin.reviews.*');
        $isPaymentSettings = request()->routeIs('admin.payment-settings.*');
    @endphp

    {{-- Mobile Sidebar Backdrop --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black/40 z-40 lg:hidden hidden transition-opacity duration-200"
         onclick="toggleAdminSidebar(false)"
         aria-hidden="true"></div>

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar (Tinggi penuh h-screen, fixed/sticky, tidak ikut bergulir) --}}
        <aside id="admin-sidebar"
               class="fixed lg:sticky top-0 inset-y-0 left-0 z-50 w-64 h-screen bg-admin-sidebar border-r border-admin-border flex flex-col shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out shadow-lg lg:shadow-none"
               aria-label="Sidebar Admin">

            {{-- Brand / Logo --}}
            <div class="px-8 pt-7 pb-6 flex items-start justify-between shrink-0">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex flex-col group rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40">
                    <span class="text-xl font-bold tracking-tight text-primary leading-tight">Kios Mandiri</span>
                    <span class="text-xs font-medium text-admin-muted mt-0.5 tracking-normal">YPKABK Admin Panel</span>
                </a>

                {{-- Close Button for Mobile Drawer --}}
                <button type="button"
                        onclick="toggleAdminSidebar(false)"
                        class="lg:hidden p-1.5 rounded-lg text-admin-muted hover:text-teks hover:bg-white/60 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
                        aria-label="Tutup menu navigasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav aria-label="Menu admin" class="flex-1 px-4 space-y-4 overflow-y-auto text-sm">
                {{-- Dashboard --}}
                <div>
                    <a href="{{ route('admin.dashboard') }}"
                       @if($isDashboard) aria-current="page" @endif
                       class="block px-3.5 py-2 rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 {{ $isDashboard ? 'bg-primary text-white font-semibold' : 'text-teks hover:text-primary hover:bg-white/60' }}">
                        Dashboard
                    </a>
                </div>

                {{-- OPERASIONAL --}}
                <div class="space-y-1" aria-label="Menu Operasional">
                    <p class="px-3.5 text-[11px] font-semibold uppercase tracking-wider text-admin-muted">Operasional</p>

                    {{-- Pesanan --}}
                    <a href="{{ route('admin.purchases.index') }}"
                       @if($isPurchases) aria-current="page" @endif
                       class="block px-3.5 py-2 rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 {{ $isPurchases ? 'bg-primary text-white font-semibold' : 'text-teks hover:text-primary hover:bg-white/60' }}">
                        Pesanan
                    </a>

                    {{-- Donasi Masuk --}}
                    <a href="{{ route('admin.donations.index') }}"
                       @if($isDonations) aria-current="page" @endif
                       class="block px-3.5 py-2 rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 {{ $isDonations ? 'bg-primary text-white font-semibold' : 'text-teks hover:text-primary hover:bg-white/60' }}">
                        Donasi Masuk
                    </a>

                    {{-- Metode Pembayaran --}}
                    <a href="{{ route('admin.payment-settings.index') }}"
                       @if($isPaymentSettings) aria-current="page" @endif
                       class="block px-3.5 py-2 rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 {{ $isPaymentSettings ? 'bg-primary text-white font-semibold' : 'text-teks hover:text-primary hover:bg-white/60' }}">
                        Metode Pembayaran
                    </a>

                    {{-- Laporan --}}
                    <a href="{{ $reportsUrl }}"
                       @if(!$reportsHasRoute) aria-disabled="true" @endif
                       @if($isReports) aria-current="page" @endif
                       class="block px-3.5 py-2 rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 {{ $isReports ? 'bg-primary text-white font-semibold' : 'text-teks hover:text-primary hover:bg-white/60' }}">
                        Laporan
                    </a>
                </div>

                {{-- KATALOG & KONTEN --}}
                <div class="space-y-1" aria-label="Menu Katalog & Konten">
                    <p class="px-3.5 text-[11px] font-semibold uppercase tracking-wider text-admin-muted">Katalog & Konten</p>

                    {{-- Produk --}}
                    <a href="{{ route('admin.products.index') }}"
                       @if($isProducts) aria-current="page" @endif
                       class="block px-3.5 py-2 rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 {{ $isProducts ? 'bg-primary text-white font-semibold' : 'text-teks hover:text-primary hover:bg-white/60' }}">
                        Produk
                    </a>

                    {{-- Galeri Unboxing --}}
                    <a href="{{ $unboxingUrl }}"
                       @if(!$unboxingHasRoute) aria-disabled="true" @endif
                       @if($isUnboxing) aria-current="page" @endif
                       class="block px-3.5 py-2 rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 {{ $isUnboxing ? 'bg-primary text-white font-semibold' : 'text-teks hover:text-primary hover:bg-white/60' }}">
                        Galeri Unboxing
                    </a>

                    {{-- Konten Situs --}}
                    <a href="{{ route('admin.site-content.edit') }}"
                       @if($isSiteContent) aria-current="page" @endif
                       class="block px-3.5 py-2 rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 {{ $isSiteContent ? 'bg-primary text-white font-semibold' : 'text-teks hover:text-primary hover:bg-white/60' }}">
                        Konten Situs
                    </a>
                </div>

                {{-- KOMUNIKASI --}}
                <div class="space-y-1" aria-label="Menu Komunikasi">
                    <p class="px-3.5 text-[11px] font-semibold uppercase tracking-wider text-admin-muted">Komunikasi</p>

                    {{-- Pesan Masuk --}}
                    <a href="{{ $messagesUrl }}"
                       @if(!$messagesHasRoute) aria-disabled="true" @endif
                       @if($isMessages) aria-current="page" @endif
                       class="flex items-center justify-between px-3.5 py-2 rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 {{ $isMessages ? 'bg-primary text-white font-semibold' : 'text-teks hover:text-primary hover:bg-white/60' }}">
                        <span>Pesan Masuk</span>
                        @if($newMessagesCount > 0 && !$isMessages)
                            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold rounded-full bg-primary text-white">
                                {{ $newMessagesCount > 99 ? '99+' : $newMessagesCount }}
                            </span>
                        @endif
                    </a>

                    {{-- Ulasan & Testimoni --}}
                    <a href="{{ route('admin.reviews.index') }}"
                       @if($isReviews) aria-current="page" @endif
                       class="block px-3.5 py-2 rounded-lg font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 {{ $isReviews ? 'bg-primary text-white font-semibold' : 'text-teks hover:text-primary hover:bg-white/60' }}">
                        Ulasan & Testimoni
                    </a>
                </div>
            </nav>

            {{-- Sidebar Footer: Pemisah tipis 1px di atas "Keluar" --}}
            <div class="border-t border-admin-border px-8 py-5 mt-auto shrink-0">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-sm font-medium text-teks hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 cursor-pointer">
                        Keluar
                    </button>
                </form>
            </div>

        </aside>

        {{-- Main Area Container --}}
        <div class="flex-1 flex flex-col h-screen min-w-0 bg-admin-bg overflow-hidden">

            {{-- Top Bar (Sticky di atas, tinggi h-16, latar lebih terang/putih, border bawah tipis 1px) --}}
            <header class="h-16 bg-admin-topbar border-b border-admin-border flex items-center justify-between px-6 lg:px-10 shrink-0 z-10">
                <div class="flex items-center gap-3">
                    {{-- Hamburger button for mobile --}}
                    <button type="button"
                            id="admin-sidebar-toggle"
                            onclick="toggleAdminSidebar(true)"
                            class="lg:hidden p-2 rounded-xl text-teks hover:bg-admin-bg focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40"
                            aria-label="Buka menu navigasi"
                            aria-expanded="false"
                            aria-controls="admin-sidebar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    {{-- Breadcrumb --}}
                    <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs sm:text-sm font-medium text-teks">
                        <span class="text-admin-muted">Kios Mandiri YPKABK</span>
                        <span class="text-admin-muted/60" aria-hidden="true">/</span>
                        <span class="font-semibold text-teks">{{ $computedHeaderTitle }}</span>
                    </nav>
                </div>

                {{-- Topbar Actions --}}
                <div class="flex items-center gap-4 sm:gap-6">
                    <a href="{{ route('home') }}"
                       target="_blank"
                       rel="noopener"
                       class="text-xs sm:text-sm font-medium text-teks hover:text-primary transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40">
                        Lihat Website
                    </a>

                    <div class="flex items-center gap-3">
                        <span class="text-xs sm:text-sm font-semibold text-teks">
                            {{ $userName }}
                        </span>
                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-xs sm:text-sm tracking-wide shrink-0 select-none shadow-xs"
                             aria-label="Inisial profil: {{ $userInitials }}">
                            {{ $userInitials }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Area Konten (Hanya area ini yang bergulir, latar pink sangat muda) --}}
            <div class="flex-1 overflow-y-auto bg-admin-bg">

                {{-- Flash Messages (Notifikasi Terpusat) --}}
                @if (session('success') || session('error'))
                    <div class="px-6 lg:px-10 pt-6">
                        @if (session('success'))
                            <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-lg text-sm flex items-center justify-between gap-3 shadow-xs" role="alert">
                                <span class="font-medium">{{ session('success') }}</span>
                                <button type="button" onclick="this.closest('[role=alert]').remove()" class="text-emerald-700 hover:text-emerald-950 p-1 text-base leading-none font-bold cursor-pointer" aria-label="Tutup notifikasi">&times;</button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 px-4 py-3 bg-rose-50 border border-rose-200 text-rose-900 rounded-lg text-sm flex items-center justify-between gap-3 shadow-xs" role="alert">
                                <span class="font-medium">{{ session('error') }}</span>
                                <button type="button" onclick="this.closest('[role=alert]').remove()" class="text-rose-700 hover:text-rose-950 p-1 text-base leading-none font-bold cursor-pointer" aria-label="Tutup notifikasi">&times;</button>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Page Content --}}
                <main class="px-6 lg:px-10 py-6">
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </main>

            </div>

        </div>

    </div>

    {{-- Script toggle sidebar mobile --}}
    <script>
        function toggleAdminSidebar(show) {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggleBtn = document.getElementById('admin-sidebar-toggle');
            if (!sidebar || !overlay) return;
            if (show) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
            }
        }

        // Close mobile drawer on Escape key press
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const sidebar = document.getElementById('admin-sidebar');
                if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                    toggleAdminSidebar(false);
                }
            }
        });
    </script>
</body>
</html>
