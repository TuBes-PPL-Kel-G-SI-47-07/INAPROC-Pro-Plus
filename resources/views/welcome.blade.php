<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>INAPROC+ | Portal Pengadaan Nasional</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        
        /* Reveal Animation Classes */
        .reveal-active {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
    </style>
</head>
<body class="antialiased text-slate-900 bg-slate-50 selection:bg-blue-200 selection:text-blue-900">

    @include('landing.navbar')
    @include('landing.hero')
    @include('landing.category')
    @include('landing.featured-product')
    @include('landing.supplier')
    @include('landing.statistic')
    @include('landing.why-choose')
    @include('landing.news')
    @include('landing.guide')
    @include('landing.cta')
    @include('landing.footer')

    <!-- Vanilla JS for Animations & Interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Navbar Scroll Effect
            const navbar = document.getElementById('navbar');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('navbar-scrolled');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                }
            });
            // Initial check
            if (window.scrollY > 50) navbar.classList.add('navbar-scrolled');

            // 2. Mobile Menu Toggle
            let isMenuOpen = false;
            mobileMenuBtn.addEventListener('click', () => {
                isMenuOpen = !isMenuOpen;
                if (isMenuOpen) {
                    mobileMenu.classList.remove('hidden');
                    // Add slight delay to allow display:block to apply before transition
                    setTimeout(() => {
                        mobileMenu.classList.remove('scale-y-0', 'opacity-0');
                        mobileMenu.classList.add('scale-y-100', 'opacity-100');
                    }, 10);
                    menuIcon.setAttribute('d', 'M6 18L18 6M6 6l12 12'); // X icon
                    navbar.classList.add('navbar-scrolled');
                } else {
                    mobileMenu.classList.remove('scale-y-100', 'opacity-100');
                    mobileMenu.classList.add('scale-y-0', 'opacity-0');
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300);
                    menuIcon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16'); // Hamburger icon
                    if (window.scrollY <= 50) {
                        navbar.classList.remove('navbar-scrolled');
                    }
                }
            });

            // 3. Scroll Reveal Animation
            const revealElements = document.querySelectorAll('.scroll-reveal');
            
            const revealOptions = {
                threshold: 0.15,
                rootMargin: "0px 0px -50px 0px"
            };
            
            const revealObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal-active');
                        observer.unobserve(entry.target);
                    }
                });
            }, revealOptions);
            
            revealElements.forEach(el => {
                revealObserver.observe(el);
            });

            // 4. Counter Animation for Statistics
            const counters = document.querySelectorAll('.counter');
            let hasCounted = false;

            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !hasCounted) {
                        hasCounted = true;
                        counters.forEach(counter => {
                            const targetText = counter.innerText;
                            const hasK = targetText.includes('K');
                            const hasM = targetText.includes('M');
                            const hasT = targetText.includes('T');
                            
                            // Simple parsing just for animation effect
                            let targetNum = parseFloat(targetText.replace(/[KMT,]/g, ''));
                            
                            let start = 0;
                            const duration = 2000;
                            const increment = targetNum / (duration / 16); // 60fps
                            
                            const updateCounter = () => {
                                start += increment;
                                if (start < targetNum) {
                                    counter.innerText = (Math.round(start * 10) / 10).toFixed(targetText.includes('.') ? 1 : 0) + (hasK ? 'K' : (hasM ? 'M' : (hasT ? 'T' : '')));
                                    requestAnimationFrame(updateCounter);
                                } else {
                                    counter.innerText = targetText; // Ensure final exact value
                                }
                            };
                            updateCounter();
                        });
                    }
                });
            }, { threshold: 0.5 });

            const statSection = document.getElementById('statistik');
            if (statSection) {
                counterObserver.observe(statSection);
            }
        });
    </script>
</body>
</html>
