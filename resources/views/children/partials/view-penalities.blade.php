<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Penalizaciones') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Aquí puedes consultar las penalizaciones que ha tenido este peñista.") }}
        </p>

    </header>

    @if ($nino->penalties)
        <table class="w-full border-collapse bg-white text-center text-gray-500 mt-6">
            <thead class="bg-orange-500">
                <tr>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Descripción</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Año</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    @foreach ($penality as $penalty_row)
                        <tr class="hover:bg-gray-50">
                            <th class="flex gap-3 px-6 py-4 font-normal text-gray-900 text-center">
                                {{ $penalty_row->name }}
                            </th>
                            <td class="px-6 py-4 font-normal text-gray-900 text-center">
                                {{ \Carbon\Carbon::parse($penalty_row->date_penality)->format('d/m/Y H:m') }}
                            </td>
                        </tr>
                    @endforeach
            </tbody>
        </table>
    @else
        <div class="mt-6">
            <h4>Este peñista no tiene penalizaciones</h4>
        </div>
    @endif

    <br>
    <header>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Aquí puedes añadir penalizaciones a este peñista.") }}
        </p>
    </header>

    <form method="post" action="{{ route('niños.add_penality' , $nino) }}" class="mt-6 space-y-6">
    @csrf
    @method('patch')
        <div>
            <x-input-label for="penality" :value="__('Añadir penalización')" />
            <select id="penality" name="penality" class="w-full border-orange-500 focus:border-orange-600 focus:ring-orange-400 rounded-md shadow-sm">
                <option value="">-- Selecciona una penalización --</option>
                @foreach ($penaltiesAll as $penalty)
                    <option value="{{ $penalty->id }}">{{ $penalty->name }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('penality')" />
        </div>
        <div class="mt-6">
            <x-primary-button>
                {{ __('Añadir penalización') }}
            </x-primary-button>
        </div>
    </form>
</section>
