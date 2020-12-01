<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pendamping;
use App\Sekolah;
class LaporanController extends Controller
{
    public function index(Request $request){
        $data_pendamping = Pendamping::has('sekolah_sasaran')->get();
        return view('laporan.index', compact('data_pendamping'));
    }
    public function validasi_token(Request $request){
        $pendamping = Pendamping::whereRaw("lower(token) = '".strtolower($request->token)."'")->first();
        $sekolah = NULL;
        if($pendamping){
            $sekolah = Sekolah::whereHas('sekolah_sasaran', function($query) use ($pendamping){
                $query->where('pendamping_id', $pendamping->pendamping_id);
                $query->whereHas('pakta_integritas', function($query){
                    $query->where('terkirim', 1);
                });
            })->get();
        }
        $token = $request->token;//str_repeat('*', strlen($request->token));
        return response()->json([
            'body' => view('laporan.sekolah', compact('sekolah', 'pendamping'))->render(),
            'token' => $token,
        ]);
    }
    public function get_sekolah(Request $request){
        $pendamping = Pendamping::find($request->pendamping_id);
        $sekolah = Sekolah::find($request->sekolah_id);
        return response()->json([
            'body' => view('laporan.form', compact('sekolah', 'pendamping'))->render(),
        ]);
    }
    public function simpan(Request $request){
        
    }
}
