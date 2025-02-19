<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AdressImport;
use Maatwebsite\Excel\Facades\Excel;

class AdressController extends Controller
{

    public function importAdress(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new AdressImport, $request->file('file'));

        return back()->with('success', 'Usuarios importados correctamente.');
    }

}
