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
    @elseif ($user->childrentoadult)
        <div class="mt-6">
            <h4>Este peñista ha pasado de niño a adulto.</h4>
        </div>
    @else
        <div class="mt-6">
            <h4>Este peñista no tiene padrinos</h4>
        </div>
    @endif

    <hr class="mt-6">

    <header class="mt-6">
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Aquí puedes consultar a quién ha apadrinado este peñista.") }}
        </p>
    </header>

    @if ($all_godfather)
        <table class="w-full border-collapse bg-white text-center text-gray-500 mt-6">
            <thead class="bg-orange-500">
                <tr>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Apadrinó a</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Año apadrinamiento</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                @foreach ($all_godfather as $year => $data_godfather)
                    <tr class="hover:bg-gray-50">
                        <th class="flex gap-3 px-6 py-4 font-normal text-gray-900 text-center">
                            <a class="underline" href="/socios/{{ $data_godfather->id }}">{{ $data_godfather->name . ' ' . $data_godfather->lastname}}</a>
                        </th>
                        <td class="px-6 py-4 font-normal text-gray-900 text-center">
                            {{ $year }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    @if ($all_childrens_godfather)
        <table class="w-full border-collapse bg-white text-center text-gray-500 mt-6">
            <thead class="bg-orange-500">
                <tr>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Apadrinó a</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Año apadrinamiento</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                @foreach ($all_childrens_godfather as $year => $data_godfather)
                    <tr class="hover:bg-gray-50">
                        <th class="flex gap-3 px-6 py-4 font-normal text-gray-900 text-center">
                            <a class="underline" href="/niños/{{ $data_godfather->id }}">{{ $data_godfather->name . ' ' . $data_godfather->lastname}}</a>
                        </th>
                        <td class="px-6 py-4 font-normal text-gray-900 text-center">
                            {{ $year }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    @if (!$all_godfather && !$all_childrens_godfather)
        <div class="mt-6">
            <h4>Este peñista no ha apadrinado a nadie todavia.</h4>
        </div>
    @endif

</section>
