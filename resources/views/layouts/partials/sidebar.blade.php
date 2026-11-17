@php
    $grupos = [
        'Principal' => [
            ['label' => 'Dashboard', 'route' => 'dashboard.index', 'icon' => 'home', 'can' => 'ver dashboard'],
        ],

        'Gestión' => [
            ['label' => 'Clientes', 'route' => 'clientes.index', 'icon' => 'users', 'can' => 'ver clientes'],
            ['label' => 'Empleados', 'route' => 'empleados.index', 'icon' => 'users', 'can' => 'ver empleados'],
            ['label' => 'Agenda', 'route' => 'agenda.index', 'icon' => 'calendar', 'can' => 'ver agenda'],
        ],

        'Operación' => [
            ['label' => 'Turnos', 'route' => 'turnos.index', 'icon' => 'chart', 'can' => 'ver turnos'],
            ['label' => 'Vehículos', 'route' => 'vehiculos.index', 'icon' => 'box', 'can' => 'ver vehiculos'],
            ['label' => 'Historial de citas', 'route' => 'agendaServicio.index', 'icon' => 'history', 'can' => 'ver agendaservicio'],
            ['label' => 'Historial de pagos', 'route' => 'pagos.historial', 'icon' => 'credit-card', 'can' => 'ver pagos'],
        ],

        'Catálogo' => [
            ['label' => 'Servicios', 'route' => 'servicios.index', 'icon' => 'sparkles', 'can' => 'ver servicios'],
            ['label' => 'Tipos de vehículo', 'route' => 'tipoVehiculo.index', 'icon' => 'box', 'can' => 'ver tipovehiculo'],
            ['label' => 'Precios de servicio', 'route' => 'precioServicio.index', 'icon' => 'tag', 'can' => 'ver precioservicio'],
        ],

        'Reportes' => [
            ['label' => 'Reporte diario', 'route' => 'reportes.diario', 'icon' => 'chart', 'can' => 'ver dashboard'],
            ['label' => 'Reporte por período', 'route' => 'reportes.periodo', 'icon' => 'chart', 'can' => 'ver dashboard'],
        ],
    ];

    $usuario = auth()->user();
    $configuracion = $configuracionLavadero ?? null;
@endphp

