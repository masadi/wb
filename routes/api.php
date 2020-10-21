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
Route::post('/login-user', 'ApiController@login_api');
//Route::get('/instrumen', 'InstrumenController@index');
//Route::delete('/instrumen/{id}', 'InstrumenController@destroy');
Route::get('/get-kategori', 'BeritaController@kategori');
Route::get('/cetak-instrumen', 'ReferensiController@cetak');
/*Route::get('/berita', 'BeritaController@index');

Route::delete('/berita/{id}', 'BeritaController@destroy');*/
Route::get('/hitung-nilai-instrumen/{user_id}', 'NilaiController@hitung_nilai');
Route::post('/hitung-nilai-instrumen', 'NilaiController@hitung_nilai');
//Route::get('/users', 'UsersController@index');
//Route::post('/users', 'UsersController@create');
Route::group(['prefix' => 'referensi'], function(){
    Route::get('/{query}', 'ReferensiController@index');
    Route::post('/komponen/upload', 'ReferensiController@upload');
    Route::post('/sekolah-sasaran', 'ReferensiController@sekolah_sasaran');
    Route::post('/sekolah-sasaran-pendamping', 'ReferensiController@sekolah_sasaran_pendamping');
    Route::post('/status-coe', 'ReferensiController@status_coe');
});
Route::group(['prefix' => 'kuisioner'], function(){
    Route::post('/', 'KuisionerController@index');
    Route::get('/', 'KuisionerController@index');
    Route::get('/progres', 'KuisionerController@progres');
    Route::post('/parse-json', 'KuisionerController@parse_json');
    //Route::get('/{query?}/{id?}', 'KuisionerController@index');
});
Route::group(['prefix' => 'verifikasi'], function(){
    Route::post('/{query}', 'VerifikasiController@index');
    Route::get('/download', 'VerifikasiController@download');
    //Route::post('/instrumen', 'VerifikasiController@get_instrumen');
    //Route::post('/subs', 'VerifikasiController@get_subs');
});
Route::group(['prefix' => 'rapor-mutu'], function(){
    Route::post('/hasil', 'RaporController@index');
    //Route::get('/hasil', 'RaporController@index');
    Route::post('/pakta', 'RaporController@pakta');
    Route::post('/pra-cetak-pakta', 'RaporController@pra_cetak_pakta');
    //Route::post('/cetak-pakta', 'RaporController@cetak_pakta');
    Route::get('/cetak-pakta', 'RaporController@cetak_pakta');
    Route::get('/cetak-rapor', 'RaporController@cetak_rapor');
    Route::post('/batal-pakta', 'RaporController@batal_pakta');
    Route::post('/kirim', 'RaporController@kirim');
    Route::post('/sekolah', 'RaporController@sekolah')->name('api.rapor_sekolah');
});
Route::group(['prefix' => 'validasi'], function(){
    Route::get('/', 'ValidasiController@index');
    Route::post('/get-data', 'ValidasiController@get_data');
    Route::post('/post-data', 'ValidasiController@post_data');
    Route::post('/proses', 'ValidasiController@proses');
    Route::get('/download', 'ValidasiController@download');
});
Route::resource('users', 'UsersController');
Route::post('/profile', 'UsersController@profile');
Route::post('/reset-password', 'UsersController@reset_passsword');
Route::post('/update-profile', 'UsersController@update_profile');
Route::resource('sekolah', 'SekolahController');
Route::resource('komponen', 'KomponenController');
Route::resource('berita', 'BeritaController');
Route::resource('instrumen', 'InstrumenController');
Route::post('/get-kuisioner', 'KuisionerController@proses');
Route::get('/get-kuisioner', 'KuisionerController@proses');
Route::post('/simpan-jawaban', 'KuisionerController@simpan_jawaban');
Route::post('/sinkronisasi', 'HomeController@sinkron');
Route::get('/progress', 'FrontController@progress')->name('api.progres');
Route::get('/smk-coe', 'FrontController@smk_coe')->name('api.smk_coe');
Route::get('/wilayah', 'FrontController@get_wilayah')->name('api.wilayah');
Route::post('/filter-wilayah', 'FrontController@filter_wilayah')->name('api.filter_wilayah');