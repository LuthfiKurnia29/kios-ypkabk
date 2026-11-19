@extends('admin.shared.Layout')

@section('title', 'Laporan Pemasukan')

@section('content')
<div class="space-y-5">

    {{-- Tabs Laporan --}}
    <div class="flex items-center gap-2 border-b border-gray-200 pb-2" role="tablist" aria-label="Tab Laporan">
        <a href="{{ route('admin.reports.income', ['periode' => $financialData['selected_period'] ?? '']) }}"
           role="tab"
           aria-selected="true"
           class="px-4 py-2 rounded-md text-sm font-semibold bg-primary text-white transition focus:outline-none focus:ring-2 focus:ring-primary/30">
            Pemasukan
        </a>
        <a href="{{ route('admin.reports.index', ['periode' => $financialData['selected_period'] ?? '']) }}"
           role="tab"
           aria-selected="false"
           class="px-4 py-2 rounded-md text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition focus:outline-none focus:ring-2 focus:ring-primary/30">
            Pengeluaran
        </a>
    </div>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Laporan Pemasukan</h2>
            <p class="text-sm text-gray-500 mt-0.5">Pantau arus pemasukan dari pesanan katalog dan penerimaan donasi barang.</p>
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
        'prefix' => 'reports-income',
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
            <p class="text-xs text-gray-400 mt-1">Pengeluaran operasional</p>
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

    {{-- Tabel 1: Pemasukan dari Penjualan --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900">Pemasukan dari Penjualan</h3>
            <p class="text-xs text-gray-500">{{ $purchases->count() }} transaksi lunas</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-100">
                <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5 w-12">#</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Kode Pesanan</th>
                        <th class="px-5 py-3.5">Nama Pembeli</th>
                        <th class="px-5 py-3.5 text-right">Total</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($purchases as $index => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4 text-gray-400 font-mono">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-gray-600">
                                {{ $item->created_at ? $item->created_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <code class="px-2 py-0.5 rounded bg-gray-100 text-gray-700 font-mono text-[11px]">{{ $item->order_code }}</code>
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-900">
                                {{ $item->customer_name }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-bold text-gray-900">
                                Rp {{ number_format($item->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-center">
                                <span class="text-[11px] font-semibold text-emerald-700">
                                    {{ strtoupper($item->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-500">
                                Belum ada pemasukan dari penjualan untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($purchases->isNotEmpty())
                    <tfoot class="bg-gray-50/70 border-t border-gray-200 font-bold text-xs">
                        <tr>
                            <td colspan="4" class="px-5 py-3.5 text-right text-gray-700">Total Pemasukan:</td>
                            <td class="px-5 py-3.5 text-right text-gray-900">Rp {{ number_format($purchases->sum('total_price'), 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    {{-- Tabel 2: Barang Donasi Diterima --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-1">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Barang Donasi Diterima</h3>
                <p class="text-xs text-gray-500 mt-0.5">Donasi berupa barang, tidak dihitung dalam saldo rupiah.</p>
            </div>
            <p class="text-xs text-gray-500">{{ $donations->count() }} donasi diterima</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-100">
                <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5 w-12">#</th>
                        <th class="px-5 py-3.5">Tanggal Diterima</th>
                        <th class="px-5 py-3.5">Nama Donatur</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5 text-right">Jumlah Barang</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($donations as $index => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4 text-gray-400 font-mono">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-gray-600">
                                {{ $item->received_at ? $item->received_at->format('d M Y') : ($item->created_at ? $item->created_at->format('d M Y') : '-') }}
                            </td>
                            <td class="px-5 py-4 font-semibold text-gray-900">
                                {{ $item->donor_name }}
                            </td>
                            <td class="px-5 py-4 text-gray-600">
                                {{ $item->category?->name ?: ($item->category_name ?: '—') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right font-semibold text-gray-900">
                                {{ number_format($item->item_count, 0, ',', '.') }} barang
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-500">
                                Belum ada barang donasi diterima untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($donations->isNotEmpty())
                    <tfoot class="bg-gray-50/70 border-t border-gray-200 font-bold text-xs">
                        <tr>
                            <td colspan="4" class="px-5 py-3.5 text-right text-gray-700">Total Barang:</td>
                            <td class="px-5 py-3.5 text-right text-gray-900">{{ number_format($totalDonationItems, 0, ',', '.') }} barang</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

</div>
@endsection
