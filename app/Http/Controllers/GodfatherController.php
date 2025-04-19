<?php

namespace App\Http\Controllers;

use App\DataTables\GodfatherDataTable;
use App\Exports\GodfatherExport;
use Illuminate\Http\Request;
use App\Imports\GodFathersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class GodfatherController extends Controller
{
    //Importar padrinos xlsx
    public function importGodfather(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new GodFathersImport, $request->file('file'));

        return back()->with('success', 'Padrinos importadas correctamente.');
    }

    //Tabla de padrinos exportable
    public function rendertable(GodfatherDataTable $dataTable)
    {
        $currentYear = YearHelperController::currentYear();
        $lastYear = YearHelperController::lastYear();
        $lastLastYear = YearHelperController::lastLastYear();
        $lastLastLastYear = YearHelperController::lastLastLastYear();
        $lastLastLastLastYear = YearHelperController::lastLastLastLastYear();
        return $dataTable->render('tools.listado_apadrinar', compact('currentYear', 'lastYear', 'lastLastYear', 'lastLastLastYear', 'lastLastLastLastYear'));
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->all();
        return Excel::download(new GodfatherExport($filters), 'padrinos.xlsx');
    }

    public static function getPueden() {
        //Calculo los socios que pueden apadrinar para mostrarlos en los select de nuevo socio
        //Socios que han pagado cuota completa los ultimos 4 años

        $godfathers = DB::table('antiquities')
            ->join('users', 'users.id', '=', 'antiquities.user_id')
            ->selectRaw('count(users.id), users.id, users.name, users.lastname')
            ->where('year', '=', YearHelperController::currentYear())
            ->orWhere('year', '=', YearHelperController::lastYear())
            ->orWhere('year', '=', YearHelperController::lastLastYear())
            ->orWhere('year', '=', YearHelperController::lastLastLastYear())
            ->orWhere('year', '=', YearHelperController::lastLastLastLastYear())
            ->groupBy('users.id', 'users.name', 'users.lastname')
            ->havingRaw('count(users.id) = ?', [5])
            ->orderby('users.lastname')
            ->get();

        $arrayGodfathers = array();
        foreach($godfathers as $row_god){
            $arrayGodfathers[$row_god->id] = $row_god->name .' ' . $row_god->lastname;
        }

        //Socios que han pagado permanencia alguno de los ultimos 4 años
        $permanence = DB::table('users')
            ->join('permanences', 'permanences.user_id', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.lastname')
            ->where('permanences.year_permanence', '=', YearHelperController::currentYear())
            ->orWhere('permanences.year_permanence', '=', YearHelperController::lastYear())
            ->orWhere('permanences.year_permanence', '=', YearHelperController::lastLastYear())
            ->orWhere('permanences.year_permanence', '=', YearHelperController::lastLastLastYear())
            ->orWhere('permanences.year_permanence', '=', YearHelperController::lastLastLastLastYear())
            ->groupBy('users.id', 'users.name', 'users.lastname')
            ->orderby('users.lastname')
            ->get();

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
            ->where('godfathers.year_godfather', '=', YearHelperController::currentYear())
            ->orWhere('godfathers.year_godfather', '=', YearHelperController::lastYear())
            ->orWhere('godfathers.year_godfather', '=', YearHelperController::lastLastYear())
            ->groupBy('users.id', 'users.name', 'users.lastname')
            ->orderby('users.lastname')
            ->get();

        $arrayGodfather_1 = array();
        foreach($godfather_1 as $row_father_1){
            $arrayGodfather_1[$row_father_1->id] = $row_father_1->name .' ' . $row_father_1->lastname;
        }

        $godfather_2 = DB::table('users')
            ->join('godfathers', 'godfathers.user_godfather_2', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.lastname')
            ->where('godfathers.year_godfather', '=', YearHelperController::currentYear())
            ->orWhere('godfathers.year_godfather', '=', YearHelperController::lastYear())
            ->orWhere('godfathers.year_godfather', '=', YearHelperController::lastLastYear())
            ->groupBy('users.id', 'users.name', 'users.lastname')
            ->orderby('users.lastname')
            ->get();

        $arrayGodfather_2 = array();
        foreach($godfather_2 as $row_father_2){
            $arrayGodfather_2[$row_father_2->id] = $row_father_2->name .' ' . $row_father_2->lastname;
        }

        //Obtengo los socios que han apadrinado a menores en los últimos 2 años
        $godfather_childrens_1 = DB::table('users')
            ->join('childrens_godfathers', 'childrens_godfathers.user_godfather_1', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.lastname')
            ->where('childrens_godfathers.year_godfather', '=', YearHelperController::currentYear())
            ->orWhere('childrens_godfathers.year_godfather', '=', YearHelperController::lastYear())
            ->orWhere('childrens_godfathers.year_godfather', '=', YearHelperController::lastLastYear())
            ->groupBy('users.id', 'users.name', 'users.lastname')
            ->orderby('users.lastname')
            ->get();

        $arrayChildrenGodfather_1 = array();
        foreach($godfather_childrens_1 as $row_father_1){
            $arrayChildrenGodfather_1[$row_father_1->id] = $row_father_1->name .' ' . $row_father_1->lastname;
        }

        $godfather_childrens_2 = DB::table('users')
            ->join('childrens_godfathers', 'childrens_godfathers.user_godfather_2', '=', 'users.id')
            ->selectRaw('users.id, users.name, users.lastname')
            ->where('childrens_godfathers.year_godfather', '=', YearHelperController::currentYear())
            ->orWhere('childrens_godfathers.year_godfather', '=', YearHelperController::lastYear())
            ->orWhere('childrens_godfathers.year_godfather', '=', YearHelperController::lastLastYear())
            ->groupBy('users.id', 'users.name', 'users.lastname')
            ->orderby('users.lastname')
            ->get();

        $arrayChildrenGodfather_2 = array();
        foreach($godfather_childrens_2 as $row_father_2){
            $arrayChildrenGodfather_2[$row_father_2->id] = $row_father_2->name .' ' . $row_father_2->lastname;
        }

        $godfather = $arrayGodfather_1+$arrayGodfather_2+$arrayChildrenGodfather_1+$arrayChildrenGodfather_2;
        ksort($godfather);


        //Descarto los que han apadrinado los dos ultimos años, del listado que cumplen con las cuotas
        $cumplenTodo = array_diff_key($cuotasOk, $godfather);

        return $cumplenTodo;

    }

}
