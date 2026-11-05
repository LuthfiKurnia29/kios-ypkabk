@extends('user.shared.Layout')

@section('content')
<div class="space-y-8 pb-16 pt-24">

    {{-- TOP SIMULATOR NOTICE BAR --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl p-4 text-amber-900 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2.5 font-bold">
                <span class="w-7 h-7 rounded-full bg-amber-200 text-amber-800 flex items-center justify-center text-sm shrink-0">🛠️</span>
                <div>
                    <span class="uppercase tracking-wider">Mode Simulator DOKU Payment Gateway</span>
                    <p class="font-normal text-amber-800 text-[11px] leading-tight pt-0.5">
                        Simulator lokal untuk menguji alur pembayaran Kios Mandiri YPKABK tanpa transaksi riil.
                    </p>
                </div>
            </div>
            <span class="px-2.5 py-1 bg-amber-200/80 rounded-md font-mono font-bold text-[11px] shrink-0">
                ENV: {{ strtoupper(config('doku.environment', 'dummy')) }}
            </span>
        </div>
    </div>

    {{-- MAIN DOKU CHECKOUT CARD --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-3xl border border-ink/15 shadow-md overflow-hidden">

            {{-- DOKU Header --}}
            <div class="bg-gradient-to-r from-red-700 via-red-600 to-rose-600 px-6 py-4 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white text-red-600 px-3 py-1 rounded-lg font-black tracking-widest text-sm shadow-xs">
                        DOKU
                    </div>
                    <div class="border-l border-white/30 pl-3">
                        <span class="text-xs uppercase font-bold tracking-wider block opacity-90">Payment Gateway</span>
                        <span class="text-[11px] opacity-75">Kios Mandiri YPKABK</span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] uppercase block opacity-75">Sisa Waktu Bayar</span>
                    <span id="countdown-timer" class="font-mono font-bold text-sm tracking-widest">59:59</span>
                </div>
            </div>

            {{-- Order Summary Info --}}
            <div class="bg-cream/60 px-6 py-4 border-b border-ink/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div>
                    <span class="text-ink/60 block text-[11px]">Nomor Tagihan / Pesanan:</span>
                    <span class="font-mono font-bold text-ink text-sm sm:text-base">{{ $purchase->order_code }}</span>
                </div>
                <div>
                    <span class="text-ink/60 block text-[11px]">Nama Donatur / Pemesan:</span>
                    <span class="font-bold text-ink">{{ $purchase->customer_name }}</span>
                </div>
                <div class="sm:text-right">
                    <span class="text-ink/60 block text-[11px]">Total Pembayaran:</span>
                    <span class="font-heading font-extrabold text-ink text-lg sm:text-xl">
                        Rp {{ number_format($purchase->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="p-6 sm:p-8 space-y-8">

                {{-- Payment Channel Selection Tabs --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading text-base sm:text-lg font-bold text-ink">
                            Pilih Metode Pembayaran
                        </h3>
                        <span class="text-xs text-ink/50">Simulasi Kanal DOKU</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs font-bold" id="channel-tabs">
                        <button type="button"
                                onclick="switchChannel('QRIS')"
                                id="tab-QRIS"
                                class="channel-tab-btn p-3 rounded-2xl border-2 border-red-600 bg-red-50 text-red-700 flex flex-col items-center justify-center gap-1 transition-all">
                            <span class="text-xl">📱</span>
                            <span>QRIS</span>
                        </button>

                        <button type="button"
                                onclick="switchChannel('VA')"
                                id="tab-VA"
                                class="channel-tab-btn p-3 rounded-2xl border border-ink/15 hover:bg-cream text-ink flex flex-col items-center justify-center gap-1 transition-all">
                            <span class="text-xl">🏦</span>
                            <span>Virtual Account</span>
                        </button>

                        <button type="button"
                                onclick="switchChannel('CC')"
                                id="tab-CC"
                                class="channel-tab-btn p-3 rounded-2xl border border-ink/15 hover:bg-cream text-ink flex flex-col items-center justify-center gap-1 transition-all">
                            <span class="text-xl">💳</span>
                            <span>Kartu Kredit</span>
                        </button>

                        <button type="button"
                                onclick="switchChannel('RETAIL')"
                                id="tab-RETAIL"
                                class="channel-tab-btn p-3 rounded-2xl border border-ink/15 hover:bg-cream text-ink flex flex-col items-center justify-center gap-1 transition-all">
                            <span class="text-xl">🏪</span>
                            <span>Gerai Retail</span>
                        </button>
                    </div>
                </div>

                {{-- Tab Panel 1: QRIS --}}
                <div id="panel-QRIS" class="channel-panel space-y-4 bg-cream/40 rounded-2xl p-6 border border-ink/10 text-center">
                    <div class="space-y-1">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 uppercase">
                            QRIS Nasional
                        </span>
                        <h4 class="font-bold text-ink text-sm sm:text-base">Scan QRIS untuk Menyelesaikan Donasi</h4>
                        <p class="text-xs text-ink/70">Mendukung BCA, Mandiri, BRI, BNI, GoPay, OVO, Dana, ShopeePay, LinkAja</p>
                    </div>

                    {{-- QR Code Display --}}
                    <div class="bg-white p-4 rounded-2xl border border-ink/15 inline-block shadow-xs">
                        <svg class="w-48 h-48 mx-auto" viewBox="0 0 200 200" fill="currentColor">
                            <!-- Simulated Realistic QR Pattern -->
                            <rect width="200" height="200" fill="white"/>
                            <!-- Top-left finder pattern -->
                            <rect x="15" y="15" width="45" height="45" fill="#2B2A1F"/>
                            <rect x="23" y="23" width="29" height="29" fill="white"/>
                            <rect x="29" y="29" width="17" height="17" fill="#D81B60"/>
                            <!-- Top-right finder pattern -->
                            <rect x="140" y="15" width="45" height="45" fill="#2B2A1F"/>
                            <rect x="148" y="23" width="29" height="29" fill="white"/>
                            <rect x="154" y="29" width="17" height="17" fill="#D81B60"/>
                            <!-- Bottom-left finder pattern -->
                            <rect x="15" y="140" width="45" height="45" fill="#2B2A1F"/>
                            <rect x="23" y="148" width="29" height="29" fill="white"/>
                            <rect x="29" y="154" width="17" height="17" fill="#D81B60"/>
                            <!-- Center DOKU mark -->
                            <rect x="85" y="85" width="30" height="30" rx="4" fill="#E11D48"/>
                            <text x="100" y="103" font-size="10" font-family="sans-serif" font-weight="900" fill="white" text-anchor="middle">DOKU</text>
                            <!-- Random QR bit clusters -->
                            <rect x="70" y="20" width="10" height="10" fill="#2B2A1F"/>
                            <rect x="90" y="25" width="8" height="15" fill="#2B2A1F"/>
                            <rect x="115" y="15" width="12" height="8" fill="#2B2A1F"/>
                            <rect x="70" y="45" width="15" height="8" fill="#2B2A1F"/>
                            <rect x="105" y="45" width="8" height="15" fill="#2B2A1F"/>
                            <rect x="20" y="70" width="12" height="12" fill="#2B2A1F"/>
                            <rect x="45" y="80" width="8" height="15" fill="#2B2A1F"/>
                            <rect x="65" y="75" width="12" height="8" fill="#2B2A1F"/>
                            <rect x="125" y="75" width="15" height="12" fill="#2B2A1F"/>
                            <rect x="150" y="85" width="10" height="8" fill="#2B2A1F"/>
                            <rect x="175" y="70" width="10" height="15" fill="#2B2A1F"/>
                            <rect x="70" y="125" width="12" height="15" fill="#2B2A1F"/>
                            <rect x="95" y="130" width="15" height="8" fill="#2B2A1F"/>
                            <rect x="120" y="125" width="10" height="15" fill="#2B2A1F"/>
                            <rect x="145" y="135" width="15" height="12" fill="#2B2A1F"/>
                            <rect x="170" y="125" width="12" height="10" fill="#2B2A1F"/>
                            <rect x="70" y="160" width="20" height="8" fill="#2B2A1F"/>
                            <rect x="105" y="155" width="10" height="15" fill="#2B2A1F"/>
                            <rect x="130" y="165" width="15" height="10" fill="#2B2A1F"/>
                            <rect x="160" y="160" width="15" height="15" fill="#2B2A1F"/>
                        </svg>
                        <span class="block text-[11px] font-mono text-ink/60 pt-2">NMID: ID1020038918291</span>
                    </div>

                    <div class="max-w-sm mx-auto text-[11px] text-ink/70 space-y-1">
                        <p>1. Buka aplikasi e-wallet atau mobile banking favorit Anda.</p>
                        <p>2. Arahkan pemindai ke QR Code di atas.</p>
                        <p>3. Periksa nominal donasi dan konfirmasi pembayaran.</p>
                    </div>
                </div>

                {{-- Tab Panel 2: Virtual Account --}}
                <div id="panel-VA" class="channel-panel hidden space-y-4 bg-cream/40 rounded-2xl p-6 border border-ink/10">
                    <div class="space-y-1">
                        <h4 class="font-bold text-ink text-sm sm:text-base">Pilih Bank Virtual Account</h4>
                        <p class="text-xs text-ink/70">Pembayaran otomatis diverifikasi 24/7 tanpa perlu upload bukti transfer</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <div class="bg-white p-4 rounded-xl border border-ink/15 space-y-1.5">
                            <span class="font-bold text-blue-700 block">Bank BCA</span>
                            <div class="flex items-center justify-between">
                                <span class="font-mono font-extrabold text-ink text-sm" id="va-bca">{{ $dummy['va_bca'] }}</span>
                                <button type="button" onclick="copyText('{{ $dummy['va_bca'] }}')" class="px-2 py-1 rounded bg-cream hover:bg-butter font-bold text-[10px] text-ink border border-ink/10">Salin</button>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-xl border border-ink/15 space-y-1.5">
                            <span class="font-bold text-yellow-700 block">Bank Mandiri</span>
                            <div class="flex items-center justify-between">
                                <span class="font-mono font-extrabold text-ink text-sm" id="va-mandiri">{{ $dummy['va_mandiri'] }}</span>
                                <button type="button" onclick="copyText('{{ $dummy['va_mandiri'] }}')" class="px-2 py-1 rounded bg-cream hover:bg-butter font-bold text-[10px] text-ink border border-ink/10">Salin</button>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-xl border border-ink/15 space-y-1.5">
                            <span class="font-bold text-blue-900 block">Bank BRI</span>
                            <div class="flex items-center justify-between">
                                <span class="font-mono font-extrabold text-ink text-sm" id="va-bri">{{ $dummy['va_bri'] }}</span>
                                <button type="button" onclick="copyText('{{ $dummy['va_bri'] }}')" class="px-2 py-1 rounded bg-cream hover:bg-butter font-bold text-[10px] text-ink border border-ink/10">Salin</button>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-xl border border-ink/15 space-y-1.5">
                            <span class="font-bold text-teal-700 block">Bank BNI</span>
                            <div class="flex items-center justify-between">
                                <span class="font-mono font-extrabold text-ink text-sm" id="va-bni">{{ $dummy['va_bni'] }}</span>
                                <button type="button" onclick="copyText('{{ $dummy['va_bni'] }}')" class="px-2 py-1 rounded bg-cream hover:bg-butter font-bold text-[10px] text-ink border border-ink/10">Salin</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab Panel 3: Credit Card --}}
                <div id="panel-CC" class="channel-panel hidden space-y-4 bg-cream/40 rounded-2xl p-6 border border-ink/10">
                    <div class="space-y-1">
                        <h4 class="font-bold text-ink text-sm sm:text-base">Simulasi Kartu Kredit / Debit</h4>
                        <p class="text-xs text-ink/70">Mendukung Visa, Mastercard, JCB berlogo 3D Secure</p>
                    </div>

                    <div class="max-w-md space-y-3 text-xs">
                        <div>
                            <label class="block mb-1 font-semibold text-ink">Nomor Kartu (Dummy)</label>
                            <input type="text" value="4532 •••• •••• 8899" readonly class="w-full px-3 py-2 rounded-xl border border-ink/15 bg-white font-mono text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block mb-1 font-semibold text-ink">Masa Berlaku</label>
                                <input type="text" value="12/28" readonly class="w-full px-3 py-2 rounded-xl border border-ink/15 bg-white font-mono text-sm">
                            </div>
                            <div>
                                <label class="block mb-1 font-semibold text-ink">CVV</label>
                                <input type="text" value="•••" readonly class="w-full px-3 py-2 rounded-xl border border-ink/15 bg-white font-mono text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tab Panel 4: Retail Store --}}
                <div id="panel-RETAIL" class="channel-panel hidden space-y-4 bg-cream/40 rounded-2xl p-6 border border-ink/10">
                    <div class="space-y-1">
                        <h4 class="font-bold text-ink text-sm sm:text-base">Gerai Retail Alfamart / Indomaret</h4>
                        <p class="text-xs text-ink/70">Tunjukkan kode pembayaran ke kasir minimarket</p>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-ink/15 max-w-sm space-y-2">
                        <span class="text-[11px] text-ink/60 block">Kode Pembayaran:</span>
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-black text-xl text-ink tracking-wider">{{ $dummy['retail_code'] }}</span>
                            <button type="button" onclick="copyText('{{ $dummy['retail_code'] }}')" class="px-3 py-1.5 rounded-lg bg-cream hover:bg-butter font-bold text-xs text-ink border border-ink/10">Salin</button>
                        </div>
                        <p class="text-[11px] text-ink/60 pt-1">Merchant: DOKU - Kios Mandiri YPKABK</p>
                    </div>
                </div>

                {{-- SIMULATION CONTROL ACTIONS --}}
                <div class="pt-6 border-t border-ink/10 space-y-4">
                    <div class="space-y-1">
                        <h4 class="font-heading text-sm sm:text-base font-bold text-ink flex items-center gap-2">
                            <span>🎮</span>
                            <span>Aksi Simulasi (Pilih Hasil Pengujian):</span>
                        </h4>
                        <p class="text-xs text-ink/70">
                            Pilih salah satu tombol di bawah untuk menyimulasikan respon DOKU Payment Gateway:
                        </p>
                    </div>

                    <form id="simulator-action-form" method="POST" action="{{ route('payment.doku.simulate-action', ['purchase' => $purchase->order_code]) }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @csrf
                        <input type="hidden" name="channel" id="selected-channel" value="QRIS">

                        {{-- Action 1: Bayar Berhasil --}}
                        <button type="submit"
                                name="action"
                                value="paid"
                                class="min-h-[48px] py-3 px-4 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>Simulasi Bayar Sukses</span>
                        </button>

                        {{-- Action 2: Bayar Gagal --}}
                        <button type="submit"
                                name="action"
                                value="cancelled"
                                class="min-h-[48px] py-3 px-4 rounded-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                            <span>Simulasi Gagal / Batal</span>
                        </button>

                        {{-- Action 3: Kadaluarsa --}}
                        <button type="submit"
                                name="action"
                                value="expired"
                                class="min-h-[48px] py-3 px-4 rounded-full bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs sm:text-sm flex items-center justify-center gap-2 shadow-sm transition-all active:scale-95">
                            <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span>Simulasi Kadaluarsa</span>
                        </button>
                    </form>
                </div>

            </div>

            {{-- DOKU Footer --}}
            <div class="bg-cream/40 px-6 py-3 border-t border-ink/10 flex items-center justify-between text-[11px] text-ink/60">
                <span>Keamanan transaksi bersertifikasi PCI-DSS Level 1 DOKU</span>
                <a href="{{ route('keranjang') }}" class="font-semibold text-ink hover:underline">
                    &larr; Batalkan dan Kembali
                </a>
            </div>

        </div>
    </div>

</div>

<script>
    let activeChannel = 'QRIS';

    function switchChannel(channel) {
        activeChannel = channel;
        document.getElementById('selected-channel').value = channel;

        // Update tabs styling
        document.querySelectorAll('.channel-tab-btn').forEach(btn => {
            btn.classList.remove('border-2', 'border-red-600', 'bg-red-50', 'text-red-700');
            btn.classList.add('border', 'border-ink/15', 'text-ink');
        });

        const activeBtn = document.getElementById('tab-' + channel);
        if (activeBtn) {
            activeBtn.classList.remove('border', 'border-ink/15', 'text-ink');
            activeBtn.classList.add('border-2', 'border-red-600', 'bg-red-50', 'text-red-700');
        }

        // Toggle panel display
        document.querySelectorAll('.channel-panel').forEach(panel => {
            panel.classList.add('hidden');
        });

        const targetPanel = document.getElementById('panel-' + channel);
        if (targetPanel) {
            targetPanel.classList.remove('hidden');
        }
    }

    function copyText(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Berhasil menyalin: ' + text);
        }).catch(() => {
            prompt('Salin teks berikut:', text);
        });
    }

    // Simple countdown timer
    let secondsLeft = 3599;
    const timerEl = document.getElementById('countdown-timer');
    setInterval(() => {
        if (secondsLeft <= 0) return;
        secondsLeft--;
        const m = Math.floor(secondsLeft / 60);
        const s = secondsLeft % 60;
        if (timerEl) {
            timerEl.innerText = (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        }
    }, 1000);
</script>
@endsection

