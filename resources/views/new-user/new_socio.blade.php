@extends('layouts.plantilla')

@section('contenido')

  <div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            @include('new-user.partials.new-user-form')
        </div>        
    </div>
  </div>
  
@endsection
