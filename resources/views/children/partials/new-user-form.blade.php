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
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="lastname" :value="__('Apellidos')" />
            <x-text-input id="lastname" name="lastname" type="text" class="mt-1 block w-full" :value="old('lastname')" required autofocus autocomplete="lastname" />
            <x-input-error class="mt-2" :messages="$errors->get('lastname')" />
        </div>

        <div>
            <x-input-label for="fecha" :value="__('Fecha nacimiento')" />
            <x-text-input id="fecha" name="fecha" type="date" class="mt-1 block w-full" :value="old('fecha')" required autofocus autocomplete="fecha"/>
            <x-input-error class="mt-2" :messages="$errors->get('fecha')" />
        </div>

        <div>
            <x-input-label for="responsible" :value="__('Responsable')" />
            <x-text-input id="responsible" name="responsible" type="text" class="mt-1 block w-full" :value="old('responsible')" required autofocus autocomplete="responsible"/>
            <x-input-error class="mt-2" :messages="$errors->get('responsible')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Teléfono responsable')" />
            <x-text-input id="phone_responsible" name="phone_responsible" type="text" class="mt-1 block w-full" :value="old('phone_responsible')" required autocomplete="phone_responsible" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>     

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>           
        </div>
    </form>
</section>
