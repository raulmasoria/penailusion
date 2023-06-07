<?php

namespace App\Http\Controllers\Api;

use App\Models\DeviceUserToken;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceUserTokenController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!isset($request->per_page)) {
            $pagination = 20;
        } else {
            $pagination = $request->per_page;
        }

        return response()->json(DeviceUserToken::paginate($pagination));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_token' => 'required',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('remember_token', $request->token)->firstOrFail();
        if (!$user) {
            return response()->json(['code' => 403, 'status' => false, 'message' => 'Unauthorized'], 403);
        }

        $id_user = $user->id;
        $check_exist = DeviceUserToken::where('id_user', '=', $id_user)->where('token', '=', $request->device_token)->get();
        if ($check_exist->count() > 0) {
            return response()->json(["success" => true, "message" => "El dispositivo ya ha sido dado de alta"], 200);
        }

        $response = DeviceUserToken::create([
            'id_user' => $id_user,
            'token' => $request->device_token,
        ]);

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeviceUserToken  $deviceUserToken
     * @return \Illuminate\Http\Response
     */
    public function show(DeviceUserToken $deviceUserToken)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeviceUserToken  $deviceUserToken
     * @return \Illuminate\Http\Response
     */
    public function edit(DeviceUserToken $deviceUserToken)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeviceUserToken  $deviceUserToken
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeviceUserToken $deviceUserToken)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeviceUserToken  $deviceUserToken
     * @return \Illuminate\Http\Response
     */
    public function destroyAdmin($id, DeviceUserToken $deviceUserToken)
    {
        $deviceUserToken = DeviceUserToken::where('id', $id)->first();

        if (is_null($deviceUserToken)) {
            return response()->json(['msg' => 'Device Token not found'], 404);
        }

        $deviceUserToken->delete();
        return response()->json(['success' => 'Device Token deleted successfully.'], 200);
    }
}
