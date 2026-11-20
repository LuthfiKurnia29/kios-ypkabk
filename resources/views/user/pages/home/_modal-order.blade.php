{{-- ========================================================
     MODAL: PEMESANAN / CHECKOUT PRODUK - FR-03 / NFR-03
     ======================================================== --}}
<div id="order-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl relative max-h-[92vh] overflow-y-auto border border-teks/10">
        {{-- Close Modal Button (Kontrol antarmuka murni) --}}
        <button type="button"
                onclick="closeOrderModal()"
                class="absolute top-5 right-5 text-teks/50 hover:text-teks p-2 rounded-xl transition-colors cursor-pointer text-xl leading-none font-bold"
                aria-label="Tutup formulir pesanan">
            &times;
        </button>

        {{-- ========================================================
             LANGKAH 1: FORMULIR IDENTITAS, ALAMAT & METODE BAYAR
             ======================================================== --}}
        <div id="order-step-1" class="space-y-4">
            <div class="space-y-1">
                <span class="inline-block text-[11px] font-bold text-primary uppercase tracking-wider bg-primary/10 border border-primary/20 px-2.5 py-1 rounded-lg">
                    Formulir Pembelian &amp; Donasi
                </span>
                <h3 class="text-xl font-bold text-teks">Pesan Produk Karya ABK</h3>
                <p class="text-xs text-teks/65 leading-relaxed">Hasil penjualan 100% dialokasikan untuk program pelatihan dan operasional kemandirian ABK.</p>
            </div>

            {{-- Ringkasan Produk Terpilih --}}
            <div class="p-3.5 bg-stone-50 rounded-xl border border-teks/10">
                <h4 id="modal-product-title" class="font-bold text-teks text-sm truncate">Nama Produk</h4>
                <p class="text-xs text-primary font-bold mt-0.5">
                    <span id="modal-product-price">Rp 0</span> <span class="text-teks/50 font-normal">/ item</span>
                </p>
            </div>

            <form id="order-form" onsubmit="handleOrderSubmit(event)" class="space-y-4 text-xs font-medium text-teks">
                @csrf
                <input type="hidden" id="modal-product-id" name="product_id" value="">
                <input type="hidden" id="modal-product-raw-price" value="0">

                {{-- Jumlah Item --}}
                <div>
                    <label class="block mb-1 font-semibold text-teks">Jumlah Pesanan *</label>
                    <div class="flex items-center gap-3">
                        <input type="number" id="modal-order-quantity" name="quantity" min="1" max="99" value="1" required
                               onchange="updateModalSubtotal()" onkeyup="updateModalSubtotal()"
                               class="w-24 px-3 py-2 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-center font-bold text-sm bg-white">
                        <span class="text-xs text-teks/60">pcs (buah)</span>
                    </div>
                </div>

                {{-- 1. Identitas Pembeli (Tanpa Email, Hanya Nama & No WA) --}}
                <div class="border-t border-teks/10 pt-3 space-y-3">
                    <h4 class="font-bold text-teks text-xs uppercase tracking-wider text-primary">Data Pemesan</h4>

                    <div>
                        <label class="block mb-1 font-semibold text-teks">Nama Lengkap Pembeli *</label>
                        <input type="text" name="customer_name" required placeholder="Contoh: Siti Rahmawati"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm bg-white">
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold text-teks">Nomor WhatsApp *</label>
                        <input type="tel" name="customer_phone" required placeholder="081234567890"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm bg-white">
                        <p class="text-[11px] text-teks/60 mt-1">Nota pembelian dan tautan pembayaran dapat dikirimkan ke nomor ini.</p>
                    </div>
                </div>

                {{-- 2. Detail Alamat Pengiriman Berurutan --}}
                <div class="border-t border-teks/10 pt-3 space-y-3">
                    <h4 class="font-bold text-teks text-xs uppercase tracking-wider text-primary">Alamat Tujuan Pengiriman</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 font-semibold text-teks">Kota / Kabupaten *</label>
                            <input type="text" name="city" required placeholder="Contoh: Kota Surabaya"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm bg-white">
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-teks">Kecamatan *</label>
                            <input type="text" name="district" required placeholder="Contoh: Gubeng"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 font-semibold text-teks">Desa / Kelurahan *</label>
                            <input type="text" name="village" required placeholder="Contoh: Mojo"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm bg-white">
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-teks">Jalan dan Nomor *</label>
                            <input type="text" name="street_address" required placeholder="Contoh: Jl. Dharmawangsa No. 25"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm bg-white">
                        </div>
                    </div>

                    {{-- Textarea: Patokan / Alamat Lengkap --}}
                    <div>
                        <label class="block mb-1 font-semibold text-teks">Patokan / Detail Alamat *</label>
                        <textarea name="address" rows="3" required
                                  placeholder="Contoh: RT 03 / RW 05, rumah pagar hitam seberang Musholla Al-Ikhlas, gang kedua sebelah kiri."
                                  class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm bg-white"></textarea>
                    </div>
                </div>

                {{-- 3. Pilihan Metode Pembayaran (QRIS / Transfer Bank) --}}
                <div class="border-t border-teks/10 pt-3 space-y-3">
                    <label class="block font-bold text-teks text-xs uppercase tracking-wider text-primary">Metode Pembayaran *</label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        {{-- Opsi 1: QRIS --}}
                        <label id="payment-card-qris"
                               onclick="selectPaymentMethod('qris')"
                               class="relative flex items-start gap-3 p-3.5 rounded-xl border-2 border-primary bg-primary/5 cursor-pointer transition-all">
                            <input type="radio" name="payment_method" value="qris" checked
                                   onchange="togglePaymentMethodChoice('qris')"
                                   class="mt-0.5 w-4 h-4 text-primary accent-primary cursor-pointer">
                            <div class="space-y-0.5">
                                <span class="text-xs font-bold text-teks block">QRIS Nasional</span>
                                <span class="text-[11px] text-teks/65 block">Scan m-Banking &amp; e-Wallet langsung</span>
                            </div>
                        </label>

                        {{-- Opsi 2: Transfer Bank --}}
                        <label id="payment-card-bank"
                               onclick="selectPaymentMethod('bank_transfer')"
                               class="relative flex items-start gap-3 p-3.5 rounded-xl border-2 border-teks/15 hover:border-primary/50 bg-white cursor-pointer transition-all">
                            <input type="radio" name="payment_method" value="bank_transfer"
                                   onchange="togglePaymentMethodChoice('bank_transfer')"
                                   class="mt-0.5 w-4 h-4 text-primary accent-primary cursor-pointer">
                            <div class="space-y-0.5">
                                <span class="text-xs font-bold text-teks block">Transfer Bank</span>
                                <span class="text-[11px] text-teks/65 block">BCA, BRI, Mandiri, BNI</span>
                            </div>
                        </label>
                    </div>

                    {{-- Pilihan Rekening Bank Statis (Muncul saat Transfer Bank aktif) --}}
                    <div id="bank-choice-container" class="hidden pt-2 space-y-2 bg-stone-50 p-3.5 rounded-xl border border-teks/10">
                        <span class="text-[11px] font-bold text-teks uppercase tracking-wide block">Pilih Rekening Bank Tujuan:</span>
                        <div class="grid grid-cols-2 gap-2">
                            {{-- BCA --}}
                            <label class="flex items-center gap-2 p-2.5 rounded-lg border border-teks/20 bg-white hover:border-primary cursor-pointer transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="radio" name="bank_name" value="BCA" checked onchange="selectBankAccount('BCA')" class="accent-primary w-3.5 h-3.5">
                                <div>
                                    <span class="font-bold text-xs text-teks block">Bank BCA</span>
                                    <span class="text-[10px] text-teks/60 block">018-384-9900</span>
                                </div>
                            </label>

                            {{-- BRI --}}
                            <label class="flex items-center gap-2 p-2.5 rounded-lg border border-teks/20 bg-white hover:border-primary cursor-pointer transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="radio" name="bank_name" value="BRI" onchange="selectBankAccount('BRI')" class="accent-primary w-3.5 h-3.5">
                                <div>
                                    <span class="font-bold text-xs text-teks block">Bank BRI</span>
                                    <span class="text-[10px] text-teks/60 block">009-601-002-345-502</span>
                                </div>
                            </label>

                            {{-- Mandiri --}}
                            <label class="flex items-center gap-2 p-2.5 rounded-lg border border-teks/20 bg-white hover:border-primary cursor-pointer transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="radio" name="bank_name" value="Mandiri" onchange="selectBankAccount('Mandiri')" class="accent-primary w-3.5 h-3.5">
                                <div>
                                    <span class="font-bold text-xs text-teks block">Bank Mandiri</span>
                                    <span class="text-[10px] text-teks/60 block">142-00-1988234-1</span>
                                </div>
                            </label>

                            {{-- BNI --}}
                            <label class="flex items-center gap-2 p-2.5 rounded-lg border border-teks/20 bg-white hover:border-primary cursor-pointer transition-colors has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                <input type="radio" name="bank_name" value="BNI" onchange="selectBankAccount('BNI')" class="accent-primary w-3.5 h-3.5">
                                <div>
                                    <span class="font-bold text-xs text-teks block">Bank BNI</span>
                                    <span class="text-[10px] text-teks/60 block">083-912-4567</span>
                                </div>
                            </label>
                        </div>
                        <p class="text-[10px] text-teks/60 italic pt-1">Seluruh rekening resmi atas nama: <strong>Yayasan Peduli Kasih ABK</strong>.</p>
                    </div>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-teks">Catatan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Pesan khusus atau petunjuk pengiriman"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm bg-white"></textarea>
                </div>

                {{-- Ringkasan Total Tagihan --}}
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-primary/5 border border-primary/20">
                    <span class="text-xs font-semibold text-teks">Total Donasi &amp; Belanja:</span>
                    <span id="modal-calculated-total" class="text-base font-extrabold text-primary">Rp 0</span>
                </div>

                <div class="pt-2 space-y-2">
                    <button type="submit" id="order-submit-btn"
                            class="w-full min-h-[48px] py-3 px-4 rounded-full bg-primary hover:bg-primary/90 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-[0.98] cursor-pointer">
                        <span id="order-submit-btn-text">Lanjut ke Pembayaran QRIS</span>
                    </button>

                    <button type="button"
                            onclick="closeOrderModal()"
                            class="w-full py-2 text-center text-xs font-semibold text-teks/60 hover:text-teks cursor-pointer">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        {{-- ========================================================
             LANGKAH 2: TAMPILAN PEMBAYARAN (QRIS / BANK) & UPLOAD BUKTI / WHATSAPP
             ======================================================== --}}
        <div id="order-step-2" class="hidden space-y-4">
            <div class="text-center space-y-1">
                <span id="modal-pay-badge" class="text-xs font-bold text-primary uppercase tracking-wider bg-primary/10 border border-primary/20 px-3 py-1 rounded-full inline-block">
                    Pembayaran Pesanan
                </span>
                <h3 class="text-lg font-bold text-teks">Selesaikan Pembayaran</h3>
                <p class="text-xs text-teks/70">
                    Kode Pesanan: <span id="modal-qris-order-code" class="font-bold text-primary">ORD-XXXX</span>
                </p>
            </div>

            {{-- Kotak Nominal Terkunci --}}
            <div class="bg-stone-50 border border-teks/10 rounded-xl p-3.5 text-center space-y-1">
                <p class="text-xs font-medium text-teks/75">Total yang harus dibayar:</p>
                <div class="flex items-center justify-center gap-2">
                    <span id="modal-qris-amount-display" class="text-2xl font-black text-primary">Rp 0</span>
                    <button type="button"
                            onclick="copyNominalAmount()"
                            title="Salin Nominal"
                            class="text-xs px-2.5 py-1 bg-white border border-teks/20 rounded-lg text-teks hover:bg-stone-100 font-semibold inline-flex items-center cursor-pointer">
                        <span>Salin</span>
                    </button>
                </div>
            </div>

            {{-- Container 1: Tampilan QRIS --}}
            <div id="modal-view-qris" class="flex flex-col items-center justify-center p-4 bg-white rounded-xl border border-teks/15 space-y-2.5 text-center">
                <div id="modal-qris-qr-container" class="w-[200px] h-[200px] flex items-center justify-center bg-stone-50 rounded-lg overflow-hidden border border-stone-200">
                    <img id="modal-qris-image" src="" alt="Kode QRIS Dinamis" class="w-full h-full object-contain">
                </div>
                <div class="text-center">
                    <p class="text-[11px] font-bold text-teks">KIOS MANDIRI YPKABK</p>
                    <p class="text-[10px] text-teks/65">BCA Mobile, Livin Mandiri, BRImo, BNI, GoPay, OVO, ShopeePay, DANA</p>
                </div>
                <button type="button"
                        onclick="downloadQrisImage()"
                        class="text-xs text-primary hover:underline font-semibold cursor-pointer">
                    Unduh Gambar QRIS
                </button>
            </div>

            {{-- Container 2: Tampilan Rekening Bank --}}
            <div id="modal-view-bank" class="hidden bg-white rounded-xl border border-teks/15 p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-teks/70 uppercase">Bank Tujuan</span>
                    <span id="modal-bank-badge-name" class="text-xs font-bold px-2.5 py-0.5 rounded bg-blue-50 text-blue-700">Bank BCA</span>
                </div>
                <div class="space-y-1">
                    <span class="text-xs text-teks/60">Nomor Rekening:</span>
                    <div class="flex items-center gap-2">
                        <span id="modal-bank-acc-display" class="text-xl font-black text-teks tracking-wider">018-384-9900</span>
                        <button type="button"
                                onclick="copyBankAccount()"
                                class="px-2.5 py-1 bg-stone-100 hover:bg-stone-200 border border-teks/20 rounded-lg text-xs font-bold text-teks inline-flex items-center cursor-pointer">
                            <span>Salin No. Rek</span>
                        </button>
                    </div>
                    <p class="text-xs text-teks/80">Atas Nama: <strong class="text-teks">Yayasan Peduli Kasih ABK</strong></p>
                </div>
            </div>

            {{-- Form Upload Bukti Transfer --}}
            <div class="border-t border-teks/10 pt-3 space-y-2.5">
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-teks">Upload Bukti Pembayaran (Foto/Struk)</label>
                    <span class="text-[10px] text-teks/60">Maks. 5MB (JPG/PNG/WebP)</span>
                </div>

                <form id="upload-proof-form" onsubmit="handleProofSubmit(event)" class="space-y-2.5">
                    @csrf
                    <input type="file" id="modal-proof-file" accept="image/*" onchange="previewProofImage(event)"
                           class="w-full text-xs text-teks/80 file:mr-2.5 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary/90 cursor-pointer border border-teks/20 rounded-xl p-1.5 bg-white">

                    {{-- Image Preview --}}
                    <div id="modal-proof-preview-wrapper" class="hidden relative max-h-40 rounded-xl overflow-hidden border border-teks/15 bg-stone-50 p-1 flex justify-center">
                        <img id="modal-proof-preview-img" src="" alt="Pratinjau Bukti Transfer" class="max-h-36 object-contain rounded-lg">
                    </div>

                    <button type="submit" id="modal-proof-submit-btn"
                            class="w-full min-h-[44px] py-2.5 px-4 rounded-full bg-primary hover:bg-primary/90 text-white font-bold text-xs flex items-center justify-center shadow-sm transition-all active:scale-[0.98] cursor-pointer">
                        <span>Kirim Bukti Pembayaran</span>
                    </button>
                </form>
            </div>

            {{-- Tombol Kirim Nota ke WhatsApp Saya & Link Halaman Detail --}}
            <div class="pt-2 border-t border-teks/10 space-y-2">
                <button type="button"
                        id="modal-send-wa-receipt-btn"
                        onclick="sendOrderReceiptToWhatsapp()"
                        class="w-full min-h-[44px] py-2.5 px-4 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center shadow-sm transition-all active:scale-[0.98] cursor-pointer">
                    <span>Kirim Nota ke WhatsApp Saya</span>
                </button>

                <div class="flex items-center justify-between text-xs pt-1">
                    <a id="modal-open-detail-link" href="#" target="_blank" class="text-primary hover:underline font-bold inline-flex items-center">
                        <span>Buka Halaman Pesanan Lengkap &rarr;</span>
                    </a>
                    <button type="button" onclick="closeOrderModal()" class="text-teks/60 hover:text-teks font-medium cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- ========================================================
             LANGKAH 3: TAMPILAN SUKSES / SELESAI
             ======================================================== --}}
        <div id="order-step-3" class="hidden text-center space-y-4 py-2">
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-teks">Bukti Pembayaran Terkirim!</h3>
                <p class="text-xs text-teks/75 leading-relaxed">
                    Terima kasih atas pesanan dan kepedulian Anda. Kode pesanan Anda:
                    <span id="modal-success-order-code" class="font-bold text-primary block text-sm mt-1">ORD-XXXX</span>
                </p>
            </div>

            <p class="text-xs text-teks/80 bg-stone-50 p-3 rounded-xl border border-teks/10 leading-relaxed text-left">
                Tim Kios Mandiri YPKABK akan segera memverifikasi bukti pembayaran dan menyiapkan pesanan Anda.
            </p>

            <div class="space-y-2 pt-1">
                <a id="modal-success-page-link" href="#"
                   class="w-full min-h-[48px] py-3 px-4 rounded-full bg-primary hover:bg-primary/90 text-white font-bold text-sm flex items-center justify-center shadow-sm transition-all active:scale-[0.98]">
                    <span>Lihat Nota &amp; Lacak Pesanan</span>
                </a>

                <button type="button"
                        onclick="closeOrderModal()"
                        class="w-full min-h-[44px] py-2.5 px-4 rounded-full border border-teks/20 text-teks hover:bg-stone-50 font-bold text-xs transition-colors cursor-pointer">
                    Selesai &amp; Tutup
                </button>
            </div>
        </div>
    </div>
</div>
