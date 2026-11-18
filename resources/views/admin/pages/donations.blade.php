@extends('admin.shared.Layout')

@section('title', 'Donasi Barang Masuk')

@section('content')
    <div class="space-y-5">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Daftar Donasi Barang Preloved Masuk</h2>
                <p class="text-sm text-gray-500 mt-0.5">Konfirmasi jadwal pickup, pengiriman, dan jumlah barang donasi nyata yang diterima.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 text-purple-700 text-xs font-semibold self-start sm:self-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                {{ $donations->total() }} total donasi
            </span>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        @endif

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
                            <th class="px-5 py-3.5 text-center">Jml Barang</th>
                            <th class="px-5 py-3.5">Deskripsi Barang</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
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
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <span class="font-bold text-gray-900 px-2.5 py-1 rounded-lg bg-gray-100 border border-gray-200">
                                        {{ $donation->item_count ?? 1 }} pcs
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
                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    <div class="flex flex-col items-end gap-1.5">
                                        {{-- Update Status & Koreksi Jumlah Barang --}}
                                        <form method="POST" action="{{ route('admin.donations.update-status', $donation) }}" class="flex items-center gap-1.5">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="item_count" value="{{ $donation->item_count ?? 1 }}" min="1" max="1000"
                                                class="w-14 px-2 py-1 rounded-lg border border-gray-200 text-xs text-gray-700 text-center font-semibold"
                                                title="Koreksi jumlah barang nyata yang diterima">
                                            <select name="status" class="text-xs rounded-lg border border-gray-200 px-2 py-1 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-primary">
                                                @foreach(['pending', 'confirmed', 'picked_up', 'received', 'rejected'] as $s)
                                                    <option value="{{ $s }}" {{ $donation->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="p-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition" title="Simpan status dan jumlah barang">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>

                                        {{-- Tombol Minta Ulasan via WhatsApp jika Received --}}
                                        @if($donation->status === 'received')
                                            @if($donation->review)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-500 border border-gray-200 text-[11px] font-semibold cursor-not-allowed whitespace-nowrap" title="Donasi ini sudah memberikan ulasan">
                                                    <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    Sudah diulas
                                                </span>
                                            @else
                                                @php
                                                    $waLink = \App\Support\ReviewRequestLink::forDonation($donation);
                                                @endphp
                                                @if($waLink)
                                                    <a href="{{ $waLink }}" target="_blank"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-semibold hover:bg-emerald-100 transition whitespace-nowrap"
                                                        title="Kirim tautan ulasan via WhatsApp">
                                                        <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0012.04 2z"/></svg>
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
                                <td colspan="9" class="px-5 py-12 text-center">
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
