@extends('admin.shared.Layout')

@section('title', 'Laporan & Statistik')

@section('content')
    <div class="space-y-6">

        {{-- Page Header + Filter --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Laporan & Statistik</h2>
                    <p class="mt-1 text-sm text-gray-500">Ringkasan penjualan dan donasi berdasarkan periode yang dipilih.</p>
                </div>

                {{-- Filter Periode --}}
                <form method="GET" action="{{ route('admin.reports.index') }}" class="flex items-center gap-2 flex-wrap">
                    <select name="bulan"
                            class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary cursor-pointer">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" @selected($m === $bulan)>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>

                    <select name="tahun"
                            class="text-sm border border-gray-200 rounded-xl px-3 py-2 bg-gray-50 font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary cursor-pointer">
                        @foreach(range(now()->year, 2024, -1) as $y)
                            <option value="{{ $y }}" @selected($y === $tahun)>{{ $y }}</option>
                        @endforeach
                    </select>

                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/40">
                        Tampilkan
                    </button>
                </form>
            </div>

            <p class="mt-3 text-xs text-gray-400">
                Menampilkan data periode:
                <span class="font-semibold text-gray-600">
                    {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}
                </span>
            </p>
        </div>

        {{-- Penjualan & Donasi - Summary Cards --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- === BLOK PENJUALAN === --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Laporan Penjualan</h3>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-gray-500 mb-1">Total Transaksi</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalTransaksi }}</p>
                        <p class="text-xs text-gray-400 mt-1">pesanan masuk</p>
                    </div>
                    <div class="bg-emerald-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-emerald-700 mb-1">Total Pendapatan</p>
                        <p class="text-xl font-bold text-emerald-700">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                        <p class="text-xs text-emerald-600 mt-1">dari {{ $totalPaid }} pesanan lunas</p>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-amber-700 mb-1">Menunggu Pembayaran</p>
                        <p class="text-3xl font-bold text-amber-700">{{ $totalPending }}</p>
                        <p class="text-xs text-amber-600 mt-1">pesanan pending</p>
                    </div>
                    <div class="bg-rose-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-rose-700 mb-1">Dibatalkan</p>
                        <p class="text-3xl font-bold text-rose-700">{{ $totalCancelled }}</p>
                        <p class="text-xs text-rose-600 mt-1">pesanan cancelled</p>
                    </div>
                </div>
            </div>

            {{-- === BLOK DONASI === --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Laporan Donasi Barang</h3>
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</p>
                    </div>
                </div>

                <div class="p-6 grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-gray-500 mb-1">Total Donasi Masuk</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalDonasi }}</p>
                        <p class="text-xs text-gray-400 mt-1">donasi barang</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-blue-700 mb-1">Sudah Diterima</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $donasiDiterima }}</p>
                        <p class="text-xs text-blue-600 mt-1">barang diterima</p>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-amber-700 mb-1">Sedang Diproses</p>
                        <p class="text-3xl font-bold text-amber-700">{{ $donasiPending }}</p>
                        <p class="text-xs text-amber-600 mt-1">pending / konfirmasi</p>
                    </div>
                    <div class="bg-rose-50 rounded-xl p-4">
                        <p class="text-xs font-medium text-rose-700 mb-1">Ditolak</p>
                        <p class="text-3xl font-bold text-rose-700">{{ $donasiDitolak }}</p>
                        <p class="text-xs text-rose-600 mt-1">donasi ditolak</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top 5 Produk Terlaku --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Top 5 Produk Terlaku</h3>
                        <p class="text-xs text-gray-400">Berdasarkan pesanan lunas bulan ini</p>
                    </div>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-primary hover:underline">
                    Lihat semua produk →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700">
                    <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-6 py-3.5">#</th>
                            <th class="px-6 py-3.5">Nama Produk</th>
                            <th class="px-6 py-3.5 text-right">Terjual (unit)</th>
                            <th class="px-6 py-3.5 text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($topProduk as $index => $item)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="inline-flex w-6 h-6 items-center justify-center rounded-lg
                                        {{ $index === 0 ? 'bg-amber-100 text-amber-700' : ($index === 1 ? 'bg-gray-100 text-gray-600' : ($index === 2 ? 'bg-orange-100 text-orange-600' : 'bg-gray-50 text-gray-400')) }}
                                        font-bold text-[11px]">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ $item->product?->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900">
                                    {{ number_format($item->total_terjual, 0, ',', '.') }} unit
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-700">
                                    Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-400">
                                    Belum ada transaksi lunas di periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel Ringkasan Per Bulan (12 bulan terakhir) --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- Penjualan per Bulan --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 text-sm">Tren Penjualan — 12 Bulan Terakhir</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Jumlah transaksi dan pendapatan per bulan</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700">
                        <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-5 py-3.5">Bulan</th>
                                <th class="px-5 py-3.5 text-right">Transaksi</th>
                                <th class="px-5 py-3.5 text-right">Pendapatan</th>
                                <th class="px-5 py-3.5 text-right">Dibatal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @foreach($penjualanPerBulan as $row)
                                <tr class="hover:bg-gray-50 transition-colors {{ $row['bulan'] === \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('M Y') ? 'bg-primary/5' : '' }}">
                                    <td class="px-5 py-3.5 font-semibold text-gray-700 whitespace-nowrap">
                                        {{ $row['bulan'] }}
                                        @if($row['bulan'] === \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('M Y'))
                                            <span class="ml-1 text-[10px] font-bold text-primary">(dipilih)</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-right text-gray-900">{{ $row['total'] }}</td>
                                    <td class="px-5 py-3.5 text-right font-bold text-emerald-700">
                                        @if($row['pendapatan'] > 0)
                                            Rp {{ number_format($row['pendapatan'], 0, ',', '.') }}
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-right {{ $row['cancelled'] > 0 ? 'text-rose-600 font-bold' : 'text-gray-300' }}">
                                        {{ $row['cancelled'] > 0 ? $row['cancelled'] : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Donasi per Bulan --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900 text-sm">Tren Donasi — 12 Bulan Terakhir</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Jumlah donasi masuk per bulan</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-700">
                        <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="px-5 py-3.5">Bulan</th>
                                <th class="px-5 py-3.5 text-right">Total</th>
                                <th class="px-5 py-3.5 text-right">Diterima</th>
                                <th class="px-5 py-3.5 text-right">Ditolak</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-medium">
                            @foreach($donasiPerBulan as $row)
                                <tr class="hover:bg-gray-50 transition-colors {{ $row['bulan'] === \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('M Y') ? 'bg-primary/5' : '' }}">
                                    <td class="px-5 py-3.5 font-semibold text-gray-700 whitespace-nowrap">
                                        {{ $row['bulan'] }}
                                        @if($row['bulan'] === \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('M Y'))
                                            <span class="ml-1 text-[10px] font-bold text-primary">(dipilih)</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5 text-right text-gray-900">{{ $row['total'] }}</td>
                                    <td class="px-5 py-3.5 text-right {{ $row['diterima'] > 0 ? 'text-blue-600 font-bold' : 'text-gray-300' }}">
                                        {{ $row['diterima'] > 0 ? $row['diterima'] : '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-right {{ $row['ditolak'] > 0 ? 'text-rose-600 font-bold' : 'text-gray-300' }}">
                                        {{ $row['ditolak'] > 0 ? $row['ditolak'] : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
