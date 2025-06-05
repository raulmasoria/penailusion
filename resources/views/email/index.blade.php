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
                            <option value="prueba">Prueba a soriailusion@gmail.com</option>
                            <option value="libre">Libre elección</option>
                            <option value="socios_permanencia_ultimo_ano">Socios y permanencia del último año</option>
                            <option value="socios_permanencia_ano_actual">Socios y permanencia de este año</option>
                            <option value="socios_ultimo_ano">Socios del último año</option>
                            <option value="socios_ano_actual">Socios de este año</option>
                            <option value="permanencia_ultimo_ano">Permanencia del último año</option>
                            <option value="permanencia_ano_actual">Permanencia de este año</option>
                        </select>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Se debe de utilizar "**** del último año" cuando queremos enviar un email de enero a marzo.') }}
                        </p>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Se debe de utilizar "**** de este año" cuando queremos enviar un email de abril a diciembre.') }}
                        </p>
                        <x-input-error class="mt-2" :messages="$errors->get('emails')" />
                    </div>

                    <div style="display:none" id="libre">
                        <x-input-label for="libre" :value="__('Introduce correos separados por comas')" />
                        <x-text-input id="libreEmails" name="libreEmails" type="text" class="mt-1 block w-full" autofocus autocomplete="libre"/>
                        <x-input-error class="mt-2" :messages="$errors->get('libre')" />
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

@push('scripts')
    <script>
        $("#emails").on( "change", function() {
            var estado = $("#emails option:selected" ).val();
            if(estado == 'libre') {
                $("#libre").css("display", "block");
            } else {
                $("#libre").css("display", "none");
            }
        } );
    </script>
@endpush