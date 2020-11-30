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
Route::get('/dashboard', 'DashboardController@index')->name('dashboard.index');
Route::get('/rapor-mutu/{query}', 'DashboardController@rapor_mutu')->name('dashboard.rapor_mutu');
Route::post('/login-dashboard', 'PageController@login_dashboard')->name('login_dashboard');
#Route::post('/page/{query}/{id_level_wilayah?}/{kode_wilayah?}', 'PageController@index')->name('page');
Route::get('/reset-instrumen/{npsn}', 'PageController@reset_instrumen')->name('reset_instrumen');
Route::get('/page/{query}/{id_level_wilayah?}/{kode_wilayah?}', 'PageController@index')->name('page');
Route::get('/berita/{slug}', 'PageController@detil_berita')->name('detil_berita');
//Route::get('/rapor-mutu/{komponen_id}', 'PageController@get_rapor_mutu')->name('get_rapor_mutu');
Route::get('/get-chart', 'PageController@get_chart')->name('get_chart');
Route::get('/get-chart-komparasi', 'PageController@get_chart_komparasi')->name('get_chart_komparasi');
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
Route::get('/geojson/{geojson}', 'PetaController@geojson');
Route::post('/pencarian', 'SekolahController@pencarian')->name('pencarian');
Route::get('/verifikasi', function(){
    return redirect()->route('verifikasi_instrumen');
});
Route::post('/verifikasi', 'VerifikasiController@verifikasi_front');
Route::get('/verifikasi-instrumen', 'VerifikasiController@verifikasi_instrumen')->name('verifikasi_instrumen');
Route::post('/verifikasi-instrumen', 'VerifikasiController@verifikasi_instrumen');
Route::get('/dapodik', 'DapodikController@index');
Route::get('/cetak-hasil-verifikasi/{sekolah_id}', 'VerifikasiController@cetak');
Route::get('/hasil-verifikasi', 'VerifikasiController@hasil_verifikasi')->name('hasil_verifikasi');
Route::get('/validasi-instrumen', 'InstrumenController@validasi_instrumen')->name('validasi_instrumen');
Route::get('/cetak-hasil-validasi-instrumen', 'InstrumenController@cetak_validasi_instrumen')->name('cetak_validasi_instrumen');
Route::get('/export-excel', 'FrontController@export_excel')->name('export_excel');