<!DOCTYPE html>
<html lang="es" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - {{ config('app.name', 'LavaSoft') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white font-sans text-slate-800 antialiased">
<main class="flex min-h-screen items-center justify-center bg-white px-5 py-10 sm:px-8">
    <section class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-7 shadow-lg shadow-slate-200/40 sm:p-10">
        <header class="mb-8 text-center">
            @if($configuracion?->mostrar_logo && $configuracion?->logo_path)
                <img
                    src="{{ route('lavadero.logo', ['v' => $configuracion->updated_at?->timestamp ?? 0]) }}"
                    alt="Logo de LavaSoft"
                    class="mx-auto h-20 w-20 rounded-2xl border border-slate-100 bg-white p-2 object-contain"
                >
            @else
                <span class="mx-auto grid h-20 w-20 place-items-center rounded-2xl bg-cyan-50 text-cyan-800">
                    <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c3.5 4.2 6 7.2 6 10.2A6 6 0 0 1 6 13.2C6 10.2 8.5 7.2 12 3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 14a3 3 0 0 0 3 3"/>
                    </svg>
                </span>
            @endif

            <p class="mt-4 text-xl font-extrabold tracking-tight text-[#0b2a3a]">LavaSoft</p>
        </header>

        <div class="mb-7 text-center">
            <p class="text-xs font-bold uppercase tracking-[.18em] text-cyan-700">Bienvenido a LavaSoft</p>
            <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">Iniciar sesión</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">Ingresa para gestionar clientes, empleados, vehículos, agenda, servicios, pagos y reportes.</p>
        </div>

        @if(session('estado'))
            <div class="mb-5 rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-3 text-sm text-cyan-800">
                {{ session('estado') }}
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" novalidate class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Correo electrónico</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    autofocus
                    autocomplete="email"
                    class="min-h-12 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm outline-none transition focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100 @error('email') border-rose-500 @enderror"
                >
                @error('email')
                    <p class="mt-2 text-xs text-rose-700">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Contraseña</label>
                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        autocomplete="current-password"
                        class="min-h-12 w-full rounded-xl border border-slate-300 bg-white px-4 pr-12 text-sm outline-none transition focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100 @error('password') border-rose-500 @enderror"
                    >
                    <button type="button" data-password-toggle="password" aria-label="Mostrar contraseña" title="Mostrar contraseña" aria-pressed="false" class="absolute inset-y-0 right-2 grid w-9 place-items-center rounded-lg text-slate-500 transition hover:bg-cyan-50 hover:text-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        <span data-password-eye class="block h-5 w-5">@include('layouts.partials.icons', ['icon' => 'eye'])</span>
                        <span data-password-eye-off class="block h-5 w-5" style="display: none">@include('layouts.partials.icons', ['icon' => 'eye-off'])</span>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-xs text-rose-700">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-3 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600">
                Recordarme
            </label>

            <div class="text-right">
                <a href="{{ route('seguridad.recuperacion.solicitar') }}" class="text-sm font-semibold text-cyan-800 hover:text-cyan-950">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <button type="submit" class="min-h-12 w-full rounded-xl bg-cyan-800 px-5 text-sm font-bold text-white shadow-sm transition hover:bg-cyan-900 focus:outline-none focus:ring-4 focus:ring-cyan-200">
                Ingresar al sistema
            </button>
        </form>
    </section>
</main>
</body>
</html>
