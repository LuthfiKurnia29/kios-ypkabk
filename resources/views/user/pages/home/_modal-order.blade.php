{{-- ========================================================
     MODAL: BELI & BANTU (ORDER CHECKOUT) - FR-03 / FR-06 / NFR-02
     Checkout langsung tanpa registrasi akun (Guest Checkout).
     ======================================================== --}}
<div id="order-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        <button type="button"
                onclick="closeOrderModal()"
                class="absolute top-5 right-5 text-ink/50 hover:text-ink p-2 rounded-xl transition-colors"
                aria-label="Tutup modal">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="space-y-1">
            <span class="text-xs font-bold text-ink uppercase tracking-wider bg-sun px-2.5 py-1 rounded-lg inline-block">
                Beli &amp; Bantu (Pesan Langsung)
            </span>
            <h3 class="text-lg font-bold text-ink" id="modal-product-title">Produk Pilihan</h3>
            <p class="text-xs text-ink/65">Harga Donasi: <span id="modal-product-price" class="font-bold text-ink">Rp 0</span></p>
        </div>

        <p class="text-xs text-ink/80 bg-cream p-3 rounded-xl border border-ink/15 leading-relaxed">
            100% dana dari pembelian ini langsung dialokasikan ke pos kas beasiswa terapi &amp; pelatihan vokasi kemandirian anak istimewa YPKABK.
        </p>

        <form id="order-form" onsubmit="handleOrderSubmit(event)" method="POST" action="{{ route('purchases.store') }}" class="space-y-3.5 text-xs font-medium text-ink">
            @csrf
            <input type="hidden" name="product_id" id="modal-product-id" value="">
            <input type="hidden" name="price" id="modal-product-raw-price" value="">

            <div>
                <label class="block mb-1 font-semibold text-ink">Nama Pemesan *</label>
                <input type="text" name="customer_name" required placeholder="Contoh: Budi Santoso"
                       class="w-full px-3 py-2 rounded-xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun text-sm">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-ink">No. WhatsApp *</label>
                <input type="tel" name="customer_phone" required placeholder="Contoh: 08123456789"
                       class="w-full px-3 py-2 rounded-xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun text-sm">
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block mb-1 font-semibold text-ink">Jumlah *</label>
                    <input type="number" name="quantity" id="modal-order-quantity" min="1" value="1" required
                           class="w-full px-3 py-2 rounded-xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun text-sm">
                </div>
                <div>
                    <label class="block mb-1 font-semibold text-ink">Kota / Alamat</label>
                    <input type="text" name="customer_address" placeholder="Sidoarjo / Surabaya"
                           class="w-full px-3 py-2 rounded-xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun text-sm">
                </div>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-ink">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Contoh: Pengiriman via ekspedisi reguler"
                          class="w-full px-3 py-2 rounded-xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun text-sm"></textarea>
            </div>

            <div class="pt-2 space-y-2">
                <button type="submit" id="order-submit-btn"
                        class="w-full min-h-[48px] py-3 px-4 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
                        <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                    </svg>
                    <span>Pesan &amp; Lanjut ke WhatsApp</span>
                </button>

                <button type="button"
                        onclick="closeOrderModal()"
                        class="w-full py-2 text-center text-xs font-semibold text-ink/60 hover:text-ink">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
