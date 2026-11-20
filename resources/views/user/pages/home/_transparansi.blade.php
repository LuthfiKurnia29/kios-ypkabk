{{-- Section: Cerita dari Sahabat Kios / Testimoni (Latar #FFFBF7 Sesuai PDF Halaman 1) --}}
<section id="cerita" class="w-full bg-[#FFFBF7] py-16 lg:py-20 font-sans">
    <div class="max-w-[1200px] mx-auto px-6 space-y-10">

        {{-- Header Section with WhatsApp Notice --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="space-y-3 max-w-2xl">
                <span class="text-xs uppercase tracking-wider font-semibold text-primary">
                    Suara Komunitas
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#2B2430] tracking-tight">
                    Cerita dari Sahabat Kios
                </h2>
                <p class="text-sm sm:text-base text-[#2B2430]/75 font-normal leading-relaxed">
                    Pengalaman nyata para donatur dan pembeli terverifikasi dalam mendukung kemandirian adik-adik berkebutuhan khusus.
                </p>
            </div>
            <div class="shrink-0">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-[#2B2430]/5 text-[#2B2430]/70 text-xs font-medium">
                    Ulasan dikirim lewat WhatsApp setelah transaksi selesai
                </span>
            </div>
        </div>

        {{-- Grid Ulasan (Maksimal 6 ulasan terverifikasi) --}}
        @if(isset($reviews) && $reviews->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
                @foreach($reviews->take(6) as $review)
                    <div class="bg-white rounded-[16px] p-6 sm:p-7 border border-[#2B2430]/10 flex flex-col justify-between space-y-5 shadow-xs hover:shadow-md transition-shadow h-full">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $review->source === 'buyer' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-pink-50 text-primary border border-pink-200' }}">
                                    {{ $review->verified_label }}
                                </span>
                                @if($review->created_at)
                                    <span class="text-[11px] text-gray-400">
                                        {{ $review->created_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                            <blockquote class="text-sm sm:text-base font-normal leading-relaxed text-[#2B2430]/85 italic">
                                &ldquo;{{ $review->quote }}&rdquo;
                            </blockquote>
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-[#2B2430]/10">
                            {{-- Inisial di Lingkaran Lembut (Tanpa Foto) --}}
                            <div class="w-10 h-10 rounded-full bg-[#FDEEF4] text-primary font-bold text-sm flex items-center justify-center shrink-0 border border-primary/20">
                                {{ $review->initials ?: substr($review->name ?? 'U', 0, 2) }}
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-semibold text-sm text-[#2B2430] truncate">
                                    {{ $review->display_name }}
                                </h3>
                                <p class="text-xs text-[#2B2430]/60 font-normal truncate">
                                    {{ $review->role ?: ($review->source === 'buyer' ? 'Pembeli Karya ABK' : 'Donatur Peduli') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- State Kosong: Belum ada ulasan aktif (Tanpa ikon ilustrasi dekoratif) --}}
            <div class="bg-white rounded-2xl border border-gray-200/80 p-8 sm:p-10 text-center max-w-xl mx-auto shadow-xs space-y-3">
                <div class="space-y-1.5">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900">Belum Ada Cerita yang Ditampilkan</h3>
                    <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto leading-relaxed">
                        Pernah berbelanja karya kriya atau berdonasi barang preloved di Kios YPKABK? Cerita dan pengalaman Anda sangat berarti untuk memotivasi anak-anak binaan.
                    </p>
                </div>
                <div class="pt-2">
                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-[#2B2430]/5 text-[#2B2430]/75 text-xs font-semibold">
                        Ulasan dikirim lewat WhatsApp setelah transaksi selesai
                    </span>
                </div>
            </div>
        @endif

    </div>
</section>
