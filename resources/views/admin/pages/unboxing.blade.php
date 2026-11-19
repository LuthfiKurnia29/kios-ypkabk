@extends('admin.shared.Layout')

@section('title', 'Galeri Unboxing')

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

    @if(session('error'))
        <div class="p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between">
            <span class="font-medium">{{ session('error') }}</span>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700" aria-label="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Galeri Unboxing Donasi</h2>
            <p class="text-sm text-gray-500 mt-0.5">Kelola dokumentasi video pembukaan paket donasi dari YouTube dan Instagram.</p>
        </div>
        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <a href="{{ route('unboxing') }}" target="_blank"
               class="inline-flex items-center px-3.5 py-2 rounded-md border border-gray-200 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50 transition shadow-xs">
                Lihat Halaman Publik
            </a>
            <button type="button" onclick="openModal('modal-tambah')"
                class="inline-flex items-center px-4 py-2 rounded-md bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition shadow-xs min-h-[40px]">
                Tambah Galeri
            </button>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500">Total Dokumentasi</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalCount }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500">Video YouTube</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $youtubeCount }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500">Reel Instagram</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $instagramCount }}</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <p class="text-xs font-semibold text-gray-500">Tampil di Galeri</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $visibleCount }}</p>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <form method="GET" action="{{ route('admin.unboxing.index') }}" class="bg-white rounded-lg border border-gray-200 p-4 flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[220px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Cari Nama Barang / Judul</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang atau keterangan..."
                class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
        </div>
        <div class="min-w-[140px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Platform</label>
            <select name="platform" class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                <option value="">Semua Platform</option>
                <option value="youtube" {{ request('platform') === 'youtube' ? 'selected' : '' }}>YouTube</option>
                <option value="instagram" {{ request('platform') === 'instagram' ? 'selected' : '' }}>Instagram</option>
            </select>
        </div>
        <div class="min-w-[140px]">
            <label class="block text-xs font-semibold text-gray-500 mb-1">Status Publikasi</label>
            <select name="is_visible" class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_visible') === '1' ? 'selected' : '' }}>Tampil di Publik</option>
                <option value="0" {{ request('is_visible') === '0' ? 'selected' : '' }}>Disembunyikan</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 rounded-md bg-gray-900 text-white text-sm font-semibold hover:bg-gray-700 transition">Cari</button>
            @if(request()->hasAny(['search', 'platform', 'is_visible']))
                <a href="{{ route('admin.unboxing.index') }}" class="px-4 py-2 rounded-md bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition">Reset</a>
            @endif
        </div>
    </form>

    {{-- Unboxing Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <p class="text-xs font-semibold text-gray-500">{{ $unboxings->total() }} video dokumentasi terdaftar</p>
        </div>

        @if($unboxings->isEmpty())
            <div class="p-8 text-center">
                <p class="text-sm text-gray-500">Belum ada video unboxing.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-100">
                    <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5 w-12">#</th>
                            <th class="px-5 py-3.5">Pratinjau & Nama Barang</th>
                            <th class="px-5 py-3.5">Platform & Tautan</th>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($unboxings as $index => $item)
                            @php
                                $isYt = ($item->platform === 'youtube');
                                $ytId = $item->youtube_id;
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-5 py-4 font-mono text-gray-400">
                                    {{ $unboxings->firstItem() + $index }}
                                </td>

                                {{-- Pratinjau & Nama Barang --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-20 h-12 rounded-md overflow-hidden bg-gray-100 shrink-0 relative border border-gray-200">
                                            @if($isYt && $ytId)
                                                <img src="https://img.youtube.com/vi/{{ $ytId }}/hqdefault.jpg"
                                                     alt="{{ $item->title }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex flex-col items-center justify-center bg-gray-50 text-gray-600 text-xs font-medium">
                                                    Video
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-900 text-sm line-clamp-1" title="{{ $item->title }}">
                                                {{ $item->title }}
                                            </p>
                                            @if($item->description)
                                                <p class="text-[11px] text-gray-500 line-clamp-1 mt-0.5">
                                                    {{ $item->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Platform & Tautan --}}
                                <td class="px-5 py-4">
                                    <div class="space-y-1">
                                        <span class="text-[11px] font-semibold text-gray-700">
                                            {{ $isYt ? 'YouTube' : 'Instagram' }}
                                        </span>
                                        <div>
                                            <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                                               class="text-[11px] text-primary hover:underline inline-flex items-center gap-1 font-mono max-w-[200px] truncate"
                                               title="{{ $item->url }}">
                                                <span class="truncate">{{ $item->url }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tanggal --}}
                                <td class="px-5 py-4 whitespace-nowrap text-gray-600">
                                    {{ $item->recorded_at ? $item->recorded_at->format('d M Y') : '-' }}
                                </td>

                                {{-- Status Tampil --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <form method="POST" action="{{ route('admin.unboxing.toggle-visibility', $item->id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                title="Klik untuk mengubah visibilitas"
                                                class="text-[11px] font-medium transition {{ $item->is_visible ? 'text-emerald-700 hover:text-emerald-900' : 'text-gray-500 hover:text-gray-700' }}">
                                            {{ $item->is_visible ? 'Tampil' : 'Disembunyikan' }}
                                        </button>
                                    </form>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <button type="button"
                                            onclick="openEditUnboxingModal(
                                                {{ $item->id }},
                                                @js($item->title),
                                                @js($item->url),
                                                @js($item->platform),
                                                @js($item->recorded_at ? $item->recorded_at->format('Y-m-d') : ''),
                                                @js($item->description),
                                                {{ $item->is_visible ? 'true' : 'false' }}
                                            )"
                                            class="px-2.5 py-1 rounded-md text-blue-700 text-xs font-semibold hover:bg-blue-50 transition">
                                            Edit
                                        </button>
                                        <button type="button"
                                            onclick="openDeleteUnboxingModal({{ $item->id }}, @js($item->title))"
                                            class="px-2.5 py-1 rounded-md text-rose-700 text-xs font-semibold hover:bg-rose-50 transition">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($unboxings->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $unboxings->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

{{-- ======= MODAL TAMBAH DOKUMENTASI UNBOXING ======= --}}
<div id="modal-tambah" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="modal-tambah-title">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-tambah')"></div>
    <div class="relative bg-white rounded-lg shadow-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div>
                <h3 id="modal-tambah-title" class="text-base font-bold text-gray-900">Tambah Galeri Unboxing</h3>
                <p class="text-xs text-gray-500 mt-0.5">Dokumentasikan penerimaan paket barang donasi dari YouTube atau Instagram.</p>
            </div>
            <button type="button" onclick="closeModal('modal-tambah')" class="p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.unboxing.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            {{-- 1. Input Nama Barang --}}
            <div>
                <label for="tambah-title" class="block text-xs font-semibold text-gray-700 mb-1">
                    Nama Barang / Judul Unboxing <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="title"
                       id="tambah-title"
                       value="{{ old('title') }}"
                       required
                       maxlength="255"
                       placeholder="Contoh: Paket Pakaian Balita Bunda Melati / Mainan Sensorik Balok Kayu"
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition @error('title') border-rose-500 @enderror">
                @error('title')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-gray-400 mt-1">Nama barang yang dibuka atau judul dokumentasi paket donasi.</p>
            </div>

            {{-- 2. Input Link YouTube / Instagram --}}
            <div>
                <label for="tambah-url" class="block text-xs font-semibold text-gray-700 mb-1">
                    Link YouTube / Instagram <span class="text-rose-500">*</span>
                </label>
                <input type="url"
                       name="url"
                       id="tambah-url"
                       value="{{ old('url') }}"
                       required
                       maxlength="500"
                       oninput="handleUrlInput(this.value, 'tambah')"
                       placeholder="https://www.youtube.com/watch?v=... atau https://www.instagram.com/reel/..."
                       class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition font-mono @error('url') border-rose-500 @enderror">
                @error('url')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-gray-400 mt-1">Mendukung link video YouTube, YouTube Shorts, atau postingan Instagram Reel.</p>
            </div>

            {{-- Live Thumbnail / Platform Detector --}}
            <div id="tambah-preview-container" class="hidden p-3 rounded-xl bg-gray-50 border border-gray-200 flex items-center gap-3">
                <div id="tambah-preview-thumb" class="w-20 h-12 rounded-lg bg-gray-200 shrink-0 overflow-hidden flex items-center justify-center">
                    {{-- Diisi secara dinamis via JS --}}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span id="tambah-preview-badge" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"></span>
                        <span class="text-xs text-gray-500">Tautan Terdeteksi</span>
                    </div>
                    <p id="tambah-preview-text" class="text-xs text-gray-700 truncate mt-0.5 font-medium"></p>
                </div>
            </div>

            {{-- 3. Platform & Tanggal Dokumentasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="tambah-platform" class="block text-xs font-semibold text-gray-700 mb-1">Platform Video</label>
                    <select name="platform" id="tambah-platform"
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                        <option value="">Deteksi Otomatis</option>
                        <option value="youtube" {{ old('platform') === 'youtube' ? 'selected' : '' }}>YouTube</option>
                        <option value="instagram" {{ old('platform') === 'instagram' ? 'selected' : '' }}>Instagram</option>
                    </select>
                </div>
                <div>
                    <label for="tambah-recorded-at" class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Dokumentasi</label>
                    <input type="date"
                           name="recorded_at"
                           id="tambah-recorded-at"
                           value="{{ old('recorded_at', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                </div>
            </div>

            {{-- 4. Deskripsi / Catatan Tambahan --}}
            <div>
                <label for="tambah-description" class="block text-xs font-semibold text-gray-700 mb-1">Keterangan / Catatan Tambahan (Opsional)</label>
                <textarea name="description"
                          id="tambah-description"
                          rows="2"
                          maxlength="1000"
                          placeholder="Keterangan singkat isi barang atau nama donatur (jika ada)..."
                          class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none">{{ old('description') }}</textarea>
            </div>

            {{-- 5. Status Publikasi --}}
            <div class="flex items-center gap-3 p-3 rounded-md bg-gray-50 border border-gray-200">
                <input type="hidden" name="is_visible" value="0">
                <input type="checkbox"
                       name="is_visible"
                       id="tambah-is-visible"
                       value="1"
                       {{ old('is_visible', '1') == '1' ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/30 cursor-pointer">
                <label for="tambah-is-visible" class="text-xs font-semibold text-gray-700 cursor-pointer select-none">
                    Tampilkan langsung di Galeri Unboxing publik
                </label>
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex-1 py-2 rounded-md bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition shadow-xs">
                    Simpan Video Unboxing
                </button>
                <button type="button" onclick="closeModal('modal-tambah')" class="px-5 py-2 rounded-md bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======= MODAL EDIT DOKUMENTASI UNBOXING ======= --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="modal-edit-title">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-edit')"></div>
    <div class="relative bg-white rounded-lg shadow-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div>
                <h3 id="modal-edit-title" class="text-base font-bold text-gray-900">Edit Galeri Unboxing</h3>
                <p class="text-xs text-gray-500 mt-0.5">Perbarui informasi barang dan tautan video unboxing.</p>
            </div>
            <button type="button" onclick="closeModal('modal-edit')" class="p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="form-edit-unboxing" method="POST" action="" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            {{-- 1. Input Nama Barang --}}
            <div>
                <label for="edit-title" class="block text-xs font-semibold text-gray-700 mb-1">
                    Nama Barang / Judul Unboxing <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       name="title"
                       id="edit-title"
                       required
                       maxlength="255"
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition">
            </div>

            {{-- 2. Input Link YouTube / Instagram --}}
            <div>
                <label for="edit-url" class="block text-xs font-semibold text-gray-700 mb-1">
                    Link YouTube / Instagram <span class="text-rose-500">*</span>
                </label>
                <input type="url"
                       name="url"
                       id="edit-url"
                       required
                       maxlength="500"
                       oninput="handleUrlInput(this.value, 'edit')"
                       class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition font-mono">
            </div>

            {{-- Live Thumbnail / Platform Detector --}}
            <div id="edit-preview-container" class="hidden p-3 rounded-md bg-gray-50 border border-gray-200 flex items-center gap-3">
                <div id="edit-preview-thumb" class="w-20 h-12 rounded bg-gray-200 shrink-0 overflow-hidden flex items-center justify-center"></div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span id="edit-preview-badge" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider"></span>
                        <span class="text-xs text-gray-500">Tautan Terdeteksi</span>
                    </div>
                    <p id="edit-preview-text" class="text-xs text-gray-700 truncate mt-0.5 font-medium"></p>
                </div>
            </div>

            {{-- 3. Platform & Tanggal Dokumentasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="edit-platform" class="block text-xs font-semibold text-gray-700 mb-1">Platform Video</label>
                    <select name="platform" id="edit-platform"
                        class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                        <option value="">Deteksi Otomatis</option>
                        <option value="youtube">YouTube</option>
                        <option value="instagram">Instagram</option>
                    </select>
                </div>
                <div>
                    <label for="edit-recorded-at" class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Dokumentasi</label>
                    <input type="date"
                           name="recorded_at"
                           id="edit-recorded-at"
                           class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition bg-white">
                </div>
            </div>

            {{-- 4. Deskripsi / Catatan Tambahan --}}
            <div>
                <label for="edit-description" class="block text-xs font-semibold text-gray-700 mb-1">Keterangan / Catatan Tambahan (Opsional)</label>
                <textarea name="description"
                          id="edit-description"
                          rows="2"
                          maxlength="1000"
                          class="w-full px-3 py-2 rounded-md border border-gray-200 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition resize-none"></textarea>
            </div>

            {{-- 5. Status Publikasi --}}
            <div class="flex items-center gap-3 p-3 rounded-md bg-gray-50 border border-gray-200">
                <input type="hidden" name="is_visible" value="0">
                <input type="checkbox"
                       name="is_visible"
                       id="edit-is-visible"
                       value="1"
                       class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/30 cursor-pointer">
                <label for="edit-is-visible" class="text-xs font-semibold text-gray-700 cursor-pointer select-none">
                    Tampilkan di Galeri Unboxing publik
                </label>
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-2 flex gap-3">
                <button type="submit" class="flex-1 py-2 rounded-md bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition shadow-xs">
                    Simpan Perubahan
                </button>
                <button type="button" onclick="closeModal('modal-edit')" class="px-5 py-2 rounded-md bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ======= MODAL HAPUS DOKUMENTASI UNBOXING ======= --}}
<div id="modal-hapus" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="modal-hapus-title">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal('modal-hapus')"></div>
    <div class="relative bg-white rounded-lg shadow-lg w-full max-w-sm p-6">
        <div class="text-center space-y-3">
            <h3 id="modal-hapus-title" class="text-base font-bold text-gray-900">Hapus Video Unboxing?</h3>
            <p class="text-sm text-gray-500">
                Dokumentasi "<span id="hapus-nama-barang" class="font-semibold text-gray-800"></span>" akan dihapus secara permanen dari galeri.
            </p>
        </div>
        <form id="form-hapus-unboxing" method="POST" action="" class="mt-5 flex gap-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex-1 py-2 rounded-md bg-rose-600 text-white font-semibold text-sm hover:bg-rose-700 transition shadow-xs">
                Ya, Hapus
            </button>
            <button type="button" onclick="closeModal('modal-hapus')" class="flex-1 py-2 rounded-md bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition">
                Batal
            </button>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function extractYoutubeId(url) {
        if (!url) return null;
        const regExp = /(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i;
        const match = url.match(regExp);
        return match ? match[1] : null;
    }

    function handleUrlInput(url, prefix) {
        const container = document.getElementById(prefix + '-preview-container');
        const thumb = document.getElementById(prefix + '-preview-thumb');
        const badge = document.getElementById(prefix + '-preview-badge');
        const text = document.getElementById(prefix + '-preview-text');
        const platformSelect = document.getElementById(prefix + '-platform');

        if (!url || url.trim().length === 0) {
            if (container) container.classList.add('hidden');
            return;
        }

        const ytId = extractYoutubeId(url);
        const isInstagram = /instagram\.com/i.test(url);

        if (ytId) {
            if (container) container.classList.remove('hidden');
            if (badge) {
                badge.className = 'px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-700';
                badge.textContent = 'YouTube';
            }
            if (text) text.textContent = 'Video ID: ' + ytId;
            if (thumb) {
                thumb.innerHTML = `<img src="https://img.youtube.com/vi/${ytId}/hqdefault.jpg" class="w-full h-full object-cover" alt="Pratinjau YouTube">`;
            }
            if (platformSelect && !platformSelect.value) {
                platformSelect.value = 'youtube';
            }
        } else if (isInstagram) {
            if (container) container.classList.remove('hidden');
            if (badge) {
                badge.className = 'px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-pink-100 text-pink-700';
                badge.textContent = 'Instagram';
            }
            if (text) text.textContent = 'Postingan / Reel Instagram';
            if (thumb) {
                thumb.innerHTML = `<div class="w-full h-full bg-pink-50 flex items-center justify-center text-primary font-bold text-[10px]">IG Reel</div>`;
            }
            if (platformSelect && !platformSelect.value) {
                platformSelect.value = 'instagram';
            }
        } else {
            if (container) container.classList.add('hidden');
        }
    }

    function openEditUnboxingModal(id, title, url, platform, recordedAt, description, isVisible) {
        const form = document.getElementById('form-edit-unboxing');
        form.action = '/admin/unboxing/' + id;

        document.getElementById('edit-title').value = title || '';
        document.getElementById('edit-url').value = url || '';
        document.getElementById('edit-platform').value = platform || '';
        document.getElementById('edit-recorded-at').value = recordedAt || '';
        document.getElementById('edit-description').value = description || '';
        document.getElementById('edit-is-visible').checked = !!isVisible;

        handleUrlInput(url, 'edit');
        openModal('modal-edit');
    }

    function openDeleteUnboxingModal(id, title) {
        document.getElementById('form-hapus-unboxing').action = '/admin/unboxing/' + id;
        document.getElementById('hapus-nama-barang').textContent = title;
        openModal('modal-hapus');
    }

    // Auto-open modal jika ada validation errors
    @if($errors->any() && old('_method') === null)
        openModal('modal-tambah');
        handleUrlInput(@js(old('url')), 'tambah');
    @elseif($errors->any() && old('_method') === 'PUT')
        openModal('modal-edit');
        handleUrlInput(@js(old('url')), 'edit');
    @endif
</script>
@endsection
