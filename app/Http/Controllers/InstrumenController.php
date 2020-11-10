<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Instrumen;
class InstrumenController extends Controller
{
    public function index()
    {
        //ORDER BY NYA BERDASARKAN PARAMETER YANG DIKIRIM DARI VUEJS
        //SEHINGGA PENGURUTAN DATANYA SESUAI DENGAN KOLOM YG DIINGINKAN OLEH USER
        $instrumens = Instrumen::where('urut', 0)->with(['subs','indikator.atribut.aspek.komponen'])->orderBy(request()->sortby, request()->sortbydesc)
            //JIKA Q ATAU PARAMETER PENCARIAN INI TIDAK KOSONG
            ->when(request()->q, function($instrumens) {
                //MAKA FUNGSI FILTER AKAN DIJALANKAN
                $instrumens = $instrumens->where('pertanyaan', 'LIKE', '%' . request()->q . '%')
                ->orWhere(function($query){
                    $query->whereHas('indikator', function($query) {
                        $query->where('nama', 'LIKE', '%' . request()->q . '%');
                    });
                })
                ->orWhere(function($query){
                    $query->whereHas('indikator.atribut', function($query) {
                        $query->where('nama', 'LIKE', '%' . request()->q . '%');
                    });
                })
                ->orWhere(function($query){
                    $query->whereHas('indikator.atribut.aspek', function($query) {
                        $query->where('nama', 'LIKE', '%' . request()->q . '%');
                    });
                })
                ->orWhere(function($query){
                    $query->whereHas('indikator.atribut.aspek.komponen', function($query) {
                        $query->where('nama', 'LIKE', '%' . request()->q . '%');
                    });
                });
                    //->orWhere('author', 'LIKE', '%' . request()->q . '%')
                    //->orWhere('category', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $instrumens]);
    }
    public function destroy($id)
    {
        $instrumen = Instrumen::find($id);
        $instrumen->delete();
        return response()->json(['status' => 'success']);
    }
    public function validasi_instrumen(Request $request){
        if ($request->isMethod('post')) {
        } else {
            return view('validasi_instrumen');
        }
    }
    public function validasi_token(Request $request){
        $token = strtolower($request->token);
        $instrumens = NULL;
        $instrumen = NULL;
        if($token == '3l3ktr4&cyber'){
            $instrumens = Instrumen::where('urut', 0)->orderBy('indikator_id')->get();
        }
        $query = 'select';
        return response()->json([
            'body' => view('form_validasi_instrumen', compact('instrumens', 'instrumen', 'query'))->render(),
            'token' => $request->token,
        ]);
    }
    public function cari(Request $request){
        $instrumens = NULL;
        $instrumen = Instrumen::with(['subs','indikator.atribut.aspek.komponen'])->find($request->instrumen_id);
        $query = 'input';
        return response()->json([
            'body' => view('form_validasi_instrumen', compact('instrumens', 'instrumen', 'query'))->render(),
            'token' => $request->token,
        ]);
    }
}
