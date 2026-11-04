{{-- ========================================================
     SECTION: CERITA / TESTIMONI - FR-01 / FR-04
     id="cerita", layout: kartu kutipan berwarna, bisa digeser.
     ======================================================== --}}
<section id="cerita" class="bg-white py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-6 space-y-10 lg:space-y-12">

        {{-- Header Section --}}
        <div class="space-y-3">
            <span class="text-sm font-semibold text-ink/60 block">
                Cerita Nyata
            </span>
            <h2 class="font-heading text-3xl lg:text-5xl font-bold text-ink tracking-tight leading-tight">
                Cerita dari Donatur &amp; Pembeli
            </h2>
            <p class="text-lg text-ink/70 leading-relaxed">
                Rantai kebaikan yang mengalir dari setiap barang preloved dan karya vokasi anak-anak istimewa binaan YPKABK.
            </p>
        </div>

        {{-- Kartu Kutipan Berwarna (Bisa Digeser di Mobile) --}}
        <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 -mx-6 px-6 lg:mx-0 lg:px-0 lg:grid lg:grid-cols-3 lg:gap-6 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden pb-4 lg:pb-0">
            @if(isset($reviews) && $reviews->count() > 0)
                @php
                    $colors = [
                        ['bg' => 'bg-sun text-ink', 'quote' => 'text-ink/30', 'avatar_bg' => 'bg-white text-ink'],
                        ['bg' => 'bg-cream text-ink border border-ink/10', 'quote' => 'text-ink/30', 'avatar_bg' => 'bg-sun text-ink'],
                        ['bg' => 'bg-olive text-white', 'quote' => 'text-sun', 'avatar_bg' => 'bg-sun text-ink', 'name_color' => 'text-white', 'role_color' => 'text-white/70'],
                    ];
                @endphp
                @foreach($reviews as $index => $review)
                    @php
                        $color = $colors[$index % count($colors)];
                        $isDark = str_contains($color['bg'], 'bg-olive');
                    @endphp
                    <div class="{{ $color['bg'] }} rounded-3xl p-8 flex flex-col justify-between min-h-[320px] min-w-[85%] sm:min-w-[70%] lg:min-w-0 snap-center shrink-0 lg:shrink shadow-sm">
                        <div class="space-y-4">
                            <span class="font-heading text-6xl leading-none {{ $color['quote'] }} select-none block">&ldquo;</span>
                            <blockquote class="text-lg leading-relaxed {{ $isDark ? 'text-white' : 'text-ink' }}">
                                {{ $review->quote }}
                            </blockquote>
                        </div>
                        <div class="flex items-center gap-3 mt-8 pt-4">
                            <div class="w-12 h-12 rounded-full {{ $color['avatar_bg'] }} font-heading font-bold text-base flex items-center justify-center shrink-0 shadow-sm">
                                {{ $review->initials }}
                            </div>
                            <div>
                                <h3 class="font-heading text-lg font-bold {{ $isDark ? 'text-white' : 'text-ink' }} leading-tight">
                                    {{ $review->name }}
                                </h3>
                                <p class="text-sm {{ $isDark ? 'text-white/70' : 'text-ink/70' }}">
                                    {{ $review->role }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Fallback jika DB kosong --}}
                <div class="bg-sun text-ink rounded-3xl p-8 flex flex-col justify-between min-h-[320px] min-w-[85%] sm:min-w-[70%] lg:min-w-0 snap-center shrink-0 lg:shrink shadow-sm">
                    <div class="space-y-4">
                        <span class="font-heading text-6xl leading-none text-ink/30 select-none block">&ldquo;</span>
                        <blockquote class="text-lg leading-relaxed text-ink">
                            Sempat bingung pakaian anak menumpuk mau dikemanakan. Di Kios Mandiri YPKABK prosesnya mudah, laporannya transparan, dan senang sekali melihat hasilnya membuka ruang terapi bagi anak-anak istimewa.
                        </blockquote>
                    </div>
                    <div class="flex items-center gap-3 mt-8 pt-4">
                        <div class="w-12 h-12 rounded-full bg-white text-ink font-heading font-bold text-base flex items-center justify-center shrink-0 shadow-sm">
                            NK
                        </div>
                        <div>
                            <h3 class="font-heading text-lg font-bold text-ink leading-tight">
                                Nanie Kurniawati
                            </h3>
                            <p class="text-sm text-ink/70">
                                Donatur Preloved &bull; Sidoarjo
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-cream text-ink border border-ink/10 rounded-3xl p-8 flex flex-col justify-between min-h-[320px] min-w-[85%] sm:min-w-[70%] lg:min-w-0 snap-center shrink-0 lg:shrink shadow-sm">
                    <div class="space-y-4">
                        <span class="font-heading text-6xl leading-none text-ink/30 select-none block">&ldquo;</span>
                        <blockquote class="text-lg leading-relaxed text-ink">
                            Saya beli papan titian dan tote bag rajut. Kualitasnya sangat rapi, packing ramah lingkungan, dan yang terpenting menyokong anak-anak istimewa jadi lebih mandiri.
                        </blockquote>
                    </div>
                    <div class="flex items-center gap-3 mt-8 pt-4">
                        <div class="w-12 h-12 rounded-full bg-sun text-ink font-heading font-bold text-base flex items-center justify-center shrink-0 shadow-sm">
                            DP
                        </div>
                        <div>
                            <h3 class="font-heading text-lg font-bold text-ink leading-tight">
                                Dr. Dimas Prasetyo
                            </h3>
                            <p class="text-sm text-ink/70">
                                Pembeli &amp; Orang Tua Asuh &bull; Mojokerto
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-olive text-white rounded-3xl p-8 flex flex-col justify-between min-h-[320px] min-w-[85%] sm:min-w-[70%] lg:min-w-0 snap-center shrink-0 lg:shrink shadow-sm">
                    <div class="space-y-4">
                        <span class="font-heading text-6xl leading-none text-sun select-none block">&ldquo;</span>
                        <blockquote class="text-lg leading-relaxed text-white">
                            Pelatihan keterampilan di sentra Mojokerto sangat bermakna bagi putra kami. Kini dia jauh lebih percaya diri dan bangga melihat karya kerajinannya bisa diapresiasi serta dibeli oleh banyak orang.
                        </blockquote>
                    </div>
                    <div class="flex items-center gap-3 mt-8 pt-4">
                        <div class="w-12 h-12 rounded-full bg-sun text-ink font-heading font-bold text-base flex items-center justify-center shrink-0 shadow-sm">
                            EW
                        </div>
                        <div>
                            <h3 class="font-heading text-lg font-bold text-white leading-tight">
                                Ibu Endah Wulandari
                            </h3>
                            <p class="text-sm text-white/70">
                                Orang Tua Siswa &amp; Donatur &bull; Mojokerto
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</section>
