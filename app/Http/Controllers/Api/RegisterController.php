<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{
  public function index(Request $request) {

    $validatedData = $request->validate([
      'name' => 'required|string',
      'email' => 'required|email|unique:users',
      'phone' => 'required|string',
      'age' => 'required|integer',
      'address' => 'required|string',
      'height' => 'required|integer',
      'weight' => 'required|integer',
      'password' => 'required|string',

    ]);



    $users = new User;

    $users->name = $request->name;
    $users->email = $request->email;
    $users->phone = $request->phone;
    $users->age = $request->age;
    $users->address = $request->address;
    $users->height = $request->height;
    $users->weight = $request->weight;
    $users->image = $request->image;
    $users->type = '1';
    $users->password = Hash::make($request->password);

    $users->save();
    return response()->json(["code" => "200", "msg" => "User Added Succesfully"]);

  }

  public function admin (Request $request) {

    $validatedData = $request->validate([
      'name' => 'required|string',
      'email' => 'required|email|unique:users',
      'phone' => 'required|string',
      'age' => 'required|integer',
      'address' => 'required|string',
      'height' => 'required|integer',
      'weight' => 'required|integer',
      'password' => 'required|string',
      'type' => 'required|integer|in:1,2,3'
    ]);



    $users = new User;

    $users->name = $request->name;
    $users->email = $request->email;
    $users->phone = $request->phone;
    $users->age = $request->age;
    $users->address = $request->address;
    $users->height = $request->height;
    $users->weight = $request->weight;
    $users->type = $request->type;
    $users->password = Hash::make($request->password);

    $users->save();

    if ($request->type == "1") {
      return response()->json(["code" => "200", "msg" => "User Added Succesfully"]);
    } elseif ($request->type == "2") {
      return response()->json(["code" => "200", "msg" => "Coach Added Succesfully"]);

    } elseif ($request->type == "3") {
      return response()->json(["code" => "200", "msg" => "Admin Added Succesfully"]);
    }
  }
}