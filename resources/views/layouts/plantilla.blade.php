<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Peña Ilusión - Gestión de Socios</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"/>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        <!-- Vite CSS -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/custom.css'])
        <!-- jquery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!--select2-->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!--ckeditor-->
        <script src="{{ asset('/vendors/ckeditor/ckeditor.js') }}"></script>

        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <main>
                @yield('contenido')
            </main>
        </div>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
