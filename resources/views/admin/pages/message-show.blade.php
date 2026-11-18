@extends('admin.shared.Layout')

@section('title', 'Detail Pesan')

@section('content')
<div class="space-y-5">

    {{-- Alert Flash Message --}}
    @if(session('success'))
        <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
            <span class="font-medium">{{ session('success') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700" aria-label="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Back Link --}}
    <div>
        <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-gray-900 transition">
            &larr; Kembali ke Daftar Pesan Masuk
        </a>
    </div>

    {{-- Kartu Detail Pesan --}}
    <div class="bg-white rounded-lg border border-gray-200 p-6 sm:p-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-gray-100">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $message->name }}</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Diterima pada {{ $message->created_at->format('d F Y, H:i') }} WIB
                </p>
            </div>
            <div>
                @if($message->status === 'new')
                    <span class="text-xs font-semibold text-primary">
                        Pesan Baru
                    </span>
                @else
                    <span class="text-xs font-medium text-gray-500">
                        Sudah Dibaca {{ $message->read_at ? '(' . $message->read_at->format('d M Y H:i') . ')' : '' }}
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
            <div>
                <span class="text-xs font-semibold text-gray-400 block mb-1">Nama Pengirim</span>
                <span class="font-bold text-gray-900 text-base">{{ $message->name }}</span>
            </div>
            <div>
                <span class="text-xs font-semibold text-gray-400 block mb-1">Nomor WhatsApp</span>
                <span class="font-bold text-gray-900 text-base font-mono">{{ $message->phone }}</span>
            </div>
        </div>

        <div>
            <span class="text-xs font-semibold text-gray-400 block mb-2">Isi Pesan Lengkap</span>
            <div class="bg-gray-50 rounded-md p-5 text-sm text-gray-800 leading-relaxed whitespace-pre-wrap border border-gray-200">{{ $message->message }}</div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex flex-wrap items-center gap-3 pt-6 border-t border-gray-100">
            <a href="https://wa.me/{{ $message->waPhone() }}"
               target="_blank"
               rel="noopener noreferrer"
               class="inline-flex items-center px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition">
                Balas via WhatsApp
            </a>

            <form method="POST" action="{{ route('admin.messages.toggle-read', $message) }}" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="px-4 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold transition">
                    {{ $message->status === 'new' ? 'Tandai Dibaca' : 'Tandai Belum Dibaca' }}
                </button>
            </form>

            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" class="inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan dari {{ addslashes($message->name) }}?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-md text-rose-700 hover:bg-rose-50 text-sm font-semibold transition">
                    Hapus Pesan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
