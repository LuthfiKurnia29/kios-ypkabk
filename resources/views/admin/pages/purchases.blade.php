@extends('admin.shared.Layout')

@section('title', 'Pesanan')

@section('content')
    <div class="space-y-5">

        {{-- Page Header --}}
        <div>
            <h2 class="text-lg font-bold text-gray-900">Pesanan & Transaksi</h2>
            <p class="text-sm text-gray-500 mt-0.5">Semua pesanan pembeli yang masuk via website atau WhatsApp.</p>
        </div>

        {{-- Export Controls --}}
        @include('admin.partials._export-controls', ['action' => route('admin.purchases.export'), 'prefix' => 'purchases'])

        {{-- Flash message --}}
        @if(session('success'))
            <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-xs font-medium cursor-pointer">
                    Tutup
                </button>
            </div>
        @endif

        {{-- Tabel Pesanan --}}
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-200">
                    <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Kode Pesanan</th>
                            <th class="px-5 py-3.5">Nama Pembeli</th>
                            <th class="px-5 py-3.5">No. WhatsApp</th>
                            <th class="px-5 py-3.5">Total Belanja</th>
                            <th class="px-5 py-3.5">Bukti Bayar</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($purchases as $purchase)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap text-gray-500">
                                    {{ $purchase->created_at->format('d M Y') }}<br>
                                    <span class="text-[11px] text-gray-400">{{ $purchase->created_at->format('H:i') }}</span>
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
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $purchase->customer_phone) }}" target="_blank" class="text-emerald-700 hover:underline font-medium">
                                        {{ $purchase->customer_phone }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-bold text-gray-900">
                                    Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($purchase->payment_proof)
                                        <a href="{{ asset('storage/'.$purchase->payment_proof) }}" target="_blank" class="px-2 py-1 rounded border border-gray-200 text-gray-700 hover:bg-gray-50 text-[11px] font-medium transition-colors" title="Lihat Foto Bukti Transfer">
                                            Lihat Bukti
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-[11px]">Belum diunggah</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-xs font-medium {{ $purchase->status === 'paid' || $purchase->status === 'completed' ? 'text-emerald-700' : ($purchase->status === 'cancelled' ? 'text-rose-700' : 'text-amber-700') }}">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    <div class="flex flex-col items-end gap-1.5">
                                        {{-- Update Status --}}
                                        <form method="POST" action="{{ route('admin.purchases.update-status', $purchase) }}" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="text-xs rounded-md border border-gray-200 px-2 py-1 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-primary cursor-pointer">
                                                @foreach(['pending', 'paid', 'completed', 'cancelled'] as $s)
                                                    <option value="{{ $s }}" {{ $purchase->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                                @endforeach
                                            </select>
                                        </form>

                                        {{-- Tombol Minta Ulasan via WhatsApp jika Paid / Completed --}}
                                        @if(in_array($purchase->status, ['paid', 'completed']))
                                            @if($purchase->review)
                                                <span class="text-[11px] text-gray-500 font-medium">
                                                    Sudah diulas
                                                </span>
                                            @else
                                                @php
                                                    $waLink = \App\Support\ReviewRequestLink::forPurchase($purchase);
                                                @endphp
                                                @if($waLink)
                                                    <a href="{{ $waLink }}" target="_blank"
                                                        class="text-[11px] font-medium text-emerald-700 hover:underline">
                                                        Minta Ulasan
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-10 text-center text-sm text-gray-500">
                                    Belum ada transaksi pesanan yang masuk.
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
