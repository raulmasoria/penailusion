<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Adress;
use App\Models\Antiquity;
use App\Models\Childrens_antiquities_old;
use App\Models\Godfather;
use App\Models\Childrens_godfathers;
use App\Models\Childrens_responsible;
use App\Models\Childrens;
use App\Models\Permanence;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\Models\IntolerancesUser;
use App\DataTables\UsersDataTable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
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
        $all_childrens_godfather = array();

        $adress = Adress::where('user_id', $user->id)->firstOrFail();
        $antiquity = Antiquity::where('user_id', $user->id)->get();
        $permanence = Permanence::where('user_id', $user->id)->get();
        $antiquity_children_old = Childrens_antiquities_old::where('user_id', $user->id)->get();
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

        if (Childrens_godfathers::where('user_godfather_1', $user->id)->orWhere('user_godfather_2', $user->id)->exists()) {
            $the_childrens_godfathers = Childrens_godfathers::where('user_godfather_1', $user->id)->orWhere('user_godfather_2', $user->id)->get();
            foreach($the_childrens_godfathers as $the_godfather){
                $all_childrens_godfather[$the_godfather->year_godfather] = Childrens::where('id',$the_godfather->children_new)->firstOrFail();
            }
        }

        //¿Es responsable de algún menor?
        if( Childrens_responsible::where('user_id', $user->id)->exists()) {
            $childrens_responsible = Childrens_responsible::where('user_id', $user->id)->get();

            foreach ($childrens_responsible as $cr) {
                $children = Childrens::where('id', $cr->children_id)->firstOrFail();
                $children->years = YearHelperController::obtener_edad_segun_fecha($children->birthdate);
                $childrens[$children->id] = $children;
            }
        } else {
            $childrens = array();
        }

        //junto la antiguedad con la permanencia
        $anos = array();
        foreach ($antiquity as $anti){
            $anos[$anti->year] = "Cuota completa";
        }
        foreach ($permanence as $perman){
            $anos[$perman->year_permanence] = "Cuota de permanencia";
        }
        foreach ($antiquity_children_old as $anti){
            $anos[$anti->year] = "Cuota de niño";
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
            "all_childrens_godfather" => $all_childrens_godfather,
            "intolerances" => $allIntolerances,
            "childrens_responsible" => $childrens,
        ]);
    }

    //editar un usuario siendo junta directiva
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],
        ]);

        $user->name = $request->name;
        $user->lastname = $request->lastname;
        $user->phone = $request->phone;
        $user->email = $request->email;
        //$user->RGPD = $request->RGPD;
        if($request->RGPD == 'RGPD') {
            $user->RGPD = 1;
        } else {
            $user->RGPD = 0;
        }

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
        $cumplenTodo = GodfatherController::getPueden();

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
            /*'via' => ['string', 'max:255'],
            'direccion' => ['string', 'max:255'],
            'piso' => ['string', 'max:255'],
            'cp' => ['Numeric'],
            'ciudad' => ['string', 'max:255'],
            'provincia' => ['string', 'max:255'],*/
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
            //Mail::to($user)->send(new CuotaSocioEmail($user,$adress) );
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
            /*'via' => ['string', 'max:255'],
            'direccion' => ['string', 'max:255'],
            'piso' => ['string', 'max:255'],
            'cp' => ['Numeric'],
            'ciudad' => ['string', 'max:255'],
            'provincia' => ['string', 'max:255'],*/
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
        $antiquity->year = YearHelperController::currentYear();

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

        //guardo los padrinos que es el usuario de directiva
        $godfather = new Godfather;

        $godfather->user_new = $user->id;
        $godfather->user_godfather_1 = 583; //ID del usuario de directiva
        $godfather->user_godfather_2 = 583; //ID del usuario de directiva
        $godfather->year_godfather = Carbon::now()->format('Y');

        $godfather->save();

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

    //Importar usuario desde excel
    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return back()->with('success', 'Usuarios importados correctamente.');
    }

    //Tablas de usuarios con filtros (Exportar adultos)
    public function rendertable(UsersDataTable $dataTable)
    {
        return $dataTable->render('tools.tabla_filtros');
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new UsersExport($filters), 'usuarios_filtrados.xlsx');
    }


}
