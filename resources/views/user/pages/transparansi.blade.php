@extends('user.shared.Layout')

@section('content')
<div class="space-y-12 lg:space-y-16 pb-20 pt-24">

    {{-- Header Banner Transparansi --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-hero-gradient rounded-3xl p-8 sm:p-12 border border-ink/10 space-y-4 text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-butter border border-ink/15 text-ink text-xs font-semibold">
                <span>📊</span> Transparansi &amp; Akuntabilitas Publik (FR-04)
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-ink tracking-tight leading-tight">
                Laporan Aliran Dana &amp; Donasi Masuk
            </h1>
            <p class="text-sm sm:text-base text-ink/80 leading-relaxed max-w-2xl mx-auto">
                Setiap rupiah dari pembelian etalase dan setiap barang preloved yang dititipkan dicatat secara terbuka untuk menjaga amanah donatur dan pembeli.
            </p>
        </div>
    </section>

    {{-- Ringkasan Dampak & Angka Akuntabilitas --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @if(isset($impactMetrics) && $impactMetrics->count() > 0)
                @foreach($impactMetrics as $metric)
                    <div class="bg-white rounded-2xl p-6 border border-ink/15 shadow-xs space-y-2">
                        <p class="text-xs font-bold text-ink/60 uppercase tracking-wider">{{ $metric->label }}</p>
                        <p class="font-heading text-3xl sm:text-4xl font-extrabold text-ink">{{ $metric->value }}</p>
                        <p class="text-xs text-ink/70">{{ $metric->description }}</p>
                    </div>
                @endforeach
            @else
                <div class="bg-white rounded-2xl p-6 border border-ink/15 shadow-xs space-y-2">
                    <p class="text-xs font-bold text-ink/60 uppercase tracking-wider">Barang Preloved Tersalurkan</p>
                    <p class="font-heading text-3xl sm:text-4xl font-extrabold text-ink">1.420+</p>
                    <p class="text-xs text-ink/70">Dari donatur Sidoarjo &amp; Mojokerto</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-ink/15 shadow-xs space-y-2">
                    <p class="text-xs font-bold text-ink/60 uppercase tracking-wider">Siswa Terlatih Mandiri</p>
                    <p class="font-heading text-3xl sm:text-4xl font-extrabold text-ink">38 Anak</p>
                    <p class="text-xs text-ink/70">Kelas vokasi, sensori &amp; kriya</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-ink/15 shadow-xs space-y-2">
                    <p class="text-xs font-bold text-ink/60 uppercase tracking-wider">Dana Terapi Terkumpul</p>
                    <p class="font-heading text-3xl sm:text-4xl font-extrabold text-ink">Rp 84,5 Jt</p>
                    <p class="text-xs text-ink/70">100% dialokasikan secara transparan</p>
                </div>
            @endif
        </div>
    </section>

    {{-- Galeri Video Unboxing & Dokumentasi Penerimaan (SRS 2.1 & 2.2) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-heading text-2xl sm:text-3xl font-bold text-ink">
                    Dokumentasi Penerimaan &amp; Unboxing Donasi
                </h2>
                <p class="text-xs sm:text-sm text-ink/70">
                    Bukti video unboxing paket donasi dari para sahabat Kios Mandiri di seluruh Indonesia.
                </p>
            </div>
            <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs self-start sm:self-auto transition-colors">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                <span>Lihat YouTube YPKABK</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-cream rounded-2xl p-4 border border-ink/10 space-y-3 shadow-xs">
                <div class="aspect-video rounded-xl overflow-hidden bg-butter relative flex items-center justify-center group">
                    <img src="{{ asset('images/books.jpg') }}" alt="Unboxing Donasi Buku" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/50 transition-colors">
                        <span class="w-12 h-12 rounded-full bg-white/90 text-red-600 flex items-center justify-center shadow-lg font-bold">▶</span>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-ink line-clamp-1">Unboxing 5 Kardus Ensiklopedia &amp; Buku Cerita Anak</h3>
                    <p class="text-xs text-ink/60">Kiriman dari Sahabat Donatur di Jakarta Selatan</p>
                </div>
            </div>

            <div class="bg-cream rounded-2xl p-4 border border-ink/10 space-y-3 shadow-xs">
                <div class="aspect-video rounded-xl overflow-hidden bg-butter relative flex items-center justify-center group">
                    <img src="{{ asset('images/denim-jacket.jpg') }}" alt="Unboxing Pakaian Anak" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/50 transition-colors">
                        <span class="w-12 h-12 rounded-full bg-white/90 text-red-600 flex items-center justify-center shadow-lg font-bold">▶</span>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-ink line-clamp-1">Sortir &amp; Sanitasi Pakaian Balita Layak Pakai</h3>
                    <p class="text-xs text-ink/60">Kiriman dari Komunitas Ibu Ceria Surabaya</p>
                </div>
            </div>

            <div class="bg-cream rounded-2xl p-4 border border-ink/10 space-y-3 shadow-xs">
                <div class="aspect-video rounded-xl overflow-hidden bg-butter relative flex items-center justify-center group">
                    <img src="{{ asset('images/kegiatan/kegiatan-grup.webp') }}" alt="Kegiatan Vokasi Kios" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/50 transition-colors">
                        <span class="w-12 h-12 rounded-full bg-white/90 text-red-600 flex items-center justify-center shadow-lg font-bold">▶</span>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-sm text-ink line-clamp-1">Kelas Pengemasan Ramah Lingkungan Siswa Vokasi</h3>
                    <p class="text-xs text-ink/60">Sentra YPKABK Sepanjang Sidoarjo</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Tabel Terbuka: Transaksi Penjualan & Donasi Barang (FR-04) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        {{-- Bagian 1: Transaksi Penjualan Katalog (Penyaluran Dana Terapi) --}}
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="font-heading text-xl sm:text-2xl font-bold text-ink">
                        Tabel Transaksi Penjualan Barang (Aliran Dana Terbuka)
                    </h2>
                    <p class="text-xs text-ink/70">
                        *Identitas pembeli disamarkan untuk menjaga privasi (NFR-02).
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold self-start sm:self-auto">
                    <span>🟢</span> Data Real-Time
                </span>
            </div>

            <div class="bg-white rounded-2xl border border-ink/15 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-ink divide-y divide-ink/10">
                        <thead class="bg-cream font-bold text-ink/75 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Kode Order</th>
                                <th class="px-4 py-3">Pembeli</th>
                                <th class="px-4 py-3">Item Produk</th>
                                <th class="px-4 py-3">Total Donasi</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ink/5 font-medium">
                            @forelse($recentPurchases ?? [] as $purchase)
                                @php
                                    // Masking nama: "Budi Santoso" -> "Budi S."
                                    $parts = explode(' ', trim($purchase->customer_name ?? 'Sahabat Kios'));
                                    $maskedName = count($parts) > 1 ? $parts[0] . ' ' . substr($parts[1], 0, 1) . '.' : $parts[0];
                                @endphp
                                <tr class="hover:bg-cream/40 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-ink/70">
                                        {{ $purchase->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap font-mono font-bold text-ink">
                                        {{ $purchase->order_code }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ $maskedName }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @foreach($purchase->detailPurchases as $item)
                                            <span class="inline-block bg-cream px-2 py-0.5 rounded text-[11px] mr-1 my-0.5">
                                                {{ $item->product?->name ?? 'Produk' }} (x{{ $item->quantity }})
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap font-bold text-emerald-700">
                                        Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $purchase->status === 'paid' || $purchase->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ strtoupper($purchase->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-ink/60">
                                        Belum ada transaksi tercatat minggu ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Bagian 2: Donasi Barang Preloved Masuk --}}
        <div class="space-y-4 pt-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h2 class="font-heading text-xl sm:text-2xl font-bold text-ink">
                        Penerimaan Donasi Barang Preloved Masuk
                    </h2>
                    <p class="text-xs text-ink/70">
                        Catatan barang donasi yang telah dikonfirmasi dan diterima di sentra Kios Mandiri.
                    </p>
                </div>
                <button type="button" onclick="openDonationModal()" class="px-4 py-2 rounded-xl bg-sun hover:bg-sun/90 text-ink font-bold text-xs transition-colors shadow-xs self-start sm:self-auto">
                    + Formulir Donasi Barang
                </button>
            </div>

            <div class="bg-white rounded-2xl border border-ink/15 overflow-hidden shadow-xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-ink divide-y divide-ink/10">
                        <thead class="bg-cream font-bold text-ink/75 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Tanggal Masuk</th>
                                <th class="px-4 py-3">Nama Donatur</th>
                                <th class="px-4 py-3">Kota Asal</th>
                                <th class="px-4 py-3">Kategori Barang</th>
                                <th class="px-4 py-3">Deskripsi Singkat</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ink/5 font-medium">
                            @forelse($recentDonations ?? [] as $donation)
                                @php
                                    $parts = explode(' ', trim($donation->donor_name ?? 'Donatur'));
                                    $maskedDonor = count($parts) > 1 ? $parts[0] . ' ' . substr($parts[1], 0, 1) . '.' : $parts[0];
                                @endphp
                                <tr class="hover:bg-cream/40 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap text-ink/70">
                                        {{ $donation->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap font-bold">
                                        {{ $maskedDonor }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ $donation->donor_city }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded bg-butter text-ink text-[11px] font-semibold">
                                            {{ $donation->category?->name ?: ($donation->category_name ?: 'Donasi Umum') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-ink/75 max-w-xs truncate" title="{{ $donation->description }}">
                                        {{ $donation->description ?: 'Barang layak pakai' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            {{ strtoupper($donation->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-ink/60">
                                        Belum ada data donasi tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>

@include('user.pages.home._modal-donasi')
@include('user.pages.home._scripts')
@endsection

