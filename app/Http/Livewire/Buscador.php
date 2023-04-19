<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Adress;
use Livewire\Component;
use App\Models\Antiquity;
use App\Models\Permanence;
use App\Mail\CuotaSocioEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\CuotaMantenimientoEmail;

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

        //Obtener si ya ha pagado cuota o permanencia este año
        $year = Carbon::now()->format('Y');
        $antiquitys = Antiquity::select('user_id')->where('year',$year)->get();
        $permanences = Permanence::select('user_id')->where('year_permanence',$year)->get();

        //Obtener las permanencias de años anteriores para ver si puede segir apadrinando, puesto que solo se pueden dos años seguidos
        $year1 = "2019";
        $year2 = "2022";

        $noPermanence = DB::select('SELECT user_id
            FROM (
                SELECT * 
                FROM permanences
                WHERE year_permanence = :year1
                
                UNION 
            
                SELECT * 
                FROM permanences
                WHERE year_permanence = :year2
            ) permanences
            group by user_id
            HAVING  count(user_id) = 2', ['year1' => $year1,'year2' => $year2]);
    

        return view('livewire.buscador',[
            "socios" => $socios,
            "antiquitys" => $antiquitys,
            "permanences" => $permanences,
            'noPermanences' => $noPermanence
        ]);
    }

    //pagar cuota anual
    public function pagarCuota($id)
    {
        $user = User::where('id',$id)->firstOrFail();
        $address = Adress::where('user_id',$id)->firstOrFail();
        if(!empty($user->email)){
            Mail::to($user)->send(new CuotaSocioEmail($user,$address));
        }
        

        DB::table('antiquities')->insert([
            'year' => Carbon::now()->format('Y'),
            'user_id' => $id,
            'created_at' => Carbon::now()
        ]);
    }

    //pagar cuota de mantenimiento
    public function mantenimiento($id)
    {
        $user = User::where('id',$id)->firstOrFail();
        $address = Adress::where('user_id',$id)->firstOrFail();
        if(!empty($user->email)){
            Mail::to($user)->send(new CuotaMantenimientoEmail($user,$address) );
        }    
        DB::table('permanences')->insert([
            'year_permanence' => Carbon::now()->format('Y'),
            'user_id' => $id,
            'created_at' => Carbon::now()
        ]);
    }
  

    
}
