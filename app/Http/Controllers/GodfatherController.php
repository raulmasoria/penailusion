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

}
