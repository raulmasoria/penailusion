<div class="bg-gray-100 py-10">
    <h2 class="text-2xl md:text-4xl text-gray-600 text-center font-extrabold my-5">Busqueda de niños</h2>

    <div class="max-w-7xl mx-auto">
        <form wire:submit.prevent='leerDatosFormulario'>
            <div class="mb-5 flex-auto">
                <input 
                    id="termino"
                    type="text"
                    placeholder="Buscar por nombre, apellido, fecha de nacimiento o datos del responsable..."
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-3/4 inline-flex ml-5 mr-5"
                    wire:model="termino"
                />
                <button class="button bg-orange-500 mt-5 p-2 ml-5 inline-flex rounded-lg" wire:click="leerDatosFormulario">
                    Buscar
                </button>
                <a href="/niños" class="button bg-orange-500 mt-5 p-2 ml-5 inline-flex rounded-lg" >
                    Limpiar datos
                </a>
            </div>
        </form>    
    </div>
</div>