
@props(['headers' => []])

<div class="lavasoft-table-shell overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">

    <table data-datatable data-server-paginated="true" {{ $attributes->merge(['class' => 'lavasoft-table min-w-full']) }}>

        <thead>
            <tr class="bg-slate-50">

                @foreach ($headers as $header)
                    <th
                        scope="col"
                        class="whitespace-nowrap px-5 py-4 text-left text-[11px] font-extrabold uppercase tracking-wider text-slate-500"
                    >
                        {{ $header }}
                    </th>
                @endforeach

            </tr>
        </thead>

        <tbody class="divide-y divide-slate-100 bg-white">
            {{ $slot }}
        </tbody>

    </table>

</div>
