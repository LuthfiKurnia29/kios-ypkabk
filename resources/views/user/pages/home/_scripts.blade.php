{{-- ========================================================
     INLINE SCRIPTS: Order Modal, AJAX Form Submissions, QRIS & WhatsApp
     ======================================================== --}}
<script>
    // ========================================================
    // ORDER MODAL & QRIS DINAMIS / TRANSFER BANK LOGIC
    // ========================================================
    const staticBankAccounts = {
        'BCA': {
            name: 'Bank BCA',
            short_name: 'BCA',
            number: '018-384-9900',
            raw: '0183849900',
            holder: 'Yayasan Peduli Kasih ABK'
        },
        'BRI': {
            name: 'Bank BRI',
            short_name: 'BRI',
            number: '009-601-002-345-502',
            raw: '009601002345502',
            holder: 'Yayasan Peduli Kasih ABK'
        },
        'Mandiri': {
            name: 'Bank Mandiri',
            short_name: 'Mandiri',
            number: '142-00-1988234-1',
            raw: '1420019882341',
            holder: 'Yayasan Peduli Kasih ABK'
        },
        'BNI': {
            name: 'Bank BNI',
            short_name: 'BNI',
            number: '083-912-4567',
            raw: '0839124567',
            holder: 'Yayasan Peduli Kasih ABK'
        }
    };

    let currentOrder = {
        productId: null,
        title: '',
        unitPrice: 0,
        quantity: 1,
        totalPrice: 0,
        orderCode: '',
        customerName: '',
        customerPhone: '',
        paymentMethod: 'qris',
        bankName: 'BCA',
        orderUrl: '',
        whatsappReceiptUrl: '',
        uploadProofUrl: '',
        qrisPayload: ''
    };

    function selectPaymentMethod(method) {
        const radio = document.querySelector(`input[name="payment_method"][value="${method}"]`);
        if (radio) {
            radio.checked = true;
        }
        togglePaymentMethodChoice(method);
    }

    function togglePaymentMethodChoice(method) {
        currentOrder.paymentMethod = method;

        const cardQris = document.getElementById('payment-card-qris');
        const cardBank = document.getElementById('payment-card-bank');
        const bankContainer = document.getElementById('bank-choice-container');
        const btnText = document.getElementById('order-submit-btn-text');

        if (method === 'bank_transfer') {
            if (cardQris) {
                cardQris.className = 'relative flex items-start gap-3 p-3.5 rounded-xl border-2 border-teks/15 hover:border-primary/50 bg-white cursor-pointer transition-all';
            }
            if (cardBank) {
                cardBank.className = 'relative flex items-start gap-3 p-3.5 rounded-xl border-2 border-primary bg-primary/5 cursor-pointer transition-all';
            }
            if (bankContainer) {
                bankContainer.classList.remove('hidden');
            }
            if (btnText) {
                btnText.innerText = `Lanjut ke Transfer Bank (${currentOrder.bankName || 'BCA'})`;
            }
        } else {
            if (cardQris) {
                cardQris.className = 'relative flex items-start gap-3 p-3.5 rounded-xl border-2 border-primary bg-primary/5 cursor-pointer transition-all';
            }
            if (cardBank) {
                cardBank.className = 'relative flex items-start gap-3 p-3.5 rounded-xl border-2 border-teks/15 hover:border-primary/50 bg-white cursor-pointer transition-all';
            }
            if (bankContainer) {
                bankContainer.classList.add('hidden');
            }
            if (btnText) {
                btnText.innerText = 'Lanjut ke Pembayaran QRIS';
            }
        }
    }

    function selectBankAccount(bankKey) {
        currentOrder.bankName = bankKey;
        const btnText = document.getElementById('order-submit-btn-text');
        if (btnText && currentOrder.paymentMethod === 'bank_transfer') {
            btnText.innerText = `Lanjut ke Transfer Bank (${bankKey})`;
        }
    }

    function copyBankAccount() {
        const bank = staticBankAccounts[currentOrder.bankName] || staticBankAccounts['BCA'];
        navigator.clipboard.writeText(bank.raw).then(() => {
            window.showAppToast(`Nomor rekening ${bank.name} (${bank.number}) berhasil disalin!`);
        }).catch(() => {
            window.showAppToast(`No Rekening: ${bank.number} a.n. ${bank.holder}`);
        });
    }

    function sendOrderReceiptToWhatsapp() {
        if (currentOrder.whatsappReceiptUrl) {
            window.open(currentOrder.whatsappReceiptUrl, '_blank');
            return;
        }

        const phone = currentOrder.customerPhone ? currentOrder.customerPhone.replace(/[^0-9]/g, '') : '';
        const intlPhone = phone.startsWith('0') ? '62' + phone.slice(1) : phone;
        const targetUrl = currentOrder.orderUrl || window.location.origin + '/purchases/' + currentOrder.orderCode;
        const text = encodeURIComponent(`Halo, berikut adalah nota pesanan Anda di Kios Mandiri YPKABK:\nKode: ${currentOrder.orderCode}\nTotal: Rp ${currentOrder.totalPrice.toLocaleString('id-ID')}\nTautan: ${targetUrl}`);
        window.open(`https://wa.me/${intlPhone}?text=${text}`, '_blank');
    }

    function formatRupiah(num) {
        return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
    }

    function openOrderModal(title, price, productId) {
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
        currentOrder.bankName = 'BCA';

        // Reset step view
        const step1 = document.getElementById('order-step-1');
        const step2 = document.getElementById('order-step-2');
        const step3 = document.getElementById('order-step-3');

        if (step1) step1.classList.remove('hidden');
        if (step2) step2.classList.add('hidden');
        if (step3) step3.classList.add('hidden');

        // Reset method choice to QRIS
        selectPaymentMethod('qris');

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
        const originalText = btn ? btn.innerHTML : 'Lanjut ke Pembayaran';

        const productId = document.getElementById('modal-product-id')?.value;
        const quantity = currentOrder.quantity;
        const customerName = form.elements['customer_name']?.value?.trim();
        const customerPhone = form.elements['customer_phone']?.value?.trim();
        const city = form.elements['city']?.value?.trim();
        const district = form.elements['district']?.value?.trim();
        const village = form.elements['village']?.value?.trim();
        const streetAddress = form.elements['street_address']?.value?.trim();
        const address = form.elements['address']?.value?.trim();
        const paymentMethod = form.elements['payment_method']?.value || 'qris';
        const bankName = form.elements['bank_name']?.value || 'BCA';
        const notes = form.elements['notes']?.value?.trim();

        currentOrder.customerName = customerName;
        currentOrder.customerPhone = customerPhone;
        currentOrder.paymentMethod = paymentMethod;
        currentOrder.bankName = bankName;

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Menyiapkan Pembayaran...</span>';
        }

        try {
            const payload = {
                customer_name: customerName,
                customer_phone: customerPhone,
                city: city,
                district: district,
                village: village,
                street_address: streetAddress,
                address: address,
                payment_method: paymentMethod,
                bank_name: paymentMethod === 'bank_transfer' ? bankName : null,
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
                currentOrder.paymentMethod = result.data.payment_method || paymentMethod;
                currentOrder.bankName = result.data.bank_name || bankName;
                currentOrder.orderUrl = result.order_url || `/purchases/${currentOrder.orderCode}`;
                currentOrder.whatsappReceiptUrl = result.whatsapp_receipt_url || '';
                currentOrder.totalPrice = result.qris ? result.qris.total_price : currentOrder.totalPrice;
                currentOrder.uploadProofUrl = result.qris ? result.qris.upload_proof_url : `/purchases/${currentOrder.orderCode}/payment-proof`;
                currentOrder.qrisPayload = result.qris ? result.qris.payload : '';

                // Update Step 2 DOM
                const qrisCodeEl = document.getElementById('modal-qris-order-code');
                const qrisAmountEl = document.getElementById('modal-qris-amount-display');
                const qrisImg = document.getElementById('modal-qris-image');
                const badgeEl = document.getElementById('modal-pay-badge');

                if (qrisCodeEl) qrisCodeEl.innerText = currentOrder.orderCode;
                if (qrisAmountEl) qrisAmountEl.innerText = formatRupiah(currentOrder.totalPrice);

                // Toggle QRIS vs Bank Container
                const viewQris = document.getElementById('modal-view-qris');
                const viewBank = document.getElementById('modal-view-bank');

                if (currentOrder.paymentMethod === 'bank_transfer') {
                    if (viewQris) viewQris.classList.add('hidden');
                    if (viewBank) viewBank.classList.remove('hidden');

                    const selectedBank = staticBankAccounts[currentOrder.bankName] || staticBankAccounts['BCA'];
                    if (badgeEl) badgeEl.innerText = `Transfer ${selectedBank.name}`;

                    const bankBadgeName = document.getElementById('modal-bank-badge-name');
                    const bankAccDisplay = document.getElementById('modal-bank-acc-display');
                    if (bankBadgeName) bankBadgeName.innerText = selectedBank.name;
                    if (bankAccDisplay) bankAccDisplay.innerText = selectedBank.number;
                } else {
                    if (viewQris) viewQris.classList.remove('hidden');
                    if (viewBank) viewBank.classList.add('hidden');
                    if (badgeEl) badgeEl.innerText = 'Pembayaran QRIS Nasional';

                    // Render QR Code via QRServer API generator
                    if (qrisImg && currentOrder.qrisPayload) {
                        qrisImg.src = `https://api.qrserver.com/v1/create-qr-code/?size=260x260&margin=10&data=${encodeURIComponent(currentOrder.qrisPayload)}`;
                    }
                }

                // Update Links to detailed order page
                const detailLink = document.getElementById('modal-open-detail-link');
                const successLink = document.getElementById('modal-success-page-link');
                if (detailLink) detailLink.href = currentOrder.orderUrl;
                if (successLink) successLink.href = currentOrder.orderUrl;

                // Switch to Step 2
                document.getElementById('order-step-1')?.classList.add('hidden');
                document.getElementById('order-step-2')?.classList.remove('hidden');
            } else {
                window.showAppModal({
                    title: 'Pesanan Belum Lengkap',
                    message: result.message || 'Mohon periksa kembali formulir pesanan Anda.',
                    type: 'error',
                    closeText: 'Perbaiki'
                });
            }
        } catch (err) {
            window.showAppToast('Terjadi kendala jaringan saat membuat pesanan. Silakan coba kembali.');
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
            window.showAppToast(`Nominal Rp ${Number(amount).toLocaleString('id-ID')} berhasil disalin!`);
        }).catch(() => {
            window.showAppToast(`Nominal: Rp ${Number(amount).toLocaleString('id-ID')}`);
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
            window.showAppToast('Silakan pilih foto bukti transfer struk terlebih dahulu.');
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
                const successOrderCodeEl = document.getElementById('modal-success-order-code');
                if (successOrderCodeEl) {
                    successOrderCodeEl.innerText = currentOrder.orderCode;
                }

                document.getElementById('order-step-2')?.classList.add('hidden');
                document.getElementById('order-step-3')?.classList.remove('hidden');
            } else {
                window.showAppModal({
                    title: 'Gagal Mengunggah Bukti',
                    message: result.message || 'Pastikan ukuran gambar di bawah 5MB dan berformat JPG, PNG, atau WebP.',
                    type: 'error',
                    closeText: 'Coba Lagi'
                });
            }
        } catch (err) {
            window.showAppToast('Terjadi gangguan saat mengunggah. Anda dapat mengirimkan bukti via WhatsApp.');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }
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
