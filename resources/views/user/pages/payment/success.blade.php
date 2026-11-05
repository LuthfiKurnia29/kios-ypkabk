@extends('user.shared.Layout')

@section('content')
<div class="space-y-10 lg:space-y-14 pb-20 pt-24">

    {{-- SUCCESS HEADER & RECEIPT CARD --}}
    <div class="max-w-3xl mx-auto px-4 sm:px-6">

        {{-- Top Success Banner --}}
        <div class="text-center space-y-4 mb-8">
            <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-4xl shadow-sm border border-emerald-200">
                🎉
            </div>

            <div class="space-y-1">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                    Pembayaran Berhasil Diverifikasi
                </span>
                <h1 class="font-heading text-3xl sm:text-4xl font-extrabold text-ink tracking-tight">
                    Terima Kasih Banyak, Orang Baik!
                </h1>
                <p class="text-sm sm:text-base text-ink/75 max-w-lg mx-auto leading-relaxed">
                    Pesanan dan donasi Anda telah kami terima melalui <strong>DOKU Payment Gateway</strong>. Tim Kios Mandiri YPKABK akan segera menyiapkan dan mengemas barang pilihan Anda.
                </p>
            </div>
        </div>

        {{-- Printable Donation Receipt Card --}}
        <div id="printable-receipt" class="bg-white rounded-3xl p-6 sm:p-10 border border-ink/15 shadow-md space-y-6">

            {{-- Receipt Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-ink/10 gap-4">
                <div class="space-y-1">
                    <img src="{{ asset('images/logo/logo.webp') }}" alt="Logo Kios Mandiri YPKABK" class="h-10 w-auto object-contain">
                    <p class="text-[11px] text-ink/60">Unit Usaha Sosial Yayasan Peduli Kasih ABK</p>
                </div>

                <div class="sm:text-right space-y-1">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-600 text-white shadow-xs">
                        <span>✓</span> LUNAS (PAID)
                    </span>
                    <p class="text-xs font-mono font-bold text-ink">
                        {{ $purchase->order_code }}
                    </p>
                </div>
            </div>

            {{-- Customer & Transaction Metadata --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-ink/80 pb-6 border-b border-ink/10">
                <div class="space-y-1.5">
                    <span class="text-[10px] uppercase font-bold text-ink/50 tracking-wider block">Data Pemesan</span>
                    <p><strong class="text-ink">Nama:</strong> {{ $purchase->customer_name }}</p>
                    <p><strong class="text-ink">No. WhatsApp:</strong> {{ $purchase->customer_phone }}</p>
                    <p><strong class="text-ink">Alamat Kirim:</strong> {{ $purchase->customer_address ?: '-' }}</p>
                </div>

                <div class="space-y-1.5 sm:text-right">
                    <span class="text-[10px] uppercase font-bold text-ink/50 tracking-wider block">Informasi Pembayaran</span>
                    <p><strong class="text-ink">Metode:</strong> DOKU ({{ $purchase->payment_channel ?: 'Payment Gateway' }})</p>
                    <p><strong class="text-ink">No. Referensi:</strong> <span class="font-mono text-ink">{{ $purchase->payment_reference ?: '-' }}</span></p>
                    <p><strong class="text-ink">Waktu Lunas:</strong> {{ $purchase->paid_at ? $purchase->paid_at->format('d M Y, H:i') . ' WIB' : now()->format('d M Y, H:i') . ' WIB' }}</p>
                </div>
            </div>

            {{-- Items Purchased Table --}}
            <div class="space-y-3">
                <span class="text-xs font-bold text-ink block uppercase tracking-wider">
                    Daftar Barang &amp; Donasi
                </span>

                <div class="divide-y divide-ink/10 text-xs text-ink">
                    @forelse($purchase->detailPurchases as $detail)
                        <div class="py-2.5 flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <span class="font-bold text-ink block truncate">{{ $detail->product?->name ?? 'Produk Donasi' }}</span>
                                <span class="text-[11px] text-ink/60">{{ $detail->quantity }} pcs × Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
                            </div>
                            <span class="font-bold text-ink shrink-0">
                                Rp {{ number_format($detail->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    @empty
                        <div class="py-2 text-ink/60">Rincian produk tersimpan pada sistem.</div>
                    @endforelse
                </div>
            </div>

            {{-- Totals Calculation --}}
            <div class="pt-4 border-t border-ink/10 space-y-2 text-xs text-ink/80">
                <div class="flex justify-between">
                    <span>Subtotal Barang:</span>
                    <span class="font-semibold text-ink">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Kemasan Kardus Daur Ulang:</span>
                    <span class="font-bold text-emerald-600">Gratis</span>
                </div>
                <div class="flex justify-between items-baseline pt-2 border-t border-ink/10">
                    <span class="font-bold text-ink text-sm sm:text-base">Total Nilai Donasi:</span>
                    <span class="font-heading font-extrabold text-ink text-xl sm:text-2xl">
                        Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Social Impact Commitment --}}
            <div class="rounded-2xl p-4 bg-cream border border-ink/15 space-y-2 text-xs leading-relaxed text-ink/80">
                <div class="flex items-center gap-1.5 font-bold text-ink">
                    <span>💖</span>
                    <span>Dampak Nyata Donasi Anda</span>
                </div>
                <p class="text-[11px]">
                    100% margin penjualan ini langsung dialokasikan ke pos beasiswa terapi wicara, sensori integrasi, dan pelatihan vokasi kemandirian anak-anak istimewa binaan Yayasan Peduli Kasih ABK.
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-4 flex flex-col sm:flex-row items-center gap-3">
                <button type="button"
                        onclick="window.print()"
                        class="w-full sm:w-1/2 min-h-[48px] py-3 px-4 rounded-full border border-ink/20 hover:bg-cream text-ink font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition-all active:scale-95">
                    <svg class="w-4 h-4 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak / Simpan Bukti</span>
                </button>

                @php
                    $waText = "Halo Admin Kios Mandiri YPKABK,%0A%0ASaya telah menyelesaikan pembayaran via DOKU Payment Gateway:%0A- *Kode Pesanan:* {$purchase->order_code}%0A- *Nama Pemesan:* " . urlencode($purchase->customer_name) . "%0A- *Total Donasi:* Rp " . number_format($purchase->total_price, 0, ',', '.') . "%0A- *Metode:* DOKU ({$purchase->payment_channel})%0A%0AMohon konfirmasi pengiriman paket pesanan saya. Terima kasih!";
                @endphp

                <a href="https://wa.me/6281234567890?text={{ $waText }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="w-full sm:w-1/2 min-h-[48px] py-3 px-4 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                    </svg>
                    <span>Konfirmasi WhatsApp Admin</span>
                </a>
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('katalog') }}" class="text-xs font-semibold text-ink/70 hover:text-ink">
                    &larr; Kembali Jelajahi Katalog Kios
                </a>
            </div>

        </div>

    </div>

</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printable-receipt, #printable-receipt * {
        visibility: visible;
    }
    #printable-receipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
        box-shadow: none !important;
    }
    header, footer, a, button {
        display: none !important;
    }
}
</style>
@endsection

