@extends('admin.shared.Layout')

@section('title', 'Pesan Masuk')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Pesan Masuk</h2>
            <p class="text-sm text-gray-500 mt-0.5">Pesan pertanyaan, konsultasi, dan rencana donasi dari masyarakat via website.</p>
        </div>
        <div class="flex items-center gap-2">
            @if($newCount > 0)
                <span class="px-2.5 py-1 rounded-md text-primary text-xs font-semibold">
                    {{ $newCount }} pesan baru
                </span>
            @endif
            <span class="px-2.5 py-1 rounded-md text-gray-600 text-xs font-medium">
                {{ $messages->total() }} total pesan
            </span>
        </div>
    </div>

    {{-- Tabel Pesan Masuk --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-200">
                <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Nama</th>
                        <th class="px-5 py-3.5">No. WhatsApp</th>
                        <th class="px-5 py-3.5">Cuplikan Pesan</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 font-medium">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap text-gray-400">
                                {{ $msg->created_at->format('d M Y') }}<br>
                                <span class="text-[11px]">{{ $msg->created_at->format('H:i') }} WIB</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-semibold text-gray-900">
                                {{ $msg->name }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-gray-600 font-mono">
                                {{ $msg->phone }}
                            </td>
                            <td class="px-5 py-4 text-gray-600 max-w-xs">
                                <span class="line-clamp-2">{{ Str::limit($msg->message, 80) }}</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($msg->status === 'new')
                                    <span class="text-[11px] font-semibold text-primary">
                                        Baru
                                    </span>
                                @else
                                    <span class="text-[11px] font-medium text-gray-500">
                                        Dibaca
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.messages.show', $msg) }}"
                                       class="px-2.5 py-1 rounded-md text-blue-700 text-xs font-semibold hover:bg-blue-50 transition">
                                        Detail
                                    </a>
                                    <a href="https://wa.me/{{ $msg->waPhone() }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="px-2.5 py-1 rounded-md text-emerald-700 text-xs font-semibold hover:bg-emerald-50 transition">
                                        Balas WA
                                    </a>
                                    <form method="POST" action="{{ route('admin.messages.toggle-read', $msg) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 rounded-md text-gray-700 text-xs font-semibold hover:bg-gray-100 transition">
                                            {{ $msg->status === 'new' ? 'Tandai Dibaca' : 'Tandai Baru' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" class="inline"
                                          onsubmit="return confirm('Hapus pesan dari {{ addslashes($msg->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-md text-rose-700 text-xs font-semibold hover:bg-rose-50 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-500">
                                Belum ada pesan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $messages->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
