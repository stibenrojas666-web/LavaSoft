@php
    $esEdicion = isset($cliente);
    $valor = fn ($campo) => old($campo, $esEdicion ? $cliente->{$campo} : '');
@endphp

<div class="cliente-banner flex items-center gap-4 px-6 py-6 sm:px-8">
    <div class="cliente-icon">
        @include('layouts.partials.icons', ['icon' => 'users'])
    </div>

    <div class="relative z-10">
        <p class="mb-1 text-xs font-bold uppercase tracking-widest text-cyan-300">
            Gestión de clientes
        </p>
        <h2 class="text-xl font-extrabold text-white sm:text-2xl">
            {{ $esEdicion ? 'Actualizar cliente' : 'Registrar cliente' }}
        </h2>
        <p class="mt-1 text-xs text-slate-200 sm:text-sm">
            {{ $esEdicion ? 'Modifica la información del cliente.' : 'Completa la información para agregarlo al sistema.' }}
        </p>
    </div>
</div>

<form
    action="{{ $esEdicion ? route('clientes.update', $cliente->id) : route('clientes.store') }}"
    method="POST"
    novalidate
    class="cliente-panel"
>
    @csrf

    @if($esEdicion)
        @method('PUT')
    @endif

    <div class="p-5 sm:p-8">
        <div class="mb-7 flex items-center gap-3 border-b border-slate-100 pb-5">
            <div class="grid h-10 w-10 place-items-center rounded-xl bg-cyan-50 text-cyan-700">
                @include('layouts.partials.icons', ['icon' => 'user'])
            </div>

            <div>
                <h3 class="text-sm font-extrabold text-slate-800">Información personal</h3>
                <p class="text-xs text-slate-400">Datos personales y medios de contacto.</p>
            </div>
        </div>

        <div class="cliente-form-grid">
            <div class="cliente-field">
                <label for="nombreCliente">Nombre</label>
                <div class="campo-contenedor">
                    <span class="campo-icono">
                        @include('layouts.partials.icons', ['icon' => 'user'])
                    </span>
                    <input
                        type="text"
                        name="nombreCliente"
                        id="nombreCliente"
                        value="{{ $valor('nombreCliente') }}"
                        placeholder="Ingresa el nombre"
                        autocomplete="given-name"
                        @error('nombreCliente') aria-invalid="true" @enderror
                    >
                </div>
                @error('nombreCliente')
                    <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="cliente-field">
                <label for="apellidoCliente">Apellido</label>
                <div class="campo-contenedor">
                    <span class="campo-icono">
                        @include('layouts.partials.icons', ['icon' => 'user'])
                    </span>
                    <input
                        type="text"
                        name="apellidoCliente"
                        id="apellidoCliente"
                        value="{{ $valor('apellidoCliente') }}"
                        placeholder="Ingresa el apellido"
                        autocomplete="family-name"
                        @error('apellidoCliente') aria-invalid="true" @enderror
                    >
                </div>
                @error('apellidoCliente')
                    <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="cliente-field">
                <label for="telefonoCliente">Teléfono</label>
                <div class="campo-contenedor">
                    <span class="campo-icono">
                        @include('layouts.partials.icons', ['icon' => 'phone'])
                    </span>
                    <input
                        type="tel"
                        name="telefonoCliente"
                        id="telefonoCliente"
                        inputmode="numeric"
                        value="{{ $valor('telefonoCliente') }}"
                        placeholder="Ingresa el número de teléfono"
                        autocomplete="tel"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        @error('telefonoCliente') aria-invalid="true" @enderror
                    >
                </div>
                @error('telefonoCliente')
                    <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="cliente-field">
                <label for="emailCliente">Correo electrónico</label>
                <div class="campo-contenedor">
                    <span class="campo-icono">
                        @include('layouts.partials.icons', ['icon' => 'mail'])
                    </span>
                    <input
                        type="email"
                        name="emailCliente"
                        id="emailCliente"
                        value="{{ $valor('emailCliente') }}"
                        placeholder="ejemplo@correo.com"
                        autocomplete="email"
                        @error('emailCliente') aria-invalid="true" @enderror
                    >
                </div>
                @error('emailCliente')
                    <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="cliente-note mt-7">
            @include('layouts.partials.icons', ['icon' => 'info'])
            <span>
                {{ $esEdicion ? 'Verifica los cambios antes de actualizar la información del cliente.' : 'Verifica que la información del cliente sea correcta antes de guardarla.' }}
            </span>
        </div>
    </div>

    <div class="cliente-actions">
        <a href="{{ route('clientes.index') }}" class="cliente-btn cliente-btn-cancelar">
            Cancelar
        </a>

        <button type="submit" class="cliente-btn cliente-btn-guardar">
            @include('layouts.partials.icons', ['icon' => 'save'])
            {{ $esEdicion ? 'Guardar cambios' : 'Registrar cliente' }}
        </button>
    </div>
</form>
