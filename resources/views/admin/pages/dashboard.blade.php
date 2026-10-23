@extends('admin.shared.Layout')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Welcome --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-900">Selamat datang, {{ auth()->user()->name }}!</h2>
            <p class="mt-1 text-sm text-gray-500">Kelola produk, transaksi, dan pengguna Kios Mandiri dari satu tempat.</p>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            @foreach ([
                ['label' => 'Total Produk', 'value' => '0'],
                ['label' => 'Transaksi Hari Ini', 'value' => '0'],
                ['label' => 'Total Pengguna', 'value' => '0'],
                ['label' => 'Pendapatan Bulan Ini', 'value' => 'Rp 0'],
            ] as $stat)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <p class="text-sm font-medium text-gray-500">{{ $stat['label'] }}</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

    </div>
@endsection
