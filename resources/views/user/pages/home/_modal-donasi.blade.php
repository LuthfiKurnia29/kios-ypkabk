{{-- ========================================================
     MODAL: FORM DONASI BARANG
     ======================================================== --}}
<div id="donation-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative">
        <button type="button"
                onclick="closeDonationModal()"
                class="absolute top-5 right-5 text-ink/50 hover:text-ink p-2 rounded-xl transition-colors"
                aria-label="Tutup modal">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="space-y-1">
            <span class="text-xs font-bold text-ink uppercase tracking-wider bg-sun px-2.5 py-1 rounded-lg">
                Form Donasi Preloved
            </span>
            <h3 class="text-xl font-bold text-ink">Donasikan Barang untuk Kios Mandiri</h3>
            <p class="text-xs text-ink/65">Tim kami akan menghubungi Anda untuk konfirmasi jadwal pickup / pengiriman pos.</p>
        </div>

        <form onsubmit="handleDonationSubmit(event)" class="space-y-4 text-sm font-medium text-ink">
            <div>
                <label class="block mb-1 text-ink font-semibold">Nama Lengkap Donatur *</label>
                <input type="text" required placeholder="Contoh: Siti Rahmawati"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block mb-1 text-ink font-semibold">No. WhatsApp *</label>
                    <input type="tel" required placeholder="0812xxxxxxx"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun">
                </div>
                <div>
                    <label class="block mb-1 text-ink font-semibold">Kota Asal *</label>
                    <input type="text" required placeholder="Sidoarjo / Mojokerto / dst."
                           class="w-full px-3.5 py-2.5 rounded-xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun">
                </div>
            </div>
            <div>
                <label class="block mb-1 text-ink font-semibold">Kategori Barang Donasi *</label>
                <select class="w-full px-3.5 py-2.5 rounded-xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun bg-white">
                    <option>Pakaian Anak / Dewasa Layak Pakai</option>
                    <option>Buku Bacaan / Ensiklopedia</option>
                    <option>Mainan Edukatif / Puzzle</option>
                    <option>Bahan Prakarya (Kain / Benang / Kanvas)</option>
                    <option>Peralatan Terapi / Lainnya</option>
                </select>
            </div>
            <div>
                <label class="block mb-1 text-ink font-semibold">Perkiraan Jumlah / Deskripsi Singkat</label>
                <textarea rows="2"
                          placeholder="Contoh: 2 kardus pakaian anak usia 3-6 tahun, kondisi bersih dan layak."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-ink/20 focus:outline-none focus:ring-2 focus:ring-sun"></textarea>
            </div>
            <button type="submit"
                    class="w-full min-h-[48px] rounded-full bg-sun hover:bg-sun/90 text-ink font-bold text-sm shadow-sm transition-all">
                Kirim Formulir Donasi
            </button>
        </form>
    </div>
</div>
