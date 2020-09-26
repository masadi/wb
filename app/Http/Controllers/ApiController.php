<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function login_api(Request $request){
        $input = $request->all();
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        //MELAKUKAN PROSES OTENTIKASI
        if(auth()->attempt(array($fieldType => $input['email'], 'password' => $input['password']))){
            //APABILA BERHASIL, GENERATE API_TOKEN MENGGUNAKAN STRING RANDOM
            //auth()->user()->update(['api_token' => Str::random(40)]);
            //KEMUDIAN KIRIM RESPONSENYA KE CLIENT UNTUK DIPROSES LEBIH LANJUT
            return response()->json(['status' => 'success', 'data' => ['user_id' => auth()->user()->user_id]], 200);
        }
        //APABILA GAGAL, KIRIM RESPONSE LAGI KE BAHWA PERMINTAAN GAGAL
        return response()->json(['status' => 'failed']);
    }
}
