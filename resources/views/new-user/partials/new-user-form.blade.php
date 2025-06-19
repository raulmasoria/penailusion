<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Creación de socio') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Aquí puedes añadir los datos del nuevo peñista") }}
        </p>

    </header>

    <form method="post" action="{{ route('socios.store') }}" class="mt-6 space-y-6">
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
            <x-input-label for="phone" :value="__('Telefono *')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" required autofocus autocomplete="phone"/>
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="nif" :value="__('DNI *')" />
            <x-text-input id="nif" name="nif" type="text" class="mt-1 block w-full" :value="old('nif')" required autofocus autocomplete="nif"/>
            <x-input-error class="mt-2" :messages="$errors->get('nif')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email *')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <hr class="mt-6 mb-6">

        <p class="mt-1 text-xl text-gray-600">
            {{ __("Datos de la dirección postal") }}
        </p>

        <div>
            <x-input-label for="via" :value="__('Via')" />
            <x-text-input id="via" name="via" type="text" class="mt-1 block w-full" :value="old('via')" autofocus autocomplete="via" />
            <x-input-error class="mt-2" :messages="$errors->get('via')" />
        </div>

        <div>
            <x-input-label for="direccion" :value="__('Dirección')" />
            <x-text-input id="direccion" name="direccion" type="text" class="mt-1 block w-full" :value="old('direccion')" autofocus autocomplete="direccion" />
            <x-input-error class="mt-2" :messages="$errors->get('direccion')" />
        </div>

        <div>
            <x-input-label for="piso" :value="__('Piso')" />
            <x-text-input id="piso" name="piso" type="text" class="mt-1 block w-full" :value="old('piso')" autofocus autocomplete="piso"/>
            <x-input-error class="mt-2" :messages="$errors->get('piso')" />
        </div>

        <div>
            <x-input-label for="cp" :value="__('Codigo postal')" />
            <x-text-input id="cp" name="cp" type="text" class="mt-1 block w-full" :value="old('cp')" autofocus autocomplete="cp" />
            <x-input-error class="mt-2" :messages="$errors->get('cp')" />
        </div>

        <div>
            <x-input-label for="ciudad" :value="__('Ciudad')" />
            <x-text-input id="ciudad" name="ciudad" type="text" class="mt-1 block w-full" :value="old('ciudad')" autofocus autocomplete="ciudad" />
            <x-input-error class="mt-2" :messages="$errors->get('ciudad')" />
        </div>

        <div>
            <x-input-label for="provincia" :value="__('Provincia')" />
            <x-text-input id="provincia" name="provincia" type="text" class="mt-1 block w-full" :value="old('provincia')" autofocus autocomplete="provincia" />
            <x-input-error class="mt-2" :messages="$errors->get('provincia')" />
        </div>

        <hr class="mt-6 mb-6">

        <p class="mt-1 text-xl text-gray-600">
            {{ __("Datos de intolerancias") }}
        </p>

        <div>
            <x-input-label for="lactosa" :value="__('Lactosa')" />
            <x-text-input id="lactosa" name="lactosa" type="checkbox" class="mt-1 block" value="lactosa" autofocus autocomplete="lactosa" />
            <x-input-error class="mt-2" :messages="$errors->get('lactosa')" />
        </div>

        <div>
            <x-input-label for="gluten" :value="__('Gluten')" />
            <x-text-input id="gluten" name="gluten" type="checkbox" class="mt-1 block" value="gluten" autofocus autocomplete="gluten" />
            <x-input-error class="mt-2" :messages="$errors->get('gluten')" />
        </div>

        <div>
            <x-input-label for="celiaco" :value="__('Celiaco')" />
            <x-text-input id="celiaco" name="celiaco" type="checkbox" class="mt-1 block" value="celiaco" autofocus autocomplete="celiaco" />
            <x-input-error class="mt-2" :messages="$errors->get('celiaco')" />
        </div>

        <div>
            <x-input-label for="fructosa" :value="__('Fructosa')" />
            <x-text-input id="fructosa" name="fructosa" type="checkbox" class="mt-1 block" value="fructosa" autofocus autocomplete="fructosa" />
            <x-input-error class="mt-2" :messages="$errors->get('fructosa')" />
        </div>

        <div>
            <x-input-label for="huevo" :value="__('Alergico al huevo')" />
            <x-text-input id="huevo" name="huevo" type="checkbox" class="mt-1 block" value="huevo" autofocus autocomplete="huevo" />
            <x-input-error class="mt-2" :messages="$errors->get('huevo')" />
        </div>

        <hr class="mt-6 mb-6">

        <p class="mt-1 text-xl text-gray-600">
            {{ __("Datos de los padrinos") }}
        </p>

        <div>
            <x-input-label for="padrino1" :value="__('Primer padrino *')" />
                <select id="padrino1" name="padrino1" required class="select2 border-orange-500 focus:border-orange-600 focus:ring-orange-400 rounded-md shadow-sm w-full">
                    <option value="" >-- Elige un socio --</option>
                    @foreach ($godfathers as $godfather_id => $godfather)
                        <option value="{{ $godfather_id }}"> {{$godfather }} </option>
                    @endforeach
                </select>
            <x-input-error :messages="$errors->get('padrino1')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="padrino2" :value="__('Segundo padrino *')" />
                <select id="padrino2" name="padrino2" required class="select2 border-orange-500 focus:border-orange-600 focus:ring-orange-400 rounded-md shadow-sm w-full">
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