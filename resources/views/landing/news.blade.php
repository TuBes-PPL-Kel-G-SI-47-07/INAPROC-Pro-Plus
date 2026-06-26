<!-- Berita Terbaru Section -->
<section id="berita" class="py-20 bg-slate-50 border-t border-slate-100 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 scroll-reveal">
            <div class="max-w-2xl">
                <h2 class="text-blue-600 font-semibold tracking-wide uppercase text-sm mb-2">Pusat Informasi</h2>
                <h3 class="text-3xl font-bold text-slate-900 sm:text-4xl">Berita & Pengumuman</h3>
            </div>
            <a href="#" class="hidden md:inline-flex items-center text-blue-600 font-semibold hover:text-blue-800 transition-colors">
                Lihat Semua Berita
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <!-- Horizontal scroll wrapper for cards without Swiper dependency for simplicity -->
        <div class="flex overflow-x-auto gap-6 pb-8 snap-x snap-mandatory scrollbar-hide" style="scrollbar-width: none; -ms-overflow-style: none;">
            @php
                // Dummy News Data
                $dummyNews = [
                    ['title' => 'Sosialisasi Penggunaan Produk Dalam Negeri (PDN) 2026', 'date' => '24 Jun 2026', 'summary' => 'Pemerintah kembali menegaskan komitmen penggunaan PDN dalam setiap proses pengadaan barang dan jasa...', 'image' => 'https://images.unsplash.com/photo-1577563908411-5077b6dc7624?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'],
                    ['title' => 'Update Sistem INAPROC+ v2.4: Fitur Auto-Scoring Terbaru', 'date' => '20 Jun 2026', 'summary' => 'Pembaruan sistem terbaru mencakup peningkatan algoritma auto-scoring untuk evaluasi tender yang lebih presisi...', 'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'],
                    ['title' => 'Penghargaan Tata Kelola Pengadaan Terbaik 2026', 'date' => '15 Jun 2026', 'summary' => 'Penganugerahan bagi instansi dan vendor dengan rekam jejak tata kelola pengadaan yang bersih dan transparan...', 'image' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'],
                ];
                $newsData = isset($news) && count($news) > 0 ? $news : $dummyNews;
            @endphp

            @foreach($newsData as $item)
                @php
                    $nTitle = is_array($item) ? $item['title'] : $item->title;
                    $nDate = is_array($item) ? $item['date'] : $item->created_at->format('d M Y');
                    $nSummary = is_array($item) ? $item['summary'] : Str::limit($item->content, 100);
                    $nImage = is_array($item) ? $item['image'] : (isset($item->image_url) ? $item->image_url : 'https://via.placeholder.com/600x400');
                @endphp
                <div class="snap-start flex-none w-80 md:w-96 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group scroll-reveal cursor-pointer flex flex-col">
                    <div class="relative h-48 overflow-hidden bg-slate-100">
                        <img src="{{ $nImage }}" alt="News image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="text-xs font-semibold text-blue-600 mb-3 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $nDate }}
                        </div>
                        <h4 class="font-bold text-slate-900 text-lg mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $nTitle }}</h4>
                        <p class="text-slate-600 text-sm line-clamp-3 mb-6">{{ $nSummary }}</p>
                        
                        <div class="mt-auto">
                            <span class="inline-flex items-center text-sm font-semibold text-blue-600 group-hover:text-blue-800">
                                Baca Selengkapnya
                                <svg class="ml-1 w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <style>
            /* Hide scrollbar for Chrome, Safari and Opera */
            .scrollbar-hide::-webkit-scrollbar {
                display: none;
            }
        </style>
    </div>
</section>
