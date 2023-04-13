<?php

namespace App\Http\Livewire;

use Livewire\Component;

class BuscadorFiltros extends Component
{   
    public $termino;

    public function render()
    {
        return view('livewire.buscador-filtros');
    }

    public function leerDatosFormulario()
    {        
       $this->emit('terminosBusqueda',$this->termino);
    }
}
