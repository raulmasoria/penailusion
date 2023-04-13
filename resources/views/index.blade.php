@extends('layouts.plantilla')

@section('contenido')

  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="text-2xl  text-gray-900 m-6">
                    {{ __("Bienvenido al panel de gestión de socios de la Peña Ilusión") }}
                </h2>
                <div class="p-6 text-gray-900">
                    {{ __("En esta web podrás gestionar los usuarios de la peña.") }}
                </div>
                <img class="w-64 h-64 rounded-full mx-auto" src="/img/Escudo_peña.jpeg" alt="Peña Ilusión">

          </div>
      </div>
  </div>
 
@endsection