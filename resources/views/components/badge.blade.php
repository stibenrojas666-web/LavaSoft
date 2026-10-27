@props(['status'])

@php
    $map = [
        'activo'     => ['bg-emerald-50 text-emerald-800 ring-emerald-600/20', 'bg-emerald-500'],
        'confirmada' => ['bg-sky-50 text-sky-800 ring-sky-600/20', 'bg-sky-500'],
        'terminada'  => ['bg-emerald-50 text-emerald-800 ring-emerald-600/20', 'bg-emerald-500'],
        'pendiente'  => ['bg-amber-50 text-amber-800 ring-amber-600/25', 'bg-amber-500'],
        'cancelada'  => ['bg-rose-50 text-rose-800 ring-rose-600/20', 'bg-rose-500'],
        'inactivo'   => ['bg-slate-100 text-slate-700 ring-slate-500/20', 'bg-slate-400'],
    ];
    [$classes, $dot] = $map[strtolower($status)] ?? ['bg-slate-100 text-slate-700 ring-slate-500/20', 'bg-slate-400'];
@endphp

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold capitalize ring-1 ring-inset {{ $classes }}">
    <span class="h-1.5 w-1.5 rounded-full {{ $dot }}" aria-hidden="true"></span>
    {{ $status }}
</span>