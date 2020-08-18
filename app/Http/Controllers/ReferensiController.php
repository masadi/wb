<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Komponen;
use App\Aspek;
use App\Atribut;
use App\Indikator;
use App\Instrumen;
use Carbon\Carbon;
use File;
use Validator;
class ReferensiController extends Controller
{
    public function index(Request $request, $query){
        $function = 'get_'.str_replace('-', '_', $query);
        return $this->{$function}($request);
    }
    public function get_komponen($request)
    {
        $all_data = Komponen::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($berita) {
                $all_data = $all_data->where('nama', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_aspek($request)
    {
        $all_data = Aspek::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($berita) {
                $all_data = $all_data->where('nama', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_atribut($request)
    {
        $all_data = Atribut::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($berita) {
                $all_data = $all_data->where('nama', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
    public function get_indikator($request)
    {
        $all_data = Indikator::orderBy(request()->sortby, request()->sortbydesc)
            ->when(request()->q, function($berita) {
                $all_data = $all_data->where('nama', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $all_data]);
    }
}
