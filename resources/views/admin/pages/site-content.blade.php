@extends('admin.shared.Layout')

@section('title', 'Konten Situs')

@section('content')
<div class="space-y-6">

    {{-- Alert Flash Message Umum --}}
    @if(session('success'))
        <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <span class="font-medium">{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700" aria-label="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Konten Situs</h2>
            <p class="text-sm text-gray-500 mt-0.5">Kelola banner hero dan data pembinaan ABK.</p>
        </div>
        <a href="{{ route('home') }}" target="_blank"
            class="inline-flex items-center px-3.5 py-2 rounded-md border border-gray-200 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50 transition shadow-xs self-start sm:self-auto min-h-[40px]">
            Lihat Beranda
        </a>
    </div>

    {{-- FORM PENGATURAN HERO --}}
    <form method="POST" action="{{ route('admin.site-content.update-hero') }}" enctype="multipart/form-data" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-bold text-gray-900">Hero Beranda</h3>
                <p class="text-xs text-gray-500 mt-0.5">Atur foto utama dan teks ajakan di bagian paling atas halaman beranda.</p>
            </div>
            @if(session('success_hero'))
                <span class="text-xs font-semibold text-emerald-700">
                    {{ session('success_hero') }}
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Pratinjau & Upload Foto --}}
            <div class="lg:col-span-5 space-y-3">
                <label class="block text-xs font-semibold text-gray-700">Foto Hero Saat Ini</label>
                <div class="rounded-md border border-gray-200 p-2 bg-gray-50 flex flex-col items-center justify-center">
                    <img id="hero-preview" src="{{ $heroImageUrl }}" alt="Pratinjau Hero"
                        class="max-h-64 w-auto object-contain rounded border border-gray-200">
                </div>

                <div>
                    <label for="hero_image" class="block text-xs font-semibold text-gray-700 mb-1">
                        Unggah Foto Baru <span class="text-gray-400 font-normal">(JPG, PNG, atau WebP, maks. 2 MB)</span>
                    </label>
                    <input type="file" id="hero_image" name="hero_image" accept="image/jpeg,image/png,image/webp"
                        onchange="previewHeroImage(this)"
                        class="w-full text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
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
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
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
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none">{{ old('hero_title', \App\Models\SiteSetting::cleanHeroTitle($settings->get('hero_title'))) }}</textarea>
                    @error('hero_title')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hero_subtitle" class="block text-xs font-semibold text-gray-700 mb-1">
                        Subjudul / Deskripsi Pendek Hero <span class="text-gray-400 font-normal">(Teks biasa)</span>
                    </label>
                    <textarea id="hero_subtitle" name="hero_subtitle" rows="4"
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none">{{ old('hero_subtitle', $settings->get('hero_subtitle', 'Donasi barang layak pakai dan hasil karya adik-adik disabilitas kami kelola untuk operasional ruang terapi gratis dan pelatihan vokasi mandiri di Sidoarjo dan Mojokerto.')) }}</textarea>
                    @error('hero_subtitle')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit"
                class="px-5 py-2 rounded-md bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition shadow-sm min-h-[40px]">
                Simpan Hero
            </button>
        </div>
    </form>

    {{-- FORM JUMLAH ABK TERLATIH MANDIRI --}}
    <form method="POST" action="{{ route('admin.site-content.update-abk') }}" class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">
        @csrf
        @method('PUT')

        <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-base font-bold text-gray-900">Jumlah ABK Terlatih Mandiri</h3>
                <p class="text-xs text-gray-500 mt-0.5">Diisi manual oleh admin sesuai data binaan aktif yayasan yang telah mandiri.</p>
            </div>
            @if(session('success_abk'))
                <span class="text-xs font-semibold text-emerald-700">
                    {{ session('success_abk') }}
                </span>
            @endif
        </div>

        @php
            $currentAbk = $settings->get('abk_trained_count');
            $isAbkEmpty = $currentAbk === null || (int)$currentAbk <= 0;
        @endphp

        @if($isAbkEmpty)
            <div class="p-3 rounded-md bg-amber-50 border border-amber-200 text-amber-800 text-xs">
                Jumlah ABK belum diisi. Angka di beranda saat ini menampilkan 0 ABK.
            </div>
        @endif

        <div class="max-w-xs space-y-2">
            <label for="abk_trained_count" class="block text-xs font-semibold text-gray-700">
                Jumlah Anak Binaan Mandiri (Orang) <span class="text-rose-500">*</span>
            </label>
            <div>
                <input type="number" step="1" min="0" id="abk_trained_count" name="abk_trained_count" required
                    value="{{ old('abk_trained_count', $currentAbk ?? 0) }}"
                    class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
            </div>
            <p class="text-[11px] text-gray-400">Tampil pada kartu kedua di seksi Jejak Kebaikan &amp; halaman /dampak.</p>
            @error('abk_trained_count')
                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-3 border-t border-gray-100 flex justify-end">
            <button type="submit"
                class="px-5 py-2 rounded-md bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition shadow-sm min-h-[40px]">
                Simpan Jumlah ABK
            </button>
        </div>
    </form>

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
