@extends('admin.shared.Layout')

@section('title', 'Donasi Barang Masuk')

@section('content')
    <div class="space-y-5">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Daftar Donasi Barang Preloved Masuk</h2>
                <p class="text-sm text-gray-500 mt-0.5">Konfirmasi jadwal pickup atau pengiriman barang donasi dari donatur.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-purple-700 text-xs font-semibold self-start sm:self-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                {{ $donations->total() }} total donasi
            </span>
        </div>

        {{-- Tabel Donasi --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-200">
                    <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Nama Donatur</th>
                            <th class="px-5 py-3.5">WhatsApp</th>
                            <th class="px-5 py-3.5">Kota Asal</th>
                            <th class="px-5 py-3.5">Kategori</th>
                            <th class="px-5 py-3.5">Deskripsi Barang</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($donations as $donation)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap text-gray-400">
                                    {{ $donation->created_at->format('d M Y') }}<br>
                                    <span class="text-[11px]">{{ $donation->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-bold text-gray-900">
                                    {{ $donation->donor_name }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $donation->donor_phone) }}" target="_blank" class="text-emerald-600 hover:underline font-medium flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
                                        {{ $donation->donor_phone }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-gray-700">
                                    {{ $donation->donor_city }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-lg bg-gray-100 text-gray-800 text-[11px] font-semibold">
                                        {{ $donation->category?->name ?: ($donation->category_name ?: 'Umum') }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-600 max-w-[200px] truncate" title="{{ $donation->description }}">
                                    {{ $donation->description ?: '-' }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold
                                        {{ $donation->status === 'received' ? 'bg-emerald-100 text-emerald-800' :
                                           ($donation->status === 'rejected' ? 'bg-rose-100 text-rose-800' :
                                           ($donation->status === 'confirmed' ? 'bg-blue-100 text-blue-800' :
                                           ($donation->status === 'picked_up' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800'))) }}">
                                        {{ strtoupper($donation->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.donations.update-status', $donation) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg border border-gray-200 px-2 py-1.5 bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer">
                                            @foreach(['pending', 'confirmed', 'picked_up', 'received', 'rejected'] as $s)
                                                <option value="{{ $s }}" {{ $donation->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                                        </svg>
                                        <p class="text-sm font-medium">Belum ada formulir donasi barang yang masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($donations->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection

