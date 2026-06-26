<!-- Panduan Singkat Section -->
<section class="py-24 bg-white border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16 scroll-reveal">
            <h2 class="text-emerald-600 font-semibold tracking-wide uppercase text-sm mb-2">Alur Pengadaan</h2>
            <h3 class="text-3xl font-bold text-slate-900 sm:text-4xl">Cara Kerja INAPROC+</h3>
            <p class="mt-4 text-slate-600">Proses pengadaan yang disederhanakan namun tetap mematuhi regulasi dan standar kepatuhan.</p>
        </div>

        <div class="relative max-w-5xl mx-auto">
            <!-- Connecting Line -->
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-slate-100 -translate-y-1/2 z-0"></div>
            <div class="hidden md:block absolute top-1/2 left-0 w-0 h-1 bg-gradient-to-r from-blue-500 to-emerald-500 -translate-y-1/2 z-0 transition-all duration-1000 ease-out" id="timeline-progress"></div>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-8 md:gap-4 relative z-10">
                <!-- Step 1 -->
                <div class="text-center scroll-reveal relative group">
                    <div class="w-16 h-16 mx-auto rounded-full bg-white border-4 border-slate-100 text-slate-400 flex items-center justify-center mb-6 group-hover:border-blue-500 group-hover:text-blue-600 transition-all duration-300 shadow-sm relative z-10">
                        <span class="font-bold text-xl">1</span>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Cari Produk</h4>
                    <p class="text-sm text-slate-500">Temukan barang/jasa sesuai kebutuhan Anda.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center scroll-reveal relative group" style="animation-delay: 100ms;">
                    <div class="w-16 h-16 mx-auto rounded-full bg-white border-4 border-slate-100 text-slate-400 flex items-center justify-center mb-6 group-hover:border-blue-500 group-hover:text-blue-600 transition-all duration-300 shadow-sm relative z-10">
                        <span class="font-bold text-xl">2</span>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Pilih Penyedia</h4>
                    <p class="text-sm text-slate-500">Bandingkan rating dan kualifikasi vendor.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center scroll-reveal relative group" style="animation-delay: 200ms;">
                    <div class="w-16 h-16 mx-auto rounded-full bg-white border-4 border-slate-100 text-slate-400 flex items-center justify-center mb-6 group-hover:border-blue-500 group-hover:text-blue-600 transition-all duration-300 shadow-sm relative z-10">
                        <span class="font-bold text-xl">3</span>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Login / Daftar</h4>
                    <p class="text-sm text-slate-500">Masuk ke sistem dengan akun terverifikasi.</p>
                </div>

                <!-- Step 4 -->
                <div class="text-center scroll-reveal relative group" style="animation-delay: 300ms;">
                    <div class="w-16 h-16 mx-auto rounded-full bg-white border-4 border-slate-100 text-slate-400 flex items-center justify-center mb-6 group-hover:border-emerald-500 group-hover:text-emerald-600 transition-all duration-300 shadow-sm relative z-10">
                        <span class="font-bold text-xl">4</span>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Transaksi</h4>
                    <p class="text-sm text-slate-500">Proses negosiasi dan kontrak yang aman (AES-256).</p>
                </div>

                <!-- Step 5 -->
                <div class="text-center scroll-reveal relative group" style="animation-delay: 400ms;">
                    <div class="w-16 h-16 mx-auto rounded-full bg-white border-4 border-slate-100 text-slate-400 flex items-center justify-center mb-6 group-hover:border-emerald-500 group-hover:text-emerald-600 transition-all duration-300 shadow-sm relative z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-900 mb-2">Selesai</h4>
                    <p class="text-sm text-slate-500">Barang diterima dan tercatat di audit trail.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script for simple timeline animation on scroll -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const progressBar = document.getElementById('timeline-progress');
                    if(progressBar) {
                        setTimeout(() => {
                            progressBar.style.width = '100%';
                        }, 500);
                    }
                }
            });
        }, { threshold: 0.5 });
        
        const timelineSection = document.querySelector('#timeline-progress');
        if (timelineSection) {
            observer.observe(timelineSection.parentElement);
        }
    });
</script>
