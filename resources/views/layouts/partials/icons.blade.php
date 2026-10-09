{{-- Set de iconos SVG (Heroicons outline, inline para no depender de paquetes extra) --}}
@switch($icon)
    @case('home')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10" /></svg>
        @break
    @case('users')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m5-5.13a4 4 0 100-8 4 4 0 000 8zm6 3a4 4 0 10-8 0" /></svg>
        @break
    @case('shield')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-1a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        @break
    @case('tag')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5.586a1 1 0 01.707.293l7.414 7.414a1 1 0 010 1.414l-7.586 7.586a1 1 0 01-1.414 0L4.293 12.293A1 1 0 014 11.586V6a3 3 0 013-3z" /></svg>
        @break
    @case('box')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
        @break
    @case('chart')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
        @break
    @case('cog')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        @break
    @case('search')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" /></svg>
        @break
    @case('bell')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        @break
    @case('menu')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        @break
    @case('chevron-down')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
        @break
    @case('eye')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
        @break
    @case('pencil')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
        @break
    @case('trash')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9.5 4h5a1 1 0 011 1v2h-7V5a1 1 0 011-1z" /></svg>
        @break
    @case('user')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><circle cx="12" cy="8" r="4" stroke-width="2" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 21a8 8 0 0116 0" /></svg>
        @break
    @case('briefcase')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><rect x="3" y="7" width="18" height="14" rx="2" stroke-width="2" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2m-13 5h18m-11 0v2h4v-2" /></svg>
        @break
    @case('clock')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><circle cx="12" cy="12" r="9" stroke-width="2" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 7v5l3 2" /></svg>
        @break
    @case('car')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17h14l1-6-2-5H6l-2 5 1 6zm0 0v2m14-2v2M4 11h16M7 14h.01M17 14h.01" /></svg>
        @break
    @case('calendar')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><rect x="3" y="5" width="18" height="16" rx="2" stroke-width="2" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3v4M8 3v4M3 10h18m-13 4h.01M12 14h.01M15 14h.01M8 18h.01M12 18h.01" /></svg>
        @break
    @case('credit-card')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><rect x="2.5" y="5" width="19" height="14" rx="2" stroke-width="2" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h3" /></svg>
        @break
    @case('clipboard')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><rect x="5" y="4" width="14" height="17" rx="2" stroke-width="2" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4.5V3h6v1.5M9 10h6m-6 4h6m-6 4h3" /></svg>
        @break
    @case('grid')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="2" /><rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="2" /><rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="2" /><rect x="14" y="14" width="7" height="7" rx="1.5" stroke-width="2" /></svg>
        @break
    @case('wrench')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a5 5 0 00-6.4 6.4L3.5 17.5a2.1 2.1 0 003 3l4.8-4.8a5 5 0 006.4-6.4l-3 3-3.5-3.5 3.5-2.5z" /></svg>
        @break
    @case('currency-dollar')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v20m5-15a4 4 0 00-4-3h-2a4 4 0 000 8h2a4 4 0 010 8h-2a4 4 0 01-4-3" /></svg>
        @break
    @default
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-full h-full"><circle cx="12" cy="12" r="9" stroke-width="2" /></svg>
@endswitch
