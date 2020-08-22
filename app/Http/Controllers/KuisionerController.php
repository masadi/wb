<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Komponen;
use App\Aspek;
use App\Instrumen;
use App\Jawaban;
use App\Nilai_aspek;
use App\Nilai_instrumen;
use App\User;
use App\HelperModel;
class KuisionerController extends Controller
{
    public function index(Request $request){
        return Komponen::withCount(['jawaban' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
            $query->whereNull('verifikator_id');
        }, 'indikator'])->with(['aspek'])->get();
    }
    public function proses(Request $request){
        $komponen = Komponen::find($request->komponen_id);
        $callback_jawaban = function($query) use ($request){
            $query->where('user_id', $request->user_id);
            $query->whereNull('verifikator_id');
        };
        $previous = Komponen::where('id', '<', $request->komponen_id)->max('id');
        $next = Komponen::where('id', '>', $request->komponen_id)->min('id');
        $aspek = Aspek::where('komponen_id', $request->komponen_id)->paginate(1);
        foreach($aspek as $a){
            $callback = function($query) use ($request, $a){
                $query->where('id', $a->id);
            };
            $instrumens = Instrumen::where('urut', 0)->whereHas('indikator.atribut.aspek', $callback)->with(['jawaban' => $callback_jawaban, 'subs', 'indikator.atribut.aspek' => $callback])->get();
            $output = [];
            foreach($instrumens as $instrumen){
                $output[$instrumen->indikator->atribut->aspek->nama][] = $instrumen;
            }
        }
        $pakta_integritas = User::with(['sekolah.sekolah_sasaran.pakta_integritas'])->find($request->user_id);
        //Pakta_integritas::where('user_id', $request->user_id)->first();
        return response()->json(['status' => 'success', 'data' => $output, 'title' => $komponen->nama, 'aspek' => $aspek, 'previous' => $previous, 'next' => $next, 'pakta_integritas' => ($pakta_integritas) ? TRUE : FALSE, 'user' => $pakta_integritas]);
    }
    public function simpan_jawaban(Request $request){
        if($request->instrumen_id){
            foreach($request->instrumen_id as $instrumen_id => $nilai){
                Jawaban::updateOrCreate(
                    [
                        'user_id' => $request->user_id,
                        'indikator_id' => $request->indikator_id[$instrumen_id],
                        'atribut_id' => $request->atribut_id[$instrumen_id],
                        'aspek_id' => $request->aspek_id[$instrumen_id],
                        'komponen_id' => $request->komponen_id[$instrumen_id],
                        'instrumen_id' => $instrumen_id,
                        'verifikator_id' => NULL,
                    ],
                    [
                        'nilai' => $nilai,
                    ]
                );
                Nilai_instrumen::updateOrCreate(
                    [
                        'user_id' => $request->user_id,
                        'instrumen_id' => $instrumen_id,
                        'verifikator_id' => NULL,
                    ],
                    [
                        'nilai' => $nilai,
                        'predikat' => HelperModel::predikat($nilai),
                    ]
                );
                Jawaban::updateOrCreate(
                    [
                        'user_id' => $request->user_id,
                        'indikator_id' => $request->indikator_id[$instrumen_id],
                        'atribut_id' => $request->atribut_id[$instrumen_id],
                        'aspek_id' => $request->aspek_id[$instrumen_id],
                        'komponen_id' => $request->komponen_id[$instrumen_id],
                        'instrumen_id' => $instrumen_id,
                        'verifikator_id' => $request->verifikator_id,
                    ],
                    [
                        'nilai' => $nilai,
                    ]
                );
                Nilai_instrumen::updateOrCreate(
                    [
                        'user_id' => $request->user_id,
                        'instrumen_id' => $instrumen_id,
                        'verifikator_id' => $request->verifikator_id,
                    ],
                    [
                        'nilai' => $nilai,
                        'predikat' => HelperModel::predikat($nilai),
                    ]
                );
            }
        }
    }
    public function progres(Request $request){
        $komponen = Komponen::with('aspek')->find($request->komponen_id);
        $callback = function($query) use ($request){
            $query->where('id', $request->komponen_id);
        };
        $callback_jawaban = function($query) use ($request){
            $query->where('user_id', $request->user_id);
            $query->whereNull('verifikator_id');
        };
        $instrumens = Instrumen::where('urut', 0)->whereHas('indikator.atribut.aspek.komponen', $callback)->with(['aspek.nilai_aspek' => $callback_jawaban])->withCount(['jawaban' => $callback_jawaban])->get();
        $output = [];
        $output_aspek = [];
        $output_nilai = Nilai_aspek::whereHas('aspek', function($query) use ($request){
            $query->where('komponen_id', $request->komponen_id);
            $query->where('user_id', $request->user_id);
            $query->whereNull('verifikator_id');
        })->with(['aspek'])->get();
        
        foreach($instrumens as $instrumen){
            $output[$instrumen->indikator->atribut->aspek->nama][] = $instrumen;
            //$output_aspek[$instrumen->indikator->atribut->aspek->nama] = $i++;//$instrumen->indikator->atribut->aspek->id;
            //$output[$instrumen->indikator->atribut->aspek->nama][$instrumen->indikator->atribut->aspek->id][] = $instrumen;
        }
        $i=1;
        foreach($komponen->aspek as $aspek){
            $output_aspek[$aspek->nama] = $i++;
        }
        return response()->json(['status' => 'success', 'data' => $output, 'output_aspek' => $output_aspek, 'nilai' => $output_nilai]);
    }
}
