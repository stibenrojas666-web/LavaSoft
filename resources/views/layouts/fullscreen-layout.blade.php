<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'LavaSoft' }} | LavaSoft</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            min-height: 100%;
            background: #f8fafc;
        }

        body {
            min-height: 100%;
            margin: 0;
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>

    <script>
        (function () {
            try {
                const temaGuardado = localStorage.getItem('theme');
                const temaSistema = window.matchMedia('(prefers-color-scheme: dark)').matches
                    ? 'dark'
                    : 'light';

                if ((temaGuardado || temaSistema) === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            } catch (error) {
                // La aplicación continúa usando el tema claro si el almacenamiento no está disponible.
            }
        })();

        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',

                toggle() {
                    this.theme = this.theme === 'dark' ? 'light' : 'dark';
                    this.update();
                },

                update() {
                    document.documentElement.classList.toggle('dark', this.theme === 'dark');

                    try {
                        localStorage.setItem('theme', this.theme);
                    } catch (error) {
                        // No interrumpir la interfaz si el almacenamiento está bloqueado.
                    }
                }
            });

            Alpine.store('sidebar', {
                isExpanded: window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },

                setMobileOpen(value) {
                    this.isMobileOpen = Boolean(value);
                },

                setHovered(value) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = Boolean(value);
                    }
                }
            });
        });
    </script>
</head>

<body class="min-h-full bg-slate-50 text-slate-800 antialiased">
    @yield('content')

    @stack('scripts')
</body>
</html>