<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin — ' . config('app.name', 'Kios Mandiri YPKABK') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-100 font-sans antialiased text-[#2B2430]">

    {{-- Mobile Sidebar Backdrop --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 lg:hidden hidden transition-opacity" onclick="toggleAdminSidebar(false)"></div>

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside id="admin-sidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-[#1F1924] text-gray-100 flex flex-col shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-200 ease-in-out">

            {{-- Brand --}}
            <div class="h-16 flex items-center justify-between px-5 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 text-base font-bold tracking-wide text-white hover:opacity-90 transition-opacity">
                    <span class="w-8 h-8 rounded-xl bg-primary flex items-center justify-center text-white text-xs font-black shadow-sm shrink-0">
                        KM
                    </span>
                    <div class="flex flex-col min-w-0">
                        <span class="leading-tight truncate text-sm font-bold">{{ config('app.name', 'Kios Mandiri') }}</span>
                        <span class="text-[10px] font-medium text-gray-400 uppercase tracking-wider">Admin Panel</span>
                    </div>
                </a>

                {{-- Close Button for Mobile --}}
                <button type="button" onclick="toggleAdminSidebar(false)" class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 focus:outline-none" aria-label="Tutup Sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 py-5 space-y-1.5 overflow-y-auto text-sm">
                
                {{-- 1. Dashboard --}}
                @php
                    $isDashboardActive = request()->routeIs('admin.dashboard');
                @endphp
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition-colors {{ $isDashboardActive ? 'bg-primary text-white shadow-sm font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0 {{ $isDashboardActive ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                {{-- 2. Kelola Produk --}}
                @php
                    $isProductsActive = request()->routeIs('admin.products.*');
                @endphp
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition-colors {{ $isProductsActive ? 'bg-primary text-white shadow-sm font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0 {{ $isProductsActive ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>Kelola Produk</span>
                </a>

                {{-- 3. Pesanan & Transaksi --}}
                @php
                    $isPurchasesActive = request()->routeIs('admin.purchases.*');
                @endphp
                <a href="{{ route('admin.purchases.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition-colors {{ $isPurchasesActive ? 'bg-primary text-white shadow-sm font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0 {{ $isPurchasesActive ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Pesanan & Transaksi</span>
                </a>

                {{-- 3. Donasi Preloved Masuk --}}
                @php
                    $isDonationsActive = request()->routeIs('admin.donations.*');
                @endphp
                <a href="{{ route('admin.donations.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition-colors {{ $isDonationsActive ? 'bg-primary text-white shadow-sm font-semibold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-4 h-4 shrink-0 {{ $isDonationsActive ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                    <span>Donasi Masuk</span>
                </a>

                {{-- Divider --}}
                <div class="pt-3 pb-1 px-3">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Akses Cepat Pengunjung</p>
                </div>

                {{-- 4. Katalog Produk --}}
                @php
                    $isKatalogActive = request()->routeIs('katalog') || request()->routeIs('products.*');
                @endphp
                <a href="{{ route('katalog') }}" target="_blank"
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium transition-colors {{ $isKatalogActive ? 'bg-white/10 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <span>Katalog Produk</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>

                {{-- 5. Lihat Web Kios --}}
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium transition-colors text-gray-300 hover:bg-white/10 hover:text-white">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                        <span>Lihat Web Kios</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>

            </nav>

            {{-- Sidebar Footer --}}
            <div class="px-4 py-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-gray-300 hover:bg-red-500/10 hover:text-red-400 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                        </svg>
                        <span>Logout Keluar</span>
                    </button>
                </form>
            </div>

        </aside>

        {{-- Main Area --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">

            {{-- Top Bar --}}
            @php
                $computedHeaderTitle = $title ?? match(true) {
                    request()->routeIs('admin.products.*') => 'Kelola Produk',
                    request()->routeIs('admin.purchases.*') => 'Daftar Pesanan & Transaksi',
                    request()->routeIs('admin.donations.*') => 'Daftar Donasi Preloved Masuk',
                    request()->routeIs('admin.dashboard') => 'Dashboard Ringkasan',
                    default => 'Panel Admin'
                };
            @endphp
            <header class="h-16 bg-white shadow-sm border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 shrink-0 z-10">
                <div class="flex items-center gap-3">
                    {{-- Hamburger button for mobile --}}
                    <button type="button" onclick="toggleAdminSidebar(true)" class="lg:hidden p-2 rounded-xl text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:outline-none" aria-label="Buka Navigasi">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <h1 class="text-base sm:text-lg font-bold text-gray-800 tracking-tight">{{ $computedHeaderTitle }}</h1>
                </div>

                <div class="flex items-center gap-3 text-sm text-gray-700">
                    <span class="hidden sm:inline font-medium text-xs sm:text-sm">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <div class="w-9 h-9 rounded-xl bg-primary text-white flex items-center justify-center font-bold text-xs shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="px-4 sm:px-6 pt-4">
                @if (session('success'))
                    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 px-4 py-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-4">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>

        </div>

    </div>

    {{-- Script toggle sidebar mobile --}}
    <script>
        function toggleAdminSidebar(show) {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar || !overlay) return;
            if (show) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }
    </script>
</body>
</html>
