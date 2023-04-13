<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BuscadorFiltrosNiños extends Component
{
    public $termino;
    public $limpieza = '';

    public function render()
    {
        return view('livewire.buscador-filtros-niños');
    }

    public function leerDatosFormulario()
    {        
       $this->emit('terminosBusqueda',$this->termino);
    }

}