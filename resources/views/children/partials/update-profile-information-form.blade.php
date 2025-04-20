<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Información del peñista') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Aquí puedes actualizar algunos datos como el telefono,email o dirección postal.") }}
        </p>

    </header>

    <form method="post" action="{{ route('niños.update' , $nino) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $nino->name)" required autofocus autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="lastname" :value="__('Apellidos')" />
            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" :value="old('lastname', $nino->lastname)" required autofocus autocomplete="lastname" />
            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
        </div>

        <div>
            <x-input-label for="fecha" :value="__('Fecha nacimiento')" />
            <x-text-input id="fecha" name="fecha" type="date" class="mt-1 block w-full" :value="old('fecha', $nino->birthdate ? $nino->birthdate->format('Y-m-d') : '')" required autofocus autocomplete="fecha"/>
            <x-input-error class="mt-2" :messages="$errors->get('fecha')" />
        </div>

        <div>
            <x-input-label for="responsible" :value="__('Responsable')" />
            <x-text-input id="responsible" name="responsible" type="text" class="mt-1 block w-full" :value="old('responsible', $responsible[0]->name . ' ' . $responsible[0]->lastname)" required autofocus autocomplete="responsible" disabled/>
            <x-input-error class="mt-2" :messages="$errors->get('responsible')" />
        </div>

        <div>
            <x-input-label for="responsiblePhone" :value="__('Telefono del responsable')" />
            <x-text-input id="responsiblePhone" name="phone_responsible" type="text" class="mt-1 block w-full" :value="old('responsiblePhone', $responsible[0]->phone)" required autofocus autocomplete="responsiblePhone" disabled/>
            <x-input-error class="mt-2" :messages="$errors->get('responsiblePhone')" />
        </div>

        <hr class="my-4">

        <div>
            <div class="flex items-center gap-2">
                <x-input-label for="changeeResponsible" :value="__('Asignar otro responsable')" />
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 12 12" enable-background="new 0 0 12 12" version="1.1" xml:space="preserve" class="cursor-pointer mb-2">
                    <title>Un adulto solo puede ser responsable si ha pagado la cuota completa o permanencia en el año actual.</title>
                    <path d="M6,0C2.6862793,0,0,2.6862793,0,6s2.6862793,6,6,6s6-2.6862793,6-6S9.3137207,0,6,0z M6.5,9.5h-1v-1h1V9.5z M7.2651367,6.1738281C6.7329102,6.5068359,6.5,6.6845703,6.5,7v0.5h-1V7c0-0.9023438,0.7138672-1.3486328,1.2348633-1.6738281 C7.2670898,4.9931641,7.5,4.8154297,7.5,4.5c0-0.5517578-0.4487305-1-1-1h-1c-0.5512695,0-1,0.4482422-1,1V5h-1V4.5 c0-1.1025391,0.8969727-2,2-2h1c1.1030273,0,2,0.8974609,2,2C8.5,5.4023438,7.7861328,5.8486328,7.2651367,6.1738281z" fill="#1D1D1B"/>
                </svg>
            </div>
            <select id="changeeResponsible" name="changeeResponsible" class="select2 w-full border-orange-500 focus:border-orange-600 focus:ring-orange-400 rounded-md shadow-sm">
                <option value="">-- Selecciona un adulto responsable para cambiarlo --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} {{ $user->lastname }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('changeeResponsible')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'nino-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 5000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
@push('scripts')
<script>
    $('#changeeResponsible').select2({
        placeholder: "-- Selecciona un adulto responsable para cambiarlo --",
        allowClear: true,
        width: '100%'
    });
</script>
@endpush
