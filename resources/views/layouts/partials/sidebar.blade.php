{{--
    Sidebar principal.
    Usa la variable de ruta actual (Route::currentRouteName() o request()->routeIs())
    para resaltar el enlace activo.
--}}
<aside
    x-cloak
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-300 transform transition-transform duration-200 ease-in-out lg:translate-x-0"
    aria-label="Menú principal"
>
    <div class="h-full flex flex-col">

        {{-- Logo --}}
        <div class="flex items-center gap-3 h-16 px-6 border-b border-slate-800">
            <div class="w-9 h-9 rounded-lg bg-primary-600 flex items-center justify-center text-white font-bold">
                A
            </div>
            <span class="text-white font-semibold text-lg tracking-tight">{{ config('app.name', 'AdminPanel') }}</span>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 overflow-y-auto px-3 py-6 space-y-1" aria-label="Navegación lateral">

            @php
                $serviciosActivo = request()->routeIs('servicios.*', 'tipoVehiculo.*', 'precioServicio.*');
                $links = [
                    ['label' => 'Dashboard',       'route' => 'dashboard.index',      'icon' => 'home'],
                    ['label' => 'Usuarios',        'route' => 'users.index',          'icon' => 'users'],
                    ['label' => 'Perfiles/Roles',  'route' => 'roles.index',          'icon' => 'shield'],
                    ['label' => 'Clientes',        'route' => 'clientes.index',       'icon' => 'user'],
                    ['label' => 'Empleados',       'route' => 'empleados.index',      'icon' => 'briefcase'],
                    ['label' => 'Turnos',          'route' => 'turnos.index',         'icon' => 'clock'],
                    ['label' => 'Vehículos',       'route' => 'vehiculos.index',      'icon' => 'car'],
                    ['label' => 'Agenda',          'route' => 'agenda.index',         'icon' => 'calendar'],
                    ['label' => 'Pagos',           'route' => 'pagos.index',          'icon' => 'credit-card'],
                    ['label' => 'Órdenes',         'route' => 'agendaServicio.index', 'icon' => 'clipboard'],
                    ['label' => 'Configuración',   'route' => 'settings.index',       'icon' => 'cog'],
                ];
            @endphp

            @foreach ($links as $link)
                @php
                    $active = Route::has($link['route']) && request()->routeIs(explode('.', $link['route'])[0] . '.*');
                @endphp
                <a
                    href="{{ Route::has($link['route']) ? route($link['route']) : '#' }}"
                    @if($active) aria-current="page" @endif
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                        {{ $active
                            ? 'bg-primary-600 text-white shadow-sm'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                >
                    <span class="w-5 h-5 flex-shrink-0 opacity-80 transition-opacity group-hover:opacity-100" aria-hidden="true">
                        @include('layouts.partials.icons', ['icon' => $link['icon']])
                    </span>
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
            {{-- Menú de Servicios --}}
            <div
                x-data="{ open: {{ $serviciosActivo ? 'true' : 'false' }} }"
                class="space-y-1">
                <button
                    type="button"
                    @click="open = !open"
                    :aria-expanded="open.toString()"
                    class="w-full group flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                        {{ $serviciosActivo
                            ? 'bg-primary-600 text-white shadow-sm'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <span class="flex items-center gap-3">
                        <span class="w-5 h-5 flex-shrink-0 opacity-80 transition-opacity group-hover:opacity-100" aria-hidden="true">
                            @include('layouts.partials.icons', ['icon' => 'grid'])
                        </span>

                        <span>Más opciones</span>
                    </span>

                </button>

                <div
                    x-show="open"
                    x-cloak
                    x-transition
                    class="ml-5 pl-3 border-l border-slate-700 space-y-1">
                    <a
                        href="{{ route('servicios.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                            {{ request()->routeIs('servicios.*')
                                ? 'bg-slate-800 text-white'
                                : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <span class="h-4 w-4 shrink-0 opacity-75" aria-hidden="true">@include('layouts.partials.icons', ['icon' => 'wrench'])</span>
                        <span>Servicios</span>
                    </a>

                    <a
                        href="{{ route('tipoVehiculo.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                            {{ request()->routeIs('tipoVehiculo.*')
                                ? 'bg-slate-800 text-white'
                                : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <span class="h-4 w-4 shrink-0 opacity-75" aria-hidden="true">@include('layouts.partials.icons', ['icon' => 'car'])</span>
                        <span>Tipo de Vehículo</span>
                    </a>

                    <a
                        href="{{ route('precioServicio.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                            {{ request()->routeIs('precioServicio.*')
                                ? 'bg-slate-800 text-white'
                                : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <span class="h-4 w-4 shrink-0 opacity-75" aria-hidden="true">@include('layouts.partials.icons', ['icon' => 'currency-dollar'])</span>
                        <span>Precio de Servicio</span>
                    </a>
                </div>
            </div>

        </nav>

        {{-- Cerrar sesión --}}
        <div class="px-3 py-4 border-t border-slate-800">
            <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500"
                    aria-label="Cerrar sesión"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</aside>
