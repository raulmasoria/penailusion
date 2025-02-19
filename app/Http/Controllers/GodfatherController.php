<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\GodFathersImport;
use Maatwebsite\Excel\Facades\Excel;

class GodfatherController extends Controller
{
    public function importGodfather(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new GodFathersImport, $request->file('file'));

        return back()->with('success', 'Padrinos importadas correctamente.');
    }
}
