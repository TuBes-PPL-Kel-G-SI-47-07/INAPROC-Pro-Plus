<!-- Navbar -->
<nav id="navbar" class="fixed w-full z-50 bg-transparent transition-all duration-300 ease-in-out border-b border-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center transition-all duration-300" id="navbar-container">
            <div class="flex-shrink-0 flex items-center gap-3">
                <div id="logo-icon" class="w-10 h-10 rounded-lg bg-blue-700 flex items-center justify-center font-bold text-white shadow-md transition-all duration-300">
                    IN
                </div>
                <span id="logo-text" class="font-bold text-2xl tracking-tight text-white transition-all duration-300">INAPROC<span class="text-emerald-400">+</span></span>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="#" class="text-sm font-medium nav-link hover:text-blue-600 transition-colors">Beranda</a>
                <a href="#produk" class="text-sm font-medium nav-link hover:text-blue-600 transition-colors">Temukan Produk</a>
                <a href="#penyedia" class="text-sm font-medium nav-link hover:text-blue-600 transition-colors">Penyedia</a>
                <a href="#statistik" class="text-sm font-medium nav-link hover:text-blue-600 transition-colors">Dashboard PDN</a>
                <a href="#berita" class="text-sm font-medium nav-link hover:text-blue-600 transition-colors">Berita</a>
                <a href="#" class="text-sm font-medium nav-link hover:text-blue-600 transition-colors">Pusat Bantuan</a>
            </div>

            <!-- Auth Buttons Desktop -->
            <div class="hidden md:flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-md transition-all">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium nav-link hover:text-blue-600 transition-colors">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-md transition-all hover:shadow-lg hover:-translate-y-0.5">
                                Daftar
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="nav-link hover:text-blue-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-slate-100 shadow-lg absolute w-full transition-all duration-300 origin-top transform scale-y-0 opacity-0">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Beranda</a>
            <a href="#produk" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Temukan Produk</a>
            <a href="#penyedia" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Penyedia</a>
            <a href="#statistik" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Dashboard PDN</a>
            <a href="#berita" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Berita</a>
            <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Pusat Bantuan</a>
            <div class="pt-4 pb-2 border-t border-slate-100 flex flex-col gap-2 px-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="w-full text-center px-4 py-2 border border-slate-300 rounded-md shadow-sm text-base font-medium text-slate-700 bg-white hover:bg-slate-50">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

<style>
    /* Add this class via JS on scroll */
    .navbar-scrolled {
        background-color: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(8px);
        border-color: #e2e8f0 !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    .navbar-scrolled #logo-icon {
        width: 2.2rem; 
        height: 2.2rem;
    }
    .navbar-scrolled #logo-text {
        font-size: 1.35rem; 
        color: #1e3a8a !important; 
    }
    .navbar-scrolled #logo-text span {
        color: #10b981 !important; 
    }
    .navbar-scrolled .nav-link {
        color: #475569 !important; 
    }
    .navbar-scrolled .nav-link:hover {
        color: #2563eb !important; 
    }
    /* Initially white text if not scrolled */
    .nav-link {
        color: rgba(255, 255, 255, 0.95);
    }
    .nav-link:hover {
        color: #ffffff;
    }
</style>
