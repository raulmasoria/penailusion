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

        $users = User::select('users.id', 'users.name', 'users.lastname', 'users.email')
                ->leftjoin('antiquities', 'users.id', '=', 'antiquities.user_id')
                ->leftjoin('permanences', 'users.id', '=', 'permanences.user_id')
                ->where('antiquities.year', 2023)   
                ->orWhere('permanences.year_permanence', 2023)                                   
                ->groupBy('users.id')
                ->get();
        //dd($users);
        foreach($users as $user){
            $adress = Adress::where('user_id', $user->id)->firstOrFail();
            if(!empty($user->email)){
                Mail::to($user->email)->send(new PlantillaEmail($user,$adress,$subject,$body));

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


