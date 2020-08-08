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
        $instrumens = Instrumen::orderBy(request()->sortby, request()->sortbydesc)
            //JIKA Q ATAU PARAMETER PENCARIAN INI TIDAK KOSONG
            ->when(request()->q, function($instrumens) {
                //MAKA FUNGSI FILTER AKAN DIJALANKAN
                $instrumens = $instrumens->where('title', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('author', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('category', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $instrumens]);
    }
    public function destroy($id)
    {
        $instrumen = Instrumen::find($id);
        $instrumen->delete();
        return response()->json(['status' => 'success']);
    }
}
