<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Adress;
use App\Mail\ResetPasswd;
use App\Models\Antiquity;
use App\Models\Permanence;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'El email o contraseña introducidos, no son correctos.',
                ], 401);
            }


            $user = User::where('email', $request->email)->first();
            $token = $user->createToken("API TOKEN")->plainTextToken;
            $user->remember_token = $token;
            $user->update();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $token
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function refresh(Request $request)
    {

        if (!$request->token || !User::where('remember_token', $request->token)->first()) {
            return response()->json([
                'status' => false,
                'message' => 'token error',
            ], 401);
        }

        $user = User::where('remember_token', $request->token)->first();
        $user->tokens()->delete();

        $token = $user->createToken("API TOKEN REFRESH")->plainTextToken;
        $user->remember_token = $token;
        $user->update();

        return response()->json([
            'status' => true,
            'message' => 'refresh token',
            'token' => $token,
        ]);
    }

    public function getUser(Request $request)
    {
        if (!$request->token || !User::where('remember_token', $request->token)->first()) {
            return response()->json([
                'status' => false,
                'message' => 'token error',
            ], 401);
        }

        $user = User::where('remember_token', $request->token)->first();

        $adress = Adress::where('user_id', $user->id)->first();
        if ($adress == null) {
            $adress['via'] = '';
            $adress['direccion'] = '';
            $adress['piso'] = '';
            $adress['cp'] = '';
            $adress['ciudad'] = '';
            $adress['provincia'] = '';
        }

        $antiquity = Antiquity::where('user_id', $user->id)->get();
        $permanence = Permanence::where('user_id', $user->id)->get();

        //junto la antiguedad con la permanencia
        $anos = array();
        foreach ($antiquity as $anti) {
            $anos[$anti->year] = "Cuota completa";
        }
        foreach ($permanence as $perman) {
            $anos[$perman->year_permanence] = "Cuota de permanencia";
        }
        ksort($anos);


        return response()->json([
            'status' => true,
            'message' => 'User info',
            'data_user' => ['name' => $user->name, 'lastname' => $user->lastname, 'phone' => $user->phone, 'email' => $user->email, 'nif' => $user->nif, 'rol' => $user->rol, 'notifications' => $user->notifications, 'active' => $user->active, 'carta' => $user->carta],
            'data_user_adress' => ['via' => $adress['via'], 'direccion' => $adress['direccion'], 'piso' => $adress['piso'], 'cp' => $adress['cp'], 'ciudad' => $adress['ciudad'], 'provincia' => $adress['provincia']],
            'data_user_antiquity' => $anos,
            'token' => $request->token
        ], 200);
    }

    public function updateUser(Request $request)
    {
        try {
            if (!$request->token || !User::where('remember_token', $request->token)->first()) {
                return response()->json([
                    'status' => false,
                    'message' => 'token error',
                ], 401);
            }

            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'phone' => 'required',
                    'via' => 'required',
                    'direccion' => 'required',
                    'piso' => 'required',
                    'cp' => 'required',
                    'ciudad' => 'required',
                    'provincia' => 'required',

                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::where('remember_token', $request->token)->first();
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->save();

            $adress = Adress::where('user_id', $user->id)->first();
            $adress->via = $request->via;
            $adress->direccion = $request->direccion;
            $adress->piso = $request->piso;
            $adress->cp = $request->cp;
            $adress->ciudad = $request->ciudad;
            $adress->provincia = $request->provincia;
            $adress->save();

            return response()->json([
                'status' => true,
                'message' => 'User updated',
                'token' => $request->token,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateNotifications(Request $request)
    {
        try {
            if (!$request->token || !User::where('remember_token', $request->token)->first()) {
                return response()->json([
                    'status' => false,
                    'message' => 'token error',
                ], 401);
            }

            $validateUser = Validator::make(
                $request->all(),
                [
                    'notifications' => 'required|boolean',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = User::where('remember_token', $request->token)->first();
            $user->notifications = $request->notifications;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'notifications updated',
                'token' => $request->token
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updatePostalNotification(Request $request)
    {
        try {
            if (!$request->token || !User::where('remember_token', $request->token)->first()) {
                return response()->json([
                    'status' => false,
                    'message' => 'token error',
                ], 401);
            }

            $validateUser = Validator::make(
                $request->all(),
                [
                    'carta' => 'required|boolean',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            $user = User::where('remember_token', $request->token)->first();
            $user->carta = $request->carta;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'postal notifications updated',
                'token' => $request->token
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updatePasswd(Request $request)
    {
        try {
            if (!$request->token || !User::where('remember_token', $request->token)->first()) {
                return response()->json([
                    'status' => false,
                    'message' => 'token error',
                ], 401);
            }

            $validateUser = Validator::make(
                $request->all(),
                [
                    'passwd' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::where('remember_token', $request->token)->first();
            $user->password = Hash::make($request->passwd);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'password updated',
                'token' => $request->token
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function resetPasswd(Request $request)
    {
        try {
            if (!$request->email || !User::where('email', $request->email)->first()) {
                return response()->json([
                    'status' => false,
                    'message' => 'email error',
                ], 401);
            }

            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            $adress = Adress::where('user_id', $user->id)->first();
            $passwd = Str::random(10);
            $user->password = Hash::make($passwd);
            $user->save();

            if (!empty($user->email)) {
                Mail::to($user)->send(new ResetPasswd($user, $adress, $passwd));
            }

            return response()->json([
                'status' => true,
                'message' => 'password reset',
                'token' => $request->token
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    
}