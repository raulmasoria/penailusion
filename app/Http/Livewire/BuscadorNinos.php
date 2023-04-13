<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Children;
use App\Models\Childrens_antiquities;
use Illuminate\Support\Facades\DB;

class BuscadorNinos extends Component
{
    public $termino;

    protected $listeners = ['terminosBusqueda' => 'buscar', 'pagarCuota'];    
    
    public function buscar($termino)
    {   
        $this->termino = $termino;   
    }

    public function render()
    {
        $niños = Children::when($this->termino, function($query){
            $query->where('name','LIKE', '%'.$this->termino.'%');
        })->when($this->termino, function($query){
            $query->orWhere('lastname','LIKE', '%'.$this->termino.'%');
        })->when($this->termino, function($query){
            $query->orWhere('birthdate','LIKE', '%'.$this->termino.'%');
        })->when($this->termino, function($query){
            $query->orWhere('responsible','LIKE', '%'.$this->termino.'%');
        })->when($this->termino, function($query){
            $query->orWhere('phone_responsible','LIKE', '%'.$this->termino.'%');
        })->orderBy('name', 'asc')
        ->paginate(50);

        $year = Carbon::now()->format('Y');
        $antiquitys = Childrens_antiquities::select('children_id')->where('year',$year)->get();

        return view('livewire.buscador-ninos',[
            "niños" => $niños,
            "antiquitys" => $antiquitys,
            "titulo" => "Buscador de niños",
        ]);
    }

    //pagar cuota anual
    public function pagarCuota($id)
    {
        DB::table('childrens_antiquities')->insert([
            'year' => Carbon::now()->format('Y'),
            'children_id' => $id,
            'created_at' => Carbon::now()
        ]);
    }
}
