
@extends('layouts.app')

@section('title', 'Registrar empleado')
@push('styles')
   
    
    <style>
        .empleado-field select,
        .empleado-field input {
            width: 100%;
            min-height: 49px;
            padding: 11px 14px 11px 57px;
            border: 1px solid #d5e3ed;
            border-radius: 12px;
            background-color: #fbfdff;
            color: #17394d;
            font-size: 13px;
            transition: .2s ease;
        }

        .empleado-field select {
            cursor: pointer;
        }

        .empleado-field input:focus,
        .empleado-field select:focus {
            border-color: #08a6c1;
            background: white;
            box-shadow: 0 0 0 4px rgb(8 166 193 / 11%);
            outline: none;
        }

        .empleado-section {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #eaf0f4;
            padding-bottom: 18px;
        }

        .empleado-section-icon {
            display: grid;
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            place-items: center;
            border-radius: 12px;
            background: #e4f9fc;
            color: #087e99;
        }

        .empleado-field label {
            display: block;
            margin-bottom: 8px;
            color: #17394d;
            font-size: 13px;
            font-weight: 700;
        }

        @media(max-width: 640px) {
            .empleado-form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
@section('content')
<div class="cliente-page px-3 py-5 sm:px-4 sm:py-7">

    {{-- Encabezado --}}
    <div class="cliente-banner flex items-center gap-4 px-5 py-6 sm:px-8 sm:py-7">
        <div class="cliente-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="8" r="4"/>
                <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                <path d="M19 8h4M21 6v4"/>
            </svg>
        </div>

        <div class="min-w-0">
            <h1 class="text-xl font-extrabold text-white sm:text-2xl">
                Registrar empleado
            </h1>
            <p class="mt-1 text-xs text-slate-200 sm:text-sm">
                Agrega un nuevo integrante al equipo de trabajo.
            </p>
        </div>
    </div>

    {{-- Formulario --}}
    <form action="{{ route('empleados.store') }}"
          method="POST"
          novalidate
          class="cliente-panel">

        @csrf

        <div class="p-5 sm:p-8">

            {{-- Información personal --}}
            <div class="empleado-section">
                <div class="empleado-section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-slate-800">
                        Información personal
                    </h2>
                    <p class="text-xs text-slate-400">
                        Datos básicos y de contacto del empleado.
                    </p>
                </div>
            </div>

            <div class="cliente-form-grid empleado-form-grid">

                {{-- Nombre --}}
                <div class="empleado-field">
                    <label for="nombre">Nombre</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                            </svg>
                        </span>
                        <input type="text" name="nombre" id="nombre"
                               value="{{ old('nombre') }}"
                               placeholder="Ingresa el nombre">
                    </div>
                    @error('nombre')
                        <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Apellido --}}
                <div class="empleado-field">
                    <label for="apellido">Apellido</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4"/>
                                <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                            </svg>
                        </span>
                        <input type="text" name="apellido" id="apellido"
                               value="{{ old('apellido') }}"
                               placeholder="Ingresa el apellido">
                    </div>
                    @error('apellido')
                        <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Identificación --}}
                <div class="empleado-field">
                    <label for="identificacion">Identificación</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <circle cx="9" cy="11" r="2"/>
                                <path d="M6 16c.7-2 5.3-2 6 0M15 10h3M15 14h3"/>
                            </svg>
                        </span>
                        <input type="text" name="identificacion" id="identificacion"
                               inputmode="numeric"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               value="{{ old('identificacion') }}"
                               placeholder="Número de identificación">
                    </div>
                    @error('identificacion')
                        <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Teléfono --}}
                <div class="empleado-field">
                    <label for="telefono">Teléfono</label>
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
                        <input type="text" name="telefono" id="telefono"
                               inputmode="numeric"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               value="{{ old('telefono') }}"
                               placeholder="Número de teléfono">
                    </div>
                    @error('telefono')
                        <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Información complementaria --}}
            <div class="empleado-section mt-9">
                <div class="empleado-section-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                        <path d="M8 8h8M8 12h8M8 16h4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-slate-800">
                        Información complementaria
                    </h2>
                    <p class="text-xs text-slate-400">
                        Datos adicionales, estado y comisión.
                    </p>
                </div>
            </div>

            <div class="cliente-form-grid empleado-form-grid">

                {{-- RH --}}
                <div class="empleado-field">
                    <label for="rh">Tipo de sangre (RH)</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4.5 8-12a8 8 0 1 0-16 0c0 7.5 8 12 8 12Z"/>
                                <path d="M12 7v6M9 10h6"/>
                            </svg>
                        </span>
                        <select name="rh" id="rh">
                            <option value="">Seleccione el tipo de sangre</option>
                            @foreach(config('empleado.tiposSangre') as $tipoSangre)
                                <option value="{{ $tipoSangre }}"
                                    @selected(old('rh') === $tipoSangre)>
                                    {{ $tipoSangre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('rh')
                        <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- EPS --}}
                <div class="empleado-field">
                    <label for="eps">EPS</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                                <path d="M12 8v8M8 12h8"/>
                            </svg>
                        </span>
                        <input type="text" name="eps" id="eps"
                               value="{{ old('eps') }}"
                               placeholder="Nombre de la EPS">
                    </div>
                    @error('eps')
                        <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Estado --}}
                <div class="empleado-field">
                    <label for="estado">Estado del empleado</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="9"/>
                                <path d="m8 12 3 3 5-6"/>
                            </svg>
                        </span>
                        <select name="estado" id="estado">
                            <option value="1" @selected(old('estado', 1) == 1)>Activo</option>
                            <option value="0" @selected(old('estado') == '0')>Inactivo</option>
                        </select>
                    </div>
                    @error('estado')
                        <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Comisión --}}
                <div class="empleado-field">
                    <label for="porcentajeComision">Porcentaje de comisión (%)</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 5 5 19"/>
                                <circle cx="6.5" cy="6.5" r="2.5"/>
                                <circle cx="17.5" cy="17.5" r="2.5"/>
                            </svg>
                        </span>
                        <input type="number" name="porcentajeComision"
                               id="porcentajeComision" min="0" max="100"
                               step="0.01"
                               value="{{ old('porcentajeComision', 0) }}"
                               placeholder="Ejemplo: 10">
                    </div>
                    @error('porcentajeComision')
                        <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-slate-400">
                        Ingresa 0 si el empleado no recibe comisión.
                    </p>
                </div>

            </div>

            {{-- Nota --}}
            <div class="cliente-note mt-7">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4M12 8h.01"/>
                </svg>
                <span>
                    Verifica que la información del empleado sea correcta antes de guardarla.
                </span>
            </div>

        </div>

        {{-- Botones --}}
        <div class="cliente-actions">
            <a href="{{ route('empleados.index') }}"
               class="cliente-btn cliente-btn-cancelar">
                Cancelar
            </a>

            <button type="submit" class="cliente-btn cliente-btn-guardar">
                Guardar empleado
                <span aria-hidden="true">→</span>
            </button>
        </div>

    </form>
</div>
@endsection
