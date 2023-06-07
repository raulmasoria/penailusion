<?php

namespace App\Http\Controllers;

use App\Models\Adress;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\IntolerancesUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\AdressUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $intolerances = DB::table('intolerances')
        ->join('intolerances_users', 'intolerances.id', '=', 'intolerances_users.id_intolerance')
        ->join('users', 'users.id', '=', 'intolerances_users.id_user')
        ->select('intolerances.id','intolerances.name') 
        ->where('intolerances_users.id_user', auth()->user()->id)       
        ->get();   

        $allIntolerances = array();
        foreach($intolerances as $intolerancesrow){
            $allIntolerances[$intolerancesrow->name] = $intolerancesrow->name;            
        }

        return view('profile.edit', [
            'user' => $request->user(),
            'adress' => $request->user()->adress,
            "intolerances" => $allIntolerances
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }   

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the adress information.
     */
    public function updateAdress(Request $request)
    {   
        $request->validate([
            'via' => ['string', 'max:255'],
            'direccion' => ['string', 'max:255'],
            'piso' => ['string', 'max:255'],
            'cp' => ['Numeric'],
            'ciudad' => ['string', 'max:255'],
            'provincia' => ['string', 'max:255'],
        ]);

        $adress = auth()->user()->adress;

        $adress->via = $request->via;
        $adress->direccion = $request->direccion;
        $adress->piso = $request->piso;
        $adress->cp = $request->cp;
        $adress->ciudad = $request->ciudad;
        $adress->provincia = $request->provincia;
        
        $adress->save();

        return Redirect::route('profile.edit')->with('status', 'adress-updated');
    }

    //editar intolerancias desde el profile
    public function updateIntolerances(Request $request)
    {    
        if($request->lactosa == 'lactosa') {                      
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = auth()->user()->id;
            $intolerances->id_intolerance = 1;
            $intolerances->save();
        } else {
            $tengoLactosa = DB::table('intolerances_users')
            ->select('id')
            ->where('id_user', '=', auth()->user()->id)
            ->where('id_intolerance', '=', 1)
            ->get();

            if(count($tengoLactosa) >= 1){
                DB::table('intolerances_users')->where('id_user', '=', auth()->user()->id)->where('id_intolerance', '=', 1)->delete();
            }
        }
         
        if($request->gluten == 'gluten') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = auth()->user()->id;
            $intolerances->id_intolerance = 2;
            $intolerances->save();
        } else {
            $tengoGluten = DB::table('intolerances_users')
            ->select('id')
            ->where('id_user', '=', auth()->user()->id)
            ->where('id_intolerance', '=', 2)
            ->get();

            if(count($tengoGluten) >= 1){
                DB::table('intolerances_users')->where('id_user', '=', auth()->user()->id)->where('id_intolerance', '=', 2)->delete();
            }
        }          
        
        if($request->celiaco == 'celiaco') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = auth()->user()->id;
            $intolerances->id_intolerance = 3;
            $intolerances->save();
        } else {
            $tengoCeliaco = DB::table('intolerances_users')
            ->select('id')
            ->where('id_user', '=', auth()->user()->id)
            ->where('id_intolerance', '=', 3)
            ->get();

            if(count($tengoCeliaco) >= 1){
                DB::table('intolerances_users')->where('id_user', '=', auth()->user()->id)->where('id_intolerance', '=', 3)->delete();
            }
        }
        
        if($request->fructosa == 'fructosa') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = auth()->user()->id;
            $intolerances->id_intolerance = 4;
            $intolerances->save();
        } else {
            $tengoFructosa = DB::table('intolerances_users')
            ->select('id')
            ->where('id_user', '=', auth()->user()->id)
            ->where('id_intolerance', '=', 4)
            ->get();

            if(count($tengoFructosa) >= 1){
                DB::table('intolerances_users')->where('id_user', '=', auth()->user()->id)->where('id_intolerance', '=', 4)->delete();
            }
        }
          
        if($request->huevo == 'huevo') {
            $intolerances = new IntolerancesUser();
            $intolerances->id_user = auth()->user()->id;
            $intolerances->id_intolerance = 5;
            $intolerances->save();
        } else {
            $tengoHuevo = DB::table('intolerances_users')
            ->select('id')
            ->where('id_user', '=', auth()->user()->id)
            ->where('id_intolerance', '=', 5)
            ->get();

            if(count($tengoHuevo) >= 1){
                DB::table('intolerances_users')->where('id_user', '=', auth()->user()->id)->where('id_intolerance', '=', 5)->delete();
            }
        }     
        

        return Redirect::route('profile.edit')->with('status', 'intolerances-updated');
        
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
