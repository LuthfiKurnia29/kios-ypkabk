@extends('admin.shared.Layout')

@section('title', 'Laporan Pengeluaran')

@section('content')
<div class="space-y-5">

    {{-- Alert Flash Message --}}
    @if(session('success'))
        <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <span class="font-medium">{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700" aria-label="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Tabs Laporan --}}
    <div class="flex items-center gap-2 border-b border-gray-200 pb-2" role="tablist" aria-label="Tab Laporan">
        <a href="{{ route('admin.reports.income', ['periode' => $financialData['selected_period'] ?? '']) }}"
           role="tab"
           aria-selected="false"
           class="px-4 py-2 rounded-md text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-primary/30">
            Pemasukan
        </a>
        <a href="{{ route('admin.reports.index', ['periode' => $financialData['selected_period'] ?? '']) }}"
           role="tab"
           aria-selected="true"
           class="px-4 py-2 rounded-md text-sm font-semibold bg-primary text-white transition focus:outline-none focus:ring-2 focus:ring-primary/30">
            Pengeluaran
        </a>
    </div>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Laporan Pengeluaran</h2>
            <p class="text-sm text-gray-500 mt-0.5">Kelola data pengeluaran operasional dan pantau arus kas bulanan.</p>
        </div>

        {{-- Pemilih Periode --}}
        <div class="flex items-center gap-2 self-start sm:self-auto">
            <label for="admin-period-select" class="text-xs font-semibold text-gray-500 shrink-0">Periode:</label>
            <select id="admin-period-select"
                    onchange="window.location.search = '?periode=' + this.value"
                    class="px-3 py-1.5 rounded-md border border-gray-200 text-sm text-gray-800 font-semibold focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white cursor-pointer">
                @foreach($financialData['periods'] ?? [] as $period)
                    <option value="{{ $period['key'] }}" {{ ($financialData['selected_period'] ?? '') === $period['key'] ? 'selected' : '' }}>
                        {{ $period['label'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Export Controls --}}
    @include('admin.partials._export-controls', [
        'action' => route('admin.reports.export'),
        'prefix' => 'reports-expense',
        'currentPeriod' => $financialData['selected_period'] ?? null,
    ])

    {{-- Ringkasan Bulan Terpilih (3 Kartu) --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500">Pemasukan Bulan Ini</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">
                Rp {{ number_format($financialData['income'] ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Dari pesanan paid &amp; completed</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">
                Rp {{ number_format($financialData['expense'] ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ $expenses->count() }} pos pengeluaran</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500">Saldo Bulan Ini</p>
            @php $balance = $financialData['balance'] ?? 0; @endphp
            <p class="text-2xl font-bold text-gray-900 mt-1">
                Rp {{ number_format($balance, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Pemasukan dikurangi pengeluaran</p>
        </div>
    </div>

    {{-- Form Tambah Pengeluaran --}}
    <div class="bg-white rounded-lg border border-gray-200 p-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Tambah Pengeluaran</h3>
        <form method="POST" action="{{ route('admin.reports.store') }}" id="form-tambah-expense" class="space-y-4">
            @csrf
            <input type="hidden" name="periode_redirect" value="{{ $financialData['selected_period'] ?? '' }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal *</label>
                    <input type="date" name="spent_at"
                           value="{{ old('spent_at', now()->format('Y-m-d')) }}"
                           max="{{ now()->format('Y-m-d') }}"
                           class="w-full px-3 py-2 rounded-md border {{ $errors->has('spent_at') ? 'border-rose-400' : 'border-gray-200' }} text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                    @error('spent_at')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Keterangan *</label>
                    <input type="text" name="description"
                           value="{{ old('description') }}"
                           placeholder="Contoh: Pembelian alat terapi sensori"
                           maxlength="255"
                           class="w-full px-3 py-2 rounded-md border {{ $errors->has('description') ? 'border-rose-400' : 'border-gray-200' }} text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                    @error('description')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori <span class="font-normal text-gray-400">(opsional)</span></label>
                    <input type="text" name="category"
                           value="{{ old('category') }}"
                           placeholder="Contoh: Operasional, Terapi, Logistik"
                           maxlength="100"
                           class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Jumlah (Rp) *</label>
                    <div class="relative flex items-center">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-semibold text-gray-500 pointer-events-none select-none">Rp</span>
                        <input type="text"
                               id="tambah-amount-display"
                               value="{{ old('amount') ? number_format((int) old('amount'), 0, ',', '.') : '' }}"
                               placeholder="0"
                               inputmode="numeric"
                               oninput="handleRupiahInput(this, 'tambah-amount-clean')"
                               class="w-full pl-9 pr-3 py-2 rounded-md border {{ $errors->has('amount') ? 'border-rose-400' : 'border-gray-200' }} text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition font-medium">
                        <input type="hidden" name="amount" id="tambah-amount-clean" value="{{ old('amount', '') }}">
                    </div>
                    @error('amount')
                        <p class="text-[11px] text-rose-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="px-4 py-2 rounded-md bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition shadow-xs min-h-[38px]">
                    Simpan Pengeluaran
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Pengeluaran --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <p class="text-xs font-semibold text-gray-500">{{ $expenses->count() }} pengeluaran tercatat pada periode ini</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-100">
                <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Keterangan</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Jumlah</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap text-gray-500">
                                {{ $expense->spent_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-gray-800 max-w-xs">
                                {{ $expense->description }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-gray-500">
                                {{ $expense->category ?: '—' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-semibold text-gray-900">
                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                            onclick="openEditExpenseModal({{ $expense->id }}, '{{ $expense->spent_at->format('Y-m-d') }}', {{ json_encode($expense->description) }}, {{ json_encode($expense->category ?? '') }}, {{ $expense->amount }})"
                                            class="px-2.5 py-1 rounded-md text-blue-700 text-xs font-semibold hover:bg-blue-50 transition">
                                        Ubah
                                    </button>
                                    <form method="POST" action="{{ route('admin.reports.destroy', $expense) }}" class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengeluaran ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-md text-rose-700 text-xs font-semibold hover:bg-rose-50 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500">
                                Belum ada catatan pengeluaran untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal Ubah Pengeluaran --}}
<div id="modal-edit-expense" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900">Ubah Pengeluaran</h3>
            <button type="button" onclick="closeEditExpenseModal()" class="text-gray-400 hover:text-gray-600" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="form-edit-expense" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="periode_redirect" value="{{ $financialData['selected_period'] ?? '' }}">

            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Tanggal *</label>
                <input type="date" name="spent_at" id="edit-spent-at"
                       max="{{ now()->format('Y-m-d') }}"
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Keterangan *</label>
                <input type="text" name="description" id="edit-description" maxlength="255"
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori <span class="font-normal text-gray-400">(opsional)</span></label>
                <input type="text" name="category" id="edit-category" maxlength="100"
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Jumlah (Rp) *</label>
                <div class="relative flex items-center">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-xs font-semibold text-gray-500 pointer-events-none select-none">Rp</span>
                    <input type="text" id="edit-amount-display"
                           inputmode="numeric"
                           oninput="handleRupiahInput(this, 'edit-amount-clean')"
                           placeholder="0"
                           class="w-full pl-9 pr-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition font-medium" required>
                    <input type="hidden" name="amount" id="edit-amount-clean">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeEditExpenseModal()" class="px-4 py-2 rounded-md border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-md bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function handleRupiahInput(displayInput, cleanInputId) {
    const raw = displayInput.value.replace(/\D/g, '');
    const cleanInput = document.getElementById(cleanInputId);
    if (cleanInput) {
        cleanInput.value = raw;
    }
    displayInput.value = raw ? new Intl.NumberFormat('id-ID').format(parseInt(raw, 10)) : '';
}

function openEditExpenseModal(id, spentAt, description, category, amount) {
    document.getElementById('form-edit-expense').action = '/admin/reports/' + id;
    document.getElementById('edit-spent-at').value = spentAt;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-category').value = category || '';
    const formatted = amount ? new Intl.NumberFormat('id-ID').format(amount) : '';
    document.getElementById('edit-amount-display').value = formatted;
    document.getElementById('edit-amount-clean').value = amount;
    const modal = document.getElementById('modal-edit-expense');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditExpenseModal() {
    const modal = document.getElementById('modal-edit-expense');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('modal-edit-expense')?.addEventListener('click', function(e) {
    if (e.target === this) closeEditExpenseModal();
});
</script>
@endsection
