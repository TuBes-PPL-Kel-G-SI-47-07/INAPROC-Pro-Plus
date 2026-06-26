<!-- Kategori Produk Section -->
<section id="kategori" class="py-20 bg-slate-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 scroll-reveal">
            <h2 class="text-blue-600 font-semibold tracking-wide uppercase text-sm mb-2">Eksplorasi</h2>
            <h3 class="text-3xl font-bold text-slate-900 sm:text-4xl">Kategori Pengadaan</h3>
            <p class="mt-4 text-slate-600 max-w-2xl mx-auto">Temukan berbagai kebutuhan pengadaan pemerintah yang terstruktur dalam kategori yang rapi.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @php
                // Dummy data fallback
                $dummyCategories = [
                    ['name' => 'Barang', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'color' => 'blue'],
                    ['name' => 'Jasa', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'emerald'],
                    ['name' => 'Konstruksi', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'amber'],
                    ['name' => 'Produk Dalam Negeri', 'icon' => 'M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9', 'color' => 'red'],
                    ['name' => 'UMKM', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'purple'],
                ];
                $cats = isset($categories) && count($categories) > 0 ? $categories : $dummyCategories;
            @endphp

            @foreach($cats as $cat)
                <a href="#" class="group bg-white rounded-2xl p-6 flex flex-col items-center justify-center text-center border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 scroll-reveal cursor-pointer">
                    @php 
                        $color = isset($cat['color']) ? $cat['color'] : 'blue'; 
                        $name = is_array($cat) ? $cat['name'] : $cat->name;
                        $iconPath = is_array($cat) ? $cat['icon'] : 'M4 6h16M4 10h16M4 14h16M4 18h16';
                    @endphp
                    <div class="w-16 h-16 rounded-2xl bg-{{ $color }}-50 text-{{ $color }}-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-{{ $color }}-100 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $iconPath }}"></path>
                        </svg>
                    </div>
                    <h4 class="font-semibold text-slate-800 group-hover:text-{{ $color }}-600 transition-colors">{{ $name }}</h4>
                </a>
            @endforeach
        </div>
    </div>
</section>
