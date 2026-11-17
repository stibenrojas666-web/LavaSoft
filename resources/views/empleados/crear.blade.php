@extends('layouts.app')

@section('title', 'Registrar empleado')
@section('page-title', 'Registrar empleado')

@section('content')
<style>
    .empleado-form-page{width:100%;max-width:1050px;margin:auto}
    .empleado-banner{position:relative;overflow:hidden;isolation:isolate;background:linear-gradient(115deg,#082b3b,#0b3b50 65%,#087e99);border-radius:20px;color:white}
    .empleado-banner:before,.empleado-banner:after{position:absolute;z-index:-1;content:"";border:2px solid rgb(34 211 238 / 18%);border-radius:50%}
    .empleado-banner:before{width:230px;height:230px;right:35px;top:-125px}
    .empleado-banner:after{width:145px;height:145px;right:135px;bottom:-100px}
    .empleado-panel{overflow:hidden;border:1px solid #dce8f0;border-radius:20px;background:white;box-shadow:0 14px 38px rgb(11 42 58 / 7%)}
    .empleado-section{display:flex;align-items:center;gap:12px;margin-bottom:22px;padding-bottom:16px;border-bottom:1px solid #eaf0f4}
    .empleado-section-icon{display:grid;width:42px;height:42px;flex-shrink:0;place-items:center;border-radius:12px;background:#e4f9fc;color:#087e99}
    .empleado-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:22px 26px}
    .empleado-field label{display:block;margin-bottom:8px;color:#17394d;font-size:13px;font-weight:700}
    .empleado-field .campo-contenedor{position:relative}
    .empleado-field .campo-icono{position:absolute;top:50%;left:12px;display:grid;width:34px;height:34px;place-items:center;transform:translateY(-50%);border-radius:10px;background:#e4f9fc;color:#087e99;pointer-events:none}
    .empleado-field input,.empleado-field select{width:100%;min-height:49px;padding:11px 14px 11px 57px;border:1px solid #d5e3ed;border-radius:12px;background:#fbfdff;color:#17394d;font-size:13px;transition:.2s}
    .empleado-field input:focus,.empleado-field select:focus{border-color:#08a6c1;background:#fff;box-shadow:0 0 0 4px rgb(8 166 193 / 11%);outline:none}
    .empleado-field input::placeholder{color:#9aabb9}
    .empleado-note{display:flex;align-items:center;gap:10px;padding:13px 16px;border:1px solid #d8edf8;border-radius:12px;background:#eff9ff;color:#315d78;font-size:12px}
    .empleado-note-icon{display:grid;width:30px;height:30px;flex:0 0 30px;place-items:center;border-radius:9px;background:#dff4fa;color:#087e99}
    .empleado-actions{display:flex;justify-content:flex-end;gap:10px;padding:16px 28px;border-top:1px solid #eaf0f4;background:#fbfdff}
    .empleado-btn{display:inline-flex;min-width:115px;min-height:40px;align-items:center;justify-content:center;gap:7px;padding:0 15px;border-radius:10px;font-size:12px;font-weight:700;transition:.2s}
    .empleado-btn-cancel{border:1px solid #d7e4ed;background:#fff;color:#526b7d}
    .empleado-btn-cancel:hover{background:#f0f6f9}
    .empleado-btn-save{background:linear-gradient(110deg,#087b98,#06364c);color:#fff}
    .empleado-btn-save:hover{filter:brightness(1.1);transform:translateY(-1px)}
    @media(max-width:640px){.empleado-form-grid{grid-template-columns:1fr;gap:18px}.empleado-actions{padding:14px 17px}.empleado-btn{flex:1}}
</style>

<div class="empleado-form-page px-3 py-5 sm:px-4 sm:py-7">

    <div class="mb-5">
        <div class="mb-2 flex flex-wrap items-center gap-2 text-xs font-medium text-slate-400">
            <a href="{{ route('dashboard.index') }}" class="hover:text-cyan-700">Inicio</a>
            <span>/</span>
            <a href="{{ route('empleados.index') }}" class="hover:text-cyan-700">Empleados</a>
            <span>/</span>
            <span class="font-semibold text-cyan-700">Registrar empleado</span>
        </div>
    </div>

    <div class="empleado-banner mb-0 flex items-center gap-4 px-5 py-6 sm:px-8 sm:py-7">
        <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-cyan-100 text-cyan-900">
            @include('layouts.partials.icons', ['icon' => 'employee'])
        </div>
        <div class="min-w-0">
            <p class="mb-1 text-xs font-bold uppercase tracking-widest text-cyan-300">Equipo de trabajo</p>
            <h1 class="text-xl font-extrabold text-white sm:text-2xl">Registrar empleado</h1>
            <p class="mt-1 text-xs text-slate-200 sm:text-sm">Agrega un nuevo integrante al equipo de trabajo.</p>
        </div>
    </div>

    <form action="{{ route('empleados.store') }}" method="POST" novalidate class="empleado-panel">
        @csrf

        <div class="p-5 sm:p-8">

            <div class="empleado-section">
                <div class="empleado-section-icon">
                    @include('layouts.partials.icons', ['icon' => 'user'])
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-slate-800">Información personal</h2>
                    <p class="text-xs text-slate-400">Datos básicos y de contacto del empleado.</p>
                </div>
            </div>

            <div class="empleado-form-grid">
                <div class="empleado-field">
                    <label for="nombre">Nombre</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">@include('layouts.partials.icons', ['icon' => 'user'])</span>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" placeholder="Ingresa el nombre">
                    </div>
                    @error('nombre')<p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="empleado-field">
                    <label for="apellido">Apellido</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">@include('layouts.partials.icons', ['icon' => 'user'])</span>
                        <input type="text" name="apellido" id="apellido" value="{{ old('apellido') }}" placeholder="Ingresa el apellido">
                    </div>
                    @error('apellido')<p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="empleado-field">
                    <label for="identificacion">Identificación</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">@include('layouts.partials.icons', ['icon' => 'id-card'])</span>
                        <input type="text" name="identificacion" id="identificacion" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" value="{{ old('identificacion') }}" placeholder="Número de identificación">
                    </div>
                    @error('identificacion')<p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="empleado-field">
                    <label for="telefono">Teléfono</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">@include('layouts.partials.icons', ['icon' => 'phone'])</span>
                        <input type="text" name="telefono" id="telefono" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'')" value="{{ old('telefono') }}" placeholder="Número de teléfono">
                    </div>
                    @error('telefono')<p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="empleado-section mt-9">
                <div class="empleado-section-icon">
                    @include('layouts.partials.icons', ['icon' => 'clipboard-list'])
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-slate-800">Información complementaria</h2>
                    <p class="text-xs text-slate-400">Datos adicionales, estado y comisión.</p>
                </div>
            </div>

            <div class="empleado-form-grid">
                <div class="empleado-field">
                    <label for="rh">Tipo de sangre (RH)</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">@include('layouts.partials.icons', ['icon' => 'droplet'])</span>
                        <select name="rh" id="rh">
                            <option value="">Seleccione el tipo de sangre</option>
                            @foreach(config('empleado.tiposSangre') as $tipoSangre)
                                <option value="{{ $tipoSangre }}" @selected(old('rh') === $tipoSangre)>
                                    {{ $tipoSangre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('rh')<p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="empleado-field">
                    <label for="eps">EPS</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">@include('layouts.partials.icons', ['icon' => 'shield-check'])</span>
                        <input type="text" name="eps" id="eps" value="{{ old('eps') }}" placeholder="Nombre de la EPS">
                    </div>
                    @error('eps')<p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="empleado-field">
                    <label for="estado">Estado del empleado</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">@include('layouts.partials.icons', ['icon' => 'check-circle'])</span>
                        <select name="estado" id="estado">
                            <option value="1" @selected(old('estado', 1) == 1)>Activo</option>
                            <option value="0" @selected(old('estado') == '0')>Inactivo</option>
                        </select>
                    </div>
                    @error('estado')<p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="empleado-field">
                    <label for="porcentajeComision">Porcentaje de comisión (%)</label>
                    <div class="campo-contenedor">
                        <span class="campo-icono">@include('layouts.partials.icons', ['icon' => 'percent'])</span>
                        <input type="number" name="porcentajeComision" id="porcentajeComision" min="0" max="100" step="0.01" value="{{ old('porcentajeComision', 0) }}" placeholder="Ejemplo: 10">
                    </div>
                    @error('porcentajeComision')<p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>@enderror
                    <p class="mt-1.5 text-xs text-slate-400">Ingresa 0 si el empleado no recibe comisión.</p>
                </div>
            </div>

            <div class="empleado-note mt-7">
                <span class="empleado-note-icon">
                    @include('layouts.partials.icons', ['icon' => 'info'])
                </span>
                <span>Verifica que la información del empleado sea correcta antes de guardarla.</span>
            </div>
        </div>

        <x-form-actions :cancel-url="route('empleados.index')" save-label="Guardar empleado" class="empleado-actions" />
    </form>
</div>
@endsection
