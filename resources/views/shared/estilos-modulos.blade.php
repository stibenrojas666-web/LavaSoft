
<style>
    /* =========================================
       IDENTIDAD VISUAL COMPARTIDA - LAVASOFT
    ========================================= */

    :root {
        --lava-petroleo: #082b3b;
        --lava-azul: #0b3b50;
        --lava-celeste: #087e99;
        --lava-turquesa: #0e7490;
        --lava-fondo: #f4f8fb;
        --lava-borde: #e3edf2;
        --lava-texto: #243746;
        --lava-muted: #647b89;
        --lava-blanco: #ffffff;
        --lava-sombra: 0 5px 18px rgba(8, 43, 59, .05);
    }

    /* =========================================
       CONTENEDORES
    ========================================= */

    .modulo-page {
        width: 100%;
        max-width: 1120px;
        margin: 0 auto;
    }

    .modulo-panel,
    .modulo-card {
        background: var(--lava-blanco);
        border: 1px solid var(--lava-borde);
        border-radius: 18px;
        box-shadow: var(--lava-sombra);
    }

    /* =========================================
       ENCABEZADOS
    ========================================= */

    .modulo-banner {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        padding: 27px 30px;
        color: var(--lava-blanco);
        border-radius: 20px 20px 0 0;
        background: linear-gradient(
            115deg,
            var(--lava-petroleo),
            var(--lava-azul),
            var(--lava-celeste)
        );
    }

    .lavasoft-module-header {
        border-radius: 20px;
    }

    .modulo-banner::before,
    .modulo-banner::after {
        content: "";
        position: absolute;
        z-index: -1;
        border: 2px solid rgb(34 211 238 / 18%);
        border-radius: 50%;
        pointer-events: none;
    }

    .modulo-banner::before {
        width: 230px;
        height: 230px;
        right: 35px;
        top: -125px;
    }

    .modulo-banner::after {
        width: 145px;
        height: 145px;
        right: 135px;
        bottom: -100px;
    }

    .modulo-banner h1,
    .modulo-banner h2,
    .modulo-banner p {
        color: var(--lava-blanco);
    }

    /* =========================================
       FORMULARIOS
    ========================================= */

    .modulo-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px 25px;
    }

    .modulo-field {
        min-width: 0;
    }

    .modulo-field label {
        display: block;
        margin-bottom: 8px;
        color: var(--lava-petroleo);
        font-size: 13px;
        font-weight: 700;
    }

    .modulo-field input,
    .modulo-field select,
    .modulo-field textarea {
        width: 100%;
        min-height: 47px;
        padding: 11px 14px;
        color: var(--lava-texto);
        background: #f7fafc;
        border: 1px solid #dce8ee;
        border-radius: 11px;
        outline: none;
        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background-color .2s ease;
    }

    .modulo-field textarea {
        min-height: 110px;
        resize: vertical;
    }

    .modulo-field input:focus,
    .modulo-field select:focus,
    .modulo-field textarea:focus {
        background: var(--lava-blanco);
        border-color: var(--lava-celeste);
        box-shadow: 0 0 0 3px rgba(8, 126, 153, .10);
    }

    .modulo-field input:disabled,
    .modulo-field select:disabled,
    .modulo-field textarea:disabled {
        background: #edf2f5;
        color: #8295a0;
        cursor: not-allowed;
    }

    /* =========================================
       BOTONES
    ========================================= */

    .modulo-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
        padding: 9px 17px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition:
            background-color .2s ease,
            color .2s ease,
            border-color .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .modulo-btn:focus-visible {
        outline: 3px solid rgb(8 126 153 / 25%);
        outline-offset: 3px;
    }

    .modulo-btn-primary {
        color: var(--lava-blanco);
        border: 0;
        background: linear-gradient(110deg, var(--lava-azul), var(--lava-celeste));
    }

    .modulo-btn-primary:hover {
        color: var(--lava-blanco);
        transform: translateY(-1px);
        box-shadow: 0 5px 13px rgb(8 126 153 / 18%);
    }

    .modulo-btn-secondary {
        color: var(--lava-petroleo);
        border: 1px solid var(--lava-borde);
        background: var(--lava-blanco);
    }

    .modulo-btn-secondary:hover {
        background: #f1f8fa;
        border-color: #b8d8e2;
    }

    /* =========================================
       AVISOS
    ========================================= */

    .modulo-note {
        padding: 13px 16px;
        color: #315b70;
        background: #eff8fc;
        border: 1px solid #d8edf5;
        border-radius: 11px;
        font-size: 13px;
        line-height: 1.6;
    }

    /* =========================================
       ACCIONES
    ========================================= */

    .modulo-actions,
    .cliente-actions {
        border-top: 1px solid #eaf0f4;
        background: #fbfdff;
    }

    /* =========================================
       TABLAS DE MÓDULOS
    ========================================= */

    .modulo-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        color: #334e5e;
        font-size: .875rem;
    }

    .modulo-table thead th {
        padding: .9rem 1rem;
        border-bottom: 1px solid var(--lava-borde);
        background: #eef6f9;
        color: #476171;
        font-size: .68rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .modulo-table tbody td {
        padding: .9rem 1rem;
        border-bottom: 1px solid #edf3f6;
        color: #334e5e;
        vertical-align: middle;
    }

    .modulo-table tbody tr:nth-child(even) > td {
        background: #fbfdfe;
    }

    .modulo-table tbody tr:last-child > td {
        border-bottom: 0;
    }

    .modulo-table tbody tr {
        transition: background-color .14s ease;
    }

    .modulo-table tbody tr:hover > td {
        background: #effbfc;
    }

    /* =========================================
       SUPERFICIES DE MÓDULOS
    ========================================= */

    main :is(
        .modulo-panel,
        .modulo-card,
        .cliente-stat,
        .empleado-stat,
        .empleado-card,
        .cliente-card,
        .as-panel
    ) {
        border-color: var(--lava-borde);
        border-radius: 17px;
        box-shadow: 0 5px 16px rgb(11 42 58 / 5%);
    }

    /* =========================================
       ACCESIBILIDAD Y MOVIMIENTO
    ========================================= */

    .modulo-btn:disabled {
        opacity: .55;
        cursor: not-allowed;
        transform: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .modulo-btn,
        .modulo-field input,
        .modulo-field select,
        .modulo-field textarea,
        .modulo-table tbody tr {
            transition: none;
        }
    }

    /* =========================================
       DISEÑO RESPONSIVE
    ========================================= */

    @media (max-width: 700px) {
        .lava-main-content {
            width: 100%;
            max-width: 100%;
            padding: 12px !important;
        }

        main {
            min-width: 0;
        }

        main :is(section, article, form, .rounded-2xl, .rounded-xl) {
            min-width: 0;
        }

        main :is(.modulo-page, .cliente-page, .empleado-page, .empleado-form-page) {
            width: 100%;
            max-width: 100%;
            margin-inline: auto;
        }

        main .overflow-x-auto {
            width: 100%;
            max-width: 100%;
            overscroll-behavior-x: contain;
            -webkit-overflow-scrolling: touch;
        }

        main :is(input, select, textarea) {
            max-width: 100%;
            min-width: 0;
        }

        main :is(.cliente-actions, .empleado-actions) {
            flex-wrap: wrap;
        }

        main :is(.cliente-actions .cliente-btn, .empleado-actions .empleado-btn) {
            min-width: 0;
            flex: 1 1 125px;
        }

        main [class*="grid-cols-2"] {
            grid-template-columns: minmax(0, 1fr) !important;
        }

        main [class*="grid-cols-3"],
        main [class*="grid-cols-4"] {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        .modulo-form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .modulo-banner {
            padding: 22px 18px;
        }

        .modulo-btn {
            max-width: 100%;
        }

        .modulo-table {
            font-size: .8rem;
        }

        .modulo-table thead th,
        .modulo-table tbody td {
            padding: .7rem .65rem;
        }
    }

    @media (max-width: 420px) {
        .lava-main-content {
            padding: 10px !important;
        }

        main [class*="grid-cols-3"],
        main [class*="grid-cols-4"] {
            grid-template-columns: minmax(0, 1fr) !important;
        }

        main .lavasoft-module-header {
            padding: 16px !important;
        }
    }

    @media (min-width: 1600px) {
        .lava-main-content {
            max-width: 1800px;
        }
    }

    .lava-filter-popover {
        right: 0;
        width: min(82vw, 28rem);
        max-width: calc(100vw - 2rem);
        max-height: min(70vh, 32rem);
        padding: .75rem;
        overflow-y: auto;
    }

    .lava-filter-popover :is(input, select) {
        min-height: 36px !important;
        padding-block: 6px !important;
    }

    @media (max-width: 1023px) {
        .lava-filter-popover {
            right: auto;
            left: 0;
        }
    }
</style>
