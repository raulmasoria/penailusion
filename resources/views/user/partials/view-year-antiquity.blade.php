<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Antiguedad del peñista') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Aquí puedes consultar los años que el peñista ha pagado su cuota.") }}
        </p>
        
    </header>
     
    
    <table class="w-full border-collapse bg-white text-center text-gray-500 mt-6">
        <thead class="bg-orange-500">
            <tr>
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Año</th>                
                <th scope="col" class="px-6 py-4 font-medium text-gray-900">Tipo de cuota</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 border-t border-gray-100">
             @foreach ($anos as $anyo => $cuota)
                <tr class="hover:bg-gray-50">               
                    <th class="flex gap-3 px-6 py-4 font-normal text-gray-900 text-center">                    
                        {{ $anyo }}            
                    </th>
                    <td class="px-6 py-4 font-normal text-gray-900 text-center">                    
                        {{ $cuota }}         
                    </td>
                </tr> 
            @endforeach  
        </tbody>
    </table>    
    
</section>
