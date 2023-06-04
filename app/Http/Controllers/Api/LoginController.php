<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;

class LoginController extends Controller
{
    public function index(Request $request)
   
{

$validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $token = $request->user()->createToken('api-token');

        if ($token instanceof NewAccessToken) {
            
            
            return response()->json(["code" => "200", "token" =>$token->plainTextToken], 200, [], JSON_UNESCAPED_UNICODE);
        }
    }

    return response()->json(["code" => "401", "Msg" =>"خطأ في البيانات"], 401, [], JSON_UNESCAPED_UNICODE);
}
}