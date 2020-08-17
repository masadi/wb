<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aspek;
use App\Komponen;
use App\Nilai_aspek;
use App\Nilai_komponen;
use App\Nilai_akhir;
class NilaiController extends Controller
{
    public function hitung_nilai(Request $request){
        $callback = function($query) use ($request){
            $query->where('user_id', $request->user_id);
        };
        $all_komponen = Komponen::with(['aspek.jawaban' => $callback,'aspek.instrumen' => function($query) use ($callback){
            $query->whereNull('ins_id');
        }])->get();
        $total_nilai = 0;
        foreach($all_komponen as $komponen){
            $instrumen_id = $komponen->aspek->map(function($aspek) use ($request){
                $instrumen_id = $aspek->jawaban()->select('instrumen_id')->get()->keyBy('instrumen_id')->keys()->all();
                $nilai = $aspek->jawaban()->sum('nilai');
                $skor = $aspek->instrumen()->whereIn('instrumen_id', $instrumen_id)->sum('skor');
                $nilai_aspek = ($nilai) ? ($nilai * $aspek->bobot) / $skor : 0;
                Nilai_aspek::updateOrCreate(
                    [
                        'user_id' => $request->user_id,
                        'aspek_id' => $aspek->id,
                        'komponen_id' => $aspek->komponen_id,
                    ],
                    [
                        'nilai' => $nilai,
                        'total_nilai' => $nilai_aspek,
                    ]
                );
            });
            $all_nilai_aspek = Nilai_aspek::where('user_id', $request->user_id)->where('komponen_id', $komponen->id)->sum('total_nilai');
            $all_bobot = $komponen->aspek()->sum('bobot');
            $nilai_komponen = ($all_nilai_aspek) ? ($all_nilai_aspek * 100) / $all_bobot : 0;
            Nilai_komponen::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'komponen_id' => $komponen->id,
                ],
                [
                    'nilai' => $all_nilai_aspek,
                    'total_nilai' => $nilai_komponen,
                ]
            );
            $total_nilai += $all_nilai_aspek;
        }
        Nilai_akhir::updateOrCreate(
            [
                'user_id' => $request->user_id,
            ],
            [
                'nilai' => $total_nilai,
            ]
        );
        return response()->json(['status' => 'success', 'data' => '']);
    }
}
