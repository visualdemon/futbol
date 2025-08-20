<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1e3a8a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title>{{ $title ?? 'Fútbol Jueves - Registro de jugadores' }}</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Registro de jugadores para fútbol de los jueves. Inscríbete fácilmente y consulta los equipos formados.">
    <meta name="keywords" content="fútbol, jueves, registro, equipos, deportes">
    <meta name="author" content="Sistema de Registro Fútbol Jueves">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? 'Fútbol Jueves' }}">
    <meta property="og:description" content="Registro de jugadores para fútbol de los jueves">
    <meta property="og:image" content="{{ asset('images/football-og.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="{{ $title ?? 'Fútbol Jueves' }}">
    <meta property="twitter:description" content="Registro de jugadores para fútbol de los jueves">
    <meta property="twitter:image" content="{{ asset('images/football-twitter.png') }}">

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('fonts/instrument-sans-variable.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- DNS Prefetch for external resources -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">

    <!-- Stylesheets -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Critical CSS inlined for faster loading -->
    <style>
        /* Critical above-the-fold styles */
        body {
            margin: 0;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            min-height: 100vh;
            color: white;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Prevent flash of unstyled content */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased">
    <!-- Skip to content link for accessibility -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-blue-600 text-white px-4 py-2 rounded-lg z-50">
        Saltar al contenido principal
    </a>

    <!-- Loading indicator -->
    <div id="loading-overlay" class="fixed inset-0 bg-gradient-to-br from-blue-900 to-indigo-900 flex items-center justify-center z-50 transition-opacity duration-500">
        <div class="text-center">
            <div class="text-6xl mb-4 animate-bounce">⚽</div>
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-white text-lg font-medium">Cargando Fútbol Jueves...</p>
        </div>
    </div>

    <!-- Main content -->
    <main id="main-content" role="main" class="relative">
        {{ $slot }}
    </main>

    <!-- Error boundary -->
    <div id="error-boundary" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 text-center">
            <div class="text-4xl mb-4">⚠️</div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Oops! Algo salió mal</h2>
            <p class="text-gray-600 mb-4">Ha ocurrido un error inesperado. Por favor, recarga la página.</p>
            <button onclick="location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                Recargar página
            </button>
        </div>
    </div>

    <!-- Toast notifications container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2" role="alert" aria-live="polite"></div>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- CSRF Token Configuration for Livewire -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Configure CSRF token for all Livewire requests
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.livewire_token = token.getAttribute('content');
            }
        });
    </script>

    <!-- App initialization script -->
    <script>
        // Hide loading overlay when page is ready
        window.addEventListener('load', function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.opacity = '0';
                setTimeout(() => {
                    loadingOverlay.remove();
                }, 500);
            }
        });

        // Error boundary handler
        window.addEventListener('error', function(e) {
            console.error('Global error caught:', e.error);
            const errorBoundary = document.getElementById('error-boundary');
            if (errorBoundary) {
                errorBoundary.classList.remove('hidden');
            }
        });

        // Service Worker registration disabled temporarily
        // if ('serviceWorker' in navigator) {
        //     window.addEventListener('load', function() {
        //         navigator.serviceWorker.register('/sw.js')
        //             .then(function(registration) {
        //                 console.log('SW registered: ', registration);
        //             })
        //             .catch(function(registrationError) {
        //                 console.log('SW registration failed: ', registrationError);
        //             });
        //     });
        // }

        // PWA install prompt
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Show custom install button if needed
            const installButton = document.getElementById('install-button');
            if (installButton) {
                installButton.style.display = 'block';
                installButton.addEventListener('click', () => {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the A2HS prompt');
                        }
                        deferredPrompt = null;
                        installButton.style.display = 'none';
                    });
                });
            }
        });

        // Toast notification system
        function showToast(message, type = 'info', duration = 5000) {
            const toastContainer = document.getElementById('toast-container');
            if (!toastContainer) return;

            const toast = document.createElement('div');
            toast.className = `flex items-center gap-3 p-4 rounded-xl shadow-lg transition-all duration-300 transform translate-x-full max-w-sm ${
                type === 'success' ? 'bg-green-50 text-green-800 border-l-4 border-green-500' :
                type === 'error' ? 'bg-red-50 text-red-800 border-l-4 border-red-500' :
                type === 'warning' ? 'bg-yellow-50 text-yellow-800 border-l-4 border-yellow-500' :
                'bg-blue-50 text-blue-800 border-l-4 border-blue-500'
            }`;

            const icon = type === 'success' ? '✅' : type === 'error' ? '❌' : type === 'warning' ? '⚠️' : 'ℹ️';

            toast.innerHTML = `
                <span class="text-xl flex-shrink-0">${icon}</span>
                <span class="font-medium flex-1">${message}</span>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <span class="sr-only">Cerrar</span>
                    ×
                </button>
            `;

            toastContainer.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.style.transform = 'translateX(0)';
            }, 100);

            // Auto remove
            setTimeout(() => {
                toast.style.transform = 'translateX(100%)';
                toast.style.opacity = '0';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, duration);
        }

        // Expose toast function globally
        window.showToast = showToast;

        // Livewire hooks
        document.addEventListener('livewire:initialized', () => {
            console.log('Livewire initialized');
        });

        document.addEventListener('livewire:navigating', () => {
            console.log('Livewire navigating');
            // Show loading state if needed
        });

        // Performance monitoring
        if ('performance' in window) {
            window.addEventListener('load', function() {
                setTimeout(() => {
                    const perfData = performance.getEntriesByType('navigation')[0];
                    const loadTime = perfData.loadEventEnd - perfData.fetchStart;
                    console.log('Page load time:', Math.round(loadTime), 'ms');

                    // Send to analytics if needed
                    if (loadTime > 3000) {
                        console.warn('Slow page load detected');
                    }
                }, 0);
            });
        }

        // Accessibility enhancements
        document.addEventListener('keydown', function(e) {
            // ESC key handling
            if (e.key === 'Escape') {
                // Close modals, dropdowns, etc.
                const activeModal = document.querySelector('.modal.active');
                if (activeModal) {
                    activeModal.classList.remove('active');
                }
            }
        });

        // Touch device optimizations
        if ('ontouchstart' in window) {
            document.body.classList.add('touch-device');
        }

        // Network status handling
        window.addEventListener('online', function() {
            showToast('Conexión restablecida', 'success', 3000);
        });

        window.addEventListener('offline', function() {
            showToast('Sin conexión a internet', 'warning', 5000);
        });
    </script>

    <!-- Analytics or tracking scripts can go here -->
    @stack('scripts')
</body>
</html>
