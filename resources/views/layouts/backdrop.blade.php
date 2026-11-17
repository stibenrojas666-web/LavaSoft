<div
    x-cloak
    x-show="$store.sidebar.isMobileOpen"
    x-transition.opacity
    @click="$store.sidebar.setMobileOpen(false)"
    class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm xl:hidden"
    aria-hidden="true"
></div>