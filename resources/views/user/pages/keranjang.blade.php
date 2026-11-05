@extends('user.shared.Layout')

@section('content')
<div class="space-y-10 lg:space-y-14 pb-16">

    {{-- ========================================================
         HEADER KERANJANG: BG-HERO-GRADIENT DI BELAKANG NAVBAR
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
                            <strong>Transparan:</strong> 100% margin penjualan dialokasikan untuk beasiswa terapi &amp; kemandirian vokasi anak istimewa YPKABK.
                        </span>
                    </div>
                    <div class="flex items-center gap-4 text-ink text-[11px] font-semibold">
                        <span class="flex items-center gap-1">
                            <span>📦</span> Kemasan Kardus Daur Ulang
                        </span>
                        <span class="text-ink">•</span>
                        <span class="flex items-center gap-1 text-ink">
                            <span>⚡</span> Belanja Cepat Tanpa Akun
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
                    <a href="{{ route('katalog') }}" class="hover:text-ink">Katalog</a>
                    <span>/</span>
                    <span class="text-ink font-bold">Keranjang Belanja</span>
                </nav>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-ink tracking-tight leading-tight">
                    Keranjang Belanja &amp; Donasi
                </h1>
                <p class="text-sm sm:text-base text-ink/80 leading-relaxed">
                    Periksa kembali daftar karya anak istimewa dan barang donasi preloved pilihan Anda sebelum melanjutkan ke pengiriman.
                </p>
            </div>
        </section>
    </div>

    {{-- ========================================================
         MAIN CONTENT: CART ITEMS & SUMMARY
         ======================================================== --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- EMPTY STATE (Shown via JS when cart is empty) --}}
        <div id="cart-empty-state" class="hidden bg-white rounded-3xl p-8 sm:p-14 text-center max-w-2xl mx-auto border border-ink/10 shadow-xs space-y-6">
            <div class="w-20 h-20 mx-auto rounded-full bg-cream border border-ink/10 flex items-center justify-center text-4xl shadow-xs">
                🛒
            </div>
            <div class="space-y-2">
                <h2 class="font-heading text-2xl sm:text-3xl font-bold text-ink">
                    Keranjang Belanja Masih Kosong
                </h2>
                <p class="text-sm text-ink/70 leading-relaxed max-w-md mx-auto">
                    Belum ada karya anak istimewa atau barang donasi yang Anda pilih. Dukung kemandirian mereka lewat etalase produk kami.
                </p>
            </div>
            <div class="pt-2">
                <a href="{{ route('katalog') }}"
                   class="inline-flex items-center justify-center gap-2 px-8 min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink text-sm sm:text-base font-bold shadow-sm transition-all active:scale-95">
                    <span>🛍️</span>
                    <span>Jelajahi Katalog Sekarang</span>
                </a>
            </div>
        </div>

        {{-- ACTIVE CART STATE (Shown via JS when cart has items) --}}
        <div id="cart-active-state" class="hidden grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- LEFT COLUMN: LIST OF CART ITEMS --}}
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-ink/10 shadow-xs space-y-5">
                    <div class="flex items-center justify-between pb-4 border-b border-ink/10">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">🛍️</span>
                            <h2 class="font-bold text-ink text-base sm:text-lg">
                                Daftar Barang Pilihan (<span id="cart-items-count-label">0</span>)
                            </h2>
                        </div>
                        <button type="button"
                                onclick="clearKiosCart()"
                                class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition-colors inline-flex items-center gap-1">
                            <span>🗑️</span>
                            <span>Kosongkan Semua</span>
                        </button>
                    </div>

                    {{-- Items Container --}}
                    <div id="cart-items-container" class="divide-y divide-ink/10 space-y-4">
                        {{-- Rendered dynamically by JavaScript --}}
                    </div>
                </div>

                {{-- Back to catalog button --}}
                <div class="pt-2">
                    <a href="{{ route('katalog') }}"
                       class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-ink hover:text-ink/80 transition-colors">
                        <span>&larr;</span>
                        <span>Tambah Produk Lain dari Katalog</span>
                    </a>
                </div>
            </div>

            {{-- RIGHT COLUMN: ORDER SUMMARY CARD --}}
            <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-28">
                <div class="bg-white rounded-3xl p-6 border border-ink/10 shadow-xs space-y-5">
                    <h3 class="font-heading text-lg font-bold text-ink pb-3 border-b border-ink/10">
                        Ringkasan Donasi &amp; Pembelian
                    </h3>

                    <div class="space-y-3 text-xs sm:text-sm text-ink/80">
                        <div class="flex items-center justify-between">
                            <span>Subtotal Donasi Barang:</span>
                            <span id="cart-summary-subtotal" class="font-bold text-ink">Rp 0</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Pengemasan Daur Ulang:</span>
                            <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md text-[11px]">Gratis &amp; Ramah Lingkungan</span>
                        </div>
                        <div class="flex flex-col gap-0.5 pt-1 text-xs">
                            <div class="flex items-center justify-between">
                                <span>Estimasi Ongkir:</span>
                                <span class="font-semibold text-ink/70">Kurir Reguler / Hemat</span>
                            </div>
                            <span class="text-[11px] text-ink/50">*Dikoordinasikan via WhatsApp setelah pesanan dibuat</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-ink/10 space-y-2">
                        <div class="flex items-baseline justify-between">
                            <span class="font-bold text-ink text-sm sm:text-base">Total Nilai Donasi:</span>
                            <span id="cart-summary-total" class="font-extrabold text-ink text-xl sm:text-2xl">Rp 0</span>
                        </div>
                        <p class="text-[11px] text-ink/60">
                            Belum termasuk ongkir. Admin akan membantu memilihkan tarif ongkir paling hemat ke alamat Anda.
                        </p>
                    </div>

                    {{-- Social Impact Box --}}
                    <div class="rounded-2xl p-4 bg-cream border border-ink/15 space-y-2 text-xs leading-relaxed">
                        <div class="flex items-center gap-1.5 font-bold text-ink">
                            <span>💖</span>
                            <span>Dampak Sosial Nyata</span>
                        </div>
                        <p class="text-ink/80 text-[11px]">
                            100% donasi margin dari transaksi ini langsung dialokasikan ke beasiswa terapi wicara, okupasi, dan pelatihan vokasi mandiri anak istimewa YPKABK.
                        </p>
                    </div>

                    {{-- Action Checkout Button --}}
                    <div class="pt-2">
                        <a href="{{ route('checkout') }}"
                           class="w-full min-h-[48px] py-3.5 px-6 rounded-full bg-sun hover:bg-sun/90 text-ink font-bold text-base flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-sun">
                            <span>Lanjut ke Checkout</span>
                            <svg class="w-5 h-5 text-ink shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </aside>

        </div>

    </section>

</div>

{{-- CLIENT-SIDE CART INTERACTIVE LOGIC --}}
<script>
    function renderCartView() {
        const cart = getKiosCart();
        const emptyState = document.getElementById('cart-empty-state');
        const activeState = document.getElementById('cart-active-state');
        const container = document.getElementById('cart-items-container');
        const countLabel = document.getElementById('cart-items-count-label');
        const subtotalEl = document.getElementById('cart-summary-subtotal');
        const totalEl = document.getElementById('cart-summary-total');

        if (!cart || cart.length === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            if (activeState) activeState.classList.add('hidden');
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');
        if (activeState) activeState.classList.remove('hidden');

        const totalItemsCount = cart.reduce((sum, item) => sum + (parseInt(item.quantity, 10) || 1), 0);
        if (countLabel) countLabel.innerText = totalItemsCount + ' barang';

        let subtotal = 0;
        let html = '';

        cart.forEach((item, index) => {
            const qty = parseInt(item.quantity, 10) || 1;
            const price = parseInt(item.price, 10) || 0;
            const itemTotal = price * qty;
            subtotal += itemTotal;

            const formattedPrice = new Intl.NumberFormat('id-ID').format(price);
            const formattedTotal = new Intl.NumberFormat('id-ID').format(itemTotal);

            html += `
                <div class="pt-4 first:pt-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <img src="${item.image || '/images/tote-bag.jpg'}"
                             alt="${item.name}"
                             class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover bg-butter shrink-0 border border-ink/10">
                        <div class="space-y-1 min-w-0 flex-1">
                            <span class="text-[10px] uppercase font-bold text-ink/50 block tracking-wider">
                                ${item.category || 'Karya ABK'}
                            </span>
                            <h4 class="font-heading text-sm sm:text-base font-bold text-ink leading-snug line-clamp-1" title="${item.name}">
                                ${item.name}
                            </h4>
                            <p class="text-xs text-ink/70">
                                Rp ${formattedPrice} / pcs
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-4 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-ink/5">
                        <div class="flex items-center border border-ink/15 rounded-xl overflow-hidden bg-cream">
                            <button type="button"
                                    onclick="updateItemQty(${index}, -1)"
                                    class="w-8 h-8 flex items-center justify-center text-ink hover:bg-butter font-bold text-sm transition-colors"
                                    aria-label="Kurangi jumlah">
                                -
                            </button>
                            <span class="w-10 text-center font-bold text-xs text-ink select-none">
                                ${qty}
                            </span>
                            <button type="button"
                                    onclick="updateItemQty(${index}, 1)"
                                    class="w-8 h-8 flex items-center justify-center text-ink hover:bg-butter font-bold text-sm transition-colors"
                                    aria-label="Tambah jumlah">
                                +
                            </button>
                        </div>

                        <div class="text-right min-w-[90px]">
                            <span class="text-xs sm:text-sm font-extrabold text-ink block">
                                Rp ${formattedTotal}
                            </span>
                        </div>

                        <button type="button"
                                onclick="removeCartItem(${index})"
                                class="p-2 text-ink/40 hover:text-rose-600 rounded-lg transition-colors"
                                title="Hapus barang">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        });

        if (container) container.innerHTML = html;

        const formattedSubtotal = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
        if (subtotalEl) subtotalEl.innerText = formattedSubtotal;
        if (totalEl) totalEl.innerText = formattedSubtotal;
    }

    function updateItemQty(index, delta) {
        const cart = getKiosCart();
        if (!cart[index]) return;

        const newQty = (parseInt(cart[index].quantity, 10) || 1) + delta;
        if (newQty <= 0) {
            if (confirm(`Hapus "${cart[index].name}" dari keranjang?`)) {
                cart.splice(index, 1);
            }
        } else {
            cart[index].quantity = newQty;
        }

        saveKiosCart(cart);
        renderCartView();
    }

    function removeCartItem(index) {
        const cart = getKiosCart();
        if (!cart[index]) return;

        const name = cart[index].name;
        cart.splice(index, 1);
        saveKiosCart(cart);
        renderCartView();
        showKiosToast('Dihapus dari Keranjang', name, '🗑️');
    }

    function clearKiosCart() {
        if (!confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang belanja?')) return;
        saveKiosCart([]);
        renderCartView();
        showKiosToast('Keranjang Dikosongkan', 'Semua barang telah dihapus', 'ℹ️');
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderCartView();
    });

    window.addEventListener('kios-cart-updated', () => {
        renderCartView();
    });
</script>
@endsection

