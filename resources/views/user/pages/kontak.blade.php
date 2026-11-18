@extends('user.shared.Layout')

@section('content')
{{-- Header Halaman --}}
<x-page-header
    title="Kontak & Lokasi Sentra"
    subtitle="Hubungi tim Kios Mandiri YPKABK untuk donasi barang preloved, pembelian produk vokasi, atau kunjungan ke sentra kami."
    pill="Respon Cepat 1x24 Jam"
    breadcrumb="Beranda / Kontak"
/>

{{-- 1. Kartu Saluran Komunikasi Cepat (Quick Access Cards) --}}
<section class="w-full bg-krem pt-10 pb-6 border-b border-teks/10">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- Kartu WhatsApp --}}
            <div class="bg-white rounded-2xl p-6 border border-teks/10 shadow-xs flex flex-col justify-between space-y-4 hover:border-emerald-500/40 transition-all">
                <div class="space-y-3">
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12.031 2c-5.508 0-9.984 4.477-9.984 9.984 0 1.76.459 3.473 1.332 4.987L2 22l5.176-1.356c1.464.8 3.111 1.22 4.855 1.22 5.508 0 9.984-4.477 9.984-9.984 0-5.508-4.476-9.98-9.984-9.98zm0 18.283c-1.508 0-2.986-.406-4.275-1.173l-.307-.183-3.176.833.847-3.096-.201-.321c-.84-1.339-1.284-2.887-1.284-4.459 0-4.57 3.718-8.287 8.287-8.287 4.57 0 8.288 3.717 8.288 8.287 0 4.571-3.718 8.288-8.288 8.288zm4.542-6.208c-.249-.125-1.472-.726-1.7-.809-.228-.083-.394-.125-.56.125-.166.249-.643.809-.788.975-.145.166-.29.187-.539.062s-1.05-.387-2.001-1.234c-.74-.659-1.24-1.474-1.385-1.723-.145-.249-.015-.383.109-.507.112-.111.249-.29.373-.435.125-.145.166-.249.249-.415.083-.166.041-.311-.021-.435-.062-.125-.56-1.349-.768-1.847-.202-.486-.407-.42-.56-.428l-.477-.008c-.166 0-.435.062-.663.311-.228.249-.87 0.851-.87 2.074 0 1.224.891 2.406 1.015 2.572.125.166 1.753 2.677 4.248 3.754.594.257 1.058.41 1.42.525.597.19 1.14.163 1.569.099.479-.071 1.472-.601 1.68-1.182.207-.581.207-1.079.145-1.182-.062-.104-.228-.166-.477-.29z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 block">WhatsApp Hotline</span>
                        <h3 class="font-bold text-lg text-teks mt-0.5">+62 812-3456-7890</h3>
                        <p class="text-xs text-teks/75 mt-1 leading-relaxed">
                            Respon cepat setiap Senin &ndash; Sabtu untuk info donasi &amp; pemesanan produk kriya.
                        </p>
                    </div>
                </div>
                <div class="pt-3 border-t border-teks/10">
                    <a href="https://wa.me/6281234567890"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center justify-center w-full h-10 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors shadow-xs">
                        Chat WhatsApp Sekarang
                    </a>
                </div>
            </div>

            {{-- Kartu Email Resmi --}}
            <div class="bg-white rounded-2xl p-6 border border-teks/10 shadow-xs flex flex-col justify-between space-y-4 hover:border-primary/40 transition-all">
                <div class="space-y-3">
                    <div class="w-11 h-11 rounded-xl bg-pink-pucat text-primary flex items-center justify-center font-bold text-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-primary block">Surat Elektronik</span>
                        <h3 class="font-bold text-lg text-teks mt-0.5 break-all">kios@pedulikasihabk.org</h3>
                        <p class="text-xs text-teks/75 mt-1 leading-relaxed">
                            Saluran resmi untuk kerja sama CSR instansi, undangan kemitraan, dan permohonan kunjungan.
                        </p>
                    </div>
                </div>
                <div class="pt-3 border-t border-teks/10 flex gap-2">
                    <a href="mailto:kios@pedulikasihabk.org"
                       class="inline-flex items-center justify-center flex-1 h-10 rounded-xl bg-primary hover:bg-primary/90 text-white font-semibold text-xs transition-colors shadow-xs">
                        Kirim Email
                    </a>
                    <button type="button"
                            onclick="copyToClipboard('kios@pedulikasihabk.org', 'Alamat email berhasil disalin!')"
                            class="inline-flex items-center justify-center px-3.5 h-10 rounded-xl border border-teks/20 text-teks hover:bg-stone-50 font-semibold text-xs transition-colors"
                            title="Salin Alamat Email">
                        Salin
                    </button>
                </div>
            </div>

            {{-- Kartu Jam Operasional & Layanan --}}
            <div class="bg-white rounded-2xl p-6 border border-teks/10 shadow-xs flex flex-col justify-between space-y-4 hover:border-teks/30 transition-all">
                <div class="space-y-3">
                    <div class="w-11 h-11 rounded-xl bg-stone-100 text-teks flex items-center justify-center font-bold text-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-teks/70 block">Jam Pelayanan Sentra</span>
                        <h3 class="font-bold text-lg text-teks mt-0.5">Senin &ndash; Sabtu</h3>
                        <p class="text-xs text-teks/75 mt-1 leading-relaxed">
                            Pukul <strong class="text-teks">08.00 &ndash; 16.00 WIB</strong>.<br>
                            Minggu dan Hari Libur Nasional tutup untuk kegiatan pembinaan internal.
                        </p>
                    </div>
                </div>
                <div class="pt-3 border-t border-teks/10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] font-semibold w-full justify-center">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Menerima Donasi Langsung Setiap Hari Kerja</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 2. Konten Utama: Lokasi Sentra, Peta Interaktif, & Formulir Kontak --}}
