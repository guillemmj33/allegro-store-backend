<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Routes with middleware on the controller except login and register
Route::controller(AuthController::class)->group(function () {
  Route::post('login', 'login');
  Route::post('register', 'register');
  Route::post('logout', 'logout');
  Route::post('refresh', 'refresh');
});

//Routes with middleware auth:api protection
Route::controller(ItemController::class)->group(function () {
  Route::get('items', 'index');
  Route::post('item/create', 'store');
  Route::get('item/{id}', 'show');
  Route::put('item/{id}', 'update');
  Route::delete('item/{id}', 'destroy');
});
