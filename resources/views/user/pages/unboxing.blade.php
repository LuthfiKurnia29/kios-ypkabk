@extends('user.shared.Layout')

@section('title', 'Galeri Unboxing')

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
    :pill="$unboxingVideos->count() . ' video dokumentasi'"
    breadcrumb="Beranda / Galeri Unboxing"
/>

{{-- Konten Utama: Grid Video Dokumentasi Unboxing --}}
<section class="w-full bg-krem py-12 lg:py-16 pb-20">
    <div class="max-w-[1200px] mx-auto px-6 space-y-8">

        @if($unboxingVideos->isEmpty())
            {{-- State Kosong --}}
            <div class="bg-white rounded-[12px] p-12 border border-teks/10 shadow-xs text-center space-y-2">
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
                        $platform = strtolower($video->platform ?? 'instagram');
                        $thumbUrl = $video->thumbnail_url;

                        $formattedDate = '';
                        if (! empty($video->recorded_at)) {
                            try {
                                $dateObj = $video->recorded_at instanceof \Carbon\CarbonInterface
                                    ? $video->recorded_at
                                    : \Carbon\Carbon::parse($video->recorded_at);
                                $monthNum = (int) $dateObj->format('n');
                                $monthName = $indonesianMonths[$monthNum] ?? $dateObj->format('F');
                                $formattedDate = $dateObj->format('j') . ' ' . $monthName . ' ' . $dateObj->format('Y');
                            } catch (\Throwable $e) {
                                $formattedDate = (string) $video->recorded_at;
                            }
                        }
                    @endphp

                    <div class="bg-white rounded-[12px] border border-teks/10 shadow-xs flex flex-col justify-between overflow-hidden hover:shadow-md transition-shadow">
                        {{-- Bagian Atas: Pratinjau Video (Prioritas: thumbnail upload > thumbnail YouTube > placeholder bersih) --}}
                        @if($thumbUrl)
                            <div class="relative w-full aspect-video bg-pink-pucat overflow-hidden">
                                <img src="{{ $thumbUrl }}"
                                     alt="Pratinjau video {{ $video->title }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover">
                            </div>
                        @else
                            {{-- Placeholder Bersih Tanpa Ikon Dekoratif --}}
                            <div class="relative w-full aspect-video bg-pink-pucat flex flex-col items-center justify-center p-4 text-center">
                                <span class="text-xs font-semibold text-primary">Dokumentasi {{ $video->platform_label }}</span>
                            </div>
                        @endif

                        {{-- Bagian Tengah & Bawah: Metadata & Tombol --}}
                        <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2.5">
                                {{-- Platform Badge & Tanggal --}}
                                <div class="flex items-center justify-between gap-2">
                                    @if($platform === 'youtube')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-[6px] bg-red-50 text-red-600 text-xs font-semibold">
                                            YouTube
                                        </span>
                                    @elseif($platform === 'tiktok')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-[6px] bg-purple-50 text-purple-700 text-xs font-semibold">
                                            TikTok
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-[6px] bg-pink-pucat text-primary text-xs font-semibold">
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
                                <h3 class="font-bold text-base text-teks leading-snug line-clamp-2" title="{{ $video->title }}">
                                    {{ $video->title }}
                                </h3>
                            </div>

                            {{-- Tombol Tautan Eksternal --}}
                            <div class="pt-2">
                                <a href="{{ $video->url }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="w-full h-10 flex items-center justify-center rounded-[8px] border border-primary text-primary hover:bg-primary hover:text-white text-xs sm:text-sm font-semibold transition-colors active:scale-[0.98]">
                                    <span>{{ $video->platform_action_text }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
@endsection
