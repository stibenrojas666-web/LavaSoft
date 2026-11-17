<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Seguridad') - {{ config('app.name', 'LavaSoft') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="min-h-full bg-slate-50 font-sans text-slate-800 antialiased">
<div x-data="{ sidebarOpen: false, menuOpen: false }" class="min-h-screen">

    <div
        x-show="sidebarOpen"
        x-cloak
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-950/50 lg:hidden"
    ></div>

    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-[#0b2a3a] text-slate-300 transition-transform duration-300 lg:translate-x-0"
    >
        <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
            <img src="{{ asset('images/logo.png') }}" alt="LavaSoft" class="h-11 w-11 rounded-xl bg-white object-contain p-1">
            <div>
                <span class="block text-lg font-extrabold text-white">LavaSoft</span>
                <span class="text-[10px] font-bold uppercase tracking-[.18em] text-cyan-300">Centro de lavado</span>
            </div>
            <button @click="sidebarOpen = false" class="ml-auto text-slate-400 lg:hidden" aria-label="Cerrar menú">✕</button>
        </div>

        <nav class="flex-1 space-y-2 overflow-y-auto px-4 py-6">
            <p class="mb-3 px-3 text-[10px] font-bold uppercase tracking-[.2em] text-slate-500">Administración de seguridad</p>

            @foreach ([
                ['seguridad.roles.index', 'Roles', 'seguridad.roles.*', '♙'],
                ['seguridad.permisos.index', 'Permisos', 'seguridad.permisos.*', '◈'],
                ['seguridad.cuentas.index', 'Cuentas de usuario', 'seguridad.cuentas.*', '♧'],
                ['seguridad.usuarios.index', 'Usuarios y roles', 'seguridad.usuarios.*', '♧'],
            ] as [$ruta, $texto, $patron, $icono])
                <a
                    href="{{ route($ruta) }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition
                    {{ request()->routeIs($patron) ? 'bg-cyan-700 text-white shadow-lg shadow-cyan-950/20' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}"
                >
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-white/10 text-lg">{{ $icono }}</span>
                    {{ $texto }}
                </a>
            @endforeach

        </nav>

        <div class="border-t border-white/10 bg-slate-950/30 p-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold text-rose-300 transition hover:bg-rose-500/10">
                    <span class="text-lg">↪</span>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    <div class="min-h-screen lg:pl-72">
        <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white/95 px-4 shadow-sm backdrop-blur sm:px-6">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true" class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-600 lg:hidden" aria-label="Abrir menú">
                    ☰
                </button>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[.15em] text-cyan-700">Panel administrativo</p>
                    <h1 class="text-sm font-extrabold text-slate-900 sm:text-base">@yield('title', 'Seguridad')</h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden text-right sm:block">
                    <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500">Administrador</p>
                </div>
                <span class="grid h-10 w-10 place-items-center rounded-full bg-cyan-100 text-sm font-extrabold text-cyan-800">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </span>
            </div>
        </header>

        <main class="mx-auto w-full max-w-[1600px] p-4 sm:p-6 lg:p-8">
            @include('layouts.partials.form-breadcrumbs')
            @if(session('success'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@stack('scripts')
</body>
</html>
