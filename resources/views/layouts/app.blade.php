<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LavaSoft') - LavaSoft</title>

    {{-- Tailwind CSS. Reemplazar por tu build de Vite/PostCSS en producción (ver README). --}}
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

    {{-- Chart.js (solo se usa donde se necesita) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }

        .sidebar-link {
            display: flex;
            min-height: 42px;
            align-items: center;
            gap: .75rem;
            border-radius: .75rem;
            padding: .625rem .75rem;
            color: #cbd5e1;
            font-size: .875rem;
            font-weight: 500;
            transition: background-color .16s ease, color .16s ease, transform .16s ease;
        }
        .sidebar-link:hover { background: #1e293b; color: #fff; transform: translateX(2px); }
        .sidebar-link-active { background: #4f46e5; color: #fff; box-shadow: 0 5px 14px rgb(49 46 129 / 25%); }
        .sidebar-link-active:hover { background: #4338ca; }
        .sidebar-icon { display: block; width: 1.125rem; height: 1.125rem; flex: 0 0 auto; }

        main form:not(.inline) input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]),
        main form:not(.inline) select,
        main form:not(.inline) textarea {
            min-height: 2.75rem;
            border-radius: .75rem;
            border-color: #dbe3ee;
            background-color: #fff;
            transition: border-color .16s ease, box-shadow .16s ease;
        }
        main form:not(.inline) input:focus,
        main form:not(.inline) select:focus,
        main form:not(.inline) textarea:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgb(6 182 212 / 14%);
            outline: none;
        }

        main table { width: 100%; border-collapse: separate; border-spacing: 0; }
        main table thead tr { background: #cffafe; color: #155e75; }
        main table thead th {
            padding: .85rem 1rem;
            border-bottom: 1px solid #a5f3fc;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-align: left;
            text-transform: uppercase;
            white-space: nowrap;
        }
        main table tbody td { padding: .9rem 1rem; border-bottom: 1px solid #e8edf3; vertical-align: middle; }
        main table tbody tr:last-child td { border-bottom: 0; }
        main table tbody tr:hover { background-color: #f0fdff; }
        main table tbody tr:nth-child(even) { background-color: #fbfdff; }
        main table tbody tr:nth-child(even):hover { background-color: #f0fdff; }
        main td a[aria-label="Editar"], main td button[aria-label="Eliminar"] {
            display: inline-grid;
            width: 2.15rem;
            height: 2.15rem;
            place-items: center;
            border-radius: .7rem;
            transition: background-color .16s ease, color .16s ease, transform .16s ease;
        }
        main td a[aria-label="Editar"] { background: #ecfeff; color: #0e7490; }
        main td a[aria-label="Editar"]:hover { background: #cffafe; color: #155e75; transform: translateY(-1px); }
        main td button[aria-label="Eliminar"] { background: #fff1f2; color: #be123c; }
        main td button[aria-label="Eliminar"]:hover { background: #ffe4e6; color: #9f1239; transform: translateY(-1px); }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased">

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

        {{-- Overlay móvil --}}
        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"
            aria-hidden="true"
        ></div>

        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
            @include('layouts.partials.navbar')

            <main class="mx-auto w-full max-w-[1600px] flex-1 p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>
                @endif
                @if (session('error'))
                    <x-alert type="error" class="mb-6">{{ session('error') }}</x-alert>
                @endif

                @yield('content')
            </main>

            @include('layouts.partials.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>

    @stack('scripts')
</body>
</html>
