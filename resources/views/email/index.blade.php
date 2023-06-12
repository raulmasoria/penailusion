@extends('layouts.plantilla')

@section('contenido')

  <div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <section>
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Envio de emails') }}
                    </h2>
            
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __("Aquí puedes redactar y enviar los emails a los socios.") }}
                    </p>
                    
                </header>
            
                <form method="post" action="{{ route('email.send') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('patch')
                    
                    <div>
                        <x-input-label for="emails" :value="__('Listado de emails')" />       
                        <select id="emails" name="emails" class="border-orange-500 focus:border-orange-600 focus:ring-orange-400 rounded-md shadow-sm w-full">
                            <option value="" >-- ¿A quién quieres enviar el email? --</option>
                            <option value="prueba">Prueba</option>
                            <option value="socios">Socios del último año</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('emails')" />
                    </div>

                    <div>
                        <x-input-label for="asunto" :value="__('Asunto')" />
                        <x-text-input id="asunto" name="asunto" type="text" class="mt-1 block w-full" required autofocus autocomplete="asunto"/>
                        <x-input-error class="mt-2" :messages="$errors->get('asunto')" />
                    </div>

                    <div>
                        <x-input-label for="cuerpo" :value="__('Cuerpo del email')" />
                        <textarea class="ckeditor" name="cuerpo" id="cuerpo" rows="10" cols="80">                            
                        </textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('cuerpo')" />
                    </div>
                               
            
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Enviar') }}</x-primary-button>
            
                        @if (session('status') === 'email-send')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 5000)"
                                class="text-sm text-gray-600"
                            >{{ __('Enviado.') }}</p>
                        @endif
                    </div>
                </form>
            </section>
            
        </div>        
    </div>
  </div>
  
@endsection