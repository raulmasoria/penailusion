<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Adress;
use App\Models\Antiquity;
use App\Models\Godfather;
use App\Models\Permanence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
        
    public function index()
    {
        return view('socios');
    }

    //obtener datos de un usuario siendo junta directiva
    public function edit(User $user)
    {  
        $godfathers = '';
        $godfather1 = '';
        $godfather2 = '';
        $all_godfather = '';
        
        $adress = Adress::where('user_id', $user->id)->firstOrFail();
        $antiquity = Antiquity::where('user_id', $user->id)->get();
        $permanence = Permanence::where('user_id', $user->id)->get();

        //¿Quien le ha apadrinado?
        if (Godfather::where('user_new', $user->id)->exists()) {
            $godfathers = Godfather::where('user_new', $user->id)->firstOrFail();
            $godfather1 = User::where('id',$godfathers->user_godfather_1)->firstOrFail();
            $godfather2 = User::where('id',$godfathers->user_godfather_2)->firstOrFail();
        }    
        
        //¿A quien ha apadrinado?
        if (Godfather::where('user_godfather_1', $user->id)->orWhere('user_godfather_2', $user->id)->exists()) {
            $the_godfathers = Godfather::where('user_godfather_1', $user->id)->orWhere('user_godfather_2', $user->id)->get();
            foreach($the_godfathers as $the_godfather){
                $all_godfather[$the_godfather->year_godfather] = User::where('id',$the_godfather->user_new)->firstOrFail();
            }            
        }   
         
        //junto la antiguedad con la permanencia
        $anos = array();            
        foreach ($antiquity as $anti){
            $anos[$anti->year] = "Cuota completa";
        }
        foreach ($permanence as $perman){
            $anos[$perman->year_permanence] = "Cuota de permanencia"; 
        }        
        ksort($anos);

        return view('user.edit',[
            "user" => $user,
            "adress" => $adress,
            "anos" => $anos,
            "godfathers" => $godfathers,
            "godfather1" => $godfather1,
            "godfather2" => $godfather2,
            "all_godfather" => $all_godfather
        ]);
    }

    //editar un usuario siendo junta directiva
    public function update(Request $request, User $user)
    {   
        $request->validate([
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],            
            'phone' => ['Numeric'],
            'nif' => ['string', 'max:10'],
            'carta' => ['Numeric'],
            'email' => ['email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->nif = $request->nif;
        $user->carta = $request->carta;
        $user->email = $request->email;        
        
        $user->save();

        return Redirect::route('user.edit',$user)->with('status', 'user-updated');
        
    }

    //editar dirección de un usuario siendo junta directiva
    public function adress(Request $request, User $user)
    {   
        $request->validate([
            'via' => ['string', 'max:255'],
            'direccion' => ['string', 'max:255'],
            'piso' => ['string', 'max:255'],
            'cp' => ['Numeric'],
            'ciudad' => ['string', 'max:255'],
            'provincia' => ['string', 'max:255'],
        ]);

        $adress = $user->adress;

        $adress->via = $request->via;
        $adress->direccion = $request->direccion;
        $adress->piso = $request->piso;
        $adress->cp = $request->cp;
        $adress->ciudad = $request->ciudad;
        $adress->provincia = $request->provincia;
        
        $adress->save();

        return Redirect::route('user.edit',$user)->with('status', 'adress-updated');
        
    }

    //página de crear un usuario siendo junta directiva
    public function create()
    {        
        //recojo los socios que pueden apadrinar para mostrarlos en los select de nuevo socio
        $godfathers = DB::table('antiquities')
            ->join('users', 'users.id', '=', 'antiquities.user_id')
            ->selectRaw('count(users.id), users.id, users.name, users.lastname')
            ->where('year', '=', 2019)
            ->orWhere('year', '=', 2018)
            ->groupBy('users.id')
            ->havingRaw('count(users.id) = ?', [2])
            ->orderby('users.name')
            ->get();        

        return view('new-user.new_socio',[
            "godfathers" => $godfathers
        ]);        
    }

    //crear un usuario siendo junta directiva
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],            
            'phone' => ['Numeric'],
            'nif' => ['string', 'max:10'],
            'carta' => ['Numeric'],
            'email' => ['email', 'max:255', 'unique:users'],
            'via' => ['string', 'max:255'],
            'direccion' => ['string', 'max:255'],
            'piso' => ['string', 'max:255'],
            'cp' => ['Numeric'],
            'ciudad' => ['string', 'max:255'],
            'provincia' => ['string', 'max:255'],
            'padrino1' => ['Numeric'],
            'padrino2' => ['Numeric'],
        ]);

        //guardo usuario
        $user = new User;              
 
        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->nif = $request->nif;
        $user->carta = $request->carta;
        $user->email = $request->email; 

        $user->save();      

        //guardo dirección
        $adress = new Adress;  

        $adress->user_id = $user->id;
        $adress->via = $request->via;
        $adress->direccion = $request->direccion;
        $adress->piso = $request->piso;
        $adress->cp = $request->cp;
        $adress->ciudad = $request->ciudad;
        $adress->provincia = $request->provincia; 
        
        $adress->save();

        //guardo antiguedad
        $antiquity = new Antiquity;

        $antiquity->user_id = $user->id;
        $antiquity->year = Carbon::now()->format('Y');

        $antiquity->save();

        //guardo los padrinos del usuario
        $godfather = new Godfather;

        $godfather->user_new = $user->id;
        $godfather->user_godfather_1 = $request->padrino1;
        $godfather->user_godfather_2 = $request->padrino2;
        $godfather->year_godfather = Carbon::now()->format('Y');

        $godfather->save();

        return Redirect::route('user.edit',$user)->with('status', 'user-create');
    }



    
}
