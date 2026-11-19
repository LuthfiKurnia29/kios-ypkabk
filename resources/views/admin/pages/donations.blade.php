@extends('admin.shared.Layout')

@section('title', 'Donasi Masuk')

@section('content')
    <div class="space-y-5">

        {{-- Page Header --}}
        <div>
            <h2 class="text-lg font-bold text-gray-900">Donasi Barang Preloved Masuk</h2>
            <p class="text-sm text-gray-500 mt-0.5">Konfirmasi jadwal pickup, pengiriman, dan jumlah barang donasi nyata yang diterima.</p>
        </div>

        {{-- Export Controls --}}
        @include('admin.partials._export-controls', ['action' => route('admin.donations.export'), 'prefix' => 'donations'])

        {{-- Tabel Donasi --}}
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
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
                            <th class="px-5 py-3.5">Foto Barang</th>
                            <th class="px-5 py-3.5">Deskripsi Barang</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($donations as $donation)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap text-gray-500">
                                    {{ $donation->created_at->format('d M Y') }}<br>
                                    <span class="text-[11px] text-gray-400">{{ $donation->created_at->format('H:i') }}</span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-bold text-gray-900">
                                    {{ $donation->donor_name }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $donation->donor_phone) }}" target="_blank" class="text-emerald-700 hover:underline font-medium">
                                        {{ $donation->donor_phone }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-gray-700">
                                    {{ $donation->donor_city }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-gray-800 text-[11px] font-medium">
                                        {{ $donation->category?->name ?: ($donation->category_name ?: 'Umum') }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-center">
                                    <span class="font-medium text-gray-900">
                                        {{ $donation->item_count ?? 1 }} pcs
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $photos = $donation->item_photos_urls;
                                    @endphp
                                    @if(!empty($photos))
                                        <div class="flex items-center gap-1.5 flex-wrap max-w-[160px]">
                                            @foreach($photos as $index => $photoUrl)
                                                <a href="{{ $photoUrl }}" target="_blank" rel="noopener noreferrer"
                                                   class="block group relative w-10 h-10 rounded-lg overflow-hidden border border-gray-200 bg-gray-50 hover:ring-2 hover:ring-primary transition-all shrink-0"
                                                   title="Foto {{ $index + 1 }} - Klik untuk melihat ukuran penuh">
                                                    <img src="{{ $photoUrl }}" alt="Foto {{ $index + 1 }} {{ $donation->donor_name }}" class="w-full h-full object-cover">
                                                    <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-[9px] text-white font-semibold transition-opacity">
                                                        #{{ $index + 1 }}
                                                    </span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-[11px]">Tanpa foto</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-600 max-w-[200px] truncate" title="{{ $donation->description }}">
                                    {{ $donation->description ?: '-' }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="text-xs font-medium
                                        {{ $donation->status === 'received' ? 'text-emerald-700' :
                                           ($donation->status === 'rejected' ? 'text-rose-700' :
                                           ($donation->status === 'confirmed' ? 'text-blue-700' :
                                           ($donation->status === 'picked_up' ? 'text-indigo-700' : 'text-amber-700'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $donation->status)) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    <div class="flex flex-col items-end gap-1.5">
                                        {{-- Update Status & Koreksi Jumlah Barang --}}
                                        <form method="POST" action="{{ route('admin.donations.update-status', $donation) }}" class="flex items-center gap-1.5">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="item_count" value="{{ $donation->item_count ?? 1 }}" min="1" max="1000"
                                                class="w-14 px-2 py-1 rounded-md border border-gray-200 text-xs text-gray-700 text-center font-medium"
                                                title="Koreksi jumlah barang nyata yang diterima">
                                            <select name="status" class="text-xs rounded-md border border-gray-200 px-2 py-1 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-primary">
                                                @foreach(['pending', 'confirmed', 'picked_up', 'received', 'rejected'] as $s)
                                                    <option value="{{ $s }}" {{ $donation->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="px-2 py-1 rounded-md border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-xs font-medium transition" title="Simpan status dan jumlah barang">
                                                Simpan
                                            </button>
                                        </form>

                                        {{-- Tombol Minta Ulasan via WhatsApp jika Received --}}
                                        @if($donation->status === 'received')
                                            @if($donation->review)
                                                <span class="text-[11px] text-gray-500 font-medium">
                                                    Sudah diulas
                                                </span>
                                            @else
                                                @php
                                                    $waLink = \App\Support\ReviewRequestLink::forDonation($donation);
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
                                <td colspan="10" class="px-5 py-10 text-center text-sm text-gray-500">
                                    Belum ada formulir donasi barang yang masuk.
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
