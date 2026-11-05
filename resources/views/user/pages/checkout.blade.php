@extends('user.shared.Layout')

@section('content')
<div class="space-y-10 lg:space-y-14 pb-16">

    {{-- ========================================================
         HEADER CHECKOUT: BG-HERO-GRADIENT DI BELAKANG NAVBAR
         ======================================================== --}}
    <div class="bg-hero-gradient pt-24 pb-8 space-y-6">
        {{-- TOP TRUST NOTICE BAR --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-cream rounded-2xl border border-ink/15 text-ink py-2.5 px-4 text-xs font-medium">
                <div class="flex flex-col md:flex-row items-center justify-between gap-2 text-center md:text-left">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-cream text-ink flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span>
                            <strong>Guest Checkout:</strong> Pesan langsung tanpa registrasi akun (NFR-02). Data aman &amp; langsung diproses admin.
                        </span>
                    </div>
                    <div class="flex items-center gap-4 text-ink text-[11px] font-semibold">
                        <span class="flex items-center gap-1">
                            <span>📦</span> Kemasan Higienis &amp; Aman
                        </span>
                        <span class="text-ink">•</span>
                        <span class="flex items-center gap-1 text-ink">
                            <span>💬</span> Konfirmasi Cepat via WhatsApp
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- PAGE TITLE & BREADCRUMB --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-2">
            <div class="max-w-3xl space-y-3">
                <nav class="flex items-center gap-2 text-xs text-ink/60 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-ink">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('keranjang') }}" class="hover:text-ink">Keranjang</a>
                    <span>/</span>
                    <span class="text-ink font-bold">Checkout &amp; Pengiriman</span>
                </nav>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-ink tracking-tight leading-tight">
                    Checkout &amp; Pengiriman Pesanan
                </h1>
                <p class="text-sm sm:text-base text-ink/80 leading-relaxed">
                    Lengkapi alamat pengiriman Anda. 100% margin penjualan dialokasikan untuk beasiswa terapi &amp; kemandirian vokasi anak istimewa YPKABK.
                </p>
            </div>
        </section>
    </div>

    {{-- ========================================================
         MAIN CONTENT: FORM & ORDER REVIEW
         ======================================================== --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- EMPTY STATE (Shown via JS if user navigates with empty cart) --}}
        <div id="checkout-empty-state" class="hidden bg-white rounded-3xl p-8 sm:p-14 text-center max-w-2xl mx-auto border border-ink/10 shadow-xs space-y-6">
            <div class="w-20 h-20 mx-auto rounded-full bg-cream border border-ink/10 flex items-center justify-center text-4xl shadow-xs">
                🧺
            </div>
            <div class="space-y-2">
                <h2 class="font-heading text-2xl sm:text-3xl font-bold text-ink">
                    Tidak Ada Barang untuk Dicheckout
                </h2>
                <p class="text-sm text-ink/70 leading-relaxed max-w-md mx-auto">
                    Keranjang belanja Anda masih kosong. Silakan pilih produk dari katalog sebelum melakukan checkout.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('katalog') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink text-sm sm:text-base font-bold shadow-sm transition-all active:scale-95">
                    <span>🛍️</span>
                    <span>Kembali ke Katalog</span>
                </a>
            </div>
        </div>

        {{-- ACTIVE CHECKOUT VIEW --}}
        <div id="checkout-active-view" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- LEFT COLUMN: CUSTOMER & SHIPPING FORM --}}
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-ink/10 shadow-xs space-y-6">
                    <div class="space-y-1 pb-4 border-b border-ink/10">
                        <span class="text-xs font-bold text-ink uppercase tracking-wider bg-sun px-2.5 py-1 rounded-lg inline-block">
                            Langkah 1 dari 2
                        </span>
                        <h2 class="font-heading text-xl sm:text-2xl font-bold text-ink">
                            Informasi Pemesan &amp; Alamat Kirim
                        </h2>
                        <p class="text-xs text-ink/70">
                            Tanpa perlu registrasi akun. Tim Kios Mandiri akan mengonfirmasi resi dan donasi via WhatsApp.
                        </p>
                    </div>

                    <form id="checkout-form" onsubmit="handleCheckoutSubmit(event)" method="POST" action="{{ route('purchases.store') }}" class="space-y-4 text-xs font-medium text-ink">
                        @csrf

                        {{-- Nama Pemesan --}}
                        <div class="space-y-1.5">
                            <label for="customer_name" class="block font-bold text-sm text-ink">
                                Nama Lengkap Pemesan <span class="text-rose-600">*</span>
                            </label>
                            <input type="text"
                                   id="customer_name"
                                   name="customer_name"
                                   required
                                   placeholder="Contoh: Ibu Siti Rahmawati"
                                   class="w-full px-4 py-3 rounded-2xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun text-sm bg-white">
                        </div>

                        {{-- No WhatsApp --}}
                        <div class="space-y-1.5">
                            <label for="customer_phone" class="block font-bold text-sm text-ink">
                                Nomor WhatsApp Aktif <span class="text-rose-600">*</span>
                            </label>
                            <input type="tel"
                                   id="customer_phone"
                                   name="customer_phone"
                                   required
                                   placeholder="Contoh: 081234567890"
                                   class="w-full px-4 py-3 rounded-2xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun text-sm bg-white">
                            <p class="text-[11px] text-ink/60">
                                Digunakan untuk konfirmasi ketersediaan rekening donasi/QRIS dan nomor resi pengiriman.
                            </p>
                        </div>

                        {{-- Alamat Pengiriman --}}
                        <div class="space-y-1.5">
                            <label for="customer_address" class="block font-bold text-sm text-ink">
                                Alamat Lengkap Pengiriman <span class="text-rose-600">*</span>
                            </label>
                            <textarea id="customer_address"
                                      name="customer_address"
                                      rows="3"
                                      required
                                      placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, dan kode pos"
                                      class="w-full px-4 py-3 rounded-2xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun text-sm bg-white leading-relaxed"></textarea>
                            <p class="text-[11px] text-ink/60">
                                Harap cantumkan nama kota / kabupaten dengan jelas untuk perhitungan tarif kurir hemat.
                            </p>
                        </div>

                        {{-- Catatan Khusus --}}
                        <div class="space-y-1.5">
                            <label for="notes" class="block font-bold text-sm text-ink">
                                Catatan Pesanan (Opsional)
                            </label>
                            <textarea id="notes"
                                      name="notes"
                                      rows="2"
                                      placeholder="Contoh: Tolong bungkus rapi dengan kartu ucapan, atau titipkan di pos satpam."
                                      class="w-full px-4 py-3 rounded-2xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun text-sm bg-white leading-relaxed"></textarea>
                        </div>

                        {{-- Pilihan Metode Pembayaran --}}
                        <div class="pt-4 border-t border-ink/10 space-y-3">
                            <h3 class="font-bold text-sm text-ink flex items-center gap-2">
                                <span>💳</span>
                                <span>Pilihan Metode Pembayaran</span>
                            </h3>

                            <div class="space-y-2.5">
                                {{-- Option 1: DOKU Payment Gateway --}}
                                <label class="flex items-start gap-3 p-4 rounded-2xl border-2 border-sun bg-butter/30 cursor-pointer transition-all hover:bg-butter/50">
                                    <input type="radio" name="payment_method_choice" value="doku" checked class="mt-1 text-sun focus:ring-sun">
                                    <div class="space-y-1 flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-sm text-ink">DOKU Payment Gateway</span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-sun text-ink uppercase">Otomatis</span>
                                        </div>
                                        <p class="text-xs text-ink/75 leading-relaxed">
                                            Bayar instan dengan QRIS (semua e-wallet), Virtual Account (BCA, Mandiri, BRI, BNI), Kartu Kredit, atau gerai Alfamart/Indomaret.
                                        </p>
                                    </div>
                                </label>

                                {{-- Option 2: Manual WhatsApp --}}
                                <label class="flex items-start gap-3 p-4 rounded-2xl border border-ink/15 bg-white cursor-pointer transition-all hover:bg-cream">
                                    <input type="radio" name="payment_method_choice" value="manual_wa" class="mt-1 text-sun focus:ring-sun">
                                    <div class="space-y-1 flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-sm text-ink">Transfer Manual &amp; WhatsApp</span>
                                        </div>
                                        <p class="text-xs text-ink/75 leading-relaxed">
                                            Pesan langsung dan koordinasi rekening donasi/bukti transfer via admin WhatsApp Kios Mandiri YPKABK.
                                        </p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Submit button (Mobile Only duplicate for convenience) --}}
                        <div class="pt-4 lg:hidden">
                            <button type="submit"
                                    id="checkout-submit-btn-mobile"
                                    class="w-full min-h-[48px] py-3.5 px-6 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95">
                                <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24">
                                    <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                                </svg>
                                <span>Konfirmasi &amp; Lanjut ke WhatsApp</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            {{-- RIGHT COLUMN: ORDER SUMMARY & SUBMIT --}}
            <aside class="lg:col-span-5 space-y-6 lg:sticky lg:top-28">
                <div class="bg-white rounded-3xl p-6 border border-ink/10 shadow-xs space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-ink/10">
                        <h3 class="font-heading text-lg font-bold text-ink">
                            Rincian Pembelian
                        </h3>
                        <a href="{{ route('keranjang') }}" class="text-xs font-semibold text-ink/60 hover:text-ink underline">
                            Ubah
                        </a>
                    </div>

                    {{-- Dynamic Items Container --}}
                    <div id="checkout-items-list" class="divide-y divide-ink/10 max-h-72 overflow-y-auto pr-1 space-y-3">
                        {{-- Rendered via JS --}}
                    </div>

                    {{-- Total Calculations --}}
                    <div class="pt-4 border-t border-ink/10 space-y-2.5 text-xs sm:text-sm text-ink/80">
                        <div class="flex items-center justify-between">
                            <span>Subtotal Donasi Barang:</span>
                            <span id="checkout-subtotal" class="font-bold text-ink">Rp 0</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Pengemasan Kardus Bersih:</span>
                            <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md text-[11px]">Gratis</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Estimasi Ongkir:</span>
                            <span class="font-semibold text-ink/70">Dihitung di WhatsApp</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-ink/10 space-y-2">
                        <div class="flex items-baseline justify-between">
                            <span class="font-bold text-ink text-sm sm:text-base">Total Tagihan Donasi:</span>
                            <span id="checkout-total" class="font-extrabold text-ink text-xl sm:text-2xl">Rp 0</span>
                        </div>
                        <p class="text-[11px] text-ink/60 leading-relaxed">
                            *Belum termasuk ongkir. Admin Kios akan merekomendasikan ekspedisi reguler/kargo dengan biaya terendah.
                        </p>
                    </div>

                    {{-- Social Impact Box --}}
                    <div class="rounded-2xl p-4 bg-butter border border-ink/15 space-y-1.5 text-xs">
                        <div class="flex items-center gap-1.5 font-bold text-ink">
                            <span>⭐</span>
                            <span>Transparansi 100% YPKABK</span>
                        </div>
                        <p class="text-ink/80 text-[11px] leading-relaxed">
                            Dana yang Anda keluarkan langsung dialokasikan untuk mendanai guru vokasi, ruang sensori, dan beasiswa terapi anak berkebutuhan khusus.
                        </p>
                    </div>

                    {{-- Primary Submit Button (Desktop) --}}
                    <div class="pt-2 hidden lg:block">
                        <button type="button"
                                onclick="document.getElementById('checkout-form').requestSubmit()"
                                id="checkout-submit-btn-desktop"
                                class="w-full min-h-[48px] py-3.5 px-6 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-base flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">
                            <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24">
                                <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                            </svg>
                            <span>Konfirmasi &amp; Lanjut ke WhatsApp</span>
                        </button>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('keranjang') }}" class="text-xs font-semibold text-ink/70 hover:text-ink">
                            &larr; Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </aside>

        </div>

    </section>

