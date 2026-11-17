{{-- Iconos SVG locales de LavaSoft --}}
@switch($icon)
    @case('home')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg>
        @break

    @case('calendar')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><rect x="3" y="5" width="18" height="16" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M16 3v4M8 3v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 17h.01M12 17h.01"/></svg>
        @break

    @case('clipboard')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><rect x="5" y="4" width="14" height="17" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4V3h6v1M9 11h6M9 15h6"/></svg>
        @break

    @case('sparkles')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 3 1.7 5.3L19 10l-5.3 1.7L12 17l-1.7-5.3L5 10l5.3-1.7L12 3zM19 16l.8 2.2L22 19l-2.2.8L19 22l-.8-2.2L16 19l2.2-.8z"/></svg>
        @break

    @case('logout')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m10 17 5-5-5-5m5 5H3m9-9h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/></svg>
        @break

    @case('users')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m12-13a4 4 0 1 1-8 0 4 4 0 0 1 8 0zm2 5a4 4 0 0 1 4 4v2m-2-11a4 4 0 0 1 0-8"/></svg>
        @break

    @case('shield')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 12 2 2 4-4"/></svg>
        @break

    @case('tag')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13 11 22l-9-9V2h11l9 9a2 2 0 0 1-2 2z"/><circle cx="7" cy="7" r="1" fill="currentColor"/></svg>
        @break

    @case('box')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 3 9 4.5v9L12 21l-9-4.5v-9L12 3zM3 7.5l9 4.5 9-4.5M12 12v9M7.5 5.25l9 4.5"/></svg>
        @break

    @case('chart')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19V5m0 14h17M8 15l4-4 3 2 5-6"/></svg>
        @break

    @case('cog')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><circle cx="12" cy="12" r="3" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19.4 15 .1.1a2 2 0 1 1-2.8 2.8l-.1-.1a2 2 0 0 0-3.4 1.4v.3a2 2 0 1 1-4 0v-.2a2 2 0 0 0-3.4-1.4l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a2 2 0 0 0-1.4-3.4h-.3a2 2 0 1 1 0-4h.2a2 2 0 0 0 1.4-3.4l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a2 2 0 0 0 3.4-1.4v-.3a2 2 0 1 1 4 0v.2a2 2 0 0 0 3.4 1.4l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a2 2 0 0 0 1.4 3.4h.3a2 2 0 1 1 0 4h-.2a2 2 0 0 0-1.4 3.4z"/></svg>
        @break

    @case('search')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><circle cx="11" cy="11" r="7" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="m16 16 5 5"/></svg>
        @break

    @case('bell')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 12a2 2 0 0 0 4 0"/></svg>
        @break

    @case('menu')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        @break

    @case('chevron-down')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/></svg>
        @break

    @case('car')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17h14l1-6-2-5H6l-2 5 1 6zm0 0v2m14-2v2M4 11h16M7 14h.01M17 14h.01"/></svg>
        @break

    @case('motorcycle')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><circle cx="6" cy="17" r="3" stroke-width="2"/><circle cx="18" cy="17" r="3" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17h4l3-7h2l2 7M13 10h-3l-2-3h4m-1 10 3-7"/></svg>
        @break

    @case('credit-card')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><rect x="2.5" y="5" width="19" height="14" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M3 10h18M7 15h3"/></svg>
        @break

    @case('grid')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2"/><rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2"/><rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2"/><rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2"/></svg>
        @break

    @case('wrench')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3.5 17.5a2.1 2.1 0 0 0 3 3l4.8-4.8a5 5 0 0 0 6.4-6.4l-3 3-3.5-3.5 3.5-2.5z"/></svg>
        @break

    @case('receipt')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3h12v18l-3-2-3 2-3-2-3 2V3zM9 8h6M9 12h6M9 16h2"/></svg>
        @break

    @case('x')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-width="2" d="m6 6 12 12M18 6 6 18"/></svg>
        @break

    @case('history')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 1 0 3-6.7L3 8m0-5v5h5m4-1v5l3 2"/></svg>
        @break

    @case('currency-dollar')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m5-15a4 4 0 0 0-4-3h-2a4 4 0 0 0 0 8h2a4 4 0 0 1 0 8h-2a4 4 0 0 1-4-3"/></svg>
        @break

    @case('arrow-left')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7 7-7-7 7-7"/></svg>
        @break

    @case('check-circle')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 12 2.5 2.5L16 9"/></svg>
        @break

    @case('clipboard-list')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><rect x="5" y="4" width="14" height="17" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4V3h6v1M9 10h1m3 0h3m-7 4h1m3 0h3m-7 4h1m3 0h3"/></svg>
        @break

    @case('droplet')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c-3.5 4.5-7 8.2-7 12a7 7 0 0 0 14 0c0-3.8-3.5-7.5-7-12z"/><path stroke-linecap="round" stroke-width="2" d="M9 16a3 3 0 0 0 3 3"/></svg>
        @break

    @case('employee')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><circle cx="12" cy="7" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 21v-1a8 8 0 0 1 16 0v1M19 4h3v5h-3z"/></svg>
        @break

    @case('eye')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.5 12S6 5 12 5s9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7z"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
        @break

    @case('eye-off')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 3 18 18M10.6 10.6a2 2 0 0 0 2.8 2.8M9.9 5.2A10.8 10.8 0 0 1 12 5c6 0 9.5 7 9.5 7a17.7 17.7 0 0 1-3.1 3.8M6.2 6.2C3.8 7.8 2.5 12 2.5 12s3.5 7 9.5 7c1 0 1.9-.2 2.7-.5"/></svg>
        @break

    @case('filter')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5h16l-6.5 7.5V19l-3 1v-7.5z"/></svg>
        @break

    @case('id-card')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><rect x="3" y="5" width="18" height="14" rx="2" stroke-width="2"/><circle cx="8" cy="11" r="2" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M6 16a2 2 0 0 1 4 0m4-5h4m-4 4h4"/></svg>
        @break

    @case('info')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><circle cx="12" cy="12" r="9" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="M12 11v5m0-8h.01"/></svg>
        @break

    @case('mail')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><rect x="3" y="5" width="18" height="14" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 7 9 6 9-6"/></svg>
        @break

    @case('pencil')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 5 3 3M4 20l4.5-1 11-11a2.1 2.1 0 0 0-3-3l-11 11L4 20z"/></svg>
        @break

    @case('percent')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><circle cx="7" cy="7" r="3" stroke-width="2"/><circle cx="17" cy="17" r="3" stroke-width="2"/><path stroke-linecap="round" stroke-width="2" d="m19 5-14 14"/></svg>
        @break

    @case('phone')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h4l2 5-2.5 1.5a15 15 0 0 0 6 6L16 13l5 2v4a2 2 0 0 1-2 2A18 18 0 0 1 3 5a2 2 0 0 1 2-2z"/></svg>
        @break

    @case('plus')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-width="2" d="M12 5v14M5 12h14"/></svg>
        @break

    @case('save')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h12l4 4v14H3V3h2zM7 3v6h10V3M7 21v-8h10v8"/></svg>
        @break

    @case('arrow-right')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"/></svg>
        @break

    @case('shield-check')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8.5 12 2.3 2.3 4.7-4.7"/></svg>
        @break

    @case('trash')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"/></svg>
        @break

    @case('user')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><circle cx="12" cy="8" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 21a8 8 0 0 1 16 0"/></svg>
        @break

    @case('wallet')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h15a2 2 0 0 1 2 2v11H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h13m3 8h-5a2 2 0 0 0 0 4h5"/><circle cx="16" cy="13" r=".5" fill="currentColor"/></svg>
        @break

    @default
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="h-full w-full"><circle cx="12" cy="12" r="9" stroke-width="2"/></svg>
@endswitch
