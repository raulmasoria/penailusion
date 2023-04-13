@extends('layouts.plantilla')

@section('contenido')   
    
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'user-create')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 10000)"
                    class="text-xl text-green-600"
                >{{ __('Nuevo socio creado.') }}</p>
            @endif
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @include('user.partials.update-profile-information-form')
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @include('user.partials.update-adress-form')
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @include('user.partials.view-year-antiquity')
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                @include('user.partials.view-godfathers')
            </div>

            <!-- <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('user.partials.update-password-form')
                </div>
            </div> -->
        </div>
    </div>
@endsection