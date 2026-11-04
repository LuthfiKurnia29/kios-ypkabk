@extends('admin.shared.Layout')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Welcome --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Selamat datang, {{ auth()->user()->name }}!</h2>
                <p class="mt-1 text-sm text-gray-500">Kelola katalog produk, pesanan pembeli, dan donasi masuk Kios Mandiri YPKABK.</p>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs transition-colors self-start sm:self-auto">
                <span>Lihat Website Pengunjung</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>

        {{-- Statistics (Real Data from DB) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <p class="text-sm font-medium text-gray-500">Total Produk Katalog</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalProducts ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <p class="text-sm font-medium text-gray-500">Pesanan Hari Ini</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $todayPurchases ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <p class="text-sm font-medium text-gray-500">Total Donasi Barang Masuk</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalDonations ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <p class="text-sm font-medium text-gray-500">Dana Terapi Bulan Ini</p>
                <p class="mt-2 text-2xl font-bold text-emerald-600">Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Tabel 1: Pesanan Pembeli Terbaru --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-base">Pesanan Pembeli Terbaru</h3>
                    <p class="text-xs text-gray-500">Daftar transaksi pesanan masuk via website atau WhatsApp.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-200">
                    <thead class="bg-gray-50 font-bold text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Kode Pesanan</th>
                            <th class="px-5 py-3">Nama Pembeli</th>
                            <th class="px-5 py-3">No. WhatsApp</th>
                            <th class="px-5 py-3">Total Belanja</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($recentPurchases ?? [] as $purchase)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5 whitespace-nowrap text-gray-500">
                                    {{ $purchase->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap font-mono font-bold text-gray-900">
                                    {{ $purchase->order_code }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap font-semibold text-gray-900">
                                    {{ $purchase->customer_name }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $purchase->customer_phone) }}" target="_blank" class="text-emerald-600 hover:underline font-medium">
                                        {{ $purchase->customer_phone }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap font-bold text-gray-900">
                                    Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $purchase->status === 'paid' || $purchase->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($purchase->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ strtoupper($purchase->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                                    Belum ada transaksi pesanan yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabel 2: Donasi Barang Masuk Terbaru --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-base">Donasi Barang Preloved Masuk</h3>
                    <p class="text-xs text-gray-500">Konfirmasi jadwal pickup / pengiriman pos barang donasi.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-200">
                    <thead class="bg-gray-50 font-bold text-gray-600 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Nama Donatur</th>
                            <th class="px-5 py-3">WhatsApp</th>
                            <th class="px-5 py-3">Kota Asal</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3">Deskripsi Barang</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($recentDonations ?? [] as $donation)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-3.5 whitespace-nowrap text-gray-500">
                                    {{ $donation->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap font-bold text-gray-900">
                                    {{ $donation->donor_name }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $donation->donor_phone) }}" target="_blank" class="text-emerald-600 hover:underline font-medium">
                                        {{ $donation->donor_phone }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    {{ $donation->donor_city }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 text-[11px] font-semibold">
                                        {{ $donation->category?->name ?: ($donation->category_name ?: 'Umum') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-600 max-w-xs truncate" title="{{ $donation->description }}">
                                    {{ $donation->description ?: '-' }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                                        {{ strtoupper($donation->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-gray-500">
                                    Belum ada formulir donasi barang yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
