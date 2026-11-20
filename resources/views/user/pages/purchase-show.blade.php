@extends('user.shared.Layout')

@section('title', 'Detail Pesanan ' . $purchase->order_code)

@section('content')
@php
    $statusColors = [
        'pending' => 'bg-amber-50 text-amber-800 border-amber-200',
        'paid' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'shipped' => 'bg-sky-50 text-sky-800 border-sky-200',
        'completed' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'cancelled' => 'bg-rose-50 text-rose-800 border-rose-200',
    ];

    $statusLabels = [
        'pending' => 'Menunggu Pembayaran',
        'paid' => 'Pembayaran Diterima (Diproses)',
        'shipped' => 'Sedang Dikirim',
        'completed' => 'Pesanan Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    $statusClass = $statusColors[$purchase->status] ?? 'bg-stone-100 text-teks/80 border-teks/20';
    $statusLabel = $statusLabels[$purchase->status] ?? ucfirst($purchase->status);
    $orderUrl = route('purchases.show', $purchase->order_code);
    $notaText = $purchase->buildWhatsappReceiptText();
    $bankAccount = $purchase->selected_bank_account;
@endphp

<div class="w-full bg-[#FFFBF7] min-h-screen py-8 sm:py-12">
    <div class="max-w-[820px] mx-auto px-4 sm:px-6 space-y-6">

        {{-- Breadcrumb Navigasi --}}
        <div class="flex items-center justify-between text-xs text-teks/70">
            <div class="flex items-center gap-1.5">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
                <span>/</span>
                <span>Pesanan</span>
                <span>/</span>
                <span class="font-semibold text-teks">{{ $purchase->order_code }}</span>
            </div>
            <a href="{{ route('katalog') }}" class="text-primary hover:underline font-semibold flex items-center gap-1">
                <span>&larr; Lihat Produk Lain</span>
            </a>
        </div>

        {{-- Flash Notification --}}
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-center justify-between shadow-xs">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-xs text-emerald-700 hover:underline font-semibold cursor-pointer">Tutup</button>
            </div>
        @endif

        {{-- Main Container Card --}}
        <div class="bg-white rounded-xl border border-teks/10 shadow-sm p-5 sm:p-8 space-y-6">

            {{-- 1. Header Pesanan & Status --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-teks/10">
                <div class="space-y-1">
                    <span class="inline-block text-[11px] font-bold text-primary uppercase tracking-wider bg-primary/10 border border-primary/20 px-2.5 py-0.5 rounded-md">
                        Nota Pesanan Publik
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-teks tracking-tight">{{ $purchase->order_code }}</h1>
                    <p class="text-xs text-teks/60">
                        Waktu pemesanan: {{ $purchase->created_at->format('d F Y, H:i') }} WIB
                    </p>
                </div>
                <div class="self-start sm:self-auto">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border {{ $statusClass }}">
                        <span class="w-2 h-2 rounded-full bg-current"></span>
                        <span>{{ $statusLabel }}</span>
                    </span>
                </div>
            </div>

            {{-- 2. NOTA WHATSAPP & CADANGAN TAUTAN --}}
            <div class="bg-[#FFFDFB] border border-teks/10 rounded-xl p-5 space-y-3.5">
                <div class="space-y-1">
                    <h2 class="text-sm sm:text-base font-bold text-teks">Simpan Nota ke WhatsApp Anda</h2>
                    <p class="text-xs text-teks/70 leading-relaxed">
                        Kirimkan salinan nota ke chat WhatsApp Anda sendiri agar tautan detail pesanan dan pembayaran ini tersimpan rapi dan tetap dapat dibuka kembali kapan saja jika tab browser tertutup.
                    </p>
                </div>

                <div class="pt-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                    {{-- Tombol Utama: Kirim Nota ke WhatsApp Pembeli --}}
                    <a href="{{ $purchase->whatsapp_receipt_url }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="flex-1 min-h-[44px] px-5 py-2.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center shadow-xs transition-all active:scale-[0.98]">
                        <span>Kirim Nota ke WhatsApp Saya</span>
                    </a>

                    {{-- Salin Tautan Pembayaran --}}
                    <button type="button"
                            onclick="copyOrderInfo('{{ $orderUrl }}', 'Tautan halaman pesanan berhasil disalin!')"
                            class="px-4 py-2.5 rounded-full border border-teks/20 bg-white hover:bg-stone-50 text-teks font-semibold text-xs flex items-center justify-center transition-colors cursor-pointer">
                        <span>Salin Tautan</span>
                    </button>

                    {{-- Salin Teks Nota --}}
                    <button type="button"
                            onclick="copyOrderInfo(`{{ addslashes($notaText) }}`, 'Teks nota lengkap berhasil disalin!')"
                            class="px-4 py-2.5 rounded-full border border-teks/20 bg-white hover:bg-stone-50 text-teks font-semibold text-xs flex items-center justify-center transition-colors cursor-pointer">
                        <span>Salin Nota</span>
                    </button>
                </div>
            </div>

            {{-- 3. KOTAK INFORMASI PENGIRIMAN (Jika sudah diinput kurir / nomor resi) --}}
            @if($purchase->courier_name || $purchase->tracking_number)
                <div class="bg-white border border-teks/10 rounded-xl p-5 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-teks/10">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-primary"></span>
                            <h3 class="text-sm font-bold text-teks">Informasi Pengiriman Pesanan</h3>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-0.5 rounded-md bg-stone-100 text-teks">
                            {{ $purchase->courier_name }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs pt-1">
                        <div>
                            <span class="text-teks/60 block">Ekspedisi / Kurir:</span>
                            <span class="font-bold text-teks text-sm">{{ $purchase->courier_name }}</span>
                        </div>
                        <div>
                            <span class="text-teks/60 block">Nomor Resi:</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="font-bold text-teks text-sm">{{ $purchase->tracking_number ?: '-' }}</span>
                                @if($purchase->tracking_number && $purchase->tracking_number !== '-')
                                    <button type="button"
                                            onclick="copyOrderInfo('{{ $purchase->tracking_number }}', 'Nomor resi berhasil disalin!')"
                                            class="text-[11px] px-2.5 py-0.5 bg-stone-100 border border-teks/20 text-teks hover:bg-stone-200 rounded-md font-semibold inline-flex items-center cursor-pointer">
                                        <span>Salin Resi</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($purchase->shipping_receipt_path)
                        <div class="pt-2 border-t border-teks/10 flex items-center justify-between text-xs">
                            <span class="text-teks/70 font-medium">Foto bukti resi pengiriman telah diunggah:</span>
                            <a href="{{ asset('storage/'.$purchase->shipping_receipt_path) }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="text-primary hover:underline font-bold inline-flex items-center">
                                <span>Lihat Foto Bukti Resi &rarr;</span>
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- 4. INSTRUKSI PEMBAYARAN SESUAI METODE (QRIS / BANK) & UPLOAD BUKTI --}}
            <div class="bg-white border border-teks/10 rounded-xl p-5 sm:p-6 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-teks/10">
                    <div class="space-y-0.5">
                        <span class="text-xs uppercase tracking-wider font-semibold text-primary">Instruksi Pembayaran</span>
                        <h3 class="text-lg font-bold text-teks">
                            @if($purchase->payment_method === 'bank_transfer')
                                Transfer {{ $bankAccount['name'] ?? ($purchase->bank_name ? 'Bank '.$purchase->bank_name : 'Bank BCA') }}
                            @else
                                QRIS Kios Mandiri YPKABK
                            @endif
                        </h3>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-xs text-teks/60 block">Total Tagihan:</span>
                        <span class="text-2xl font-black text-primary">
                            Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Tampilan Transfer Bank --}}
                @if($purchase->payment_method === 'bank_transfer')
                    <div class="bg-stone-50 rounded-xl border border-teks/10 p-4 sm:p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-teks/70 uppercase">Bank Tujuan</span>
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-800 border border-blue-200">
                                {{ $bankAccount['name'] ?? ($purchase->bank_name ?: 'Bank Central Asia (BCA)') }}
                            </span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-xs text-teks/60">Nomor Rekening:</span>
                            <div class="flex items-center gap-3">
                                <span class="text-2xl font-black text-teks tracking-wider">
                                    {{ $bankAccount['account_number'] ?? '018-384-9900' }}
                                </span>
                                <button type="button"
                                        onclick="copyOrderInfo('{{ $bankAccount['account_number_raw'] ?? '0183849900' }}', 'Nomor rekening berhasil disalin!')"
                                        class="px-3 py-1 bg-white hover:bg-stone-100 border border-teks/20 rounded-lg text-xs font-bold text-teks inline-flex items-center transition-colors cursor-pointer">
                                    <span>Salin No. Rekening</span>
                                </button>
                            </div>
                            <p class="text-xs text-teks/70">Atas Nama: <strong class="text-teks">{{ $bankAccount['account_holder'] ?? 'Yayasan Peduli Kasih ABK' }}</strong></p>
                        </div>
                        <div class="pt-2 text-xs text-teks/65 border-t border-teks/10">
                            Silakan transfer tepat sebesar <strong>Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</strong>, lalu unggah foto struk / tangkapan layar bukti transfer di bawah ini.
                        </div>
                    </div>
                @else
                    {{-- Tampilan QRIS --}}
                    <div class="bg-stone-50 rounded-xl border border-teks/10 p-5 flex flex-col items-center justify-center space-y-3 text-center">
                        <div class="w-[220px] h-[220px] bg-white border border-teks/15 rounded-xl overflow-hidden flex items-center justify-center p-2 shadow-xs">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=10&data={{ urlencode(app(\App\Http\Services\PurchaseService::class)->generateDynamicQris($purchase->total_price)) }}"
                                 alt="QRIS Pembayaran Kios Mandiri"
                                 class="w-full h-full object-contain">
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-bold text-teks">Scan dengan Aplikasi Mobile Banking / E-Wallet</p>
                            <p class="text-[11px] text-teks/65">BCA Mobile, Livin Mandiri, BRImo, BNI, GoPay, OVO, ShopeePay, DANA</p>
                        </div>
                        <p class="text-[11px] text-teks/60">
                            Nominal otomatis tertera <strong>Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</strong> saat discan.
                        </p>
                    </div>
                @endif

                {{-- Status Bukti Bayar & Form Upload Bukti --}}
                <div class="pt-3 border-t border-teks/10 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-teks">Unggah Bukti Pembayaran</h4>
                        <span class="text-[11px] text-teks/60">Format JPG, PNG, atau WebP (Maks. 5MB)</span>
                    </div>

                    @if($purchase->payment_proof)
                        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between gap-3 text-xs">
                            <span class="text-emerald-900 font-medium">Bukti transfer sudah pernah diunggah.</span>
                            <a href="{{ asset('storage/'.$purchase->payment_proof) }}" target="_blank" class="font-bold text-emerald-700 hover:underline shrink-0">
                                Lihat Bukti Saat Ini &rarr;
                            </a>
                        </div>
                    @endif

                    <form action="{{ route('purchases.upload-proof', $purchase->order_code) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <div class="flex flex-col sm:flex-row gap-2.5">
                            <input type="file" name="payment_proof" required accept="image/jpeg,image/png,image/webp"
                                   class="flex-1 text-xs text-teks file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary/90 cursor-pointer border border-teks/20 rounded-xl p-1.5 bg-white">
                            <button type="submit"
                                    class="min-h-[42px] px-5 py-2 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold text-xs shadow-xs transition-all active:scale-[0.98] shrink-0 flex items-center justify-center cursor-pointer">
                                <span>Kirim Bukti Pembayaran</span>
                            </button>
                        </div>
                        <p class="text-[11px] text-teks/60">
                            Anda dapat mengunggah ulang jika ingin memperbarui foto bukti transfer.
                        </p>
                    </form>
                </div>
            </div>

            {{-- 5. DETAIL ALAMAT TUJUAN PENGIRIMAN --}}
            <div class="bg-white border border-teks/10 rounded-xl p-5 sm:p-6 space-y-4">
                <div class="pb-2 border-b border-teks/10">
                    <span class="text-xs uppercase tracking-wider font-semibold text-primary">Tujuan Pengiriman</span>
                    <h3 class="text-base font-bold text-teks">Detail Alamat Penerima</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-teks/60 block">Nama Penerima:</span>
                        <span class="font-bold text-teks text-sm">{{ $purchase->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-teks/60 block">Nomor WhatsApp:</span>
                        <a href="https://wa.me/{{ $purchase->international_phone }}" target="_blank" class="font-bold text-primary hover:underline text-sm inline-flex items-center">
                            <span>{{ $purchase->customer_phone }}</span>
                        </a>
                    </div>
                    <div>
                        <span class="text-teks/60 block">Kota / Kabupaten:</span>
                        <span class="font-semibold text-teks">{{ $purchase->city ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-teks/60 block">Kecamatan:</span>
                        <span class="font-semibold text-teks">{{ $purchase->district ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-teks/60 block">Desa / Kelurahan:</span>
                        <span class="font-semibold text-teks">{{ $purchase->village ?: '-' }}</span>
                    </div>
                    <div>
                        <span class="text-teks/60 block">Jalan dan Nomor Rumah:</span>
                        <span class="font-semibold text-teks">{{ $purchase->street_address ?: '-' }}</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-teks/10 text-xs">
                    <span class="text-teks/60 block mb-0.5">Patokan / Detail Alamat:</span>
                    <p class="font-medium text-teks bg-stone-50 p-3 rounded-lg border border-teks/10 leading-relaxed">
                        {{ $purchase->address ?: ($purchase->customer_address ?: 'Tidak ada patokan tambahan.') }}
                    </p>
                </div>

                @if($purchase->notes)
                    <div class="text-xs">
                        <span class="text-teks/60 block mb-0.5">Catatan Khusus:</span>
                        <p class="text-teks/80 italic">{{ $purchase->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- 6. RINCIAN PRODUK PESANAN --}}
            <div class="bg-white border border-teks/10 rounded-xl p-5 sm:p-6 space-y-4">
                <div class="pb-2 border-b border-teks/10">
                    <span class="text-xs uppercase tracking-wider font-semibold text-primary">Rincian Item</span>
                    <h3 class="text-base font-bold text-teks">Daftar Produk yang Dipesan</h3>
                </div>

                <div class="divide-y divide-teks/10">
                    @foreach($purchase->detailPurchases as $item)
                        <div class="py-3 flex items-center justify-between gap-4 text-xs">
                            <div class="space-y-0.5">
                                <h4 class="font-bold text-teks text-sm">{{ $item->product?->name ?? 'Produk Kios Mandiri' }}</h4>
                                <p class="text-teks/60">{{ $item->quantity }} pcs &times; Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <span class="font-bold text-teks text-sm">
                                Rp {{ number_format($item->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-teks/10 flex items-center justify-between">
                    <span class="text-sm font-bold text-teks">Total Donasi &amp; Belanja:</span>
                    <span class="text-xl font-black text-primary">
                        Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    function copyOrderInfo(text, message) {
        if (!text) return;
        navigator.clipboard.writeText(text).then(() => {
            if (window.showAppToast) {
                window.showAppToast(message || 'Berhasil disalin!');
            }
        }).catch(() => {
            if (window.showAppToast) {
                window.showAppToast('Silakan salin teks secara manual.');
            }
        });
    }
</script>
@endsection
