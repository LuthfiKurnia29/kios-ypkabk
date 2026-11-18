<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan & Keterbukaan - {{ $rangeLabel }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; margin: 20px; line-height: 1.4; }
        .header { margin-bottom: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; }
        .header h1 { font-size: 16px; margin: 0 0 4px 0; color: #111827; }
        .header p { margin: 0; font-size: 11px; color: #6b7280; }
        .summary-box { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .summary-box td { border: 1px solid #e5e7eb; padding: 10px; width: 33.33%; background-color: #f9fafb; }
        .summary-title { font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold; }
        .summary-value { font-size: 15px; font-weight: bold; margin-top: 4px; color: #111827; }
        .section-title { font-size: 13px; font-weight: bold; margin-top: 20px; margin-bottom: 6px; color: #111827; }
        .note { font-size: 10px; color: #6b7280; font-style: italic; margin-top: 4px; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 6px; margin-bottom: 16px; }
        table.data-table th { background-color: #f3f4f6; color: #374151; font-weight: bold; text-align: left; padding: 6px 8px; font-size: 10px; text-transform: uppercase; border: 1px solid #e5e7eb; }
        table.data-table td { padding: 5px 8px; border: 1px solid #e5e7eb; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background-color: #f9fafb; font-weight: bold; }
        .empty-row { text-align: center; padding: 12px; color: #6b7280; font-style: italic; }
        .footer { margin-top: 20px; font-size: 9px; color: #9ca3af; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan & Keterbukaan</h1>
        <p>Periode: {{ $rangeLabel }}</p>
    </div>

    {{-- Ringkasan --}}
    <table class="summary-box">
        <tr>
            <td>
                <div class="summary-title">Total Pemasukan</div>
                <div class="summary-value">Rp {{ number_format($income, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-title">Total Pengeluaran</div>
                <div class="summary-value">Rp {{ number_format($expense, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-title">Saldo Periode Ini</div>
                <div class="summary-value">Rp {{ number_format($balance, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    {{-- 1. Tabel Pemasukan dari Penjualan --}}
    <div class="section-title">Pemasukan dari Penjualan</div>
    <table class="data-table">
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
                    <td>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                    <td><code>{{ $item->order_code }}</code></td>
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
                    <td colspan="4" class="text-right">Total Pemasukan:</td>
                    <td class="text-right">Rp {{ number_format($income, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- 2. Tabel Pengeluaran --}}
    <div class="section-title">Pengeluaran Operasional & Terapi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">#</th>
                <th style="width: 80px;">Tanggal</th>
                <th>Keterangan</th>
                <th style="width: 120px;">Kategori</th>
                <th style="width: 90px;" class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->spent_at ? $item->spent_at->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->category ?: '—' }}</td>
                    <td class="text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-row">Tidak ada data pada periode ini</td>
                </tr>
            @endforelse
            @if($expenses->isNotEmpty())
                <tr class="total-row">
                    <td colspan="4" class="text-right">Total Pengeluaran:</td>
                    <td class="text-right">Rp {{ number_format($expense, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- 3. Tabel Barang Donasi Diterima --}}
    <div class="section-title">Barang Donasi Diterima</div>
    <div class="note">* Donasi berupa barang, tidak dihitung dalam saldo rupiah.</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">#</th>
                <th style="width: 80px;">Tanggal</th>
                <th>Nama Donatur</th>
                <th style="width: 120px;">Kategori</th>
                <th style="width: 90px;" class="text-right">Jumlah Barang</th>
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
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-row">Tidak ada data pada periode ini</td>
                </tr>
            @endforelse
            @if($donations->isNotEmpty())
                <tr class="total-row">
                    <td colspan="4" class="text-right">Total Barang Donasi:</td>
                    <td class="text-right">{{ number_format($total_donation_items, 0, ',', '.') }} barang</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ $generatedAt }}
    </div>
</body>
</html>
