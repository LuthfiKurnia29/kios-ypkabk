<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pesanan - {{ $rangeLabel }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 20px; line-height: 1.4; }
        .header { margin-bottom: 20px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; }
        .header h1 { font-size: 16px; margin: 0 0 4px 0; color: #111827; }
        .header p { margin: 0; font-size: 11px; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th { background-color: #f3f4f6; color: #374151; font-weight: bold; text-align: left; padding: 7px 8px; font-size: 10px; text-transform: uppercase; border: 1px solid #e5e7eb; }
        td { padding: 6px 8px; border: 1px solid #e5e7eb; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background-color: #f9fafb; font-weight: bold; }
        .empty-row { text-align: center; padding: 16px; color: #6b7280; font-style: italic; }
        .footer { margin-top: 20px; font-size: 9px; color: #9ca3af; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pesanan</h1>
        <p>Periode: {{ $rangeLabel }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">#</th>
                <th style="width: 80px;">Tanggal</th>
                <th style="width: 100px;">Kode Pesanan</th>
                <th>Pembeli</th>
                <th style="width: 90px;" class="text-right">Total</th>
                <th style="width: 70px;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}</td>
                    <td><code>{{ $item->order_code }}</code>@if($item->order_type === 'on_demand')<br><small style="color: #4f46e5; font-weight: bold;">On Demand</small>@endif</td>
                    <td>{{ $item->customer_name }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ strtoupper($item->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-row">Tidak ada data pada periode ini</td>
                </tr>
            @endforelse
            @if($purchases->isNotEmpty())
                <tr class="total-row">
                    <td colspan="4" class="text-right">Total Penjualan:</td>
                    <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ $generatedAt }}
    </div>
</body>
</html>
