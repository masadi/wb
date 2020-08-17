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
//Route::get('/instrumen', 'InstrumenController@index');
//Route::delete('/instrumen/{id}', 'InstrumenController@destroy');
Route::get('/get-kategori', 'BeritaController@kategori');
/*Route::get('/berita', 'BeritaController@index');

Route::delete('/berita/{id}', 'BeritaController@destroy');*/
Route::get('/hitung-nilai-instrumen/{user_id}', 'InstrumenController@hitung_nilai');
//Route::get('/users', 'UsersController@index');
//Route::post('/users', 'UsersController@create');
Route::group(['prefix' => 'komponen'], function(){
    Route::post('/upload', 'KomponenController@upload');
});
Route::group(['prefix' => 'kuisioner'], function(){
    Route::post('/', 'KuisionerController@index');
    Route::get('/', 'KuisionerController@index');
    //Route::get('/{query?}/{id?}', 'KuisionerController@index');
});
Route::resource('users', 'UsersController');
Route::resource('komponen', 'KomponenController');
Route::resource('berita', 'BeritaController');
Route::resource('instrumen', 'InstrumenController');
Route::post('/get-kuisioner', 'KuisionerController@proses');
Route::get('/get-kuisioner', 'KuisionerController@proses');
Route::post('/simpan-jawaban', 'KuisionerController@simpan_jawaban');