{{-- ========================================================
     MODAL: PEMESANAN PRINT ON DEMAND (KUSTOM) - USER SIDE
     ======================================================== --}}
<div id="on-demand-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 sm:p-8 space-y-5 shadow-2xl relative max-h-[92vh] overflow-y-auto border border-teks/10">
        {{-- Close Modal Button --}}
        <button type="button"
                onclick="closeOnDemandModal()"
                class="absolute top-5 right-5 text-teks/50 hover:text-teks p-2 rounded-xl transition-colors cursor-pointer text-xl leading-none font-bold"
                aria-label="Tutup formulir print on demand">
            &times;
        </button>

        {{-- Step 1: Formulir Pemesanan On Demand --}}
        <div id="pod-step-1" class="space-y-4">
            <div class="space-y-1">
                <span class="inline-block text-[11px] font-bold text-primary uppercase tracking-wider bg-pink-pucat border border-primary/20 px-2.5 py-1 rounded-lg">
                    Layanan Print on Demand
                </span>
                <h3 class="text-xl font-bold text-teks">Pesan Cetak Kustom Karya ABK</h3>
                <p class="text-xs text-teks/65 leading-relaxed">
                    Ajukan pesanan cetak dengan desain Anda sendiri atau kolaborasi karya ABK. Pesanan akan diverifikasi oleh Admin terlebih dahulu sebelum pembayaran.
                </p>
            </div>

            {{-- Ringkasan Produk Pilihan / Kategori Dasar --}}
            <div class="p-4 bg-stone-50 rounded-xl border border-teks/10 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-teks/60 uppercase">Item Dasar Pesanan</span>
                    <span id="pod-modal-base-badge" class="text-[11px] font-semibold text-primary bg-pink-pucat border border-primary/20 px-2 py-0.5 rounded">Cetak Kustom</span>
                </div>
                <h4 id="pod-modal-product-title" class="font-bold text-teks text-sm">Tote Bag Kanvas Lukis Kustom</h4>
                <div class="flex items-center justify-between text-xs text-primary font-bold">
                    <span>
                        <span id="pod-modal-unit-price">Rp 45.000</span> <span class="text-teks/50 font-normal">/ item</span>
                    </span>
                    <span class="text-teks/70 font-medium">
                        Estimasi Subtotal: <strong id="pod-modal-subtotal-display" class="text-primary font-black">Rp 45.000</strong>
                    </span>
                </div>
            </div>

            <form id="on-demand-form" onsubmit="handleOnDemandSubmit(event)" enctype="multipart/form-data" class="space-y-4 text-xs font-medium text-teks">
                @csrf
                <input type="hidden" id="pod-product-id" name="product_id" value="">
                <input type="hidden" id="pod-raw-unit-price" name="price" value="45000">

                {{-- 1. Pilihan Varian Dasar (Jika dibuka dari menu umum) --}}
                <div id="pod-product-selector-group">
                    <label class="block mb-1.5 font-semibold text-teks">Pilih Jenis Media Cetak *</label>
                    <select id="pod-product-preset-select" onchange="handlePodPresetChange()"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-xs font-semibold bg-white cursor-pointer">
                        <option value="45000" data-name="Tote Bag Kanvas Kustom (36x40cm)">Tote Bag Kanvas Tebal (Rp 45.000 / pcs)</option>
                        <option value="65000" data-name="Kaos Katun Combed 30s Kustom">Kaos Katun Combed 30s (Rp 65.000 / pcs)</option>
                        <option value="25000" data-name="Pouch Kanvas Multifungsi Kustom">Pouch Kanvas Multifungsi (Rp 25.000 / pcs)</option>
                        <option value="35000" data-name="Mug Keramik Sablon Sublim Kustom">Mug Keramik Sublim (Rp 35.000 / pcs)</option>
                    </select>
                </div>

                {{-- 2. Jumlah Pesanan (Quantity) --}}
                <div>
                    <label class="block mb-1 font-semibold text-teks">Jumlah Pesanan (Quantity) *</label>
                    <div class="flex items-center gap-3">
                        <input type="number" id="pod-order-quantity" name="quantity" min="1" max="1000" value="1" required
                               onchange="updatePodSubtotal()" onkeyup="updatePodSubtotal()"
                               class="w-28 px-3.5 py-2 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-center font-bold text-sm bg-white">
                        <span class="text-xs text-teks/70">pcs / buah (Dapat memesan satuan atau partai banyak)</span>
                    </div>
                </div>

                {{-- 3. Upload File Desain / Gambar Kustom --}}
                <div class="border-t border-teks/10 pt-3 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block font-semibold text-teks">Upload File Desain / Gambar Kustom *</label>
                        <span class="text-[10px] text-teks/50">Maks. 10MB (JPG, PNG, WebP, PDF)</span>
                    </div>
                    <input type="file" id="pod-design-file" name="design_file" required
                           accept="image/jpeg,image/png,image/webp,application/pdf"
                           onchange="previewPodDesign(event)"
                           class="w-full text-xs text-teks/80 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary/90 cursor-pointer border border-teks/20 rounded-xl p-1.5 bg-white">

                    {{-- Image Preview Container --}}
                    <div id="pod-design-preview-wrapper" class="hidden relative max-h-48 rounded-xl overflow-hidden border border-primary/20 bg-pink-pucat/50 p-2 flex flex-col items-center justify-center">
                        <img id="pod-design-preview-img" src="" alt="Pratinjau Desain" class="max-h-40 object-contain rounded-lg">
                        <span id="pod-design-filename" class="text-[11px] text-teks font-medium mt-1 truncate max-w-full"></span>
                    </div>

                    <p class="text-[11px] text-teks/60 leading-relaxed">
                        Gunakan file dengan resolusi terbaik agar hasil cetak tajam. Admin akan mengecek file desain sebelum menyetujui pesanan.
                    </p>
                </div>

                {{-- 4. Catatan Spesifikasi Kustom --}}
                <div class="space-y-1">
                    <label class="block font-semibold text-teks">Catatan Spesifikasi Kustom (Opsional)</label>
                    <textarea name="design_notes" rows="2"
                              placeholder="Contoh: Letak sablon di dada tengah, ukuran cetak A4, warna bahan kain hitam, tambahkan tulisan kecil 'YPKABK'."
                              class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-xs bg-white"></textarea>
                </div>

                {{-- 5. Data Pemesan --}}
                <div class="border-t border-teks/10 pt-3 space-y-3">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-primary">Data Pemesan</h4>

                    <div>
                        <label class="block mb-1 font-semibold text-teks">Nama Lengkap Pemesan *</label>
                        <input type="text" name="customer_name" required placeholder="Contoh: Siti Rahmawati"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-xs bg-white">
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold text-teks">Nomor WhatsApp Aktif *</label>
                        <input type="tel" name="customer_phone" required placeholder="Contoh: 081234567890"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-xs bg-white">
                        <p class="text-[11px] text-teks/60 mt-0.5">Admin akan mengonfirmasi persetujuan desain dan pembayaran ke nomor WhatsApp ini.</p>
                    </div>
                </div>

                {{-- 6. Detail Alamat Pengiriman Berstruktur --}}
                <div class="border-t border-teks/10 pt-3 space-y-3">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-primary">Alamat Tujuan Pengiriman</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 font-semibold text-teks">Kota / Kabupaten *</label>
                            <input type="text" name="city" required placeholder="Contoh: Kota Surabaya"
                                   class="w-full px-3.5 py-2 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-xs bg-white">
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-teks">Kecamatan *</label>
                            <input type="text" name="district" required placeholder="Contoh: Gubeng"
                                   class="w-full px-3.5 py-2 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-xs bg-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block mb-1 font-semibold text-teks">Desa / Kelurahan *</label>
                            <input type="text" name="village" required placeholder="Contoh: Mojo"
                                   class="w-full px-3.5 py-2 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-xs bg-white">
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold text-teks">Jalan dan Nomor *</label>
                            <input type="text" name="street_address" required placeholder="Contoh: Jl. Dharmawangsa No. 25"
                                   class="w-full px-3.5 py-2 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-xs bg-white">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold text-teks">Patokan / Detail Alamat *</label>
                        <textarea name="address" rows="2" required placeholder="Contoh: Pagar hitam depan lapangan RW, seberang masjid."
                                  class="w-full px-3.5 py-2 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-xs bg-white"></textarea>
                    </div>
                </div>

                @php
                    $payCfg = $paymentConfig ?? app(\App\Http\Services\PaymentSettingService::class)->getPaymentConfig();
                    $isQrisActive = !empty($payCfg['qris_enabled']);
                    $isBankActive = !empty($payCfg['bank_enabled']);
                    $activeBanks = $payCfg['bank_accounts'] ?? [];
                    $defaultMethod = $payCfg['default_method'] ?? 'qris';
                @endphp

                {{-- 7. Pilihan Rencana Pembayaran --}}
                @if($isQrisActive || $isBankActive)
                <div class="border-t border-teks/10 pt-3 space-y-3">
                    <h4 class="font-bold text-xs uppercase tracking-wider text-primary">Rencana Pembayaran (Setelah Disetujui)</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @if($isQrisActive)
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-teks/20 cursor-pointer hover:bg-stone-50 transition-colors">
                            <input type="radio" name="payment_method" value="qris" {{ $defaultMethod === 'qris' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            <div>
                                <span class="font-bold text-xs block text-teks">QRIS</span>
                                <span class="text-[10px] text-teks/60">Semua E-Wallet &amp; M-Banking</span>
                            </div>
                        </label>
                        @endif
                        @if($isBankActive)
                        <label class="flex items-center gap-2.5 p-3 rounded-xl border border-teks/20 cursor-pointer hover:bg-stone-50 transition-colors">
                            <input type="radio" name="payment_method" value="bank_transfer" {{ $defaultMethod === 'bank_transfer' ? 'checked' : '' }} class="text-primary focus:ring-primary">
                            <div>
                                <span class="font-bold text-xs block text-teks">Transfer Bank</span>
                                <span class="text-[10px] text-teks/60">{{ implode(', ', array_slice(array_keys($activeBanks), 0, 4)) }}</span>
                            </div>
                        </label>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Submit Buttons --}}
                <div class="pt-4 border-t border-teks/10 space-y-2">
                    <button type="submit" id="pod-submit-btn"
                            class="w-full min-h-[46px] py-3 px-4 rounded-full bg-primary hover:bg-primary/90 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-md transition-all active:scale-[0.98] cursor-pointer">
                        <span>Ajukan Pesanan On Demand</span>
                    </button>

                    <button type="button" onclick="closeOnDemandModal()"
                            class="w-full py-2 text-center text-xs font-semibold text-teks/60 hover:text-teks cursor-pointer">
                        Batal
                    </button>
                </div>
            </form>
        </div>

        {{-- Step 2: Konfirmasi Berhasil Diajukan --}}
        <div id="pod-step-2" class="hidden text-center space-y-4 py-2">
            <div class="w-14 h-14 mx-auto rounded-full bg-amber-100 text-amber-800 flex items-center justify-center text-2xl">
                ⏳
            </div>
            <div class="space-y-1">
                <span class="text-xs font-bold text-amber-800 uppercase tracking-wider bg-amber-50 border border-amber-200 px-3 py-1 rounded-full inline-block">
                    Menunggu Persetujuan Admin
                </span>
                <h3 class="text-xl font-bold text-teks">Pesanan Berhasil Diajukan!</h3>
                <p class="text-xs text-teks/70">
                    Kode Pesanan: <span id="pod-success-order-code" class="font-mono font-bold text-primary text-sm">POD-XXXX</span>
                </p>
            </div>

            <div class="bg-stone-50 border border-teks/10 rounded-xl p-4 text-left text-xs text-teks/80 space-y-2 leading-relaxed">
                <p>
                    <strong>Langkah Selanjutnya:</strong>
                </p>
                <ol class="list-decimal list-inside space-y-1 text-teks/70">
                    <li>Tim Admin Kios Mandiri YPKABK akan memeriksa file desain dan kuantitas pesanan Anda.</li>
                    <li>Setelah disetujui, instruksi pembayaran QRIS / Bank Transfer akan aktif di halaman nota pesanan Anda.</li>
                    <li>Kami juga akan mengirimkan pemberitahuan persetujuan melalui nomor WhatsApp Anda.</li>
                </ol>
            </div>

            <div class="space-y-2 pt-2">
                <a id="pod-success-detail-btn" href="#"
                   class="w-full min-h-[44px] py-2.5 px-4 rounded-full bg-primary hover:bg-primary/90 text-white font-bold text-xs sm:text-sm flex items-center justify-center shadow-sm transition-all active:scale-[0.98]">
                    <span>Buka Halaman Nota &amp; Status Pesanan</span>
                </a>

                <a id="pod-success-wa-receipt-btn" href="#" target="_blank"
                   class="w-full min-h-[44px] py-2.5 px-4 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-sm transition-all active:scale-[0.98]">
                    <span>Simpan Rincian ke WhatsApp Saya</span>
                </a>

                <button type="button" onclick="closeOnDemandModal()"
                        class="w-full py-2 text-center text-xs font-semibold text-teks/60 hover:text-teks cursor-pointer">
                    Selesai &amp; Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    window.openOnDemandModal = function(productName = null, price = null, productId = null) {
        const modal = document.getElementById('on-demand-modal');
        const form = document.getElementById('on-demand-form');
        const step1 = document.getElementById('pod-step-1');
        const step2 = document.getElementById('pod-step-2');
        const titleEl = document.getElementById('pod-modal-product-title');
        const unitPriceEl = document.getElementById('pod-modal-unit-price');
        const productIdInput = document.getElementById('pod-product-id');
        const rawPriceInput = document.getElementById('pod-raw-unit-price');
        const presetGroup = document.getElementById('pod-product-selector-group');
        const previewWrapper = document.getElementById('pod-design-preview-wrapper');

        if (!modal) return;

        form.reset();
        step1.classList.remove('hidden');
        step2.classList.add('hidden');
        if (previewWrapper) previewWrapper.classList.add('hidden');

        if (productId) {
            productIdInput.value = productId;
            titleEl.innerText = productName || 'Produk Karya ABK';
            const numPrice = typeof price === 'number' ? price : parseInt(String(price).replace(/\D/g, ''), 10) || 45000;
            rawPriceInput.value = numPrice;
            unitPriceEl.innerText = 'Rp ' + numPrice.toLocaleString('id-ID');
            if (presetGroup) presetGroup.classList.add('hidden');
        } else {
            productIdInput.value = '';
            if (presetGroup) presetGroup.classList.remove('hidden');
            handlePodPresetChange();
        }

        updatePodSubtotal();
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.closeOnDemandModal = function() {
        const modal = document.getElementById('on-demand-modal');
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    };

    window.handlePodPresetChange = function() {
        const select = document.getElementById('pod-product-preset-select');
        const titleEl = document.getElementById('pod-modal-product-title');
        const unitPriceEl = document.getElementById('pod-modal-unit-price');
        const rawPriceInput = document.getElementById('pod-raw-unit-price');

        if (!select) return;

        const selectedOption = select.options[select.selectedIndex];
        const price = parseInt(selectedOption.value, 10) || 45000;
        const name = selectedOption.getAttribute('data-name') || selectedOption.text;

        if (titleEl) titleEl.innerText = name;
        if (unitPriceEl) unitPriceEl.innerText = 'Rp ' + price.toLocaleString('id-ID');
        if (rawPriceInput) rawPriceInput.value = price;

        updatePodSubtotal();
    };

    window.updatePodSubtotal = function() {
        const qtyInput = document.getElementById('pod-order-quantity');
        const rawPriceInput = document.getElementById('pod-raw-unit-price');
        const subtotalEl = document.getElementById('pod-modal-subtotal-display');

        const qty = Math.max(1, parseInt(qtyInput ? qtyInput.value : '1', 10) || 1);
        const unitPrice = parseFloat(rawPriceInput ? rawPriceInput.value : '45000') || 45000;
        const total = qty * unitPrice;

        if (subtotalEl) {
            subtotalEl.innerText = 'Rp ' + total.toLocaleString('id-ID');
        }
    };

    window.previewPodDesign = function(event) {
        const file = event.target.files ? event.target.files[0] : null;
        const wrapper = document.getElementById('pod-design-preview-wrapper');
        const img = document.getElementById('pod-design-preview-img');
        const filename = document.getElementById('pod-design-filename');

        if (!file || !wrapper) return;

        if (filename) filename.innerText = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        wrapper.classList.remove('hidden');

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (img) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                }
            };
            reader.readAsDataURL(file);
        } else {
            if (img) img.classList.add('hidden');
        }
    };

    window.handleOnDemandSubmit = async function(event) {
        event.preventDefault();
        const form = event.target;
        const btn = document.getElementById('pod-submit-btn');
        const originalText = btn ? btn.innerHTML : '';

        const formData = new FormData(form);

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span>Mengirimkan Pesanan...</span>';
        }

        try {
            const response = await fetch('{{ route('purchases.on-demand.store') }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const result = await response.json();

            if (!response.ok) {
                const errorMsg = result.message || (result.errors ? Object.values(result.errors).flat().join('<br>') : 'Terjadi kesalahan validasi.');
                if (window.showAppModal) {
                    window.showAppModal({
                        title: 'Periksa Data Pesanan',
                        message: errorMsg,
                    });
                } else {
                    alert(errorMsg);
                }
                return;
            }

            // Success step
            const step1 = document.getElementById('pod-step-1');
            const step2 = document.getElementById('pod-step-2');
            const orderCodeEl = document.getElementById('pod-success-order-code');
            const detailBtn = document.getElementById('pod-success-detail-btn');
            const waReceiptBtn = document.getElementById('pod-success-wa-receipt-btn');

            if (orderCodeEl) orderCodeEl.innerText = result.data.order_code;
            if (detailBtn && result.order_url) detailBtn.href = result.order_url;
            if (waReceiptBtn && result.whatsapp_receipt_url) waReceiptBtn.href = result.whatsapp_receipt_url;

            if (step1) step1.classList.add('hidden');
            if (step2) step2.classList.remove('hidden');

            if (window.showAppToast) {
                window.showAppToast('Pesanan Print on Demand berhasil diajukan!');
            }
        } catch (err) {
            if (window.showAppToast) {
                window.showAppToast('Terjadi kendala jaringan saat mengajukan pesanan.');
            }
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        const podModal = document.getElementById('on-demand-modal');
        if (podModal) {
            podModal.addEventListener('click', (e) => {
                if (e.target === podModal) {
                    closeOnDemandModal();
                }
            });
        }
    });
</script>
