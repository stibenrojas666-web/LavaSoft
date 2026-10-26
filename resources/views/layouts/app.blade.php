<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LavaSoft') - LavaSoft</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ecfeff', 100: '#cffafe', 200: '#a5f3fc', 300: '#67e8f9',
                            400: '#22d3ee', 500: '#06b6d4', 600: '#0e7490', 700: '#155e75',
                            800: '#164e63', 900: '#083344'
                        },
                        navy: { 800: '#123446', 900: '#0b2a3a', 950: '#071c28' }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js" defer></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }

        /* Menú lateral */
        .sidebar-link {
            display: flex;
            min-height: 44px;
            align-items: center;
            gap: .75rem;
            border-radius: .9rem;
            padding: .6rem .8rem;
            color: #a9c1ce;
            font-size: .875rem;
            font-weight: 500;
            transition: background-color .16s ease, color .16s ease;
        }
        .sidebar-link:hover { background: rgb(255 255 255 / 7%); color: #fff; }
        .sidebar-link-active { background: rgb(34 211 238 / 15%); color: #67e8f9; font-weight: 700; }
        .sidebar-link-active:hover { background: rgb(34 211 238 / 20%); color: #a5f3fc; }
        .sidebar-icon { display: block; width: 1.125rem; height: 1.125rem; flex: 0 0 auto; }

        /* Formularios (todos los del sistema) */
        main form:not(.inline) input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]),
        main form:not(.inline) select,
        main form:not(.inline) textarea {
            min-height: 2.75rem;
            border-radius: .85rem;
            border: 1px solid #d3e2ea;
            background-color: #fff;
            transition: border-color .16s ease, box-shadow .16s ease;
        }
        main form:not(.inline) input:focus,
        main form:not(.inline) select:focus,
        main form:not(.inline) textarea:focus {
            border-color: #0891b2;
            box-shadow: 0 0 0 4px rgb(8 145 178 / 14%);
            outline: none;
        }
        main form:not(.inline) label { color: #334e5e; font-weight: 600; }

        /* Tablas (todas las del sistema) */
        main table { width: 100%; border-collapse: separate; border-spacing: 0; }
        main table thead tr { background: #f1f7fa; color: #4a6372; }
        main table thead th {
            padding: .9rem 1rem;
            border-bottom: 1px solid #e1ecf1;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-align: left;
            text-transform: uppercase;
            white-space: nowrap;
        }
        main table tbody td { padding: .95rem 1rem; border-bottom: 1px solid #edf3f6; vertical-align: middle; }
        main table tbody tr:last-child td { border-bottom: 0; }
        main table tbody tr { transition: background-color .14s ease; }
        main table tbody tr:hover { background-color: #f5fbfc; }
        main td a[aria-label="Editar"], main td button[aria-label="Eliminar"] {
            display: inline-grid;
            width: 2.25rem;
            height: 2.25rem;
            place-items: center;
            border-radius: .8rem;
            transition: background-color .16s ease, color .16s ease, transform .16s ease;
        }
        main td a[aria-label="Editar"] { background: #ecfeff; color: #0e7490; }
        main td a[aria-label="Editar"]:hover { background: #cffafe; color: #155e75; transform: translateY(-1px); }
        main td button[aria-label="Eliminar"] { background: #fff1f2; color: #be123c; }
        main td button[aria-label="Eliminar"]:hover { background: #ffe4e6; color: #9f1239; transform: translateY(-1px); }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-slate-100 text-slate-800 antialiased">

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-navy-950/60 backdrop-blur-sm lg:hidden"
            aria-hidden="true"
        ></div>

        @include('layouts.partials.sidebar')

        <div class="flex-1 flex flex-col min-w-0 lg:pl-72">
            @include('layouts.partials.navbar')

            <main class="mx-auto w-full max-w-[1600px] flex-1 p-4 sm:p-6 lg:p-8">
               

                @yield('content')
            </main>

            @include('layouts.partials.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js" defer></script>

    <script>
        window.addEventListener('pageshow', function (event) {
            const navigation = performance.getEntriesByType('navigation')[0];
            const regresoConAtrasAdelante = navigation && navigation.type === 'back_forward';

            if (event.persisted || regresoConAtrasAdelante) {
                window.location.reload();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>