<section class="w-full bg-krem py-12 lg:py-16">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            {{-- Kolom Kiri (7 Kolom Desktop): Kartu Sentra & Peta Interaktif Tabbed --}}
            <div class="lg:col-span-7 space-y-8">
                <div class="space-y-2">
                    <span class="text-xs uppercase tracking-wider font-bold text-primary block">
                        Sentra Fisik Kami
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-teks tracking-tight">
                        Lokasi Workshop &amp; Sentra Kios Mandiri
                    </h2>
                    <p class="text-xs sm:text-sm text-teks/75 leading-relaxed">
                        Anda dapat menyerahkan donasi barang secara langsung, melihat ruang kurasi, atau berkunjung ke sentra pelatihan vokasi kami di dua lokasi berikut.
                    </p>
                </div>

                {{-- Dua Kartu Sentra Berdampingan --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Sentra Sidoarjo --}}
                    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-teks/10 flex flex-col justify-between space-y-4 shadow-xs hover:shadow-md transition-shadow">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="font-bold text-base text-teks">
                                    Sentra Sidoarjo
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-pink-pucat text-primary border border-primary/20">
                                    Sentra Utama
                                </span>
                            </div>
                            <div class="text-xs text-teks/80 space-y-2 leading-relaxed">
                                <p>
                                    <strong class="text-teks block mb-0.5">Alamat:</strong>
                                    Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo, Jawa Timur
                                </p>
                                <p>
                                    <strong class="text-teks block mb-0.5">Jadwal:</strong>
                                    Senin &ndash; Sabtu, 08.00 &ndash; 16.00 WIB
                                </p>
                                <p>
                                    <strong class="text-teks block mb-0.5">Kontak Sentra:</strong>
                                    +62 812-3456-7890
                                </p>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-teks/10 space-y-2">
                            <div class="flex flex-wrap gap-2">
                                <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin Kios Mandiri, saya ingin berkunjung / menyerahkan donasi ke Sentra Sidoarjo.') }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center px-3.5 h-9 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                                    Chat Sentra
                                </a>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode('Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo') }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center px-3.5 h-9 rounded-xl border border-teks/20 text-teks hover:bg-stone-50 font-semibold text-xs transition-colors">
                                    Buka Rute
                                </a>
                                <button type="button"
                                        onclick="copyToClipboard('Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo, Jawa Timur', 'Alamat Sentra Sidoarjo berhasil disalin!')"
                                        class="inline-flex items-center justify-center px-3 h-9 rounded-xl border border-teks/20 text-teks/80 hover:text-teks hover:bg-stone-50 text-xs font-semibold transition-colors"
                                        title="Salin Alamat">
                                    Salin Alamat
                                </button>
                            </div>
                            <button type="button"
                                    onclick="switchMap('sidoarjo')"
                                    class="text-[11px] text-primary hover:underline font-semibold block pt-1">
                                &rarr; Tampilkan di Peta Interaktif
                            </button>
                        </div>
                    </div>

                    {{-- Sentra Mojokerto --}}
                    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-teks/10 flex flex-col justify-between space-y-4 shadow-xs hover:shadow-md transition-shadow">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="font-bold text-base text-teks">
                                    Sentra Mojokerto
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    Sentra Vokasi
                                </span>
                            </div>
                            <div class="text-xs text-teks/80 space-y-2 leading-relaxed">
                                <p>
                                    <strong class="text-teks block mb-0.5">Alamat:</strong>
                                    Jl. Raya Brangkal No. 12, Sooko, Mojokerto, Jawa Timur
                                </p>
                                <p>
                                    <strong class="text-teks block mb-0.5">Jadwal:</strong>
                                    Senin &ndash; Sabtu, 08.00 &ndash; 16.00 WIB
                                </p>
                                <p>
                                    <strong class="text-teks block mb-0.5">Kontak Sentra:</strong>
                                    +62 812-3456-7890
                                </p>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-teks/10 space-y-2">
                            <div class="flex flex-wrap gap-2">
                                <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin Kios Mandiri, saya ingin berkunjung / menyerahkan donasi ke Sentra Mojokerto.') }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center px-3.5 h-9 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                                    Chat Sentra
                                </a>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode('Jl. Raya Brangkal No. 12, Sooko, Mojokerto') }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center px-3.5 h-9 rounded-xl border border-teks/20 text-teks hover:bg-stone-50 font-semibold text-xs transition-colors">
                                    Buka Rute
                                </a>
                                <button type="button"
                                        onclick="copyToClipboard('Jl. Raya Brangkal No. 12, Sooko, Mojokerto, Jawa Timur', 'Alamat Sentra Mojokerto berhasil disalin!')"
                                        class="inline-flex items-center justify-center px-3 h-9 rounded-xl border border-teks/20 text-teks/80 hover:text-teks hover:bg-stone-50 text-xs font-semibold transition-colors"
                                        title="Salin Alamat">
                                    Salin Alamat
                                </button>
                            </div>
                            <button type="button"
                                    onclick="switchMap('mojokerto')"
                                    class="text-[11px] text-primary hover:underline font-semibold block pt-1">
                                &rarr; Tampilkan di Peta Interaktif
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Peta Interaktif Tabbed Google Maps --}}
                <div class="w-full bg-white rounded-2xl border border-teks/10 p-5 sm:p-6 space-y-4 shadow-xs">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-teks/10 pb-4">
                        <div>
                            <span class="text-xs font-bold text-primary uppercase tracking-wider block">
                                Peta Navigasi Interaktif
                            </span>
                            <h4 id="map-active-title" class="font-bold text-base text-teks mt-0.5">
                                Sentra Sidoarjo (Sepanjang)
                            </h4>
                        </div>

                        {{-- Tab Switcher --}}
                        <div class="inline-flex p-1 bg-stone-100 rounded-xl border border-teks/10 self-start sm:self-auto text-xs font-semibold">
                            <button type="button"
                                    id="tab-btn-sidoarjo"
                                    onclick="switchMap('sidoarjo')"
                                    class="px-3.5 py-1.5 rounded-lg bg-white text-teks shadow-xs font-bold transition-all">
                                Sidoarjo
                            </button>
                            <button type="button"
                                    id="tab-btn-mojokerto"
                                    onclick="switchMap('mojokerto')"
                                    class="px-3.5 py-1.5 rounded-lg text-teks/70 hover:text-teks transition-all">
                                Mojokerto
                            </button>
                        </div>
                    </div>

                    {{-- Kontainer Iframe Peta --}}
                    <div class="relative w-full h-[320px] sm:h-[380px] rounded-xl overflow-hidden bg-stone-100 border border-teks/10">
                        <iframe id="map-iframe"
                                src="https://maps.google.com/maps?q=Jl.+Wonocolo+Pabrik+Kulit+No.+34,+Sepanjang,+Sidoarjo&t=&z=15&ie=UTF8&iwloc=&output=embed"
                                class="w-full h-full border-0"
                                loading="lazy"
                                allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Peta Lokasi Sentra Kios Mandiri">
                        </iframe>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-teks/75 pt-1">
                        <p id="map-active-desc">
                            📍 <strong>Sentra Sidoarjo:</strong> Berlokasi strategis di Sepanjang, dekat akses stasiun dan jalur penghubung Surabaya &ndash; Sidoarjo.
                        </p>
                        <a id="map-direct-link"
                           href="https://www.google.com/maps/search/?api=1&query={{ urlencode('Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo') }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center gap-1 font-semibold text-primary hover:underline shrink-0">
                            <span>Buka di Google Maps</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan (5 Kolom Desktop): Formulir Kontak --}}
            <div class="lg:col-span-5 bg-white rounded-2xl border border-teks/10 p-6 sm:p-8 space-y-6 shadow-xs sticky top-24">
                <div class="space-y-1.5 border-b border-teks/10 pb-4">
                    <span class="text-xs uppercase tracking-wider font-bold text-primary block">
                        Formulir Kontak
                    </span>
                    <h3 class="font-bold text-xl text-teks">
                        Kirim Pesan &amp; Konsultasi
                    </h3>
                    <p class="text-xs text-teks/75 leading-relaxed">
                        Lengkapi formulir di bawah ini untuk konsultasi donasi barang, pembelian produk, kemitraan, atau kunjungan. Pesan akan diteruskan langsung ke WhatsApp tim admin kami.
                    </p>
                </div>

                <form id="contact-form" novalidate class="space-y-4">
                    {{-- Kategori / Subjek --}}
                    <div>
                        <label for="contact-category" class="block text-xs font-semibold text-teks mb-1.5">
                            Kategori Keperluan *
                        </label>
                        <select id="contact-category"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 text-xs sm:text-sm text-teks bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                            <option value="Konsultasi Donasi Barang Preloved">Konsultasi Donasi Barang Preloved</option>
                            <option value="Pertanyaan Pembelian & Katalog Produk">Pertanyaan Pembelian &amp; Katalog Produk</option>
                            <option value="Kunjungan Sentra & Edukasi ABK">Kunjungan Sentra &amp; Edukasi ABK</option>
                            <option value="Kemitraan Perusahaan & Kerjasama CSR">Kemitraan Perusahaan &amp; Kerjasama CSR</option>
                            <option value="Pertanyaan Umum Lainnya">Pertanyaan Umum Lainnya</option>
                        </select>
                    </div>

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="contact-name" class="block text-xs font-semibold text-teks mb-1.5">
                            Nama Lengkap *
                        </label>
                        <input type="text"
                               id="contact-name"
                               placeholder="Contoh: Budi Santoso"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 text-xs sm:text-sm text-teks focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <p id="error-name" class="text-[11px] text-rose-600 mt-1 hidden"></p>
                    </div>

                    {{-- Nomor WhatsApp --}}
                    <div>
                        <label for="contact-phone" class="block text-xs font-semibold text-teks mb-1.5">
                            Nomor WhatsApp *
                        </label>
                        <input type="tel"
                               id="contact-phone"
                               placeholder="Contoh: 081234567890"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 text-xs sm:text-sm text-teks focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary">
                        <p id="error-phone" class="text-[11px] text-rose-600 mt-1 hidden"></p>
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
                        <label for="contact-message" class="block text-xs font-semibold text-teks mb-1.5">
                            Pesan Detail *
                        </label>
                        <textarea id="contact-message"
                                  rows="4"
                                  placeholder="Ceritakan rencana donasi Anda (jenis & jumlah barang) atau pertanyaan yang ingin disampaikan..."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 text-xs sm:text-sm text-teks focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"></textarea>
                        <p id="error-message" class="text-[11px] text-rose-600 mt-1 hidden"></p>
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

