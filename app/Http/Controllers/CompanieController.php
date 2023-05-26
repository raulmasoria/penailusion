<?php

namespace App\Http\Controllers;

use App\Models\Companie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\companiesResource;

class CompanieController extends Controller
{
    

    public function index()
    {
        //$companies = Companie::all();

        $companies = DB::table('companies')
        ->join('companies_address', 'companies.id', '=', 'companies_address.id')
        ->select('*')        
        ->orderby('companies.id')
        ->get();   

        return companiesResource::collection($companies);
    }
    
    public function show($id)
    {
        //$companie_row = Companie::where('id',$id)->firstOrFail();
        $companie_row = DB::table('companies')
        ->join('companies_address', 'companies.id', '=', 'companies_address.id')
        ->select('companies.id as id', 'establecimiento', 'tipo', 'via', 'direccion', 'piso', 'cp', 'ciudad', 'provincia', 'coordx', 'coordy')        
        ->where('companies.id', '=', $id)
        ->get();   

        return companiesResource::collection($companie_row);
    }

    public function update($companie, $coordx, $coordy)
    {
        $result = DB::table('companies_address')
              ->where('id', $companie)
              ->update(['coordx' => $coordx, 'coordy' => $coordy]);

        if($result){
            return response()->json([
                'message' => 'Success'
            ],204);
        } else {
            return  response()->json([
                'message' => 'Not found'
            ],404);
        }     
    }

}
