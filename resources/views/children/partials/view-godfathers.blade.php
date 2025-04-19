<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Padrinos') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Aquí puedes consultar los padrinos que ha tenido este peñista para entrar en la asociación.") }}
        </p>

    </header>

    @if ($godfathers)
        <table class="w-full border-collapse bg-white text-center text-gray-500 mt-6">
            <thead class="bg-orange-500">
                <tr>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Padrino 1</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Padrino 2</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Año apadrinamiento</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 border-t border-gray-100">

                    <tr class="hover:bg-gray-50">
                        <th class="flex gap-3 px-6 py-4 font-normal text-gray-900 text-center">
                            <a class="underline" href="/socios/{{ $godfather1->id }}">{{ $godfather1->name . ' ' . $godfather1->lastname}}</a>
                        </th>
                        <td class="px-6 py-4 font-normal text-gray-900 text-center">
                            <a class="underline" href="/socios/{{ $godfather2->id }}">{{ $godfather2->name . ' ' . $godfather2->lastname }}</a>
                        </td>
                        <td class="px-6 py-4 font-normal text-gray-900 text-center">
                            {{ $godfathers->year_godfather }}
                        </td>
                    </tr>
            </tbody>
        </table>
    @else    
        <div class="mt-6">
            <h4>Este peñista no tiene padrinos</h4>
        </div>
    @endif 
</section>
