@extends('admin.shared.Layout')

@section('title', 'Pesanan')

@section('content')
    <div class="space-y-5">

        {{-- Page Header --}}
        <div>
            <h2 class="text-lg font-bold text-gray-900">Pesanan &amp; Transaksi</h2>
            <p class="text-sm text-gray-500 mt-0.5">Semua pesanan pembeli baik reguler maupun Print on Demand yang masuk via website atau WhatsApp.</p>
        </div>

        {{-- Export Controls --}}
        @include('admin.partials._export-controls', ['action' => route('admin.purchases.export'), 'prefix' => 'purchases'])

        {{-- Filter Tabs (Semua, Reguler, Print on Demand, Menunggu Persetujuan) --}}
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <a href="{{ route('admin.purchases.index') }}"
               class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ !request()->has('order_type') && !request()->has('status') ? 'bg-primary text-white shadow-xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Semua Pesanan
            </a>
            <a href="{{ route('admin.purchases.index', ['order_type' => 'regular']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ request('order_type') === 'regular' ? 'bg-primary text-white shadow-xs' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }}">
                Pesanan Reguler
            </a>
            <a href="{{ route('admin.purchases.index', ['order_type' => 'on_demand']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ request('order_type') === 'on_demand' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white border border-indigo-200 text-indigo-700 hover:bg-indigo-50' }}">
                Print on Demand
            </a>
            <a href="{{ route('admin.purchases.index', ['status' => 'waiting_approval']) }}"
               class="px-3 py-1.5 rounded-lg font-bold transition-colors {{ request('status') === 'waiting_approval' ? 'bg-amber-600 text-white shadow-xs' : 'bg-white border border-amber-300 text-amber-800 hover:bg-amber-50' }}">
                Menunggu Persetujuan
            </a>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                @foreach($errors->all() as $error)
                    <p>&bull; {{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Tabel Pesanan --}}
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700 divide-y divide-gray-200">
                    <thead class="bg-gray-50 font-bold text-gray-500 uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Kode &amp; Jenis</th>
                            <th class="px-5 py-3.5">Nama &amp; Alamat Lengkap</th>
                            <th class="px-5 py-3.5">No. WhatsApp</th>
                            <th class="px-5 py-3.5">Metode &amp; Total</th>
                            <th class="px-5 py-3.5">Bukti Bayar</th>
                            <th class="px-5 py-3.5">Pengiriman / Resi</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($purchases as $purchase)
                            <tr class="hover:bg-gray-50 transition-colors {{ $purchase->isWaitingApproval() ? 'bg-amber-50/40' : '' }}">
                                {{-- Tanggal --}}
                                <td class="px-5 py-4 whitespace-nowrap text-gray-500">
                                    {{ $purchase->created_at->format('d M Y') }}<br>
                                    <span class="text-[11px] text-gray-400">{{ $purchase->created_at->format('H:i') }}</span>
                                </td>

                                {{-- Kode Pesanan & Badge Jenis --}}
                                <td class="px-5 py-4 whitespace-nowrap text-xs">
                                    <div class="space-y-1">
                                        <a href="{{ route('purchases.show', $purchase->order_code) }}" target="_blank" class="font-mono font-bold text-gray-900 hover:text-primary hover:underline block" title="Buka Halaman Nota Publik">
                                            {{ $purchase->order_code }}
                                        </a>

                                        @if($purchase->isOnDemand())
                                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                Print on Demand
                                            </span>
                                            @if($purchase->design_file_path)
                                                <a href="{{ asset('storage/'.$purchase->design_file_path) }}" target="_blank" class="text-[11px] font-bold text-indigo-600 hover:underline flex items-center gap-1 mt-0.5">
                                                    <span>Lihat File Desain &rarr;</span>
                                                </a>
                                            @endif
                                            @if($purchase->design_notes)
                                                <p class="text-[10px] text-gray-500 italic max-w-[170px] truncate" title="{{ $purchase->design_notes }}">
                                                    Spec: {{ $purchase->design_notes }}
                                                </p>
                                            @endif
                                            @if($purchase->admin_notes)
                                                <p class="text-[10px] text-emerald-700 font-semibold max-w-[170px] truncate" title="{{ $purchase->admin_notes }}">
                                                    Catatan Admin: {{ $purchase->admin_notes }}
                                                </p>
                                            @endif
                                        @else
                                            <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                                Reguler
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Nama Pembeli & Alamat Terstruktur Lengkap --}}
                                <td class="px-5 py-4 min-w-[220px] max-w-[300px]">
                                    <p class="font-bold text-gray-900 text-sm">{{ $purchase->customer_name }}</p>

                                    {{-- Alamat Terstruktur --}}
                                    <div class="mt-1 space-y-0.5 text-[11px] text-gray-600 bg-gray-50 p-2 rounded border border-gray-100">
                                        @if($purchase->street_address)
                                            <p class="font-medium text-gray-800">{{ $purchase->street_address }}</p>
                                        @endif
                                        @if($purchase->village || $purchase->district)
                                            <p>
                                                {{ $purchase->village ? 'Desa/Kel. ' . $purchase->village : '' }}
                                                {{ $purchase->village && $purchase->district ? ', ' : '' }}
                                                {{ $purchase->district ? 'Kec. ' . $purchase->district : '' }}
                                            </p>
                                        @endif
                                        @if($purchase->city)
                                            <p class="font-semibold text-gray-700">{{ $purchase->city }}</p>
                                        @endif

                                        {{-- Patokan / Detail Textarea --}}
                                        @php
                                            $patokan = $purchase->address ?: $purchase->customer_address;
                                        @endphp
                                        @if($patokan)
                                            <p class="pt-1 mt-1 border-t border-gray-200/60 text-gray-500 italic">
                                                <span class="font-semibold not-italic text-gray-600">Patokan:</span> {{ $patokan }}
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                {{-- No. WhatsApp & Tautan Cepat --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <a href="https://wa.me/{{ $purchase->international_phone }}" target="_blank"
                                       class="inline-flex items-center px-2.5 py-1 rounded bg-emerald-50 border border-emerald-200 text-emerald-800 hover:bg-emerald-100 font-semibold text-xs transition-colors"
                                       title="Chat langsung di WhatsApp">
                                        <span>{{ $purchase->customer_phone }}</span>
                                    </a>
                                </td>

                                {{-- Metode Pembayaran & Total --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if($purchase->payment_method === 'bank_transfer')
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                Transfer {{ $purchase->bank_name ?: 'BCA' }}
                                            </span>
                                        @else
                                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-bold bg-pink-50 text-pink-700 border border-pink-200">
                                                QRIS
                                            </span>
                                        @endif
                                        <p class="font-bold text-gray-900 text-xs font-mono">
                                            Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Bukti Bayar --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($purchase->payment_proof)
                                        <a href="{{ asset('storage/'.$purchase->payment_proof) }}" target="_blank"
                                           class="px-2.5 py-1 rounded border border-gray-200 text-gray-700 hover:bg-gray-100 text-[11px] font-semibold transition-colors inline-flex items-center"
                                           title="Lihat Foto Bukti Transfer">
                                            <span>Lihat Bukti</span>
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-[11px]">Belum diunggah</span>
                                    @endif
                                </td>

                                {{-- Pengiriman / Kurir & Resi --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @if($purchase->courier_name)
                                        <div class="space-y-1">
                                            <span class="font-bold text-gray-900 block">{{ $purchase->courier_name }}</span>
                                            <span class="font-mono text-xs text-gray-600 block">{{ $purchase->tracking_number ?: '-' }}</span>
                                            @if($purchase->shipping_receipt_path)
                                                <a href="{{ asset('storage/'.$purchase->shipping_receipt_path) }}" target="_blank" class="text-[11px] text-primary hover:underline font-semibold block">
                                                    Foto Resi &rarr;
                                                </a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-[11px]">Belum diatur</span>
                                    @endif
                                </td>

                                {{-- Status Pesanan --}}
                                <td class="px-5 py-4 whitespace-nowrap">
                                    @php
                                        $badgeColor = match($purchase->status) {
                                            'waiting_approval' => 'bg-amber-100 text-amber-900 border-amber-300 font-bold',
                                            'paid', 'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'shipped' => 'bg-sky-50 text-sky-700 border-sky-200',
                                            'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-amber-50 text-amber-700 border-amber-200',
                                        };
                                        $label = match($purchase->status) {
                                            'waiting_approval' => 'Menunggu Persetujuan',
                                            'paid' => 'Paid (Lunas)',
                                            'shipped' => 'Shipped (Dikirim)',
                                            'completed' => 'Completed (Selesai)',
                                            'cancelled' => 'Cancelled',
                                            default => 'Pending (Siap Bayar)',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold border {{ $badgeColor }}">
                                        {{ $label }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-5 py-4 whitespace-nowrap text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        {{-- JIKA STATUS MENUNGGU PERSETUJUAN (ON DEMAND) --}}
                                        @if($purchase->isWaitingApproval())
                                            <div class="flex items-center gap-1.5">
                                                <button type="button"
                                                        onclick="openApproveModal('{{ $purchase->id }}', '{{ $purchase->order_code }}', '{{ (float) $purchase->total_price }}', '{{ route('admin.purchases.approve-on-demand', $purchase) }}')"
                                                        class="px-2.5 py-1 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-colors cursor-pointer">
                                                    Setujui
                                                </button>
                                                <button type="button"
                                                        onclick="openRejectModal('{{ $purchase->id }}', '{{ $purchase->order_code }}', '{{ route('admin.purchases.reject-on-demand', $purchase) }}')"
                                                        class="px-2.5 py-1 rounded-md bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 font-bold text-xs transition-colors cursor-pointer">
                                                    Tolak
                                                </button>
                                            </div>
                                        @else
                                            {{-- Tombol Buka Modal Input Kurir & Resi --}}
                                            <button type="button"
                                                    onclick="openShippingModal('{{ $purchase->id }}', '{{ $purchase->order_code }}', '{{ $purchase->courier_name ?? '' }}', '{{ $purchase->tracking_number ?? '' }}', '{{ route('admin.purchases.update-shipping', $purchase) }}')"
                                                    class="px-2.5 py-1 rounded bg-stone-100 hover:bg-stone-200 border border-gray-300 text-gray-800 font-semibold text-xs transition-colors inline-flex items-center cursor-pointer">
                                                <span>{{ $purchase->courier_name ? 'Edit Resi' : 'Input Resi' }}</span>
                                            </button>

                                            {{-- Update Status Dropdown --}}
                                            <form method="POST" action="{{ route('admin.purchases.update-status', $purchase) }}" class="flex items-center gap-1.5">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="text-xs rounded-md border border-gray-200 px-2 py-1 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-primary cursor-pointer">
                                                    @foreach(['waiting_approval', 'pending', 'paid', 'shipped', 'completed', 'cancelled'] as $s)
                                                        <option value="{{ $s }}" {{ $purchase->status === $s ? 'selected' : '' }}>
                                                            {{ $s === 'waiting_approval' ? 'Waiting Approval' : ucfirst($s) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        @endif

                                        {{-- Notifikasi WhatsApp Khusus On Demand --}}
                                        @if($purchase->isOnDemand())
                                            @if($purchase->status === 'pending' || $purchase->status === 'paid')
                                                <a href="{{ $purchase->whatsapp_approval_notification_url }}" target="_blank"
                                                   class="text-[11px] font-semibold text-emerald-700 hover:underline">
                                                    Kirim Notif Disetujui (WA)
                                                </a>
                                            @elseif($purchase->status === 'cancelled')
                                                <a href="{{ $purchase->whatsapp_rejection_notification_url }}" target="_blank"
                                                   class="text-[11px] font-semibold text-rose-700 hover:underline">
                                                    Kirim Notif Ditolak (WA)
                                                </a>
                                            @endif
                                        @endif

                                        {{-- Tombol Minta Ulasan via WhatsApp jika Paid / Shipped / Completed --}}
                                        @if(in_array($purchase->status, ['paid', 'shipped', 'completed']))
                                            @if($purchase->review)
                                                <span class="text-[11px] text-gray-500 font-medium">Sudah diulas</span>
                                            @else
                                                @php
                                                    $waLink = \App\Support\ReviewRequestLink::forPurchase($purchase);
                                                @endphp
                                                @if($waLink)
                                                    <a href="{{ $waLink }}" target="_blank"
                                                        class="text-[11px] font-semibold text-emerald-700 hover:underline">
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
                                <td colspan="9" class="px-5 py-10 text-center text-sm text-gray-500">
                                    Belum ada transaksi pesanan yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($purchases->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $purchases->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- MODAL INPUT KURIR & NOMOR RESI PENGIRIMAN --}}
    <div id="admin-shipping-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl relative">
            <button type="button"
                    onclick="closeShippingModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 p-2 rounded-xl transition-colors cursor-pointer"
                    aria-label="Tutup modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="space-y-1">
                <span class="text-xs uppercase tracking-wider font-bold text-primary">Ekspedisi &amp; Logistik</span>
                <h3 class="text-lg font-bold text-gray-900">Input Pengiriman Pesanan</h3>
                <p class="text-xs text-gray-500 font-mono" id="modal-order-code-display">ORD-XXXX</p>
            </div>

            <form id="shipping-form" method="POST" action="" enctype="multipart/form-data" class="space-y-3.5 text-xs text-gray-700">
                @csrf
                @method('PATCH')

                {{-- Dropdown Ekspedisi / Cargo --}}
                <div>
                    <label class="block mb-1 font-semibold text-gray-800">Pilih Ekspedisi / Cargo *</label>
                    <select name="courier_name" id="modal-courier-select" required onchange="handleCourierChange()"
                            class="w-full px-3 py-2 rounded-xl border border-gray-300 bg-white text-gray-900 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">-- Pilih Ekspedisi --</option>
                        @foreach(['JNE', 'J&T Express', 'SiCepat', 'AnterAja', 'Pos Indonesia', 'Kurir Internal', 'Ambil di Kios'] as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Nomor Resi --}}
                <div>
                    <label class="block mb-1 font-semibold text-gray-800" id="modal-tracking-label">Nomor Resi Pengiriman *</label>
                    <input type="text" name="tracking_number" id="modal-tracking-input" placeholder="Contoh: JP1234567890"
                           class="w-full px-3 py-2 rounded-xl border border-gray-300 font-mono text-xs focus:outline-none focus:ring-2 focus:ring-primary">
                    <p class="text-[11px] text-gray-400 mt-0.5" id="modal-tracking-hint">Wajib diisi jika menggunakan kurir ekspedisi.</p>
                </div>

                {{-- Upload Foto Bukti Resi --}}
                <div>
                    <label class="block mb-1 font-semibold text-gray-800">Upload Foto Bukti Resi (Opsional)</label>
                    <input type="file" name="shipping_receipt" accept="image/jpeg,image/png,image/webp"
                           class="w-full text-xs text-gray-600 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-800 hover:file:bg-gray-200 cursor-pointer border border-gray-200 rounded-xl p-1 bg-white">
                    <p class="text-[11px] text-gray-400 mt-0.5">Maks. 5MB (JPG, PNG, atau WebP).</p>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button"
                            onclick="closeShippingModal()"
                            class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-medium cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold text-xs shadow-xs transition-all active:scale-[0.98] cursor-pointer">
                        Simpan Data Pengiriman
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL PERSETUJUAN (APPROVE) ON DEMAND --}}
    <div id="admin-approve-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl relative">
            <button type="button"
                    onclick="closeApproveModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 p-2 rounded-xl transition-colors cursor-pointer"
                    aria-label="Tutup modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="space-y-1">
                <span class="text-xs uppercase tracking-wider font-bold text-emerald-600">Persetujuan Pesanan</span>
                <h3 class="text-lg font-bold text-gray-900">Setujui Pesanan On Demand</h3>
                <p class="text-xs text-gray-500 font-mono" id="approve-order-code-display">POD-XXXX</p>
            </div>

            <p class="text-xs text-gray-600 leading-relaxed">
                Setelah disetujui, status pesanan akan berubah menjadi <strong>Pending (Siap Bayar)</strong> dan tautan pembayaran QRIS / Bank Transfer akan aktif untuk pembeli.
            </p>

            <form id="approve-form" method="POST" action="" class="space-y-3.5 text-xs text-gray-700">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block mb-1 font-semibold text-gray-800">Total Harga Pesanan (Rp)</label>
                    <input type="number" name="adjusted_price" id="approve-price-input" min="0" step="1000"
                           class="w-full px-3 py-2 rounded-xl border border-gray-300 font-bold text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-[11px] text-gray-400 mt-0.5">Biarkan seperti semula atau sesuaikan jika ada biaya tambahan kustom/diskon.</p>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-800">Catatan Persetujuan untuk Pembeli (Opsional)</label>
                    <textarea name="admin_notes" rows="2" placeholder="Contoh: Desain siap cetak. Estimasi pengerjaan 3 hari kerja."
                              class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button"
                            onclick="closeApproveModal()"
                            class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-medium cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition-all active:scale-[0.98] cursor-pointer">
                        Setujui Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL PENOLAKAN (REJECT) ON DEMAND --}}
    <div id="admin-reject-modal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl relative">
            <button type="button"
                    onclick="closeRejectModal()"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 p-2 rounded-xl transition-colors cursor-pointer"
                    aria-label="Tutup modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="space-y-1">
                <span class="text-xs uppercase tracking-wider font-bold text-rose-600">Penolakan Pesanan</span>
                <h3 class="text-lg font-bold text-gray-900">Tolak Pesanan On Demand</h3>
                <p class="text-xs text-gray-500 font-mono" id="reject-order-code-display">POD-XXXX</p>
            </div>

            <form id="reject-form" method="POST" action="" class="space-y-3.5 text-xs text-gray-700">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block mb-1 font-semibold text-gray-800">Alasan Penolakan *</label>
                    <textarea name="reason" rows="3" required placeholder="Contoh: Resolusi file gambar terlalu rendah untuk dicetak, atau bahan dasar sedang kosong."
                              class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                    <p class="text-[11px] text-gray-400 mt-0.5">Alasan ini akan ditampilkan pada nota pesanan pembeli.</p>
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button"
                            onclick="closeRejectModal()"
                            class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-medium cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-xs transition-all active:scale-[0.98] cursor-pointer">
                        Konfirmasi Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openShippingModal(id, orderCode, courier, tracking, actionUrl) {
            const modal = document.getElementById('admin-shipping-modal');
            const form = document.getElementById('shipping-form');
            const codeDisplay = document.getElementById('modal-order-code-display');
            const courierSelect = document.getElementById('modal-courier-select');
            const trackingInput = document.getElementById('modal-tracking-input');

            if (form) form.action = actionUrl;
            if (codeDisplay) codeDisplay.innerText = orderCode;
            if (courierSelect) courierSelect.value = courier || '';
            if (trackingInput) trackingInput.value = tracking || '';

            handleCourierChange();

            if (modal) modal.classList.remove('hidden');
        }

        function closeShippingModal() {
            const modal = document.getElementById('admin-shipping-modal');
            if (modal) modal.classList.add('hidden');
        }

        function openApproveModal(id, orderCode, totalPrice, actionUrl) {
            const modal = document.getElementById('admin-approve-modal');
            const form = document.getElementById('approve-form');
            const codeDisplay = document.getElementById('approve-order-code-display');
            const priceInput = document.getElementById('approve-price-input');

            if (form) form.action = actionUrl;
            if (codeDisplay) codeDisplay.innerText = orderCode;
            if (priceInput) priceInput.value = Math.round(totalPrice);

            if (modal) modal.classList.remove('hidden');
        }

        function closeApproveModal() {
            const modal = document.getElementById('admin-approve-modal');
            if (modal) modal.classList.add('hidden');
        }

        function openRejectModal(id, orderCode, actionUrl) {
            const modal = document.getElementById('admin-reject-modal');
            const form = document.getElementById('reject-form');
            const codeDisplay = document.getElementById('reject-order-code-display');

            if (form) form.action = actionUrl;
            if (codeDisplay) codeDisplay.innerText = orderCode;

            if (modal) modal.classList.remove('hidden');
        }

        function closeRejectModal() {
            const modal = document.getElementById('admin-reject-modal');
            if (modal) modal.classList.add('hidden');
        }

        function handleCourierChange() {
            const courier = document.getElementById('modal-courier-select')?.value;
            const trackingInput = document.getElementById('modal-tracking-input');
            const trackingLabel = document.getElementById('modal-tracking-label');
            const trackingHint = document.getElementById('modal-tracking-hint');

            if (courier === 'Ambil di Kios') {
                if (trackingInput) {
                    trackingInput.required = false;
                    trackingInput.placeholder = 'Tidak memerlukan resi (Ambil di Kios)';
                }
                if (trackingLabel) trackingLabel.innerText = 'Nomor Resi (Tidak Diperlukan)';
                if (trackingHint) trackingHint.innerText = 'Pelanggan mengambil langsung di gerai Kios Mandiri.';
            } else {
                if (trackingInput) {
                    trackingInput.required = true;
                    trackingInput.placeholder = 'Contoh: JP1234567890';
                }
                if (trackingLabel) trackingLabel.innerText = 'Nomor Resi Pengiriman *';
                if (trackingHint) trackingHint.innerText = 'Wajib diisi jika menggunakan kurir ekspedisi.';
            }
        }
    </script>
@endsection
