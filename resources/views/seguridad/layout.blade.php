<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seguridad') - {{ config('app.name', 'Laravel') }}</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc',
                            400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                            800: '#3730a3', 900: '#312e81'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>

    @stack('styles')
</head>
<body class="h-full text-slate-800 antialiased">

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

        {{-- Overlay móvil --}}
        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"
            aria-hidden="true"
        ></div>

        {{-- Sidebar Estilo Panel de Control --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-300 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0">
            <div class="h-16 flex items-center px-6 bg-slate-950 gap-3">
                <span class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">A</span>
                <span class="text-white font-bold tracking-wide">Laravel</span>
            </div>

            <div class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-3 mb-2">Módulo Seguridad</div>

                @foreach ([
                    ['seguridad.roles.index', 'Roles', 'seguridad.roles.*', '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />'],
                    ['seguridad.permisos.index', 'Permisos', 'seguridad.permisos.*', '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />'],
                    ['seguridad.cuentas.index', 'Cuentas de usuario', 'seguridad.cuentas.*', '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />'],
                    ['seguridad.usuarios.index', 'Usuarios y roles', 'seguridad.usuarios.*', '<path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.715m12 0v-.503a3.333 3.333 0 00-3-3.333h-6c-1.657 0-3 1.492-3 3.333v.503m6-9.5a3 3 0 11-6 0 3 3 0 016 0z" />'],
                ] as [$ruta, $texto, $patron, $icono])
                    <a href="{{ route($ruta) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs($patron) ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">{!! $icono !!}</svg>
                        {{ $texto }}
                    </a>
                @endforeach

                <div class="pt-4 mt-4 border-t border-slate-800">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition-colors">
                        <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                        Volver a la app
                    </a>
                </div>
            </div>

            <div class="p-4 bg-slate-950 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- Contenido principal --}}
        <div class="flex-1 flex flex-col min-w-0 lg:pl-64">

            {{-- Navbar superior --}}
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="text-slate-500 hover:text-slate-700 lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="text-base font-semibold text-slate-800">@yield('title', 'Seguridad')</h1>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Buscador simulado --}}
                    <div class="hidden sm:flex items-center bg-slate-100 rounded-lg px-3 py-1.5 border border-slate-200 text-sm">
                        <svg class="w-4 h-4 text-slate-400 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        <input type="text" placeholder="Buscar..." class="bg-transparent border-0 focus:outline-none text-slate-700 placeholder-slate-400 text-xs w-36 lg:w-48">
                    </div>

                    {{-- Notificaciones --}}
                    <button class="text-slate-400 hover:text-slate-600 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    {{-- Dropdown de usuario estilo imagen --}}
                    <div x-data="{ openUserMenu: false }" class="relative">
                        <button @click="openUserMenu = !openUserMenu" class="flex items-center gap-2 focus:outline-none">
                            <span class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 font-semibold text-xs flex items-center justify-center">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                            </span>
                            <span class="text-sm font-medium text-slate-700 hidden sm:inline">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="openUserMenu" x-cloak @click.away="openUserMenu = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-100 py-1 text-sm z-50">
                            <a href="#" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">Mi perfil</a>
                            <a href="#" class="block px-4 py-2 text-slate-700 hover:bg-slate-50">Configuración</a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50">Cerrar sesión</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Contenido con alertas y yield --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-4">
                @if (session('success'))
                    <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm shadow-sm">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm shadow-sm">{{ session('error') }}</div>
                @endif
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="bg-white border-t border-slate-200 py-4 px-6 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Todos los derechos reservados.</div>
                <div>Versión 1.0.0</div>
            </footer>
        </div>
    </div>

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>
</html>
