<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);
Route::get('/{query}', 'PageController@index');
/*Route::get('/{query}', function ($query) {
    if($query == 'home'){
        return view('welcome');
    }
    return view('page.'.$query)->with(['id_level_wilayah' => 1]);
});
Route::get('progres-wilayah/{id_level_wilayah}', function ($id_level_wilayah) {
    return view('page.progres-wilayah')->with(['id_level_wilayah' => $id_level_wilayah]);
});*/
#Route::get('/home', 'HomeController@index')->name('home');
Route::get('/app/{vue_capture?}', function () {
    if (Auth::check()) {
        return view('admin')->with(['user' => Auth::user()]);
    }
    return redirect('/login');
})->where('vue_capture', '[\/\w\.-]*');
Route::group(['prefix' => 'komponen'], function(){
    Route::post('/upload', 'KomponenController@upload');
});
//Route::get('/users', 'UsersController@index');
//Route::post('/users', 'UsersController@create');
