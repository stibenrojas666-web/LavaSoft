@if ($errors->any())
    <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-4 text-sm text-rose-800" role="alert">
        <p class="mb-2 font-bold">Revisa la información ingresada:</p>
        <ul class="list-inside list-disc space-y-1">
            @foreach ($errors->all() as $mensaje)
                <li>{{ $mensaje }}</li>
            @endforeach
        </ul>
    </div>
@endif