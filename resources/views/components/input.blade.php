
@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'hint' => null,
])

<div class="min-w-0">

    @if ($label)
        <label
            for="{{ $name }}"
            class="mb-2 block text-sm font-bold text-[#284657]"
        >
            {{ $label }}

            @if ($attributes->has('required'))
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @if ($errors->has($name))
            aria-invalid="true"
            aria-describedby="{{ $name }}-error"
        @endif
        {{ $attributes->merge([
            'class' =>
                'min-h-[46px] w-full rounded-xl border bg-white px-3.5 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-cyan-500 focus:outline-none focus:ring-4 focus:ring-cyan-100 ' .
                ($errors->has($name)
                    ? 'border-rose-400'
                    : 'border-slate-200')
        ]) }}
    >

    @if ($hint && !$errors->has($name))
        <p class="mt-1.5 text-xs leading-relaxed text-slate-400">
            {{ $hint }}
        </p>
    @endif

    @error($name)
        <p
            id="{{ $name }}-error"
            class="mt-1.5 text-xs font-medium text-rose-600"
        >
            {{ $message }}
        </p>
    @enderror

</div>
