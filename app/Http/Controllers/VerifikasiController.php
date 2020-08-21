<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Komponen;
use App\Aspek;
use App\Instrumen;
use App\Nilai_instrumen;
use App\Jawaban;
use App\Sekolah;
use App\HelperModel;
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
        $output['user_id'] = $request->user_id;
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
        if($request->verifikator_id){
            $komponen = Komponen::with(['nilai_komponen' => function($query) use ($request){
                $query->whereHas('user', function($query) use ($request){
                    $query->where('sekolah_id', $request->sekolah_id);
                });
                $query->whereNull('verifikator_id');
            }, 'nilai_komponen_verifikasi' => function($query) use ($request){
                $query->whereHas('user', function($query) use ($request){
                    $query->where('sekolah_id', $request->sekolah_id);
                });
                $query->where('verifikator_id', $request->verifikator_id);
            }])->get();
            $respone['data'] = $komponen;
            return response()->json($respone);
        }
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
        return Instrumen::with(['jawaban' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
            $query->where('verifikator_id', $request->verifikator_id);
            /*$query->whereHas('user', function($query) use ($request){
                $query->whereHas('sekolah', function($query) use ($request){
                    $query->where('sekolah_id', $request->sekolah_id);
                });
                $query->where('sekolah_id', $request->sekolah_id);
            });*/
        }, 'subs'])->find($request->instrumen_id);
    }
    public function get_simpan(Request $request){
        $post = $request->except(['komponen', 'aspek', 'instrumen', 'jawaban']);
        $instrumen = Instrumen::with(['indikator.atribut'])->find($request->instrumen_id['value']);
        $save = Nilai_instrumen::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'verifikator_id' => $request->verifikator_id,
                'instrumen_id' => $request->instrumen_id['value'],
            ],
            [
                'nilai' => $request->nilai,
                'predikat' => HelperModel::predikat($request->nilai),
            ]
        );
        if($save){
            Jawaban::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'verifikator_id' => $request->verifikator_id,
                    'komponen_id' => $request->komponen_id['value'],
                    'aspek_id' => $request->aspek_id['value'],
                    'atribut_id' => $instrumen->indikator->atribut_id,
                    'indikator_id' => $instrumen->indikator_id,
                    'instrumen_id' => $request->instrumen_id['value'],
                ],
                [
                    'nilai' => $request->nilai,
                ]
            );
            HelperModel::generate_nilai($request->user_id, $request->verifikator_id);
            $output = [
                'icon' => 'success',
                'title' => 'Jawaban berhasil diperbaharui',
            ];
        } else {
            $output = [
                'icon' => 'error',
                'title' => 'Jawaban gagal diperbaharui',
            ];
        }
        return response()->json($output);
    }
    public function get_sekolah(Request $request){
        $all_data = Sekolah::where(function($query) use ($request){
            $query->whereHas('sekolah_sasaran', function($query) use ($request){
                $query->where('verifikator_id', $request->verifikator_id);
            });
        })->get();
        if($all_data->count()){
			foreach($all_data as $data){
				$record= [];
				$record['value'] 	= $data->sekolah_id;
                $record['text'] 	= $data->nama;
                $output['result'][] = $record;
			}
		} else {
			$record['value'] 	= '';
			$record['text'] 	= 'Tidak ditemukan data sekolah';
			$output['result'][] = $record;
		}
		return response()->json($output);
    }
}
