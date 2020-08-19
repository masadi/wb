<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Komponen;
use App\Instrumen;
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
            }]);
        }])->get();
        $output_aspek = $komponen->pluck('aspek')->flatten();
        $output_atribut = $output_aspek->pluck('atribut')->flatten();
        $output_indikator = $output_atribut->pluck('indikator')->flatten();
        $output_instrumen = $output_indikator->pluck('instrumen')->flatten();
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
        dd($output_aspek->all());*/
        $instrumen = Instrumen::with(['nilai_instrumen' => function($query) use ($request){
            $query->where('user_id', $request->user_id);
        }, 'indikator.atribut.aspek.komponen'])->where('urut', 0)->get();
        return response()->json(['status' => 'success', 'data' => $komponen, 'output_indikator' => $output_indikator]);
    }
}
