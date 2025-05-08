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
use App\Mail\PlantillaEmailLibreElecion;
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
        Mail::mailer('mailrelay')->to('soriailusion@gmail.com')->send(new PlantillaEmail($user,$adress,$subject,$body));
        //Mail::to('raulmasoria@gmail.com')->send(new PlantillaEmail($user,$adress,$subject,$body));

        $log = new Email();
        $log->user_id = $user->id;
        $log->email = $user->email;
        $log->asunto = $subject;
        $log->estado = 'ok';
        $log->save();

    } else if($request->emails == 'libre'){

       $libreEmails = $request->libreEmails;
       $emails = explode(',', $libreEmails);

        foreach($emails as $email){
            $email = trim($email);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $log = new Email;
                $log->user_id = 583;
                $log->email = $email;
                $log->asunto = $subject;
                $log->estado = 'ko';
                $log->save();
                continue;
            }
            if(!empty($email)){
                Mail::mailer('mailrelay')->to($email)->send(new PlantillaEmailLibreElecion($subject,$body));

                $log = new Email;
                $log->user_id = 583;
                $log->email = $email;
                $log->asunto = $subject;
                $log->estado = 'ok';
                $log->save();
            }
        }

    } else if($request->emails == 'socios'){

        $users = User::select('users.id', 'users.name', 'users.lastname', 'users.email')
            ->leftJoin('antiquities', 'users.id', '=', 'antiquities.user_id')
            ->leftJoin('permanences', 'users.id', '=', 'permanences.user_id')
            ->where(function ($query) {
                $query->where('antiquities.year', YearHelperController::lastYear())
                        ->orWhere('permanences.year_permanence', YearHelperController::lastYear());
            })
            ->whereNotNull('users.email')
            ->where('users.email', '<>', '')
            ->groupBy('users.id', 'users.name', 'users.lastname', 'users.email')
            ->get();

        //dd($users);
        foreach($users as $user){
            $adress = Adress::where('user_id', $user->id)->firstOrFail();
            if(!empty($user->email)){
                Mail::mailer('mailrelay')->to($user->email)->send(new PlantillaEmail($user,$adress,$subject,$body));

                $log = new Email;
                $log->user_id = $user->id;
                $log->email = $user->email;
                $log->asunto = $subject;
                $log->estado = 'ok';
                $log->save();
            }
        }
    } else if($request->emails == 'socios_ano_actual'){

        $users = User::select('users.id', 'users.name', 'users.lastname', 'users.email')
                ->leftjoin('antiquities', 'users.id', '=', 'antiquities.user_id')
                ->leftjoin('permanences', 'users.id', '=', 'permanences.user_id')
                ->where(function ($query) {
                    $query->where('antiquities.year', YearHelperController::currentYear())
                            ->orWhere('permanences.year_permanence', YearHelperController::currentYear());
                })
                ->whereNotNull('users.email')
                ->where('users.email', '<>', '')
                ->groupBy('users.id', 'users.name', 'users.lastname', 'users.email')
                ->get();
        //dd($users);
        foreach($users as $user){
            $adress = Adress::where('user_id', $user->id)->firstOrFail();
            if(!empty($user->email)){
                Mail::mailer('mailrelay')->to($user->email)->send(new PlantillaEmail($user,$adress,$subject,$body));

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


