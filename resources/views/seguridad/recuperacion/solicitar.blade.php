
<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-full items-center justify-center p-6 font-sans text-slate-800">
    <main class="w-full max-w-md">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl sm:p-10">
            <div class="mb-6 text-center">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-100 text-2xl">
                    <span>🔐</span>
                </div>

                <h1 class="text-2xl font-extrabold text-slate-800">
                    Recuperar contraseña
                </h1>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Ingresa el correo electrónico asociado a tu cuenta. Te enviaremos un código de verificación de 6 dígitos.
                </p>
            </div>

            @if (session('estado'))
                <div class="mb-5 rounded-xl border border-cyan-200 bg-cyan-50 p-3 text-sm text-cyan-800">
                    {{ session('estado') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('seguridad.recuperacion.enviar') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">
                        Correo electrónico registrado
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="ejemplo@correo.com"
                        class="min-h-[48px] w-full rounded-xl border border-slate-300 bg-white px-4 text-sm outline-none transition focus:border-cyan-600 focus:ring-4 focus:ring-cyan-100"
                    >
                </div>

                <button
                    type="submit"
                    class="min-h-[48px] w-full rounded-xl bg-cyan-700 text-sm font-bold text-white shadow-sm transition hover:bg-cyan-800 focus:outline-none focus:ring-4 focus:ring-cyan-200"
                >
                    Enviar código de verificación
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-cyan-700 hover:text-cyan-900">
                    ← Volver al inicio de sesión
                </a>
            </div>
        </div>
    </main>
</body>
</html>
