@if ($errors->any())
    <div class="rounded-md bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $mensaje)
                <li>{{ $mensaje }}</li>
            @endforeach
        </ul>
    </div>
@endif
