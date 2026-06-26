<!-- Penyedia Terpercaya Section -->
<section id="penyedia" class="py-20 bg-slate-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 scroll-reveal">
            <div class="max-w-2xl">
                <h2 class="text-emerald-600 font-semibold tracking-wide uppercase text-sm mb-2">Mitra Kami</h2>
                <h3 class="text-3xl font-bold text-slate-900 sm:text-4xl">Penyedia Terpercaya</h3>
                <p class="mt-4 text-slate-600">Bermitra dengan ribuan penyedia kualifikasi terbaik dari seluruh Indonesia.</p>
            </div>
            <a href="#" class="hidden md:inline-flex items-center text-emerald-600 font-semibold hover:text-emerald-800 transition-colors">
                Lihat Direktori Penyedia
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                // Dummy Data
                $dummySuppliers = [
                    ['name' => 'PT. Teknologi Nusantara', 'logo' => 'TN', 'products_count' => 1250, 'category' => 'Barang IT', 'rating' => 4.9],
                    ['name' => 'CV. Bina Karya Konstruksi', 'logo' => 'BK', 'products_count' => 45, 'category' => 'Konstruksi', 'rating' => 4.8],
                    ['name' => 'PT. Sejahtera Abadi', 'logo' => 'SA', 'products_count' => 830, 'category' => 'ATK & Elektronik', 'rating' => 4.7],
                    ['name' => 'CV. Maju Bersama', 'logo' => 'MB', 'products_count' => 120, 'category' => 'Jasa Kebersihan', 'rating' => 4.6],
                    ['name' => 'PT. Inovasi Medika', 'logo' => 'IM', 'products_count' => 340, 'category' => 'Alat Kesehatan', 'rating' => 4.9],
                    ['name' => 'Koperasi Karya Mandiri', 'logo' => 'KM', 'products_count' => 85, 'category' => 'Furnitur', 'rating' => 4.8],
                ];
                $sups = isset($suppliers) && count($suppliers) > 0 ? $suppliers : $dummySuppliers;
            @endphp

            @foreach($sups as $sup)
                @php
                    $sName = is_array($sup) ? $sup['name'] : $sup->name;
                    $sLogo = is_array($sup) ? $sup['logo'] : substr($sup->name, 0, 2);
                    $sCount = is_array($sup) ? $sup['products_count'] : (isset($sup->products_count) ? $sup->products_count : 0);
                    $sCat = is_array($sup) ? $sup['category'] : (isset($sup->category) ? $sup->category : 'General');
                    $sRating = is_array($sup) ? $sup['rating'] : (isset($sup->rating) ? $sup->rating : 0);
                    // Generate pseudo random color based on name length
                    $colors = ['blue', 'emerald', 'purple', 'amber', 'rose', 'indigo'];
                    $color = $colors[strlen($sName) % count($colors)];
                @endphp
                
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex items-center gap-5 scroll-reveal group cursor-pointer">
                    <!-- Logo Avatar -->
                    <div class="w-16 h-16 rounded-2xl bg-{{ $color }}-50 text-{{ $color }}-600 font-bold text-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 group-hover:bg-{{ $color }}-100 transition-all duration-300">
                        {{ strtoupper($sLogo) }}
                    </div>
                    
                    <div class="flex-grow min-w-0">
                        <h4 class="font-bold text-slate-800 text-base truncate group-hover:text-{{ $color }}-600 transition-colors">{{ $sName }}</h4>
                        <div class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                {{ $sCount }} Produk
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                {{ $sCat }}
                            </span>
                        </div>
                    </div>
                    
                    @if($sRating > 0)
                    <div class="flex-shrink-0 flex items-center bg-amber-50 px-2 py-1 rounded-md text-amber-600 text-xs font-bold border border-amber-100">
                        <svg class="w-3.5 h-3.5 mr-0.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        {{ $sRating }}
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <div class="mt-10 text-center md:hidden">
            <a href="#" class="inline-flex items-center justify-center px-6 py-3 border border-slate-200 text-base font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                Lihat Direktori Penyedia
            </a>
        </div>
    </div>
</section>
