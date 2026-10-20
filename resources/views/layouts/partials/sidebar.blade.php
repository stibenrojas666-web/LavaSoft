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
                <a href="{{ route('dashboard.index') }}"
                   @if(request()->routeIs('dashboard.index')) aria-current="page" @endif
                   @click="sidebarOpen = false"
                   class="sidebar-link {{ request()->routeIs('dashboard.index') ? 'sidebar-link-active' : '' }}">
                    <span class="sidebar-icon">@include('layouts.partials.icons', ['icon' => 'home'])</span>
                    <span>Dashboard</span>
                </a>
            </section>

            <section aria-labelledby="nav-gestion">
                <h2 id="nav-gestion" class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Gestión</h2>
                <div class="space-y-1">
                    @foreach ([
                        ['Clientes', 'clientes.index', 'users'],
                        ['Empleados', 'empleados.index', 'users'],
                        ['Turnos', 'turnos.index', 'chart'],
                        ['Vehículos', 'vehiculos.index', 'box'],
                        ['Agenda', 'agenda.index', 'calendar'],
                        ['Servicios por cita', 'agendaServicio.index', 'clipboard'],
                        ['Pagos', 'pagos.index', 'tag'],
                    ] as [$label, $routeName, $icon])
                        @php($active = request()->routeIs(str_replace('.index', '.*', $routeName)))
                        <a href="{{ route($routeName) }}"
                           @if($active) aria-current="page" @endif
                           @click="sidebarOpen = false"
                           class="sidebar-link {{ $active ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-icon">@include('layouts.partials.icons', ['icon' => $icon])</span>
                            <span>{{ $label }}</span>
                        </a>
                    @endforeach
                </div>
            </section>

            <section aria-labelledby="nav-catalogo">
                <h2 id="nav-catalogo" class="px-3 pb-2 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Catálogo</h2>
                <div class="space-y-1">
                    @foreach ([
                        ['Servicios', 'servicios.index', 'sparkles'],
                        ['Tipos de vehículo', 'tipoVehiculo.index', 'box'],
                        ['Precios de servicio', 'precioServicio.index', 'tag'],
                    ] as [$label, $routeName, $icon])
                        @php($active = request()->routeIs(str_replace('.index', '.*', $routeName)))
                        <a href="{{ route($routeName) }}"
                           @if($active) aria-current="page" @endif
                           @click="sidebarOpen = false"
                           class="sidebar-link {{ $active ? 'sidebar-link-active' : '' }}">
                            <span class="sidebar-icon">@include('layouts.partials.icons', ['icon' => $icon])</span>
                            <span>{{ $label }}</span>
                        </a>
                    @endforeach
                </div>
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
                @if (Route::has('logout'))
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg p-2 text-slate-400 transition hover:bg-slate-700 hover:text-white" aria-label="Cerrar sesión" title="Cerrar sesión">
                            <span class="block h-4 w-4">@include('layouts.partials.icons', ['icon' => 'logout'])</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</aside>
