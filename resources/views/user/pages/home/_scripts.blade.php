{{-- ========================================================
     INLINE SCRIPTS: Modal, AJAX Form Submissions, Order & WhatsApp
     ======================================================== --}}
<script>
    // Donation Modal
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

    // Order Modal
    let currentOrderProduct = { title: '', price: '0', id: null };

    function openOrderModal(title, price, productId) {
        currentOrderProduct = { title, price, id: productId };

        const titleEl = document.getElementById('modal-product-title');
        const priceEl = document.getElementById('modal-product-price');
        const idEl = document.getElementById('modal-product-id');

        if (titleEl) titleEl.innerText = title;
        if (priceEl) priceEl.innerText = 'Rp ' + price;
        if (idEl) idEl.value = productId || '';

        const modal = document.getElementById('order-modal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeOrderModal() {
        const modal = document.getElementById('order-modal');
        if (modal) modal.classList.add('hidden');
    }

    async function handleOrderSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const btn = document.getElementById('order-submit-btn');
        const originalText = btn ? btn.innerHTML : 'Pesan & Lanjut ke WhatsApp';

        const productId = document.getElementById('modal-product-id')?.value;
        const quantity = parseInt(document.getElementById('modal-order-quantity')?.value || '1', 10);
        const customerName = form.elements['customer_name']?.value?.trim();
        const customerPhone = form.elements['customer_phone']?.value?.trim();
        const customerAddress = form.elements['customer_address']?.value?.trim();
        const notes = form.elements['notes']?.value?.trim();

        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span>Memproses pesanan...</span>';
        }

        try {
            // Submit to Backend API (Guest Checkout per NFR-02 & FR-06)
            const payload = {
                customer_name: customerName,
                customer_phone: customerPhone,
                customer_address: customerAddress,
                notes: notes,
                items: [
                    {
                        product_id: parseInt(productId, 10),
                        quantity: quantity
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

            let orderCode = 'LANGSUNG';
            if (res.ok && result.data && result.data.order_code) {
                orderCode = result.data.order_code;
            }

            // Redirect ke WhatsApp resmi Kios Mandiri
            const message = `Halo Admin Kios Mandiri YPKABK,%0A%0ASaya ingin memesan produk berikut:%0A- *Kode Pesanan:* ${orderCode}%0A- *Produk:* ${encodeURIComponent(currentOrderProduct.title)}%0A- *Jumlah:* ${quantity} pcs%0A- *Nama Pemesan:* ${encodeURIComponent(customerName)}%0A- *No. Kontak:* ${encodeURIComponent(customerPhone)}%0A- *Alamat:* ${encodeURIComponent(customerAddress || '-')}%0A%0AMohon informasi ketersediaan rekening donasi / QRIS untuk penyelesaian pesanan. Terima kasih!`;

            window.open(`https://wa.me/6281234567890?text=${message}`, '_blank');

            form.reset();
            closeOrderModal();
        } catch (err) {
            // Fallback jika ada offline atau error backend
            const message = `Halo Admin Kios Mandiri YPKABK,%0A%0ASaya ingin memesan:* ${encodeURIComponent(currentOrderProduct.title)}*%0A- *Nama:* ${encodeURIComponent(customerName)}%0A- *Kontak:* ${encodeURIComponent(customerPhone)}%0A%0AMohon informasi ketersediaan barang dan pembayaran donasi.`;
            window.open(`https://wa.me/6281234567890?text=${message}`, '_blank');
            closeOrderModal();
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
