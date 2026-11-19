@extends('user.shared.Layout')

@section('title', 'Kontak')

@section('content')
{{-- Header Halaman --}}
<x-page-header
    title="Kontak & Lokasi Sentra"
    subtitle="Hubungi tim Kios Mandiri YPKABK untuk donasi barang preloved, pembelian produk vokasi, atau kunjungan ke sentra kami."
    pill="Respon Cepat 1x24 Jam"
    breadcrumb="Beranda / Kontak"
/>

{{-- Konten Utama: Lokasi Sentra, Peta, & Formulir Kontak --}}
<section class="w-full bg-krem py-12 lg:py-20 pb-24">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            {{-- Kolom Kiri (7 Kolom Desktop): Kartu Sentra & Peta Interaktif Tabbed --}}
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
                @php
                    $site = $siteSettings ?? \App\Models\SiteSetting::getPublicSettings();
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    {{-- Sentra Sidoarjo --}}
                    <div class="bg-white rounded-[12px] p-5 sm:p-6 border border-teks/10 flex flex-col justify-between space-y-4 shadow-xs">
                        <div class="space-y-2.5">
                            <h3 class="font-semibold text-base text-teks">
                                {{ $site['sentra_sidoarjo_name'] }}
                            </h3>
                            <div class="text-xs text-teks/80 space-y-1">
                                <p><span class="font-medium text-teks">Alamat:</span> {{ $site['sentra_sidoarjo_address'] }}</p>
                                <p><span class="font-medium text-teks">Jadwal:</span> {{ $site['service_hours'] }}</p>
                                <p><span class="font-medium text-teks">Kontak:</span> {{ $site['contact_whatsapp'] }}</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 pt-2 border-t border-teks/10">
                            <a href="{{ $site['contact_whatsapp_link'] }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center px-3.5 h-9 rounded-[8px] bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                                Chat WhatsApp
                            </a>
                            <a href="{{ $site['sentra_sidoarjo_maps_url'] }}"
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
                                {{ $site['sentra_mojokerto_name'] }}
                            </h3>
                            <div class="text-xs text-teks/80 space-y-1">
                                <p><span class="font-medium text-teks">Alamat:</span> {{ $site['sentra_mojokerto_address'] }}</p>
                                <p><span class="font-medium text-teks">Jadwal:</span> {{ $site['service_hours'] }}</p>
                                <p><span class="font-medium text-teks">Kontak:</span> {{ $site['contact_whatsapp'] }}</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 pt-2 border-t border-teks/10">
                            <a href="{{ $site['contact_whatsapp_link'] }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center px-3.5 h-9 rounded-[8px] bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                                Chat WhatsApp
                            </a>
                            <a href="{{ $site['sentra_mojokerto_maps_url'] }}"
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
                        <strong>{{ $site['sentra_sidoarjo_name'] }}:</strong> {{ $site['sentra_sidoarjo_address'] }}.<br>
                        <strong>{{ $site['sentra_mojokerto_name'] }}:</strong> {{ $site['sentra_mojokerto_address'] }}.
                    </p>
                    <div class="pt-2 flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ $site['sentra_sidoarjo_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="text-xs text-primary font-semibold hover:underline">
                            Peta {{ $site['sentra_sidoarjo_name'] }} &rarr;
                        </a>
                        <span class="text-teks/30">&bull;</span>
                        <a href="{{ $site['sentra_mojokerto_maps_url'] }}" target="_blank" rel="noopener noreferrer" class="text-xs text-primary font-semibold hover:underline">
                            Peta {{ $site['sentra_mojokerto_name'] }} &rarr;
                        </a>
                    </div>
                </div>
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

                    {{-- Nomor WhatsApp --}}
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

                    {{-- Email (Opsional) --}}
                    <div>
                        <label for="contact-email" class="block text-xs font-semibold text-teks mb-1.5">
                            Alamat Email <span class="text-teks/50 font-normal">(Opsional)</span>
                        </label>
                        <input type="email"
                               id="contact-email"
                               placeholder="nama@email.com"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 text-xs sm:text-sm text-teks focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <p id="error-email" class="text-[11px] text-rose-600 mt-1 hidden"></p>
                    </div>

                    {{-- Pesan Detail --}}
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

                    {{-- Tombol Kirim --}}
                    <button type="submit"
                            id="btn-submit-contact"
                            class="w-full h-11 rounded-xl bg-primary hover:bg-primary/90 text-white font-semibold text-sm transition-all shadow-xs active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12.031 2c-5.508 0-9.984 4.477-9.984 9.984 0 1.76.459 3.473 1.332 4.987L2 22l5.176-1.356c1.464.8 3.111 1.22 4.855 1.22 5.508 0 9.984-4.477 9.984-9.984 0-5.508-4.476-9.98-9.984-9.98zm0 18.283c-1.508 0-2.986-.406-4.275-1.173l-.307-.183-3.176.833.847-3.096-.201-.321c-.84-1.339-1.284-2.887-1.284-4.459 0-4.57 3.718-8.287 8.287-8.287 4.57 0 8.288 3.717 8.288 8.287 0 4.571-3.718 8.288-8.288 8.288z"/>
                        </svg>
                        <span>Kirim via WhatsApp</span>
                    </button>
                    <p class="text-[11px] text-teks/60 text-center">
                        Informasi Anda dijaga kerahasiaannya dan hanya digunakan untuk komunikasi Kios Mandiri YPKABK.
                    </p>
                </form>

                {{-- Alert Sukses / Fallback Modal Ringkas --}}
                <div id="contact-success-box" class="hidden p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 space-y-3">
                    <div class="flex items-start gap-2.5">
                        <span class="text-base leading-none">✅</span>
                        <div>
                            <p class="font-bold">Format Pesan WhatsApp Telah Dibuat!</p>
                            <p class="mt-0.5 text-emerald-800">
                                Jika tab WhatsApp tidak terbuka secara otomatis, Anda dapat mengklik tombol di bawah atau menyalin pesan secara manual.
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 pt-1">
                        <a id="btn-manual-wa"
                           href="#"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center justify-center px-3.5 h-8 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                            Buka WhatsApp
                        </a>
                        <button type="button"
                                id="btn-copy-wa-text"
                                class="inline-flex items-center justify-center px-3 h-8 rounded-lg border border-emerald-300 text-emerald-800 hover:bg-emerald-100 font-semibold text-xs transition-colors">
                            Salin Pesan
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
@endsection
