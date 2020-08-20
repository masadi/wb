<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Komponen;
use App\Aspek;
use App\Instrumen;
class VerifikasiController extends Controller
{
    public function index(Request $request, $query){
        $function = 'get_'.str_replace('-', '_', $query);
        return $this->{$function}($request);
    }
    public function get_komponen(Request $request){
        $all_data = Komponen::get();
		if($all_data->count()){
			foreach($all_data as $data){
				$record= [];
				$record['value'] 	= $data->id;
                $record['text'] 	= $data->nama;
                $output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan data komponen';
			$output['result'][] = $record;
        }
        return response()->json($output);
    }
    public function get_aspek(Request $request){
        $all_data = Aspek::where('komponen_id', $request->komponen_id)->get();
        if($all_data->count()){
			foreach($all_data as $data){
				$record= [];
				$record['value'] 	= $data->id;
                $record['text'] 	= $data->nama;
                $output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan data aspek';
			$output['result'][] = $record;
		}
		return response()->json($output);
    }
    public function get_instrumen(Request $request){
        $all_data = Instrumen::where(function($query) use ($request){
            $query->where('urut', 0);
            $query->whereHas('indikator', function($query) use ($request){
                $query->whereHas('atribut', function($query) use ($request){
                    $query->whereHas('aspek', function($query) use ($request){
                        $query->where('aspek_id', $request->aspek_id);
                    });
                });
            });
        })->get();
        if($all_data->count()){
			foreach($all_data as $data){
				$record= [];
				$record['value'] 	= $data->instrumen_id;
                $record['text'] 	= $data->pertanyaan;
                $output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan data instrumen';
			$output['result'][] = $record;
		}
		return response()->json($output);
    }
    public function get_subs(Request $request){
        return Instrumen::with(['nilai_instrumen' => function($query) use ($request){
            $query->whereHas('user', function($query) use ($request){
                $query->whereHas('sekolah', function($query) use ($request){
                    $query->where('sekolah_id', $request->sekolah_id);
                });
            });
        }, 'subs'])->find($request->instrumen_id);
    }
}
