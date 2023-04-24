<?php

namespace App\Http\Livewire;

use DateTime;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Children;
use Illuminate\Support\Facades\DB;
use App\Models\Childrens_antiquities;

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
        $ninos = Children::when($this->termino, function($query){
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

        foreach($ninos as $nino)
        {
            if(!empty($nino->birthdate)){
                $fecha = Carbon::parse($nino->birthdate)->format('d-m-Y');
                $anios = $this->obtener_edad_segun_fecha($nino->birthdate);

                $nino->birthdate = $fecha;
                $nino['anios'] = $anios;
            }    
        }

        $year = Carbon::now()->format('Y');
        $antiquitys = Childrens_antiquities::select('children_id')->where('year',$year)->get();
        
        return view('livewire.buscador-ninos',[
            "ninos" => $ninos,
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

    function obtener_edad_segun_fecha($fecha_nacimiento)
    {
        $nacimiento = Carbon::parse($fecha_nacimiento)->format('d-m-Y');
        $ahora = Carbon::now()->format('Y-m-d');
        $diferencia = Carbon::now()->diffInYears($nacimiento);
        return $diferencia;
    }
}
