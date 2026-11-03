@extends('user.shared.Layout')

@section('content')
<div class="space-y-16 lg:space-y-24 pb-20 pt-24">

    {{-- Hero Section: Profil Kios Mandiri & YPKABK (FR-02) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-hero-gradient rounded-3xl p-8 sm:p-12 lg:p-16 border border-ink/10 space-y-8">
            <div class="max-w-3xl space-y-4">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-butter border border-ink/15 text-ink text-xs font-semibold">
                    <span>🌱</span> Profil &amp; Misi Sosial
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-ink tracking-tight leading-tight">
                    Laboratorium Vokasi &amp; Ekonomi Sirkular untuk Anak Istimewa
                </h1>
                <p class="text-base sm:text-lg text-ink/80 leading-relaxed">
                    Kios Mandiri adalah unit pemberdayaan ekonomi sosial di bawah naungan <strong>Yayasan Peduli Kasih Anak Berkebutuhan Khusus (YPKABK)</strong>. Kami memadukan kepedulian terhadap lingkungan melalui kurasi donasi barang preloved dengan program pelatihan kemandirian hidup bagi anak-anak berkebutuhan khusus.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
                <div class="bg-white/90 backdrop-blur rounded-2xl p-6 border border-ink/10 space-y-2">
                    <span class="text-2xl">🤝</span>
                    <h3 class="font-bold text-lg text-ink">Afiliasi YPKABK</h3>
                    <p class="text-xs sm:text-sm text-ink/70 leading-relaxed">
                        Terhubung langsung dengan sentra terapi dan pelatihan vokasi YPKABK di Sidoarjo dan Mojokerto.
                    </p>
                </div>
                <div class="bg-white/90 backdrop-blur rounded-2xl p-6 border border-ink/10 space-y-2">
                    <span class="text-2xl">♻️</span>
                    <h3 class="font-bold text-lg text-ink">Zero-Waste &amp; Sirkular</h3>
                    <p class="text-xs sm:text-sm text-ink/70 leading-relaxed">
                        Memperpanjang usia pakai pakaian, buku, dan mainan layak guna demi kelestarian bumi dan kemanfaatan sosial.
                    </p>
                </div>
                <div class="bg-white/90 backdrop-blur rounded-2xl p-6 border border-ink/10 space-y-2">
                    <span class="text-2xl">❤️</span>
                    <h3 class="font-bold text-lg text-ink">100% Margin untuk Terapi</h3>
                    <p class="text-xs sm:text-sm text-ink/70 leading-relaxed">
                        Seluruh surplus penjualan dialokasikan untuk membiayai sesi terapi okupasi, wicara, dan beasiswa siswa kurang mampu.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Edukasi Proses Daur Ulang / Siklus Kios Mandiri (FR-02) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <h2 class="font-heading text-3xl sm:text-4xl font-bold text-ink tracking-tight">
                Bagaimana Rantai Kebaikan Bekerja?
            </h2>
            <p class="text-ink/75 text-sm sm:text-base leading-relaxed">
                Dari lemari donatur hingga menjadi ruang belajar dan kemandirian bagi anak istimewa.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            {{-- Tahap 1 --}}
            <div class="bg-cream rounded-2xl p-6 border border-ink/10 space-y-4">
                <div class="w-10 h-10 rounded-full bg-sun text-ink font-bold flex items-center justify-center text-sm shadow-xs">
                    01
                </div>
                <h3 class="font-heading font-bold text-lg text-ink">Penerimaan &amp; Kurasi</h3>
                <p class="text-xs sm:text-sm text-ink/75 leading-relaxed">
                    Barang preloved yang masuk disortir dengan standar kelayakan tinggi: bersih, fungsi optimal, dan aman digunakan kembali.
                </p>
            </div>

            {{-- Tahap 2 --}}
            <div class="bg-cream rounded-2xl p-6 border border-ink/10 space-y-4">
                <div class="w-10 h-10 rounded-full bg-sun text-ink font-bold flex items-center justify-center text-sm shadow-xs">
                    02
                </div>
                <h3 class="font-heading font-bold text-lg text-ink">Sanitasi &amp; Restorasi</h3>
                <p class="text-xs sm:text-sm text-ink/75 leading-relaxed">
                    Pakaian dicuci higienis, mainan disanitasi ramah anak, dan barang kayu dirapikan kembali oleh tim relawan.
                </p>
            </div>

            {{-- Tahap 3 --}}
            <div class="bg-cream rounded-2xl p-6 border border-ink/10 space-y-4">
                <div class="w-10 h-10 rounded-full bg-sun text-ink font-bold flex items-center justify-center text-sm shadow-xs">
                    03
                </div>
                <h3 class="font-heading font-bold text-lg text-ink">Vokasi Siswa ABK</h3>
                <p class="text-xs sm:text-sm text-ink/75 leading-relaxed">
                    Anak-anak dilibatkan dalam pengemasan ramah lingkungan (kardus daur ulang), labelling, penataan etalase, dan pembuatan karya kriya.
                </p>
            </div>

            {{-- Tahap 4 --}}
            <div class="bg-cream rounded-2xl p-6 border border-ink/10 space-y-4">
                <div class="w-10 h-10 rounded-full bg-sun text-ink font-bold flex items-center justify-center text-sm shadow-xs">
                    04
                </div>
                <h3 class="font-heading font-bold text-lg text-ink">Aliran Dana Terbuka</h3>
                <p class="text-xs sm:text-sm text-ink/75 leading-relaxed">
                    Hasil penjualan dicatat dalam pembukuan transparan untuk membiayai fasilitas sensori dan terapi siswa YPKABK.
                </p>
            </div>
        </div>
    </section>

    {{-- Kegiatan & Dokumentasi Sentra --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-olive text-white rounded-3xl p-8 sm:p-12 lg:p-16 space-y-8">
            <div class="max-w-2xl space-y-3">
                <h2 class="font-heading text-3xl sm:text-4xl font-bold text-white tracking-tight">
                    Sentra Terapi &amp; Vokasi Kami
                </h2>
                <p class="text-white/80 text-sm sm:text-base leading-relaxed">
                    Kios Mandiri beroperasi di dua sentra utama yang menaungi puluhan anak-anak hebat di Jawa Timur:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white/10 rounded-2xl p-6 border border-white/15 space-y-3">
                    <span class="text-sun font-bold text-sm block uppercase tracking-wider">Sentra 1</span>
                    <h3 class="text-xl font-bold text-white">Sentra Sepanjang, Sidoarjo</h3>
                    <p class="text-sm text-white/80 leading-relaxed">
                        Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo.<br>
                        Fokus pada sentra kurasi barang preloved, pengemasan, dan kelas keterampilan kriya kanvas anak.
                    </p>
                </div>
                <div class="bg-white/10 rounded-2xl p-6 border border-white/15 space-y-3">
                    <span class="text-sun font-bold text-sm block uppercase tracking-wider">Sentra 2</span>
                    <h3 class="text-xl font-bold text-white">Sentra Dlanggu, Mojokerto</h3>
                    <p class="text-sm text-white/80 leading-relaxed">
                        Pondok Asri No. 12, Dlanggu, Mojokerto.<br>
                        Fokus pada sensory room, pelatihan motorik mandiri, kriya rajut amigurumi, dan bengkel kayu mainan edukasi.
                    </p>
                </div>
            </div>

            <div class="pt-4 flex flex-wrap items-center gap-4">
                <a href="{{ route('katalog') }}" class="px-6 py-3 rounded-full bg-sun text-ink font-bold text-sm hover:bg-sun/90 transition-all shadow-sm">
                    Lihat Etalase Karya &amp; Preloved
                </a>
                <button type="button" onclick="openDonationModal()" class="px-6 py-3 rounded-full bg-white/20 hover:bg-white/30 text-white font-bold text-sm transition-all border border-white/30">
                    Donasikan Barang Preloved
                </button>
            </div>
        </div>
    </section>

</div>

@include('user.pages.home._modal-donasi')
@include('user.pages.home._scripts')
@endsection

