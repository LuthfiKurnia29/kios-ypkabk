@extends('admin.shared.Layout')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Welcome Header --}}
        <div class="bg-white rounded-lg border border-gray-200 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Selamat datang, {{ auth()->user()->name }}!</h2>
                <p class="mt-0.5 text-sm text-gray-500">Kelola katalog produk, pesanan pembeli, dan donasi masuk Kios Mandiri YPKABK.</p>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center px-3.5 py-2 rounded-md border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-medium text-xs transition-colors self-start sm:self-auto">
                Lihat Website Pengunjung
            </a>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <a href="{{ route('admin.purchases.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:border-gray-300 transition-colors block">
                <p class="text-xs text-gray-500 font-medium">Pesanan Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $todayPurchases ?? 0 }}</p>
                <p class="mt-1 text-xs text-primary font-medium">Lihat semua pesanan &rarr;</p>
            </a>

            <a href="{{ route('admin.purchases.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:border-gray-300 transition-colors block">
                <p class="text-xs text-gray-500 font-medium">Dana Terapi Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-primary font-medium">Lihat rincian &rarr;</p>
            </a>

            <a href="{{ route('admin.donations.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:border-gray-300 transition-colors block">
                <p class="text-xs text-gray-500 font-medium">Total Donasi Barang</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalDonations ?? 0 }}</p>
                <p class="mt-1 text-xs text-primary font-medium">Kelola donasi masuk &rarr;</p>
            </a>

            <a href="{{ route('admin.products.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 hover:border-gray-300 transition-colors block">
                <p class="text-xs text-gray-500 font-medium">Total Produk Katalog</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalProducts ?? 0 }}</p>
                <p class="mt-1 text-xs text-primary font-medium">Kelola produk &rarr;</p>
            </a>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Pesanan Terbaru --}}
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Pesanan Terbaru</h3>
                        <p class="text-xs text-gray-500 mt-0.5">5 pesanan terakhir masuk</p>
                    </div>
                    <a href="{{ route('admin.purchases.index') }}" class="text-xs font-medium text-primary hover:underline">Lihat semua &rarr;</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentPurchases ?? [] as $purchase)
                        <div class="px-4 py-3 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $purchase->customer_name }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $purchase->order_code }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</p>
                                <span class="text-xs font-medium {{ $purchase->status === 'paid' || $purchase->status === 'completed' ? 'text-emerald-700' : ($purchase->status === 'cancelled' ? 'text-rose-700' : 'text-amber-700') }}">
                                    {{ strtoupper($purchase->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-6 text-center text-xs text-gray-500">Belum ada pesanan masuk.</div>
                    @endforelse
                </div>
            </div>

            {{-- Donasi Terbaru --}}
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Donasi Barang Terbaru</h3>
                        <p class="text-xs text-gray-500 mt-0.5">5 donasi preloved terakhir</p>
                    </div>
                    <a href="{{ route('admin.donations.index') }}" class="text-xs font-medium text-primary hover:underline">Lihat semua &rarr;</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentDonations ?? [] as $donation)
                        <div class="px-4 py-3 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $donation->donor_name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $donation->donor_city }} &middot; {{ $donation->category?->name ?: ($donation->category_name ?: 'Umum') }}</p>
                            </div>
                            <span class="text-xs font-medium text-blue-700 shrink-0">
                                {{ strtoupper($donation->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="px-4 py-6 text-center text-xs text-gray-500">Belum ada donasi masuk.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection
