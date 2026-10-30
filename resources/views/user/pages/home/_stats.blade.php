{{-- ========================================================
     SECTION: TENTANG & DAMPAK
     id="dampak", latar putih, padding py-16 lg:py-24.
     - Judul section
     - 3 foto kegiatan berbentuk lonjong (rounded-full rasio lebar)
     - 3 angka dampak (angka Baloo 2 besar, keterangan kecil)
     - Mobile: foto geser horizontal/bertumpuk, angka 1 kolom
     ======================================================== --}}
<section id="dampak" class="bg-white py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 lg:space-y-16">

        {{-- Judul Section --}}
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <h2 class="font-heading text-3xl sm:text-4xl lg:text-5xl font-bold text-ink tracking-tight">
                Dampak Nyata dari Setiap Donasi Anda
            </h2>
            <p class="text-ink/75 text-base sm:text-lg leading-relaxed">
                Kebaikan yang Anda titipkan menjadi ruang terapi gratis, alat belajar ramah sensori, dan kemandirian bagi anak-anak berkebutuhan khusus.
            </p>
        </div>

        {{-- 3 Foto Kegiatan Berbentuk Lonjong --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="aspect-[16/9] rounded-[999px] overflow-hidden bg-cream shadow-sm">
                {{-- TODO: ganti foto asli --}}
                <img src="{{ asset('images/kegiatan/kegiatan-grup.webp') }}"
                     alt="Kegiatan kelompok anak-anak istimewa bersama pembina YPKABK"
                     width="1600"
                     height="1066"
                     class="w-full h-full object-cover object-left rounded-[999px]"
                     loading="lazy">
            </div>

            <div class="aspect-[16/9] rounded-[999px] overflow-hidden bg-cream shadow-sm">
                {{-- TODO: ganti foto asli --}}
                <img src="{{ asset('images/kegiatan/kegiatan-lomba.webp') }}"
                     alt="Anak berkebutuhan khusus berpartisipasi dalam kegiatan lomba kreatif YPKABK"
                     width="853"
                     height="1280"
                     class="w-full h-full object-cover rounded-[999px]"
                     loading="lazy">
            </div>

            <div class="aspect-[16/9] rounded-[999px] overflow-hidden bg-butter shadow-sm">
                {{-- TODO: ganti foto asli --}}
                <img src="{{ asset('images/kegiatan/anak-bernyanyi.webp') }}"
                     alt="Anak istimewa bernyanyi dengan penuh keceriaan dalam kegiatan YPKABK"
                     width="640"
                     height="663"
                     class="w-full h-full object-contain rounded-[999px]"
                     loading="lazy">
            </div>
        </div>

        {{-- Baris 3 Angka Dampak --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 pt-4 text-center">

            {{-- Angka 1 --}}
            <div class="space-y-2">
                {{-- TODO: ganti dengan data asli --}}
                <div class="font-heading text-4xl sm:text-5xl lg:text-6xl font-bold text-ink">
                    1.420+
                </div>
                <div class="text-base sm:text-lg font-bold text-ink">
                    Barang Preloved Tersalurkan
                </div>
                <div class="text-xs sm:text-sm text-ink/70">
                    {{-- TODO: ganti dengan data asli --}}
                    Dari 1.120+ donatur di Sidoarjo &amp; Mojokerto
                </div>
            </div>

            {{-- Angka 2 --}}
            <div class="space-y-2">
                {{-- TODO: ganti dengan data asli --}}
                <div class="font-heading text-4xl sm:text-5xl lg:text-6xl font-bold text-ink">
                    38 Anak
                </div>
                <div class="text-base sm:text-lg font-bold text-ink">
                    ABK Terlatih Mandiri
                </div>
                <div class="text-xs sm:text-sm text-ink/70">
                    Kelas keterampilan, kriya, dan pelayanan kios
                </div>
            </div>

            {{-- Angka 3 --}}
            <div class="space-y-2">
                {{-- TODO: ganti dengan data asli --}}
                <div class="font-heading text-4xl sm:text-5xl lg:text-6xl font-bold text-ink">
                    Rp 84,5 Jt
                </div>
                <div class="text-base sm:text-lg font-bold text-ink">
                    Dana Terapi Terkumpul
                </div>
                <div class="text-xs sm:text-sm text-ink/70">
                    100% dialokasikan secara transparan
                </div>
            </div>

        </div>

    </div>
</section>
