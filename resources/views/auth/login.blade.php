<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin — Kios Mandiri YPKABK</title>
    <meta name="description" content="Masuk ke portal admin Kios Mandiri YPKABK.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FFFBF7] text-[#2B2430] font-sans antialiased flex flex-col justify-center items-center py-12 px-4 selection:bg-pink-pucat selection:text-primary">

    {{-- Main Container --}}
    <div class="w-full max-w-[420px] mx-auto">

        {{-- Login Card --}}
        <div class="bg-white border border-[#2B2430]/10 rounded-[24px] shadow-[0_12px_40px_-10px_rgba(43,36,48,0.08)] p-8 sm:p-10">

            {{-- Title --}}
            <h1 class="text-2xl sm:text-[28px] font-bold text-[#1F1924] text-center tracking-tight mb-8">
                Login Admin
            </h1>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs sm:text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs sm:text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                @csrf

                {{-- Email atau Nomor WhatsApp --}}
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-[#2B2430]">
                        Email atau Nomor WhatsApp
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="text"
                        inputmode="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="contoh@email.com atau 0812xxxxxxx"
                        class="w-full px-4 py-3 rounded-xl border bg-[#FFFBF7] text-sm text-[#2B2430] placeholder-[#A098A6] outline-none transition focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 @error('email') border-rose-400 focus:border-rose-500 @else border-[#E8E1D9] @enderror"
                    >
                    @error('email')
                        <p class="text-xs font-medium text-rose-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Kata Sandi --}}
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-[#2B2430]">
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi"
                            class="w-full pl-4 pr-16 py-3 rounded-xl border bg-[#FFFBF7] text-sm text-[#2B2430] placeholder-[#A098A6] outline-none transition focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 @error('password') border-rose-400 focus:border-rose-500 @else border-[#E8E1D9] @enderror"
                        >
                        <button
                            type="button"
                            id="toggle-password-btn"
                            onclick="togglePasswordVisibility()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-[#7A7280] hover:text-[#2B2430] font-medium transition-colors cursor-pointer select-none"
                        >
                            Lihat
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs font-medium text-rose-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- Lupa Kata Sandi --}}
                    <div class="text-right pt-1">
                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Kios%20Mandiri,%20saya%20butuh%20bantuan%20reset%20kata%20sandi%20login%20admin"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="text-xs sm:text-sm font-semibold text-primary hover:underline transition-colors">
                            Lupa kata sandi?
                        </a>
                    </div>
                </div>

                {{-- Ingat Saya --}}
                <div class="pt-1">
                    <label for="remember" class="inline-flex items-center gap-2.5 text-xs sm:text-sm text-[#2B2430]/80 cursor-pointer select-none">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            value="1"
                            @checked(old('remember'))
                            class="w-4 h-4 rounded border-[#D8D0C5] text-primary focus:ring-primary/30 cursor-pointer"
                        >
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                {{-- Tombol Masuk --}}
                <button
                    type="submit"
                    class="w-full mt-2 h-[46px] rounded-xl bg-primary hover:bg-primary/90 text-white font-semibold text-sm sm:text-base shadow-xs active:scale-[0.99] transition-all flex items-center justify-center cursor-pointer"
                >
                    Masuk
                </button>
            </form>
        </div>

        {{-- Footer / Navigasi --}}
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm text-[#2B2430]/80 hover:text-primary font-medium transition-colors">
                <span>&larr;</span>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

    </div>

    {{-- Script Toggle Password --}}
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('toggle-password-btn');

            if (!passwordInput || !toggleBtn) return;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = 'Sembunyikan';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = 'Lihat';
            }
        }
    </script>
</body>
</html>