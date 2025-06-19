<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Creación de socio sin necesidad de padrinos') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Aquí puedes añadir los datos del nuevo peñista.") }}
        </p>

    </header>

    <form method="post" action="{{ route('socios.store.admin') }}" class="mt-6 space-y-6">
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
        <div class="inline-flex">
            <div class="mr-5">
                <x-input-label for="lactosa" :value="__('Lactosa')" />
                <x-text-input id="lactosa" name="lactosa" type="checkbox" class="mt-1 block" value="lactosa" autofocus autocomplete="lactosa" />
                <x-input-error class="mt-2" :messages="$errors->get('lactosa')" />
            </div>

            <div class="mr-5">
                <x-input-label for="gluten" :value="__('Gluten')" />
                <x-text-input id="gluten" name="gluten" type="checkbox" class="mt-1 block" value="gluten" autofocus autocomplete="gluten" />
                <x-input-error class="mt-2" :messages="$errors->get('gluten')" />
            </div>

            <div class="mr-5">
                <x-input-label for="celiaco" :value="__('Celiaco')" />
                <x-text-input id="celiaco" name="celiaco" type="checkbox" class="mt-1 block" value="celiaco" autofocus autocomplete="celiaco" />
                <x-input-error class="mt-2" :messages="$errors->get('celiaco')" />
            </div>

            <div class="mr-5">
                <x-input-label for="fructosa" :value="__('Fructosa')" />
                <x-text-input id="fructosa" name="fructosa" type="checkbox" class="mt-1 block" value="fructosa" autofocus autocomplete="fructosa" />
                <x-input-error class="mt-2" :messages="$errors->get('fructosa')" />
            </div>

            <div class="mr-5">
                <x-input-label for="huevo" :value="__('Alergico al huevo')" />
                <x-text-input id="huevo" name="huevo" type="checkbox" class="mt-1 block" value="huevo" autofocus autocomplete="huevo" />
                <x-input-error class="mt-2" :messages="$errors->get('huevo')" />
            </div>
        </div>

        <hr>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>
        </div>
    </form>
</section>
