@extends('user.shared.Layout')

@section('content')
@php
    $indonesianMonths = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $getYoutubeId = function (?string $url): ?string {
        if (! $url) {
            return null;
        }
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match)) {
            return $match[1];
        }
        return null;
    };
@endphp

{{-- Header Halaman (Komponen <x-page-header>) --}}
<x-page-header
    title="Galeri Unboxing"
    subtitle="Bukti penerimaan donasi yang kami dokumentasikan secara terbuka."
    :pill="count($unboxingVideos) . ' video dokumentasi'"
    breadcrumb="Beranda / Galeri Unboxing"
/>

{{-- Konten Utama: Grid Video Dokumentasi Unboxing --}}
<section class="w-full bg-krem py-12 lg:py-16 pb-20">
    <div class="max-w-[1200px] mx-auto px-6 space-y-8">

        @if(empty($unboxingVideos) || count($unboxingVideos) === 0)
            {{-- State Kosong --}}
            <div class="bg-white rounded-[12px] p-12 border border-teks/10 shadow-xs text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-pink-pucat text-primary flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-teks">Belum ada dokumentasi unboxing</h3>
                <p class="text-xs sm:text-sm text-teks/70 max-w-md mx-auto">
                    Dokumentasi video pembukaan paket donasi akan segera kami tampilkan di sini secara berkala.
                </p>
            </div>
        @else
            {{-- Grid Kartu Video: 3 Kolom Desktop, 2 Tablet, 1 Mobile --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($unboxingVideos as $video)
                    @php
                        $platform = strtolower($video['platform'] ?? 'instagram');
                        $isYoutube = ($platform === 'youtube');
                        $youtubeId = $isYoutube ? $getYoutubeId($video['url'] ?? '') : null;

                        $formattedDate = '';
                        if (! empty($video['recorded_at'])) {
                            try {
                                $dateObj = \Carbon\Carbon::parse($video['recorded_at']);
                                $monthNum = (int) $dateObj->format('n');
                                $monthName = $indonesianMonths[$monthNum] ?? $dateObj->format('F');
                                $formattedDate = $dateObj->format('j') . ' ' . $monthName . ' ' . $dateObj->format('Y');
                            } catch (\Throwable $e) {
                                $formattedDate = (string) $video['recorded_at'];
                            }
                        }
                    @endphp

                    <div class="bg-white rounded-[12px] border border-teks/10 shadow-xs flex flex-col justify-between overflow-hidden hover:shadow-md transition-shadow">
                        {{-- Bagian Atas: Pratinjau Video (Aspect Ratio 16:9) --}}
                        @if($isYoutube && $youtubeId)
                            <div class="relative w-full aspect-video bg-pink-pucat overflow-hidden group">
                                <img src="https://img.youtube.com/vi/{{ $youtubeId }}/hqdefault.jpg"
                                     alt="Pratinjau video {{ $video['title'] }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center group-hover:bg-black/30 transition-colors">
                                    <div class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center shadow-md">
                                        <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Instagram / Placeholder Tanpa Script Eksternal --}}
                            <div class="relative w-full aspect-video bg-pink-pucat flex flex-col items-center justify-center p-4 text-center">
                                <div class="w-12 h-12 rounded-full bg-primary/10 border border-primary/20 text-primary flex items-center justify-center shadow-xs mb-2">
                                    <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-semibold text-primary">Dokumentasi Instagram Reel</span>
                            </div>
                        @endif

                        {{-- Bagian Tengah & Bawah: Metadata & Tombol --}}
                        <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2.5">
                                {{-- Platform Badge & Tanggal --}}
                                <div class="flex items-center justify-between gap-2">
                                    @if($isYoutube)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[6px] bg-red-50 text-red-600 text-xs font-semibold">
                                            YouTube
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-[6px] bg-pink-pucat text-primary text-xs font-semibold">
                                            Instagram
                                        </span>
                                    @endif

                                    @if($formattedDate)
                                        <span class="text-xs text-teks/60 font-medium">
                                            {{ $formattedDate }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Judul Video --}}
                                <h3 class="font-bold text-base text-teks leading-snug line-clamp-2" title="{{ $video['title'] }}">
                                    {{ $video['title'] }}
                                </h3>
                            </div>

                            {{-- Tombol Tautan Eksternal --}}
                            <div class="pt-2">
                                <a href="{{ $video['url'] }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="w-full h-10 flex items-center justify-center gap-2 rounded-[8px] border border-primary text-primary hover:bg-primary hover:text-white text-xs sm:text-sm font-semibold transition-colors active:scale-[0.98]">
                                    <span>Tonton di {{ $isYoutube ? 'YouTube' : 'Instagram' }}</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>

@include('user.pages.home._modal-donasi')
@include('user.pages.home._scripts')
@endsection
