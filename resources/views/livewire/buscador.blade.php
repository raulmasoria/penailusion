<div class="relative items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-lg border border-gray-200 shadow-md m-5">
            <livewire:buscador-filtros />
            </div>
            
            @if (count($socios) > 0)

                <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
                    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Nombre</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Estado</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Email</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Teléfono</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">DNI</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Cuota completa - Permanencia - Editar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        
                        @foreach ($socios as $socio)
                        <tr class="hover:bg-gray-50">
                            <th class="flex gap-3 px-6 py-4 font-normal text-gray-900">
                                <div class="text-sm">
                                    <div class="font-medium text-gray-700">{{ $socio->name }}</div>
                                    <div class="text-gray-400">{{ $socio->lastname }}</div>
                                </div>
                            </th>
                            
                            @php
                                $activo = false;
                                $mantenimiento = false;
                            @endphp

                            @foreach ($antiquitys as $antiquity)
                                @if ($antiquity->user_id == $socio->id)
                                    @php $activo = true; @endphp                                    
                                @endif
                            @endforeach

                            @foreach ($permanences as $permanence)
                                @if ($permanence->user_id == $socio->id)
                                    @php $mantenimiento = true; @endphp                                    
                                @endif
                            @endforeach

                            <td class="px-6 py-4">  
                                @if ($activo)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                        Cuota pagada
                                    </span>
                                @elseif($mantenimiento)    
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-blue-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                        Cuota de permanencia
                                    </span> 
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                                        Cuota no pagada
                                    </span>
                                @endif                                         
                            
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-700">{{ $socio->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-700">{{ $socio->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-700">{{ $socio->nif }}</div>
                            </td>
                            
                            <td class="px-6 py-4">
                            <div class="flex justify-end gap-4">
                                @php
                                    $socioname = '"'.$socio->id. '-' . $socio->name.'"';
                                @endphp

                                @if (!$activo && !$mantenimiento)
                                    <button wire:click="$emit('pagarCuotaScript',{{ $socioname }})" title="Cuota completa">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                        </svg>                                          
                                    </button>    
                                    <button wire:click="$emit('pagarMantenimientoScript',{{ $socioname }})" title="Cuota de permanencia">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10.496 2.132a1 1 0 00-.992 0l-7 4A1 1 0 003 8v7a1 1 0 100 2h14a1 1 0 100-2V8a1 1 0 00.496-1.868l-7-4zM6 9a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1zm3 1a1 1 0 012 0v3a1 1 0 11-2 0v-3zm5-1a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>                                        
                                    </button>                                
                                @endif                                       
                               
                                <a href="{{ route('user.edit', $socio->id) }}" title="Editar usuario">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6" x-tooltip="tooltip">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"></path>
                                    </svg>
                                </a>                                    
                            </div>
                            </td>
                        </tr>
                        @endforeach
                        
                        
                    </tbody>

                    </table>
                    
                </div>
                <div class="container">                        
                    {{ $socios->links() }} 
                </div>
            @else    
                <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
                    <h1>No se han encontrado socios con esos parametros. Te recordamos que solo se puede buscar por un parametro. Nombre y apellidos, son dos diferentes.</h1>
                </div>
            @endif

            @push('scripts')
                <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <script>
                    
                    Livewire.on('pagarCuotaScript',function (usuario) {
                        let arr = usuario.split("-");
                        Swal.fire({
                            title: '¿Ha pagado ' + arr[1] + ' la cuota anual?',
                            text: "¡Esta accion no se puede revetir!",
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonColor: '#F97316',
                            cancelButtonColor: '#000',
                            confirmButtonText: 'Si, cuota pagada',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                //llamar al metodo de livewire
                                Livewire.emit('pagarCuota', arr[0]);
                                Swal.fire(
                                    'Pagado!',
                                    'Este socio ya puede pillar una birra.',
                                    'success'
                                )
                            }
                        })
                    })

                    Livewire.on('pagarMantenimientoScript',function (usuario) {
                        let arr = usuario.split("-");
                        Swal.fire({
                            title: '¿Ha pagado ' + arr[1] + ' la cuota de mantenimiento?',
                            text: "¡Esta accion no se puede revetir!",
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonColor: '#F97316',
                            cancelButtonColor: '#000',
                            confirmButtonText: 'Si, cuota pagada',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                //llamar al metodo de livewire
                                Livewire.emit('mantenimiento', arr[0]);
                                Swal.fire(
                                    'Pagado!',
                                    'Este socio ya puede pillar una birra.',
                                    'success'
                                )
                            }
                        })
                    })
                </script>
            @endpush
        </div>
    </div>
</div>

  
