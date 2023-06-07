<?php

namespace App\Http\Controllers\Api;

use App\Models\RoundDay;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DeviceUserToken;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as NotificationFirebase;

class RoundDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->token || !User::where('remember_token', $request->token)->first()) {
            return response()->json(['code' => 403, 'status' => false, 'message' => 'Unauthorized'], 403);
        }

        $rondas = DB::table('round_days')
            ->join('companies', 'companies.id', '=', 'round_days.id_companie')
            ->get();
        return response()->json($rondas);
    }

    /**
     * Display a listing of the resource for day.
     *
     * @return \Illuminate\Http\Response
     */
    public function roundsByDay($day, Request $request)
    {
        if (!$request->token || !User::where('remember_token', $request->token)->first()) {
            return response()->json(['code' => 403, 'status' => false, 'message' => 'Unauthorized'], 403);
        }

        $rondas = DB::table('round_days')
            ->leftJoin('companies', 'companies.id', '=', 'round_days.id_companie')
            ->leftJoin('companies_address', 'companies.id', '=', 'companies_address.id')
            ->select(
                'round_days.id',
                'round_days.tipo',
                'round_days.description',
                'round_days.day',
                'round_days.hour',
                'round_days.march',
                'round_days.id_companie',
                'companies.establecimiento',
                'companies.imagen',
                'coordx',
                'coordy',
                'direccion'
            )
            ->where('day', 'LIKE', '%' . $day . '%')
            ->get();

        return response()->json($rondas);
    }

    public function showBarsWithRound(Request $request)
    {
        if (!$request->token || !User::where('remember_token', $request->token)->first()) {
            return response()->json(['code' => 403, 'status' => false, 'message' => 'Unauthorized'], 403);
        }

        $companies = DB::table('companies')
            ->join('round_days', 'companies.id', '=', 'round_days.id_companie')
            ->select('*')
            ->get();

        return response()->json($companies);
    }

    public function showBarsByDay($day, Request $request)
    {
        if (!$request->token || !User::where('remember_token', $request->token)->first()) {
            return response()->json(['code' => 403, 'status' => false, 'message' => 'Unauthorized'], 403);
        }

        $companies = DB::table('companies')
            ->join('round_days', 'companies.id', '=', 'round_days.id_companie')
            ->select('*')
            ->where('round_days.day', 'LIKE', '%' . $day . '%')
            ->get();

        return response()->json($companies);
    }

    /**
     * Display a listing of the resource for companie.
     *
     * @return \Illuminate\Http\Response
     */
    public function roundsByCompanie($id, Request $request)
    {
        if (!$request->token || !User::where('remember_token', $request->token)->first()) {
            return response()->json(['code' => 403, 'status' => false, 'message' => 'Unauthorized'], 403);
        }

        $rondas = RoundDay::where('id_companie', '=', $id)->get();
        return response()->json($rondas);
    }

    //editar una ronda siendo junta directiva
    public function update(RoundDay $roundDay, Request $request)
    {
        try {
            $request->validate([
                'type' => 'required',
                'day' => 'required',
                'token' => 'required',
                'newHour' => 'string',
                'newBar' => 'integer',
                'notification' => 'required|boolean',
            ]);

            if ($request->notification && (!$request->title || !$request->body)) {
                return response()->json(['code' => 400, 'status' => false, 'message' => 'Bad request. Not title or body'], 400);
            }

            $user = User::where('remember_token', $request->token)->first();
            if (!$request->token || !$user) {
                return response()->json(['code' => 403, 'status' => false, 'message' => 'Unauthorized'], 403);
            }

            if (!$user->active || $user->rol != 1) {
                return response()->json(['code' => 403, 'status' => false, 'message' => 'Unauthorized'], 403);
            }

            $roundDay->tipo = $request->type;
            $roundDay->day = $request->day;
            $roundDay->hour = $request->newHour;
            $roundDay->id_companie = $request->newBar;
            $roundDay->save();

            $notificationUser = [
                'title' => $request->title,
                'body' => $request->body,
                'read' => 0,
                'id_user' => 0
            ];

            if ($request->notification) {
                $send_tokens = [];
                $send_users = [];
                $tokens_devices = DeviceUserToken::select('token','id_user')
                    ->join('users', 'id_user', '=', 'users.id')
                    ->where('users.notifications', '=', 1)
                    ->get();
                foreach ($tokens_devices as $device) {
                    if (!in_array($device->token, $send_tokens)) {
                        array_push($send_tokens, $device->token);
                    }
                    if (!in_array($device->id_user, $send_users)) {
                        array_push($send_users, $device->id_user);
                        $notificationUser['id_user'] = $device->id_user;
                        Notification::create($notificationUser);
                    }
                }

                $messaging = app('firebase.messaging');
                $notification = NotificationFirebase::fromArray([
                    'title' => $request->title,
                    'body' => $request->body
                ]);

                $message = CloudMessage::new()->withNotification($notification);

                $report = $messaging->sendMulticast($message, $send_tokens);
                return response()->json(["round" => $roundDay, "count_messages" => $report->successes()->count()]);
            }

            return response()->json(["round" => $roundDay]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
