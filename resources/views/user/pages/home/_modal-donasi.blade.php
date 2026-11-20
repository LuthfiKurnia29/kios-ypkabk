{{-- ========================================================
     MODAL: FORM DONASI BARANG - FR-04 / NFR-02
     Tanpa kewajiban NIK / pendaftaran akun (Perlindungan Privasi).
     ======================================================== --}}
<div id="donation-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-2xl relative max-h-[90vh] overflow-y-auto border border-teks/10">
        <button type="button"
                onclick="closeDonationModal()"
                class="absolute top-5 right-5 text-teks/50 hover:text-teks p-2 rounded-xl transition-colors cursor-pointer"
                aria-label="Tutup modal">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div class="space-y-1">
            <span class="inline-block text-[11px] font-bold text-primary uppercase tracking-wider bg-primary/10 border border-primary/20 px-2.5 py-1 rounded-lg">
                Form Donasi Preloved
            </span>
            <h3 class="text-xl font-bold text-teks">Donasikan Barang untuk Kios Mandiri</h3>
            <p class="text-xs text-teks/65 leading-relaxed">Tim kami akan menghubungi Anda melalui WhatsApp untuk konfirmasi jadwal pickup / pengiriman pos.</p>
        </div>

        <form id="donation-form" onsubmit="handleDonationSubmit(event)" method="POST" action="{{ route('donations.store') }}" enctype="multipart/form-data" class="space-y-4 text-sm font-medium text-teks">
            @csrf
            <div>
                <label class="block mb-1 text-teks font-semibold">Nama Lengkap Donatur *</label>
                <input type="text" name="donor_name" required placeholder="Contoh: Siti Rahmawati"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block mb-1 text-teks font-semibold">No. WhatsApp *</label>
                    <input type="tel" name="donor_phone" required placeholder="0812xxxxxxx"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm">
                </div>
                <div>
                    <label class="block mb-1 text-teks font-semibold">Kota Asal *</label>
                    <input type="text" name="donor_city" required placeholder="Sidoarjo / Mojokerto / dst."
                           class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block mb-1 text-teks font-semibold">Kategori Barang Donasi *</label>
                    <select name="category_id" class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary bg-white text-sm">
                        @if(isset($donationCategories) && $donationCategories->count() > 0)
                            @foreach($donationCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        @else
                            <option value="">Pakaian Anak / Dewasa Layak Pakai</option>
                            <option value="">Buku Bacaan / Ensiklopedia</option>
                            <option value="">Mainan Edukatif / Puzzle</option>
                            <option value="">Bahan Prakarya (Kain / Benang / Kanvas)</option>
                            <option value="">Peralatan Terapi / Lainnya</option>
                        @endif
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-teks font-semibold">Perkiraan Jumlah Barang *</label>
                    <input type="number" name="item_count" min="1" max="1000" value="1" required placeholder="Contoh: 5"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm">
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-teks font-semibold">Foto Barang Donasi (Opsional, Maks. 5 Foto)</label>
                    <span id="donation-photos-count" class="text-[11px] text-teks/60">0/5 foto</span>
                </div>
                <input type="file" name="item_photos[]" id="donation_item_photos" multiple accept="image/jpeg,image/png,image/webp"
                       onchange="previewDonationPhotos(event)"
                       class="w-full text-xs text-teks/80 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary/90 cursor-pointer border border-teks/20 rounded-xl p-1.5 bg-white">
                <p class="text-[11px] text-teks/60 mt-1">Dapat memilih hingga 5 foto (JPG, PNG, atau WebP, maks. 5MB per foto).</p>

                {{-- Container Preview Thumbnail --}}
                <div id="donation-photos-preview" class="hidden mt-3 grid grid-cols-5 gap-2"></div>
            </div>
            <div>
                <label class="block mb-1 text-teks font-semibold">Deskripsi Singkat / Catatan</label>
                <textarea name="description" rows="2"
                          placeholder="Contoh: 2 kardus pakaian anak usia 3-6 tahun, kondisi bersih dan layak."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-teks/20 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm"></textarea>
            </div>
            <button type="submit" id="donation-submit-btn"
                    class="w-full min-h-[48px] rounded-full bg-primary hover:bg-primary/90 text-white font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-[0.98]">
                <span>Kirim Formulir Donasi</span>
            </button>
        </form>
    </div>
</div>
