<?php

namespace App\Http\Controllers;

use App\Models\Intolerance;
use App\Models\IntolerancesUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IntolerancesController extends Controller
{
    public function index()
    {
        $intolerances = Intolerance::all();

        return response()->json($intolerances);
    }

    public function showByUser(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('remember_token',$request->token)->first();
        if(!$user){
            return response()->json(['code' => 403,'status'=> false, 'message' => 'Unauthorized'], 403);
        }

        $response = $user->intolerances;
        
        return response()->json($response);
    }

    public function update(Request $request, User $user)
    {   
        $validator = Validator::make($request->all(), [
            'intolerances' => 'required|array',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('remember_token',$request->token)->first();
        if(!$user){
            return response()->json(['code' => 403,'status'=> false, 'message' => 'Unauthorized'], 403);
        }

        $user->intolerances()->sync($request->intolerances);
        
        $user->save();
        return response()->json($user->intolerances);
    }
}
