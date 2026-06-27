<!-- Navbar -->
<nav id="navbar" class="fixed z-50 left-1/2 -translate-x-1/2 top-0 w-full bg-transparent border-transparent" 
     style="transition: all 500ms cubic-bezier(0.22, 1, 0.36, 1);">
    <div id="navbar-container" class="mx-auto px-4 sm:px-6 lg:px-8 w-full" style="transition: all 500ms cubic-bezier(0.22, 1, 0.36, 1);">
        <div id="navbar-inner" class="flex justify-between items-center h-20 transition-all" style="transition: height 500ms cubic-bezier(0.22, 1, 0.36, 1);">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center gap-3">
                <div id="logo-icon" class="w-10 h-10 rounded-lg bg-blue-700 flex items-center justify-center font-bold text-white shadow-md transition-all" style="transition: all 500ms cubic-bezier(0.22, 1, 0.36, 1);">
                    IN
                </div>
                <span id="logo-text" class="font-bold text-2xl tracking-tight text-white transition-all" style="transition: all 500ms cubic-bezier(0.22, 1, 0.36, 1);">
                    INAPROC<span class="text-emerald-400">+</span>
                </span>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-sm font-medium nav-link relative transition-colors">Beranda</a>
                <a href="#produk" class="text-sm font-medium nav-link relative transition-colors">Temukan Produk</a>
                <a href="#penyedia" class="text-sm font-medium nav-link relative transition-colors">Penyedia</a>
                <a href="#statistik" class="text-sm font-medium nav-link relative transition-colors">Dashboard PDN</a>
                <a href="#berita" class="text-sm font-medium nav-link relative transition-colors">Berita</a>
            </div>

            <!-- Auth Buttons Desktop -->
            <div class="hidden md:flex items-center space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-5 py-2 text-sm font-medium rounded-full text-white bg-blue-600 hover:bg-blue-700 shadow-md transition-all">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium nav-link transition-colors">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-glow inline-flex items-center justify-center px-6 py-2.5 text-sm font-bold rounded-full text-white bg-blue-600 hover:bg-blue-500 transition-all hover:-translate-y-0.5">
                                Daftar
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="nav-link focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden bg-white shadow-lg absolute w-full left-0 top-full rounded-b-2xl transition-all origin-top transform scale-y-0 opacity-0" style="transition: all 400ms cubic-bezier(0.22, 1, 0.36, 1);">
        <div class="px-4 py-4 space-y-2">
            <a href="#" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Beranda</a>
            <a href="#produk" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Temukan Produk</a>
            <a href="#penyedia" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Penyedia</a>
            <a href="#statistik" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Dashboard PDN</a>
            <a href="#berita" class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50">Berita</a>
            <div class="pt-4 pb-2 border-t border-slate-100 flex flex-col gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full text-center px-4 py-2.5 rounded-xl shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="w-full text-center px-4 py-2.5 rounded-xl border border-slate-200 text-base font-medium text-slate-700 bg-white hover:bg-slate-50">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 rounded-xl shadow-sm text-base font-bold text-white bg-blue-600 hover:bg-blue-700">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

<style>
    /* 
      DYNAMIC ISLAND MORPH STYLES
      These classes are applied to the #navbar on scroll 
    */
    .navbar-scrolled {
        width: 80% !important;
        max-width: 1024px !important;
        top: 20px !important;
        border-radius: 9999px !important; /* capsule */
        background-color: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.15) !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
    }
    
    /* When on mobile, don't use dynamic island, just make it sticky top */
    @media (max-width: 768px) {
        .navbar-scrolled {
            width: 100% !important;
            max-width: 100% !important;
            top: 0 !important;
            border-radius: 0 !important;
            border-bottom: 1px solid rgba(0,0,0,0.05) !important;
        }
    }

    /* Inner adjustments when scrolled */
    .navbar-scrolled #navbar-inner {
        height: 4rem !important; /* h-16 */
    }
    .navbar-scrolled #navbar-container {
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
    }

    /* Logo Shrink */
    .navbar-scrolled #logo-icon {
        width: 2rem !important; /* 32px */
        height: 2rem !important;
        font-size: 0.875rem !important;
    }
    .navbar-scrolled #logo-text {
        font-size: 1.25rem !important; /* 20px */
        color: #1e293b !important; /* slate-800 */
    }
    .navbar-scrolled #logo-text span {
        color: #10b981 !important; 
    }

    /* Nav Links Color */
    .nav-link {
        color: rgba(255, 255, 255, 0.95);
    }
    .nav-link:hover {
        color: #ffffff;
    }
    .navbar-scrolled .nav-link {
        color: #475569 !important; /* slate-600 */
    }
    .navbar-scrolled .nav-link:hover {
        color: #2563eb !important; /* blue-600 */
    }

    /* Hover Underline Animation */
    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -4px;
        left: 50%;
        background-color: currentColor;
        transition: all 300ms cubic-bezier(0.22, 1, 0.36, 1);
        transform: translateX(-50%);
        opacity: 0;
    }
    .nav-link:hover::after {
        width: 100%;
        opacity: 1;
    }

    /* Daftar Button Glow */
    .btn-glow {
        box-shadow: 0 4px 14px 0 rgba(37, 99, 235, 0.39);
    }
    .btn-glow:hover {
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.6);
    }
</style>
