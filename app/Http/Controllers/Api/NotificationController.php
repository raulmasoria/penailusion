<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DeviceUserToken;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Messaging\Notification as NotificationFirebase;
use Kreait\Laravel\Firebase\Facades\Firebase;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!isset($request->per_page)) {
            $pagination = 20;
        } else {
            $pagination = $request->per_page;
        }

        return response()->json(Notification::paginate($pagination));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listByUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        
        $user = User::where('remember_token',$request->token)->firstOrFail();

        if(!User::where('remember_token', $request->token)->firstOrFail()){
            return response()->json(['code' => 403,'status'=> false, 'message' => 'Unauthorized'], 403);
        }

        $response = Notification::where('id_user', '=', $user->id)
        ->orderBy('created_at', 'desc')->paginate($request->per_page);

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $types = ["all", "actives"];

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'type' => 'required',
            'notification.title' => 'required',
            'notification.body' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('remember_token', $request->token)->first();
        if (!$user) {
            return response()->json(['code' => 403, 'status' => false, 'message' => 'Unauthorized'], 403);
        }

        if (!in_array(request()->input('type'), $types)) {
            return response()->json(["status" => "error", "message" => "Type not supported"], 500);
        }

        $notification = [
            'title' => request()->input('notification')["title"],
            'body' => request()->input('notification')["body"],
            'read' => 0,
            'id_user' => ''
        ];

        $send_tokens = [];
        $send_users = [];
        $tokens_devices = [];
        if($request->type == 'all') {
            $tokens_devices = DeviceUserToken::select('token','id_user')->get();
        }
        if($request->type == 'actives') {
            $tokens_devices = DeviceUserToken::select('token','id_user')
            ->join('users', 'id_user', '=', 'users.id')
            ->where('users.notifications', '=', 1)
            ->get();
        }
        foreach ($tokens_devices as $device) {
            if (!in_array($device->token, $send_tokens)) {
                array_push($send_tokens, $device->token);
            }
            if (!in_array($device->id_user, $send_users)) {
                array_push($send_users, $device->id_user);
                $notification['id_user'] = $device->id_user;
                Notification::create($notification);
            }
        }

        $messaging = app('firebase.messaging');
        $notification = NotificationFirebase::fromArray([
            'title' => request()->input('notification')["title"],
            'body' => request()->input('notification')["body"]
        ]);

        $message = CloudMessage::new()->withNotification($notification);

        $report = $messaging->sendMulticast($message, $send_tokens);

        return response()->json(["count_messages" => $report->successes()->count()]);
    }

    public function sendNotification() {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Notification $notification)
    {
        $deviceUserToken = Notification::where('id',$id)->first();

        if(is_null($deviceUserToken)) {
            return response()->json(['msg' => 'Notification not found'], 404);
        }

        $deviceUserToken->delete();
        return response()->json(['success'=>'Notification deleted successfully.'], 200);
    }
}
