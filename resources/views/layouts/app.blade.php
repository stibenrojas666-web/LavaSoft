<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LavaSoft') - LavaSoft</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ecfeff', 100: '#cffafe', 200: '#a5f3fc', 300: '#67e8f9',
                            400: '#22d3ee', 500: '#06b6d4', 600: '#0e7490', 700: '#155e75',
                            800: '#164e63', 900: '#083344'},
                        navy: { 800: '#123446', 900: '#0b2a3a', 950: '#071c28' }},
                    fontFamily: {sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif']}
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }

        :root {
            color-scheme: light;
            --lava-ink: #123446;
            --lava-muted: #647b89;
            --lava-border: #dbe7ed;
            --lava-cyan: #0e7490;
        }

        main { color: #243b4a; }
        main :is(h1, h2, h3) { text-wrap: balance; }
        main p { text-wrap: pretty; }

        /* Campos compartidos por formularios de todos los módulos */
        main form:not(.inline) input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]),
        main form:not(.inline) select,
        main form:not(.inline) textarea {
            min-height: 2.75rem;
            border-radius: .8rem;
            border: 1px solid var(--lava-border);
            background-color: #fff;
            color: #183445;
            transition: border-color .16s ease, box-shadow .16s ease, background-color .16s ease;
        }
        main form:not(.inline) input::placeholder,
        main form:not(.inline) textarea::placeholder { color: #91a4af; }
        main form:not(.inline) input:focus,
        main form:not(.inline) select:focus,
        main form:not(.inline) textarea:focus {
            border-color: #0891b2;
            box-shadow: 0 0 0 4px rgb(8 145 178 / 13%);
            outline: none;
        }
        main form:not(.inline) :is(input, select, textarea).border-red-500,
        main form:not(.inline) :is(input, select, textarea)[aria-invalid="true"] {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgb(239 68 68 / 10%) !important;
        }
        main form:not(.inline) :is(input, select, textarea):disabled {
            background-color: #f1f5f8;
            color: #8295a0;
            cursor: not-allowed;
        }
        main form:not(.inline) input[type="checkbox"],
        main form:not(.inline) input[type="radio"] { accent-color: var(--lava-cyan); }
        main form:not(.inline) label { color: #334e5e; font-weight: 600; }
        main button[type="submit"], main input[type="submit"] {
            min-height: 2.75rem;
            border-radius: .8rem;
            font-weight: 700;
            transition: filter .16s ease, transform .16s ease, box-shadow .16s ease;
        }
        main button[type="submit"]:hover, main input[type="submit"]:hover { filter: brightness(.96); }
        main button[type="submit"]:active, main input[type="submit"]:active { transform: translateY(1px); }
        main :is(button, a, input, select, textarea):focus-visible {
            outline: 3px solid rgb(8 145 178 / 28%);
            outline-offset: 2px;
        }

        /* Superficies, avisos y listados comparten el mismo acabado */
        main form:not(.inline)[class*="bg-white"][class*="border"] {
            border-color: #dce8ee;
            border-radius: 1.25rem;
            box-shadow: 0 12px 32px rgb(11 42 58 / 6%);
        }
        main [role="alert"] { border-radius: .9rem; }
        main .overflow-x-auto { scrollbar-color: #b7cbd5 transparent; scrollbar-width: thin; }
        main table thead th { color: #506b7a; }
        main table tbody tr:focus-within { background: #f0fbfd; }
        ::selection { background: rgb(34 211 238 / 25%); color: #083344; }

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

        /* Acabado único para las tablas de todos los módulos */
        main .lavasoft-table-shell {
            overflow-x: auto;
            border: 1px solid #dce8ee;
            border-radius: 1.25rem;
            background: #fff;
            box-shadow: 0 8px 24px rgb(11 42 58 / 5%);
            -webkit-overflow-scrolling: touch;
        }
        main .lavasoft-table-shell > .overflow-x-auto { border-radius: inherit; }
        main table.lavasoft-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            color: #334e5e;
            font-size: .875rem;
        }
        main table.lavasoft-table thead tr {
            background: #eef6f9 !important;
            color: #476171 !important;
        }
        main table.lavasoft-table thead th {
            padding: .9rem 1rem !important;
            border-bottom: 1px solid #dce8ee !important;
            background: transparent !important;
            color: #476171 !important;
            font-size: .68rem !important;
            font-weight: 800 !important;
            letter-spacing: .08em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        main table.lavasoft-table tbody td {
            padding: .9rem 1rem !important;
            border-bottom: 1px solid #edf3f6 !important;
            background: #fff;
            color: #334e5e;
            vertical-align: middle;
        }
        main table.lavasoft-table tbody tr:nth-child(even) > td { background: #fbfdfe; }
        main table.lavasoft-table tbody tr:last-child > td { border-bottom: 0 !important; }
        main table.lavasoft-table tbody tr { transition: background-color .14s ease; }
        main table.lavasoft-table tbody tr:hover > td,
        main table.lavasoft-table tbody tr:focus-within > td { background: #effbfc !important; }
        main table.lavasoft-table input:not([type="checkbox"]):not([type="radio"]):not([type="hidden"]) {
            width: 100%;
            min-height: 2.5rem;
            border: 1px solid #dbe7ed;
            border-radius: .75rem;
            background: #fff;
            color: #183445;
            padding: .55rem .75rem;
            transition: border-color .16s ease, box-shadow .16s ease;
        }
        main table.lavasoft-table input:not([type="checkbox"]):focus {
            border-color: #0891b2;
            box-shadow: 0 0 0 3px rgb(8 145 178 / 13%);
            outline: none;
        }
        main table.lavasoft-table input[type="checkbox"] { accent-color: var(--lava-cyan); }
        main table.lavasoft-table button {
            min-height: 2.25rem;
            border-radius: .7rem;
            font-size: .75rem;
            font-weight: 700;
            transition: filter .16s ease, transform .16s ease;
        }
        main table.lavasoft-table button:hover { filter: brightness(.96); }
        main table.lavasoft-table--invoice { border-collapse: collapse; font-size: .8rem; }
        main table.lavasoft-table--invoice tbody td {
            padding: .5rem .25rem !important;
            border-bottom: 1px solid #edf3f6 !important;
            background: transparent !important;
        }
        main table.lavasoft-table--invoice tbody tr:hover > td { background: transparent !important; }
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

    @include('shared.estilos-modulos')
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
