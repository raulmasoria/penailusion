<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Responsable de menores') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Aquí puedes consultar si este socio es responsable de algún menor.") }}
        </p>

    </header>

    @if ($childrens_responsible)
        <table class="w-full border-collapse bg-white text-center text-gray-500 mt-6">
            <thead class="bg-orange-500">
                <tr>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Datos del menor</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                @foreach ($childrens_responsible as $child)
                    <tr class="hover:bg-gray-50">
                        <th class="flex gap-3 px-6 py-4 font-normal text-gray-900 text-center">
                            <a class="underline" href="/niños/{{ $child->id }}">{{ $child->name . ' ' . $child->lastname}}</a>
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="mt-6">
            <h4>Este peñista no tiene menores a su cargo</h4>
        </div>
    @endif

</section>