{{-- 3. Bagian FAQ Interaktif (Pertanyaan yang Sering Diajukan) --}}
<section class="w-full bg-pink-pucat py-14 lg:py-20 border-t border-teks/10">
    <div class="max-w-[1200px] mx-auto px-6 space-y-10">
        <div class="text-center max-w-2xl mx-auto space-y-2">
            <span class="text-xs uppercase tracking-wider font-bold text-primary block">
                Pusat Bantuan
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-teks tracking-tight">
                Pertanyaan yang Sering Diajukan
            </h2>
            <p class="text-xs sm:text-sm text-teks/75 leading-relaxed">
                Temukan jawaban cepat seputar serah terima barang donasi, teknis penjemputan, hingga kunjungan langsung ke sentra.
            </p>
        </div>

        <div class="max-w-3xl mx-auto space-y-4">
            {{-- FAQ 1 --}}
            <div class="faq-item bg-white rounded-2xl border border-teks/10 overflow-hidden shadow-xs">
                <button type="button"
                        onclick="toggleFaq(this)"
                        class="w-full px-6 py-4.5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-teks hover:text-primary transition-colors focus:outline-none">
                    <span>Kapan waktu terbaik untuk mengantar langsung barang donasi ke sentra?</span>
                    <span class="faq-icon text-lg text-teks/50 font-normal shrink-0 transition-transform duration-200">+</span>
                </button>
                <div class="faq-content hidden px-6 pb-5 pt-1 text-xs sm:text-sm text-teks/80 leading-relaxed border-t border-teks/5">
                    Anda dapat mengantar donasi pada hari kerja <strong>Senin sampai Sabtu pukul 08.00 &ndash; 16.00 WIB</strong> baik di Sentra Sidoarjo maupun Sentra Mojokerto. Relawan kami selalu siap menyambut dan membantu proses bongkar muat barang.
                </div>
            </div>

            {{-- FAQ 2 --}}
            <div class="faq-item bg-white rounded-2xl border border-teks/10 overflow-hidden shadow-xs">
                <button type="button"
                        onclick="toggleFaq(this)"
                        class="w-full px-6 py-4.5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-teks hover:text-primary transition-colors focus:outline-none">
                    <span>Apakah tersedia layanan penjemputan barang donasi ke rumah atau kantor?</span>
                    <span class="faq-icon text-lg text-teks/50 font-normal shrink-0 transition-transform duration-200">+</span>
                </button>
                <div class="faq-content hidden px-6 pb-5 pt-1 text-xs sm:text-sm text-teks/80 leading-relaxed border-t border-teks/5">
                    Ya, layanan jemput donasi oleh armada relawan Kios Mandiri tersedia untuk area <strong>Surabaya, Sidoarjo, dan Mojokerto</strong> (dengan minimal donasi 5 barang layak guna). Jadwal penjemputan dapat diselaraskan melalui WhatsApp hotline kami.
                </div>
            </div>

            {{-- FAQ 3 --}}
            <div class="faq-item bg-white rounded-2xl border border-teks/10 overflow-hidden shadow-xs">
                <button type="button"
                        onclick="toggleFaq(this)"
                        class="w-full px-6 py-4.5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-teks hover:text-primary transition-colors focus:outline-none">
                    <span>Barang apa saja yang diterima oleh Kios Mandiri YPKABK?</span>
                    <span class="faq-icon text-lg text-teks/50 font-normal shrink-0 transition-transform duration-200">+</span>
                </button>
                <div class="faq-content hidden px-6 pb-5 pt-1 text-xs sm:text-sm text-teks/80 leading-relaxed border-t border-teks/5">
                    Kami menerima pakaian anak/dewasa bersih dan layak pakai, buku bacaan &amp; ensiklopedia, mainan edukatif lengkap, kerajinan kayu, perlengkapan rumah tangga kecil, serta bahan prakarya untuk terapi motorik. Kami tidak menerima pakaian rusak/noda berat, makanan, obat-obatan, atau kasur/perabot berukuran raksasa.
                </div>
            </div>

            {{-- FAQ 4 --}}
            <div class="faq-item bg-white rounded-2xl border border-teks/10 overflow-hidden shadow-xs">
                <button type="button"
                        onclick="toggleFaq(this)"
                        class="w-full px-6 py-4.5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-teks hover:text-primary transition-colors focus:outline-none">
                    <span>Bagaimana alur pengajuan kunjungan studi, liputan, atau program CSR perusahaan?</span>
                    <span class="faq-icon text-lg text-teks/50 font-normal shrink-0 transition-transform duration-200">+</span>
                </button>
                <div class="faq-content hidden px-6 pb-5 pt-1 text-xs sm:text-sm text-teks/80 leading-relaxed border-t border-teks/5">
                    Silakan mengirimkan surat permohonan resmi ke email <strong>kios@pedulikasihabk.org</strong> atau mengisi formulir kontak di atas dengan memilih kategori <em>"Kemitraan Perusahaan & Kerjasama CSR"</em>. Tim divisi relasi eksternal kami akan merespons dalam 1x24 jam kerja untuk menyusun agenda dan jadwal kunjungan sentra.
                </div>
            </div>

            {{-- FAQ 5 --}}
            <div class="faq-item bg-white rounded-2xl border border-teks/10 overflow-hidden shadow-xs">
                <button type="button"
                        onclick="toggleFaq(this)"
                        class="w-full px-6 py-4.5 text-left flex items-center justify-between gap-4 font-bold text-sm sm:text-base text-teks hover:text-primary transition-colors focus:outline-none">
                    <span>Apakah hasil penjualan barang benar-benar 100% untuk pembinaan ABK?</span>
                    <span class="faq-icon text-lg text-teks/50 font-normal shrink-0 transition-transform duration-200">+</span>
                </button>
                <div class="faq-content hidden px-6 pb-5 pt-1 text-xs sm:text-sm text-teks/80 leading-relaxed border-t border-teks/5">
                    Tepat sekali. Seluruh surplus penjualan produk kurasi donasi dan karya kriya dialokasikan untuk pembiayaan sesi terapi sensori-motorik gratis, pengadaan alat peraga edukatif, serta beasiswa pelatihan vokasi mandiri bagi anak-anak berkebutuhan khusus binaan YPKABK di Jawa Timur. Anda dapat meninjau laporan berkala di menu <a href="{{ route('dampak') }}" class="text-primary font-semibold underline">Dampak &amp; Transparansi</a>.
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Toast Feedback Salin Clipboard --}}
<div id="toast-clipboard"
     class="fixed bottom-6 right-6 z-50 transform translate-y-16 opacity-0 transition-all duration-300 pointer-events-none bg-teks text-white text-xs font-semibold px-4 py-3 rounded-xl shadow-lg flex items-center gap-2">
    <span>📋</span>
    <span id="toast-clipboard-text">Teks berhasil disalin!</span>
