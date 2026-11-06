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
<section class="w-full bg-[#FFFBF7] py-12 lg:py-20 pb-24">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">

            {{-- Kolom Kiri (7 Kolom Desktop): Kartu Sentra & Peta Polos --}}
            <div class="lg:col-span-7 space-y-8">
                <div class="space-y-2">
                    <span class="text-xs uppercase tracking-wider font-bold text-primary block">
                        Lokasi Sentra
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2B2430] tracking-tight">
                        Sentra Fisik Kios Mandiri
                    </h2>
                    <p class="text-xs sm:text-sm text-[#2B2430]/75 leading-relaxed">
                        Kunjungi langsung sentra kami untuk menyerahkan barang donasi atau melihat proses workshop.
                    </p>
                </div>

                {{-- Dua Kartu Sentra Berdampingan (Tanpa Badge, Data Sama Persis dengan Footer) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    {{-- Sentra Sidoarjo --}}
                    <div class="bg-white rounded-[12px] p-5 sm:p-6 border border-[#2B2430]/10 flex flex-col justify-between space-y-4 shadow-xs">
                        <div class="space-y-2.5">
                            <h3 class="font-bold text-base text-[#2B2430]">
                                Sentra Sidoarjo
                            </h3>
                            <div class="text-xs text-[#2B2430]/80 space-y-1">
                                <p><strong class="text-[#2B2430]">Alamat:</strong> Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo, Jawa Timur</p>
                                <p><strong class="text-[#2B2430]">Jadwal:</strong> Senin &ndash; Sabtu, 08.00 &ndash; 16.00 WIB</p>
                                <p><strong class="text-[#2B2430]">Kontak:</strong> +62 812-3456-7890</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 pt-2 border-t border-[#2B2430]/10">
                            <a href="https://wa.me/6281234567890"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center px-3.5 h-9 rounded-[8px] bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                                Chat WhatsApp
                            </a>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode('Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang, Sidoarjo') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center px-3.5 h-9 rounded-[8px] border border-[#2B2430]/20 text-[#2B2430] hover:bg-stone-50 font-semibold text-xs transition-colors">
                                Lihat di Peta
                            </a>
                        </div>
                    </div>

                    {{-- Sentra Mojokerto --}}
                    <div class="bg-white rounded-[12px] p-5 sm:p-6 border border-[#2B2430]/10 flex flex-col justify-between space-y-4 shadow-xs">
                        <div class="space-y-2.5">
                            <h3 class="font-bold text-base text-[#2B2430]">
                                Sentra Mojokerto
                            </h3>
                            <div class="text-xs text-[#2B2430]/80 space-y-1">
                                <p><strong class="text-[#2B2430]">Alamat:</strong> Jl. Raya Brangkal No. 12, Sooko, Mojokerto, Jawa Timur</p>
                                <p><strong class="text-[#2B2430]">Jadwal:</strong> Senin &ndash; Sabtu, 08.00 &ndash; 16.00 WIB</p>
                                <p><strong class="text-[#2B2430]">Kontak:</strong> +62 812-3456-7890</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 pt-2 border-t border-[#2B2430]/10">
                            <a href="https://wa.me/6281234567890"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center px-3.5 h-9 rounded-[8px] bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors">
                                Chat WhatsApp
                            </a>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode('Jl. Raya Brangkal No. 12, Sooko, Mojokerto') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center justify-center px-3.5 h-9 rounded-[8px] border border-[#2B2430]/20 text-[#2B2430] hover:bg-stone-50 font-semibold text-xs transition-colors">
                                Lihat di Peta
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Placeholder Peta Polos #FDEEF4 (Berisi Teks Dua Lokasi, Tanpa Embed, Tampil Utuh Berjarak Sebelum Footer) --}}
                <div class="w-full bg-[#FDEEF4] rounded-[12px] border border-[#2B2430]/10 p-6 sm:p-8 text-center space-y-2 shadow-xs">
                    <span class="text-xs font-bold text-primary uppercase tracking-wider block">
                        Peta Wilayah Sentra Jawa Timur (Koridor Sidoarjo &ndash; Mojokerto)
                    </span>
                    <p class="text-xs sm:text-sm text-[#2B2430]/80 leading-relaxed max-w-lg mx-auto">
                        <strong>Sentra 1 (Sidoarjo):</strong> Jl. Wonocolo Pabrik Kulit No. 34, Sepanjang.<br>
                        <strong>Sentra 2 (Mojokerto):</strong> Jl. Raya Brangkal No. 12, Sooko.
                    </p>
                    <p class="text-[11px] text-[#2B2430]/60 pt-1">
                        Akses mudah via rute darat Surabaya &ndash; Mojokerto.
                    </p>
                </div>
            </div>

            {{-- Kolom Kanan (5 Kolom Desktop): Formulir Kontak --}}
            <div class="lg:col-span-5 bg-white rounded-[12px] border border-[#2B2430]/10 p-6 sm:p-8 space-y-5 shadow-xs">
                <div class="space-y-1">
                    <span class="text-xs uppercase tracking-wider font-bold text-primary block">
                        Formulir Kontak
                    </span>
                    <h3 class="font-bold text-lg text-[#2B2430]">
                        Kirim Pesan atau Konsultasi Donasi
                    </h3>
                    <p class="text-xs text-[#2B2430]/70 leading-relaxed">
                        Sampaikan pertanyaan mengenai donasi barang, kunjungan, atau kemitraan.
                    </p>
                </div>

                <form id="contact-form" novalidate class="space-y-4">
                    <div>
                        <label for="contact-name" class="block text-xs font-semibold text-[#2B2430] mb-1">
                            Nama Lengkap *
                        </label>
                        <input type="text"
                               id="contact-name"
                               placeholder="Nama lengkap Anda"
                               class="w-full px-3.5 py-2.5 rounded-[8px] border border-[#2B2430]/20 text-xs sm:text-sm text-[#2B2430] focus:outline-none focus:ring-1 focus:ring-primary">
                        <p id="error-name" class="text-[11px] text-rose-600 mt-1 hidden"></p>
                    </div>

                    <div>
                        <label for="contact-phone" class="block text-xs font-semibold text-[#2B2430] mb-1">
                            Nomor WhatsApp *
                        </label>
                        <input type="tel"
                               id="contact-phone"
                               placeholder="0812xxxxxxx"
                               class="w-full px-3.5 py-2.5 rounded-[8px] border border-[#2B2430]/20 text-xs sm:text-sm text-[#2B2430] focus:outline-none focus:ring-1 focus:ring-primary">
                        <p id="error-phone" class="text-[11px] text-rose-600 mt-1 hidden"></p>
                    </div>

                    <div>
                        <label for="contact-message" class="block text-xs font-semibold text-[#2B2430] mb-1">
                            Pesan Detail *
                        </label>
                        <textarea id="contact-message"
                                  rows="4"
                                  placeholder="Tuliskan pesan atau rencana donasi Anda..."
                                  class="w-full px-3.5 py-2.5 rounded-[8px] border border-[#2B2430]/20 text-xs sm:text-sm text-[#2B2430] focus:outline-none focus:ring-1 focus:ring-primary"></textarea>
                        <p id="error-message" class="text-[11px] text-rose-600 mt-1 hidden"></p>
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

{{-- Validasi & Pengalihan Kontak WhatsApp --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('contact-form');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const nameInput = document.getElementById('contact-name');
            const phoneInput = document.getElementById('contact-phone');
            const messageInput = document.getElementById('contact-message');

            const errName = document.getElementById('error-name');
            const errPhone = document.getElementById('error-phone');
            const errMessage = document.getElementById('error-message');

            let valid = true;

            const name = nameInput?.value?.trim() || '';
            const phone = phoneInput?.value?.trim() || '';
            const message = messageInput?.value?.trim() || '';

            // Reset errors
            [errName, errPhone, errMessage].forEach(el => {
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

            if (!message) {
                valid = false;
                if (errMessage) {
                    errMessage.innerText = 'Pesan detail wajib diisi.';
                    errMessage.classList.remove('hidden');
                }
            }

            if (!valid) return;

            // TODO: nanti diganti pengiriman POST ke backend bila endpoint kontak dibuat temanku
            const text = `Halo Admin Kios Mandiri YPKABK,%0A%0ASaya ingin mengirimkan pesan/konsultasi:%0A- *Nama:* ${encodeURIComponent(name)}%0A- *No. WhatsApp:* ${encodeURIComponent(phone)}%0A- *Pesan:* ${encodeURIComponent(message)}`;
            window.open(`https://wa.me/6281234567890?text=${text}`, '_blank');
            form.reset();
        });
    });
</script>

@include('user.pages.home._modal-donasi')
@include('user.pages.home._scripts')
@endsection
