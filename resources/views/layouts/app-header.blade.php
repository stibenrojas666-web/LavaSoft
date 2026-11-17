<header
    x-data="{ menuAbierto: false }"
    class="sticky top-0 z-30 w-full border-b border-slate-200 bg-white/95 backdrop-blur-xl"
>
    <div class="flex min-h-[76px] items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <button
                type="button"
                @click="$store.sidebar.toggleMobileOpen()"
                class="grid h-11 w-11 shrink-0 place-items-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-cyan-300 hover:bg-cyan-50 hover:text-cyan-800 xl:hidden"
                aria-label="Abrir menú"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <button
                type="button"
                @click="$store.sidebar.toggleExpanded()"
                class="hidden h-11 w-11 shrink-0 place-items-center rounded-xl border border-slate-200 text-slate-600 transition hover:border-cyan-300 hover:bg-cyan-50 hover:text-cyan-800 xl:grid"
                aria-label="Contraer o expandir menú"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16"/>
                </svg>
            </button>

            <div class="min-w-0">
                <p class="truncate text-xs font-semibold uppercase tracking-[0.16em] text-cyan-700">
                    LavaSoft
                </p>
                <h1 class="truncate text-lg font-extrabold text-slate-800 sm:text-xl">
                    @yield('page-title', 'Panel administrativo')
                </h1>
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-2 sm:gap-3">
            <div class="hidden items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 sm:flex">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Sistema activo
            </div>

            @auth
                <div class="relative" @click.away="menuAbierto = false">
                    <button
                        type="button"
                        @click="menuAbierto = !menuAbierto"
                        :aria-expanded="menuAbierto.toString()"
                        aria-haspopup="true"
                        class="flex min-h-[44px] items-center gap-2 rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 transition hover:border-cyan-300 hover:bg-cyan-50"
                    >
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-cyan-700 text-xs font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name ?? 'US', 0, 2)) }}
                        </span>

                        <span class="hidden max-w-[150px] text-left sm:block">
                            <span class="block truncate text-sm font-bold text-slate-800">
                                {{ auth()->user()->name }}
                            </span>
                            <span class="block text-[11px] text-slate-500">Cuenta activa</span>
                        </span>

                        <svg class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>

                    <div
                        x-cloak
                        x-show="menuAbierto"
                        x-transition
                        class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-xl"
                    >
                        <div class="border-b border-slate-100 px-3 py-2">
                            <p class="truncate text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="mt-2">
                            @csrf
                            <button
                                type="submit"
                                class="flex min-h-11 w-full items-center gap-2 rounded-xl px-3 text-left text-sm font-semibold text-rose-600 transition hover:bg-rose-50"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>
                                </svg>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>