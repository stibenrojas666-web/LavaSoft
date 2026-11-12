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
    $rolVisible = $usuarioActual?->getRoleNames()->first();
@endphp

<header class="sticky top-0 z-20 border-b border-slate-200/70 bg-white/80 backdrop-blur-xl">
    <div class="flex h-[76px] items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3 sm:gap-4">
            <button
                @click="sidebarOpen = !sidebarOpen"
                type="button"
                class="grid h-11 w-11 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 lg:hidden"
                aria-label="Abrir menú lateral"
            >
                <span class="block h-5 w-5">@include('layouts.partials.icons', ['icon' => 'menu'])</span>
            </button>

            <div class="min-w-0">
                <h1 class="truncate text-xl font-extrabold tracking-tight text-slate-800 sm:text-2xl">@yield('page-title', $tituloActual)</h1>
                <p class="hidden text-xs font-medium text-slate-500 sm:block">{{ now()->translatedFormat('l d \d\e F, Y') }}</p>
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-3 sm:gap-4">
            @if (!request()->routeIs('dashboard.index') && auth()->user()?->can('ver dashboard'))
    <a href="{{ route('dashboard.index') }}"
       title="Volver al Dashboard"
       aria-label="Volver al Dashboard"
       class="inline-flex h-10 w-10 items-center justify-center rounded-xl
              border border-cyan-200 bg-cyan-50 text-cyan-700
              transition hover:bg-cyan-100 hover:scale-105
              focus:outline-none focus:ring-2 focus:ring-cyan-300">

        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="2">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M3 10.5L12 3l9 7.5M5.25 9v11.25h13.5V9M9 20.25v-6h6v6"/>
        </svg>
    </a>
@endif
            <div class="hidden items-center gap-2 rounded-full bg-emerald-50 px-3.5 py-2 text-xs font-semibold text-emerald-800 ring-1 ring-emerald-200 md:flex">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Sistema activo
            </div>

            <div x-data="{ openUserMenu: false }" class="relative">
                <button type="button" @click="openUserMenu = !openUserMenu" :aria-expanded="openUserMenu.toString()"
                        aria-haspopup="true" class="flex min-h-[44px] items-center gap-2.5 rounded-2xl bg-white py-1.5 pl-1.5 pr-3 ring-1 ring-slate-200 transition hover:ring-cyan-300">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-primary-600 text-xs font-bold text-white">
                        {{ strtoupper(substr($usuarioActual?->name ?? 'Admin', 0, 2)) }}
                    </span>
                    <span class="hidden text-left sm:block">
                        <span class="block text-sm font-semibold leading-tight text-slate-700">{{ $usuarioActual?->name ?? 'Administrador' }}</span>
                        <span class="mt-0.5 block text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ $rolVisible ? ucfirst($rolVisible) : 'Usuario' }}</span>
                    </span>
                    <span class="block h-4 w-4 text-slate-500">@include('layouts.partials.icons', ['icon' => 'chevron-down'])</span>
                </button>

                <div x-show="openUserMenu" x-cloak @click.away="openUserMenu = false" x-transition
                     class="absolute right-0 z-50 mt-2 w-64 overflow-hidden rounded-2xl border border-slate-200 bg-white p-1.5 shadow-xl">
                    @can(config('seguridad.permiso_admin'))
                        <a href="{{ route('seguridad.permisos.index') }}" class="flex min-h-[44px] items-center rounded-xl px-3.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            Seguridad y permisos
                        </a>
                    @endcan
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex min-h-[44px] w-full items-center rounded-xl px-3.5 text-left text-sm font-semibold text-red-700 hover:bg-red-50">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
