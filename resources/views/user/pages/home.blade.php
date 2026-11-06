@extends('user.shared.Layout')

@section('content')
    {{-- 1. Hero (#FFFBF7) --}}
    @include('user.pages.home._hero')

    {{-- 2. Jejak Kebaikan (#FDEEF4) --}}
    @include('user.pages.home._stats')

    {{-- 3. Cara Berbagi (#FFFBF7) --}}
    @include('user.pages.home._cara-kerja')

    {{-- 4. Produk Pilihan (#FDEEF4) --}}
    @include('user.pages.home._katalog')

    {{-- 5. Cerita dari Sahabat Kios (#FFFBF7) --}}
    @include('user.pages.home._transparansi')

    {{-- 6. Banner Mulai Donasi (Full-Width #C2185B Menempel ke Footer) --}}
    @include('user.pages.home._cta-donasi')

    {{-- Modal & Skrip Interaktif --}}
    @include('user.pages.home._modal-donasi')
    @include('user.pages.home._modal-order')
    @include('user.pages.home._scripts')
@endsection
