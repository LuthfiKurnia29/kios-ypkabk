@extends('admin.shared.Layout')

@section('title', 'Konten Situs')

@section('content')
<div class="space-y-6">

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

    {{-- CARD 3: Pengaturan Kontak, Sentra Fisik, Media Sosial, & Footer --}}
    @php
        $site = \App\Models\SiteSetting::getPublicSettings();
    @endphp
    <form action="{{ route('admin.site-content.update-info') }}" method="POST"
        class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <h3 class="text-base font-bold text-gray-900">Kontak Resmi, Sentra Fisik, &amp; Footer</h3>
            <p class="text-xs text-gray-500 mt-0.5">Pengaturan terpusat untuk hotline WhatsApp, email, jam layanan, alamat sentra, Google Maps, media sosial, dan deskripsi footer.</p>
        </div>

        {{-- 1. Deskripsi Footer --}}
        <div class="space-y-1.5">
            <label for="footer_description" class="block text-xs font-semibold text-gray-700">
                Deskripsi Singkat Footer (Kolom Kiri)
            </label>
            <textarea id="footer_description" name="footer_description" rows="3"
                class="w-full px-3.5 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition leading-relaxed">{{ old('footer_description', $settings->get('footer_description') ?? $site['footer_description']) }}</textarea>
            <p class="text-[11px] text-gray-400">Paragraf pengantar brand Kios Mandiri YPKABK di bagian bawah situs.</p>
        </div>

        {{-- 2. Hotline Kontak & Jam Layanan --}}
        <div class="pt-4 border-t border-gray-100 space-y-3">
            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider text-primary">Hotline Kontak &amp; Operasional</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label for="contact_whatsapp" class="block text-xs font-semibold text-gray-700">Nomor WhatsApp Admin</label>
                    <input type="text" id="contact_whatsapp" name="contact_whatsapp"
                        value="{{ old('contact_whatsapp', $settings->get('contact_whatsapp') ?? $site['contact_whatsapp']) }}"
                        placeholder="081234567890"
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                    <p class="text-[10px] text-gray-400">Otomatis dihubungkan ke wa.me/{62...}.</p>
                </div>
                <div class="space-y-1">
                    <label for="contact_email" class="block text-xs font-semibold text-gray-700">Email Resmi</label>
                    <input type="email" id="contact_email" name="contact_email"
                        value="{{ old('contact_email', $settings->get('contact_email') ?? $site['contact_email']) }}"
                        placeholder="kios@ypkabk.org"
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
                <div class="space-y-1">
                    <label for="service_hours" class="block text-xs font-semibold text-gray-700">Jam Layanan</label>
                    <input type="text" id="service_hours" name="service_hours"
                        value="{{ old('service_hours', $settings->get('service_hours') ?? $site['service_hours']) }}"
                        placeholder="Senin – Sabtu: 08.00 – 16.00 WIB"
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
            </div>
        </div>

        {{-- 3. Sentra Fisik 1 (Sidoarjo) --}}
        <div class="pt-4 border-t border-gray-100 space-y-3">
            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider text-primary">Sentra Fisik 1 (Sidoarjo)</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="sentra_sidoarjo_name" class="block text-xs font-semibold text-gray-700">Nama Sentra</label>
                    <input type="text" id="sentra_sidoarjo_name" name="sentra_sidoarjo_name"
                        value="{{ old('sentra_sidoarjo_name', $settings->get('sentra_sidoarjo_name') ?? $site['sentra_sidoarjo_name']) }}"
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
                <div class="space-y-1">
                    <label for="sentra_sidoarjo_maps_url" class="block text-xs font-semibold text-gray-700">Link Google Maps</label>
                    <input type="text" id="sentra_sidoarjo_maps_url" name="sentra_sidoarjo_maps_url"
                        value="{{ old('sentra_sidoarjo_maps_url', $settings->get('sentra_sidoarjo_maps_url') ?? $site['sentra_sidoarjo_maps_url']) }}"
                        placeholder="https://maps.google.com/?q=..."
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
                <div class="md:col-span-2 space-y-1">
                    <label for="sentra_sidoarjo_address" class="block text-xs font-semibold text-gray-700">Alamat Lengkap Sidoarjo</label>
                    <input type="text" id="sentra_sidoarjo_address" name="sentra_sidoarjo_address"
                        value="{{ old('sentra_sidoarjo_address', $settings->get('sentra_sidoarjo_address') ?? $site['sentra_sidoarjo_address']) }}"
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
            </div>
        </div>

        {{-- 4. Sentra Fisik 2 (Mojokerto) --}}
        <div class="pt-4 border-t border-gray-100 space-y-3">
            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider text-primary">Sentra Fisik 2 (Mojokerto)</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="sentra_mojokerto_name" class="block text-xs font-semibold text-gray-700">Nama Sentra</label>
                    <input type="text" id="sentra_mojokerto_name" name="sentra_mojokerto_name"
                        value="{{ old('sentra_mojokerto_name', $settings->get('sentra_mojokerto_name') ?? $site['sentra_mojokerto_name']) }}"
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
                <div class="space-y-1">
                    <label for="sentra_mojokerto_maps_url" class="block text-xs font-semibold text-gray-700">Link Google Maps</label>
                    <input type="text" id="sentra_mojokerto_maps_url" name="sentra_mojokerto_maps_url"
                        value="{{ old('sentra_mojokerto_maps_url', $settings->get('sentra_mojokerto_maps_url') ?? $site['sentra_mojokerto_maps_url']) }}"
                        placeholder="https://maps.google.com/?q=..."
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
                <div class="md:col-span-2 space-y-1">
                    <label for="sentra_mojokerto_address" class="block text-xs font-semibold text-gray-700">Alamat Lengkap Mojokerto</label>
                    <input type="text" id="sentra_mojokerto_address" name="sentra_mojokerto_address"
                        value="{{ old('sentra_mojokerto_address', $settings->get('sentra_mojokerto_address') ?? $site['sentra_mojokerto_address']) }}"
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
            </div>
        </div>

        {{-- 5. Media Sosial --}}
        <div class="pt-4 border-t border-gray-100 space-y-3">
            <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider text-primary">Tautan Media Sosial Resmi</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label for="social_instagram" class="block text-xs font-semibold text-gray-700">Instagram URL</label>
                    <input type="text" id="social_instagram" name="social_instagram"
                        value="{{ old('social_instagram', $settings->get('social_instagram') ?? $site['social_instagram']) }}"
                        placeholder="https://instagram.com/..."
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
                <div class="space-y-1">
                    <label for="social_youtube" class="block text-xs font-semibold text-gray-700">YouTube URL</label>
                    <input type="text" id="social_youtube" name="social_youtube"
                        value="{{ old('social_youtube', $settings->get('social_youtube') ?? $site['social_youtube']) }}"
                        placeholder="https://youtube.com/@..."
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
                <div class="space-y-1">
                    <label for="social_tiktok" class="block text-xs font-semibold text-gray-700">TikTok URL</label>
                    <input type="text" id="social_tiktok" name="social_tiktok"
                        value="{{ old('social_tiktok', $settings->get('social_tiktok') ?? $site['social_tiktok']) }}"
                        placeholder="https://tiktok.com/@..."
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>
            </div>
        </div>

        <div class="pt-3 border-t border-gray-100 flex justify-end">
            <button type="submit"
                class="px-5 py-2 rounded-md bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition shadow-sm min-h-[40px]">
                Simpan Pengaturan Kontak &amp; Footer
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
