@extends('user.shared.Layout')

@section('content')
{{-- Header Halaman (Komponen <x-page-header>) --}}
<x-page-header
    title="Cara Donasi"
    subtitle="Barang layak pakai dan karya kreatif bisa langsung kamu donasikan lewat sentra kami."
    pill="Respon dalam 1x24 jam"
    breadcrumb="Beranda / Cara Donasi"
/>

{{-- 1. Empat Langkah Mudah (Latar bg-krem) --}}
<section class="w-full bg-krem py-12 lg:py-16">
    <div class="max-w-[1200px] mx-auto px-6 space-y-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-teks tracking-tight">
            Empat langkah mudah
        </h2>

        <div class="space-y-4">
            {{-- Langkah 1 --}}
            <div class="flex items-start gap-4 pb-4 border-b border-teks/10">
                <span class="font-bold text-base text-primary shrink-0">1.</span>
                <p class="text-sm sm:text-base text-teks/85 leading-relaxed font-normal">
                    <strong class="font-bold text-teks">Pilih barang.</strong> Pastikan masih layak pakai, bersih, dan lengkap.
                </p>
            </div>

            {{-- Langkah 2 --}}
            <div class="flex items-start gap-4 pb-4 border-b border-teks/10">
                <span class="font-bold text-base text-primary shrink-0">2.</span>
                <p class="text-sm sm:text-base text-teks/85 leading-relaxed font-normal">
                    <strong class="font-bold text-teks">Isi formulir.</strong> Klik tombol Donasikan Barang, lengkapi formulir donasi, lalu kirim.
                </p>
            </div>

            {{-- Langkah 3 --}}
            <div class="flex items-start gap-4 pb-4 border-b border-teks/10">
                <span class="font-bold text-base text-primary shrink-0">3.</span>
                <p class="text-sm sm:text-base text-teks/85 leading-relaxed font-normal">
                    <strong class="font-bold text-teks">Dihubungi lewat WhatsApp.</strong> Tim relawan kami akan menghubungi Anda untuk konfirmasi kondisi barang dan jadwal.
                </p>
            </div>

            {{-- Langkah 4 --}}
            <div class="flex items-start gap-4 pb-4 border-b border-teks/10">
                <span class="font-bold text-base text-primary shrink-0">4.</span>
                <p class="text-sm sm:text-base text-teks/85 leading-relaxed font-normal">
                    <strong class="font-bold text-teks">Antar atau dijemput.</strong> Bawa langsung barang ke sentra kami atau tunggu penjemputan oleh relawan. Barang akan dikurasi dan hasil penjualannya mendanai terapi.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- 2. Barang yang Diterima dan Tidak Diterima (Latar bg-pink-pucat) --}}
<section class="w-full bg-pink-pucat py-12 lg:py-16">
    <div class="max-w-[1200px] mx-auto px-6 space-y-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-teks tracking-tight">
            Barang yang diterima dan tidak diterima
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 md:divide-x md:divide-teks/15">
            {{-- Kolom 1: Kami Terima --}}
            <div class="space-y-4">
                <h3 class="font-bold text-base sm:text-lg text-teks flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 inline-block"></span>
                    <span>Kami terima</span>
                </h3>
                <ul class="space-y-3 text-xs sm:text-sm text-teks/80 leading-relaxed font-normal">
                    <li class="flex items-start gap-2.5">
                        <span class="text-emerald-700 font-bold">&bull;</span>
                        <span>Pakaian anak dan dewasa layak pakai</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-emerald-700 font-bold">&bull;</span>
                        <span>Buku bacaan dan ensiklopedia</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-emerald-700 font-bold">&bull;</span>
                        <span>Mainan edukatif dan puzzle yang lengkap</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-emerald-700 font-bold">&bull;</span>
                        <span>Perlengkapan rumah dan kerajinan kayu</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-emerald-700 font-bold">&bull;</span>
                        <span>Bahan prakarya dan alat terapi</span>
                    </li>
                </ul>
            </div>

            {{-- Kolom 2: Belum Bisa Kami Terima --}}
            <div class="space-y-4 md:pl-12">
                <h3 class="font-bold text-base sm:text-lg text-teks flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-600 inline-block"></span>
                    <span>Belum bisa kami terima</span>
                </h3>
                <ul class="space-y-3 text-xs sm:text-sm text-teks/80 leading-relaxed font-normal">
                    <li class="flex items-start gap-2.5">
                        <span class="text-rose-700 font-bold">&bull;</span>
                        <span>Pakaian rusak atau bernoda</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-rose-700 font-bold">&bull;</span>
                        <span>Barang elektronik yang tidak berfungsi</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-rose-700 font-bold">&bull;</span>
                        <span>Kasur dan perabot besar</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-rose-700 font-bold">&bull;</span>
                        <span>Makanan dan obat-obatan</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="text-rose-700 font-bold">&bull;</span>
                        <span>Mainan dengan bagian hilang atau berbahaya</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- 3. Cara Menyerahkan Barang (Latar bg-krem, Dua Kolom) --}}
