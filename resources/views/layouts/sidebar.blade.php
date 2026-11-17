@php
    use App\Helpers\MenuHelper;

    $menuGroups = MenuHelper::getMenuGroups();
    $currentPath = request()->path();
@endphp

<aside
    id="sidebar"
    x-data="{
        openSubmenus: {},

        init() {
            this.initializeActiveMenus();
        },

        initializeActiveMenus() {
            const currentPath = @js($currentPath);

            @foreach ($menuGroups as $groupIndex => $menuGroup)
                @foreach ($menuGroup['items'] as $itemIndex => $item)
                    @if (isset($item['subItems']))
                        @foreach ($item['subItems'] as $subItem)
                            if (
                                currentPath === @js(ltrim($subItem['path'], '/')) ||
                                window.location.pathname === @js($subItem['path'])
                            ) {
                                this.openSubmenus['{{ $groupIndex }}-{{ $itemIndex }}'] = true;
                            }
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        },

        toggleSubmenu(groupIndex, itemIndex) {
            const key = groupIndex + '-' + itemIndex;
            const newState = !this.openSubmenus[key];

            if (newState) {
                this.openSubmenus = {};
            }

            this.openSubmenus[key] = newState;
        },

        isSubmenuOpen(groupIndex, itemIndex) {
            return this.openSubmenus[groupIndex + '-' + itemIndex] || false;
        },

        isActive(path) {
            return window.location.pathname === path ||
                @js($currentPath) === path.replace(/^\//, '');
        }
    }"
    :class="{
        'w-[290px]': $store.sidebar.isExpanded || $store.sidebar.isMobileOpen || $store.sidebar.isHovered,
        'w-[88px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
        'translate-x-0': $store.sidebar.isMobileOpen,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen
    }"
    @mouseenter="$store.sidebar.setHovered(true)"
    @mouseleave="$store.sidebar.setHovered(false)"
    class="fixed left-0 top-0 z-50 flex h-screen flex-col border-r border-slate-200 bg-white px-4 text-slate-800 shadow-xl transition-all duration-300 ease-in-out"
    aria-label="Menú principal"
>
    <div
        class="flex min-h-[84px] items-center border-b border-slate-100"
        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'justify-center' : 'justify-start'"
    >
        <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-cyan-700 text-white shadow-lg shadow-cyan-900/15">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c3.5 4.2 6 7.2 6 10.2A6 6 0 0 1 6 13.2C6 10.2 8.5 7.2 12 3z"/>
                </svg>
            </span>

            <span
                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                x-cloak
                class="min-w-0"
            >
                <span class="block text-lg font-extrabold tracking-tight text-slate-800">LavaSoft</span>
                <span class="block text-[10px] font-bold uppercase tracking-[0.16em] text-cyan-700">Centro de lavado</span>
            </span>
        </a>
    </div>

    <div class="flex min-h-0 flex-1 flex-col overflow-y-auto py-5">
        <nav class="space-y-5" aria-label="Navegación">
            @foreach ($menuGroups as $groupIndex => $menuGroup)
                <section>
                    <h2
                        class="mb-2 flex min-h-6 items-center text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-400"
                        :class="(!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen) ? 'justify-center' : 'justify-start'"
                    >
                        <span x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen" x-cloak>
                            {{ $menuGroup['title'] }}
                        </span>
                        <span x-show="!$store.sidebar.isExpanded && !$store.sidebar.isHovered && !$store.sidebar.isMobileOpen" class="text-base">
                            ···
                        </span>
                    </h2>

                    <ul class="space-y-1">
                        @foreach ($menuGroup['items'] as $itemIndex => $item)
                            <li>
                                @if (isset($item['subItems']))
                                    <button
                                        type="button"
                                        @click="toggleSubmenu({{ $groupIndex }}, {{ $itemIndex }})"
                                        class="flex min-h-11 w-full items-center gap-3 rounded-xl px-3 text-left text-sm font-semibold transition"
                                        :class="isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) ? 'bg-cyan-50 text-cyan-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                                    >
                                        <span class="grid h-5 w-5 shrink-0 place-items-center">
                                            {!! MenuHelper::getIconSvg($item['icon']) !!}
                                        </span>

                                        <span
                                            x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                            x-cloak
                                            class="min-w-0 flex-1 truncate"
                                        >
                                            {{ $item['name'] }}
                                        </span>

                                        <svg
                                            x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                            class="h-4 w-4 shrink-0 transition-transform"
                                            :class="isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) ? 'rotate-180' : ''"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                                        </svg>
                                    </button>

                                    <div
                                        x-cloak
                                        x-show="isSubmenuOpen({{ $groupIndex }}, {{ $itemIndex }}) && ($store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen)"
                                        x-transition
                                        class="ml-6 mt-1 space-y-1 border-l border-slate-200 pl-3"
                                    >
                                        @foreach ($item['subItems'] as $subItem)
                                            <a
                                                href="{{ $subItem['path'] }}"
                                                class="flex min-h-10 items-center rounded-lg px-3 text-xs font-semibold transition"
                                                :class="isActive(@js($subItem['path'])) ? 'bg-cyan-50 text-cyan-800' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800'"
                                            >
                                                <span class="truncate">{{ $subItem['name'] }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <a
                                        href="{{ $item['path'] }}"
                                        class="flex min-h-11 items-center gap-3 rounded-xl px-3 text-sm font-semibold transition"
                                        :class="isActive(@js($item['path'])) ? 'bg-cyan-50 text-cyan-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                                    >
                                        <span class="grid h-5 w-5 shrink-0 place-items-center">
                                            {!! MenuHelper::getIconSvg($item['icon']) !!}
                                        </span>

                                        <span
                                            x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                            x-cloak
                                            class="min-w-0 truncate"
                                        >
                                            {{ $item['name'] }}
                                        </span>

                                        @if (!empty($item['new']))
                                            <span
                                                x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
                                                class="ml-auto rounded-full bg-cyan-100 px-2 py-0.5 text-[9px] font-bold text-cyan-800"
                                            >
                                                Nuevo
                                            </span>
                                        @endif
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </nav>

        <div
            x-show="$store.sidebar.isExpanded || $store.sidebar.isHovered || $store.sidebar.isMobileOpen"
            x-cloak
            class="mt-auto pt-5"
        >
            @include('layouts.sidebar-widget')
        </div>
    </div>
</aside>

<div
    x-cloak
    x-show="$store.sidebar.isMobileOpen"
    @click="$store.sidebar.setMobileOpen(false)"
    class="fixed inset-0 z-40 bg-slate-950/50 xl:hidden"
    aria-hidden="true"
></div>