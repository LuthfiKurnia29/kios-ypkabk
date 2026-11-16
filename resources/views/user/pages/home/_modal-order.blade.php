{{-- ========================================================
     MODAL: BELI & BANTU (ORDER CHECKOUT & QRIS DINAMIS)
     Checkout langsung tanpa registrasi akun (Guest Checkout).
     Multi-step: 1) Form Pesanan -> 2) QRIS & Bukti Bayar -> 3) Selesai / WhatsApp
     ======================================================== --}}
<div id="order-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 sm:p-7 space-y-4 shadow-xl relative max-h-[92vh] overflow-y-auto">
        {{-- Tombol Tutup --}}
        <button type="button"
                onclick="closeOrderModal()"
                class="absolute top-4 right-4 text-[#2B2A33]/50 hover:text-[#2B2A33] p-2 rounded-xl transition-colors"
                aria-label="Tutup modal">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        {{-- ========================================================
             LANGKAH 1: FORM PEMESANAN (GUEST CHECKOUT)
             ======================================================== --}}
        <div id="order-step-1" class="space-y-4">
            <div class="space-y-1">
                <span class="text-xs font-bold text-[#2B2A33] uppercase tracking-wider bg-[#FFC933] px-2.5 py-1 rounded-lg inline-block">
                    Beli &amp; Bantu (Pesan Langsung)
                </span>
                <h3 class="text-lg font-bold text-[#2B2A33]" id="modal-product-title">Produk Pilihan</h3>
                <p class="text-xs text-[#2B2A33]/70">Harga Donasi Satuan: <span id="modal-product-price" class="font-bold text-[#D81B60]">Rp 0</span></p>
            </div>

            <p class="text-xs text-[#2B2A33]/80 bg-[#FFF9F0] p-3 rounded-xl border border-[#2B2A33]/10 leading-relaxed">
                100% hasil penjualan dialokasikan untuk beasiswa terapi dan pelatihan vokasi anak istimewa di YPKABK.
            </p>

            <form id="order-form" onsubmit="handleOrderSubmit(event)" method="POST" action="{{ route('purchases.store') }}" class="space-y-3.5 text-xs font-medium text-[#2B2A33]">
                @csrf
                <input type="hidden" name="product_id" id="modal-product-id" value="">
                <input type="hidden" name="price" id="modal-product-raw-price" value="">

                <div>
                    <label class="block mb-1 font-semibold text-[#2B2A33]">Nama Lengkap Pemesan *</label>
                    <input type="text" name="customer_name" required placeholder="Contoh: Budi Santoso"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#2B2A33]/20 focus:outline-none focus:ring-2 focus:ring-[#D81B60]/30 text-sm">
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-[#2B2A33]">No. WhatsApp Aktif *</label>
                    <input type="tel" name="customer_phone" required placeholder="Contoh: 08123456789"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-[#2B2A33]/20 focus:outline-none focus:ring-2 focus:ring-[#D81B60]/30 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block mb-1 font-semibold text-[#2B2A33]">Jumlah (Pcs) *</label>
                        <input type="number" name="quantity" id="modal-order-quantity" min="1" value="1" required
                               oninput="updateModalSubtotal()"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#2B2A33]/20 focus:outline-none focus:ring-2 focus:ring-[#D81B60]/30 text-sm">
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold text-[#2B2A33]">Kota / Alamat</label>
                        <input type="text" name="customer_address" placeholder="Contoh: Sidoarjo"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-[#2B2A33]/20 focus:outline-none focus:ring-2 focus:ring-[#D81B60]/30 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-[#2B2A33]">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Pengiriman reguler atau pesan khusus"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-[#2B2A33]/20 focus:outline-none focus:ring-2 focus:ring-[#D81B60]/30 text-sm"></textarea>
                </div>

                {{-- Ringkasan Total Tagihan --}}
                <div class="flex items-center justify-between p-3 rounded-xl bg-[#FDE8F0] border border-[#D81B60]/20">
                    <span class="text-xs font-semibold text-[#2B2A33]">Total Donasi &amp; Belanja:</span>
                    <span id="modal-calculated-total" class="text-base font-extrabold text-[#D81B60]">Rp 0</span>
                </div>

                <div class="pt-2 space-y-2">
                    <button type="submit" id="order-submit-btn"
                            class="w-full min-h-[48px] py-3 px-4 rounded-full bg-[#D81B60] hover:bg-[#D81B60]/90 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        <span>Lanjut ke Pembayaran QRIS</span>
                    </button>

                    <button type="button"
                            onclick="closeOrderModal()"
                            class="w-full py-2 text-center text-xs font-semibold text-[#2B2A33]/60 hover:text-[#2B2A33]">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        {{-- ========================================================
             LANGKAH 2: TAMPILAN QRIS DINAMIS & UPLOAD BUKTI / WHATSAPP
             ======================================================== --}}
        <div id="order-step-2" class="hidden space-y-4">
            <div class="text-center space-y-1">
                <span class="text-xs font-bold text-[#D81B60] uppercase tracking-wider bg-[#FDE8F0] px-3 py-1 rounded-full inline-block">
                    Pembayaran QRIS Nasional
                </span>
                <h3 class="text-lg font-bold text-[#2B2A33]">Scan &amp; Bayar Sesuai Nominal</h3>
                <p class="text-xs text-[#2B2A33]/70">
                    Kode Pesanan: <span id="modal-qris-order-code" class="font-mono font-bold text-[#1A75B8]">ORD-XXXX</span>
                </p>
            </div>

            {{-- Kotak Nominal Terkunci --}}
            <div class="bg-[#FFF9F0] border border-[#FFC933] rounded-xl p-3.5 text-center space-y-1">
                <p class="text-xs font-medium text-[#2B2A33]/75">Total yang harus dibayar:</p>
                <div class="flex items-center justify-center gap-2">
                    <span id="modal-qris-amount-display" class="text-2xl font-black text-[#D81B60]">Rp 0</span>
                    <button type="button"
                            onclick="copyNominalAmount()"
                            title="Salin Nominal"
                            class="text-xs px-2 py-1 bg-white border border-[#2B2A33]/20 rounded-lg text-[#2B2A33] hover:bg-stone-50 font-semibold inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>Salin</span>
                    </button>
                </div>
                <p class="text-[11px] text-[#2B2A33]/60">Nominal otomatis terkunci pada QRIS saat di-scan.</p>
            </div>

            {{-- Container QR Code Dinamis --}}
            <div class="flex flex-col items-center justify-center p-4 bg-white rounded-xl border border-[#2B2A33]/15 space-y-2.5">
                <div id="modal-qris-qr-container" class="w-[200px] h-[200px] flex items-center justify-center bg-stone-50 rounded-lg overflow-hidden border border-stone-200">
                    <img id="modal-qris-image" src="" alt="Kode QRIS Dinamis" class="w-full h-full object-contain">
                </div>
                <div class="text-center">
                    <p class="text-[11px] font-bold text-[#2B2A33]">KIOS MANDIRI YPKABK</p>
                    <p class="text-[10px] text-[#2B2A33]/65">BCA Mobile, Livin Mandiri, BRImo, BNI, GoPay, OVO, ShopeePay, DANA</p>
                </div>
                <button type="button"
                        onclick="downloadQrisImage()"
                        class="text-xs text-[#1A75B8] hover:underline font-semibold flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh Gambar QRIS</span>
                </button>
            </div>

            {{-- Form Upload Bukti Transfer --}}
            <div class="border-t border-[#2B2A33]/10 pt-3 space-y-2.5">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-[#2B2A33]">Upload Bukti Transfer (Foto/Struk)</label>
                    <span class="text-[10px] text-[#2B2A33]/60">Maks. 5MB (JPG/PNG)</span>
                </div>

                <form id="upload-proof-form" onsubmit="handleProofSubmit(event)" class="space-y-2.5">
                    @csrf
                    <input type="file" id="modal-proof-file" accept="image/*" onchange="previewProofImage(event)"
                           class="w-full text-xs text-[#2B2A33]/80 file:mr-2.5 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#FDE8F0] file:text-[#D81B60] hover:file:bg-[#FDE8F0]/80 cursor-pointer">

                    {{-- Image Preview --}}
                    <div id="modal-proof-preview-wrapper" class="hidden relative max-h-40 rounded-xl overflow-hidden border border-[#2B2A33]/15 bg-stone-50 p-1 flex justify-center">
                        <img id="modal-proof-preview-img" src="" alt="Pratinjau Bukti Transfer" class="max-h-36 object-contain rounded-lg">
                    </div>

                    <button type="submit" id="modal-proof-submit-btn"
                            class="w-full min-h-[44px] py-2.5 px-4 rounded-full bg-[#1A75B8] hover:bg-[#1A75B8]/90 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Kirim Bukti Pembayaran</span>
                    </button>
                </form>
            </div>

            {{-- Opsi Alternatif: Langsung WhatsApp --}}
            <div class="pt-1 border-t border-[#2B2A33]/10">
                <p class="text-[11px] text-center text-[#2B2A33]/60 mb-2">Atau jika ingin kirim foto langsung via chat WhatsApp:</p>
                <button type="button"
                        id="modal-direct-whatsapp-btn"
                        onclick="openDirectWhatsApp()"
                        class="w-full min-h-[44px] py-2.5 px-4 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98]">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                    <span>Konfirmasi &amp; Kirim Bukti via WhatsApp</span>
                </button>
            </div>
        </div>

        {{-- ========================================================
             LANGKAH 3: TAMPILAN SUKSES / SELESAI
             ======================================================== --}}
        <div id="order-step-3" class="hidden text-center space-y-4 py-2">
            <div class="w-14 h-14 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>

            <div class="space-y-1">
                <h3 class="text-lg font-bold text-[#2B2A33]">Bukti Pembayaran Terkirim!</h3>
                <p class="text-xs text-[#2B2A33]/75 leading-relaxed">
                    Terima kasih atas pesanan dan kepedulian Anda. Kode pesanan Anda:
                    <span id="modal-success-order-code" class="font-mono font-bold text-[#D81B60] block text-sm mt-1">ORD-XXXX</span>
                </p>
            </div>

            <p class="text-xs text-[#2B2A33]/80 bg-[#FFF9F0] p-3 rounded-xl border border-[#2B2A33]/10 leading-relaxed text-left">
                Tim Kios Mandiri YPKABK akan segera memverifikasi bukti pembayaran dan menyiapkan pesanan Anda. Anda dapat mengabari admin secara instan lewat WhatsApp.
            </p>

            <div class="space-y-2 pt-1">
                <button type="button"
                        id="modal-success-whatsapp-btn"
                        onclick="openSuccessWhatsApp()"
                        class="w-full min-h-[48px] py-3 px-4 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98]">
                    <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                    <span>Buka Chat WhatsApp Admin</span>
                </button>

                <button type="button"
                        onclick="closeOrderModal()"
                        class="w-full min-h-[44px] py-2.5 px-4 rounded-full border border-[#2B2A33]/20 text-[#2B2A33] hover:bg-stone-50 font-bold text-xs transition-colors">
                    Selesai &amp; Tutup
                </button>
            </div>
        </div>
    </div>
</div>
