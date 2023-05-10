<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Pasar a adulto') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("En este apartado podrás rellenar los siguientes datos para completar su migración de niño a adulto.") }}
        </p>
        <p class="mt-1 text-sm text-gray-900">
            {{ __("-- Solo rellenar si cumple con la edad para ello. --") }}
        </p>

        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" style="float:right;" id="flechaAdulto">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
        </svg>
          
        
    </header>

    <form method="post" action="{{ route('niños.adult' , $nino) }}" class="mt-6 space-y-6" id="formAdulto" style="display:none">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="phone" :value="__('Telefono')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" required autofocus autocomplete="phone"/>
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="nif" :value="__('DNI')" />
            <x-text-input id="nif" name="nif" type="text" class="mt-1 block w-full" :value="old('nif')" required autofocus autocomplete="nif"/>
            <x-input-error class="mt-2" :messages="$errors->get('nif')" />
        </div>        

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />            
        </div>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Datos de la dirección.") }}
        </p>

        <div>
            <x-input-label for="via" :value="__('Via')" />
            <x-text-input id="via" name="via" type="text" class="mt-1 block w-full" :value="old('via')" required autofocus autocomplete="via" />
            <x-input-error class="mt-2" :messages="$errors->get('via')" />
        </div>

        <div>
            <x-input-label for="direccion" :value="__('Dirección')" />
            <x-text-input id="direccion" name="direccion" type="text" class="mt-1 block w-full" :value="old('direccion')" required autofocus autocomplete="direccion" />
            <x-input-error class="mt-2" :messages="$errors->get('direccion')" />
        </div>

        <div>
            <x-input-label for="piso" :value="__('Piso')" />
            <x-text-input id="piso" name="piso" type="text" class="mt-1 block w-full" :value="old('piso')" required autofocus autocomplete="piso"/>
            <x-input-error class="mt-2" :messages="$errors->get('piso')" />
        </div>

        <div>
            <x-input-label for="cp" :value="__('Codigo postal')" />
            <x-text-input id="cp" name="cp" type="text" class="mt-1 block w-full" :value="old('cp')" required autofocus autocomplete="cp" />
            <x-input-error class="mt-2" :messages="$errors->get('cp')" />
        </div>

        <div>
            <x-input-label for="ciudad" :value="__('Ciudad')" />
            <x-text-input id="ciudad" name="ciudad" type="text" class="mt-1 block w-full" :value="old('ciudad')" required autofocus autocomplete="ciudad" />
            <x-input-error class="mt-2" :messages="$errors->get('ciudad')" />
        </div>

        <div>
            <x-input-label for="provincia" :value="__('Provincia')" />
            <x-text-input id="provincia" name="provincia" type="text" class="mt-1 block w-full" :value="old('provincia')" required autofocus autocomplete="provincia" />
            <x-input-error class="mt-2" :messages="$errors->get('provincia')" />
        </div>

        <p class="mt-1 text-xl text-red-600 border-red-400 border p-2 bg-red-50">
            {{ __("Al pulsar el botón Guardar, se borrará este niño y se creará un socio de pleno derecho. Asegurate de que todos los datos están correctos y que tiene la edad para ser socio de pleno derecho.") }}
        </p>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>            
        </div>
    </form>

</section>   
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $("#flechaAdulto").on( "click", function() {
            if($('#formAdulto').css('display') == 'none')
            {
                $("#formAdulto").css("display", "block");
            } else {
                $("#formAdulto").css("display", "none");
            }
                
        } );
        
    </script>
@endpush