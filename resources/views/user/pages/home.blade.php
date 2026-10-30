@extends('user.shared.Layout')

@section('content')
@include('user.pages.home._hero')

@include('user.pages.home._stats')

@include('user.pages.home._cara-kerja')

@include('user.pages.home._katalog')

@include('user.pages.home._cta-donasi')

@include('user.pages.home._transparansi')

@include('user.pages.home._modal-donasi')
@include('user.pages.home._modal-order')
@include('user.pages.home._scripts')
@endsection
