<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AntiquitiesPermanencesImport;
use Maatwebsite\Excel\Facades\Excel;

class AntiquityController extends Controller
{
    public function importAntiquity(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new AntiquitiesPermanencesImport, $request->file('file'));

        return back()->with('success', 'Antigüedades importadas correctamente.');
    }
}
