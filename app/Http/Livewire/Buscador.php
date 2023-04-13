<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Antiquity;
use App\Models\Permanence;
use Illuminate\Support\Facades\DB;

class Buscador extends Component
{   
    public $termino;

    protected $listeners = ['terminosBusqueda' => 'buscar', 'pagarCuota', 'mantenimiento' => 'mantenimiento'];    

    public function buscar($termino)
    {   
        $this->termino = $termino;        
    }

    public function render()
    {
        $socios = User::when($this->termino, function($query){
            $query->where('name','LIKE', '%'.$this->termino.'%');
        })->when($this->termino, function($query){
            $query->orWhere('lastname','LIKE', '%'.$this->termino.'%');
        })->when($this->termino, function($query){
            $query->orWhere('email','LIKE', '%'.$this->termino.'%');
        })->when($this->termino, function($query){
            $query->orWhere('phone','LIKE', '%'.$this->termino.'%');
        })->when($this->termino, function($query){
            $query->orWhere('nif','LIKE', '%'.$this->termino.'%');
        })->orderBy('name', 'asc')
        ->paginate(50);

        $year = Carbon::now()->format('Y');
        $antiquitys = Antiquity::select('user_id')->where('year',$year)->get();
        $permanences = Permanence::select('user_id')->where('year_permanence',$year)->get();

        return view('livewire.buscador',[
            "socios" => $socios,
            "antiquitys" => $antiquitys,
            "permanences" => $permanences
        ]);
    }

    //pagar cuota anual
    public function pagarCuota($id)
    {
        DB::table('antiquities')->insert([
            'year' => Carbon::now()->format('Y'),
            'user_id' => $id,
            'created_at' => Carbon::now()
        ]);
    }

    //pagar cuota de mantenimiento
    public function mantenimiento($id)
    {
        DB::table('permanences')->insert([
            'year_permanence' => Carbon::now()->format('Y'),
            'user_id' => $id,
            'created_at' => Carbon::now()
        ]);
    }
  

    
}