</div>

{{-- Skrip Interaktivitas Halaman Kontak --}}
<script>
    // Data Lokasi Peta
    const mapLocations = {
        sidoarjo: {
            title: 'Sentra Sidoarjo (Sepanjang)',
            desc: '📍 <strong>Sentra Sidoarjo:</strong> Berlokasi strategis di Sepanjang, dekat akses stasiun dan jalur penghubung Surabaya &ndash; Sidoarjo.',
            iframeSrc: 'https://maps.google.com/maps?q=Jl.+Wonocolo+Pabrik+Kulit+No.+34,+Sepanjang,+Sidoarjo&t=&z=15&ie=UTF8&iwloc=&output=embed',
            directUrl: 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent('Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo')
        },
        mojokerto: {
            title: 'Sentra Mojokerto (Sooko)',
            desc: '📍 <strong>Sentra Mojokerto:</strong> Berlokasi di jalur utama Brangkal Sooko, pusat pelatihan kriya kayu dan kerajinan siswa ABK.',
            iframeSrc: 'https://maps.google.com/maps?q=Jl.+Raya+Brangkal+No.+12,+Sooko,+Mojokerto&t=&z=15&ie=UTF8&iwloc=&output=embed',
            directUrl: 'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent('Jl. Raya Brangkal No. 12, Sooko, Mojokerto')
        }
    };

    // Fungsi Pengalih Peta
    function switchMap(target) {
        const data = mapLocations[target];
        if (!data) return;

        const iframe = document.getElementById('map-iframe');
        const titleEl = document.getElementById('map-active-title');
        const descEl = document.getElementById('map-active-desc');
        const directLink = document.getElementById('map-direct-link');

        const btnSidoarjo = document.getElementById('tab-btn-sidoarjo');
        const btnMojokerto = document.getElementById('tab-btn-mojokerto');

        if (iframe) iframe.src = data.iframeSrc;
        if (titleEl) titleEl.innerText = data.title;
        if (descEl) descEl.innerHTML = data.desc;
        if (directLink) directLink.href = data.directUrl;

        if (target === 'sidoarjo') {
            btnSidoarjo?.classList.add('bg-white', 'text-teks', 'shadow-xs', 'font-bold');
            btnSidoarjo?.classList.remove('text-teks/70');
            btnMojokerto?.classList.remove('bg-white', 'text-teks', 'shadow-xs', 'font-bold');
            btnMojokerto?.classList.add('text-teks/70');
        } else {
            btnMojokerto?.classList.add('bg-white', 'text-teks', 'shadow-xs', 'font-bold');
            btnMojokerto?.classList.remove('text-teks/70');
            btnSidoarjo?.classList.remove('bg-white', 'text-teks', 'shadow-xs', 'font-bold');
            btnSidoarjo?.classList.add('text-teks/70');
        }
    }

    // Fungsi Salin ke Clipboard dengan Toast Notifikasi
    function copyToClipboard(text, customMessage = 'Teks berhasil disalin!') {
        if (!navigator.clipboard) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                showToast(customMessage);
            } catch (err) {
                alert('Gagal menyalin: ' + text);
            }
            document.body.removeChild(textarea);
            return;
        }

        navigator.clipboard.writeText(text).then(() => {
            showToast(customMessage);
        }).catch(() => {
            showToast('Gagal menyalin teks');
        });
    }

    function showToast(message) {
        const toast = document.getElementById('toast-clipboard');
        const toastText = document.getElementById('toast-clipboard-text');
        if (!toast || !toastText) return;

        toastText.innerText = message;
        toast.classList.remove('translate-y-16', 'opacity-0', 'pointer-events-none');
        toast.classList.add('translate-y-0', 'opacity-100');

        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-16', 'opacity-0', 'pointer-events-none');
        }, 2800);
    }

    // Fungsi Toggle Accordion FAQ
    function toggleFaq(button) {
        const item = button.closest('.faq-item');
        if (!item) return;

        const content = item.querySelector('.faq-content');
        const icon = item.querySelector('.faq-icon');
        const isHidden = content.classList.contains('hidden');

        // Tutup item lain untuk kerapian tampilan
        document.querySelectorAll('.faq-item').forEach(other => {
            if (other !== item) {
                other.querySelector('.faq-content')?.classList.add('hidden');
                const otherIcon = other.querySelector('.faq-icon');
                if (otherIcon) {
                    otherIcon.innerText = '+';
                    otherIcon.classList.remove('rotate-45');
                }
            }
        });

        if (isHidden) {
            content.classList.remove('hidden');
            if (icon) {
                icon.innerText = '−';
            }
        } else {
            content.classList.add('hidden');
            if (icon) {
                icon.innerText = '+';
            }
        }
    }

    // Validasi & Pembuatan Pesan WhatsApp
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('contact-form');
        if (!form) return;

        let latestFormattedText = '';

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const categoryInput = document.getElementById('contact-category');
            const nameInput = document.getElementById('contact-name');
            const phoneInput = document.getElementById('contact-phone');
            const emailInput = document.getElementById('contact-email');
            const messageInput = document.getElementById('contact-message');

            const errName = document.getElementById('error-name');
            const errPhone = document.getElementById('error-phone');
            const errEmail = document.getElementById('error-email');
            const errMessage = document.getElementById('error-message');
            const successBox = document.getElementById('contact-success-box');
            const manualWaBtn = document.getElementById('btn-manual-wa');

            let valid = true;

            const category = categoryInput?.value?.trim() || 'Konsultasi Donasi Barang Preloved';
            const name = nameInput?.value?.trim() || '';
            const phone = phoneInput?.value?.trim() || '';
            const email = emailInput?.value?.trim() || '';
            const message = messageInput?.value?.trim() || '';

            // Reset pesan error
            [errName, errPhone, errEmail, errMessage].forEach(el => {
                if (el) {
                    el.innerText = '';
                    el.classList.add('hidden');
                }
            });

            if (!name) {
                valid = false;
                if (errName) {
                    errName.innerText = 'Nama lengkap wajib diisi.';
                    errName.classList.remove('hidden');
                }
            }

            if (!phone) {
                valid = false;
                if (errPhone) {
                    errPhone.innerText = 'Nomor WhatsApp wajib diisi.';
                    errPhone.classList.remove('hidden');
                }
            } else if (phone.length < 8) {
                valid = false;
                if (errPhone) {
                    errPhone.innerText = 'Nomor WhatsApp tidak valid (minimal 8 digit).';
                    errPhone.classList.remove('hidden');
                }
            }

            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                valid = false;
                if (errEmail) {
                    errEmail.innerText = 'Format email tidak valid.';
                    errEmail.classList.remove('hidden');
                }
            }

            if (!message) {
                valid = false;
                if (errMessage) {
                    errMessage.innerText = 'Pesan detail wajib diisi.';
                    errMessage.classList.remove('hidden');
                }
            }

            if (!valid) return;

            // Format pesan WhatsApp terstruktur
            let rawText = `Halo Admin Kios Mandiri YPKABK,\n\nSaya ingin berkonsultasi mengenai:\n*Topik:* ${category}\n*Nama:* ${name}\n*No. WhatsApp:* ${phone}`;
            if (email) {
                rawText += `\n*Email:* ${email}`;
            }
            rawText += `\n\n*Pesan Detail:*\n${message}`;

            latestFormattedText = rawText;
            const waUrl = `https://wa.me/6281234567890?text=${encodeURIComponent(rawText)}`;

            // Tampilkan alert sukses dan link fallback
            if (successBox) {
                successBox.classList.remove('hidden');
            }
            if (manualWaBtn) {
                manualWaBtn.href = waUrl;
            }

            // Buka WhatsApp di tab baru
            window.open(waUrl, '_blank');
        });

        // Event listener untuk tombol salin format pesan di box sukses
        const copyWaTextBtn = document.getElementById('btn-copy-wa-text');
        if (copyWaTextBtn) {
            copyWaTextBtn.addEventListener('click', () => {
                if (latestFormattedText) {
                    copyToClipboard(latestFormattedText, 'Pesan WhatsApp berhasil disalin!');
                }
            });
        }
    });
</script>

@include('user.pages.home._modal-donasi')
@include('user.pages.home._scripts')
@endsection