</div>

{{-- SUCCESS ORDER MODAL --}}
<div id="checkout-success-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 mx-auto flex items-center justify-center text-3xl">
            🎉
        </div>

        <div class="space-y-2">
            <span class="text-xs font-bold text-ink uppercase tracking-wider bg-sun px-3 py-1 rounded-lg inline-block">
                Pesanan Berhasil Dicatat!
            </span>
            <h3 class="font-heading text-2xl font-bold text-ink">
                Terima Kasih Banyak!
            </h3>
            <p class="text-xs sm:text-sm text-ink/80 leading-relaxed">
                Pesanan Anda telah disimpan dengan kode pesanan:
            </p>
            <div class="bg-cream py-2 px-4 rounded-xl border border-ink/15 inline-block font-mono font-extrabold text-base text-ink" id="success-order-code">
                ORD-XXXXXX
            </div>
        </div>

        <p class="text-xs text-ink/70 leading-relaxed bg-butter/50 p-4 rounded-2xl border border-ink/10">
            Silakan tekan tombol di bawah ini untuk membuka percakapan WhatsApp resmi Kios Mandiri YPKABK dan menyelesaikan donasi pesanan Anda.
        </p>

        <div class="space-y-2 pt-2">
            <a href="#"
               id="success-whatsapp-link"
               target="_blank"
               rel="noopener noreferrer"
               class="w-full min-h-[48px] py-3.5 px-6 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm sm:text-base flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95">
                <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24">
                    <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                </svg>
                <span>Buka WhatsApp Sekarang</span>
            </a>

            <a href="{{ route('katalog') }}"
               class="block w-full py-2.5 text-center text-xs font-semibold text-ink/70 hover:text-ink">
                Kembali ke Katalog Produk
            </a>
        </div>
    </div>
