<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Adress;
use App\Models\Children;
use App\Models\Antiquity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Childrens_antiquities;
use Illuminate\Support\Facades\Redirect;

class ChildrenController extends Controller
{
    public function index(){
        return view('children.niños');
    }

    //obtener datos de un niño siendo junta directiva
    public function edit(Children $nino)
    {          

        $antiquity = Childrens_antiquities::where('children_id', $nino->id)->get();       
    
        return view('children.edit',[
            "nino" => $nino,
            "antiquitys" => $antiquity,
        ]);
    }

    //editar un niño siendo junta directiva
    public function update(Request $request, Children $nino)
    {   
        $request->validate([
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],            
            'fecha' => ['date'],
            'responsible' => ['string', 'max:255'],
            'phone_responsible' => ['Numeric'],
        ]);

        $nino->name = $request->name;
        $nino->lastname = $request->lastname;
        $nino->birthdate = $request->fecha;
        $nino->responsible = $request->responsible;
        $nino->phone_responsible = $request->phone_responsible;   
        
        $nino->save();

        return Redirect::route('niños.edit',$nino)->with('status', 'nino-updated');
        
    }

    //página de crear un niño siendo junta directiva
    public function create()
    {        
        return view('children.new_niño');        
    }

    //crear un usuario niño junta directiva
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],            
            'fecha' => ['date'],
            'responsible' => ['string', 'max:255'],
            'phone_responsible' => ['Numeric'],         
        ]);

        //guardo usuario
        $nino = new Children();              
 
        $nino->name = $request->name;
        $nino->lastname = $request->lastname;
        $nino->birthdate = $request->fecha;
        $nino->responsible = $request->responsible;
        $nino->phone_responsible = $request->phone_responsible;  

        $nino->save();              

        //guardo antiguedad
        $antiquity = new Childrens_antiquities();

        $antiquity->children_id = $nino->id;
        $antiquity->year = Carbon::now()->format('Y');

        $antiquity->save();        

        return Redirect::route('niños.edit',$nino)->with('status', 'user-create');
    }

    //Pasar de niño a adulto
    public function pasarAdulto(Request $request, Children $nino){
        //Solicito unos datos como email, telefono, dirección 
        $request->validate([                     
            'phone' => ['Numeric'],
            'nif' => ['string', 'max:10'],
            'email' => ['email', 'max:255', 'unique:users'],
            'via' => ['string', 'max:255'],
            'direccion' => ['string', 'max:255'],
            'piso' => ['string', 'max:255'],
            'cp' => ['Numeric'],
            'ciudad' => ['string', 'max:255'],
            'provincia' => ['string', 'max:255']
        ]);

        //Los doy de alta en users, address y antiquity        
        $user = new User;              
 
        $user->name = $nino->name;
        $user->lastname = $nino->lastname;
        $user->phone = $request->phone;
        $user->nif = $request->nif;
        $user->password = Hash::make($request->nif, ['rounds' => 10]);
        $user->carta = '0';
        $user->email = $request->email; 
        $user->rol = '0';
        $user->childrentoadult = '1';

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

        //Borro de children y childrenAnqtiquity

        DB::table('childrens')->where('id', '=', $nino->id)->delete();
        DB::table('childrens_antiquities')->where('id', '=', $nino->id)->delete();

        return Redirect::route('user.edit',$user)->with('status', 'nino-adult');

    }

    //página de crear un niño siendo un padre (publica)
    public function createbyfathers()
    {        
        return view('children.new_niño_fathers');        
    }

    //crear un usuario niño siendo un padre (publica)
    public function storebyfathers(Request $request)
    {
        $request->validate([
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],            
            'fecha' => ['date'],
            'responsible' => ['string', 'max:255'],
            'phone_responsible' => ['Numeric'],         
        ]);

        //guardo usuario
        $nino = new Children();              
 
        $nino->name = $request->name;
        $nino->lastname = $request->lastname;
        $nino->birthdate = $request->fecha;
        $nino->responsible = $request->responsible;
        $nino->phone_responsible = $request->phone_responsible;  

        $nino->save();              

        //guardo antiguedad
        $antiquity = new Childrens_antiquities();

        $antiquity->children_id = $nino->id;
        $antiquity->year = Carbon::now()->format('Y');

        $antiquity->save();        

        return Redirect::route('niñosfathers.create')->with('status', 'children-create');
    }

}
