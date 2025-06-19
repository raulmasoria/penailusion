<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Creación de niño') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Aquí puedes añadir los datos del nuevo peñista") }}
        </p>

    </header>

    <form method="post" action="{{ route('niños.store') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nombre *')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="lastname" :value="__('Apellidos *')" />
            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" :value="old('lastname')" required autofocus autocomplete="lastname" />
            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
        </div>

        <div>
            <x-input-label for="fecha" :value="__('Fecha nacimiento *')" />
            <x-text-input id="fecha" name="fecha" type="date" class="mt-1 block w-full" :value="old('fecha')" required autofocus autocomplete="fecha"/>
            <x-input-error class="mt-2" :messages="$errors->get('fecha')" />
        </div>

        <div>
            <div class="flex items-center gap-2">
                <x-input-label for="responsible" :value="__('Asignar responsable *')" />
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 12 12" enable-background="new 0 0 12 12" version="1.1" xml:space="preserve" class="cursor-pointer mb-2">
                    <title>Un adulto solo puede ser responsable si ha pagado la cuota completa o permanencia en el año actual.</title>
                    <path d="M6,0C2.6862793,0,0,2.6862793,0,6s2.6862793,6,6,6s6-2.6862793,6-6S9.3137207,0,6,0z M6.5,9.5h-1v-1h1V9.5z M7.2651367,6.1738281C6.7329102,6.5068359,6.5,6.6845703,6.5,7v0.5h-1V7c0-0.9023438,0.7138672-1.3486328,1.2348633-1.6738281 C7.2670898,4.9931641,7.5,4.8154297,7.5,4.5c0-0.5517578-0.4487305-1-1-1h-1c-0.5512695,0-1,0.4482422-1,1V5h-1V4.5 c0-1.1025391,0.8969727-2,2-2h1c1.1030273,0,2,0.8974609,2,2C8.5,5.4023438,7.7861328,5.8486328,7.2651367,6.1738281z" fill="#1D1D1B"/>
                </svg>
            </div>
            <select id="responsible" name="responsible_id" class="select2 w-full border-orange-500 focus:border-orange-600 focus:ring-orange-400 rounded-md shadow-sm" required>
                <option value="">-- Selecciona un adulto responsable --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} {{ $user->lastname }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('responsible')" />
        </div>
        <hr class="my-4">
        <div>
            <div class="flex items-center gap-2">
                <x-input-label :value="__('¿Necesita padrinos?')" />
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 12 12" enable-background="new 0 0 12 12" version="1.1" xml:space="preserve" class="cursor-pointer mb-2">
                    <title>Los niños de 16 y 17 años necesitan 2 padrinos para poder apuntarse. Completar los campos solo en ese caso.</title>
                    <path d="M6,0C2.6862793,0,0,2.6862793,0,6s2.6862793,6,6,6s6-2.6862793,6-6S9.3137207,0,6,0z M6.5,9.5h-1v-1h1V9.5z M7.2651367,6.1738281C6.7329102,6.5068359,6.5,6.6845703,6.5,7v0.5h-1V7c0-0.9023438,0.7138672-1.3486328,1.2348633-1.6738281 C7.2670898,4.9931641,7.5,4.8154297,7.5,4.5c0-0.5517578-0.4487305-1-1-1h-1c-0.5512695,0-1,0.4482422-1,1V5h-1V4.5 c0-1.1025391,0.8969727-2,2-2h1c1.1030273,0,2,0.8974609,2,2C8.5,5.4023438,7.7861328,5.8486328,7.2651367,6.1738281z" fill="#1D1D1B"/>
                </svg>
            </div>
        </div>
        <div>
            <x-input-label for="padrino1" :value="__('Primer padrino')" />
                <select id="padrino1" name="padrino1" class="select2 border-orange-500 focus:border-orange-600 focus:ring-orange-400 rounded-md shadow-sm w-full">
                    <option value="" >-- Elige un socio --</option>
                    @foreach ($godfathers as $godfather_id => $godfather)
                        <option value="{{ $godfather_id }}"> {{$godfather }} </option>
                    @endforeach
                </select>
            <x-input-error :messages="$errors->get('padrino1')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="padrino2" :value="__('Segundo padrino')" />
                <select id="padrino2" name="padrino2" class="select2 border-orange-500 focus:border-orange-600 focus:ring-orange-400 rounded-md shadow-sm w-full">
                    <option value="" >-- Elige un socio --</option>
                    @foreach ($godfathers as $godfather_id => $godfather)
                        <option value="{{ $godfather_id }}"> {{$godfather }} </option>
                    @endforeach
                </select>
            <x-input-error :messages="$errors->get('padrino2')" class="mt-2" />
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>
        </div>
    </form>
</section>
@push('scripts')
<script>
    $('#responsible').select2({
        placeholder: "-- Selecciona un adulto responsable --",
        allowClear: true,
        width: '100%'
    });

    $('#padrino1').select2({
        placeholder: "-- Elige un socio --",
        allowClear: true,
        width: '100%'
    });

    $('#padrino2').select2({
        placeholder: "-- Elige un socio --",
        allowClear: true,
        width: '100%'
    });
</script>
@endpush