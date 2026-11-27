@extends('admin.shared.Layout')

@section('title', 'Metode Pembayaran')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Metode Pembayaran</h2>
            <p class="text-sm text-gray-500 mt-0.5">Kelola status aktif, konfigurasi QRIS Dinamis, dan daftar rekening Transfer Bank resmi.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ ($qrisEnabled || $bankEnabled) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                <span class="w-2 h-2 rounded-full {{ ($qrisEnabled || $bankEnabled) ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                {{ ($qrisEnabled && $bankEnabled) ? '2 Metode Aktif' : (($qrisEnabled || $bankEnabled) ? '1 Metode Aktif' : 'Semua Nonaktif') }}
            </span>
        </div>
    </div>

    {{-- Session Alerts --}}
    @if(session('success_qris'))
        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success_qris') }}</span>
            </div>
        </div>
    @endif

    @if(session('success_bank'))
        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success_bank') }}</span>
            </div>
        </div>
    @endif

    @if(session('success_bank_account'))
        <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success_bank_account') }}</span>
            </div>
        </div>
    @endif

    @if(session('error_bank_account'))
        <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('error_bank_account') }}</span>
        </div>
    @endif

    {{-- ========================================================
         KARTU 1: PENGATURAN QRIS DINAMIS
         ======================================================== --}}
    <form method="POST" action="{{ route('admin.payment-settings.update-qris') }}" class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">QRIS Dinamis (EMVCo Nasional)</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Mendukung pembayaran instan melalui aplikasi Mobile Banking dan E-Wallet.</p>
                </div>
            </div>

            {{-- Sakelar Status QRIS --}}
            <label class="relative inline-flex items-center cursor-pointer select-none">
                <input type="checkbox" name="payment_qris_enabled" value="1" class="sr-only peer" {{ $qrisEnabled ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                <span class="ml-3 text-xs font-bold {{ $qrisEnabled ? 'text-primary' : 'text-gray-400' }}">
                    {{ $qrisEnabled ? 'Aktif' : 'Nonaktif' }}
                </span>
            </label>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Form Fields --}}
            <div class="lg:col-span-8 space-y-4">
                <div>
                    <label for="payment_qris_merchant_name" class="block text-xs font-semibold text-gray-700 mb-1">
                        Nama Merchant QRIS <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="payment_qris_merchant_name" name="payment_qris_merchant_name"
                           value="{{ old('payment_qris_merchant_name', $qrisMerchantName) }}"
                           placeholder="Contoh: KIOS MANDIRI YPKABK"
                           class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                    <p class="text-[11px] text-gray-400 mt-1">Nama merchant yang tertera pada nota dan konfirmasi pembeli.</p>
                    @error('payment_qris_merchant_name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_qris_base_string" class="block text-xs font-semibold text-gray-700 mb-1">
                        String Dasar QRIS EMVCo (Static Payload)
                    </label>
                    <p class="text-[11px] text-gray-500 mb-1.5">
                        Sistem secara otomatis menginjeksi nominal pesanan dinamis dan menghitung ulang checksum CRC16-CCITT pada setiap transaksi.
                    </p>
                    <textarea id="payment_qris_base_string" name="payment_qris_base_string" rows="3"
                              placeholder="00020101021126600016ID.CO.QRIS.WWW..."
                              class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs font-mono text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none">{{ old('payment_qris_base_string', $qrisBaseString) }}</textarea>
                    @error('payment_qris_base_string')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Live Preview Box --}}
            <div class="lg:col-span-4 bg-gray-50 p-4 rounded-xl border border-gray-200 flex flex-col items-center justify-center text-center space-y-2">
                <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">Pratinjau Kode QRIS</span>
                <div class="w-36 h-36 bg-white p-2 rounded-lg border border-gray-200 flex items-center justify-center shadow-xs">
                    <img src="{{ $previewQrUrl }}" alt="Preview QRIS" class="w-full h-full object-contain">
                </div>
                <div class="text-[11px] text-gray-500">
                    <span class="font-semibold text-gray-700 block">{{ $qrisMerchantName }}</span>
                    <span>Simulasi nominal otomatis Rp 10.000</span>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <button type="submit"
                    class="px-5 py-2 rounded-md bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition shadow-sm min-h-[40px] cursor-pointer">
                Simpan Pengaturan QRIS
            </button>
        </div>
    </form>

    {{-- ========================================================
         KARTU 2: PENGATURAN TRANSFER BANK
         ======================================================== --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6 space-y-6">

        <div class="border-b border-gray-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Transfer Bank Manual</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Kelola rekening bank tujuan transfer resmi untuk menerima pembayaran pesanan.</p>
                </div>
            </div>

            {{-- Sakelar Status Global Transfer Bank --}}
            <form method="POST" action="{{ route('admin.payment-settings.update-bank') }}" class="flex items-center gap-3">
                @csrf
                @method('PUT')
                <label class="relative inline-flex items-center cursor-pointer select-none">
                    <input type="checkbox" name="payment_bank_enabled" value="1" onchange="this.form.submit()" class="sr-only peer" {{ $bankEnabled ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    <span class="ml-3 text-xs font-bold {{ $bankEnabled ? 'text-primary' : 'text-gray-400' }}">
                        {{ $bankEnabled ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </label>
            </form>
        </div>

        {{-- Action Bar: Tambah Rekening --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h4 class="text-sm font-bold text-gray-900">Daftar Rekening Bank</h4>
                <p class="text-xs text-gray-500">Rekening yang aktif akan ditampilkan pada pilihan checkout pembeli.</p>
            </div>
            <button type="button" onclick="openAddBankModal()"
                    class="inline-flex items-center gap-2 px-3.5 py-2 rounded-md bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary/90 transition shadow-sm cursor-pointer self-start sm:self-auto min-h-[38px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Rekening Baru</span>
            </button>
        </div>

        {{-- Tabel Rekening Bank --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-[11px] font-bold uppercase text-gray-500 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3">Bank</th>
                        <th class="px-4 py-3">Nomor Rekening</th>
                        <th class="px-4 py-3">Atas Nama</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bankAccounts as $bank)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $bank['color'] ?? 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                                        {{ $bank['code'] }}
                                    </span>
                                    <span class="font-bold text-gray-900 text-xs sm:text-sm">{{ $bank['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 font-mono font-semibold text-gray-800 text-xs sm:text-sm">
                                {{ $bank['account_number'] }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $bank['account_holder'] }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form method="POST" action="{{ route('admin.payment-settings.banks.toggle', $bank['code']) }}" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Klik untuk mengubah status"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold cursor-pointer transition-colors {{ !empty($bank['is_active']) ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ !empty($bank['is_active']) ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                        {{ !empty($bank['is_active']) ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    {{-- Edit Button --}}
                                    <button type="button"
                                            onclick='openEditBankModal(@json($bank))'
                                            class="px-2.5 py-1 rounded border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold transition cursor-pointer">
                                        Edit
                                    </button>

                                    {{-- Delete Button --}}
                                    <form method="POST" action="{{ route('admin.payment-settings.banks.destroy', $bank['code']) }}"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekening bank {{ $bank['name'] }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-2.5 py-1 rounded border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 text-xs font-semibold transition cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                Belum ada rekening bank yang dikonfigurasi. Klik tombol "Tambah Rekening Baru" untuk menambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ========================================================
     MODAL TAMBAH REKENING BANK
     ======================================================== --}}
<div id="modal-add-bank" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-base font-bold text-gray-900">Tambah Rekening Bank</h3>
            <button type="button" onclick="closeAddBankModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.payment-settings.banks.store') }}" class="space-y-3.5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Bank (Singkat) *</label>
                <input type="text" name="code" required placeholder="Contoh: BSI, BCA, JATIM"
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs uppercase focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                <p class="text-[10px] text-gray-400 mt-0.5">Hanya huruf dan angka tanpa spasi (unik).</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap Bank *</label>
                <input type="text" name="name" required placeholder="Contoh: Bank Syariah Indonesia (BSI)"
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Rekening *</label>
                <input type="text" name="account_number" required placeholder="Contoh: 712-345-6789"
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Atas Nama Rekening *</label>
                <input type="text" name="account_holder" required value="Yayasan Peduli Kasih ABK"
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </div>

            <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeAddBankModal()"
                        class="px-4 py-2 rounded-md border border-gray-200 text-gray-600 text-xs font-semibold hover:bg-gray-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-md bg-primary text-white text-xs font-semibold hover:bg-primary/90 cursor-pointer">
                    Tambah Rekening
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================
     MODAL EDIT REKENING BANK
     ======================================================== --}}
<div id="modal-edit-bank" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 class="text-base font-bold text-gray-900">Edit Rekening Bank</h3>
            <button type="button" onclick="closeEditBankModal()" class="text-gray-400 hover:text-gray-600 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="form-edit-bank" method="POST" action="" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Bank</label>
                <input type="text" id="edit-bank-code" disabled
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs uppercase bg-gray-50 text-gray-500 font-bold">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap Bank *</label>
                <input type="text" id="edit-bank-name" name="name" required
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Rekening *</label>
                <input type="text" id="edit-bank-account-number" name="account_number" required
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Atas Nama Rekening *</label>
                <input type="text" id="edit-bank-account-holder" name="account_holder" required
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
            </div>

            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="edit-bank-is-active" name="is_active" value="1" class="w-4 h-4 text-primary accent-primary rounded cursor-pointer">
                <label for="edit-bank-is-active" class="text-xs text-gray-700 font-semibold cursor-pointer">Status Rekening Aktif</label>
            </div>

            <div class="pt-3 border-t border-gray-100 flex items-center justify-end gap-2">
                <button type="button" onclick="closeEditBankModal()"
                        class="px-4 py-2 rounded-md border border-gray-200 text-gray-600 text-xs font-semibold hover:bg-gray-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-md bg-primary text-white text-xs font-semibold hover:bg-primary/90 cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddBankModal() {
        document.getElementById('modal-add-bank').classList.remove('hidden');
    }

    function closeAddBankModal() {
        document.getElementById('modal-add-bank').classList.add('hidden');
    }

    function openEditBankModal(bank) {
        document.getElementById('edit-bank-code').value = bank.code;
        document.getElementById('edit-bank-name').value = bank.name;
        document.getElementById('edit-bank-account-number').value = bank.account_number;
        document.getElementById('edit-bank-account-holder').value = bank.account_holder;
        document.getElementById('edit-bank-is-active').checked = !!bank.is_active;

        const form = document.getElementById('form-edit-bank');
        form.action = `/admin/payment-settings/banks/${bank.code}`;

        document.getElementById('modal-edit-bank').classList.remove('hidden');
    }

    function closeEditBankModal() {
        document.getElementById('modal-edit-bank').classList.add('hidden');
    }
</script>
@endsection
