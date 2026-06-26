<!-- Dashboard Statistik Section -->
<section id="statistik" class="py-24 bg-blue-900 relative overflow-hidden">
    <!-- Background Patterns -->
    <div class="absolute inset-0 opacity-10">
        <svg class="absolute h-full w-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid-pattern" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid-pattern)" />
        </svg>
    </div>
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-blue-500/20 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-emerald-500/20 blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16 scroll-reveal">
            <h2 class="text-blue-300 font-semibold tracking-wide uppercase text-sm mb-2">Transparansi Data</h2>
            <h3 class="text-3xl font-bold text-white sm:text-4xl">Statistik E-Procurement</h3>
            <p class="mt-4 text-blue-100/80 max-w-2xl mx-auto">Pantau perkembangan pengadaan nasional secara real-time dan terukur.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8">
            @php
                // Dummy Data for Statistics
                $dummyStats = [
                    ['label' => 'Total Produk', 'value' => '45.2K', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'prefix' => ''],
                    ['label' => 'Total Penyedia', 'value' => '12.4K', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'prefix' => ''],
                    ['label' => 'Nilai Transaksi', 'value' => '2.5T', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'prefix' => 'Rp '],
                    ['label' => 'Transaksi Selesai', 'value' => '1.8M', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'prefix' => ''],
                ];
                $statsData = isset($statistics) && count($statistics) > 0 ? $statistics : $dummyStats;
            @endphp

            @foreach($statsData as $stat)
                @php
                    $label = is_array($stat) ? $stat['label'] : $stat->label;
                    $value = is_array($stat) ? $stat['value'] : $stat->value;
                    $icon = is_array($stat) ? $stat['icon'] : 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6';
                    $prefix = is_array($stat) ? $stat['prefix'] : '';
                @endphp
                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 md:p-8 border border-white/10 text-center shadow-2xl hover:bg-white/20 transition-all duration-300 scroll-reveal">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-white/10 text-blue-200 flex items-center justify-center mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"></path>
                        </svg>
                    </div>
                    <div class="text-3xl md:text-4xl font-black text-white mb-2 tracking-tight">
                        {{ $prefix }}<span class="counter">{{ $value }}</span>
                    </div>
                    <div class="text-sm md:text-base text-blue-200 font-medium">{{ $label }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
