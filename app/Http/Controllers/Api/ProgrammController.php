<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Program;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgrammController extends Controller
{
  public function add (Request $request) {

    $validatedData = $request->validate([
      'name' => 'required|string',
      'image' => 'required|string',
      'coach' => 'required|string',
      'number_of_players' => 'required|string',
      'price' => 'required|string',
      'description' => 'required|string',
    ]);

    Program::create([
      "name" => $request->name,
      "image" => $request->image,
      "coach" => $request->coach,
      "number_of_players" => $request->number_of_players,
      "price" => $request->price,
      "description" => $request->description
    ]);

    return response()->json([
      "code" => 200,
      "msg" => " Program Added Successfully"
    ], 200);

  }
  public function edit(Request $request) {
    $validatedData = $request->validate([
      'id' => 'required|integer',
      'name' => 'required|string',
      'image' => 'required|string',
      'coach' => 'required|string',
      'number_of_players' => 'required|string',
      'price' => 'required|string',
      'description' => 'required|string',
    ]);



  }
  public function delete(Request $request) {
    $validatedData = $request->validate([
      'id' => 'required|string',

    ]);

    $del = Program::where('id', $request->id)->delete();
    if (!$del) {
      return response()->json([
        "code" => 400,
        "msg" => "An Error occurd"
      ], 400);
    }
    return response()->json([
      "code" => 200,
      "msg" => " Program Deleted Successfully"
    ], 200);
  }
  public function coach() {
    $coach = Auth::user()->email;
    $programs = Program::all()->where('coach', $coach);
    $transformedPrograms = $programs->map(function ($program) {
      return [
        'id' => $program->id,
        'name' => $program->name,
        'image' => $program->image,
        'price' => $program->price,
        'number_of_players' => $program->number_of_players,
        'description' => $program->description
      ];
    })->values();
    return response()->json([
      'code' => 200,
      'programs' => $transformedPrograms
    ]);
  }
  public function get_all() {
    $programs = Program::all();

    $transformedPrograms = $programs->map(function ($program) {
      $coach = User::where('email', $program->coach)->first();

      return [
        'id' => $program->id,
        'name' => $program->name,
        'image' => $program->image,
        'coach' => $coach->name,
        'price' => $program->price,
        'number_of_players' => $program->number_of_players,
        'description' => $program->description
      ];
    })->values();
    return response()->json([
      'code' => 200,
      'programs' => $transformedPrograms
    ]);
  }
  public function get_one($id) {
    $getprogram = Program::find($id);
    if (!$getprogram) {
      return response()->json([
        "code" => 400,
        "Msg" => "program not found"
      ], 400);
    }

    $user = User::where('email', Auth::user()->email)->first();
    $coach = $user->name;

    return response()->json([
      "code" => 200,
      "data" => array(
        "id" => $getprogram->id,
        "name" => $getprogram->name,
        "coach" => $coach,
        "number_of_players" => $getprogram->number_of_players,
        "price" => $getprogram->price,
        "description" => $getprogram->description
      )

    ]);
  }

  public function member($id) {
    //Check If Exist
    $getprogram = Program::find($id);
    if (!$getprogram) {
      return response()->json([
        "code" => 400,
        "Msg" => "program not found"
      ], 400);
    }

    $members = DB::table('program_user')->where('program_id', $id)->get();
$updatedMembers = [];

foreach ($members as $member) {
    $user = User::where('email', $member->user_id)->first();
    if ($user) {
        $updatedMember = [
            "usermail" => $member->user_id,
            "username" => $user->name
        ];
        $updatedMembers[] = $updatedMember;
    }
}

return response()->json([
    "code" => 200,
    "members" => $updatedMembers
]);



  }

  public function subscribe(Request $request) {
    $validate = $request->validate([
      'id' => 'required|integer'
    ]);

    $checkprogram = DB::table('programs')->where('id', $request->id)->get();


    if ($checkprogram->count() !== 0) {



      $check = DB::table('program_user')
      ->where('program_id', $request->id)
      ->where('user_id', Auth::user()->email)
      ->get();


      if ($check->count() > 0) {
        return response()->json([
          'code' => 400,
          'Msg' => 'allready subscribed'
        ]);

      } else {
        DB::table('program_user')->insert([
          'program_id' => $request->id,
          'user_id' => Auth::user()->email

        ]);

        return response()->json([
          'code' => 200,
          'Msg' => 'subscribed Successfully'
        ]);
      }
    } else {
      return response()->json([
        'code' => 400,
        'Msg' => 'Program Not Found'
      ]);
    }


  }


  public function unsubscribe(Request $request) {
    $validate = $request->validate([
      'subscribe_id' => 'required|integer'
    ]);

    $check = DB::table('program_user')
    ->where('id', $request->subscribe_id)
    ->where('user_id', Auth::user()->email)
    ->get();

    if ($check->count() > 0) {
      $check = DB::table('program_user')
      ->where('id', $request->subscribe_id)
      ->where('user_id', Auth::user()->email)
      ->delete();

      return response()->json([
        'code' => 200,
        'Msg' => 'Deleted Successfully'
      ]);
    } else {
      return response()->json([
        'code' => 400,
        'Msg' => 'subscribe id not found'
      ]);
    }
  }



}