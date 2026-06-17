<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Email;
use App\Models\Adress;
use App\Mail\PlantillaEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PlantillaEmailLibreElecion;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;


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

        } else if($request->emails == 'socios_permanencia_ultimo_ano'){

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
        } else if($request->emails == 'socios_permanencia_ano_actual'){

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
        } else if($request->emails == 'socios_ultimo_ano'){

            $users = User::select('users.id', 'users.name', 'users.lastname', 'users.email')
                ->leftJoin('antiquities', 'users.id', '=', 'antiquities.user_id')
                ->where(function ($query) {
                    $query->where('antiquities.year', YearHelperController::lastYear());
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
                    ->where(function ($query) {
                        $query->where('antiquities.year', YearHelperController::currentYear());
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
        } else if($request->emails == 'permanencia_ultimo_ano'){

            $users = User::select('users.id', 'users.name', 'users.lastname', 'users.email')
                ->leftJoin('permanences', 'users.id', '=', 'permanences.user_id')
                ->where(function ($query) {
                    $query->where('permanences.year_permanence', YearHelperController::lastYear());
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
        } else if($request->emails == 'permanencia_ano_actual'){

            $users = User::select('users.id', 'users.name', 'users.lastname', 'users.email')
                    ->leftjoin('permanences', 'users.id', '=', 'permanences.user_id')
                    ->where(function ($query) {
                        $query->where('permanences.year_permanence', YearHelperController::currentYear());
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

    //Envio de email de notificación de penalización añadida a responsable del niño
    public static function sendPenalityAddedNotification($user_responsible, $nino, $penality, $penality_name){
        $date_penality = Carbon::parse($penality->date_penality)->format('d/m/Y H:i');
        $subject = 'Notificación de penalización añadida';
        $body = "Estimado/a {$user_responsible->name} {$user_responsible->lastname},<br><br>
                    Le informamos que se ha añadido una penalización al menor, del cual es responsable, {$nino->name} {$nino->lastname} con fecha {$date_penality}.<br><br>
                    El motivo de la penalización es el siguiente: {$penality_name}.<br><br>
                    Por favor, póngase en contacto con nosotros si tiene alguna pregunta o necesita más información.<br><br>
                    Atentamente,<br>
                    La junta directiva de la Peña Ilusión";
        Mail::mailer('mailrelay')->to($user_responsible->email)->send(new PlantillaEmailLibreElecion($subject,$body));
        //Mail::to($user_responsible->email)->send(new PlantillaEmailLibreElecion($subject,$body));

        $log = new Email;
        $log->user_id = $user_responsible->id;
        $log->email = $user_responsible->email;
        $log->asunto = $subject;
        $log->estado = 'ok';
        $log->save();
    }
}


