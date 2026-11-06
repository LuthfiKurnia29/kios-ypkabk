{{-- Section: Cerita dari Sahabat Kios / Testimoni (Latar #FFFBF7 Sesuai PDF Halaman 1) --}}
<section id="cerita" class="w-full bg-[#FFFBF7] py-16 lg:py-20 font-sans">
    <div class="max-w-[1200px] mx-auto px-6 space-y-10">

        {{-- Header Section --}}
        <div class="space-y-3 max-w-2xl">
            <span class="text-xs uppercase tracking-wider font-semibold text-primary">
                Suara Komunitas
            </span>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#2B2430] tracking-tight">
                Cerita dari Sahabat Kios
            </h2>
            <p class="text-sm sm:text-base text-[#2B2430]/75 font-normal leading-relaxed">
                Pengalaman donatur dan pembeli dalam mendukung program kemandirian anak.
            </p>
        </div>

        {{-- 3 Kartu Putih Berjejer (Tinggi Sama, Inisial di Lingkaran Kecil, Tanpa Foto) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-stretch">
            @if(isset($reviews) && $reviews->count() > 0)
                @foreach($reviews->take(3) as $review)
                    <div class="bg-white rounded-[12px] p-6 sm:p-8 border border-[#2B2430]/10 flex flex-col justify-between space-y-6 shadow-xs h-full">
                        <div class="space-y-3">
                            <span class="text-xs font-semibold text-primary block">
                                {{ str_contains(strtolower($review->role ?? ''), 'pembeli') ? 'Ulasan Pembeli' : 'Ulasan Donatur' }}
                            </span>
                            <blockquote class="text-sm sm:text-base font-normal leading-relaxed text-[#2B2430]/85">
                                &ldquo;{{ $review->quote }}&rdquo;
                            </blockquote>
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-[#2B2430]/10">
                            {{-- Inisial di Lingkaran Kecil (Tanpa Foto) --}}
                            <div class="w-10 h-10 rounded-full bg-[#FDEEF4] text-primary font-bold text-sm flex items-center justify-center shrink-0 border border-primary/20">
                                {{ $review->initials ?: substr($review->name ?? 'U', 0, 2) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-sm text-[#2B2430] truncate">
                                    {{ $review->name }}
                                </h3>
                                <p class="text-xs text-[#2B2430]/60 font-normal truncate">
                                    {{ $review->role }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Fallback jika data database kosong --}}
                <div class="bg-white rounded-[12px] p-6 sm:p-8 border border-[#2B2430]/10 flex flex-col justify-between space-y-6 shadow-xs h-full">
                    <div class="space-y-3">
                        <span class="text-xs font-semibold text-primary block">Ulasan Donatur</span>
                        <blockquote class="text-sm sm:text-base font-normal leading-relaxed text-[#2B2430]/85">
                            &ldquo;Proses donasi pakaian kerja di Kios Mandiri tertata jelas dan komunikatif. Mengetahui hasilnya disalurkan untuk jam terapi adik-adik di Sidoarjo membuat donasi terasa bermakna.&rdquo;
                        </blockquote>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-[#2B2430]/10">
                        <div class="w-10 h-10 rounded-full bg-[#FDEEF4] text-primary font-bold text-sm flex items-center justify-center shrink-0 border border-primary/20">
                            RW
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-sm text-[#2B2430] truncate">Ibu Rina Wardhani</h3>
                            <p class="text-xs text-[#2B2430]/60 font-normal truncate">Donatur Preloved &bull; Surabaya</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[12px] p-6 sm:p-8 border border-[#2B2430]/10 flex flex-col justify-between space-y-6 shadow-xs h-full">
                    <div class="space-y-3">
                        <span class="text-xs font-semibold text-primary block">Ulasan Pembeli</span>
                        <blockquote class="text-sm sm:text-base font-normal leading-relaxed text-[#2B2430]/85">
                            &ldquo;Tote bag karya Dimas rapi dan motifnya bagus untuk tas kerja harian. Membeli produk ini membantu keberlanjutan sanggar kriya anak-anak istimewa.&rdquo;
                        </blockquote>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-[#2B2430]/10">
                        <div class="w-10 h-10 rounded-full bg-[#FDEEF4] text-primary font-bold text-sm flex items-center justify-center shrink-0 border border-primary/20">
                            DW
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-sm text-[#2B2430] truncate">Mas Danang Wicaksono</h3>
                            <p class="text-xs text-[#2B2430]/60 font-normal truncate">Pembeli &bull; Mojokerto</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[12px] p-6 sm:p-8 border border-[#2B2430]/10 flex flex-col justify-between space-y-6 shadow-xs h-full">
                    <div class="space-y-3">
                        <span class="text-xs font-semibold text-primary block">Ulasan Pembeli</span>
                        <blockquote class="text-sm sm:text-base font-normal leading-relaxed text-[#2B2430]/85">
                            &ldquo;Kualitas produk kriya anak-anak sangat memuaskan, kemasan ramah lingkungan, dan layanan pengirimannya cepat. Sangat merekomendasikan berbelanja sambil berdonasi di sini.&rdquo;
                        </blockquote>
                    </div>
                    <div class="flex items-center gap-3 pt-4 border-t border-[#2B2430]/10">
                        <div class="w-10 h-10 rounded-full bg-[#FDEEF4] text-primary font-bold text-sm flex items-center justify-center shrink-0 border border-primary/20">
                            EW
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-semibold text-sm text-[#2B2430] truncate">Ibu Endah Wulandari</h3>
                            <p class="text-xs text-[#2B2430]/60 font-normal truncate">Pembeli &bull; Mojokerto</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</section>
