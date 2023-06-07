<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Intolerancias') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Aquí puedes actualizar tus intolerancias") }}
        </p>
    </header>

    <form method="post" action="{{ route('intolerances.update',$user) }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')
        
        <div>
            <x-input-label for="lactosa" :value="__('Lactosa')" />
            @if(isset($intolerances['Lactosa']))
                @if($intolerances['Lactosa'] == "Lactosa")
                    <x-text-input id="lactosa" name="lactosa" type="checkbox" class="mt-1 block" value="lactosa" checked="checked" autofocus autocomplete="lactosa" />                
                @endif 
            @else
            <x-text-input id="lactosa" name="lactosa" type="checkbox" class="mt-1 block" value="lactosa" autofocus autocomplete="lactosa" />
            @endif 
            <x-input-error class="mt-2" :messages="$errors->get('lactosa')" />
        </div>

        <div>
            <x-input-label for="gluten" :value="__('Gluten')" />
            @if(isset($intolerances['Gluten']))
                @if($intolerances['Gluten'] == "Gluten")
                    <x-text-input id="gluten" name="gluten" type="checkbox" class="mt-1 block" value="gluten" checked="checked" autofocus autocomplete="gluten" />
                @endif   
            @else
            <x-text-input id="gluten" name="gluten" type="checkbox" class="mt-1 block" value="gluten" autofocus autocomplete="gluten" />
            @endif 
            <x-input-error class="mt-2" :messages="$errors->get('gluten')" />
        </div>

        <div>
            <x-input-label for="celiaco" :value="__('Celiaco')" />
            @if(isset($intolerances['Celiaco']))
                @if($intolerances['Celiaco'] == "Celiaco")
                    <x-text-input id="celiaco" name="celiaco" type="checkbox" class="mt-1 block" value="celiaco" checked="checked" autofocus autocomplete="celiaco" />
                @endif   
            @else
                <x-text-input id="celiaco" name="celiaco" type="checkbox" class="mt-1 block" value="celiaco" autofocus autocomplete="celiaco" />
            @endif 
            <x-input-error class="mt-2" :messages="$errors->get('celiaco')" />
        </div>

        <div>
            <x-input-label for="fructosa" :value="__('Fructosa')" />
            @if(isset($intolerances['Fructosa']))
                @if($intolerances['Fructosa'] == "Fructosa")
                    <x-text-input id="fructosa" name="fructosa" type="checkbox" class="mt-1 block" value="fructosa" checked="checked" autofocus autocomplete="fructosa" />
                @endif   
            @else
                <x-text-input id="fructosa" name="fructosa" type="checkbox" class="mt-1 block" value="fructosa" autofocus autocomplete="fructosa" />
            @endif   
            <x-input-error class="mt-2" :messages="$errors->get('fructosa')" />
        </div>

        <div>
            <x-input-label for="huevo" :value="__('Alergico al huevo')" />
            @if(isset($intolerances['Huevo']))
                @if($intolerances['Huevo'] == "Huevo")
                    <x-text-input id="huevo" name="huevo" type="checkbox" class="mt-1 block" value="huevo" checked="checked" autofocus autocomplete="huevo" />
                @endif 
            @else
                <x-text-input id="huevo" name="huevo" type="checkbox" class="mt-1 block" value="huevo" autofocus autocomplete="huevo" />
            @endif        
            <x-input-error class="mt-2" :messages="$errors->get('huevo')" />
        </div>

        

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'intolerances-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
