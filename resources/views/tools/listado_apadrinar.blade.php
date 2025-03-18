<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('¿Quien puede apadrinar?') }}
        </h2>
    </x-slot>

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
                        <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
                            <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                                <thead class="bg-gray-200">
                                    <tr class="border-b border-gray-200 bg-gray-200">
                                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Id</th>
                                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Nombre y apellidos</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 border-t border-gray-200">
                                    @foreach ($godfathers as $godfather_id => $godfather )
                                        <tr class="hover:bg-gray-200 border">              
                                            <td class="px-6 py-4">                    
                                                {{ $godfather_id }}            
                                            </td>
                                            <td class="px-6 py-4">                   
                                                {{ $godfather }}          
                                            </td>
                                        </tr> 
                                    @endforeach    
                                </tbody>                            
                            </table>
                        </div>    
                    </section>
                </div>
            </div>            
        </div>
    </div>
</x-app-layout>
