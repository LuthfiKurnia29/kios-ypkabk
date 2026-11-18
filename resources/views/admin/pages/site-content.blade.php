@extends('admin.shared.Layout')

@section('title', 'Kelola Konten Situs')

@section('content')
<div class="space-y-8">

    {{-- Alert Flash Message Umum --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Pengaturan Konten Situs Publik</h2>
            <p class="text-sm text-gray-500 mt-0.5">Kelola banner Hero, data pembinaan ABK, dan pantau Laporan Keterbukaan real-time.</p>
        </div>
        <a href="{{ route('home') }}" target="_blank"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50 transition shadow-xs self-start sm:self-auto min-h-[44px]">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Lihat Beranda
        </a>
    </div>

    {{-- SEKSI 1: FORM PENGATURAN HERO --}}
    <form method="POST" action="{{ route('admin.site-content.update-hero') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-bold text-gray-900">1. Bagian Hero Beranda</h3>
                <p class="text-xs text-gray-500 mt-0.5">Atur foto utama dan teks ajakan di bagian paling atas halaman beranda.</p>
            </div>
            @if(session('success_hero'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success_hero') }}
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Pratinjau & Upload Foto --}}
            <div class="lg:col-span-5 space-y-3">
                <label class="block text-xs font-semibold text-gray-700">Foto Hero Saat Ini</label>
                <div class="rounded-xl border border-gray-200 p-2 bg-gray-50 flex flex-col items-center justify-center">
                    <img id="hero-preview" src="{{ $heroImageUrl }}" alt="Pratinjau Hero"
                        class="max-h-64 w-auto object-contain rounded-lg shadow-xs border border-gray-200">
                </div>

                <div>
                    <label for="hero_image" class="block text-xs font-semibold text-gray-700 mb-1">
                        Unggah Foto Baru <span class="text-gray-400 font-normal">(JPG, PNG, atau WebP, maks. 2 MB)</span>
                    </label>
                    <input type="file" id="hero_image" name="hero_image" accept="image/jpeg,image/png,image/webp"
                        onchange="previewHeroImage(this)"
                        class="w-full text-xs text-gray-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                    @error('hero_image')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hero_image_alt" class="block text-xs font-semibold text-gray-700 mb-1">
                        Teks Alternatif Foto (Alt Text)
                    </label>
                    <input type="text" id="hero_image_alt" name="hero_image_alt"
                        value="{{ old('hero_image_alt', $settings->get('hero_image_alt', 'Karya dan kegiatan anak binaan Kios Mandiri YPKABK')) }}"
                        class="w-full px-3 py-2 rounded-xl border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                    @error('hero_image_alt')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Teks Judul & Subjudul --}}
            <div class="lg:col-span-7 space-y-4">
                <div>
                    <label for="hero_title" class="block text-xs font-semibold text-gray-700 mb-1">
                        Judul Utama Hero
                    </label>
                    <p class="text-[11px] text-gray-500 mb-1.5">
                        Teks biasa. Tandai kata yang ingin diberi warna primer dengan tanda <span class="font-mono font-bold text-primary">*bintang*</span>, contoh: <code class="bg-gray-100 px-1 py-0.5 rounded text-[11px] text-gray-800">Barang preloved, *peluang baru* untuk anak berkebutuhan khusus.</code>
                    </p>
                    <textarea id="hero_title" name="hero_title" rows="3"
                        class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none">{{ old('hero_title', \App\Models\SiteSetting::cleanHeroTitle($settings->get('hero_title'))) }}</textarea>
                    @error('hero_title')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hero_subtitle" class="block text-xs font-semibold text-gray-700 mb-1">
                        Subjudul / Deskripsi Pendek Hero <span class="text-gray-400 font-normal">(Teks biasa)</span>
                    </label>
                    <textarea id="hero_subtitle" name="hero_subtitle" rows="4"
                        class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none">{{ old('hero_subtitle', $settings->get('hero_subtitle', 'Donasi barang layak pakai dan hasil karya adik-adik disabilitas kami kelola untuk operasional ruang terapi gratis dan pelatihan vokasi mandiri di Sidoarjo dan Mojokerto.')) }}</textarea>
                    @error('hero_subtitle')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit"
                class="px-6 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition shadow-sm min-h-[44px]">
                Simpan Hero
            </button>
        </div>
    </form>

    {{-- SEKSI 2: FORM JUMLAH ABK TERLATIH MANDIRI --}}
    <form method="POST" action="{{ route('admin.site-content.update-abk') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
        @csrf
        @method('PUT')

        <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-bold text-gray-900">2. Jumlah ABK Terlatih Mandiri</h3>
                <p class="text-xs text-gray-500 mt-0.5">Diisi manual oleh admin sesuai data binaan aktif yayasan yang telah mandiri.</p>
            </div>
            @if(session('success_abk'))
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success_abk') }}
                </span>
            @endif
        </div>

        @php
            $currentAbk = $settings->get('abk_trained_count');
            $isAbkEmpty = $currentAbk === null || (int)$currentAbk <= 0;
        @endphp

        @if($isAbkEmpty)
            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-center gap-2.5">
                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span class="font-semibold">Jumlah ABK belum diisi. Angka di beranda saat ini menampilkan 0 ABK.</span>
            </div>
        @endif

        <div class="max-w-xs space-y-2">
            <label for="abk_trained_count" class="block text-xs font-semibold text-gray-700">
                Jumlah Anak Binaan Mandiri (Orang) <span class="text-rose-500">*</span>
            </label>
            <div class="relative rounded-xl shadow-xs">
                <input type="number" step="1" min="0" id="abk_trained_count" name="abk_trained_count" required
                    value="{{ old('abk_trained_count', $currentAbk ?? 0) }}"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
            </div>
            <p class="text-[11px] text-gray-400">Tampil pada kartu kedua di seksi Jejak Kebaikan &amp; halaman /dampak.</p>
            @error('abk_trained_count')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-3 border-t border-gray-100 flex justify-end">
            <button type="submit"
                class="px-6 py-2.5 rounded-xl bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition shadow-sm min-h-[44px]">
                Simpan Jumlah ABK
            </button>
        </div>
    </form>

    {{-- SEKSI 3: RINGKASAN LAPORAN KETERBUKAAN (BACA-SAJA) --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
        <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-bold text-gray-900">3. Ringkasan Laporan Keterbukaan Real-Time</h3>
                <p class="text-xs text-gray-500 mt-0.5">Nilai baca-saja yang dihitung otomatis dari transaksi nyata sistem (di-cache 1 menit).</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100 self-start sm:self-auto">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Dihitung Otomatis
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- Kartu Penyaluran Barang --}}
            <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
                <span class="text-xs uppercase tracking-wider font-bold text-primary">Barang Tersalurkan</span>
                <div class="text-3xl font-extrabold text-gray-900">
                    {{ \App\Http\Services\SiteStatsService::formatNumber($stats['barang_tersalurkan']) }}
                </div>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Dihitung dari <strong>{{ number_format($stats['donations_received_count']) }} donasi diterima</strong> ({{ number_format($stats['donations_items_sum']) }} barang) + <strong>{{ number_format($stats['sold_items_sum']) }} barang terjual</strong> dari pesanan lunas.
                </p>
            </div>

            {{-- Kartu Penerima Manfaat (ABK) --}}
            <div class="p-5 rounded-2xl bg-gray-50 border border-gray-200 space-y-3">
                <span class="text-xs uppercase tracking-wider font-bold text-primary">Penerima Manfaat</span>
                <div class="text-3xl font-extrabold text-gray-900">
                    {{ $stats['abk_trained_count'] }} ABK
                </div>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Diisi manual oleh admin pada bagian 2 di atas. Angka saat ini: <strong>{{ $stats['abk_trained_count'] }} anak mandiri</strong>.
                </p>
            </div>

            {{-- Kartu Dana Terapi --}}
            <div class="p-5 rounded-2xl bg-emerald-50/60 border border-emerald-200 space-y-3">
                <span class="text-xs uppercase tracking-wider font-bold text-emerald-700">Dana Terapi Terkumpul</span>
                <div class="text-3xl font-extrabold text-emerald-700">
                    {{ \App\Http\Services\SiteStatsService::formatRupiah($stats['dana_terapi']) }}
                </div>
                <p class="text-xs text-emerald-800/80 leading-relaxed">
                    Akumulasi 100% dari <strong>{{ number_format($stats['paid_purchases_count']) }} transaksi pesanan lunas</strong> (status PAID &amp; COMPLETED).
                </p>
            </div>
        </div>
    </div>

    {{-- SEKSI 4: RINGKASAN ULASAN & TESTIMONI --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-gray-900">4. Ringkasan Ulasan Sahabat Kios</h3>
                <p class="text-xs text-gray-500 mt-0.5">Ulasan masuk dari pembeli dan donatur yang telah diverifikasi transaksinya.</p>
            </div>
            <a href="{{ route('admin.reviews.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-xs font-semibold hover:bg-primary/90 transition shadow-xs self-start sm:self-auto min-h-[38px]">
                <span>Kelola di Menu Ulasan &rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl bg-gray-50 border border-gray-200">
                <span class="text-xs font-semibold text-gray-500">Total Ulasan Masuk</span>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalReviews }}</p>
            </div>
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200">
                <span class="text-xs font-semibold text-amber-700">Menunggu Persetujuan Admin</span>
                <p class="text-2xl font-bold text-amber-800 mt-1">{{ $pendingReviewsCount }}</p>
            </div>
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200">
                <span class="text-xs font-semibold text-emerald-700">Aktif Tampil di Beranda</span>
                <p class="text-2xl font-bold text-emerald-800 mt-1">{{ $activeReviewsCount }}</p>
            </div>
        </div>
    </div>

</div>

<script>
    function previewHeroImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('hero-preview');
                if (preview) preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
