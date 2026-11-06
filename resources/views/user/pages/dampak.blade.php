@extends('user.shared.Layout')

@section('content')
@php
    // TODO: nanti diganti $impactMetrics dari backend controller
    $summaryMetrics = [
        ['value' => '1.420+', 'label' => 'Barang layak pakai tersalurkan'],
        ['value' => '38', 'label' => 'ABK terlatih mandiri'],
        ['value' => 'Rp 84,5 Jt', 'label' => 'Dana terapi terkumpul'],
    ];

    // TODO: nanti diganti data laporan keuangan riil per periode dari backend
    $financialData = [
        'income' => 14850000,
        'expense' => 13420000,
        'balance' => 1430000,
        'monthly' => [
            ['month' => 'Jan', 'income' => 9500000, 'expense' => 8800000],
            ['month' => 'Feb', 'income' => 11200000, 'expense' => 10100000],
            ['month' => 'Mar', 'income' => 12800000, 'expense' => 12400000],
            ['month' => 'Apr', 'income' => 13500000, 'expense' => 12900000],
            ['month' => 'Mei', 'income' => 14850000, 'expense' => 13420000],
        ]
    ];

    // TODO: nanti dihubungkan ke file PDF unduhan aktual dari storage/backend
    $downloadableReports = [
        [
            'title' => 'Laporan Keuangan & Dampak Terapi – Kuartal I 2024',
            'date' => '10 April 2024',
            'size' => '2.8 MB PDF',
        ],
        [
            'title' => 'Rekap Penyaluran Barang Preloved & Vokasi – Semester II 2023',
            'date' => '15 Januari 2024',
            'size' => '3.4 MB PDF',
        ],
        [
            'title' => 'Laporan Akuntabilitas Tahunan Kios Mandiri YPKABK 2023',
            'date' => '05 Februari 2024',
            'size' => '5.1 MB PDF',
        ],
        [
            'title' => 'Laporan Pengadaan Alat Terapi Sensori Sentra Mojokerto',
            'date' => '22 November 2023',
            'size' => '1.9 MB PDF',
        ],
    ];
@endphp

{{-- Header Halaman (Komponen <x-page-header>) --}}
<x-page-header
    title="Dampak & Transparansi"
    subtitle="Laporan penyaluran donasi dan penggunaan dana untuk terapi."
    pill="Diperbarui: 15 Mei 2024"
    breadcrumb="Beranda / Dampak & Transparansi"
/>

