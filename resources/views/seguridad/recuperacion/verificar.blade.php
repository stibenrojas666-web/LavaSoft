<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar código - {{ config('app.name', 'LavaSoft') }}</title>
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
            <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-cyan-50 text-2xl">✉️</div>
            <h1 class="text-2xl font-extrabold text-slate-900">Verifica tu correo</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Ingresa el código de 6 dígitos que enviamos a tu correo electrónico registrado.
            </p>
        </div>

        @if(session('estado'))
            <div class="mb-5 rounded-xl border border-cyan-200 bg-cyan-50 p-3 text-sm text-cyan-800">{{ session('estado') }}</div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('seguridad.recuperacion.verificar.codigo') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="codigo" class="mb-2 block text-sm font-semibold text-slate-700">Código de verificación</label>
                <input
                    type="text"
                    name="codigo"
                    id="codigo"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    minlength="6"
                    required
                    autofocus
                    autocomplete="one-time-code"
                    placeholder="000000"
                    class="min-h-14 w-full rounded-xl border border-slate-300 px-4 text-center text-2xl font-extrabold tracking-[.5em] outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100"
                >
            </div>

            <button type="submit" class="min-h-12 w-full rounded-xl bg-cyan-800 px-5 text-sm font-bold text-white transition hover:bg-cyan-900 focus:outline-none focus:ring-4 focus:ring-cyan-200">
                Verificar código
            </button>
        </form>

        <div class="mt-6 border-t border-slate-100 pt-5 text-center">
            <p class="mb-3 text-sm text-slate-500">¿No recibiste el código?</p>
            <form action="{{ route('seguridad.recuperacion.reenviar') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-bold text-cyan-800 hover:text-cyan-950">Reenviar código</button>
            </form>
            <a href="{{ route('seguridad.recuperacion.solicitar') }}" class="mt-4 block text-sm font-semibold text-slate-500 hover:text-cyan-800">Usar otro correo</a>
        </div>
    </div>
</main>
</body>
</html>