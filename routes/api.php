<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/instrumen', 'InstrumenController@index');
Route::get('/hitung-nilai-instrumen/{user_id}', 'InstrumenController@hitung_nilai');
Route::delete('/instrumen/{id}', 'InstrumenController@destroy');
Route::get('/users', 'UsersController@index');
Route::post('/users', 'UsersController@create');