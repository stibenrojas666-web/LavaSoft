@php
    $titulosPantalla = [
        'dashboard.index' => 'Dashboard',
        'clientes.*' => 'Clientes',
        'empleados.*' => 'Empleados',
        'turnos.*' => 'Turnos',
        'vehiculos.*' => 'Vehículos',
        'agenda.*' => 'Agenda de citas',
        'agendaServicio.*' => 'Historial de citas',
        'pagos.*' => 'Pagos',
        'servicios.*' => 'Servicios',
        'tipoVehiculo.*' => 'Tipos de vehículo',
        'precioServicio.*' => 'Precios de servicio',
        'configuracion.*' => 'Configuración del lavadero',
        'reportes.diario' => 'Reporte diario',
        'reportes.periodo' => 'Reporte semanal y mensual',
        'seguridad.roles.*' => 'Roles',
        'seguridad.permisos.*' => 'Permisos',
        'seguridad.cuentas.*' => 'Cuentas de usuario',
        'seguridad.usuarios.*' => 'Usuarios y roles',
    ];

    $tituloActual = 'LavaSoft';

    foreach ($titulosPantalla as $patron => $titulo) {
        if (request()->routeIs($patron)) {
            $tituloActual = $titulo;
            break;
        }
    }

    $usuarioActual = auth()->user();
    $rolVisible = $usuarioActual && method_exists($usuarioActual, 'getRoleNames')
        ? $usuarioActual->getRoleNames()->first()
        : null;
@endphp

<header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/95 backdrop-blur-xl">
    <div class="flex min-h-[76px] items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3 sm:gap-4">
            <button
                type="button"
                @click="sidebarOpen = !sidebarOpen"
                class="grid h-11 w-11 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-cyan-300 hover:bg-cyan-50 hover:text-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 lg:hidden"
                aria-label="Abrir menú lateral"
            >
                <span class="block h-5 w-5">
                    @include('layouts.partials.icons', ['icon' => 'menu'])
                </span>
            </button>

            <div class="min-w-0">
                <p class="truncate text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-700 sm:text-xs">
                    LavaSoft
                </p>
                <h1 class="truncate text-lg font-extrabold tracking-tight text-slate-800 sm:text-xl">
                    @yield('page-title', $tituloActual)
                </h1>
                <p class="hidden text-xs font-medium capitalize text-slate-500 sm:block">
                    {{ now()->translatedFormat('l d \d\e F, Y') }}
                </p>
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-2 sm:gap-3">
            <div class="hidden items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-800 md:flex">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Sistema activo
            </div>

            @auth
                <div x-data="{ openUserMenu: false }" class="relative">
                    <button
                        type="button"
                        @click="openUserMenu = !openUserMenu"
                        :aria-expanded="openUserMenu.toString()"
                        aria-haspopup="true"
                        class="flex min-h-[44px] items-center gap-2 rounded-xl border border-slate-200 bg-white py-1.5 pl-1.5 pr-2.5 transition hover:border-cyan-300 hover:bg-cyan-50"
                    >
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-cyan-800 text-xs font-bold text-white">
                            {{ strtoupper(substr($usuarioActual?->name ?? 'US', 0, 2)) }}
                        </span>

                        <span class="hidden max-w-[150px] text-left sm:block">
                            <span class="block truncate text-sm font-bold leading-tight text-slate-700">
                                {{ $usuarioActual?->name ?? 'Usuario' }}
                            </span>
                            <span class="mt-0.5 block truncate text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                {{ $rolVisible ? ucfirst($rolVisible) : 'Usuario' }}
                            </span>
                        </span>

                        <span class="block h-4 w-4 text-slate-500">
                            @include('layouts.partials.icons', ['icon' => 'chevron-down'])
                        </span>
                    </button>

                    <div
                        x-cloak
                        x-show="openUserMenu"
                        @click.away="openUserMenu = false"
                        x-transition
                        class="absolute right-0 z-50 mt-2 w-60 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-xl"
                    >
                        @can(config('seguridad.permiso_admin'))
                            <a
                                href="{{ route('seguridad.permisos.index') }}"
                                class="flex min-h-11 items-center rounded-xl px-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                            >
                                Seguridad y permisos
                            </a>
                        @endcan

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="flex min-h-11 w-full items-center rounded-xl px-3 text-left text-sm font-semibold text-rose-700 transition hover:bg-rose-50"
                            >
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>
