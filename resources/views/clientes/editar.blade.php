
@extends('layouts.app')

@section('title', 'Editar Cliente')

@push('styles')
<style>
    .cliente-page {
        max-width: 1120px;
        margin: 0 auto;
    }

    .cliente-banner {
        position: relative;
        overflow: hidden;
        isolation: isolate;
        background: linear-gradient(115deg, #082b3b 0%, #0b3b50 65%, #087e99 100%);
        border-radius: 22px 22px 0 0;
        color: white;
    }

    .cliente-banner::before,
    .cliente-banner::after {
        content: "";
        position: absolute;
        z-index: -1;
        border: 2px solid rgb(34 211 238 / 18%);
        border-radius: 50%;
    }

    .cliente-banner::before {
        width: 230px;
        height: 230px;
        right: 35px;
        top: -125px;
    }

    .cliente-banner::after {
        width: 145px;
        height: 145px;
        right: 135px;
        bottom: -100px;
    }

    .cliente-icon {
        display: grid;
        width: 58px;
        height: 58px;
        flex-shrink: 0;
        place-items: center;
        border-radius: 17px;
        background: #cffafe;
        color: #07536c;
    }

    .cliente-panel {
        overflow: hidden;
        border: 1px solid #dce8f0;
        border-radius: 0 0 22px 22px;
        background: white;
        box-shadow: 0 14px 38px rgb(11 42 58 / 8%);
    }

    .cliente-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 23px 26px;
    }

    .cliente-field label {
        display: block;
        margin-bottom: 8px;
        color: #17394d;
        font-size: 13px;
        font-weight: 700;
    }

    .cliente-field .campo-contenedor {
        position: relative;
    }

    .cliente-field .campo-icono {
        position: absolute;
        top: 50%;
        left: 12px;
        display: grid;
        width: 34px;
        height: 34px;
        place-items: center;
        transform: translateY(-50%);
        border-radius: 10px;
        background: #e4f9fc;
        color: #087e99;
        pointer-events: none;
    }

    .cliente-field input {
        width: 100%;
        min-height: 49px;
        padding: 11px 14px 11px 57px;
        border: 1px solid #d5e3ed;
        border-radius: 12px;
        background: #fbfdff;
        color: #17394d;
        font-size: 13px;
        transition: .2s ease;
    }

    .cliente-field input:hover {
        border-color: #a5dbe5;
    }

    .cliente-field input:focus {
        border-color: #08a6c1;
        background: white;
        box-shadow: 0 0 0 4px rgb(8 166 193 / 11%);
        outline: none;
    }

    .cliente-field input::placeholder {
        color: #9aabb9;
    }

    .cliente-note {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 16px;
        border: 1px solid #d8edf8;
        border-radius: 12px;
        background: #eff9ff;
        color: #315d78;
        font-size: 12px;
    }

    .cliente-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 19px 28px;
        border-top: 1px solid #eaf0f4;
        background: #fbfdff;
    }

    .cliente-btn {
        display: inline-flex;
        min-height: 44px;
        align-items: center;
        justify-content: center;
        gap: 9px;
        padding: 10px 21px;
        border-radius: 11px;
        font-size: 13px;
        font-weight: 700;
        transition: .2s ease;
    }

    .cliente-btn-cancelar {
        border: 1px solid #d7e4ed;
        background: white;
        color: #526b7d;
    }

    .cliente-btn-cancelar:hover {
        background: #f0f6f9;
        transform: translateY(-1px);
    }

    .cliente-btn-guardar {
        background: linear-gradient(110deg, #087b98, #06364c);
        color: white;
        box-shadow: 0 5px 13px rgb(8 87 112 / 17%);
    }

    .cliente-btn-guardar:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
        box-shadow: 0 7px 17px rgb(8 87 112 / 23%);
    }

    @media (max-width: 640px) {
        .cliente-form-grid {
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .cliente-actions {
            flex-direction: column-reverse;
            padding: 17px;
        }

        .cliente-btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="cliente-page">

    {{-- Navegación --}}
    <div class="mb-5">
        <div class="mb-2 flex flex-wrap items-center gap-2 text-xs font-medium text-slate-400">
            <a href="{{ route('dashboard.index') }}" class="hover:text-cyan-700">
                Inicio
            </a>
            <span>/</span>
            <a href="{{ route('clientes.index') }}" class="hover:text-cyan-700">
                Clientes
            </a>
            <span>/</span>
            <span class="font-semibold text-cyan-700">Editar cliente</span>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-800">
                    Editar cliente
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Actualiza la información del cliente registrado.
                </p>
            </div>

            <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Sistema activo
            </span>
        </div>
    </div>

    {{-- Formulario --}}
    <div>
        <div class="cliente-banner flex items-center gap-4 px-6 py-6 sm:px-8">
            <div class="cliente-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>

            <div class="relative z-10">
                <p class="mb-1 text-xs font-bold uppercase tracking-widest text-cyan-300">
                    Gestión de clientes
                </p>
                <h2 class="text-xl font-extrabold text-white sm:text-2xl">
                    Actualizar cliente
                </h2>
                <p class="mt-1 text-xs text-slate-200 sm:text-sm">
                    Modifica los datos personales y de contacto.
                </p>
            </div>
        </div>

        <form action="{{ route('clientes.update', $cliente) }}"
              method="POST" novalidate class="cliente-panel">
            @csrf
            @method('PUT')

            <div class="p-5 sm:p-8">

                {{-- Información personal --}}
                <div class="mb-7 flex items-center gap-3 border-b border-slate-100 pb-5">
                    <div class="grid h-10 w-10 place-items-center rounded-xl bg-cyan-50 text-cyan-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"/>
                            <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">
                            Información personal
                        </h3>
                        <p class="text-xs text-slate-400">
                            Datos personales y medios de contacto.
                        </p>
                    </div>
                </div>

                <div class="cliente-form-grid">

                    {{-- Nombre --}}
                    <div class="cliente-field">
                        <label for="nombreCliente">Nombre</label>
                        <div class="campo-contenedor">
                            <span class="campo-icono">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                                </svg>
                            </span>
                            <input type="text" name="nombreCliente" id="nombreCliente"
                                   value="{{ old('nombreCliente', $cliente->nombreCliente) }}"
                                   placeholder="Ingresa el nombre"
                                   autocomplete="given-name"
                                   @error('nombreCliente') aria-invalid="true" @enderror>
                        </div>
                        @error('nombreCliente')
                            <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Apellido --}}
                    <div class="cliente-field">
                        <label for="apellidoCliente">Apellido</label>
                        <div class="campo-contenedor">
                            <span class="campo-icono">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="4"/>
                                    <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                                </svg>
                            </span>
                            <input type="text" name="apellidoCliente" id="apellidoCliente"
                                   value="{{ old('apellidoCliente', $cliente->apellidoCliente) }}"
                                   placeholder="Ingresa el apellido"
                                   autocomplete="family-name"
                                   @error('apellidoCliente') aria-invalid="true" @enderror>
                        </div>
                        @error('apellidoCliente')
                            <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="cliente-field">
                        <label for="telefonoCliente">Teléfono</label>
                        <div class="campo-contenedor">
                            <span class="campo-icono">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                                    19.79 19.79 0 0 1-8.63-3.07
                                    19.5 19.5 0 0 1-6-6
                                    19.79 19.79 0 0 1-3.07-8.67
                                    A2 2 0 0 1 4.11 2h3
                                    a2 2 0 0 1 2 1.72
                                    c.12.96.36 1.9.72 2.8
                                    a2 2 0 0 1-.45 2.11L8.11 9.9
                                    a16 16 0 0 0 6 6l1.27-1.27
                                    a2 2 0 0 1 2.11-.45
                                    c.9.36 1.84.6 2.8.72
                                    A2 2 0 0 1 22 16.92z"/>
                                </svg>
                            </span>
                            <input type="tel" name="telefonoCliente" id="telefonoCliente"
                                   inputmode="numeric"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   value="{{ old('telefonoCliente', $cliente->telefonoCliente) }}"
                                   placeholder="Ingresa el número de teléfono"
                                   autocomplete="tel"
                                   @error('telefonoCliente') aria-invalid="true" @enderror>
                        </div>
                        @error('telefonoCliente')
                            <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Correo --}}
                    <div class="cliente-field">
                        <label for="emailCliente">Correo electrónico</label>
                        <div class="campo-contenedor">
                            <span class="campo-icono">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                                    <path d="m3 7 9 6 9-6"/>
                                </svg>
                            </span>
                            <input type="email" name="emailCliente" id="emailCliente"
                                   value="{{ old('emailCliente', $cliente->emailCliente) }}"
                                   placeholder="ejemplo@correo.com"
                                   autocomplete="email"
                                   @error('emailCliente') aria-invalid="true" @enderror>
                        </div>
                        @error('emailCliente')
                            <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div class="cliente-note mt-7">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4M12 8h.01"/>
                    </svg>
                    <span>
                        Verifica los cambios antes de actualizar la información del cliente.
                    </span>
                </div>

            </div>

            {{-- Acciones --}}
            <div class="cliente-actions">

                <a href="{{ route('clientes.index') }}"
                   class="cliente-btn cliente-btn-cancelar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 3-6.7L3 8"/>
                        <path d="M3 3v5h5"/>
                    </svg>
                    Cancelar
                </a>

                <button type="submit" class="cliente-btn cliente-btn-guardar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                        <path d="M17 21v-8H7v8M7 3v5h8"/>
                    </svg>
                    Guardar cambios
                    <span aria-hidden="true">→</span>
                </button>

            </div>
        </form>
    </div>
</div>
@endsection
