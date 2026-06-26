<!-- CTA Section -->
<section class="py-24 relative overflow-hidden bg-slate-900">
    <!-- Dynamic Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-r from-blue-700 via-blue-900 to-slate-900 opacity-90 z-0"></div>
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 z-0 mix-blend-overlay"></div>
    
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center scroll-reveal">
        <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight">
            Siap Bergabung dengan Ekosistem INAPROC+?
        </h2>
        <p class="text-xl text-blue-100/90 mb-10 max-w-3xl mx-auto leading-relaxed">
            Daftar sekarang dan jadilah bagian dari transformasi digital pengadaan nasional. Proses cepat, mudah, dan transparan.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold rounded-xl text-blue-900 bg-white hover:bg-slate-50 focus:ring-4 focus:ring-white/50 shadow-xl transition-all hover:-translate-y-1">
                Daftar Sekarang Gratis
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
            @endif
            <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-lg font-bold rounded-xl text-white bg-white/10 border border-white/20 hover:bg-white/20 backdrop-blur-md focus:ring-4 focus:ring-white/20 transition-all hover:-translate-y-1">
                Masuk ke Dashboard
            </a>
        </div>
        <p class="mt-6 text-sm text-blue-200/70">Telah dipercaya oleh lebih dari 12,000 instansi dan penyedia.</p>
    </div>
</section>
