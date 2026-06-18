<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'INAPROC+') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900" x-data="{ sidebarOpen: true }">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="flex-shrink-0 flex flex-col bg-blue-800 text-white transition-all duration-300 ease-in-out shadow-lg z-20">
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center justify-between px-4 border-b border-blue-700 bg-blue-900">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <!-- Logo / Icon -->
                    <div class="w-8 h-8 rounded bg-blue-600 flex items-center justify-center font-bold text-white shadow-sm shrink-0">
                        IN
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-lg tracking-wider truncate">INAPROC<span class="text-emerald-500">+</span></span>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="text-blue-200 hover:text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-700 text-white shadow' : 'text-blue-100 hover:bg-blue-700/50 hover:text-white' }}">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3 font-medium">Dashboard</span>
                </a>

                <!-- Add other menu items here progressively as we move through PBI tasks -->
                <a href="#" class="flex items-center px-4 py-3 mx-2 rounded-lg transition-colors duration-200 text-blue-100 hover:bg-blue-700/50 hover:text-white">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span x-show="sidebarOpen" class="ml-3 font-medium">Pengadaan</span>
                </a>
            </nav>
            
            <!-- User Info (Bottom of sidebar) -->
            <div class="p-4 border-t border-blue-700">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-sm font-bold uppercase shrink-0">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div x-show="sidebarOpen" class="flex flex-col truncate">
                        <span class="text-sm font-medium">{{ Auth::user()->name ?? 'User' }}</span>
                        <span class="text-xs text-blue-300 truncate">{{ Auth::user()->email ?? '' }}</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content area -->
        <div class="flex-1 flex flex-col overflow-hidden bg-slate-50">
            <!-- Top Navbar -->
            <header class="h-16 flex items-center justify-between px-6 bg-white shadow-sm z-10 border-b border-slate-200">
                <!-- Breadcrumb / Page Title -->
                <div class="flex items-center text-sm text-slate-500 font-medium space-x-2">
                    <span class="text-blue-600 hover:underline cursor-pointer">Home</span>
                    <span>/</span>
                    <span class="text-slate-800">
                        @if (isset($header))
                            {{ $header }}
                        @else
                            @yield('title', 'Dashboard')
                        @endif
                    </span>
                </div>

                <!-- Right Nav -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="text-slate-400 hover:text-blue-600 transition-colors relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                    </button>
                    
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <img class="w-8 h-8 rounded-full border border-slate-200" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=0D8ABC&color=fff" alt="User Avatar">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div x-show="open" x-transition.duration.200ms style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-red-600">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Scrollable Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
                <!-- Page Content -->
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
