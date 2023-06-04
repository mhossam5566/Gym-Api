<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProgrammController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('register', [RegisterController::class, 'index']);

Route::post('login', [LoginController::class, 'index']);

##############Users Group#############

Route::middleware('auth:sanctum', 'check.role:1')->group(function () {
  Route::post('subscribe', [ProgrammController::class, 'subscribe']);

  Route::post('unsubscribe', [ProgrammController::class, 'unsubscribe']);
});


##############Coaches Group#############

Route::middleware('auth:sanctum', 'check.role:2')->group(function () {
  Route::get('get_programs', [ProgrammController::class, 'coach']);

});


##############Admin Group#############

Route::middleware('auth:sanctum', 'check.role:3')->group(function () {

  Route::post('add_program', [ProgrammController::class, 'add']);

  Route::post('delete_program', [ProgrammController::class, 'delete']);

  Route::post('edit_program', [ProgrammController::class, 'edit']);

  Route::post('add_user', [RegisterController::class, 'admin']);

});

##############Public Group#############

Route::middleware('auth:sanctum')->group(function () {

  Route::get('get_user', [UserController::class, 'get']);
  Route::post('edit_user', [UserController::class, 'edit']);
  Route::get('get_all_programs', [ProgrammController::class, 'get_all']);
  Route::get('program/{id}', [ProgrammController::class, 'get_one']);
Route::get('program_member/{id}', [ProgrammController::class, 'member']);

});