<!-- Produk Unggulan Section -->
<section id="produk" class="py-20 bg-white border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 scroll-reveal">
            <div class="max-w-2xl">
                <h2 class="text-blue-600 font-semibold tracking-wide uppercase text-sm mb-2">Katalog</h2>
                <h3 class="text-3xl font-bold text-slate-900 sm:text-4xl">Produk Unggulan</h3>
                <p class="mt-4 text-slate-600">Daftar produk dengan rating terbaik dan memenuhi standar PDN serta UMKM.</p>
            </div>
            <a href="#" class="hidden md:inline-flex items-center text-blue-600 font-semibold hover:text-blue-800 transition-colors">
                Lihat Semua Produk
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @php
                // Dummy data
                $dummyProducts = [
                    ['name' => 'Laptop Bisnis Pro 14"', 'supplier' => 'PT. Teknologi Nusantara', 'price' => 'Rp 15.000.000', 'pdn' => true, 'umkm' => false, 'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
                    ['name' => 'Meja Kerja Ergonomis', 'supplier' => 'CV. Kayu Jati Mebel', 'price' => 'Rp 2.500.000', 'pdn' => true, 'umkm' => true, 'image' => 'https://images.unsplash.com/photo-1595515106969-1ce29566ff1c?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
                    ['name' => 'Kertas HVS A4 80gr', 'supplier' => 'PT. Kertas Indonesia', 'price' => 'Rp 55.000', 'pdn' => true, 'umkm' => false, 'image' => 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
                    ['name' => 'Jasa Pembersihan Gedung', 'supplier' => 'CV. Bersih Kilau', 'price' => 'Rp 10.000.000', 'pdn' => false, 'umkm' => true, 'image' => 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80'],
                ];
                $prods = isset($products) && count($products) > 0 ? $products : $dummyProducts;
            @endphp

            @foreach($prods as $prod)
                @php
                    $pName = is_array($prod) ? $prod['name'] : $prod->name;
                    $pSupplier = is_array($prod) ? $prod['supplier'] : $prod->supplier_name;
                    $pPrice = is_array($prod) ? $prod['price'] : 'Rp ' . number_format($prod->price, 0, ',', '.');
                    $isPdn = is_array($prod) ? $prod['pdn'] : $prod->is_pdn;
                    $isUmkm = is_array($prod) ? $prod['umkm'] : $prod->is_umkm;
                    $image = is_array($prod) ? $prod['image'] : (isset($prod->image_url) ? $prod->image_url : 'https://via.placeholder.com/500x300?text=No+Image');
                @endphp
                <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col h-full scroll-reveal">
                    <!-- Image Container with Zoom effect -->
                    <div class="relative h-48 overflow-hidden bg-slate-100">
                        <img src="{{ $image }}" alt="{{ $pName }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 lazy">
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-col gap-2">
                            @if($isPdn)
                                <span class="px-2.5 py-1 bg-red-600/90 backdrop-blur-sm text-white text-xs font-bold rounded-lg shadow-sm border border-red-500">PDN</span>
                            @endif
                            @if($isUmkm)
                                <span class="px-2.5 py-1 bg-purple-600/90 backdrop-blur-sm text-white text-xs font-bold rounded-lg shadow-sm border border-purple-500">UMKM</span>
                            @endif
                        </div>
                        
                        <!-- Hover Overlay Button -->
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <button class="px-5 py-2.5 bg-white text-slate-900 font-semibold rounded-xl shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:bg-blue-50 hover:text-blue-600">
                                Lihat Detail
                            </button>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="text-sm text-slate-500 mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            {{ $pSupplier }}
                        </div>
                        <h4 class="font-bold text-slate-800 text-lg mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $pName }}</h4>
                        <div class="mt-auto">
                            <div class="text-lg font-extrabold text-slate-900">{{ $pPrice }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-10 text-center md:hidden">
            <a href="#" class="inline-flex items-center justify-center px-6 py-3 border border-slate-200 text-base font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                Lihat Semua Produk
            </a>
        </div>
    </div>
</section>
