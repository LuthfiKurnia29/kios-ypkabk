@extends('user.shared.Layout')

@section('content')
@php
    $cards = (isset($impactMetrics) && $impactMetrics->isNotEmpty()) ? $impactMetrics : ($summaryCards ?? app(\App\Http\Services\SiteStatsService::class)->getSummaryCards());
@endphp

{{-- Header Halaman (Komponen <x-page-header>) --}}
<x-page-header
    title="Dampak & Transparansi"
    subtitle="Laporan penyaluran donasi dan penggunaan dana untuk terapi."
    pill="Diperbarui: {{ $lastUpdated }}"
    breadcrumb="Beranda / Dampak & Transparansi"
/>

{{-- 1. Tiga Kartu Statistik Dampak Sejajar --}}
<section class="w-full bg-krem py-12 lg:py-16 border-b border-teks/10">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($cards as $metric)
                @php
                    $metricValue = is_array($metric) ? $metric['value'] : $metric->value;
                    $metricLabel = is_array($metric) ? $metric['label'] : $metric->label;
                    $metricDesc = is_array($metric) ? ($metric['description'] ?? null) : ($metric->description ?? null);
                @endphp
                <div class="bg-white rounded-[12px] p-6 sm:p-8 border border-teks/10 shadow-xs flex flex-col justify-between space-y-3">
                    <div class="space-y-2">
                        <div class="text-3xl sm:text-4xl font-bold tracking-tight text-primary">
                            {{ $metricValue }}
                        </div>
                        <h3 class="font-semibold text-base sm:text-lg leading-snug text-teks">
                            {{ $metricLabel }}
                        </h3>
                    </div>
                    @if(!empty($metricDesc))
                        <p class="text-xs sm:text-sm text-teks/70 leading-relaxed">
                            {{ $metricDesc }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 2. Laporan Dana: Latar krem/putih seperti seksi lain --}}
<section class="w-full bg-krem py-12 lg:py-16">
    <div class="max-w-[1200px] mx-auto px-6 space-y-8">
        {{-- Judul Seksi --}}
        <h2 class="text-2xl sm:text-3xl font-bold text-teks tracking-tight">
            Laporan dana
        </h2>

        {{-- Satu Kartu Putih Terpadu --}}
        <div class="bg-white rounded-[12px] p-6 sm:p-8 border border-teks/10 shadow-xs space-y-8">
            {{-- Header Kartu: Keterangan & Pemilih Periode di Kanan Atas --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-teks/10">
                <span class="text-xs uppercase tracking-wider font-semibold text-primary">
                    Ringkasan Transparansi Arus Kas
                </span>
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <label for="period-select" class="text-xs font-semibold text-teks/70 shrink-0">
                        Pilih periode:
                    </label>
                    <select id="period-select"
                            onchange="window.location.search = '?periode=' + this.value"
                            class="px-3 py-1.5 rounded-[8px] bg-krem border border-teks/20 text-xs font-semibold text-teks focus:outline-none focus:ring-1 focus:ring-primary cursor-pointer">
                        @foreach($financialData['periods'] ?? [] as $period)
                            <option value="{{ $period['key'] }}" {{ ($financialData['selected_period'] ?? '') === $period['key'] ? 'selected' : '' }}>
                                {{ $period['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 3 Angka: Total Pemasukan, Total Penggunaan, Saldo --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-teks/60 block">Total pemasukan</span>
                    <div class="text-xl sm:text-2xl font-bold text-teks">
                        Rp {{ number_format($financialData['income'] ?? 0, 0, ',', '.') }}
                    </div>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-teks/60 block">Total penggunaan</span>
                    <div class="text-xl sm:text-2xl font-bold text-teks">
                        Rp {{ number_format($financialData['expense'] ?? 0, 0, ',', '.') }}
                    </div>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-teks/60 block">Saldo</span>
                    <div class="text-xl sm:text-2xl font-bold text-primary">
                        Rp {{ number_format($financialData['balance'] ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            {{-- Grafik Batang di Bawahnya --}}
            <div class="pt-6 border-t border-teks/10 space-y-6">
                @if($financialData['all_zero'] ?? false)
                    <div class="flex items-center justify-center h-40 text-teks/50 text-sm">
                        Belum ada data keuangan
                    </div>
                @else
                    {{-- Legend --}}
                    <div class="flex items-center gap-6 text-xs font-semibold text-teks/80">
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-xs bg-primary inline-block"></span>
                            <span>Pemasukan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-xs bg-sky-400 inline-block"></span>
                            <span>Penggunaan</span>
                        </div>
                    </div>

                    {{-- Grafik Batang Pemasukan vs Penggunaan Per Bulan --}}
                    <div class="h-52 sm:h-60 flex items-end justify-between gap-3 sm:gap-6 pt-6 border-b border-teks/15 pb-2">
                        @php
                            $allValues = collect($financialData['monthly'] ?? [])
                                ->flatMap(fn ($m) => [$m['income'] ?? 0, $m['expense'] ?? 0]);
                            $maxVal = max(1, $allValues->max() ?: 1);
                        @endphp
                        @foreach($financialData['monthly'] ?? [] as $m)
                            @php
                                $hInc = round((($m['income'] ?? 0) / $maxVal) * 100);
                                $hExp = round((($m['expense'] ?? 0) / $maxVal) * 100);
                            @endphp
                            <div class="flex-1 flex flex-col items-center h-full justify-end">
                                <div class="w-full flex items-end justify-center gap-1.5 sm:gap-2 h-full">
                                    <div style="height: {{ $hInc }}%;"
                                         class="w-4 sm:w-8 bg-primary rounded-t-[4px] transition-all"
                                         title="Pemasukan {{ $m['month'] }}: Rp {{ number_format($m['income'] ?? 0, 0, ',', '.') }}">
                                    </div>
                                    <div style="height: {{ $hExp }}%;"
                                         class="w-4 sm:w-8 bg-sky-400 rounded-t-[4px] transition-all"
                                         title="Penggunaan {{ $m['month'] }}: Rp {{ number_format($m['expense'] ?? 0, 0, ',', '.') }}">
                                    </div>
                                </div>
                                <span class="text-xs font-medium text-teks/75 mt-3">{{ $m['month'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@include('user.pages.home._modal-donasi')
@include('user.pages.home._scripts')
@endsection
