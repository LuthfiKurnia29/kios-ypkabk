<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; font-size: 14px;">Laporan Pesanan - {{ $rangeLabel }}</th>
        </tr>
        <tr>
            <th colspan="6" style="color: #6b7280; font-size: 10px;">Dicetak pada: {{ $generatedAt }}</th>
        </tr>
        <tr></tr>
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
                <td style="border: 1px solid #e5e7eb;">{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                <td style="border: 1px solid #e5e7eb;">{{ $item->order_code }}{{ $item->order_type === 'on_demand' ? ' (On Demand)' : '' }}</td>
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
                <td colspan="4" style="font-weight: bold; text-align: right; border: 1px solid #d1d5db; background-color: #f9fafb;">Total:</td>
                <td style="font-weight: bold; border: 1px solid #d1d5db; background-color: #f9fafb;" data-format="#,##0">{{ (int) $total }}</td>
                <td style="border: 1px solid #d1d5db; background-color: #f9fafb;"></td>
            </tr>
        @endif
    </tbody>
</table>
