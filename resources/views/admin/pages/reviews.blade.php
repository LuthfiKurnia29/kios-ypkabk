@extends('admin.shared.Layout')

@section('title', 'Ulasan & Testimoni')

@section('content')
<div class="space-y-6">

    {{-- Warning: Active Reviews > 6 --}}
    @if($hasMoreThanSixActive)
        <div class="p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-sm flex items-start gap-3">
            <div>
                <p class="font-bold">Perhatian: Lebih dari 6 ulasan diaktifkan ({{ $counts['active'] }} ulasan aktif)</p>
                <p class="text-xs text-amber-800 mt-0.5">Seksi "Cerita dari Sahabat Kios" di beranda publik hanya akan menampilkan maksimal <strong>6 ulasan teratas</strong> (berdasarkan nomor urut terkecil). Ulasan aktif selebihnya tidak akan terlihat di beranda.</p>
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Ulasan & Testimoni</h2>
            <p class="text-sm text-gray-500 mt-0.5">Kurasi ulasan nyata dari pembeli dan donatur terverifikasi. Demi menjaga keaslian data, isi ulasan tidak dapat diedit.</p>
        </div>
    </div>

    {{-- Tab Filter & Counts --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-gray-200 pb-3">
        <a href="{{ route('admin.reviews.index', ['tab' => 'all']) }}"
            class="px-3.5 py-1.5 rounded-md text-xs font-semibold transition inline-flex items-center gap-2 {{ $tab === 'all' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Semua Ulasan
            <span>({{ $counts['all'] }})</span>
        </a>
        <a href="{{ route('admin.reviews.index', ['tab' => 'pending']) }}"
            class="px-3.5 py-1.5 rounded-md text-xs font-semibold transition inline-flex items-center gap-2 {{ $tab === 'pending' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Menunggu Persetujuan
            <span>({{ $counts['pending'] }})</span>
        </a>
        <a href="{{ route('admin.reviews.index', ['tab' => 'active']) }}"
            class="px-3.5 py-1.5 rounded-md text-xs font-semibold transition inline-flex items-center gap-2 {{ $tab === 'active' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
            Tampil di Beranda
            <span>({{ $counts['active'] }})</span>
        </a>
    </div>

    {{-- Reviews Table --}}
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <p class="text-xs font-semibold text-gray-500">{{ $reviews->count() }} ulasan ditampilkan</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-100">
                <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5 w-24">Urutan</th>
                        <th class="px-5 py-3.5">Pengulas & Sumber</th>
                        <th class="px-5 py-3.5">Kode Transaksi</th>
                        <th class="px-5 py-3.5">Isi Ulasan (Quote)</th>
                        <th class="px-5 py-3.5">Tanggal</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50 transition-colors">
                            {{-- Urutan with quick update form --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.reviews.order', $review) }}" class="flex items-center gap-1.5">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="order" value="{{ $review->order }}" min="0"
                                        class="w-14 px-2 py-1 rounded-md border border-gray-200 text-xs font-bold text-gray-800 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-center">
                                    <button type="submit" class="px-2 py-1 text-xs rounded-md bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                                        Simpan
                                    </button>
                                </form>
                            </td>

                            {{-- Pengulas & Sumber --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-md bg-gray-100 text-gray-800 font-bold text-xs flex items-center justify-center shrink-0 border border-gray-200">
                                        {{ $review->initials ?: substr($review->name, 0, 2) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 text-sm truncate">{{ $review->name }}</p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            @if($review->source === 'buyer')
                                                <span class="text-[10px] font-semibold text-blue-700">
                                                    Pembeli
                                                </span>
                                            @elseif($review->source === 'donor')
                                                <span class="text-[10px] font-semibold text-pink-700">
                                                    Donatur
                                                </span>
                                            @else
                                                <span class="text-[10px] font-semibold text-gray-600">
                                                    {{ $review->role ?: 'Sahabat Kios' }}
                                                </span>
                                            @endif
                                            <span class="text-[11px] text-gray-400">({{ $review->display_name }})</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kode Transaksi --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($review->transaction_code)
                                    <span class="font-mono text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-800 border border-gray-200">
                                        {{ $review->transaction_code }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Tanpa kode</span>
                                @endif
                            </td>

                            {{-- Isi Ulasan --}}
                            <td class="px-5 py-4">
                                <p class="text-gray-700 text-xs italic line-clamp-3 max-w-sm">
                                    &ldquo;{{ $review->quote }}&rdquo;
                                </p>
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-500">
                                {{ $review->created_at ? $review->created_at->format('d M Y') : '-' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($review->is_active)
                                    <span class="text-[11px] font-medium text-emerald-700">
                                        Tampil di Beranda
                                    </span>
                                @else
                                    <span class="text-[11px] font-medium text-amber-700">
                                        Menunggu Persetujuan
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Toggle Aktif Button --}}
                                    <form method="POST" action="{{ route('admin.reviews.toggle', $review) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($review->is_active)
                                            <button type="submit" class="px-2.5 py-1 rounded-md text-gray-600 text-xs font-semibold hover:bg-gray-100 transition" title="Sembunyikan dari beranda">
                                                Sembunyikan
                                            </button>
                                        @else
                                            <button type="submit" class="px-2.5 py-1 rounded-md text-emerald-700 text-xs font-semibold hover:bg-emerald-50 transition" title="Setujui dan tampilkan di beranda">
                                                Setujui
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Hapus Button (Spam Curation) --}}
                                    <button type="button"
                                        onclick="openDeleteReviewModal({{ $review->id }}, {{ json_encode($review->name) }})"
                                        class="px-2.5 py-1 rounded-md text-rose-700 text-xs font-semibold hover:bg-rose-50 transition"
                                        title="Hapus ulasan spam/tidak pantas">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-sm text-gray-500">
                                Belum ada ulasan untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ======= MODAL HAPUS REVIEW ======= --}}
<div id="modal-hapus-review" class="fixed inset-0 z-50 hidden items-center justify-center p-4" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black/50" onclick="closeReviewModal('modal-hapus-review')"></div>
    <div class="relative bg-white rounded-lg shadow-lg w-full max-w-sm p-6">
        <div class="text-center space-y-3">
            <h3 class="text-base font-bold text-gray-900">Hapus Ulasan?</h3>
            <p class="text-sm text-gray-500">Ulasan dari "<span id="hapus-nama-review" class="font-semibold text-gray-800"></span>" akan dihapus permanen dari sistem.</p>
        </div>
        <form id="form-hapus-review" method="POST" action="" class="mt-5 flex gap-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex-1 py-2 rounded-md bg-rose-600 text-white font-semibold text-sm hover:bg-rose-700 transition">Ya, Hapus</button>
            <button type="button" onclick="closeReviewModal('modal-hapus-review')" class="flex-1 py-2 rounded-md bg-gray-100 text-gray-700 font-semibold text-sm hover:bg-gray-200 transition">Batal</button>
        </form>
    </div>
</div>

<script>
    function openReviewModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeReviewModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    function openDeleteReviewModal(id, name) {
        document.getElementById('form-hapus-review').action = '/admin/reviews/' + id;
        document.getElementById('hapus-nama-review').textContent = name;
        openReviewModal('modal-hapus-review');
    }
</script>
@endsection
