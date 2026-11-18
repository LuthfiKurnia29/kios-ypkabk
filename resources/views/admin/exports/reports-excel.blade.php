<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; font-size: 14px;">Laporan Keuangan & Keterbukaan - {{ $rangeLabel }}</th>
        </tr>
        <tr>
            <th colspan="6" style="color: #6b7280; font-size: 10px;">Dicetak pada: {{ $generatedAt }}</th>
        </tr>
        <tr></tr>

        {{-- Ringkasan --}}
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Total Pemasukan</th>
            <th colspan="2" style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Total Pengeluaran</th>
            <th colspan="2" style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Saldo Periode Ini</th>
        </tr>
        <tr>
            <td colspan="2" style="font-weight: bold; border: 1px solid #d1d5db;" data-format="#,##0">{{ (int) $income }}</td>
            <td colspan="2" style="font-weight: bold; border: 1px solid #d1d5db;" data-format="#,##0">{{ (int) $expense }}</td>
            <td colspan="2" style="font-weight: bold; border: 1px solid #d1d5db;" data-format="#,##0">{{ (int) $balance }}</td>
        </tr>
        <tr></tr>

        {{-- 1. Pemasukan dari Penjualan --}}
        <tr>
            <th colspan="6" style="font-weight: bold; font-size: 12px;">Pemasukan dari Penjualan</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">No</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Tanggal</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Kode Pesanan</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Pembeli</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Total (Rp)</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($purchases as $index => $item)
            <tr>
                <td style="border: 1px solid #e5e7eb;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ $item->order_code }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ $item->customer_name }}</td>
                <td style="border: 1px solid #e5e7eb;" data-format="#,##0">{{ (int) $item->total_price }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ strtoupper($item->status) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; font-style: italic; border: 1px solid #e5e7eb;">Tidak ada data pada periode ini</td>
            </tr>
        @endforelse
        @if($purchases->isNotEmpty())
            <tr>
                <td colspan="4" style="font-weight: bold; text-align: right; border: 1px solid #d1d5db; background-color: #f9fafb;">Total Pemasukan:</td>
                <td style="font-weight: bold; border: 1px solid #d1d5db; background-color: #f9fafb;" data-format="#,##0">{{ (int) $income }}</td>
                <td style="border: 1px solid #d1d5db; background-color: #f9fafb;"></td>
            </tr>
        @endif

        <tr></tr>
        {{-- 2. Pengeluaran --}}
        <tr>
            <th colspan="6" style="font-weight: bold; font-size: 12px;">Pengeluaran Operasional & Terapi</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">No</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Tanggal</th>
            <th colspan="2" style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Keterangan</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Kategori</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Jumlah (Rp)</th>
        </tr>
        @forelse($expenses as $index => $item)
            <tr>
                <td style="border: 1px solid #e5e7eb;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ $item->spent_at ? $item->spent_at->format('d/m/Y') : '-' }}</td>
                <td colspan="2" style="border: 1px solid #e5e7eb;">{{ $item->description }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ $item->category ?: '—' }}</td>
                <td style="border: 1px solid #e5e7eb;" data-format="#,##0">{{ (int) $item->amount }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; font-style: italic; border: 1px solid #e5e7eb;">Tidak ada data pada periode ini</td>
            </tr>
        @endforelse
        @if($expenses->isNotEmpty())
            <tr>
                <td colspan="5" style="font-weight: bold; text-align: right; border: 1px solid #d1d5db; background-color: #f9fafb;">Total Pengeluaran:</td>
                <td style="font-weight: bold; border: 1px solid #d1d5db; background-color: #f9fafb;" data-format="#,##0">{{ (int) $expense }}</td>
            </tr>
        @endif

        <tr></tr>
        {{-- 3. Barang Donasi Diterima --}}
        <tr>
            <th colspan="6" style="font-weight: bold; font-size: 12px;">Barang Donasi Diterima (* Donasi berupa barang, tidak dihitung dalam saldo rupiah)</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">No</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Tanggal</th>
            <th colspan="2" style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Nama Donatur</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Kategori</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Jumlah Barang</th>
        </tr>
        @forelse($donations as $index => $item)
            <tr>
                <td style="border: 1px solid #e5e7eb;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ $item->received_at ? $item->received_at->format('d/m/Y') : ($item->created_at ? $item->created_at->format('d/m/Y') : '-') }}</td>
                <td colspan="2" style="border: 1px solid #e5e7eb;">{{ $item->donor_name }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ $item->category?->name ?: ($item->category_name ?: '-') }}</td>
                <td style="border: 1px solid #e5e7eb;" data-format="#,##0">{{ (int) $item->item_count }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; font-style: italic; border: 1px solid #e5e7eb;">Tidak ada data pada periode ini</td>
            </tr>
        @endforelse
        @if($donations->isNotEmpty())
            <tr>
                <td colspan="5" style="font-weight: bold; text-align: right; border: 1px solid #d1d5db; background-color: #f9fafb;">Total Barang Donasi:</td>
                <td style="font-weight: bold; border: 1px solid #d1d5db; background-color: #f9fafb;" data-format="#,##0">{{ (int) $total_donation_items }}</td>
            </tr>
        @endif
    </tbody>
</table>
