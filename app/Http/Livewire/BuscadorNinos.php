<?php

namespace App\Http\Livewire;

use DateTime;
use Carbon\Carbon;
use Livewire\Component;
use App\Models\Childrens;
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
        $ninos = Childrens::when($this->termino, function($query){
            $query->where('name','LIKE', '%'.$this->termino.'%');
        })->when($this->termino, function($query){
            $query->orWhere('lastname','LIKE', '%'.$this->termino.'%');
        })->when($this->termino, function($query){
            $query->orWhere('birthdate','LIKE', '%'.$this->termino.'%');
        })->orderBy('name', 'asc')
        ->orderBy('lastname', 'asc')
        ->paginate(50);

        foreach($ninos as $nino)
        {
            if(!empty($nino->birthdate)){
                $nino->birthdate = $nino->birthdate ? \Carbon\Carbon::parse($nino->birthdate) : null;
                $nino['anios'] = $this->obtener_edad_segun_fecha($nino->birthdate);
            }

            //Asocio el niño con su responsable
            $responsible = DB::table('childrens_responsible')->where('children_id', $nino->id)->first();
            if($responsible){
                $contentResponsible = DB::table('users')->where('id', $responsible->user_id)->first();
                $nino['responsable'] = $contentResponsible->name . ' ' . $contentResponsible->lastname;
                $nino['phone_responsable'] = $contentResponsible->phone;
                $nino['responsable_id'] = $responsible->user_id;
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
