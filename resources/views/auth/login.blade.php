<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk — Kios Mandiri YPKABK</title>
    <meta name="description" content="Masuk ke akun Kios Mandiri YPKABK untuk mengelola donasi dan pesanan.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FBF9F5] text-stone-800 font-sans antialiased selection:bg-rose-200 selection:text-rose-900">

    <div class="min-h-screen grid lg:grid-cols-2">

        {{-- Brand Panel --}}
        <aside class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-gradient-to-br from-rose-900 via-rose-800 to-teal-900 p-12 text-white">
            <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-rose-500/20 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-16 w-96 h-96 rounded-full bg-teal-400/20 blur-3xl"></div>

            <a href="{{ route('home') }}" class="relative flex items-center gap-3 group w-fit">
                <div class="w-11 h-11 rounded-2xl bg-white/10 border border-white/20 backdrop-blur p-0.5">
                    <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center">
                        <svg class="w-6 h-6 text-rose-700" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tight group-hover:text-rose-100 transition-colors">Kios Mandiri</span>
                    <span class="text-xs text-rose-200 font-medium">Pemberdayaan Vokasi Anak Istimewa</span>
                </div>
            </a>

            <div class="relative space-y-6 max-w-md">
                <span class="inline-flex items-center gap-1.5 bg-white/10 border border-white/20 text-rose-100 px-3 py-1 rounded-full text-[11px] font-semibold tracking-wide uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Aksi Nyata
                </span>
                <h1 class="text-4xl font-extrabold leading-tight tracking-tight">
                    Setiap transaksi<br>menciptakan peluang.
                </h1>
                <p class="text-rose-100/90 leading-relaxed">
                    100% margin penjualan dialokasikan untuk beasiswa terapi dan pelatihan vokasi anak istimewa binaan YPKABK.
                </p>
            </div>

            <div class="relative flex items-center gap-6 text-xs text-rose-200">
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Transparan
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2h5M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                    </svg>
                    Berdayakan
                </span>
                <span class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    Peduli
                </span>
            </div>
        </aside>

        {{-- Login Form --}}
        <main class="flex items-center justify-center px-4 py-12 sm:px-8">
            <div class="w-full max-w-md">

                {{-- Mobile Brand --}}
                <a href="{{ route('home') }}" class="lg:hidden flex items-center justify-center gap-3 mb-10">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-rose-600 to-teal-700 p-0.5">
                        <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center">
                            <svg class="w-5 h-5 text-rose-600" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </div>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-stone-900">Kios Mandiri</span>
                </a>

                <div class="bg-white border border-stone-200/80 rounded-3xl shadow-xl shadow-stone-900/5 p-8 sm:p-10">
                    <div class="space-y-2 mb-8">
                        <h2 class="text-2xl font-extrabold tracking-tight text-stone-900">Masuk ke akun Anda</h2>
                        <p class="text-sm text-stone-500">Kelola donasi, pesanan, dan aktivitas kios dalam satu tempat.</p>
                    </div>

                    {{-- Flash Messages --}}
                    @if (session('success'))
                        <div class="mb-5 flex items-start gap-2.5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div class="space-y-1.5">
                            <label for="email" class="block text-sm font-semibold text-stone-700">Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400 pointer-events-none">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </span>
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="nama@email.com"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border bg-stone-50/60 text-sm text-stone-900 placeholder-stone-400 outline-none transition focus:bg-white focus:ring-4 focus:ring-rose-100 @error('email') border-rose-400 focus:border-rose-500 @else border-stone-300 focus:border-rose-500 @enderror"
                                >
                            </div>
                            @error('email')
                                <p class="flex items-center gap-1.5 text-xs font-medium text-rose-600">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="space-y-1.5">
                            <label for="password" class="block text-sm font-semibold text-stone-700">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400 pointer-events-none">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </span>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="••••••••"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border bg-stone-50/60 text-sm text-stone-900 placeholder-stone-400 outline-none transition focus:bg-white focus:ring-4 focus:ring-rose-100 @error('password') border-rose-400 focus:border-rose-500 @else border-stone-300 focus:border-rose-500 @enderror"
                                >
                            </div>
                            @error('password')
                                <p class="flex items-center gap-1.5 text-xs font-medium text-rose-600">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Remember --}}
                        <div class="flex items-center justify-between pt-1">
                            <label for="remember" class="inline-flex items-center gap-2 text-sm text-stone-600 cursor-pointer select-none">
                                <input
                                    id="remember"
                                    name="remember"
                                    type="checkbox"
                                    value="1"
                                    @checked(old('remember'))
                                    class="w-4 h-4 rounded border-stone-300 text-rose-600 focus:ring-rose-200 focus:ring-offset-0 cursor-pointer"
                                >
                                Ingat saya
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl bg-gradient-to-r from-rose-700 to-rose-600 hover:from-rose-800 hover:to-rose-700 text-white text-sm font-bold shadow-sm hover:shadow-md hover:shadow-rose-700/20 active:scale-[0.99] transition-all"
                        >
                            Masuk
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <p class="mt-6 text-center text-sm text-stone-500">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 font-semibold text-stone-700 hover:text-rose-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                        </svg>
                        Kembali ke beranda
                    </a>
                </p>
            </div>
        </main>
    </div>

</body>
</html>
