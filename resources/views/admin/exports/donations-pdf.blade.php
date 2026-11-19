<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Donasi Masuk - {{ $rangeLabel }}</title>
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
        <h1>Laporan Donasi Masuk</h1>
        <p>Periode: {{ $rangeLabel }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">#</th>
                <th style="width: 80px;">Tanggal</th>
                <th>Nama Donatur</th>
                <th style="width: 120px;">Kategori</th>
                <th style="width: 90px;" class="text-right">Jumlah Barang</th>
                <th style="width: 80px;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donations as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->received_at ? $item->received_at->format('d/m/Y') : ($item->created_at ? $item->created_at->format('d/m/Y') : '-') }}</td>
                    <td>{{ $item->donor_name }}</td>
                    <td>{{ $item->category?->name ?: ($item->category_name ?: '-') }}</td>
                    <td class="text-right">{{ number_format($item->item_count, 0, ',', '.') }} barang</td>
                    <td class="text-center">{{ strtoupper($item->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty-row">Tidak ada data pada periode ini</td>
                </tr>
            @endforelse
            @if($donations->isNotEmpty())
                <tr class="total-row">
                    <td colspan="4" class="text-right">Total Barang Diterima:</td>
                    <td class="text-right">{{ number_format($total_items, 0, ',', '.') }} barang</td>
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
