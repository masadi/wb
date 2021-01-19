<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Aspek;
use App\Komponen;
use App\Nilai_aspek;
use App\Nilai_komponen;
use App\Nilai_akhir;
use App\HelperModel;
class NilaiController extends Controller
{
    public function hitung_nilai(Request $request){
        $callback = function($query) use ($request){
            $query->where('user_id', $request->user_id);
            $query->whereNull('verifikator_id');
        };
        $all_komponen = Komponen::with(['aspek.jawaban' => $callback,'aspek.instrumen' => function($query) use ($callback){
            $query->whereNull('ins_id');
        }])->get();
        $total_nilai = 0;
        foreach($all_komponen as $komponen){
            $all_nilai_aspek = 0;
            $instrumen_id = $komponen->aspek->map(function($aspek) use ($request, &$all_nilai_aspek){
                $instrumen_id = $aspek->jawaban()->where(function($query) use ($request){
                    $query->where('user_id', $request->user_id);
                    $query->whereNull('verifikator_id');
                })->select('instrumen_id')->get()->keyBy('instrumen_id')->keys()->all();
                $nilai = $aspek->jawaban()->where(function($query) use ($request){
                    $query->where('user_id', $request->user_id);
                    $query->whereNull('verifikator_id');
                })->sum('nilai');
                if($nilai){
                    $skor = $aspek->instrumen()->whereIn('instrumen_id', $instrumen_id)->sum('skor');
                    $nilai_aspek_dibobot = $nilai * $aspek->bobot / $skor;
                    $nilai_aspek = $nilai * 100 / $skor;
                    $nilai_aspek = number_format($nilai_aspek,2,'.','.');
                    Nilai_aspek::updateOrCreate(
                        [
                            'user_id' => $request->user_id,
                            'aspek_id' => $aspek->id,
                            'komponen_id' => $aspek->komponen_id,
                            'verifikator_id' => NULL,
                        ],
                        [
                            'nilai' => $nilai_aspek_dibobot,
                            'total_nilai' => $nilai_aspek,
                            'predikat' => HelperModel::predikat($nilai_aspek, true),
                        ]
                    );
                    Nilai_aspek::updateOrCreate(
                        [
                            'user_id' => $request->user_id,
                            'aspek_id' => $aspek->id,
                            'komponen_id' => $aspek->komponen_id,
                            'verifikator_id' => $request->verifikator_id,
                        ],
                        [
                            'nilai' => $nilai_aspek_dibobot,
                            'total_nilai' => $nilai_aspek,
                            'predikat' => HelperModel::predikat($nilai_aspek, true),
                        ]
                    );
                }
            });
            //$all_nilai_aspek = Nilai_aspek::where('user_id', $request->user_id)->where('komponen_id', $komponen->id)->sum('nilai');
            $all_nilai_aspek = Nilai_aspek::where(function($query) use ($request, $komponen){
				$query->where('user_id', $request->user_id);
				$query->whereNull('verifikator_id');
				$query->where('komponen_id', $komponen->id);
			})->sum('nilai');
            if($all_nilai_aspek){
                $all_bobot = $komponen->aspek()->sum('bobot');
                $nilai_komponen = ($all_nilai_aspek*100)/$all_bobot;
                $total_nilai_komponen = ($nilai_komponen * $all_bobot) / 100;
                $nilai_komponen = number_format($nilai_komponen,2,'.','.');
                $total_nilai_komponen = number_format($total_nilai_komponen,2,'.','.');
                Nilai_komponen::updateOrCreate(
                    [
                        'user_id' => $request->user_id,
                        'komponen_id' => $komponen->id,
                        'verifikator_id' => NULL,
                    ],
                    [
                        'nilai' => $total_nilai_komponen,
                        'total_nilai' => $nilai_komponen,
                        'predikat' => HelperModel::predikat($nilai_komponen, true),
                    ]
                );
                Nilai_komponen::updateOrCreate(
                    [
                        'user_id' => $request->user_id,
                        'komponen_id' => $komponen->id,
                        'verifikator_id' => $request->verifikator_id,
                    ],
                    [
                        'nilai' => $total_nilai_komponen,
                        'total_nilai' => $nilai_komponen,
                        'predikat' => HelperModel::predikat($nilai_komponen, true),
                    ]
                );
            }
            $total_nilai += $total_nilai_komponen;
        }
        Nilai_akhir::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'verifikator_id' => NULL,
            ],
            [
                'nilai' => $total_nilai,
                'predikat' => HelperModel::predikat($total_nilai, true),
                'peringkat' => HelperModel::peringkat($total_nilai),
            ]
        );
        Nilai_akhir::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'verifikator_id' => $request->verifikator_id,
            ],
            [
                'nilai' => $total_nilai,
                'predikat' => HelperModel::predikat($total_nilai, true),
                'peringkat' => HelperModel::peringkat($total_nilai),
            ]
        );
        return response()->json(['status' => 'success', 'data' => '']);
    }
}
