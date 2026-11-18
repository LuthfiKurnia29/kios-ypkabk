@extends('user.shared.Layout')

@section('content')
{{-- Header Halaman (Komponen <x-page-header>) --}}
<x-page-header
    title="Kontak"
    subtitle="Hubungi kami untuk donasi, pembelian, atau kunjungan ke sentra."
    pill="Balasan dalam 1x24 jam"
    breadcrumb="Beranda / Kontak"
/>

{{-- Konten Utama: Lokasi Sentra, Peta, & Formulir Kontak --}}
<section class="w-full bg-krem py-12 lg:py-20 pb-24">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            {{-- Kolom Kiri (7 Kolom Desktop): Kartu Sentra & Peta Polos --}}
            <div class="lg:col-span-7 space-y-8">
                <div class="space-y-2">
                    <span class="text-xs uppercase tracking-wider font-semibold text-primary block">
                        Lokasi Sentra
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-teks tracking-tight">
                        Sentra Fisik Kios Mandiri
                    </h2>
                    <p class="text-xs sm:text-sm text-teks/75 leading-relaxed">
                        Kunjungi langsung sentra kami untuk menyerahkan barang donasi atau melihat proses workshop.
                    </p>
                </div>

                {{-- Dua Kartu Sentra Berdampingan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    {{-- Sentra Sidoarjo --}}
                    <div class="bg-white rounded-[12px] p-5 sm:p-6 border border-teks/10 flex flex-col justify-between space-y-4 shadow-xs">
                        <div class="space-y-2.5">
                            <h3 class="font-semibold text-base text-teks">
                                Sentra Sidoarjo
                            </h3>
                            <div class="text-xs text-teks/80 space-y-1">
                                <p><span class="font-medium text-teks">Alamat:</span> Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo, Jawa Timur</p>
                                <p><span class="font-medium text-teks">Jadwal:</span> Senin &ndash; Sabtu, 08.00 &ndash; 16.00 WIB</p>
                                <p><span class="font-medium text-teks">Kontak:</span> +62 812-3456-7890</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 pt-2 border-t border-teks/10">
                            <a href="https://wa.me/6281234567890"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center px-3.5 h-9 rounded-[8px] bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                                Chat WhatsApp
                            </a>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode('Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center px-3.5 h-9 rounded-[8px] border border-teks/20 text-teks hover:bg-white/80 font-semibold text-xs transition-colors">
                                Lihat di Peta
                            </a>
                        </div>
                    </div>

                    {{-- Sentra Mojokerto --}}
                    <div class="bg-white rounded-[12px] p-5 sm:p-6 border border-teks/10 flex flex-col justify-between space-y-4 shadow-xs">
                        <div class="space-y-2.5">
                            <h3 class="font-semibold text-base text-teks">
                                Sentra Mojokerto
                            </h3>
                            <div class="text-xs text-teks/80 space-y-1">
                                <p><span class="font-medium text-teks">Alamat:</span> Jl. Raya Brangkal No. 12, Sooko, Mojokerto, Jawa Timur</p>
                                <p><span class="font-medium text-teks">Jadwal:</span> Senin &ndash; Sabtu, 08.00 &ndash; 16.00 WIB</p>
                                <p><span class="font-medium text-teks">Kontak:</span> +62 812-3456-7890</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 pt-2 border-t border-teks/10">
                            <a href="https://wa.me/6281234567890"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center px-3.5 h-9 rounded-[8px] bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                                Chat WhatsApp
                            </a>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode('Jl. Raya Brangkal No. 12, Sooko, Mojokerto') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center px-3.5 h-9 rounded-[8px] border border-teks/20 text-teks hover:bg-white/80 font-semibold text-xs transition-colors">
                                Lihat di Peta
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Placeholder Peta --}}
                <div class="w-full bg-pink-pucat rounded-[12px] border border-teks/10 p-6 sm:p-8 text-center space-y-2 shadow-xs">
                    <span class="text-xs font-semibold text-primary uppercase tracking-wider block">
                        Peta Wilayah Sentra Jawa Timur (Koridor Sidoarjo &ndash; Mojokerto)
                    </span>
                    <p class="text-xs sm:text-sm text-teks/80 leading-relaxed max-w-lg mx-auto">
                        <strong>Sentra 1 (Sidoarjo):</strong> Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang.<br>
                        <strong>Sentra 2 (Mojokerto):</strong> Jl. Raya Brangkal No. 12, Sooko.
                    </p>
                    <p class="text-[11px] text-teks/60 pt-1">
                        Akses mudah via rute darat Surabaya &ndash; Mojokerto.
                    </p>
                </div>
            </div>

            {{-- Kolom Kanan (5 Kolom Desktop): Formulir Kontak --}}
            <div class="lg:col-span-5 bg-white rounded-[12px] border border-teks/10 p-6 sm:p-8 space-y-5 shadow-xs">
                <div class="space-y-1">
                    <span class="text-xs uppercase tracking-wider font-semibold text-primary block">
                        Formulir Kontak
                    </span>
                    <h3 class="font-bold text-lg text-teks tracking-tight">
                        Kirim Pesan atau Konsultasi Donasi
                    </h3>
                    <p class="text-xs text-teks/70 leading-relaxed">
                        Sampaikan pertanyaan mengenai donasi barang, kunjungan, atau kemitraan.
                    </p>
                </div>

                <form id="contact-form" method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                    @csrf

                    {{-- Honeypot --}}
                    <div class="hidden" aria-hidden="true">
                        <label for="website">Biarkan kosong</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div>
                        <label for="contact-name" class="block text-xs font-semibold text-teks mb-1">
                            Nama Lengkap *
                        </label>
                        <input type="text"
                               id="contact-name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Nama lengkap Anda"
                               class="w-full px-3.5 py-2.5 rounded-[8px] border {{ $errors->has('name') ? 'border-rose-400' : 'border-teks/20' }} text-xs sm:text-sm text-teks focus:outline-none focus:ring-1 focus:ring-primary">
                        @error('name')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact-phone" class="block text-xs font-semibold text-teks mb-1">
                            Nomor WhatsApp *
                        </label>
                        <input type="tel"
                               id="contact-phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               placeholder="0812xxxxxxx"
                               class="w-full px-3.5 py-2.5 rounded-[8px] border {{ $errors->has('phone') ? 'border-rose-400' : 'border-teks/20' }} text-xs sm:text-sm text-teks focus:outline-none focus:ring-1 focus:ring-primary">
                        @error('phone')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact-message" class="block text-xs font-semibold text-teks mb-1">
                            Pesan Detail *
                        </label>
                        <textarea id="contact-message"
                                  name="message"
                                  rows="4"
                                  placeholder="Tuliskan pesan atau rencana donasi Anda..."
                                  class="w-full px-3.5 py-2.5 rounded-[8px] border {{ $errors->has('message') ? 'border-rose-400' : 'border-teks/20' }} text-xs sm:text-sm text-teks focus:outline-none focus:ring-1 focus:ring-primary">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full h-11 rounded-[8px] bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition-all shadow-xs active:scale-[0.98]">
                        Kirim Pesan
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

@include('user.pages.home._modal-donasi')
@include('user.pages.home._scripts')
@endsection
