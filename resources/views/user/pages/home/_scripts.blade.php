{{-- ========================================================
     INLINE SCRIPTS: Modal & (Tab filter dihapus sesuai keputusan desain)
     ======================================================== --}}
<script>
    // Donation Modal
    function openDonationModal() {
        document.getElementById('donation-modal').classList.remove('hidden');
    }
    function closeDonationModal() {
        document.getElementById('donation-modal').classList.add('hidden');
    }
    function handleDonationSubmit(e) {
        e.preventDefault();
        alert('Terima kasih banyak! Tim Kios Mandiri YPKABK akan segera menghubungi nomor WhatsApp Anda untuk koordinasi donasi.');
        closeDonationModal();
    }

    // Order Modal
    function openOrderModal(title, price) {
        document.getElementById('modal-product-title').innerText = title;
        document.getElementById('modal-product-price').innerText = 'Rp ' + price;
        const waText = encodeURIComponent(`Halo Admin Kios Mandiri YPKABK, saya ingin memesan "${title}" seharga Rp ${price}. Apakah barang masih tersedia?`);
        document.getElementById('modal-wa-btn').href = `https://wa.me/6281234567890?text=${waText}`;
        document.getElementById('order-modal').classList.remove('hidden');
    }
    function closeOrderModal() {
        document.getElementById('order-modal').classList.add('hidden');
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
