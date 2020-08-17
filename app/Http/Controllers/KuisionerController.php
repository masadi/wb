<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Komponen;
use App\Aspek;
use App\Instrumen;
use App\Jawaban;
use App\Http\Resources\AspekResource;
class KuisionerController extends Controller
{
    public function index(Request $request){
        return Komponen::withCount(['jawaban' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
        }, 'indikator'])->with(['aspek'])->get();
    }
    public function proses(Request $request){
        $komponen = Komponen::find($request->komponen_id);
        $callback_jawaban = function($query) use ($request){
            $query->where('user_id', $request->user_id);
        };
        $aspek = Aspek::where('komponen_id', $request->komponen_id)->paginate(1);
        foreach($aspek as $a){
            $callback = function($query) use ($request, $a){
                $query->where('id', $a->id);
            };
            $instrumens = Instrumen::where('urut', 0)->whereHas('indikator.atribut.aspek', $callback)->with(['jawaban' => $callback_jawaban, 'subs', 'indikator.atribut.aspek' => $callback])->get();
            $output = [];
            foreach($instrumens as $instrumen){
                $output[$instrumen->indikator->atribut->aspek->nama][] = $instrumen;
                //$output['aspek']['instrumen'][] = $instrumen->indikator->atribut->aspek;
            }
        }
        /*
        $instrumens = Instrumen::where('urut', 0)->whereHas('indikator.atribut.aspek.komponen', $callback)->with(['jawaban' => $callback_jawaban, 'subs', 'indikator.atribut.aspek.komponen' => $callback])->get();
        $output = [];
        foreach($instrumens as $instrumen){
            $output[$instrumen->indikator->atribut->aspek->nama][] = $instrumen;
            //$output['aspek']['instrumen'][] = $instrumen->indikator->atribut->aspek;
        }
        */
        return response()->json(['status' => 'success', 'data' => $output, 'title' => $komponen->nama, 'aspek' => $aspek]);
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
                    ],
                    [
                        'nilai' => $nilai,
                    ]
                );
            }
        }
    }
}
