{{-- ========================================================
     INLINE SCRIPTS: Modal, AJAX Form Submissions, QRIS & WhatsApp
     ======================================================== --}}
<script>
    // ========================================================
    // DONATION MODAL LOGIC
    // ========================================================
    function openDonationModal() {
        const modal = document.getElementById('donation-modal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeDonationModal() {
        const modal = document.getElementById('donation-modal');
        if (modal) modal.classList.add('hidden');
    }

    async function handleDonationSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('donation-submit-btn');
        const originalText = btn ? btn.innerHTML : 'Kirim Formulir Donasi';

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span>Mengirim data...</span>';
        }

        try {
            const formData = new FormData(form);
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await res.json();

            if (res.ok) {
                alert(data.message || 'Terima kasih banyak! Tim Kios Mandiri YPKABK akan segera menghubungi nomor WhatsApp Anda untuk koordinasi donasi.');
                form.reset();
                closeDonationModal();
            } else {
                alert(data.message || 'Mohon lengkapi formulir dengan benar.');
            }
        } catch (err) {
            alert('Terjadi kendala jaringan. Silakan hubungi kami langsung via WhatsApp atau coba sesaat lagi.');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    }

    // ========================================================
    // ORDER MODAL & QRIS DINAMIS LOGIC
    // ========================================================
    let currentOrder = {
        productId: null,
        title: '',
        unitPrice: 0,
        quantity: 1,
        totalPrice: 0,
        orderCode: '',
        customerName: '',
        customerPhone: '',
        uploadProofUrl: '',
        qrisPayload: ''
    };

    function formatRupiah(num) {
        return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
    }

    function openOrderModal(title, price, productId) {
        // Parse numerical price (handles '45.000' or 45000)
        let cleanPrice = 0;
        if (typeof price === 'string') {
            cleanPrice = parseFloat(price.replace(/[^0-9]/g, '')) || 0;
        } else if (typeof price === 'number') {
            cleanPrice = price;
        }

        currentOrder.productId = productId;
        currentOrder.title = title;
        currentOrder.unitPrice = cleanPrice;
        currentOrder.quantity = 1;
        currentOrder.totalPrice = cleanPrice;
        currentOrder.orderCode = '';
        currentOrder.uploadProofUrl = '';
        currentOrder.qrisPayload = '';

        // Reset step view
        const step1 = document.getElementById('order-step-1');
        const step2 = document.getElementById('order-step-2');
        const step3 = document.getElementById('order-step-3');

        if (step1) step1.classList.remove('hidden');
        if (step2) step2.classList.add('hidden');
        if (step3) step3.classList.add('hidden');

        // Reset proof preview & inputs
        const proofInput = document.getElementById('modal-proof-file');
        if (proofInput) proofInput.value = '';
        const previewWrapper = document.getElementById('modal-proof-preview-wrapper');
        if (previewWrapper) previewWrapper.classList.add('hidden');

        // Populate step 1
        const titleEl = document.getElementById('modal-product-title');
        const priceEl = document.getElementById('modal-product-price');
        const idEl = document.getElementById('modal-product-id');
        const rawPriceEl = document.getElementById('modal-product-raw-price');
        const qtyEl = document.getElementById('modal-order-quantity');

        if (titleEl) titleEl.innerText = title;
        if (priceEl) priceEl.innerText = formatRupiah(cleanPrice);
        if (idEl) idEl.value = productId || '';
        if (rawPriceEl) rawPriceEl.value = cleanPrice;
        if (qtyEl) qtyEl.value = '1';

        updateModalSubtotal();

        const modal = document.getElementById('order-modal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeOrderModal() {
        const modal = document.getElementById('order-modal');
        if (modal) modal.classList.add('hidden');
    }

    function updateModalSubtotal() {
        const qtyInput = document.getElementById('modal-order-quantity');
        const qty = Math.max(1, parseInt(qtyInput ? qtyInput.value : '1', 10) || 1);
        currentOrder.quantity = qty;
        currentOrder.totalPrice = currentOrder.unitPrice * qty;

        const subtotalEl = document.getElementById('modal-calculated-total');
        if (subtotalEl) {
            subtotalEl.innerText = formatRupiah(currentOrder.totalPrice);
        }
    }

    async function handleOrderSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('order-submit-btn');
        const originalText = btn ? btn.innerHTML : 'Lanjut ke Pembayaran QRIS';

        const productId = document.getElementById('modal-product-id')?.value;
        const quantity = currentOrder.quantity;
        const customerName = form.elements['customer_name']?.value?.trim();
        const customerPhone = form.elements['customer_phone']?.value?.trim();
        const customerAddress = form.elements['customer_address']?.value?.trim();
        const notes = form.elements['notes']?.value?.trim();

        currentOrder.customerName = customerName;
        currentOrder.customerPhone = customerPhone;

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Menyiapkan QRIS...</span>';
        }

        try {
            const payload = {
                customer_name: customerName,
                customer_phone: customerPhone,
                customer_address: customerAddress,
                notes: notes,
                items: [
                    {
                        product_id: parseInt(productId, 10),
                        quantity: quantity,
                        price: currentOrder.unitPrice
                    }
                ]
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

            const result = await res.json();

            if (res.ok && result.data) {
                currentOrder.orderCode = result.data.order_code;
                currentOrder.totalPrice = result.qris ? result.qris.total_price : currentOrder.totalPrice;
                currentOrder.uploadProofUrl = result.qris ? result.qris.upload_proof_url : `/purchases/${currentOrder.orderCode}/payment-proof`;
                currentOrder.qrisPayload = result.qris ? result.qris.payload : '';

                // Update Step 2 DOM
                const qrisCodeEl = document.getElementById('modal-qris-order-code');
                const qrisAmountEl = document.getElementById('modal-qris-amount-display');
                const qrisImg = document.getElementById('modal-qris-image');

                if (qrisCodeEl) qrisCodeEl.innerText = currentOrder.orderCode;
                if (qrisAmountEl) qrisAmountEl.innerText = formatRupiah(currentOrder.totalPrice);

                // Render QR Code via QRServer API generator
                if (qrisImg && currentOrder.qrisPayload) {
                    qrisImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=10&data=${encodeURIComponent(currentOrder.qrisPayload)}`;
                }

                // Switch to Step 2
                document.getElementById('order-step-1')?.classList.add('hidden');
                document.getElementById('order-step-2')?.classList.remove('hidden');
            } else {
                alert(result.message || 'Mohon periksa data pesanan Anda.');
            }
        } catch (err) {
            alert('Terjadi kendala jaringan saat membuat pesanan. Silakan coba kembali.');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    }

    function copyNominalAmount() {
        const amount = currentOrder.totalPrice.toString();
        navigator.clipboard.writeText(amount).then(() => {
            alert(`Nominal Rp ${Number(amount).toLocaleString('id-ID')} berhasil disalin ke papan klip.`);
        }).catch(() => {
            // Fallback
            alert(`Nominal: Rp ${Number(amount).toLocaleString('id-ID')}`);
        });
    }

    function downloadQrisImage() {
        const qrisImg = document.getElementById('modal-qris-image');
        if (!qrisImg || !qrisImg.src) return;

        const a = document.createElement('a');
        a.href = qrisImg.src;
        a.download = `QRIS-${currentOrder.orderCode || 'YPKABK'}.png`;
        a.target = '_blank';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    function previewProofImage(e) {
        const file = e.target.files?.[0];
        const previewWrapper = document.getElementById('modal-proof-preview-wrapper');
        const previewImg = document.getElementById('modal-proof-preview-img');

        if (file && previewWrapper && previewImg) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target?.result;
                previewWrapper.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    async function handleProofSubmit(e) {
        e.preventDefault();
        const fileInput = document.getElementById('modal-proof-file');
        const file = fileInput?.files?.[0];

        if (!file) {
            alert('Silakan pilih foto bukti transfer struk terlebih dahulu.');
            return;
        }

        const btn = document.getElementById('modal-proof-submit-btn');
        const originalText = btn ? btn.innerHTML : 'Kirim Bukti Pembayaran';

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span>Mengunggah bukti transfer...</span>';
        }

        try {
            const formData = new FormData();
            formData.append('payment_proof', file);

            const csrfToken = document.querySelector('input[name="_token"]')?.value || '';

            const uploadUrl = currentOrder.uploadProofUrl || `/purchases/${currentOrder.orderCode}/payment-proof`;

            const res = await fetch(uploadUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const result = await res.json();

            if (res.ok) {
                // Switch to Step 3 (Success)
                const successOrderCodeEl = document.getElementById('modal-success-order-code');
                if (successOrderCodeEl) {
                    successOrderCodeEl.innerText = currentOrder.orderCode;
                }

                document.getElementById('order-step-2')?.classList.add('hidden');
                document.getElementById('order-step-3')?.classList.remove('hidden');
            } else {
                alert(result.message || 'Gagal mengunggah bukti pembayaran. Pastikan ukuran gambar di bawah 5MB.');
            }
        } catch (err) {
            alert('Terjadi gangguan jaringan saat mengunggah bukti. Silakan kirimkan bukti langsung via chat WhatsApp.');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
    }

    function openDirectWhatsApp() {
        const orderCode = currentOrder.orderCode || 'LANGSUNG';
        const title = currentOrder.title || 'Produk Kios Mandiri';
        const qty = currentOrder.quantity || 1;
        const total = formatRupiah(currentOrder.totalPrice);
        const name = currentOrder.customerName || '-';
        const phone = currentOrder.customerPhone || '-';

        const message = `Halo Admin Kios Mandiri YPKABK,%0A%0ASaya ingin konfirmasi pembayaran produk donasi:%0A- *Kode Pesanan:* ${orderCode}%0A- *Produk:* ${encodeURIComponent(title)} (${qty} pcs)%0A- *Total:* ${encodeURIComponent(total)}%0A- *Nama Pemesan:* ${encodeURIComponent(name)}%0A- *No. Kontak:* ${encodeURIComponent(phone)}%0A%0ASaya mengirimkan bukti transfer melalui chat WhatsApp ini. Mohon dibantu verifikasi pesanan saya ya, terima kasih!`;

        window.open(`https://wa.me/6281234567890?text=${message}`, '_blank');
    }

    function openSuccessWhatsApp() {
        const orderCode = currentOrder.orderCode || 'LANGSUNG';
        const title = currentOrder.title || 'Produk Kios Mandiri';
        const qty = currentOrder.quantity || 1;
        const total = formatRupiah(currentOrder.totalPrice);
        const name = currentOrder.customerName || '-';

        const message = `Halo Admin Kios Mandiri YPKABK,%0A%0ASaya telah melakukan pembayaran dan mengunggah bukti transfer di website untuk pesanan berikut:%0A- *Kode Pesanan:* ${orderCode}%0A- *Produk:* ${encodeURIComponent(title)} (${qty} pcs)%0A- *Total:* ${encodeURIComponent(total)}%0A- *Nama Pemesan:* ${encodeURIComponent(name)}%0A%0ABukti transfer sudah saya upload melalui website. Mohon dicek dan diproses ya, terima kasih banyak!`;

        window.open(`https://wa.me/6281234567890?text=${message}`, '_blank');
    }

    // Tutup modal saat klik backdrop
    document.addEventListener('DOMContentLoaded', () => {
        ['donation-modal', 'order-modal'].forEach(id => {
            const modal = document.getElementById(id);
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            }
        });
    });
</script>
