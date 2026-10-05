@extends('layouts.app')

@section('title')
    Gestión de Pagos
@endsection

@section('content')

<x-card>

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-cyan-700">
            💰 Lista de Pagos
        </h2>

        <a href="{{ route('pagos.create') }}"
            class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Pago
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-cyan-100 border-b border-cyan-200 text-cyan-800 text-sm uppercase tracking-wider">
                    <th class="py-3 px-4">ID</th>
                    <th class="py-3 px-4">Cita</th>
                    <th class="py-3 px-4">Monto</th>
                    <th class="py-3 px-4">Método</th>
                    <th class="py-3 px-4">Fecha de pago</th>
                    <th class="py-3 px-4">Factura</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">

                @forelse ($pago as $p)

                <tr class="hover:bg-cyan-50">

                    <td class="px-4 py-3">{{ $p->id }}</td>

                    <td class="px-4 py-3">
                        #{{ $p->agenda->id ?? 'N/A' }}
                        @if($p->agenda)
                            - {{ \Carbon\Carbon::parse($p->agenda->fecha)->format('d/m/Y') }}
                            - {{ $p->agenda->cliente->nombreCliente ?? 'N/A' }}
                        @endif
                    </td>

                    <td class="px-4 py-3">${{ number_format($p->Monto, 2) }}</td>

                    <td class="px-4 py-3">{{ $p->MetodoPago }}</td>

                    <td class="px-4 py-3">{{ $p->FechaPago?->format('d/m/Y H:i') }}</td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs
                            {{ $p->RequerirFactura ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $p->RequerirFactura ? 'Sí' : 'No' }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-center">

                        <a href="{{ route('pagos.edit', $p->id) }}"
                            class="text-blue-600 hover:text-blue-900 mr-3">
                            ✏️
                        </a>

                        <form action="{{ route('pagos.destroy', $p->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="text-red-600 hover:text-red-900"
                                onclick="return confirm('¿Desea eliminar este pago?')">
                                🗑️
                            </button>
                        </form>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                        No hay pagos registrados.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</x-card>

@endsection