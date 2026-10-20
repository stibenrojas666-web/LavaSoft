<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full text-slate-800 antialiased">
    <main class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <h1 class="text-center text-2xl font-semibold text-slate-900 mb-6">{{ config('app.name') }}</h1>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h2 class="text-lg font-semibold">Iniciar sesión</h2>
                <p class="text-sm text-slate-500 mb-5">Ingresa tus credenciales para continuar</p>

                <form action="{{ route('login.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                               class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                        <input type="password" name="password" id="password" required autocomplete="current-password"
                               class="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 border px-3 py-2">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 h-4 w-4">
                        Recordarme
                    </label>

                    <button type="submit" class="w-full rounded-md bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2">
                        Ingresar
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
