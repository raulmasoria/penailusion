<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Children;
use Illuminate\Http\Request;
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

    //página de crear un usuario siendo junta directiva
    public function create()
    {        
        return view('children.new_niño');        
    }

    //crear un usuario siendo junta directiva
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

}
