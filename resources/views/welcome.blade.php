
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="theme-color" content="#082b3b">
    <meta name="description" content="LavaSoft, sistema de gestión para lavaderos de vehículos.">

    <title>{{ config('app.name', 'LavaSoft') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --navy: #082b3b;
            --navy-light: #10465b;
            --cyan: #0891b2;
            --turquoise: #14b8a6;
            --text: #243746;
            --muted: #647b89;
            --border: #e3edf2;
            --background: #f5f9fb;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: var(--background);
            color: var(--text);
            font-family: "Segoe UI", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
        }

        .site-header {
            position: relative;
            z-index: 2;
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .header-inner {
            width: min(1160px, calc(100% - 40px));
            min-height: 76px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--navy);
            font-size: 21px;
            font-weight: 800;
            letter-spacing: -.7px;
        }

        .brand-icon {
            display: grid;
            width: 43px;
            height: 43px;
            place-items: center;
            border-radius: 13px;
            color: white;
            background: linear-gradient(135deg, var(--navy), var(--cyan));
        }

        .brand-icon svg {
            width: 25px;
            height: 25px;
        }

        .header-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 43px;
            padding: 0 19px;
            border-radius: 10px;
            color: white;
            background: var(--navy);
            font-size: 13px;
            font-weight: 700;
            transition: .2s ease;
        }

        .header-link:hover {
            background: var(--cyan);
            transform: translateY(-1px);
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 90px 20px 100px;
            color: white;
            background:
                radial-gradient(circle at 85% 15%, rgba(20,184,166,.25), transparent 28%),
                radial-gradient(circle at 10% 100%, rgba(8,145,178,.25), transparent 30%),
                linear-gradient(120deg, #062737, #0b3d53 65%, #087e99);
        }

        .hero::before,
        .hero::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero::before {
            width: 450px;
            height: 450px;
            top: -270px;
            right: -90px;
        }

        .hero::after {
            width: 330px;
            height: 330px;
            bottom: -230px;
            left: -100px;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            width: min(920px, 100%);
            margin: auto;
            text-align: center;
        }

        .hero-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border: 1px solid rgba(103,232,249,.3);
            border-radius: 30px;
            color: #a5f3fc;
            background: rgba(255,255,255,.07);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.6px;
            text-transform: uppercase;
        }

        .hero h1 {
            max-width: 800px;
            margin: 25px auto 17px;
            font-size: clamp(37px, 6vw, 66px);
            line-height: 1.08;
            letter-spacing: -2.5px;
        }

        .hero h1 span {
            color: #67e8f9;
        }

        .hero-description {
            max-width: 650px;
            margin: 0 auto;
            color: #d0e5ed;
            font-size: 16px;
            line-height: 1.9;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 32px;
        }

        .button-primary,
        .button-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 50px;
            padding: 0 23px;
            border-radius: 11px;
            font-size: 14px;
            font-weight: 800;
            transition: .2s ease;
        }

        .button-primary {
            color: var(--navy);
            background: white;
        }

        .button-primary:hover {
            color: var(--cyan);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,.15);
        }

        .button-outline {
            color: white;
            border: 1px solid rgba(255,255,255,.35);
            background: rgba(255,255,255,.06);
        }

        .button-outline:hover {
            background: rgba(255,255,255,.15);
            transform: translateY(-2px);
        }

        .features {
            width: min(1100px, calc(100% - 40px));
            margin: -42px auto 0;
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .feature-card {
            padding: 27px 23px;
            border: 1px solid var(--border);
            border-radius: 17px;
            background: white;
            box-shadow: 0 10px 30px rgba(8,43,59,.06);
        }

        .feature-icon {
            display: grid;
            width: 47px;
            height: 47px;
            place-items: center;
            margin-bottom: 17px;
            border-radius: 13px;
            color: #087e99;
            background: #e9f8fb;
        }

        .feature-icon svg {
            width: 24px;
            height: 24px;
        }

        .feature-card h2 {
            margin: 0 0 9px;
            color: var(--navy);
            font-size: 16px;
        }

        .feature-card p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .bottom-section {
            width: min(900px, calc(100% - 40px));
            margin: 75px auto;
            text-align: center;
        }

        .bottom-section h2 {
            margin: 0 0 13px;
            color: var(--navy);
            font-size: clamp(25px, 4vw, 34px);
            letter-spacing: -.8px;
        }

        .bottom-section p {
            max-width: 650px;
            margin: 0 auto;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.9;
        }

        .site-footer {
            padding: 24px 20px;
            border-top: 1px solid var(--border);
            background: white;
            text-align: center;
        }

        .site-footer p {
            margin: 0;
            color: #8295a0;
            font-size: 12px;
        }

        @media (max-width: 760px) {
            .hero {
                padding: 70px 20px 85px;
            }

            .features {
                grid-template-columns: 1fr;
                margin-top: -30px;
            }

            .feature-card {
                padding: 23px;
            }

            .bottom-section {
                margin: 55px auto;
            }
        }

        @media (max-width: 420px) {
            .header-inner {
                width: calc(100% - 28px);
            }

            .brand {
                font-size: 18px;
            }

            .header-link {
                padding: 0 12px;
                font-size: 12px;
            }

            .hero h1 {
                letter-spacing: -1.5px;
            }

            .hero-actions {
                flex-direction: column;
            }

            .button-primary,
            .button-outline {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>
    <header class="site-header">
        <div class="header-inner">
            <a href="{{ url('/') }}" class="brand" aria-label="LavaSoft inicio">
                <span class="brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M3 17h18l-2-7H5l-2 7Z"/>
                        <path d="M5 10l2-5h10l2 5"/>
                        <circle cx="7.5" cy="18.5" r="1.5"/>
                        <circle cx="16.5" cy="18.5" r="1.5"/>
                        <path d="M12 2v2m-4 0 1 2m7-2-1 2"/>
                    </svg>
                </span>
                LavaSoft
            </a>

            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="header-link">
                    Iniciar sesión
                </a>
            @endif
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <span class="hero-label">
                    <span aria-hidden="true">✦</span>
                    Gestión inteligente para lavaderos
                </span>

                <h1>
                    Administra tu lavadero con
                    <span>más control y facilidad</span>
                </h1>

                <p class="hero-description">
                    LavaSoft reúne en un solo lugar la administración de citas,
                    clientes, vehículos, servicios, empleados y pagos.
                    Organiza tu negocio y mantén toda tu operación bajo control.
                </p>

                <div class="hero-actions">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="button-primary">
                            Acceder al sistema
                            <span aria-hidden="true">→</span>
                        </a>
                    @endif

                    <a href="#funciones" class="button-outline">
                        Conocer funcionalidades
                    </a>
                </div>
            </div>
        </section>

        <section class="features" id="funciones" aria-label="Funcionalidades de LavaSoft">
            <article class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <rect x="3" y="4" width="18" height="17" rx="2"/>
                        <path d="M16 2v4M8 2v4M3 10h18M8 14h3m-3 3h6"/>
                    </svg>
                </div>
                <h2>Agenda organizada</h2>
                <p>
                    Administra citas, programa servicios y consulta la
                    disponibilidad de tu operación.
                </p>
            </article>

            <article class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <circle cx="9" cy="8" r="3.5"/>
                        <path d="M3 21v-2a6 6 0 0 1 12 0v2"/>
                        <path d="M16 5.5a3.5 3.5 0 0 1 0 6.8M18 15a5 5 0 0 1 3 4.5V21"/>
                    </svg>
                </div>
                <h2>Clientes y vehículos</h2>
                <p>
                    Consulta la información de tus clientes y lleva el
                    seguimiento de sus vehículos e historial.
                </p>
            </article>

            <article class="feature-card">
                <div class="feature-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                        <path d="M3 10h18M7 15h4m5 0h1"/>
                    </svg>
                </div>
                <h2>Servicios y pagos</h2>
                <p>
                    Controla los servicios realizados, sus precios y los
                    pagos registrados de manera ordenada.
                </p>
            </article>
        </section>

        <section class="bottom-section">
            <h2>Todo tu negocio, en un solo lugar</h2>
            <p>
                Una herramienta diseñada para facilitar el trabajo diario,
                mejorar la organización y ayudarte a administrar tu lavadero
                de vehículos de forma profesional.
            </p>
        </section>
    </main>

    <footer class="site-footer">
        <p>
            © {{ date('Y') }} LavaSoft. Todos los derechos reservados.
        </p>
    </footer>
</body>
</html>
