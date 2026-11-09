@extends('admin.shared.Layout')

@section('title', 'Pesanan & Transaksi')

@section('content')
    <div class="space-y-5">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Daftar Pesanan & Transaksi</h2>
                <p class="text-sm text-gray-500 mt-0.5">Semua pesanan pembeli yang masuk via website atau WhatsApp.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-blue-700 text-xs font-semibold self-start sm:self-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                {{ $purchases->total() }} total pesanan
            </span>
        </div>

        {{-- Tabel Pesanan --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-200">
                    <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Kode Pesanan</th>
                            <th class="px-5 py-3.5">Nama Pembeli</th>
                            <th class="px-5 py-3.5">No. WhatsApp</th>
                            <th class="px-5 py-3.5">Total Belanja</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($purchases as $purchase)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap text-gray-400">
                                    {{ $purchase->created_at->format('d M Y') }}<br>
                                    <span class="text-[11px]">{{ $purchase->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-mono font-bold text-gray-900 text-xs">
                                    {{ $purchase->order_code }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <p class="font-semibold text-gray-900">{{ $purchase->customer_name }}</p>
                                    @if($purchase->customer_address)
                                        <p class="text-[11px] text-gray-400 truncate max-w-[160px]" title="{{ $purchase->customer_address }}">{{ $purchase->customer_address }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $purchase->customer_phone) }}" target="_blank" class="text-emerald-600 hover:underline font-medium flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                                        {{ $purchase->customer_phone }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-bold text-gray-900">
                                    Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $purchase->status === 'paid' || $purchase->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($purchase->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ strtoupper($purchase->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.purchases.update-status', $purchase) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg border border-gray-200 px-2 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer">
                                            @foreach(['pending', 'paid', 'completed', 'cancelled'] as $s)
                                                <option value="{{ $s }}" {{ $purchase->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                        <p class="text-sm font-medium">Belum ada transaksi pesanan yang masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($purchases->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $purchases->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection

