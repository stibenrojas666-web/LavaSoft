<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-40 flex w-64 transform flex-col bg-slate-900 text-slate-300 shadow-xl transition-transform duration-200 ease-in-out lg:translate-x-0"
    aria-label="Menú principal"
>
    <div class="flex h-full flex-col">
        <a href="{{ route('dashboard.index') }}" class="flex h-[76px] items-center gap-3 border-b border-slate-800 px-5">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-primary-600 text-sm font-extrabold tracking-wide text-white shadow-lg shadow-primary-950/30">LS</span>
            <span class="min-w-0">
                <span class="block truncate text-base font-semibold tracking-tight text-white">LavaSoft</span>
                <span class="mt-0.5 block text-[11px] uppercase tracking-[0.16em] text-slate-500">Centro de lavado</span>
            </span>
        </a>

        <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5" aria-label="Navegación lateral">
            <section aria-labelledby="nav-principal">
                <h2 id="nav-principal" class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Principal</h2>
                @can('ver dashboard')
                    <a href="{{ route('dashboard.index') }}"
                        @if(request()->routeIs('dashboard.index')) aria-current="page" @endif
                        @click="sidebarOpen = false"
                        class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'sidebar-link-active' : '' }}">
                        <span class="sidebar-icon">@include('layouts.partials.icons', ['icon' => 'home'])</span>
                        <span>Dashboard</span>
                    </a>
                @endcan
            </section>

            <section aria-labelledby="nav-gestion">
                <h2 id="nav-gestion" class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Gestión</h2>
                <div class="space-y-1">
                    @foreach ([
                        ['label' => 'Clientes', 'route' => 'clientes.index', 'icon' => 'users', 'can' => 'ver clientes'],
                        ['label' => 'Empleados', 'route' => 'empleados.index', 'icon' => 'users', 'can' => 'ver empleados'],
                        ['label' => 'Turnos', 'route' => 'turnos.index', 'icon' => 'chart', 'can' => 'ver turnos'],
                        ['label' => 'Vehículos', 'route' => 'vehiculos.index', 'icon' => 'box', 'can' => 'ver vehiculos'],
                        ['label' => 'Agenda', 'route' => 'agenda.index', 'icon' => 'calendar', 'can' => 'ver agenda'],
                        ['label' => 'Servicios por cita', 'route' => 'agendaServicio.index', 'icon' => 'clipboard', 'can' => 'ver agendaservicio'],
                        ['label' => 'Pagos', 'route' => 'pagos.index', 'icon' => 'tag', 'can' => 'ver pagos'],
                    ] as $link)
                        @can($link['can'])
                            @php($active = request()->routeIs(str_replace('.index', '.*', $link['route'])))
                            <a href="{{ route($link['route']) }}"
                                @if($active) aria-current="page" @endif
                                @click="sidebarOpen = false"
                                class="sidebar-link {{ $active ? 'sidebar-link-active' : '' }}">
                                <span class="sidebar-icon">@include('layouts.partials.icons', ['icon' => $link['icon']])</span>
                                <span>{{ $link['label'] }}</span>
                            </a>
                        @endcan
                    @endforeach
                </div>
            </section>

            <section aria-labelledby="nav-catalogo">
                <h2 id="nav-catalogo" class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Catálogo</h2>
                <div class="space-y-1">
                    @foreach ([
                        ['label' => 'Servicios', 'route' => 'servicios.index', 'icon' => 'sparkles', 'can' => 'ver servicios'],
                        ['label' => 'Tipos de vehículo', 'route' => 'tipoVehiculo.index', 'icon' => 'box', 'can' => 'ver tipovehiculo'],
                        ['label' => 'Precios de servicio', 'route' => 'precioServicio.index', 'icon' => 'tag', 'can' => 'ver precioservicio'],
                    ] as $link)
                        @can($link['can'])
                            @php($active = request()->routeIs(str_replace('.index', '.*', $link['route'])))
                            <a href="{{ route($link['route']) }}"
                                @if($active) aria-current="page" @endif
                                @click="sidebarOpen = false"
                                class="sidebar-link {{ $active ? 'sidebar-link-active' : '' }}">
                                <span class="sidebar-icon">@include('layouts.partials.icons', ['icon' => $link['icon']])</span>
                                <span>{{ $link['label'] }}</span>
                            </a>
                        @endcan
                    @endforeach
                </div>
            </section>

            <section aria-labelledby="nav-seguridad">
                <h2 id="nav-seguridad" class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Seguridad</h2>
                @can(config('seguridad.permiso_admin'))
                    <a href="{{ route('seguridad.permisos.index') }}"
                        @if(request()->routeIs('seguridad.*')) aria-current="page" @endif
                        @click="sidebarOpen = false"
                        class="sidebar-link {{ request()->routeIs('seguridad.*') ? 'sidebar-link-active' : '' }}">
                        <span class="sidebar-icon">@include('layouts.partials.icons', ['icon' => 'shield'])</span>
                        <span>Gestión de usuarios y permisos</span>
                    </a>
                @endcan
            </section>
        </nav>

        <div class="border-t border-slate-800 p-3">
            <div class="flex items-center gap-3 rounded-xl bg-slate-800/70 px-3 py-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-slate-700 text-xs font-semibold text-slate-100">
                    {{ strtoupper(substr(auth()->user()?->name ?? 'Admin', 0, 2)) }}
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-sm font-medium text-white">{{ auth()->user()?->name ?? 'Administrador' }}</span>
                    <span class="mt-0.5 block text-xs text-slate-500">Panel administrativo</span>
                </span>

            </div>
        </div>
    </div>
</aside>
