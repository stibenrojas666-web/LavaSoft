<a
    href="{{ route('agenda.factura', ['agendaId' => $citaCalendario->id, 'vista' => 'calendario', 'mes' => $primerDia->format('Y-m'), 'fecha' => \Carbon\Carbon::parse($citaCalendario->fecha)->toDateString()]) }}"
    title="{{ substr($citaCalendario->hora, 0, 5) }} · {{ $citaCalendario->cliente->nombreCliente ?? 'Cliente' }}"
    class="block truncate rounded-md px-1.5 py-1 text-[9px] font-semibold leading-tight shadow-sm transition hover:shadow-md sm:px-2 sm:text-[10px]
        {{ $citaCalendario->estado === 'confirmada' ? 'bg-emerald-50 text-emerald-800' : ($citaCalendario->estado === 'cancelada' ? 'bg-rose-50 text-rose-700' : ($citaCalendario->estado === 'terminada' ? 'bg-sky-50 text-sky-800' : 'bg-amber-50 text-amber-800')) }}"
>
    <span class="font-extrabold">{{ substr($citaCalendario->hora, 0, 5) }}</span>
    {{ $citaCalendario->cliente->nombreCliente ?? 'Cliente' }}
</a>
