<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'INAPROC+') }} | Secure Access</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 antialiased bg-white">
    <div class="min-h-screen flex">
        
        <!-- Form Area (Left Side) -->
        <div class="flex-1 flex flex-col justify-center px-4 sm:px-6 lg:flex-none lg:w-1/2 xl:w-5/12 bg-white relative z-10 shadow-2xl">
            <div class="mx-auto w-full max-w-sm lg:w-96 py-12">
                <!-- Mobile Logo (Hidden on Desktop) -->
                <div class="lg:hidden mb-8 flex items-center gap-2">
                    <div class="w-8 h-8 rounded bg-blue-700 flex items-center justify-center font-bold text-white shadow-sm">
                        IN
                    </div>
                    <span class="font-bold text-xl tracking-tight text-blue-900">INAPROC<span class="text-emerald-500">+</span></span>
                </div>
                
                {{ $slot }}
                
            </div>
        </div>

        <!-- Branding Area (Right Side) -->
        <div class="hidden lg:flex flex-1 relative bg-gradient-to-br from-blue-900 to-blue-700 overflow-hidden items-center justify-center">
            <!-- Geometric Patterns -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 32px 32px;"></div>
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            
            <div class="relative z-10 text-center px-12 max-w-2xl">
                <div class="flex justify-center mb-8">
                    <div class="w-20 h-20 rounded-2xl bg-white flex items-center justify-center font-black text-blue-700 text-3xl shadow-2xl transform -rotate-6 transition-transform hover:rotate-0">
                        IN
                    </div>
                </div>
                <h2 class="text-4xl font-extrabold text-white tracking-tight mb-4">Enterprise E-Procurement System</h2>
                <p class="text-lg text-blue-100 font-medium leading-relaxed">
                    Mewujudkan pengadaan yang transparan, akuntabel, dan aman dengan teknologi Sealed Bidding & Auto-Scoring berbasis aturan.
                </p>
                <div class="mt-12 flex items-center justify-center space-x-4">
                    <span class="px-4 py-2 rounded-full bg-blue-800/50 border border-blue-600/50 text-blue-200 text-sm font-semibold backdrop-blur-sm">
                        AES-256 Secured
                    </span>
                    <span class="px-4 py-2 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-sm font-semibold backdrop-blur-sm">
                        Immutable Audit Trail
                    </span>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>
