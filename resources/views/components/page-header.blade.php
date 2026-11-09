@props([
    'title',
    'subtitle' => null,
    'pill' => null,
    'breadcrumb' => null,
])

<div class="w-full bg-[#FDEEF4] pt-28 lg:pt-32 pb-10">
    <div class="max-w-[1200px] mx-auto px-6">
        {{-- Breadcrumb: "Beranda / Nama" tanpa ikon --}}
        <nav class="text-xs text-[#2B2430]/60 mb-3 font-medium" aria-label="Breadcrumb">
            {{ $breadcrumb ?? ('Beranda / ' . $title) }}
        </nav>

        {{-- Flex Header & Subtitle + Pill Kanan Bawah --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="space-y-2">
                <h1 class="text-3xl sm:text-4xl lg:text-[40px] font-bold text-[#2B2430] leading-tight tracking-tight">
                    {{ $title }}
                </h1>
                @if(!empty($subtitle))
                    <p class="text-sm sm:text-base text-[#2B2430]/75 max-w-[640px] leading-relaxed">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            @if(!empty($pill))
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-white border border-[#2B2430]/15 text-xs font-semibold text-[#2B2430] self-start md:self-end shrink-0 shadow-xs">
                    {{ $pill }}
                </div>
            @endif
        </div>
    </div>
</div>
