<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: {
                primary: { 50: '#ecfeff', 100: '#cffafe', 200: '#a5f3fc', 300: '#67e8f9', 400: '#22d3ee', 500: '#06b6d4', 600: '#0e7490', 700: '#155e75', 800: '#164e63', 900: '#083344' },
                navy: { 800: '#123446', 900: '#0b2a3a', 950: '#071c28' }
            },
            fontFamily: { sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'] }
        } } }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="h-full font-sans text-slate-800 antialiased">
    <main class="grid min-h-screen lg:grid-cols-2">
        {{-- Panel de marca --}}
        <section class="relative hidden overflow-hidden bg-navy-900 p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <svg class="pointer-events-none absolute -right-10 top-24 h-96 w-96 text-cyan-300/20" viewBox="0 0 260 260" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="90" cy="150" r="60"/><circle cx="190" cy="80" r="38"/><circle cx="215" cy="185" r="26"/><circle cx="140" cy="215" r="14"/><circle cx="235" cy="40" r="10"/>
            </svg>
            <p class="relative text-2xl font-extrabold tracking-tight">{{ config('app.name') }}</p>
            <div class="relative max-w-md">
                <h2 class="text-4xl font-extrabold leading-tight">Tu lavadero, ordenado y siempre brillante.</h2>
                <p class="mt-4 text-base text-slate-300">Agenda, clientes, servicios y pagos en un solo lugar.</p>
            </div>
            <p class="relative text-sm text-slate-400">Centro de lavado</p>
        </section>

        {{-- Formulario --}}
        <section class="flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md">
                <p class="mb-8 text-center text-2xl font-extrabold tracking-tight text-navy-900 lg:hidden">{{ config('app.name') }}</p>

                <div class="rounded-3xl border border-slate-200/70 bg-white p-7 shadow-[0_16px_40px_rgba(11,42,58,0.08)] sm:p-9">
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">Iniciar sesión</h1>
                    <p class="mb-7 mt-1 text-sm text-slate-500">Ingresa tus credenciales para continuar</p>

                    <form action="{{ route('login.store') }}" method="POST" novalidate class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Correo electrónico</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" autofocus autocomplete="email"
                                   class="min-h-[48px] w-full rounded-xl border border-slate-300 bg-white px-4 text-sm focus:border-cyan-600 focus:outline-none focus:ring-4 focus:ring-cyan-100 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Contraseña</label>
                            <input type="password" name="password" id="password" autocomplete="current-password"
                                   class="min-h-[48px] w-full rounded-xl border border-slate-300 bg-white px-4 text-sm focus:border-cyan-600 focus:outline-none focus:ring-4 focus:ring-cyan-100">
                            @error('password')
                                <p class="mt-1.5 text-xs text-red-700">{{ $message }}</p>
                            @enderror
                        </div>

                        <label class="flex min-h-[44px] items-center gap-3 text-sm text-slate-600">
                            <input type="checkbox" name="remember" value="1" class="h-5 w-5 rounded border-slate-300 text-cyan-700 focus:ring-cyan-500">
                            Recordarme
                        </label>

                        <button type="submit" class="min-h-[48px] w-full rounded-xl bg-primary-600 text-sm font-bold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-4 focus:ring-cyan-200">
                            Ingresar
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>