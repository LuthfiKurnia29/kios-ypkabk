@extends('user.shared.Layout')

@section('content')
<div class="w-full bg-[#FFFBF7] pt-[96px] pb-16 lg:pt-[112px] lg:pb-24">
    <div class="max-w-[600px] mx-auto px-6">

        @if(isset($state) && $state === 'invalid')
            {{-- STATE: TAUTAN TIDAK VALID --}}
            <div class="bg-white rounded-2xl p-8 sm:p-10 border border-[#2B2430]/10 shadow-xs text-center space-y-5">
                <div class="w-14 h-14 rounded-full bg-rose-50 text-rose-600 mx-auto flex items-center justify-center border border-rose-200">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h1 class="text-xl sm:text-2xl font-bold text-[#2B2430]">Tautan Tidak Valid</h1>
                    <p class="text-xs sm:text-sm text-[#2B2430]/75 max-w-md mx-auto leading-relaxed">
                        Tautan ulasan ini tidak ditemukan atau transaksi Anda belum selesai. Pastikan Anda membuka tautan resmi yang dikirimkan oleh tim Kios Mandiri YPKABK melalui WhatsApp setelah transaksi diselesaikan.
                    </p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary/90 transition shadow-xs">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

        @elseif(isset($state) && in_array($state, ['already_reviewed', 'submitted'], true))
            {{-- STATE: SUDAH DIULAS / TERIMA KASIH --}}
            <div class="bg-white rounded-2xl p-8 sm:p-10 border border-[#2B2430]/10 shadow-xs text-center space-y-5">
                <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center border border-emerald-200">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h1 class="text-xl sm:text-2xl font-bold text-[#2B2430]">Terima kasih, ulasan Anda sudah kami terima.</h1>
                    <p class="text-xs sm:text-sm text-[#2B2430]/75 max-w-md mx-auto leading-relaxed">
                        Pengalaman dan cerita Anda sangat berharga untuk memotivasi kemandirian adik-adik berkebutuhan khusus. Ulasan Anda akan ditinjau oleh admin sebelum ditampilkan di halaman beranda.
                    </p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary/90 transition shadow-xs">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

        @else
            {{-- STATE: FORMULIR ULASAN --}}
            {{-- Header Section --}}
            <div class="text-center space-y-2 mb-8">
                <span class="text-xs uppercase tracking-wider font-semibold text-primary">
                    Cerita Sahabat Kios
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold text-[#2B2430] tracking-tight">
                    {{ ($source ?? 'buyer') === 'buyer' ? 'Ceritakan Pengalaman Berbelanja Anda' : 'Ceritakan Pengalaman Berdonasi Anda' }}
                </h1>
                <p class="text-xs sm:text-sm text-[#2B2430]/75 max-w-md mx-auto leading-relaxed">
                    {{ ($source ?? 'buyer') === 'buyer'
                        ? 'Pengalaman berbelanja karya kriya adik-adik disabilitas sangat berarti bagi apresiasi hasil karya mereka.'
                        : 'Pengalaman menyalurkan barang donasi preloved menjadi inspirasi transparansi bagi masyarakat luas.' }}
                </p>
            </div>

            {{-- Card Form --}}
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-[#2B2430]/10 shadow-xs space-y-6">

                <form method="POST" action="{{ route('reviews.submit', $token) }}" class="space-y-5">
                    @csrf

                    {{-- Kolom 1: Nama Tampilan (Pre-filled dari data transaksi, boleh diubah) --}}
                    <div>
                        <label for="name" class="block text-xs font-semibold text-[#2B2430] mb-1">
                            Nama Anda / Nama Tampilan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required maxlength="100"
                            value="{{ old('name', $defaultName ?? '') }}"
                            placeholder="Contoh: Rina Wardhani"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#2B2430]/20 text-xs sm:text-sm text-[#2B2430] focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition font-medium">
                        <p class="text-[11px] text-[#2B2430]/60 mt-1">Demi privasi, di beranda publik hanya akan ditampilkan nama depan dan inisial (mis. "Rina W.").</p>
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kolom 2: Pengalaman / Ulasan (Maks 300 karakter, dengan penghitung) --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="quote" class="text-xs font-semibold text-[#2B2430]">
                                Pengalaman / Ulasan Anda <span class="text-rose-500">*</span>
                            </label>
                            <span id="char-counter" class="text-[11px] text-[#2B2430]/50 font-mono">0 / 300</span>
                        </div>
                        <textarea id="quote" name="quote" rows="4" required maxlength="300"
                            oninput="updateCharCount(this)"
                            placeholder="{{ ($source ?? 'buyer') === 'buyer' ? 'Ceritakan kepuasan berbelanja produk kriya karya adik-adik...' : 'Ceritakan kemudahan dan kejelasan proses penyaluran donasi barang Anda...' }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#2B2430]/20 text-xs sm:text-sm text-[#2B2430] focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none">{{ old('quote') }}</textarea>
                        @error('quote')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Persetujuan Tampil --}}
                    <div class="pt-1">
                        <label class="flex items-start gap-2.5 cursor-pointer text-xs text-[#2B2430]/80">
                            <input type="checkbox" name="agree_terms" value="1" required
                                {{ old('agree_terms') ? 'checked' : '' }}
                                class="w-4 h-4 mt-0.5 rounded border-[#2B2430]/20 text-primary focus:ring-primary/30 cursor-pointer">
                            <span>Saya menyatakan bahwa ulasan ini adalah pengalaman nyata dan menyetujui ulasan ini untuk ditampilkan secara publik setelah diverifikasi oleh admin.</span>
                        </label>
                        @error('agree_terms')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full h-[46px] rounded-xl bg-primary hover:bg-primary/90 text-white font-bold text-sm shadow-xs active:scale-[0.99] transition-all flex items-center justify-center cursor-pointer">
                            Kirim Ulasan Saya
                        </button>
                    </div>
                </form>

            </div>
        @endif

    </div>
</div>

<script>
    function updateCharCount(el) {
        const counter = document.getElementById('char-counter');
        if (counter) {
            counter.textContent = el.value.length + ' / 300';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const quoteEl = document.getElementById('quote');
        if (quoteEl) updateCharCount(quoteEl);
    });
</script>
@endsection
