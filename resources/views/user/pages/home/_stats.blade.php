{{-- Section: Jejak Kebaikan / Angka Dampak (Latar #FDEEF4 Sesuai PDF Halaman 1) --}}
<section id="dampak" class="w-full bg-[#FDEEF4] py-16 lg:py-20 font-sans">
    <div class="max-w-[1200px] mx-auto px-6 space-y-10">

        {{-- Header Section --}}
        <div class="space-y-3 max-w-2xl">
            <span class="text-xs uppercase tracking-wider font-semibold text-primary">
                Laporan Keterbukaan
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#2B2430] tracking-tight">
                Jejak Kebaikan Anda yang Tumbuh Bersama Kami
            </h2>
            <p class="text-sm sm:text-base text-[#2B2430]/75 font-normal leading-relaxed">
                Transparansi adalah janji kami kepada setiap donatur dan pembeli dermawan.
            </p>
        </div>

        {{-- Grid Kartu Dampak: 3 Kolom, Kartu Terakhir Berlatar Primary Teks Putih --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if(isset($impactMetrics) && $impactMetrics->count() > 0)
                @foreach($impactMetrics as $metric)
                    @php
                        $isLast = $loop->last;
                    @endphp
                    <div class="{{ $isLast ? 'bg-primary text-white border-primary' : 'bg-white text-[#2B2430] border-[#2B2430]/10' }} rounded-[12px] p-6 sm:p-8 border flex flex-col justify-between space-y-4 shadow-xs">
                        <div class="space-y-3">
                            <span class="text-xs uppercase tracking-wider font-semibold {{ $isLast ? 'text-white/80' : 'text-primary' }}">
                                {{ $isLast ? 'Alokasi Dana' : ($loop->first ? 'Penyaluran' : 'Penerima Manfaat') }}
                            </span>
                            <div class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight {{ $isLast ? 'text-white' : 'text-primary' }}">
                                {{ $metric->value }}
                            </div>
                            <div class="font-semibold text-base sm:text-lg leading-snug {{ $isLast ? 'text-white' : 'text-[#2B2430]' }}">
                                {{ $metric->label }}
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm font-normal leading-relaxed {{ $isLast ? 'text-white/90' : 'text-[#2B2430]/70' }}">
                            {{ $metric->description }}
                        </p>
                    </div>
                @endforeach
            @else
                {{-- Fallback jika data database belum ada --}}
                <div class="bg-white text-[#2B2430] rounded-[12px] p-6 sm:p-8 border border-[#2B2430]/10 flex flex-col justify-between space-y-4 shadow-xs">
                    <div class="space-y-3">
                        <span class="text-xs uppercase tracking-wider font-semibold text-primary">Penyaluran</span>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-primary">1.420+</div>
                        <div class="font-semibold text-base sm:text-lg leading-snug text-[#2B2430]">Barang tersalurkan</div>
                    </div>
                    <p class="text-xs sm:text-sm font-normal leading-relaxed text-[#2B2430]/70">
                        Barang preloved terkurasi yang diterima pemilik baru dan tidak berakhir sebagai sampah.
                    </p>
                </div>

                <div class="bg-white text-[#2B2430] rounded-[12px] p-6 sm:p-8 border border-[#2B2430]/10 flex flex-col justify-between space-y-4 shadow-xs">
                    <div class="space-y-3">
                        <span class="text-xs uppercase tracking-wider font-semibold text-primary">Penerima Manfaat</span>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-primary">38 ABK</div>
                        <div class="font-semibold text-base sm:text-lg leading-snug text-[#2B2430]">Terlatih mandiri</div>
                    </div>
                    <p class="text-xs sm:text-sm font-normal leading-relaxed text-[#2B2430]/70">
                        Remaja dengan autisme, down syndrome, dan disabilitas fisik kini aktif berkarya kriya dan menjahit.
                    </p>
                </div>

                <div class="bg-primary text-white rounded-[12px] p-6 sm:p-8 border border-primary flex flex-col justify-between space-y-4 shadow-xs">
                    <div class="space-y-3">
                        <span class="text-xs uppercase tracking-wider font-semibold text-white/80">Alokasi Dana</span>
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-white">Rp 84,5 Jt</div>
                        <div class="font-semibold text-base sm:text-lg leading-snug text-white">Dana terapi terkumpul</div>
                    </div>
                    <p class="text-xs sm:text-sm font-normal leading-relaxed text-white/90">
                        Seluruh dana digunakan untuk 620 jam sesi terapi okupasi, wicara, dan fisioterapi tanpa potongan perantara.
                    </p>
                </div>
            @endif
        </div>

    </div>
</section>
