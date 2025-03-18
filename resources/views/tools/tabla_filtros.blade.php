@extends('layouts.plantilla')

@section('contenido')
    <!DOCTYPE html>
    <html>
    <head>
        <title>Usuarios</title>
        <!-- Estilos de DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
    </head>
    <body>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-7xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Listado de usuarios') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __("Aquí puedes filtrar y exportar listados de usuario a tu gusto.") }}
                                </p>
                            </header>
                            <form method="GET" action="{{ route('users.filter') }}" class="bg-white p-6 rounded-lg shadow-md w-full">
                                <!-- Contenedor principal con espacio entre filas -->
                                <div class="space-y-4">
                                    <!-- Primera fila: Nombre y Apellido -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex flex-col w-full">
                                            <label class="text-orange-600 font-semibold">Nombre</label>
                                            <input type="text" name="filter_name" placeholder="Nombre" value="{{ request('filter_name') }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-300 p-2">
                                        </div>
                                        <div class="flex flex-col w-full">
                                            <label class="text-orange-600 font-semibold">Apellido</label>
                                            <input type="text" name="filter_lastname" placeholder="Apellido" value="{{ request('filter_lastname') }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-300 p-2">
                                        </div>
                                    </div>
                            
                                    <!-- Segunda fila: Email y Teléfono -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex flex-col w-full">
                                            <label class="text-orange-600 font-semibold">Email</label>
                                            <input type="text" name="filter_email" placeholder="Email" value="{{ request('filter_email') }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-300 p-2">
                                        </div>
                                        <div class="flex flex-col w-full">
                                            <label class="text-orange-600 font-semibold">Teléfono</label>
                                            <input type="text" name="filter_phone" placeholder="Teléfono" value="{{ request('filter_phone') }}"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-300 p-2">
                                        </div>
                                    </div>
                            
                                    <!-- Tercera fila: Selects en dos columnas -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="flex flex-col w-full">
                                            <div class="flex items-center">
                                                <label class="text-orange-600 font-semibold mr-2">Años de cuota completa</label>
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 12 12" enable-background="new 0 0 12 12" version="1.1" xml:space="preserve" class="cursor-pointer">
                                                    <title>Los años que selecciones serán acumulativos. Para seleccionar mas de uno, mantén CTRL y pulsa sobre los años. Para deselecionarlos igual.</title>
                                                    <path d="M6,0C2.6862793,0,0,2.6862793,0,6s2.6862793,6,6,6s6-2.6862793,6-6S9.3137207,0,6,0z M6.5,9.5h-1v-1h1V9.5z M7.2651367,6.1738281C6.7329102,6.5068359,6.5,6.6845703,6.5,7v0.5h-1V7c0-0.9023438,0.7138672-1.3486328,1.2348633-1.6738281 C7.2670898,4.9931641,7.5,4.8154297,7.5,4.5c0-0.5517578-0.4487305-1-1-1h-1c-0.5512695,0-1,0.4482422-1,1V5h-1V4.5 c0-1.1025391,0.8969727-2,2-2h1c1.1030273,0,2,0.8974609,2,2C8.5,5.4023438,7.7861328,5.8486328,7.2651367,6.1738281z" fill="#1D1D1B"/>
                                                </svg>
                                            </div>
                                            <select name="filter_antiquity[]" multiple class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-300 p-2">
                                                @php
                                                    $years = [
                                                        \App\Http\Controllers\YearHelperController::lastLastLastLastYear(),
                                                        \App\Http\Controllers\YearHelperController::lastLastLastYear(),
                                                        \App\Http\Controllers\YearHelperController::lastLastYear(),
                                                        \App\Http\Controllers\YearHelperController::lastYear(),
                                                        \App\Http\Controllers\YearHelperController::currentYear()
                                                    ];
                                                    $selectedYears = request('filter_antiquity', []);
                                                @endphp
                                                @foreach($years as $year)
                                                    <option value="{{ $year }}" {{ in_array($year, $selectedYears) ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                            </select>
                                            <span class="absolute top-0 right-0 p-2 text-gray-500 cursor-pointer" title="Selecciona los años de cuota completa para los socios.">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                        </div>                            
                                        <div class="flex flex-col w-full">
                                            <div class="flex items-center">
                                                <label class="text-orange-600 font-semibold mr-2">Años de permanencia</label>
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 12 12" enable-background="new 0 0 12 12" version="1.1" xml:space="preserve" class="cursor-pointer">
                                                    <title>Los años que selecciones serán acumulativos. Para seleccionar mas de uno, mantén CTRL y pulsa sobre los años. Para deselecionarlos igual.</title>
                                                    <path d="M6,0C2.6862793,0,0,2.6862793,0,6s2.6862793,6,6,6s6-2.6862793,6-6S9.3137207,0,6,0z M6.5,9.5h-1v-1h1V9.5z M7.2651367,6.1738281C6.7329102,6.5068359,6.5,6.6845703,6.5,7v0.5h-1V7c0-0.9023438,0.7138672-1.3486328,1.2348633-1.6738281 C7.2670898,4.9931641,7.5,4.8154297,7.5,4.5c0-0.5517578-0.4487305-1-1-1h-1c-0.5512695,0-1,0.4482422-1,1V5h-1V4.5 c0-1.1025391,0.8969727-2,2-2h1c1.1030273,0,2,0.8974609,2,2C8.5,5.4023438,7.7861328,5.8486328,7.2651367,6.1738281z" fill="#1D1D1B"/>
                                                </svg>
                                            </div>
                                            <select name="filter_permanence[]" multiple class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-300 p-2">
                                                @php
                                                    $selectedPermanence = request('filter_permanence', []);
                                                @endphp
                                                @foreach($years as $year)
                                                    <option value="{{ $year }}" {{ in_array($year, $selectedPermanence) ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>                                        
                                    </div>
                            
                                    <!-- Botones -->
                                    <div class="flex justify-end space-x-4">
                                        <a href="{{ route('users.filter') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md shadow hover:bg-gray-400">
                                            Limpiar datos
                                        </a>
                                        <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-md shadow hover:bg-orange-600">
                                            Filtrar
                                        </button>
                                    </div>
                                </div>
                            </form>                                                    
                                                     
                            <div class="m-5">
                                {!! $dataTable->table() !!}
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts de DataTables y botones -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="/vendor/datatables/buttons.server-side.js"></script>

        {!! $dataTable->scripts() !!}

    </body>
    </html>
@endsection
