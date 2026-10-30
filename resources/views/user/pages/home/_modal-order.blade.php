{{-- ========================================================
     MODAL: BELI & BANTU (ORDER)
     ======================================================== --}}
<div id="order-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl relative">
        <button type="button"
                onclick="closeOrderModal()"
                class="absolute top-5 right-5 text-ink/50 hover:text-ink p-2 rounded-xl transition-colors"
                aria-label="Tutup modal">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="space-y-1">
            <span class="text-xs font-bold text-ink uppercase tracking-wider bg-sun px-2.5 py-1 rounded-lg">
                Beli &amp; Bantu
            </span>
            <h3 class="text-lg font-bold text-ink" id="modal-product-title">Produk Pilihan</h3>
            <p class="text-xs text-ink/65">Harga Donasi: <span id="modal-product-price" class="font-bold text-ink">Rp 0</span></p>
        </div>

        <p class="text-sm text-ink/80 bg-cream p-3 rounded-2xl border border-ink/15">
            100% dana dari pembelian ini langsung dialokasikan ke pos kas pelatihan &amp; rehabilitasi anak istimewa YPKABK.
        </p>

        <div class="space-y-3 pt-2">
            <a id="modal-wa-btn"
               href="#"
               target="_blank"
               rel="noopener noreferrer"
               class="w-full min-h-[48px] py-3 px-4 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/>
                </svg>
                <span>Pesan Sekarang via WhatsApp Kios</span>
            </a>
            <button type="button"
                    onclick="closeOrderModal()"
                    class="w-full py-2.5 text-center text-sm font-semibold text-ink/60 hover:text-ink">
                Tutup
            </button>
        </div>
    </div>
</div>
