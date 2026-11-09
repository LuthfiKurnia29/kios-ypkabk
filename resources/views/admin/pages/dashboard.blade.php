@extends('admin.shared.Layout')

@section('title', 'Dashboard Ringkasan')

@section('content')
    <div class="space-y-6">

        {{-- Welcome Header --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Selamat datang, {{ auth()->user()->name }}!</h2>
                <p class="mt-1 text-sm text-gray-500">Kelola katalog produk, pesanan pembeli, dan donasi masuk Kios Mandiri YPKABK.</p>
            </div>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs transition-colors self-start sm:self-auto">
                <span>Lihat Website Pengunjung</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <a href="{{ route('admin.purchases.index') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 hover:border-primary hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">Pesanan Hari Ini</p>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $todayPurchases ?? 0 }}</p>
                <p class="mt-1.5 text-xs text-blue-600 font-medium">Lihat semua pesanan →</p>
            </a>

            <a href="{{ route('admin.purchases.index') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 hover:border-primary hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">Dana Terapi Bulan Ini</p>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($monthlyRevenue ?? 0, 0, ',', '.') }}</p>
                <p class="mt-1.5 text-xs text-emerald-600 font-medium">Lihat rincian →</p>
            </a>

            <a href="{{ route('admin.donations.index') }}" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 hover:border-primary hover:shadow-md transition-all group">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">Total Donasi Barang</p>
                    <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center group-hover:bg-purple-100 transition-colors">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $totalDonations ?? 0 }}</p>
                <p class="mt-1.5 text-xs text-purple-600 font-medium">Kelola donasi masuk →</p>
            </a>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-500">Total Produk Katalog</p>
                    <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900">{{ $totalProducts ?? 0 }}</p>
                <p class="mt-1.5 text-xs text-gray-400 font-medium">Produk aktif di katalog</p>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Pesanan Terbaru --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Pesanan Terbaru</h3>
                        <p class="text-xs text-gray-400 mt-0.5">5 pesanan terakhir masuk</p>
                    </div>
                    <a href="{{ route('admin.purchases.index') }}" class="text-xs font-semibold text-primary hover:underline">Lihat semua →</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentPurchases ?? [] as $purchase)
                        <div class="px-5 py-3.5 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $purchase->customer_name }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $purchase->order_code }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</p>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $purchase->status === 'paid' || $purchase->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : ($purchase->status === 'cancelled' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ strtoupper($purchase->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada pesanan masuk.</div>
                    @endforelse
                </div>
            </div>

            {{-- Donasi Terbaru --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Donasi Barang Terbaru</h3>
                        <p class="text-xs text-gray-400 mt-0.5">5 donasi preloved terakhir</p>
                    </div>
                    <a href="{{ route('admin.donations.index') }}" class="text-xs font-semibold text-primary hover:underline">Lihat semua →</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentDonations ?? [] as $donation)
                        <div class="px-5 py-3.5 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $donation->donor_name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $donation->donor_city }} · {{ $donation->category?->name ?: ($donation->category_name ?: 'Umum') }}</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 shrink-0">
                                {{ strtoupper($donation->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-sm text-gray-400">Belum ada donasi masuk.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
@endsection
