<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:5|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'Usuari registrat correctament!',
            'data' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credencials incorrectes'], 401);
            }
            return response()->json([
                'message' => 'Login correcte!',
                'token' => $token
            ], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 'No s\'ha pogut crear el token'], 500);
        }
    }

    public function getUser()
    {
        try {
            $user = Auth::user();
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token no vàlid'], 401);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logout correcte!']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'No s\'ha pogut fer logout'], 500);
        }
    }
}
