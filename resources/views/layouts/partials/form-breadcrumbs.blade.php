@php
    $rutaActual = request()->route()?->getName();

    $rutasDeFormulario = [
        'agenda.create' => ['Agenda', 'agenda.index', 'Agendar cita'],
        'agenda.edit' => ['Agenda', 'agenda.index', 'Editar cita'],
        'agenda.factura' => ['Agenda', 'agenda.index', 'Factura de servicio'],
        'agendaServicio.create' => ['Historial de citas', 'agendaServicio.index', 'Agregar servicio a cita'],
        'agendaServicio.edit' => ['Historial de citas', 'agendaServicio.index', 'Editar servicio de cita'],
        'agendaServicio.show' => ['Historial de citas', 'agendaServicio.index', 'Detalle de la cita'],
        'servicios.create' => ['Servicios', 'servicios.index', 'Crear servicio'],
        'servicios.edit' => ['Servicios', 'servicios.index', 'Editar servicio'],
        'vehiculos.create' => ['Vehículos', 'vehiculos.index', 'Crear vehículo'],
        'vehiculos.edit' => ['Vehículos', 'vehiculos.index', 'Editar vehículo'],
        'vehiculos.show' => ['Vehículos', 'vehiculos.index', 'Historial del vehículo'],
        'tipoVehiculo.create' => ['Tipos de vehículos', 'tipoVehiculo.index', 'Crear tipo de vehículo'],
        'tipoVehiculo.edit' => ['Tipos de vehículos', 'tipoVehiculo.index', 'Editar tipo de vehículo'],
        'precioServicio.create' => ['Precios de servicio', 'precioServicio.index', 'Crear precio'],
        'precioServicio.edit' => ['Precios de servicio', 'precioServicio.index', 'Editar precio'],
        'turnos.create' => ['Turnos', 'turnos.index', 'Crear turno'],
        'turnos.edit' => ['Turnos', 'turnos.index', 'Editar turno'],
        'pagos.create' => ['Historial de pagos', 'pagos.historial', 'Registrar pago'],
        'pagos.edit' => ['Historial de pagos', 'pagos.historial', 'Editar pago'],
        'seguridad.roles.create' => ['Roles', 'seguridad.roles.index', 'Crear rol'],
        'seguridad.roles.edit' => ['Roles', 'seguridad.roles.index', 'Editar rol'],
        'seguridad.roles.index' => ['Usuarios y permisos', null, 'Roles'],
        'seguridad.permisos.index' => ['Usuarios y permisos', null, 'Permisos'],
        'seguridad.cuentas.index' => ['Usuarios y permisos', null, 'Cuentas de usuario'],
        'seguridad.usuarios.index' => ['Usuarios y permisos', null, 'Usuarios y roles'],
        'seguridad.cuentas.create' => ['Cuentas de usuario', 'seguridad.cuentas.index', 'Crear usuario'],
        'seguridad.cuentas.edit' => ['Cuentas de usuario', 'seguridad.cuentas.index', 'Editar usuario'],
        'configuracion.edit' => ['Configuración del lavadero', null, 'Editar configuración'],
    ];

    $migasFormulario = $rutasDeFormulario[$rutaActual] ?? null;
    $urlPadre = $migasFormulario && $migasFormulario[1]
        ? route($migasFormulario[1])
        : null;

    if ($rutaActual === 'agenda.factura') {
        $parametrosAgenda = request()->query();
        $parametrosAgenda['vista'] = $parametrosAgenda['vista'] ?? 'calendario';
        $urlPadre = route('agenda.index', $parametrosAgenda);
    }
@endphp

@if ($migasFormulario)
    <nav class="mb-4 flex flex-wrap items-center gap-2 text-sm font-medium text-slate-400" aria-label="Ruta de navegación">
        <a href="{{ route('dashboard.index') }}" class="transition hover:text-cyan-700">Inicio</a>
        <span aria-hidden="true">/</span>

        @if ($urlPadre)
            <a href="{{ $urlPadre }}" class="transition hover:text-cyan-700">
                {{ $migasFormulario[0] }}
            </a>
        @else
            <span>{{ $migasFormulario[0] }}</span>
        @endif

        <span aria-hidden="true">/</span>
        <span class="font-semibold text-cyan-800" aria-current="page">{{ $migasFormulario[2] }}</span>
    </nav>
@endif
