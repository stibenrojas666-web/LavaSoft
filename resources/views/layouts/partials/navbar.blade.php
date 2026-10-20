@php
    $titulosPantalla = [
        'dashboard.index' => 'Dashboard',
        'clientes.*' => 'Clientes',
        'empleados.*' => 'Empleados',
        'turnos.*' => 'Turnos',
        'vehiculos.*' => 'Vehículos',
        'agenda.*' => 'Agenda de citas',
        'agendaServicio.*' => 'Servicios por cita',
        'pagos.*' => 'Pagos',
        'servicios.*' => 'Servicios',
        'tipoVehiculo.*' => 'Tipos de vehículo',
        'precioServicio.*' => 'Precios de servicio',
    ];
    $tituloActual = 'LavaSoft';
    foreach ($titulosPantalla as $patron => $titulo) {
        if (request()->routeIs($patron)) {
            $tituloActual = $titulo;
            break;
        }
    }
    $usuarioActual = auth()->user();
@endphp

<header class="sticky top-0 z-20 border-b border-slate-200/80 bg-white/95 backdrop-blur">
    <div class="flex h-[76px] items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3 sm:gap-4">
            <button
                @click="sidebarOpen = !sidebarOpen"
                type="button"
                class="rounded-xl border border-slate-200 p-2 text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 lg:hidden"
                aria-label="Abrir menú lateral"
            >
                <span class="block h-5 w-5">@include('layouts.partials.icons', ['icon' => 'menu'])</span>
            </button>

            <div class="min-w-0">
                <p class="truncate text-lg font-semibold tracking-tight text-slate-800 sm:text-xl">@yield('page-title', $tituloActual)</p>
                <p class="hidden text-xs text-slate-500 sm:block">Panel de administración de LavaSoft</p>
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-3 sm:gap-5">
            <div class="hidden items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 sm:flex">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Sistema activo
            </div>
            <div class="hidden text-right sm:block">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Hoy</p>
                <p class="text-sm font-semibold text-slate-700">{{ now()->format('d/m/Y') }}</p>
            </div>
            <div x-data="{ openUserMenu: false }" class="relative">
                <button type="button" @click="openUserMenu = !openUserMenu" :aria-expanded="openUserMenu.toString()"
                        aria-haspopup="true" class="flex items-center gap-2 rounded-xl bg-slate-50 py-1.5 pl-1.5 pr-3 ring-1 ring-slate-200/80 hover:bg-slate-100">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary-100 text-xs font-bold text-primary-700">
                        {{ strtoupper(substr($usuarioActual?->name ?? 'Admin', 0, 2)) }}
                    </span>
                    <span class="hidden text-sm font-medium text-slate-700 sm:block">{{ $usuarioActual?->name ?? 'Administrador' }}</span>
                    <span class="block h-4 w-4 text-slate-500">@include('layouts.partials.icons', ['icon' => 'chevron-down'])</span>
                </button>

                <div x-show="openUserMenu" x-cloak @click.away="openUserMenu = false"
                     class="absolute right-0 z-50 mt-2 w-60 overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-lg">
                    @can(config('seguridad.permiso_admin'))
                        <a href="{{ route('seguridad.permisos.index') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
                            Seguridad y permisos
                        </a>
                    @endcan
                    <div class="border-t border-slate-100"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 text-left text-sm text-red-600 hover:bg-red-50">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
