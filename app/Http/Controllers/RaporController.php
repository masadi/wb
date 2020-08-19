<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Komponen;
use App\Instrumen;
use App\HelperModel;
use App\Nilai_akhir;
use App\Pakta_integritas;
use App\Verval;
use App\Verifikasi;
class RaporController extends Controller
{
    public function index(Request $request){
        $komponen = Komponen::with(['nilai_komponen' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
        }, 'aspek' => function($query) use ($request){
            $query->with(['atribut' => function($query) use ($request){
                $query->with(['indikator' => function($query) use ($request){
                    $query->with(['atribut.aspek.komponen', 'instrumen' => function($query) use ($request){
                        $query->where('urut', 0);
                    }]);
                }]);
            }, 'nilai_aspek' => function($query) use ($request){
                $query->where('user_id', $request->user_id);
            }, 'instrumen' => function($query) use ($request){
                $query->where('urut', 0);
                $query->with(['nilai_instrumen' => function($query) use ($request){
                    $query->where('user_id', $request->user_id);
                }]);
            }]);
        }])->get();
        $user = User::find($request->user_id);
        $output_aspek = $komponen->pluck('aspek')->flatten();
        $output_atribut = $output_aspek->pluck('atribut')->flatten();
        $output_indikator = $output_atribut->pluck('indikator')->flatten();
        $output_instrumen = $output_indikator->pluck('instrumen')->flatten();
        $output_nilai_instrumen = $output_instrumen->pluck('nilai_instrumen')->flatten()->filter();
        $sorted = $output_nilai_instrumen->sortByDesc('updated_at');
        $kuisioner = $sorted->first();
        $hitung = Nilai_akhir::where('user_id', $request->user_id)->first();
        $pakta_integritas = Pakta_integritas::where('user_id', $request->user_id)->first();
        $verval = Verval::where('sekolah_id', $user->sekolah_id)->first();
        $verifikasi = Verifikasi::where('sekolah_id', $user->sekolah_id)->first();
        $respone = [
            'status' => 'success', 
            'data' => $komponen, 
            'rapor' => [
                'jml_instrumen' => $output_instrumen->count(), 
                'jml_jawaban' => $output_nilai_instrumen->count(),
                'kuisioner' => ($kuisioner) ? HelperModel::TanggalIndo($kuisioner->updated_at) : NULL,
                'hitung' => ($hitung) ? HelperModel::TanggalIndo($hitung->updated_at) : NULL,
                'pakta_integritas' => ($pakta_integritas) ? HelperModel::TanggalIndo($pakta_integritas->updated_at) : NULL,
                'verval' => ($verval) ? HelperModel::TanggalIndo($verval->updated_at) : NULL,
                'verifikasi' => ($verifikasi) ? HelperModel::TanggalIndo($verifikasi->created_at) : NULL,
                'pengesahan' => ($verifikasi) ? ($verifikasi->verifikasi) ? HelperModel::TanggalIndo($pengesahan->updated_at) : NULL : NULL,
            ],
        ];
        /*
        $output_aspek = $komponen->pluck('aspek.atribut.indikator.instrumen');
        dd($output_aspek);
        $output_atribut = $output_aspek->pluck('atribut')->flatten();
        $output_indikator = $output_atribut->pluck('indikator')->flatten();
        $output_instrumen = $output_indikator->pluck('instrumen')->flatten();
        $all_output = collect([$komponen, $output_aspek, $output_atribut, $output_indikator, $output_instrumen]);
        dd($all_output->flatten()->toArray());
        $output_aspek = collect($output_aspek);
        $output_aspek = $output_aspek->flatten();
        dd($output_aspek->all());
        $instrumen = Instrumen::with(['nilai_instrumen' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
        }, 'indikator.atribut.aspek.komponen'])->where('urut', 0)->get();*/
        return response()->json($respone);
    }
}