{{-- 1. Satu Baris 3 Angka Besar Dipisah Garis Tipis Vertikal (Tanpa Kartu) --}}
<section class="w-full bg-[#FFFBF7] py-12 lg:py-16 border-b border-[#2B2430]/10">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-[#2B2430]/15">
            @foreach($summaryMetrics as $metric)
                <div class="py-6 md:py-2 md:px-8 first:pl-0 last:pr-0 space-y-1">
                    <div class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-primary tracking-tight">
                        {{ $metric['value'] }}
                    </div>
                    <p class="text-xs sm:text-sm text-[#2B2430]/75 font-medium">
                        {{ $metric['label'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 2. Laporan Dana: Dropdown Periode, 3 Ringkasan, dan 1 Grafik Batang Sederhana --}}
<section class="w-full bg-[#FDEEF4] py-16 lg:py-20">
    <div class="max-w-[1200px] mx-auto px-6 space-y-8">
        {{-- Header & Dropdown Periode --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-[#2B2430]/10">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2B2430] tracking-tight">
                Laporan dana
            </h2>
            <div class="flex items-center gap-2">
                <label for="period-select" class="text-xs font-semibold text-[#2B2430]/70 shrink-0">
                    Pilih periode:
                </label>
                <select id="period-select" class="px-3 py-1.5 rounded-[8px] bg-white border border-[#2B2430]/20 text-xs font-semibold text-[#2B2430] focus:outline-none focus:ring-1 focus:ring-primary cursor-pointer">
                    <option value="mei-2024">Mei 2024</option>
                    <option value="apr-2024">April 2024</option>
                    <option value="mar-2024">Maret 2024</option>
                </select>
            </div>
        </div>

        {{-- 3 Ringkasan: Total Pemasukan, Total Penggunaan, Saldo --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white rounded-[12px] p-6 border border-[#2B2430]/10 space-y-1 shadow-xs">
                <span class="text-xs font-semibold text-[#2B2430]/60">Total pemasukan</span>
                <div class="text-2xl lg:text-3xl font-extrabold text-[#2B2430]">
                    Rp {{ number_format($financialData['income'], 0, ',', '.') }}
                </div>
            </div>
            <div class="bg-white rounded-[12px] p-6 border border-[#2B2430]/10 space-y-1 shadow-xs">
                <span class="text-xs font-semibold text-[#2B2430]/60">Total penggunaan</span>
                <div class="text-2xl lg:text-3xl font-extrabold text-[#2B2430]">
                    Rp {{ number_format($financialData['expense'], 0, ',', '.') }}
                </div>
            </div>
            <div class="bg-white rounded-[12px] p-6 border border-[#2B2430]/10 space-y-1 shadow-xs">
                <span class="text-xs font-semibold text-[#2B2430]/60">Saldo</span>
                <div class="text-2xl lg:text-3xl font-extrabold text-primary">
                    Rp {{ number_format($financialData['balance'], 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- Grafik Batang Pemasukan vs Penggunaan Per Bulan (HTML/CSS Murni) --}}
        <div class="bg-white rounded-[12px] p-6 sm:p-8 border border-[#2B2430]/10 space-y-6 shadow-xs">
            <div class="flex items-center gap-6 text-xs font-semibold text-[#2B2430]/80">
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-xs bg-primary inline-block"></span>
                    <span>Pemasukan</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-xs bg-sky-400 inline-block"></span>
                    <span>Penggunaan</span>
                </div>
            </div>

            <div class="h-56 sm:h-64 flex items-end justify-between gap-3 sm:gap-6 pt-6 border-b border-[#2B2430]/15 pb-2">
                @php
                    $maxVal = 16000000;
                @endphp
                @foreach($financialData['monthly'] as $m)
                    @php
                        $hInc = round(($m['income'] / $maxVal) * 100);
                        $hExp = round(($m['expense'] / $maxVal) * 100);
                    @endphp
                    <div class="flex-1 flex flex-col items-center h-full justify-end">
                        <div class="w-full flex items-end justify-center gap-1.5 sm:gap-2 h-full">
                            <div style="height: {{ $hInc }}%;"
                                 class="w-4 sm:w-8 bg-primary rounded-t-[4px] transition-all"
                                 title="Pemasukan {{ $m['month'] }}: Rp {{ number_format($m['income'], 0, ',', '.') }}">
                            </div>
                            <div style="height: {{ $hExp }}%;"
                                 class="w-4 sm:w-8 bg-sky-400 rounded-t-[4px] transition-all"
                                 title="Penggunaan {{ $m['month'] }}: Rp {{ number_format($m['expense'], 0, ',', '.') }}">
                            </div>
                        </div>
                        <span class="text-xs font-medium text-[#2B2430]/75 mt-3">{{ $m['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- 3. Laporan untuk Diunduh (4 Baris) --}}
<section class="w-full bg-[#FFFBF7] py-16 lg:py-20">
    <div class="max-w-[1200px] mx-auto px-6 space-y-8">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#2B2430] tracking-tight">
            Laporan untuk diunduh
        </h2>

        <div class="divide-y divide-[#2B2430]/10 border-y border-[#2B2430]/10">
            @foreach($downloadableReports as $report)
                <div class="py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <h3 class="font-bold text-sm sm:text-base text-[#2B2430]">
                            {{ $report['title'] }}
                        </h3>
                        <p class="text-xs text-[#2B2430]/60">
                            {{ $report['date'] }} &bull; {{ $report['size'] }}
                        </p>
                    </div>
                    {{-- TODO: tautkan ke URL unduhan berkas PDF aktual --}}
                    <a href="#"
                       onclick="alert('Tautan berkas PDF contoh. Hubungkan ke penyimpanan berkas riil.'); return false;"
                       class="inline-flex items-center justify-center px-4 py-2 rounded-[8px] border border-primary text-primary hover:bg-primary hover:text-white text-xs font-semibold transition-colors self-start sm:self-auto shrink-0">
                        Unduh PDF
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

@include('user.pages.home._modal-donasi')
@include('user.pages.home._scripts')
@endsection