</div>

{{-- CLIENT-SIDE CHECKOUT INTERACTIVE LOGIC --}}
<script>
    function renderCheckoutView() {
        const cart = getKiosCart();
        const emptyState = document.getElementById('checkout-empty-state');
        const activeView = document.getElementById('checkout-active-view');
        const listContainer = document.getElementById('checkout-items-list');
        const subtotalEl = document.getElementById('checkout-subtotal');
        const totalEl = document.getElementById('checkout-total');

        if (!cart || cart.length === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            if (activeView) activeView.classList.add('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');
        if (activeView) activeView.classList.remove('hidden');

        let subtotal = 0;
        let html = '';

        cart.forEach(item => {
            const qty = parseInt(item.quantity, 10) || 1;
            const price = parseInt(item.price, 10) || 0;
            const itemTotal = price * qty;
            subtotal += itemTotal;

            const formattedPrice = new Intl.NumberFormat('id-ID').format(price);
            const formattedTotal = new Intl.NumberFormat('id-ID').format(itemTotal);

            html += `
                <div class="pt-3 first:pt-0 flex items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                        <img src="${item.image || '/images/tote-bag.jpg'}"
                             alt="${item.name}"
                             class="w-12 h-12 rounded-xl object-cover bg-butter shrink-0 border border-ink/10">
                        <div class="min-w-0 flex-1">
                            <h4 class="font-bold text-ink truncate" title="${item.name}">${item.name}</h4>
                            <p class="text-ink/60 text-[11px]">${qty} × Rp ${formattedPrice}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="font-bold text-ink">Rp ${formattedTotal}</span>
                    </div>
                </div>
            `;
        });

        if (listContainer) listContainer.innerHTML = html;

        const formattedSubtotal = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        if (subtotalEl) subtotalEl.innerText = formattedSubtotal;
        if (totalEl) totalEl.innerText = formattedSubtotal;
    }

    async function handleCheckoutSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const cart = getKiosCart();

        if (!cart || cart.length === 0) {
            alert('Keranjang belanja Anda masih kosong.');
            return;
        }

        const customerName = form.elements['customer_name']?.value?.trim();
        const customerPhone = form.elements['customer_phone']?.value?.trim();
        const customerAddress = form.elements['customer_address']?.value?.trim();
        const notes = form.elements['notes']?.value?.trim() || '';

        const submitDesktopBtn = document.getElementById('checkout-submit-btn-desktop');
        const submitMobileBtn = document.getElementById('checkout-submit-btn-mobile');

        const originalDesktopText = submitDesktopBtn ? submitDesktopBtn.innerHTML : '';
        const originalMobileText = submitMobileBtn ? submitMobileBtn.innerHTML : '';

        [submitDesktopBtn, submitMobileBtn].forEach(btn => {
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span>Memproses pesanan...</span>';
            }
        });

        const paymentChoice = form.elements['payment_method_choice']?.value || 'doku';
        let orderCode = 'ORD-' + Math.random().toString(36).substring(2, 8).toUpperCase();
        let paymentUrl = null;

        try {
            // Prepare payload according to PurchaseController::store
            const payload = {
                customer_name: customerName,
                customer_phone: customerPhone,
                customer_address: customerAddress,
                notes: notes,
                items: cart.map(item => ({
                    product_id: item.id || 1,
                    quantity: parseInt(item.quantity, 10) || 1,
                    price: parseInt(item.price, 10) || 0
                }))
            };

            const csrfToken = document.querySelector('input[name="_token"]')?.value || '';

            const res = await fetch('{{ route('purchases.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });

            if (res.ok) {
                const data = await res.json();
                if (data && data.data && data.data.order_code) {
                    orderCode = data.data.order_code;
                }
                if (data && data.payment_url) {
                    paymentUrl = data.payment_url;
                }
            }
        } catch (err) {
            console.warn('Backend call failed, proceeding with direct WhatsApp checkout fallback:', err);
        }

        // Clear Cart
        saveKiosCart([]);

        // If user chose DOKU Payment Gateway, redirect immediately to DOKU checkout / simulator
        if (paymentChoice === 'doku' && paymentUrl) {
            window.location.href = paymentUrl;
            return;
        }

        // Show Success Modal
        const modal = document.getElementById('checkout-success-modal');
        const codeEl = document.getElementById('success-order-code');
        const linkEl = document.getElementById('success-whatsapp-link');

        if (codeEl) codeEl.innerText = orderCode;
        if (linkEl) linkEl.href = waUrl;

        if (modal) modal.classList.remove('hidden');

        // Open WhatsApp window automatically
        window.open(waUrl, '_blank');

        [submitDesktopBtn, submitMobileBtn].forEach(btn => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalDesktopText;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderCheckoutView();
    });
</script>
@endsection

