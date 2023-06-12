<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Email;
use App\Models\Adress;
use App\Mail\PlantillaEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Redirect;


class EmailController extends Controller
{
   public function edit(){
    return view('email.index');
   }

   //Envio de emails
   public function store(Request $request){

    $request->validate([
        'emails' => ['string', 'max:255'],
        'asunto' => ['string', 'max:255'],            
        'cuerpo' => ['string'],       
    ]);

    $subject = $request->asunto;
    $body = $request->cuerpo;

    if($request->emails == 'prueba'){
        $user = User::where('email', 'soriailusion@gmail.com')->firstOrFail();
        $adress = Adress::where('user_id', $user->id)->firstOrFail();
        Mail::to('soriailusion@gmail.com')->send(new PlantillaEmail($user,$adress,$subject,$body));
        
        $log = new Email();
        $log->user_id = $user->id;
        $log->email = $user->email;
        $log->asunto = $subject;
        $log->estado = 'ok';
        $log->save();
        
    } else if($request->emails == 'socios'){

        $users = DB::table('users')
            ->leftJoin('antiquities', 'users.id', '=', 'antiquities.user_id')
            ->leftJoin('permanences', 'users.id', '=', 'permanences.user_id')
            ->select('users.id', 'users.name', 'users.lastname', 'users.email')
            ->whereRaw("NOT users.email = ''")
            ->where(function(Builder $query) {
                $query->where('antiquities.year', 2022)
                      ->orWhere('permanences.year_permanence', 2022);
            })       
            ->groupBy('users.id')
            ->get(); 

        //$users = User::where('email', 'soriailusion@gmail.com')->orWhere('email', 'raulma_13@hotmail.com')->get();
        //dd($users);
        foreach($users as $user){
            $adress = Adress::where('user_id', $user->id)->firstOrFail();
            if(!empty($user->email)){
                //Mail::to($user->email)->send(new PlantillaEmail($user,$adress,$subject,$body));

                $log = new Email;
                $log->user_id = $user->id;
                $log->email = $user->email;
                $log->asunto = $subject;
                $log->estado = 'ok';
                $log->save();
            }
        }
    }   
    

    return Redirect::route('email')->with('status', 'email-send');

   }
}


