<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Adress;
use App\Models\Antiquity;
use App\Models\Godfather;
use App\Models\Permanence;
use Illuminate\Http\Request;
use App\Mail\CuotaSocioEmail;
use App\Models\IntolerancesUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        $all_godfather = array();
        
        $adress = Adress::where('user_id', $user->id)->firstOrFail();
        $antiquity = Antiquity::where('user_id', $user->id)->get();
        $permanence = Permanence::where('user_id', $user->id)->get();
        $intolerances = DB::table('intolerances')
        ->join('intolerances_users', 'intolerances.id', '=', 'intolerances_users.id_intolerance')
        ->join('users', 'users.id', '=', 'intolerances_users.id_user')
        ->select('intolerances.id','intolerances.name') 
        ->where('intolerances_users.id_user', $user->id)       
        ->get();   

        $allIntolerances = array();
        foreach($intolerances as $intolerancesrow){
            $allIntolerances[$intolerancesrow->name] = $intolerancesrow->name;            
        }

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
            "all_godfather" => $all_godfather,
            "intolerances" => $allIntolerances
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
            'email' => ['email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->nif = $request->nif;
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

    //editar intolerancias de un usuario siendo junta directiva
    public function intolerances(Request $request, User $user)
    {   
                
        if($request->lactosa == 'lactosa') {            
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = $user->id;
            $intolerances->id_intolerance = 1;
            $intolerances->save();
        } else {
            $tengoLactosa = DB::table('intolerances_users')
            ->select('id')
            ->where('id_user', '=', $user->id)
            ->where('id_intolerance', '=', 1)
            ->get();

            if(count($tengoLactosa) >= 1){
                DB::table('intolerances_users')->where('id_user', '=', $user->id)->where('id_intolerance', '=', 1)->delete();
            }
        }
         
        if($request->gluten == 'gluten') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = $user->id;
            $intolerances->id_intolerance = 2;
            $intolerances->save();
        } else {
            $tengoGluten = DB::table('intolerances_users')
            ->select('id')
            ->where('id_user', '=', $user->id)
            ->where('id_intolerance', '=', 2)
            ->get();

            if(count($tengoGluten) >= 1){
                DB::table('intolerances_users')->where('id_user', '=', $user->id)->where('id_intolerance', '=', 2)->delete();
            }
        }          
        
        if($request->celiaco == 'celiaco') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = $user->id;
            $intolerances->id_intolerance = 3;
            $intolerances->save();
        } else {
            $tengoCeliaco = DB::table('intolerances_users')
            ->select('id')
            ->where('id_user', '=', $user->id)
            ->where('id_intolerance', '=', 3)
            ->get();

            if(count($tengoCeliaco) >= 1){
                DB::table('intolerances_users')->where('id_user', '=', $user->id)->where('id_intolerance', '=', 3)->delete();
            }
        }
        
        if($request->fructosa == 'fructosa') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = $user->id;
            $intolerances->id_intolerance = 4;
            $intolerances->save();
        } else {
            $tengoFructosa = DB::table('intolerances_users')
            ->select('id')
            ->where('id_user', '=', $user->id)
            ->where('id_intolerance', '=', 4)
            ->get();

            if(count($tengoFructosa) >= 1){
                DB::table('intolerances_users')->where('id_user', '=', $user->id)->where('id_intolerance', '=', 4)->delete();
            }
        }
          
        if($request->huevo == 'huevo') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = $user->id;
            $intolerances->id_intolerance = 5;
            $intolerances->save();
        } else {
            $tengoHuevo = DB::table('intolerances_users')
            ->select('id')
            ->where('id_user', '=', $user->id)
            ->where('id_intolerance', '=', 5)
            ->get();

            if(count($tengoHuevo) >= 1){
                DB::table('intolerances_users')->where('id_user', '=', $user->id)->where('id_intolerance', '=', 5)->delete();
            }
        }     
        

        return Redirect::route('user.edit',$user)->with('status', 'intolerances-updated');
        
    }

    //página de crear un nuevo usuario siendo junta directiva
    public function create()
    {        
        //Calculo los socios que pueden apadrinar para mostrarlos en los select de nuevo socio

        //Socios que han pagado cuota completa los ultimos 4 años
        $godfathers = DB::table('antiquities')
            ->join('users', 'users.id', '=', 'antiquities.user_id')
            ->selectRaw('count(users.id), users.id, users.name, users.lastname')
            ->where('year', '=', 2022)
            ->orWhere('year', '=', 2019)
            ->orWhere('year', '=', 2018)
            ->orWhere('year', '=', 2024)
            ->orWhere('year', '=', 2023)
            ->groupBy('users.id')
            ->havingRaw('count(users.id) = ?', [5])
            ->orderby('users.lastname')
            ->get(); 
            
        /*select count(users.id), users.id, users.name, users.lastname 
        from `antiquities` 
        inner join `users` on `users`.`id` = `antiquities`.`user_id` 
        where `year` = 2022 or `year` = 2019 or `year` = 2018 or `year` = 2017 
        group by `users`.`id` 
        having count(users.id) = 4
        order by `users`.`name` asc*/
            
        $arrayGodfathers = array();
        foreach($godfathers as $row_god){
            $arrayGodfathers[$row_god->id] = $row_god->name .' ' . $row_god->lastname;
        }    

        //Socios que han pagado permanencia alguno de los ultimos 4 años
        $permanence = DB::table('users')    
            ->join('permanences', 'permanences.user_id', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.lastname')
            ->where('permanences.year_permanence', '=', 2022)
            ->orWhere('permanences.year_permanence', '=', 2019)
            ->orWhere('permanences.year_permanence', '=', 2018)
            ->orWhere('permanences.year_permanence', '=', 2024)
            ->orWhere('permanences.year_permanence', '=', 2023)
            ->groupBy('users.id')
            ->orderby('users.lastname')
            ->get();  

        /*SELECT users.id, users.name, users.lastname
        FROM users
        JOIN permanences ON permanences.user_id = users.id
        WHERE permanences.year_permanence = 2022
        OR permanences.year_permanence = 2019
        OR permanences.year_permanence = 2018
        OR permanences.year_permanence = 2017
        group by users.id*/

        $arrayPermanence = array();
        foreach($permanence as $row_perman){
            $arrayPermanence[$row_perman->id] = $row_perman->name .' ' . $row_perman->lastname;
        }             

        //Agrupo los socios que podrian apadrinar, comparando los que han pagado todas las cuotas con los que han pagado alguna vez mantenimiento y quito estos últimos del array que si pueden.
        $cuotasOk = array_diff_key($arrayGodfathers, $arrayPermanence);
        

        //Obtengo los socios que han apadrinado en los últimos 2 años
        $godfather_1 = DB::table('users')    
            ->join('godfathers', 'godfathers.user_godfather_1', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.lastname')
            ->where('godfathers.year_godfather', '=', 2024)
            ->orWhere('godfathers.year_godfather', '=', 2023)
            ->orWhere('godfathers.year_godfather', '=', 2022)
            ->groupBy('users.id')
            ->orderby('users.lastname')
            ->get();  
        
        $arrayGodfather_1 = array();
        foreach($godfather_1 as $row_father_1){
            $arrayGodfather_1[$row_father_1->id] = $row_father_1->name .' ' . $row_father_1->lastname;
        }        

        $godfather_2 = DB::table('users')    
            ->join('godfathers', 'godfathers.user_godfather_2', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.lastname')
            ->where('godfathers.year_godfather', '=', 2024)
            ->orWhere('godfathers.year_godfather', '=', 2023)
            ->orWhere('godfathers.year_godfather', '=', 2022)
            ->groupBy('users.id')
            ->orderby('users.lastname')
            ->get();    
        /*
        SELECT users.id, users.name, users.lastname
        FROM users
        JOIN godfathers ON godfathers.user_godfather_1 = users.id
        WHERE godfathers.year_godfather = 2023
        OR godfathers.year_godfather = 2022
        OR godfathers.year_godfather = 2019
        group by users.id*/
            
        $arrayGodfather_2 = array();
        foreach($godfather_2 as $row_father_2){
            $arrayGodfather_2[$row_father_2->id] = $row_father_2->name .' ' . $row_father_2->lastname;
        } 

        $godfather = $arrayGodfather_1+$arrayGodfather_2;
        ksort($godfather);

        //Descarto los que han apadrinado los dos ultimos años, del listado que cumplen con las cuotas
        $cumplenTodo = array_diff_key($cuotasOk, $godfather);
        
        return view('new-user.new_socio',[
            "godfathers" => $cumplenTodo
        ]);        
    }

    //crear un nuevo usuario siendo junta directiva
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],            
            'phone' => ['Numeric'],
            'nif' => ['string', 'max:10'],
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

        //guardo intolerancias
        if($request->lactosa == 'lactosa') {            
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = $user->id;
            $intolerances->id_intolerance = 1;
            $intolerances->save();
        } 
         
        if($request->gluten == 'gluten') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = $user->id;
            $intolerances->id_intolerance = 2;
            $intolerances->save();
        }        
        
        if($request->celiaco == 'celiaco') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = $user->id;
            $intolerances->id_intolerance = 3;
            $intolerances->save();
        } 
        
        if($request->fructosa == 'fructosa') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = $user->id;
            $intolerances->id_intolerance = 4;
            $intolerances->save();
        } 
          
        if($request->huevo == 'huevo') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = $user->id;
            $intolerances->id_intolerance = 5;
            $intolerances->save();
        }
          
        //guardo los padrinos del usuario
        $godfather = new Godfather;

        $godfather->user_new = $user->id;
        $godfather->user_godfather_1 = $request->padrino1;
        $godfather->user_godfather_2 = $request->padrino2;
        $godfather->year_godfather = Carbon::now()->format('Y');

        $godfather->save();

        if(!empty($user->email)){
            Mail::to($user)->send(new CuotaSocioEmail($user,$adress) );
        } 

        return Redirect::route('user.edit',$user)->with('status', 'user-create');
    }

     //página de crear un nuevo usuario siendo junta directiva sin necesidad de padrinos
     public function create_admin()
     {                         
         return view('new-user.new_socio_admin',[]);        
     }
 
     //crear un nuevo usuario siendo junta directiva
     public function store_admin(Request $request)
     {
         $request->validate([
             'name' => ['string', 'max:255'],
             'lastname' => ['string', 'max:255'],            
             'phone' => ['Numeric'],
             'nif' => ['string', 'max:10'],
             'email' => ['email', 'max:255', 'unique:users'],
             'via' => ['string', 'max:255'],
             'direccion' => ['string', 'max:255'],
             'piso' => ['string', 'max:255'],
             'cp' => ['Numeric'],
             'ciudad' => ['string', 'max:255'],
             'provincia' => ['string', 'max:255'],
         ]);
 
         //guardo usuario
         $user = new User;              
  
         $user->name = $request->name;
         $user->lastname = $request->lastname;
         $user->phone = $request->phone;
         $user->nif = $request->nif;
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
         foreach ($request->antiguedad as $año) {
            $antiquity = new Antiquity;

            $antiquity->user_id = $user->id;
            $antiquity->year = $año;
    
            $antiquity->save();
         }
         
 
         //guardo intolerancias
         if($request->lactosa == 'lactosa') {            
             $intolerances = new IntolerancesUser();
             $intolerances->id_user = $user->id;
             $intolerances->id_intolerance = 1;
             $intolerances->save();
         } 
          
         if($request->gluten == 'gluten') {
             $intolerances = new IntolerancesUser();
             $intolerances->id_user = $user->id;
             $intolerances->id_intolerance = 2;
             $intolerances->save();
         }        
         
         if($request->celiaco == 'celiaco') {
             $intolerances = new IntolerancesUser();
             $intolerances->id_user = $user->id;
             $intolerances->id_intolerance = 3;
             $intolerances->save();
         } 
         
         if($request->fructosa == 'fructosa') {
             $intolerances = new IntolerancesUser();
             $intolerances->id_user = $user->id;
             $intolerances->id_intolerance = 4;
             $intolerances->save();
         } 
           
         if($request->huevo == 'huevo') {
             $intolerances = new IntolerancesUser();
             $intolerances->id_user = $user->id;
             $intolerances->id_intolerance = 5;
             $intolerances->save();
         }           
         
         return Redirect::route('user.edit',$user)->with('status', 'user-create');
     }

    /*public function updatePass(){

        $users = User::all();
        foreach($users as $user)
        {
            if(!empty($user->nif)){
                $user->password = Hash::make($user->nif, ['rounds' => 10]);
            } else {
                $user->password = Hash::make('naranja', ['rounds' => 10]);
            }

            echo $user->name;
            //$user->save();
        }
        
    }*/

    //Obtener los que pueden apadrinar para sacar listado
    public function apadrinate()
    {        
        //Calculo los socios que pueden apadrinar para mostrarlos en los select de nuevo socio

        //Socios que han pagado cuota completa los ultimos 4 años
        $godfathers = DB::table('antiquities')
            ->join('users', 'users.id', '=', 'antiquities.user_id')
            ->selectRaw('count(users.id), users.id, users.name, users.lastname')
            ->where('year', '=', 2022)
            ->orWhere('year', '=', 2019)
            ->orWhere('year', '=', 2018)
            ->orWhere('year', '=', 2023)
            ->groupBy('users.id')
            ->havingRaw('count(users.id) = ?', [4])
            ->orderby('users.lastname')
            ->get(); 
            
        /*select count(users.id), users.id, users.name, users.lastname 
        from `antiquities` 
        inner join `users` on `users`.`id` = `antiquities`.`user_id` 
        where `year` = 2022 or `year` = 2019 or `year` = 2018 or `year` = 2017 
        group by `users`.`id` 
        having count(users.id) = 4
        order by `users`.`name` asc*/
            
        $arrayGodfathers = array();
        foreach($godfathers as $row_god){
            $arrayGodfathers[$row_god->id] = $row_god->name .' ' . $row_god->lastname;
        }    

        //Socios que han pagado permanencia alguno de los ultimos 4 años
        $permanence = DB::table('users')    
            ->join('permanences', 'permanences.user_id', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.lastname')
            ->where('permanences.year_permanence', '=', 2022)
            ->orWhere('permanences.year_permanence', '=', 2019)
            ->orWhere('permanences.year_permanence', '=', 2018)
            ->orWhere('permanences.year_permanence', '=', 2023)
            ->groupBy('users.id')
            ->orderby('users.lastname')
            ->get();  

        /*SELECT users.id, users.name, users.lastname
        FROM users
        JOIN permanences ON permanences.user_id = users.id
        WHERE permanences.year_permanence = 2022
        OR permanences.year_permanence = 2019
        OR permanences.year_permanence = 2018
        OR permanences.year_permanence = 2017
        group by users.id*/

        $arrayPermanence = array();
        foreach($permanence as $row_perman){
            $arrayPermanence[$row_perman->id] = $row_perman->name .' ' . $row_perman->lastname;
        }             

        //Agrupo los socios que podrian apadrinar, comparando los que han pagado todas las cuotas con los que han pagado alguna vez mantenimiento y quito estos últimos del array que si pueden.
        $cuotasOk = array_diff_key($arrayGodfathers, $arrayPermanence);
        

        //Obtengo los socios que han apadrinado en los últimos 2 años
        $godfather_1 = DB::table('users')    
            ->join('godfathers', 'godfathers.user_godfather_1', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.lastname')
            ->where('godfathers.year_godfather', '=', 2024)
            ->orWhere('godfathers.year_godfather', '=', 2023)
            ->orWhere('godfathers.year_godfather', '=', 2022)
            ->groupBy('users.id')
            ->orderby('users.lastname')
            ->get();  
        
        $arrayGodfather_1 = array();
        foreach($godfather_1 as $row_father_1){
            $arrayGodfather_1[$row_father_1->id] = $row_father_1->name .' ' . $row_father_1->lastname;
        }        

        $godfather_2 = DB::table('users')    
            ->join('godfathers', 'godfathers.user_godfather_2', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.lastname')
            ->where('godfathers.year_godfather', '=', 2024)
            ->orWhere('godfathers.year_godfather', '=', 2023)
            ->orWhere('godfathers.year_godfather', '=', 2022)
            ->groupBy('users.id')
            ->orderby('users.lastname')
            ->get();    
        /*
        SELECT users.id, users.name, users.lastname
        FROM users
        JOIN godfathers ON godfathers.user_godfather_1 = users.id
        WHERE godfathers.year_godfather = 2023
        OR godfathers.year_godfather = 2022
        OR godfathers.year_godfather = 2019
        group by users.id*/
            
        $arrayGodfather_2 = array();
        foreach($godfather_2 as $row_father_2){
            $arrayGodfather_2[$row_father_2->id] = $row_father_2->name .' ' . $row_father_2->lastname;
        } 

        $godfather = $arrayGodfather_1+$arrayGodfather_2;
        ksort($godfather);

        //Descarto los que han apadrinado los dos ultimos años, del listado que cumplen con las cuotas
        $cumplenTodo = array_diff_key($cuotasOk, $godfather);
        
        return view('listado_apadrinar',[
            "godfathers" => $cumplenTodo
        ]);        
    }

    
}