<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-40 flex w-72 transform flex-col bg-[#0b2a3a] text-slate-300 shadow-2xl transition-transform duration-300 ease-in-out lg:translate-x-0"
    aria-label="Menú principal"
>
    <div class="flex h-full min-h-0 flex-col">
        <a
            href="{{ route('dashboard.index') }}"
            class="flex min-h-[84px] items-center gap-3 border-b border-white/10 px-6"
        >
            @if ($configuracion?->mostrar_logo && $configuracion?->logo_path)
                <img
                    src="{{ route('lavadero.logo', ['v' => $configuracion->updated_at?->timestamp ?? 0]) }}"
                    alt="Logo de {{ $configuracion->nombre }}"
                    class="h-11 w-11 rounded-xl bg-white object-contain p-1.5"
                >
            @else
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-cyan-500 text-white shadow-lg shadow-cyan-950/30">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c3.5 4.2 6 7.2 6 10.2A6 6 0 0 1 6 13.2C6 10.2 8.5 7.2 12 3z"/>
                    </svg>
                </span>
            @endif

            <span class="min-w-0">
                <span class="block truncate text-lg font-extrabold tracking-tight text-white">
                    {{ $configuracion?->nombre ?: 'LavaSoft' }}
                </span>
                <span class="mt-0.5 block text-[10px] font-bold uppercase tracking-[0.18em] text-cyan-300">
                    Centro de lavado
                </span>
            </span>
        </a>

        <nav class="min-h-0 flex-1 space-y-5 overflow-y-auto px-3 py-5" aria-label="Navegación lateral">
            @foreach ($grupos as $titulo => $enlaces)
                @php
                    $visibles = collect($enlaces)->filter(
                        fn ($enlace) => $usuario?->can($enlace['can'])
                    );

                    $grupoActivo = $visibles->contains(function ($enlace) {
                        $patron = $enlace['match'] ?? (
                            str_ends_with($enlace['route'], '.index')
                                ? str_replace('.index', '.*', $enlace['route'])
                                : $enlace['route']
                        );

                        return request()->routeIs($patron);
                    });
                @endphp

                @if ($visibles->isNotEmpty())
                    @if (in_array($titulo, ['Principal', 'Gestión']))
                        <section class="space-y-1">
                            <p class="px-3 pb-1 text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-500">
                                {{ $titulo }}
                            </p>

                            @foreach ($visibles as $enlace)
                                @php
                                    $patron = $enlace['match'] ?? (
                                        str_ends_with($enlace['route'], '.index')
                                            ? str_replace('.index', '.*', $enlace['route'])
                                            : $enlace['route']
                                    );

                                    $activo = request()->routeIs($patron);
                                @endphp

                                <a
                                    href="{{ route($enlace['route']) }}"
                                    @click="sidebarOpen = false"
                                    @if ($activo) aria-current="page" @endif
                                    class="sidebar-link {{ $activo ? 'sidebar-link-active' : '' }}"
                                >
                                    <span class="sidebar-icon">
                                        @include('layouts.partials.icons', ['icon' => $enlace['icon']])
                                    </span>
                                    <span class="truncate">{{ $enlace['label'] }}</span>
                                </a>
                            @endforeach
                        </section>
                    @else
                        <section
                            x-data="{ abierto: {{ $grupoActivo ? 'true' : 'false' }} }"
                            class="space-y-1"
                        >
                            <button
                                type="button"
                                @click="abierto = !abierto"
                                :aria-expanded="abierto.toString()"
                                class="flex min-h-10 w-full items-center justify-between rounded-xl px-3 text-left text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400 transition hover:bg-white/5 hover:text-white"
                            >
                                <span>{{ $titulo }}</span>
                                <svg
                                    class="h-4 w-4 transition-transform duration-200"
                                    :class="abierto ? 'rotate-180' : ''"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                                </svg>
                            </button>

                            <div x-cloak x-show="abierto" x-transition class="space-y-1 pl-1">
                                @foreach ($visibles as $enlace)
                                    @php
                                        $patron = $enlace['match'] ?? (
                                            str_ends_with($enlace['route'], '.index')
                                                ? str_replace('.index', '.*', $enlace['route'])
                                                : $enlace['route']
                                        );

                                        $activo = request()->routeIs($patron);
                                    @endphp

                                    <a
                                        href="{{ route($enlace['route']) }}"
                                        @click="sidebarOpen = false"
                                        @if ($activo) aria-current="page" @endif
                                        class="sidebar-link {{ $activo ? 'sidebar-link-active' : '' }}"
                                    >
                                        <span class="sidebar-icon">
                                            @include('layouts.partials.icons', ['icon' => $enlace['icon']])
                                        </span>
                                        <span class="truncate">{{ $enlace['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endif
            @endforeach

            @if ($usuario?->can(config('seguridad.permiso_admin')))
                @php
                    $seguridadActiva = request()->routeIs('seguridad.*');
                @endphp

                <section x-data="{ abierto: {{ $seguridadActiva ? 'true' : 'false' }} }" class="space-y-1">
                    <button
                        type="button"
                        @click="abierto = !abierto"
                        :aria-expanded="abierto.toString()"
                        class="flex min-h-10 w-full items-center justify-between rounded-xl px-3 text-left text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400 transition hover:bg-white/5 hover:text-white"
                    >
                        <span>Usuarios y permisos</span>
                        <svg
                            class="h-4 w-4 transition-transform duration-200"
                            :class="abierto ? 'rotate-180' : ''"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>

                    <div x-cloak x-show="abierto" x-transition class="space-y-1 pl-1">
                        @foreach ([
                            ['Roles', 'seguridad.roles.index', 'seguridad.roles.*', 'shield'],
                            ['Permisos', 'seguridad.permisos.index', 'seguridad.permisos.*', 'shield'],
                            ['Cuentas de usuario', 'seguridad.cuentas.index', 'seguridad.cuentas.*', 'users'],
                            ['Usuarios y roles', 'seguridad.usuarios.index', 'seguridad.usuarios.*', 'users'],
                        ] as [$label, $ruta, $patron, $icono])
                            <a
                                href="{{ route($ruta) }}"
                                @click="sidebarOpen = false"
                                @if (request()->routeIs($patron)) aria-current="page" @endif
                                class="sidebar-link {{ request()->routeIs($patron) ? 'sidebar-link-active' : '' }}"
                            >
                                <span class="sidebar-icon">
                                    @include('layouts.partials.icons', ['icon' => $icono])
                                </span>
                                <span class="truncate">{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>

                <a
                    href="{{ route('configuracion.edit') }}"
                    @click="sidebarOpen = false"
                    @if (request()->routeIs('configuracion.*')) aria-current="page" @endif
                    class="sidebar-link {{ request()->routeIs('configuracion.*') ? 'sidebar-link-active' : '' }}"
                >
                    <span class="sidebar-icon">
                        @include('layouts.partials.icons', ['icon' => 'cog'])
                    </span>
                    <span class="truncate">Configuración del lavadero</span>
                </a>
            @endif
        </nav>

        @auth
            <div class="border-t border-white/10 p-4">
                <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-3 py-3">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-cyan-600 text-sm font-extrabold text-white">
                        {{ strtoupper(substr($usuario->name ?? 'US', 0, 2)) }}
                    </span>

                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-sm font-bold text-white">
                            {{ $usuario->name }}
                        </span>
                        <span class="block truncate text-xs text-slate-400">
                            Panel administrativo
                        </span>
                    </span>
                </div>
            </div>
        @endauth
    </div>
</aside>
