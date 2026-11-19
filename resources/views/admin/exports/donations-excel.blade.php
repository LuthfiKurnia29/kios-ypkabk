<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; font-size: 14px;">Laporan Donasi Masuk - {{ $rangeLabel }}</th>
        </tr>
        <tr>
            <th colspan="6" style="color: #6b7280; font-size: 10px;">Dicetak pada: {{ $generatedAt }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">No</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Tanggal</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Nama Donatur</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Kategori</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Jumlah Barang</th>
            <th style="font-weight: bold; background-color: #f3f4f6; border: 1px solid #d1d5db;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($donations as $index => $item)
            <tr>
                <td style="border: 1px solid #e5e7eb;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ $item->received_at ? $item->received_at->format('d/m/Y') : ($item->created_at ? $item->created_at->format('d/m/Y') : '-') }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ $item->donor_name }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ $item->category?->name ?: ($item->category_name ?: '-') }}</td>
                <td style="border: 1px solid #e5e7eb;" data-format="#,##0">{{ (int) $item->item_count }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ strtoupper($item->status) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; font-style: italic; border: 1px solid #e5e7eb;">Tidak ada data pada periode ini</td>
            </tr>
        @endforelse
        @if($donations->isNotEmpty())
            <tr>
                <td colspan="4" style="font-weight: bold; text-align: right; border: 1px solid #d1d5db; background-color: #f9fafb;">Total Barang:</td>
                <td style="font-weight: bold; border: 1px solid #d1d5db; background-color: #f9fafb;" data-format="#,##0">{{ (int) $total_items }}</td>
                <td style="border: 1px solid #d1d5db; background-color: #f9fafb;"></td>
            </tr>
        @endif
    </tbody>
</table>
