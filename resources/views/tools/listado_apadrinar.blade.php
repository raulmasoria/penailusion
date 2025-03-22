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
                                    {{ __('Apadrinamientos') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __("Aquí puedes consultar quien puede apadrinar este año.") }}
                                </p>
                            </header>

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

