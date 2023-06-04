<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function get() {
    $user = Auth::user();

    if ($user->type == "1") {
      $type = "User";
    } elseif ($user->type == "2") {
      $type = "Coach";
    } elseif ($user->type == "3") {
      $type = "Admin";
    }

    return response()->json([
      "id" => $user->id,
      "name" => $user->name,
      "email" => $user->email,
      "phone" => $user->phone,
      "address" => $user->address,
      "age" => $user->age,
      "height" => $user->height,
      "weight" => $user->weight,
      "type" => $type,

    ]);

  }


  public function edit(Request $request) {
    $user = Auth::user()->id;
    User::whereId($user)->update($request->all());
  
  return response()->json([
    "code" => 200,
    "Msg" => " Account Updated Successfully"
  ]);
  }
  
  
}