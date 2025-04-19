<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Adress;
use App\Models\Antiquity;
use App\Models\Childrens;
use App\Models\Godfather;
use App\Models\Childrens_antiquities_old;
use App\Models\Childrens_antiquities;
use App\Models\Childrens_responsible;
use App\Models\Childrens_godfathers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\DataTables\ChildrensDataTable;
use App\Imports\ChildrensImport;
use App\Imports\AntiquitiesChildrensImport;
use App\Exports\ChildrensExport;

class ChildrenController extends Controller
{
    public function index(){
        return view('children.niños');
    }

    //obtener datos de un niño siendo junta directiva
    public function edit(Childrens $nino)
    {
        $godfathers = '';
        $godfather1 = '';
        $godfather2 = '';

        $antiquity = Childrens_antiquities::where('children_id', $nino->id)->get();
        $responsible_id = Childrens_responsible::where('children_id', $nino->id)->get();
        $user_responsible = User::where('id', $responsible_id[0]->user_id)->get();
        $users = User::get();

        //¿Quien le ha apadrinado?
        if (Childrens_godfathers::where('children_new', $nino->id)->exists()) {
            $godfathers = Childrens_godfathers::where('children_new', $nino->id)->firstOrFail();
            $godfather1 = User::where('id',$godfathers->user_godfather_1)->firstOrFail();
            $godfather2 = User::where('id',$godfathers->user_godfather_2)->firstOrFail();
        }

        return view('children.edit',[
            "nino" => $nino,
            "antiquitys" => $antiquity,
            "responsible" => $user_responsible,
            "users" => $users,
            "godfathers" => $godfathers,
            "godfather1" => $godfather1,
            "godfather2" => $godfather2,
        ]);
    }

    //editar un niño siendo junta directiva
    public function update(Request $request, Childrens $nino)
    {
        $request->validate([
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],
            'fecha' => ['date'],
        ]);

        $nino->name = $request->name;
        $nino->lastname = $request->lastname;
        $nino->birthdate = $request->fecha;
        $nino->save();

        //guardo responsable y borro el anterior si tuviera.
        $responsible_id = $request->changeeResponsible;
        $responsible = Childrens_responsible::where('children_id', $nino->id)->first();
        if ($responsible != null) {
            $responsible->delete();

            $responsible = new Childrens_responsible;
            $responsible->children_id = $nino->id;
            $responsible->user_id = $responsible_id;
            $responsible->save();
        } else {
            $responsible = new Childrens_responsible;
            $responsible->children_id = $nino->id;
            $responsible->user_id = $responsible_id;
            $responsible->save();
        }

        return Redirect::route('niños.edit',$nino)->with('status', 'nino-updated');

    }

    //página de crear un niño siendo junta directiva
    public function create()
    {
        $users = User::get();
        //Quien puede apadrinar
        $cumplenTodo = GodfatherController::getPueden();

        return view('children.new_niño',[
            "users" => $users,
            "godfathers" => $cumplenTodo
        ]);
    }

    //crear un usuario niño junta directiva
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['string', 'max:255'],
            'lastname' => ['string', 'max:255'],
            'fecha' => ['date']
        ]);

        //guardo niño
        $nino = new Childrens();
        $nino->name = $request->name;
        $nino->lastname = $request->lastname;
        $nino->birthdate = $request->fecha;
        $nino->save();

        //guardo responsable
        $responsible = new Childrens_responsible;
        $responsible->children_id = $nino->id;
        $responsible->user_id = $request->responsible_id;
        $responsible->save();

        //guardo antiguedad
        $antiquity = new Childrens_antiquities();
        $antiquity->children_id = $nino->id;
        $antiquity->year = YearHelperController::currentYear();;
        $antiquity->save();

        //Tiene padrinos?
        if(!empty($request->padrino1 && $request->padrino2)) {
            $godfather = new Childrens_godfathers;

            $godfather->children_new = $nino->id;
            $godfather->user_godfather_1 = $request->padrino1;
            $godfather->user_godfather_2 = $request->padrino2;
            $godfather->year_godfather = Carbon::now()->format('Y');

            $godfather->save();
        }

        return Redirect::route('niños.edit',$nino)->with('status', 'user-create');
    }

    //Pasar de niño a adulto
    public function pasarAdulto(Request $request, Childrens $nino){
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

        //Obtengo las antiguedades del niño y me quedo con las dos últimas si coinciden con los dos ultimos años para llevarlos a antiquities, las otras las guardo en childrens_antiquities_old
        $antiquities = Childrens_antiquities::where('children_id', $nino->id)->orderBy('year', 'desc')->get();
        foreach ($antiquities as $antiquity) {
            if ($antiquity->year == YearHelperController::lastYear() || $antiquity->year == YearHelperController::lastLastYear()) {
                $newAntiquity = new Antiquity;
                $newAntiquity->year = $antiquity->year;
                $newAntiquity->user_id = $user->id;
                $newAntiquity->save();
            } else {
                $oldAntiquity = new Childrens_antiquities_old;
                $oldAntiquity->user_id = $user->id;
                $oldAntiquity->year = $antiquity->year;
                $oldAntiquity->save();
            }
        }

        //Si tiene padrinos les muevo de tabla y borro los padrinos de la tabla de childrens_godfathers
        if(Childrens_godfathers::where('children_new', $nino->id)->exists()) {
            $godfathers = Childrens_godfathers::where('children_new', $nino->id)->firstOrFail();

            $newgodfather = new Godfather;
            $newgodfather->user_new = $user->id;
            $newgodfather->user_godfather_1 = $godfathers->user_godfather_1;
            $newgodfather->user_godfather_2 = $godfathers->user_godfather_2;
            $newgodfather->year_godfather = $godfathers->year_godfather;
            $newgodfather->save();

            DB::table('childrens_godfathers')->where('children_new', '=', $nino->id)->delete();
        }

        //Borro de children, childrenAnqtiquity y childrens_responsible
        DB::table('childrens_responsible')->where('children_id', '=', $nino->id)->delete();
        DB::table('childrens_antiquities')->where('children_id', '=', $nino->id)->delete();
        DB::table('childrens')->where('id', '=', $nino->id)->delete();

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

    //Importar usuario desde excel
    public function importChildrens(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new ChildrensImport, $request->file('file'));

        return back()->with('success', 'Niños importados correctamente.');
    }

    public function importChildrensAntiquities(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new AntiquitiesChildrensImport, $request->file('file'));

        return back()->with('success', 'Niños importados correctamente.');
    }

    //Tablas de niños con filtros (Exportar niños)
    public function rendertable(ChildrensDataTable $dataTable)
    {
        return $dataTable->render('tools.tabla_filtros_children');
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new ChildrensExport($filters), 'menores_filtrados.xlsx');
    }

}
