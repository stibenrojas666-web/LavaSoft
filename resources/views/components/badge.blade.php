
@props(['status'])

@php
    $map = [
        'activo' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'activa' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'confirmada' => 'bg-cyan-50 text-cyan-800 ring-cyan-600/20',
        'confirmado' => 'bg-cyan-50 text-cyan-800 ring-cyan-600/20',
        'terminada' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'completado' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'pendiente' => 'bg-amber-50 text-amber-800 ring-amber-600/20',
        'cancelada' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        'cancelado' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        'inactivo' => 'bg-slate-100 text-slate-600 ring-slate-500/20',
        'pagado' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'anulado' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        'en proceso' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
        'en_proceso' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
    ];

    $key = mb_strtolower(trim((string) $status));

    $classes = $map[$key] ?? 'bg-slate-100 text-slate-700 ring-slate-500/20';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-2 whitespace-nowrap rounded-full px-3 py-1.5 text-xs font-bold capitalize ring-1 ring-inset $classes"]) }}>
    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-current opacity-70"></span>
    {{ $status }}
</span>
