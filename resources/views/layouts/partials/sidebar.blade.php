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
@endphp

<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-40 flex w-72 transform flex-col bg-navy-900 text-slate-300 shadow-2xl transition-transform duration-200 ease-in-out lg:translate-x-0"
    aria-label="Menú principal"
>
    <div class="flex h-full flex-col">
        {{-- Marca --}}
        <a href="{{ route('dashboard.index') }}" class="flex h-[76px] items-center gap-3 px-6">
            @if($configuracionLavadero->mostrar_logo && $configuracionLavadero->logo_path)
                <img src="{{ asset('storage/' . ltrim($configuracionLavadero->logo_path, '/')) }}"
                    alt="Logo de {{ $configuracionLavadero->nombre }}" class="h-11 w-11 rounded-2xl bg-white object-contain p-1.5 shadow-lg">
            @else
                <span class="grid h-11 w-11 place-items-center rounded-2xl bg-primary-500 shadow-lg shadow-cyan-900/40" aria-hidden="true">
                    <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3c3.5 4.2 6 7.2 6 10.2A6 6 0 0 1 6 13.2C6 10.2 8.5 7.2 12 3z"/></svg>
                </span>
            @endif
            <span class="min-w-0">
                <span class="block truncate text-lg font-extrabold tracking-tight text-white">{{ $configuracionLavadero->nombre ?: 'LavaSoft' }}</span>
                <span class="block text-[11px] font-semibold uppercase tracking-[0.16em] text-cyan-300/80">Centro de lavado</span>
            </span>
        </a>

        

            {{-- Navegación --}}
        <nav class="flex-1 space-y-2 overflow-y-auto px-3 py-3"
            aria-label="Navegación lateral">

            @foreach ($grupos as $titulo => $enlaces)

                @php
                    $visibles = collect($enlaces)->filter(
                        fn ($l) => auth()->user()?->can($l['can'])
                    );

                    $grupoActivo = $visibles->contains(function ($l) {
                        $patron = $l['match'] ?? (
                            str_ends_with($l['route'], '.index')
                                ? str_replace('.index', '.*', $l['route'])
                                : $l['route']
                        );

                        return request()->routeIs($patron);
                    });
                @endphp

                @if ($visibles->isEmpty())
                    @continue
                @endif

                {{-- Dashboard y Gestión siempre visibles --}}
                @if (in_array($titulo, ['Principal', 'Gestión']))

                    @foreach ($visibles as $link)

                        @php
                            $patron = $link['match'] ?? (
                                str_ends_with($link['route'], '.index')
                                    ? str_replace('.index', '.*', $link['route'])
                                    : $link['route']
                            );

                            $active = request()->routeIs($patron);
                        @endphp

                        <a href="{{ route($link['route']) }}"
                            @click="sidebarOpen = false"
                            @if($active) aria-current="page" @endif
                            class="sidebar-link {{ $active ? 'sidebar-link-active' : '' }}">

                            <span class="sidebar-icon">
                                @include('layouts.partials.icons', ['icon' => $link['icon']])
                            </span>

                            <span class="truncate">{{ $link['label'] }}</span>
                        </a>

                    @endforeach

                {{-- Los demás módulos serán desplegables --}}
                @else

                    <section x-data="{ abierto: {{ $grupoActivo ? 'true' : 'false' }} }">

                        <button
                            type="button"
                            @click="abierto = !abierto"
                            :aria-expanded="abierto"
                            class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400 transition hover:bg-white/5 hover:text-white">

                            <span>{{ $titulo }}</span>

                            <svg class="h-4 w-4 transition-transform duration-200"
                                :class="abierto ? 'rotate-180' : ''"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        <div x-show="abierto"
                            x-transition
                            class="mt-1 space-y-0.5 pl-1">

                            @foreach ($visibles as $link)

                                @php
                                    $patron = $link['match'] ?? (
                                        str_ends_with($link['route'], '.index')
                                            ? str_replace('.index', '.*', $link['route'])
                                            : $link['route']
                                    );

                                    $active = request()->routeIs($patron);
                                @endphp

                                <a href="{{ route($link['route']) }}"
                                    @click="sidebarOpen = false"
                                    @if($active) aria-current="page" @endif
                                    class="sidebar-link {{ $active ? 'sidebar-link-active' : '' }}">

                                    <span class="sidebar-icon">
                                        @include('layouts.partials.icons', ['icon' => $link['icon']])
                                    </span>

                                    <span class="truncate">{{ $link['label'] }}</span>
                                </a>

                            @endforeach

                        </div>
                    </section>

                @endif

            @endforeach

            {{-- Usuarios y permisos --}}
            @if (auth()->user()?->can(config('seguridad.permiso_admin')))

                @php
                    $seguridadActiva = request()->routeIs('seguridad.*', 'configuracion.*');
                @endphp

                <section x-data="{ abierto: {{ $seguridadActiva ? 'true' : 'false' }} }">

                    <button
                        type="button"
                        @click="abierto = !abierto"
                        :aria-expanded="abierto"
                        class="flex w-full items-center justify-between rounded-xl px-3 py-2 text-left text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400 transition hover:bg-white/5 hover:text-white">

                        <span>Usuarios y permisos</span>

                        <svg class="h-4 w-4 transition-transform duration-200"
                            :class="abierto ? 'rotate-180' : ''"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>

                    <div x-show="abierto"
                        x-transition
                        class="mt-1 space-y-0.5 pl-1">

                        @foreach ([
                            ['Roles', 'seguridad.roles.index', 'seguridad.roles.*', 'shield'],
                            ['Permisos', 'seguridad.permisos.index', 'seguridad.permisos.*', 'shield'],
                            ['Cuentas de usuario', 'seguridad.cuentas.index', 'seguridad.cuentas.*', 'users'],
                            ['Usuarios y roles', 'seguridad.usuarios.index', 'seguridad.usuarios.*', 'users'],
                        ] as [$label, $route, $match, $icon])

                            <a href="{{ route($route) }}"
                                @click="sidebarOpen = false"
                                @if(request()->routeIs($match)) aria-current="page" @endif
                                class="sidebar-link {{ request()->routeIs($match) ? 'sidebar-link-active' : '' }}">

                                <span class="sidebar-icon">
                                    @include('layouts.partials.icons', ['icon' => $icon])
                                </span>

                                <span class="truncate">{{ $label }}</span>
                            </a>

                        @endforeach

                    </div>
                </section>

            @endif

            {{-- Configuración del lavadero --}}
            @if (auth()->user()?->can(config('seguridad.permiso_admin')))

                <a href="{{ route('configuracion.edit') }}"
                    @click="sidebarOpen = false"
                    @if(request()->routeIs('configuracion.*')) aria-current="page" @endif
                    class="sidebar-link {{ request()->routeIs('configuracion.*') ? 'sidebar-link-active' : '' }}">

                    <span class="sidebar-icon">
                        @include('layouts.partials.icons', ['icon' => 'cog'])
                    </span>

                    <span class="truncate">Configuración del lavadero</span>
                </a>

            @endif

        </nav>


        {{-- Usuario --}}
        <div class="p-4">
            <div class="flex items-center gap-3 rounded-2xl bg-white/5 px-3 py-3 ring-1 ring-white/10">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-primary-500 text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'Admin', 0, 2)) }}
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-sm font-semibold text-white">{{ auth()->user()?->name ?? 'Administrador' }}</span>
                    <span class="block text-xs text-slate-400">Panel administrativo</span>
                </span>
            </div>
        </div>
    </div>
</aside>
