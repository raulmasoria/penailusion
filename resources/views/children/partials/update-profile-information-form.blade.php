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
            <x-text-input id="fecha" name="fecha" type="date" class="mt-1 block w-full" :value="old('fecha', $nino->birthdate)" required autofocus autocomplete="fecha"/>
            <x-input-error class="mt-2" :messages="$errors->get('fecha')" />
        </div>

        <div>
            <x-input-label for="responsible" :value="__('Responsable')" />
            <x-text-input id="responsible" name="responsible" type="text" class="mt-1 block w-full" :value="old('responsible', $nino->responsible)" required autofocus autocomplete="responsible"/>
            <x-input-error class="mt-2" :messages="$errors->get('responsible')" />
        </div>

        <div>
            <x-input-label for="responsiblePhone" :value="__('Telefono del responsable')" />
            <x-text-input id="responsiblePhone" name="phone_responsible" type="text" class="mt-1 block w-full" :value="old('responsiblePhone', $nino->phone_responsible)" required autofocus autocomplete="responsiblePhone"/>
            <x-input-error class="mt-2" :messages="$errors->get('responsiblePhone')" />
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
