<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - {{ config('app.name', 'LavaSoft') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-50 p-5 font-sans text-slate-800">
<main class="w-full max-w-md">
    <div class="mb-6 text-center">
        <img src="{{ asset('images/logo.png') }}" alt="LavaSoft" class="mx-auto h-16 w-16 rounded-2xl bg-white object-contain p-2 shadow-sm">
        <p class="mt-3 text-lg font-extrabold text-[#0b2a3a]">LavaSoft</p>
    </div>

    <div class="rounded-3xl border border-slate-200 bg-white p-7 shadow-xl shadow-slate-200/60 sm:p-9">
        <div class="mb-7 text-center">
            <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-cyan-50 text-2xl text-cyan-800">🔐</div>
            <h1 class="text-2xl font-extrabold text-slate-900">Recuperar contraseña</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Ingresa el correo asociado a tu cuenta. Te enviaremos un código de verificación de 6 dígitos.
            </p>
        </div>

        @if(session('estado'))
            <div class="mb-5 rounded-xl border border-cyan-200 bg-cyan-50 p-3 text-sm text-cyan-800">{{ session('estado') }}</div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('seguridad.recuperacion.enviar') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Correo electrónico registrado</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="ejemplo@correo.com"
                    class="min-h-12 w-full rounded-xl border border-slate-300 px-4 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100"
                >
            </div>

            <button type="submit" class="min-h-12 w-full rounded-xl bg-cyan-800 px-5 text-sm font-bold text-white transition hover:bg-cyan-900 focus:outline-none focus:ring-4 focus:ring-cyan-200">
                Enviar código de verificación
            </button>
        </form>

        <nav class="mt-6 flex items-center justify-center gap-2 border-t border-slate-100 pt-5 text-sm font-medium text-slate-400" aria-label="Ruta de navegación">
            <a href="{{ route('login') }}" class="transition hover:text-cyan-700">Iniciar sesión</a>
            <span aria-hidden="true">/</span>
            <span class="font-semibold text-cyan-800" aria-current="page">Recuperar contraseña</span>
        </nav>
    </div>
</main>
</body>
</html>
