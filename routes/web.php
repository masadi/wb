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

Auth::routes();

#Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/{vue_capture?}', function () {
    if (Auth::check()) {
    return view('admin');
    }
    return redirect('/login');
})->where('vue_capture', '[\/\w\.-]*');
Route::get('/users', 'UsersController@index');
Route::post('/users', 'UsersController@create');
