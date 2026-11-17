<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña - {{ config('app.name', 'LavaSoft') }}</title>
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
            <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-cyan-50 text-2xl">🔑</div>
            <h1 class="text-2xl font-extrabold text-slate-900">Nueva contraseña</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Tu correo ha sido verificado. Escribe y confirma tu nueva contraseña para recuperar el acceso a LavaSoft.
            </p>
        </div>

        @if($errors->any())
            <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('seguridad.recuperacion.actualizar') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Nueva contraseña</label>
                <div class="relative">
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Mínimo 8 caracteres"
                    class="min-h-12 w-full rounded-xl border border-slate-300 px-4 pr-12 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100"
                >
                    <button type="button" data-password-toggle="password" aria-label="Mostrar contraseña" title="Mostrar contraseña" aria-pressed="false" class="absolute inset-y-0 right-2 grid w-9 place-items-center rounded-lg text-slate-500 transition hover:bg-cyan-50 hover:text-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        <span data-password-eye class="block h-5 w-5">@include('layouts.partials.icons', ['icon' => 'eye'])</span>
                        <span data-password-eye-off class="block h-5 w-5" style="display: none">@include('layouts.partials.icons', ['icon' => 'eye-off'])</span>
                    </button>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Confirmar nueva contraseña</label>
                <div class="relative">
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    required
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Repite tu nueva contraseña"
                    class="min-h-12 w-full rounded-xl border border-slate-300 px-4 pr-12 text-sm outline-none focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100"
                >
                    <button type="button" data-password-toggle="password_confirmation" aria-label="Mostrar contraseña" title="Mostrar contraseña" aria-pressed="false" class="absolute inset-y-0 right-2 grid w-9 place-items-center rounded-lg text-slate-500 transition hover:bg-cyan-50 hover:text-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500">
                        <span data-password-eye class="block h-5 w-5">@include('layouts.partials.icons', ['icon' => 'eye'])</span>
                        <span data-password-eye-off class="block h-5 w-5" style="display: none">@include('layouts.partials.icons', ['icon' => 'eye-off'])</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="min-h-12 w-full rounded-xl bg-cyan-800 px-5 text-sm font-bold text-white transition hover:bg-cyan-900 focus:outline-none focus:ring-4 focus:ring-cyan-200">
                Guardar nueva contraseña
            </button>
        </form>
    </div>
</main>
</body>
</html>