<section class="w-full bg-krem py-12 lg:py-16">
    <div class="max-w-[1200px] mx-auto px-6 space-y-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-teks tracking-tight">
            Cara menyerahkan barang
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
            {{-- Kolom 1: Antar Sendiri --}}
            <div class="space-y-4">
                <h3 class="font-bold text-base sm:text-lg text-teks">
                    Antar sendiri
                </h3>
                <p class="text-xs sm:text-sm text-teks/75 leading-relaxed font-normal">
                    Bawa langsung donasi barang Anda ke lokasi sentra kami:
                </p>

                <div class="space-y-3 text-xs sm:text-sm text-teks/85 bg-white p-5 rounded-[12px] border border-teks/10 shadow-xs">
                    <div>
                        <p class="font-bold text-teks">Sentra Sidoarjo:</p>
                        <p class="mt-0.5 leading-relaxed font-normal text-teks/80">Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo, Jawa Timur</p>
                    </div>
                    <div class="pt-2 border-t border-teks/10">
                        <p class="font-bold text-teks">Sentra Mojokerto:</p>
                        <p class="mt-0.5 leading-relaxed font-normal text-teks/80">Jl. Raya Brangkal No. 12, Sooko, Mojokerto, Jawa Timur</p>
                    </div>
                    <div class="pt-2 border-t border-teks/10 text-teks/70 text-xs font-normal">
                        <strong class="font-semibold text-teks">Jam layanan:</strong> Senin sampai Sabtu, 08.00 sampai 16.00 WIB
                    </div>
                </div>
            </div>

            {{-- Kolom 2: Dijemput --}}
            <div class="space-y-4">
                <h3 class="font-bold text-base sm:text-lg text-teks">
                    Dijemput
                </h3>
                <p class="text-xs sm:text-sm text-teks/75 leading-relaxed font-normal">
                    Layanan jemput donasi oleh relawan kami untuk memudahkan Anda:
                </p>

                <div class="space-y-3 text-xs sm:text-sm text-teks/85 bg-white p-5 rounded-[12px] border border-teks/10 shadow-xs">
                    <ul class="space-y-2.5 font-normal text-teks/80">
                        <li class="flex items-start gap-2">
                            <span class="text-primary font-bold">&bull;</span>
                            <span class="leading-relaxed">Tersedia khusus untuk area tertentu (wilayah Sidoarjo &amp; Mojokerto sekitarnya).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-primary font-bold">&bull;</span>
                            <span class="leading-relaxed">Jadwal dan waktu penjemputan disepakati bersama lewat WhatsApp.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-primary font-bold">&bull;</span>
                            {{-- TODO: syarat minimal 5 barang adalah contoh, sesuaikan kebijakan yayasan --}}
                            <span class="leading-relaxed">Minimal 5 barang layak pakai untuk setiap penjemputan.</span>
                        </li>
                    </ul>
                    <div class="pt-2 border-t border-teks/10 text-teks/70 text-xs font-normal">
                        <strong class="font-semibold text-teks">Waktu koordinasi:</strong> Senin sampai Sabtu, 08.00 sampai 16.00 WIB
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 4. Blok Penutup: Sudah Siap Mendonasikan Barang? --}}
<section class="w-full bg-pink-pucat py-12 lg:py-16 text-center">
    <div class="max-w-[1200px] mx-auto px-6 space-y-6">
        <h2 class="text-2xl sm:text-3xl font-bold text-teks tracking-tight">
            Sudah siap mendonasikan barang?
        </h2>
        <div>
            <button type="button"
                    onclick="openDonationModal()"
                    class="inline-flex items-center justify-center px-8 h-[44px] rounded-[8px] bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition-all active:scale-[0.98] shadow-xs cursor-pointer">
                Donasikan Barang
            </button>
        </div>
    </div>
</section>

@include('user.pages.home._modal-donasi')
@include('user.pages.home._scripts')
@endsection
