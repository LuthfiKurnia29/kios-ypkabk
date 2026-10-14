<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin — ' . config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar --}}
        <aside class="w-64 bg-gray-900 text-gray-100 flex flex-col shrink-0">

            {{-- Brand --}}
            <div class="h-16 flex items-center px-6 border-b border-gray-700">
                <a href="#" class="text-lg font-bold tracking-wide text-white">
                    {{ config('app.name') }} Admin
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto text-sm">
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition-colors {{ request()->is('admin/dashboard') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition-colors {{ request()->is('admin/produk*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                    Produk
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition-colors {{ request()->is('admin/transaksi*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Transaksi
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800 transition-colors {{ request()->is('admin/pengguna*') ? 'bg-gray-800 text-white' : 'text-gray-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                    Pengguna
                </a>
            </nav>

            {{-- Sidebar Footer --}}
            <div class="px-4 py-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-red-400 transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        {{-- Main Area --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Top Bar --}}
            <header class="h-16 bg-white shadow-sm border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
                <h1 class="text-base font-semibold text-gray-700">{{ $title ?? 'Dashboard' }}</h1>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <span>{{ auth()->user()->name ?? 'Admin' }}</span>
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-xs">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="px-6 pt-4">
                @if (session('success'))
                    <div class="mb-4 px-4 py-3 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 px-4 py-3 bg-red-100 border border-red-300 text-red-800 rounded-lg text-sm">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto px-6 py-4">
                {{ $slot }}
            </main>

        </div>

    </div>

</body>
</html>
